<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Admin extends BaseController
{
    protected $session;
    protected $userModel;

    public function __construct()
    {
        $this->session = session();
        $this->userModel = new UserModel();

        // Cek login
        if (!$this->session->has('id_user')) {
            echo redirect()->to(base_url('login'));
            exit;
        }
    }

    // ===============================
    // HALAMAN PROFIL
    // ===============================
    public function profil()
    {
        $id = $this->session->get('id_user');

        $data['user_name'] = $this->getUserName();

        $data['user'] = $this->userModel->find($id);

        return view('admin/profil', $data);
    }

    // ===============================
    // UPDATE PROFIL
    // ===============================
    public function update_profil()
    {
        $id = $this->request->getPost('id_user');

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
        ];

        // ======================
        // HANDLE UPLOAD FOTO
        // ======================
        $foto = $this->request->getFile('foto');

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $newName = $foto->getRandomName();
            $foto->move('uploads/foto/', $newName);
        
            $data['foto'] = $newName;
        
            // Update session foto
            $this->session->set('foto', $newName);
        }

        // ======================
        // UPDATE USER
        // ======================
        if ($this->userModel->skipValidation(true)->update($id, $data)) {
            return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
    }
}
