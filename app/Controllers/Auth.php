<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
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
            'login_id' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $loginId  = trim($this->request->getPost('login_id'));
        $password = $this->request->getPost('password');

        // Cari berdasarkan Email, NIM, atau alias admin
        $builder = $this->userModel->groupStart()
                                   ->where('email', $loginId)
                                   ->orWhere('nim', $loginId);

        if (in_array(strtolower($loginId), ['admin', 'superadmin', 'ketua'], true)) {
            $builder->orWhere('email', 'rabiah@himatika.org')
                    ->orWhere('role', 'admin');
        }

        $user = $builder->groupEnd()->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Akun tidak ditemukan. Periksa kembali Email atau NIM Anda.');
        }

        if (!$user['is_active']) {
            return redirect()->back()->with('error', 'Akun Anda sedang dinonaktifkan oleh administrator.');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Kata sandi tidak sesuai.');
        }

        // Ambil info divisi jika ada
        $division = null;
        if (!empty($user['division_id'])) {
            $division = $this->userModel->db->table('hmt_divisions')->where('id', $user['division_id'])->get()->getRowArray();
        }

        // Simpan Session Pengurus
        session()->set([
            'is_logged_in'        => true,
            'user_id'             => $user['id'],
            'user_name'           => $user['name'],
            'user_nim'            => $user['nim'],
            'user_email'          => $user['email'],
            'user_role'           => $user['role'], // 'admin', 'kadiv', 'staff'
            'user_division_id'    => $user['division_id'],
            'user_division_name'  => $division['name'] ?? 'Pusat / Umum',
            'user_division_color' => $division['color'] ?? '#3b82f6',
        ]);

        // Perbarui timestamp last_login
        $this->userModel->update($user['id'], [
            'last_login' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('dashboard'))->with('success', 'Selamat datang kembali, ' . esc($user['name']) . '!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'))->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Halaman Formulir Lupa Kata Sandi
     */
    public function forgotPassword()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to(site_url('dashboard'));
        }

        $data = [
            'title' => 'Lupa Kata Sandi — HIMATIKA Hub',
        ];

        return view('auth/forgot_password', $data);
    }

    /**
     * Proses Verifikasi Identitas & Reset Kata Sandi Baru
     */
    public function attemptResetPassword()
    {
        $rules = [
            'email' => [
                'label'  => 'Email Akun',
                'rules'  => 'required|valid_email',
                'errors' => [
                    'required'    => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                ],
            ],
            'nim' => [
                'label'  => 'NIM',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'NIM wajib diisi untuk verifikasi identitas pengurus.',
                ],
            ],
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

        $email       = strtolower(trim($this->request->getPost('email')));
        $nim         = trim($this->request->getPost('nim'));
        $newPassword = $this->request->getPost('new_password');

        $user = $this->userModel->where('email', $email)
                                ->where('nim', $nim)
                                ->where('deleted_at', null)
                                ->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Kombinasi Email dan NIM tidak cocok dengan data pengurus manapun. Periksa kembali atau hubungi BPH.');
        }

        if (!$user['is_active']) {
            return redirect()->back()->with('error', 'Akun Anda sedang dinonaktifkan oleh administrator. Silakan hubungi BPH.');
        }

        // Update password baru ke database
        $this->userModel->update($user['id'], [
            'password'   => password_hash($newPassword, PASSWORD_BCRYPT),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('login'))->with('success', 'Kata sandi akun ' . esc($user['name']) . ' berhasil direset! Silakan login menggunakan kata sandi baru Anda.');
    }
}
