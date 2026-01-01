<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TugasAkhirMagangModel;
use App\Models\ProfilMagangModel;
use App\Models\MatakuliahModel;

class TugasAkhirMagang extends BaseController
{
    protected $tugasAkhirModel;
    protected $profilMagangModel;
    protected $matakuliahModel;
    protected $session;

    public function __construct()
    {
        $this->tugasAkhirModel = new TugasAkhirMagangModel();
        $this->profilMagangModel = new ProfilMagangModel();
        $this->matakuliahModel = new MatakuliahModel();
        $this->session = session();
    }

    // =========================
    // 🔹 INDEX / LIST DATA
    // =========================
    public function index()
    {
        $nim = $this->session->get('nim');
        if (!$nim) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $profil = $this->profilMagangModel->where('nim', $nim)->first();
        if (!$profil) {
            return view('mahasiswa/tugas_akhir_magang', [
                'tugasAkhir' => [],
                'mataKuliah' => [],
                'mkMap' => [],
                'mataKuliahTersedia' => []
            ]);
        }

        $tugasAkhir = $this->tugasAkhirModel->getByProfil($profil['id_profil']);
        $mataKuliah = $this->matakuliahModel->getActive();

        // Mapping kode_mk => nama_mk
        $mkMap = [];
        foreach ($mataKuliah as $mk) {
            $mkMap[$mk['kode_mk']] = $mk['nama_mk'];
        }

        // Cek mata kuliah yang belum punya tugas akhir
        $mataKuliahTersedia = array_filter($mataKuliah, function($mk) use ($tugasAkhir) {
            foreach ($tugasAkhir as $ta) {
                if ($ta['kode_mk'] == $mk['kode_mk']) return false;
            }
            return true;
        });

        return view('mahasiswa/tugas_akhir_magang', [
            'tugasAkhir' => $tugasAkhir,
            'mataKuliah' => $mataKuliah,
            'mkMap' => $mkMap,
            'mataKuliahTersedia' => $mataKuliahTersedia
        ]);
    }


    // =========================
    // 🔹 STORE DATA BARU
    // =========================
    public function store()
    {
        $nim = $this->session->get('nim');
        if (!$nim) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $profil = $this->profilMagangModel->where('nim', $nim)->first();
        if (!$profil) {
            return redirect()->back()->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        $data = [
            'id_profil' => $profil['id_profil'],
            'kode_mk'   => $this->request->getPost('kode_mk'),
            'judul_ta'  => $this->request->getPost('judul_ta')
        ];

        try {
            $this->tugasAkhirModel->insert($data);
            return redirect()->to('/tugas_akhir_magang')->with('success', 'Data tugas akhir berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // =========================
    // 🔹 UPDATE DATA
    // =========================
    public function update($id)
    {
        $nim = $this->session->get('nim');
        if (!$nim) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $profil = $this->profilMagangModel->where('nim', $nim)->first();
        if (!$profil) {
            return redirect()->back()->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        $tugasAkhir = $this->tugasAkhirModel->find($id);
        if (!$tugasAkhir || $tugasAkhir['id_profil'] != $profil['id_profil']) {
            return redirect()->back()->with('error', 'Data tugas akhir tidak ditemukan atau tidak bisa diubah.');
        }

        $data = [
            'judul_ta' => $this->request->getPost('judul_ta')
        ];

        try {
            $this->tugasAkhirModel->update($id, $data);
            return redirect()->to('/tugas_akhir_magang')->with('success', 'Data tugas akhir berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}
