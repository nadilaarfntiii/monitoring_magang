<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\MitraModel;

class Home extends BaseController
{
    protected $userModel;
    protected $mahasiswaModel;
    protected $dosenModel;
    protected $mitraModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->mahasiswaModel = new MahasiswaModel();
        $this->dospemModel = new DosenModel();
        $this->mitraModel = new MitraModel();
    }

    /* DASHBOARD ADMIN */
    public function dashboard()
    {
        $session = session();
        $id_user = $session->get('id_user');

        // Ambil data user dari tabel user
        $user = $this->userModel->find($id_user);

        // Jika tidak ditemukan, fallback "User"
        $data['user_name'] = $user['nama_lengkap'] ?? 'Admin';

        return view('admin/dashboard', $data);
    }

    public function dashboard_mahasiswa()
    {
        $session = session();
        $role = $session->get('role');
        $id_user = $session->get('id_user');

        if ($role != 'mahasiswa') {
            return redirect()->to('/login');
        }

        // Ambil data user
        $user = $this->userModel->find($id_user);
        $nim = $user['nim'] ?? '';

        // Ambil data mahasiswa (termasuk foto)
        $mhs = $this->mahasiswaModel->where('nim', $nim)->first();

        $foto = 'pp.jpg'; // default
        if (!empty($mhs['foto']) && file_exists(FCPATH . 'uploads/foto/' . $mhs['foto'])) {
            $foto = $mhs['foto'];
        }

        // Siapkan data untuk view
        $data = [
            'user_name' => $mhs['nama_lengkap'] ?? 'Mahasiswa',
            'foto' => $foto,
            'role_name' => 'Mahasiswa',
            'isDefaultPassword' => $session->get('isDefaultPassword')
        ];
        
        return view('mahasiswa/dashboard', $data);
    }


}
