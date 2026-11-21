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
            'defaultWaTarget' => $waSettings['target'],
        ];

        return view('auth/forgot_password', $data);
    }

    /**
     * Kirim Tautan Reset Kata Sandi dengan Token Acak Kriptografi (CSPRNG)
     * via WhatsApp Pengurus atau Email Resmi
     */
    public function sendResetLink()
    {
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

        $email   = strtolower(trim($this->request->getPost('email')));
        $nim     = trim($this->request->getPost('nim'));
        $channel = trim($this->request->getPost('channel'));

        // Verifikasi kecocokan Email dan NIM di database
        $user = $this->userModel->where('email', $email)
                                ->where('nim', $nim)
                                ->where('deleted_at', null)
                                ->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Kombinasi Email dan NIM tidak cocok dengan data pengurus manapun. Periksa kembali data Anda atau hubungi BPH.');
        }

        if (!$user['is_active']) {
            return redirect()->back()->with('error', 'Akun Anda sedang dinonaktifkan oleh administrator. Silakan hubungi BPH.');
        }

        $target = $email;
        if ($channel === 'whatsapp') {
            $waInput = trim((string) $this->request->getPost('whatsapp_number'));
            $target  = !empty($waInput) ? $waInput : (!empty($user['phone']) ? $user['phone'] : WhatsAppService::getSettings()['target']);

            if (empty($target)) {
                return redirect()->back()->withInput()->with('error', 'Nomor WhatsApp tujuan wajib diisi untuk menerima tautan reset kata sandi.');
            }
        }

        // 1. Generate Token Acak Kriptografi (32-byte CSPRNG) dengan masa berlaku 15 menit
        $expiresMinutes = 15;
        $tokenData = $this->resetModel->createToken($user['email'], $channel, $target, $expiresMinutes);

        // 2. Buat URL Reset Aman
        $resetUrl = site_url('reset-password/' . $tokenData['plain_token']);

        // 3. Kirim sesuai channel yang dipilih
        if ($channel === 'whatsapp') {
            $sendRes = WhatsAppService::sendPasswordResetLink(
                $target,
                $user['name'],
                $user['email'],
                $resetUrl,
                $tokenData['plain_code'],
                $expiresMinutes
            );

            if ($sendRes['status'] !== 'success') {
                return redirect()->back()->withInput()->with('error', 'Gagal mengirim pesan WhatsApp: ' . ($sendRes['message'] ?? 'Gateway menolak pengiriman.'));
            }

            session()->setFlashdata('reset_sent', true);
            session()->setFlashdata('reset_channel', 'whatsapp');
            session()->setFlashdata('reset_target', $target);
            session()->setFlashdata('reset_email', $user['email']);
            session()->setFlashdata('reset_name', $user['name']);
            session()->setFlashdata('success', 'Tautan & kode reset kata sandi berhasil dikirim ke WhatsApp ' . esc($target) . '! Tautan berlaku selama 15 menit.');

            return redirect()->to(site_url('forgot-password'));
        } else {
            $sendRes = EmailService::sendPasswordResetEmail(
                $user['email'],
                $user['name'],
                $resetUrl,
                $tokenData['plain_code'],
                $expiresMinutes
            );

            if ($sendRes['status'] !== 'success') {
                return redirect()->back()->withInput()->with('error', $sendRes['message'] . ' Disarankan menggunakan opsi kirim via WhatsApp.');
            }

            session()->setFlashdata('reset_sent', true);
            session()->setFlashdata('reset_channel', 'email');
            session()->setFlashdata('reset_target', $user['email']);
            session()->setFlashdata('reset_email', $user['email']);
            session()->setFlashdata('reset_name', $user['name']);
            session()->setFlashdata('success', 'Instruksi reset kata sandi telah dikirim ke email ' . esc($user['email']) . '! Tautan berlaku selama 15 menit.');

            return redirect()->to(site_url('forgot-password'));
        }
    }

    /**
     * Halaman Input Kata Sandi Baru dengan Token Acak
     *
     * @param string|null $token
     */
    public function resetPassword($token = null)
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to(site_url('dashboard'));
        }

        $token = $token ?: $this->request->getGet('token');

        // Jika tidak ada token pada URL, tampilkan form verifikasi token / kode 6-digit
        if (empty($token)) {
            $data = [
                'title'       => 'Verifikasi Token Reset — HIMATIKA Hub',
                'hasToken'    => false,
                'tokenValid'  => false,
                'token'       => '',
            ];
            return view('auth/reset_password', $data);
        }

        // Validasi Token
        $validation = $this->resetModel->validateToken($token);

        if (!$validation['valid']) {
            $data = [
                'title'      => 'Tautan Tidak Valid — HIMATIKA Hub',
                'hasToken'   => true,
                'tokenValid' => false,
                'tokenError' => $validation['message'],
                'token'      => $token,
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
        $token = $token ?: $this->request->getPost('token');

        if (empty($token)) {
            return redirect()->to(site_url('reset-password'))->with('error', 'Token reset kata sandi tidak ditemukan.');
        }

        // 1. Validasi Keabsahan dan Masa Berlaku Token
        $validation = $this->resetModel->validateToken($token);
        if (!$validation['valid']) {
            return redirect()->to(site_url('reset-password/' . rawurlencode($token)))
                             ->with('error', $validation['message']);
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

        // 5. Kirim Notifikasi Konfirmasi Keamanan (jika via WA atau nomor ada)
        $notifyTarget = !empty($record['target']) ? $record['target'] : ($user['phone'] ?? '');
        if (!empty($notifyTarget) && $record['channel'] === 'whatsapp') {
            WhatsAppService::sendPasswordChangedNotification($notifyTarget, $user['name'], $user['email']);
        }

        return redirect()->to(site_url('login'))->with('success', 'Kata sandi akun ' . esc($user['name']) . ' berhasil diperbarui! Silakan masuk menggunakan kata sandi baru Anda.');
    }

    /**
     * Backward-compatibility alias
     */
    public function attemptResetPassword()
    {
        return $this->sendResetLink();
    }
}
