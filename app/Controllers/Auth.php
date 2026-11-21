<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PasswordResetModel;
use App\Libraries\WhatsAppService;
use App\Libraries\EmailService;

class Auth extends BaseController
{
    protected $userModel;
    protected $resetModel;

    public function __construct()
    {
        $this->userModel  = new UserModel();
        $this->resetModel = new PasswordResetModel();
    }

    public function login()
    {
        // Jika sudah login, langsung arahkan ke dashboard
        if (session()->get('is_logged_in')) {
            return redirect()->to(site_url('dashboard'));
        }

        $data = [
            'title' => 'Login Pengurus — HIMATIKA Hub',
        ];

        return view('auth/login', $data);
    }

    public function attemptLogin()
    {
        $rules = [
            'login_id' => [
                'label'  => 'Email atau NIM',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Email atau NIM wajib diisi.',
                ],
            ],
            'password' => [
                'label'  => 'Kata Sandi',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Kata sandi wajib diisi.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $loginId  = trim($this->request->getPost('login_id'));
        $password = $this->request->getPost('password');

        // Cari user berdasarkan email ATAU NIM
        $user = $this->userModel->groupStart()
                                ->where('email', $loginId)
                                ->orWhere('nim', $loginId)
                                ->groupEnd()
                                ->where('deleted_at', null)
                                ->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Kombinasi Email/NIM dan kata sandi tidak cocok.');
        }

        if (!$user['is_active']) {
            return redirect()->back()->with('error', 'Akun Anda sedang dinonaktifkan. Silakan hubungi Administrator.');
        }

        // Verifikasi Password Hash Bcrypt
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Kombinasi Email/NIM dan kata sandi tidak cocok.');
        }

        // Update Last Login
        $this->userModel->update($user['id'], [
            'last_login' => date('Y-m-d H:i:s'),
        ]);

        // Rotasi Session ID untuk mencegah Session Fixation
        session()->regenerate(true);

        // Simpan Session
        session()->set([
            'user_id'      => (int) $user['id'],
            'user_name'    => $user['name'],
            'user_nim'     => $user['nim'],
            'user_email'   => $user['email'],
            'user_role'    => $user['role'],
            'division_id'  => $user['division_id'] ? (int) $user['division_id'] : null,
            'user_avatar'  => $user['avatar'],
            'is_logged_in' => true,
        ]);

        return redirect()->to(site_url('dashboard'))->with('success', 'Selamat datang kembali, ' . esc($user['name']) . '!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'))->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Halaman Formulir Permintaan Reset Kata Sandi
     */
    public function forgotPassword()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to(site_url('dashboard'));
        }

        $waSettings = WhatsAppService::getSettings();

        $data = [
            'title'           => 'Lupa Kata Sandi — HIMATIKA Hub',
            'whatsappEnabled' => $waSettings['enabled'] && !empty($waSettings['token']),
        ];

        return view('auth/forgot_password', $data);
    }

    /**
     * Kirim Tautan Reset Kata Sandi dengan Token Acak Kriptografi (CSPRNG)
     * HANYA ke nomor WhatsApp (hmt_users.phone) atau Email (hmt_users.email) yang terdaftar di database.
     */
    public function sendResetLink()
    {
        $throttler = \Config\Services::throttler();
        $ipKey     = 'forgot_pw_ip_' . md5((string) $this->request->getIPAddress());

        // Batasi maksimal 5 permintaan reset per 15 menit per IP
        if ($throttler->check($ipKey, 5, 900) === false) {
            return redirect()->back()->withInput()->with('error', 'Terlalu banyak permintaan reset kata sandi. Silakan coba lagi dalam beberapa menit.');
        }

        $rules = [
            'email' => [
                'label'  => 'Email Pengurus',
                'rules'  => 'required|valid_email',
                'errors' => [
                    'required'    => 'Email pengurus wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                ],
            ],
            'nim' => [
                'label'  => 'NIM',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'NIM wajib diisi sebagai verifikasi identitas resmi pengurus.',
                ],
            ],
            'channel' => [
                'label'  => 'Metode Pengiriman',
                'rules'  => 'required|in_list[whatsapp,email]',
                'errors' => [
                    'required' => 'Pilih salah satu metode pengiriman (WhatsApp atau Email).',
                    'in_list'  => 'Metode pengiriman tidak valid.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email   = strtolower(trim((string) $this->request->getPost('email')));
        $nim     = trim((string) $this->request->getPost('nim'));
        $channel = trim((string) $this->request->getPost('channel'));

        // Batasi juga per akun (maks 3 permintaan per 15 menit per email)
        $emailKey = 'forgot_pw_email_' . md5($email);
        if ($throttler->check($emailKey, 3, 900) === false) {
            return redirect()->back()->withInput()->with('error', 'Permintaan reset untuk akun ini terlalu sering. Silakan tunggu beberapa menit sebelum mencoba lagi.');
        }

        // Pastikan skema tabel & kolom phone sudah tersedia
        $this->resetModel->ensureTableExists();

        // Verifikasi kecocokan Email dan NIM di database
        $user = $this->userModel->where('email', $email)
                                ->where('nim', $nim)
                                ->where('deleted_at', null)
                                ->first();

        // Anti-enumerasi: jika akun tidak ditemukan atau nonaktif, kembalikan pesan generik yang sama
        if (!$user || empty($user['is_active'])) {
            return $this->genericResetSentResponse($channel, $email);
        }

        // Kunci tujuan pengiriman HANYA dari data resmi di database (abaikan input nomor dari form)
        if ($channel === 'whatsapp') {
            $registeredPhone = WhatsAppService::sanitizeTarget((string) ($user['phone'] ?? ''));

            // Jangan pernah fallback ke nomor input user ataupun target global!
            if (empty($registeredPhone)) {
                log_message('info', 'Password reset via WA skipped: no registered phone for user ID ' . $user['id']);
                return $this->genericResetSentResponse($channel, $email);
            }

            $expiresMinutes = 15;
            $tokenData      = $this->resetModel->createToken($user['email'], 'whatsapp', $registeredPhone, $expiresMinutes);
            $resetUrl       = site_url('reset-password/' . $tokenData['plain_token']);

            $sendRes = WhatsAppService::sendPasswordResetLink(
                $registeredPhone,
                $user['name'],
                $user['email'],
                $resetUrl,
                $tokenData['plain_code'],
                $expiresMinutes
            );

            if ($sendRes['status'] !== 'success') {
                log_message('warning', 'Password reset WA send failed for user ID ' . $user['id'] . ': ' . ($sendRes['message'] ?? 'unknown'));
                return redirect()->back()->withInput()->with('error', 'Layanan WhatsApp sedang mengalami gangguan pengiriman. Silakan coba menggunakan metode Email Resmi.');
            }

            return $this->genericResetSentResponse('whatsapp', $user['email']);
        }

        // Channel Email: kirim HANYA ke email terdaftar di database
        $expiresMinutes = 15;
        $tokenData      = $this->resetModel->createToken($user['email'], 'email', $user['email'], $expiresMinutes);
        $resetUrl       = site_url('reset-password/' . $tokenData['plain_token']);

        $sendRes = EmailService::sendPasswordResetEmail(
            $user['email'],
            $user['name'],
            $resetUrl,
            $tokenData['plain_code'],
            $expiresMinutes
        );

        if ($sendRes['status'] !== 'success') {
            log_message('warning', 'Password reset Email send failed for user ID ' . $user['id'] . ': ' . ($sendRes['message'] ?? 'unknown'));
            return redirect()->back()->withInput()->with('error', 'Layanan pengiriman email sedang mengalami gangguan. Silakan coba lagi atau gunakan metode WhatsApp jika nomor Anda sudah terdaftar.');
        }

        return $this->genericResetSentResponse('email', $user['email']);
    }

    /**
     * Halaman Input Kata Sandi Baru dengan Token Acak atau Kode 6-Digit + Email
     *
     * @param string|null $token
     */
    public function resetPassword($token = null)
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to(site_url('dashboard'));
        }

        $token = trim((string) ($token ?: $this->request->getGet('token')));
        $email = strtolower(trim((string) $this->request->getGet('email')));

        // Jika tidak ada token pada URL, tampilkan form verifikasi token / kode 6-digit
        if (empty($token)) {
            $data = [
                'title'      => 'Verifikasi Token Reset — HIMATIKA Hub',
                'hasToken'   => false,
                'tokenValid' => false,
                'token'      => '',
                'email'      => $email,
            ];
            return view('auth/reset_password', $data);
        }

        // Rate-limiting verifikasi token/OTP (maks 10 percobaan per 15 menit per IP)
        $throttler = \Config\Services::throttler();
        $verifyKey = 'verify_reset_' . md5((string) $this->request->getIPAddress());
        if ($throttler->check($verifyKey, 10, 900) === false) {
            return redirect()->to(site_url('reset-password'))
                             ->with('error', 'Terlalu banyak percobaan verifikasi token/kode. Silakan coba lagi dalam 15 menit.');
        }

        // Validasi Token atau Kode 6-Digit (+ Email)
        $validation = $this->resetModel->validateToken($token, $email ?: null);

        if (!$validation['valid']) {
            $data = [
                'title'      => 'Tautan Tidak Valid — HIMATIKA Hub',
                'hasToken'   => true,
                'tokenValid' => false,
                'tokenError' => $validation['message'],
                'token'      => $token,
                'email'      => $email,
            ];
            return view('auth/reset_password', $data);
        }

        $record = $validation['record'];
        $user   = $this->userModel->where('email', $record['email'])->where('deleted_at', null)->first();

        $data = [
            'title'            => 'Atur Kata Sandi Baru — HIMATIKA Hub',
            'hasToken'         => true,
            'tokenValid'       => true,
            'token'            => $token,
            'email'            => $record['email'],
            'record'           => $record,
            'user'             => $user,
            'minutesRemaining' => $validation['minutes_remaining'],
            'secondsRemaining' => $validation['seconds_remaining'],
        ];

        return view('auth/reset_password', $data);
    }

    /**
     * Eksekusi Perubahan Kata Sandi Baru Setelah Validasi Token Berhasil
     *
     * @param string|null $token
     */
    public function attemptResetPasswordWithToken($token = null)
    {
        $throttler  = \Config\Services::throttler();
        $attemptKey = 'attempt_reset_' . md5((string) $this->request->getIPAddress());
        if ($throttler->check($attemptKey, 10, 900) === false) {
            return redirect()->to(site_url('reset-password'))
                             ->with('error', 'Terlalu banyak percobaan reset kata sandi. Silakan tunggu 15 menit.');
        }

        $token = trim((string) ($token ?: $this->request->getPost('token')));
        $email = strtolower(trim((string) $this->request->getPost('email')));

        if (empty($token)) {
            return redirect()->to(site_url('reset-password'))->with('error', 'Token reset kata sandi tidak ditemukan.');
        }

        // 1. Validasi Keabsahan dan Masa Berlaku Token / Kode
        $validation = $this->resetModel->validateToken($token, $email ?: null);
        if (!$validation['valid']) {
            $redirectUrl = site_url('reset-password');
            return redirect()->to($redirectUrl)->with('error', $validation['message']);
        }

        // 2. Validasi Input Kata Sandi Baru
        $rules = [
            'new_password' => [
                'label'  => 'Kata Sandi Baru',
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required'   => 'Kata sandi baru wajib diisi.',
                    'min_length' => 'Kata sandi baru minimal 6 karakter.',
                ],
            ],
            'confirm_password' => [
                'label'  => 'Konfirmasi Kata Sandi',
                'rules'  => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Konfirmasi kata sandi wajib diisi.',
                    'matches'  => 'Konfirmasi kata sandi tidak cocok dengan kata sandi baru.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $record      = $validation['record'];
        $newPassword = $this->request->getPost('new_password');

        $user = $this->userModel->where('email', $record['email'])->where('deleted_at', null)->first();
        if (!$user) {
            return redirect()->to(site_url('forgot-password'))->with('error', 'Akun pengurus tidak ditemukan.');
        }

        // 3. Update Password Baru dengan Hash Bcrypt Kuat
        $this->userModel->update($user['id'], [
            'password'   => password_hash($newPassword, PASSWORD_BCRYPT),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // 4. Hanguskan (Consume) Token agar tidak dapat digunakan kembali
        $this->resetModel->consumeToken((int) $record['id'], $user['email']);

        // 5. Kirim Notifikasi Konfirmasi Keamanan HANYA ke nomor terdaftar di database
        $notifyTarget = WhatsAppService::sanitizeTarget((string) ($user['phone'] ?? ''));
        if (!empty($notifyTarget) && $record['channel'] === 'whatsapp') {
            WhatsAppService::sendPasswordChangedNotification($notifyTarget, $user['name'], $user['email']);
        }

        return redirect()->to(site_url('login'))->with('success', 'Kata sandi akun Anda berhasil diperbarui! Silakan masuk menggunakan kata sandi baru.');
    }

    /**
     * Backward-compatibility alias
     */
    public function attemptResetPassword()
    {
        return $this->sendResetLink();
    }

    /**
     * Respons generik anti-enumerasi agar validitas Email/NIM maupun keberadaan nomor WA tidak bocor
     */
    private function genericResetSentResponse(string $channel, string $email)
    {
        $maskedTarget = $channel === 'whatsapp'
            ? 'Nomor WhatsApp terdaftar di profil akun'
            : $this->maskEmail($email);

        session()->setFlashdata('reset_sent', true);
        session()->setFlashdata('reset_channel', $channel);
        session()->setFlashdata('reset_target', $maskedTarget);
        session()->setFlashdata(
            'success',
            'Jika kombinasi Email dan NIM sesuai dengan akun aktif' .
            ($channel === 'whatsapp' ? ' dan nomor WhatsApp sudah terdaftar di profil' : '') .
            ', tautan & kode reset kata sandi telah dikirimkan (berlaku 15 menit).'
        );

        return redirect()->to(site_url('forgot-password'));
    }

    /**
     * Samarkan alamat email (contoh: re***@himatika.org)
     */
    private function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return '***';
        }
        $local  = $parts[0];
        $domain = $parts[1];
        $prefix = substr($local, 0, min(2, strlen($local)));
        return $prefix . str_repeat('*', max(3, strlen($local) - strlen($prefix))) . '@' . $domain;
    }
}
