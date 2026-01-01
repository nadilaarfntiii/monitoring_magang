<?php

namespace App\Controllers;

use App\Models\MitraModel;
use App\Models\UnitModel;
use App\Models\ProfilMagangModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class PembimbingMitra extends BaseController
{
    protected $session;
    protected $mitraModel;
    protected $unitModel;
    protected $profilMagangModel;
    protected $userModel;

    public function __construct()
    {
        $this->session = session();
        $this->mitraModel = new MitraModel();
        $this->unitModel = new UnitModel();
        $this->profilMagangModel = new ProfilMagangModel();
        $this->userModel  = new UserModel();
    }

    // 🔹 Dashboard Pembimbing Mitra
    public function dashboard()
    {
        $session = session();

        if (!$session->get('isLoggedIn') || $session->get('role') !== 'mitra') {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $id_user = $session->get('id_user');

        // ==============================
        // Ambil USER
        // ==============================
        $user = $this->userModel->find($id_user);

        // ==============================
        // Ambil UNIT (FK: user.id_unit)
        // ==============================
        $unit = null;
        if (!empty($user['id_unit'])) {
            $unit = $this->unitModel
                ->where('id_unit', $user['id_unit'])
                ->first();
        }

        // ==============================
        // NAMA (Unit → User → Default)
        // ==============================
        $user_name = $unit['nama_pembimbing']
            ?? $user['nama_lengkap']
            ?? 'Pembimbing Perusahaan';

        // ==============================
        // FOTO (Unit → User → Default)
        // ==============================
        $foto = 'pp.jpg';

        if (!empty($unit['foto']) && file_exists(FCPATH . 'uploads/foto/' . $unit['foto'])) {
            $foto = $unit['foto'];
        } elseif (!empty($user['foto']) && file_exists(FCPATH . 'uploads/foto/' . $user['foto'])) {
            $foto = $user['foto'];
        }

        return view('mitra/dashboard', [
            'user_name' => $user_name,
            'foto'      => $foto,
            'role_name' => 'Pembimbing Perusahaan'
        ]);
    }


     // 🔹 Profil Akun Pembimbing Mitra
    public function profil()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'mitra') {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $id_user = $this->session->get('id_user');

        // ==============================
        // Ambil USER
        // ==============================
        $user = $this->userModel->find($id_user);

        if (!$user || empty($user['id_unit'])) {
            return redirect()->back()->with('error', 'Data unit tidak ditemukan.');
        }

        // ==============================
        // Ambil UNIT (PK: id_unit)
        // ==============================
        $unit = $this->unitModel
            ->where('id_unit', $user['id_unit'])
            ->first();

        if (!$unit) {
            return redirect()->back()->with('error', 'Data unit tidak ditemukan.');
        }

        // ==============================
        // Ambil MITRA
        // ==============================
        $mitra = $this->mitraModel->find($unit['id_mitra']);

        return view('mitra/profil', [
            'user' => $user,
            'unit' => $unit,
            'mitra'=> $mitra,
            'user_name' => $unit['nama_pembimbing'] 
                            ?? $user['nama_lengkap'] 
                            ?? 'Pembimbing Perusahaan',
            'foto' => $this->getUserFoto()
        ]);
    }


    // ==============================
    // UPDATE PROFIL
    // ==============================
    public function updateProfil()
    {
        $idUser = $this->session->get('id_user');
        $idUnit = $this->request->getPost('id_unit');

        // ------------------------------
        // 1. UPDATE USER (USERNAME + FOTO)
        // ------------------------------
        $dataUser = [
            'username' => $this->request->getPost('username')
        ];

        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {

            $namaFoto = $foto->getRandomName();
            $foto->move('uploads/foto', $namaFoto);

            $dataUser['foto'] = $namaFoto;

            // update session
            $this->session->set('foto', $namaFoto);
        }

        $this->userModel->update($idUser, $dataUser);
        $this->session->set('username', $dataUser['username']);

        // ------------------------------
        // 2. UPDATE UNIT
        // ------------------------------
        $this->unitModel->update($idUnit, [
            'nama_pembimbing' => $this->request->getPost('nama_pembimbing'),
            'jabatan'         => $this->request->getPost('jabatan'),
            'no_hp'           => $this->request->getPost('no_hp'),
            'email'           => $this->request->getPost('email')
        ]);

        // ------------------------------
        // 3. UPDATE MITRA (ALAMAT)
        // ------------------------------
        $unit = $this->unitModel->find($idUnit);

        $this->mitraModel->update($unit['id_mitra'], [
            'alamat' => $this->request->getPost('alamat')
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    // 🔹 Halaman daftar mahasiswa magang (berdasarkan id_unit dari session login)
    public function mahasiswa()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'mitra') {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $id_unit = $this->session->get('id_unit');
        if (!$id_unit) {
            return view('mitra/mahasiswa', ['mahasiswa' => [], 'message' => 'Unit tidak ditemukan.']);
        }

        $data['mahasiswa'] = $this->profilMagangModel
            ->select('
                profil_magang.id_profil,
                mahasiswa.nim,
                mahasiswa.nama_lengkap,
                dosen.nama_lengkap AS nama_dosen,
                program_magang.nama_program,
                profil_magang.tanggal_mulai,
                profil_magang.tanggal_selesai,
                profil_magang.status
            ')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
            ->join('dosen', 'dosen.nppy = profil_magang.nppy', 'left')
            ->join('program_magang', 'program_magang.id_program = profil_magang.id_program', 'left')
            ->where('profil_magang.id_unit', $id_unit)
            ->findAll();

        return view('mitra/mahasiswa', $data);
    }

    public function detail($id)
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'mitra') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak memiliki akses.']);
        }

        $id_unit = $this->session->get('id_unit');

        $data = $this->profilMagangModel
            ->select('
                profil_magang.*, 
                mahasiswa.nim, mahasiswa.nama_lengkap, mahasiswa.email AS email_mahasiswa, mahasiswa.handphone,
                dosen.nppy, dosen.nama_lengkap AS nama_dosen, dosen.no_hp AS no_hp_dosen, dosen.email AS email_dosen,
                program_magang.nama_program
            ')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
            ->join('dosen', 'dosen.nppy = profil_magang.nppy', 'left')
            ->join('program_magang', 'program_magang.id_program = profil_magang.id_program', 'left')
            ->where('profil_magang.id_profil', $id)
            ->where('profil_magang.id_unit', $id_unit)
            ->first();

        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan atau bukan dari unit Anda.']);
    }
}
