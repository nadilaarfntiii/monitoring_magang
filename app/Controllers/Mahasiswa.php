<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\UserModel;
use App\Models\ProfilMagangModel;
use App\Models\MitraModel;
use App\Models\UnitModel;
use App\Models\ProgramMagangModel;

class Mahasiswa extends BaseController
{
    protected $mahasiswaModel;
    protected $profilMagangModel;
    protected $mitraModel;
    protected $unitModel;
    protected $programMagangModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        $this->profilMagangModel = new ProfilMagangModel();
        $this->mitraModel = new \App\Models\MitraModel();
        $this->unitModel = new \App\Models\UnitModel();
        $this->programMagangModel = new ProgramMagangModel();
    }

    public function profil()
    {
        $nim = session()->get('nim');
        if (!$nim) {
            return redirect()->to('/login');
        }

    $profil = $this->profilMagangModel
        ->select('
            profil_magang.*, 
            mahasiswa.nama_lengkap,
            mahasiswa.program_studi,
            mahasiswa.jenis_kelamin,
            mahasiswa.email AS email_mahasiswa,
            mahasiswa.handphone,
            dosen.nama_lengkap AS nama_dosen,
            mitra.nama_mitra, 
            mitra.alamat AS alamat_mitra,
            unit.nama_unit,
            unit.nama_pembimbing,
            unit.jabatan,
            unit.no_hp,
            unit.email,
            program_magang.nama_program
        ')
        ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim', 'left') 
        ->join('dosen', 'dosen.nppy = profil_magang.nppy', 'left')
        ->join('mitra', 'mitra.id_mitra = profil_magang.id_mitra', 'left')
        ->join('unit', 'unit.id_unit = profil_magang.id_unit', 'left')
        ->join('program_magang', 'program_magang.id_program = profil_magang.id_program', 'left')
        ->where('profil_magang.nim', $nim)
        ->first();

    $program = $this->programMagangModel->findAll();

    $unitList = [];
    if ($profil && $profil['id_mitra']) {
        $unitList = $this->unitModel->where('id_mitra', $profil['id_mitra'])->findAll();
    }

    // 🔹 Cek apakah data magang sudah lengkap
    $dataLengkap = true;
    if (
        empty($profil['id_mitra']) ||
        empty($profil['id_unit']) ||
        empty($profil['id_program']) ||
        empty($profil['tanggal_mulai']) ||
        empty($profil['tanggal_selesai'])
    ) {
        $dataLengkap = false;
    }

    $data = [
        'profil' => $profil,
        'unitList' => $unitList,
        'program' => $program,
        'dataLengkap' => $dataLengkap,
        'user_name' => $this->getUserName(),
    ];

    return view('mahasiswa/profil_magang', $data);
}


public function searchMitra()
{
    $query = $this->request->getGet('q');
    if (!$query) {
        return $this->response->setJSON([['label' => 'Tidak ada input', 'value' => '', 'id' => '']]);
    }

    $data = $this->mitraModel->like('nama_mitra', $query)->findAll(10);

    if (empty($data)) {
        return $this->response->setJSON([['label' => 'Mitra tidak ditemukan', 'value' => '', 'id' => '']]);
    }

    $results = [];
    foreach ($data as $m) {
        $results[] = [
            'label' => $m['nama_mitra'],
            'value' => $m['nama_mitra'],
            'id'    => $m['id_mitra']
        ];
    }

    return $this->response->setJSON($results);
}

public function getMitraDetail()
{
    $id_mitra = $this->request->getGet('id_mitra');
    if (!$id_mitra) {
        return $this->response->setJSON(['error' => 'ID mitra tidak ditemukan']);
    }

    $mitra = $this->mitraModel->where('id_mitra', $id_mitra)->first();
    if (!$mitra) {
        return $this->response->setJSON(['error' => 'Data mitra tidak ditemukan']);
    }

    return $this->response->setJSON([
        'alamat_mitra' => $mitra['alamat'] ?? ''
    ]);
}



    // Ambil unit berdasarkan mitra
    public function getUnitByMitra()
    {
        $id_mitra = $this->request->getGet('id_mitra');
        if (!$id_mitra) return $this->response->setJSON([]);

        $units = $this->unitModel
                    ->where('id_mitra', $id_mitra)
                    ->findAll();

        $results = [];
        foreach($units as $u){
            $results[] = [
                'id_unit'   => $u['id_unit'],
                'nama_unit' => $u['nama_unit']
            ];
        }

        return $this->response->setJSON($results);
    }

    public function getUnitDetail()
{
    $id_unit = $this->request->getGet('id_unit');
    if (!$id_unit) {
        return $this->response->setJSON(['error' => 'ID unit tidak ditemukan']);
    }

    $unit = $this->unitModel->where('id_unit', $id_unit)->first();

    if (!$unit) {
        return $this->response->setJSON(['error' => 'Data unit tidak ditemukan']);
    }

    return $this->response->setJSON([
        'nama_pembimbing' => $unit['nama_pembimbing'] ?? '',
        'jabatan'         => $unit['jabatan'] ?? '',
        'no_hp'           => $unit['no_hp'] ?? '',
        'email'           => $unit['email'] ?? ''
    ]);
}


    public function tambahUnit()
    {
        $unitModel = new \App\Models\UnitModel();
        $namaUnit = $this->request->getPost('nama_unit');
        $idMitra = $this->request->getPost('id_mitra');

        if (!$namaUnit || !$idMitra) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak lengkap'
            ]);
        }

        $data = [
            'nama_unit' => $namaUnit,
            'id_mitra'  => $idMitra
        ];
        $unitModel->insert($data);
        $idUnit = $unitModel->getInsertID();

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'id_unit' => $idUnit,
                'nama_unit' => $namaUnit
            ]
        ]);
    }

    public function updateProfil()
    {
        $nim = session()->get('nim');
        if (!$nim) {
            return redirect()->to('/login');
        }

        $this->db = \Config\Database::connect();

        // Ambil semua input dari form
        $id_mitra = $this->request->getPost('id_mitra');
        $id_unit = $this->request->getPost('id_unit');
        $id_program = $this->request->getPost('id_program');
        $tanggal_mulai = $this->request->getPost('tanggal_mulai');
        $tanggal_selesai = $this->request->getPost('tanggal_selesai');

        // 🛑 Validasi tanggal
        if (strtotime($tanggal_selesai) < strtotime($tanggal_mulai)) {
            session()->setFlashdata('error', 'Tanggal selesai tidak boleh lebih kecil dari tanggal mulai.');
            return redirect()->back()->withInput();
        }

        // === 1️⃣ Update data mahasiswa ===
        $mahasiswaData = [
            'email'     => $this->request->getPost('email_mahasiswa'),
            'handphone' => $this->request->getPost('handphone')
        ];
        $this->db->table('mahasiswa')->where('nim', $nim)->update($mahasiswaData);

        // === 2️⃣ Update data mitra ===
        if ($id_mitra) {
            $mitraData = [
                'alamat' => $this->request->getPost('alamat_mitra')
            ];
            $this->db->table('mitra')->where('id_mitra', $id_mitra)->update($mitraData);
        }

        // === 3️⃣ Update data unit ===
        if ($id_unit) {
            $unitData = [
                'nama_pembimbing' => $this->request->getPost('nama_pembimbing'),
                'jabatan'         => $this->request->getPost('jabatan'),
                'no_hp'           => $this->request->getPost('no_hp'),
                'email'           => $this->request->getPost('email_unit')
            ];
            $this->db->table('unit')->where('id_unit', $id_unit)->update($unitData);
        }

        // === 4️⃣ Update data profil magang ===
        $profilData = [
            'id_mitra'        => $id_mitra,
            'id_unit'         => $id_unit,
            'id_program'      => $id_program,
            'tanggal_mulai'   => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'status'          => 'aktif',
        ];

        $this->profilMagangModel->where('nim', $nim)->set($profilData)->update();

        session()->setFlashdata('success', 'Data profil magang berhasil diperbarui.');
        return redirect()->to(base_url('mahasiswa/profil_magang'));
    }

    public function profilAkun()
    {
        $nim = session()->get('nim');
        $id_user = session()->get('id_user');

        if (!$nim || !$id_user) {
            return redirect()->to('/login');
        }

        $mahasiswa = $this->mahasiswaModel->find($nim);

        $userModel = new UserModel();
        $user = $userModel->find($id_user);

        if (!$mahasiswa || !$user) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return redirect()->back();
        }

        return view('mahasiswa/profil', [
            'mahasiswa' => $mahasiswa,
            'user'      => $user,
            'user_name' => $this->getUserName()
        ]);
    }

    public function updateProfilAkun()
    {
        $nim = session()->get('nim');
        $id_user = session()->get('id_user');

        if (!$nim || !$id_user) {
            return redirect()->to('/login');
        }

        $mahasiswa = $this->mahasiswaModel->find($nim);
        $userModel = new UserModel();
        $user = $userModel->find($id_user);

        if (!$mahasiswa || !$user) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return redirect()->back();
        }

        /* =========================
        UPLOAD FOTO (UNTUK USER)
        ========================== */
        $foto = $this->request->getFile('foto');
        $namaFoto = $user['foto']; // default foto lama

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $namaFoto = $foto->getRandomName();
            $foto->move('uploads/foto', $namaFoto);

            // hapus foto lama
            if (!empty($user['foto']) && file_exists('uploads/foto/' . $user['foto'])) {
                unlink('uploads/foto/' . $user['foto']);
            }
        }

        /* =========================
        UPDATE TABEL MAHASISWA
        ========================== */
        $dataMahasiswa = [
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'alamat'        => $this->request->getPost('alamat'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'email'         => $this->request->getPost('email'),
            'handphone'     => $this->request->getPost('handphone'),
        ];

        $this->mahasiswaModel->update($nim, $dataMahasiswa);

        /* =========================
        UPDATE TABEL USER
        ========================== */
        $dataUser = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'foto'         => $namaFoto
        ];

        $userModel->update($id_user, $dataUser);

        session()->setFlashdata('success', 'Profil akun berhasil diperbarui.');
        return redirect()->to(base_url('mahasiswa/profil'));
    }




}
