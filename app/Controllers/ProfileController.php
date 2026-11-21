<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\DivisionModel;
use App\Models\PasswordResetModel;

class ProfileController extends BaseController
{
    protected $userModel;
    protected $divisionModel;

    public function __construct()
    {
        $this->userModel     = new UserModel();
        $this->divisionModel = new DivisionModel();
    }

    /**
     * Tampilkan halaman Profil Saya & Ganti Kata Sandi
     */
    public function index()
    {
        $userId = session()->get('user_id');

        // Pastikan kolom phone tersedia di tabel hmt_users
        (new PasswordResetModel())->ensureTableExists();

        $user = $this->userModel->select('hmt_users.*, hmt_divisions.name as division_name, hmt_divisions.color as division_color')
                                ->join('hmt_divisions', 'hmt_divisions.id = hmt_users.division_id', 'left')
                                ->where('hmt_users.id', $userId)
                                ->first();

        if (!$user) {
            return redirect()->to(site_url('login'))->with('error', 'Sesi tidak valid, silakan login kembali.');
        }

        $data = [
            'title' => 'Profil Saya & Ganti Password — HIMATIKA Hub',
            'user'  => $user,
        ];

        return view('dashboard/profile/index', $data);
    }

    /**
     * Proses Ubah Kata Sandi (Password)
     */
    public function updatePassword()
    {
        $userId = session()->get('user_id');
        $user   = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to(site_url('login'))->with('error', 'Sesi login telah berakhir.');
        }

        $rules = [
            'current_password' => [
                'label'  => 'Kata Sandi Saat Ini',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Kata sandi saat ini wajib diisi.',
                ],
            ],
            'new_password' => [
                'label'  => 'Kata Sandi Baru',
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required'   => 'Kata sandi baru wajib diisi.',
                    'min_length' => 'Kata sandi baru minimal memiliki 6 karakter.',
                ],
            ],
            'confirm_password' => [
                'label'  => 'Ulangi Kata Sandi Baru',
                'rules'  => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Konfirmasi kata sandi baru wajib diisi.',
                    'matches'  => 'Konfirmasi kata sandi baru tidak sesuai dengan kata sandi baru.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword     = $this->request->getPost('new_password');

        // Verifikasi kata sandi lama
        if (!password_verify($currentPassword, $user['password'])) {
            return redirect()->back()->with('error', 'Kata sandi saat ini yang Anda masukkan salah. Silakan coba kembali.');
        }

        // Cegah penggunaan kata sandi yang sama persis
        if ($currentPassword === $newPassword) {
            return redirect()->back()->with('error', 'Kata sandi baru tidak boleh sama dengan kata sandi saat ini.');
        }

        // Update password baru ke database
        $this->userModel->update($userId, [
            'password'   => password_hash($newPassword, PASSWORD_BCRYPT),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('dashboard/profile'))->with('success', 'Kata sandi berhasil diperbarui! Silakan gunakan kata sandi baru ini pada login berikutnya.');
    }

    /**
     * Proses Perbarui Informasi Profil Dasar (Nama, Email, & Nomor WhatsApp)
     */
    public function updateProfile()
    {
        $userId = session()->get('user_id');
        (new PasswordResetModel())->ensureTableExists();
        $user   = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to(site_url('login'))->with('error', 'Sesi login telah berakhir.');
        }

        $rules = [
            'name' => [
                'label'  => 'Nama Lengkap',
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama lengkap wajib diisi.',
                ],
            ],
            'email' => [
                'label'  => 'Email',
                'rules'  => "required|valid_email|is_unique[hmt_users.email,id,{$userId}]",
                'errors' => [
                    'required'    => 'Email wajib diisi.',
                    'valid_email' => 'Format alamat email tidak valid.',
                    'is_unique'   => 'Email ini sudah digunakan oleh akun lain.',
                ],
            ],
            'phone' => [
                'label'  => 'Nomor WhatsApp',
                'rules'  => 'permit_empty|regex_match[/^[0-9+\-\s]{8,20}$/]',
                'errors' => [
                    'regex_match' => 'Format nomor WhatsApp tidak valid (gunakan 8–20 digit angka, contoh: 081234567890).',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name       = trim((string) $this->request->getPost('name'));
        $email      = strtolower(trim((string) $this->request->getPost('email')));
        $rawPhone   = trim((string) $this->request->getPost('phone'));
        $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);

        $this->userModel->update($userId, [
            'name'       => $name,
            'email'      => $email,
            'phone'      => !empty($cleanPhone) ? $cleanPhone : null,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Perbarui data nama & email di sesi aktif
        session()->set([
            'user_name'  => $name,
            'user_email' => $email,
        ]);

        return redirect()->to(site_url('dashboard/profile'))->with('success', 'Informasi profil & nomor WhatsApp Anda berhasil diperbarui!');
    }
}
