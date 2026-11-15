<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\DivisionModel;

class PengurusController extends BaseController
{
    protected $userModel;
    protected $divisionModel;

    public function __construct()
    {
        $this->userModel     = new UserModel();
        $this->divisionModel = new DivisionModel();
    }

    public function index()
    {
        $role       = $this->request->getGet('role');
        $divisionId = $this->request->getGet('division_id') ? (int) $this->request->getGet('division_id') : null;
        $status     = $this->request->getGet('status');
        $search     = $this->request->getGet('q');

        $builder = $this->userModel->select('hmt_users.*, hmt_divisions.name as division_name, hmt_divisions.color as division_color')
                                   ->join('hmt_divisions', 'hmt_divisions.id = hmt_users.division_id', 'left')
                                   ->where('hmt_users.deleted_at', null);

        if (!empty($role)) {
            $builder->where('hmt_users.role', $role);
        }

        if (!empty($divisionId)) {
            $builder->where('hmt_users.division_id', $divisionId);
        }

        if ($status !== null && $status !== '') {
            $builder->where('hmt_users.is_active', (int) $status);
        }

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('hmt_users.name', $search)
                    ->orLike('hmt_users.nim', $search)
                    ->orLike('hmt_users.email', $search)
                    ->groupEnd();
        }

        $users     = $builder->orderBy('hmt_users.role', 'ASC')
                             ->orderBy('hmt_users.division_id', 'ASC')
                             ->orderBy('hmt_users.name', 'ASC')
                             ->findAll();
        $divisions = $this->divisionModel->where('deleted_at', null)->orderBy('name', 'ASC')->findAll();

        // Calculate statistics
        $allUsers = $this->userModel->where('deleted_at', null)->findAll();
        $stats = [
            'total'        => count($allUsers),
            'admin_count'  => count(array_filter($allUsers, fn($u) => $u['role'] === 'admin')),
            'kadiv_count'  => count(array_filter($allUsers, fn($u) => $u['role'] === 'kadiv')),
            'staff_count'  => count(array_filter($allUsers, fn($u) => $u['role'] === 'staff')),
            'active_count' => count(array_filter($allUsers, fn($u) => (int)$u['is_active'] === 1)),
        ];

        $data = [
            'title'            => 'Kelola Pengurus & Role — HIMATIKA Hub',
            'users'            => $users,
            'divisions'        => $divisions,
            'stats'            => $stats,
            'selectedRole'     => $role,
            'selectedDivision' => $divisionId,
            'selectedStatus'   => $status,
            'searchQuery'      => $search,
        ];

        return view('dashboard/pengurus/index', $data);
    }

    public function store()
    {
        $rules = [
            'name'        => 'required|min_length[3]|max_length[100]',
            'nim'         => 'required|min_length[4]|max_length[20]|is_unique[hmt_users.nim]',
            'email'       => 'required|valid_email|is_unique[hmt_users.email]',
            'password'    => 'required|min_length[6]',
            'role'        => 'required|in_list[admin,kadiv,staff]',
            'division_id' => 'permit_empty|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $divisionId = $this->request->getPost('division_id');

        $data = [
            'name'        => trim($this->request->getPost('name')),
            'nim'         => trim($this->request->getPost('nim')),
            'email'       => strtolower(trim($this->request->getPost('email'))),
            'password'    => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'        => $this->request->getPost('role'),
            'division_id' => !empty($divisionId) ? (int)$divisionId : null,
            'is_active'   => (int) ($this->request->getPost('is_active') ?? 1),
        ];

        $this->userModel->insert($data);

        return redirect()->to(site_url('dashboard/pengurus'))->with('success', 'Akun pengurus baru berhasil ditambahkan!');
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to(site_url('dashboard/pengurus'))->with('error', 'Pengurus tidak ditemukan.');
        }

        $rules = [
            'name'        => 'required|min_length[3]|max_length[100]',
            'nim'         => "required|min_length[4]|max_length[20]|is_unique[hmt_users.nim,id,{$id}]",
            'email'       => "required|valid_email|is_unique[hmt_users.email,id,{$id}]",
            'role'        => 'required|in_list[admin,kadiv,staff]',
            'division_id' => 'permit_empty|is_natural_no_zero',
            'password'    => 'permit_empty|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $divisionId = $this->request->getPost('division_id');
        $newPassword = $this->request->getPost('password');

        $data = [
            'name'        => trim($this->request->getPost('name')),
            'nim'         => trim($this->request->getPost('nim')),
            'email'       => strtolower(trim($this->request->getPost('email'))),
            'role'        => $this->request->getPost('role'),
            'division_id' => !empty($divisionId) ? (int)$divisionId : null,
            'is_active'   => (int) ($this->request->getPost('is_active') ?? 1),
        ];

        if (!empty($newPassword)) {
            $data['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        $this->userModel->update($id, $data);

        return redirect()->to(site_url('dashboard/pengurus'))->with('success', 'Data pengurus berhasil diperbarui!');
    }

    public function toggleStatus($id)
    {
        $currentUserId = session()->get('user_id');
        if ((int)$id === (int)$currentUserId) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Pengurus tidak ditemukan.');
        }

        $newStatus = $user['is_active'] ? 0 : 1;
        $this->userModel->update($id, ['is_active' => $newStatus]);

        $statusLabel = $newStatus ? 'diaktifkan kembali' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Akun {$user['name']} berhasil {$statusLabel}.");
    }

    public function delete($id)
    {
        $currentUserId = session()->get('user_id');
        if ((int)$id === (int)$currentUserId) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Pengurus tidak ditemukan.');
        }

        $this->userModel->delete($id);

        return redirect()->to(site_url('dashboard/pengurus'))->with('success', "Akun pengurus {$user['name']} berhasil dihapus.");
    }
}
