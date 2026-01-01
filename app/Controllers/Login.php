<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Login extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    // Tampilkan halaman login
    public function index()
    {
        // Jika sudah login, redirect sesuai role
        if ($this->session->get('isLoggedIn')) {
            return $this->redirectToDashboard($this->session->get('role'));
        }

        return view('login'); // file login.html atau login.php
    }

    public function auth()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('pass');
    
        if (empty($username) || empty($password)) {
            return redirect()->back()->with('error', 'Username dan Password wajib diisi.');
        }
    
        $user = $this->userModel->where('username', $username)->first();
    
        if (!$user || !password_verify($password, $user['password']) || $user['status'] !== 'aktif') {
            return redirect()->back()->with('error', 'Username atau Password salah.');
        }
    
        // 🔹 Siapkan variabel tambahan
        $nim = null;
        $id_unit = null;
    
        if ($user['role'] === 'mahasiswa') {
            $nim = $user['nim'] ?? null;
        }
    
        if ($user['role'] === 'mitra') {
            $id_unit = $user['id_unit'] ?? null;
        }
    
        // 🔹 Set session
        $this->session->set([
            'isLoggedIn' => true,
            'id_user'    => $user['id_user'],
            'username'   => $user['username'],
            'role'       => $user['role'],
            'nim'        => $nim,
            'id_unit'    => $id_unit,
            'foto'       => !empty($user['foto']) ? $user['foto'] : 'pp.jpg',
            'user_name'  => $user['nama_lengkap'] ?? $user['username']
        ]);
    
        // 🔹 Cek password default sesuai buatAkunDefault()
        $defaultPassword = null;
    
        switch ($user['role']) {
            case 'mahasiswa':
                $defaultPassword = $user['nim'] ?? null; // NIM sebagai default
                break;
            case 'dospem':
            case 'kaprodi':
            case 'mitra':
                $defaultPassword = '12345678';
                break;
        }
    
        $isDefault = $defaultPassword ? password_verify($defaultPassword, $user['password']) : false;
    
        $this->session->set('isDefaultPassword', $isDefault);

        // Jika role kaprodi → ambil jabatan_fungsional & nppy dari tabel dosen
        if ($user['role'] === 'kaprodi') {
            $dosenModel = new \App\Models\DosenModel();

            // Ambil data dosen berdasarkan nppy di tabel user
            $dosen = $dosenModel->where('nppy', $user['nppy'])->first();

            if ($dosen) {
                $this->session->set([
                    'nppy' => $dosen['nppy'],
                    'jabatan_fungsional' => $dosen['jabatan_fungsional']
                ]);
            } else {
                return redirect()->back()->with('error', 'Data Kaprodi tidak ditemukan.');
            }
        }

        // 🔹 Ambil nama lengkap sesuai role
        $namaLengkap = $user['nama_lengkap'] ?? $user['username']; // default dari tabel user

        switch ($user['role']) {
            case 'mahasiswa':
                $mahasiswaModel = new \App\Models\MahasiswaModel();
                $mahasiswa = $mahasiswaModel->where('nim', $user['nim'])->first();
                if ($mahasiswa) {
                    $namaLengkap = $mahasiswa['nama_lengkap'];
                }
                break;

            case 'dospem':
            case 'kaprodi':
                $dosenModel = new \App\Models\DosenModel();
                $dosen = $dosenModel->where('nppy', $user['nppy'])->first();
                if ($dosen) {
                    $namaLengkap = $dosen['nama_lengkap'];
                }
                break;

            case 'mitra':
                $unitModel = new \App\Models\UnitModel();
                $unit = $unitModel->find($user['id_unit']);
                if ($unit) {
                    $namaLengkap = $unit['nama_pembimbing'];
                }
                break;

            case 'admin':
            default:
                $namaLengkap = $user['nama_lengkap'] ?? $user['username'];
                break;
        }

        // 🔹 Mapping role ke nama deskriptif
        $roleNames = [
            'admin'     => 'Administrator',
            'mahasiswa' => 'Mahasiswa',
            'dospem'    => 'Dosen Pembimbing',
            'mitra'     => 'Pembimbing Perusahaan',
            'kaprodi'   => 'Kepala Program Studi'
        ];

        $roleDisplay = $roleNames[$user['role']] ?? ucfirst($user['role']);

        // 🔹 Set flashdata
        $this->session->setFlashdata(
            'success', 
            'Selamat datang ' . $namaLengkap . ', Anda login sebagai ' . $roleDisplay
        );


        // 🔹 Redirect sesuai role
        return $this->redirectToDashboard($user['role']);
    }
    

    // Logout
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }

    // Redirect sesuai role
    private function redirectToDashboard($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->to('/admin/dashboard');
            case 'mahasiswa':
                return redirect()->to('/mahasiswa/dashboard');
            case 'dospem':
                return redirect()->to('/dospem/dashboard');
            case 'mitra':
                return redirect()->to('/mitra/dashboard');
            case 'kaprodi':
                return redirect()->to('/kaprodi/dashboard');
            default:
                return redirect()->to('/login');
        }
    }

    // Halaman update password
    public function updatePassword()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data['user_name'] = $this->getUserName();
        $data['foto'] = $this->getUserFoto();

        return view('update_password', $data);
    }

    // Proses update password
    public function updatePasswordProcess()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = $this->session->get('id_user');
        $oldPassword = $this->request->getPost('old_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Validasi input
        if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
            return redirect()->back()->with('error', 'Semua kolom wajib diisi.');
        }

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Kata sandi baru dan konfirmasi tidak sama.');
        }

        if (strlen($newPassword) < 8) {
            return redirect()->back()->with('error', 'Kata sandi baru minimal 8 karakter.');
        }

        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // 🔹 Cek kata sandi lama (password HASH)
        if (!password_verify($oldPassword, $user['password'])) {
            return redirect()->back()->with('error', 'Kata sandi lama salah.');
        }

        $this->userModel->update($userId, [
            'password' => $newPassword // simpan plain text
        ]);

        // 🔹 Tandai password bukan default lagi
        $this->session->set('isDefaultPassword', false);

        return redirect()->to('/update_password')->with('success', 'Kata sandi berhasil diperbarui.');
    }

}
