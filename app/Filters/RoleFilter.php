<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to(site_url('login'))->with('warning', 'Silakan login terlebih dahulu.');
        }

        $userRole = session()->get('user_role');

        if (!empty($arguments)) {
            // $arguments bisa berupa array roles yang diizinkan, misal ['admin', 'kadiv']
            if (!in_array($userRole, $arguments, true)) {
                return redirect()->to(site_url('dashboard'))->with('error', 'Akses ditolak: Anda tidak memiliki hak akses untuk membuka halaman tersebut.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
