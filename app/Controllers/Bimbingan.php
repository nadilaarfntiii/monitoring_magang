<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BimbinganModel;
use App\Models\ProfilMagangModel;
use App\Models\TugasAkhirMagangModel;

class Bimbingan extends BaseController
{
    protected $bimbinganModel;
    protected $profilMagangModel;
    protected $session;

    public function __construct()
    {
        $this->bimbinganModel = new BimbinganModel();
        $this->profilMagangModel = new ProfilMagangModel();
        $this->taModel = new TugasAkhirMagangModel();
        $this->session = \Config\Services::session();
    }

    public function magang()
    {
        $nim = $this->session->get('nim');

        if (!$nim) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 🔹 Ambil profil magang mahasiswa berdasarkan NIM
        $profil = $this->profilMagangModel
            ->select('id_profil')
            ->where('nim', $nim)
            ->first();

        if (!$profil) {
            return view('mahasiswa/bimbingan_magang', ['bimbingan' => []]);
        }

        // 🔹 Ambil tugas akhir mahasiswa untuk kode_mk = BB010
        $tugasAkhir = $this->bimbinganModel->db->table('tugas_akhir_magang')
            ->where('id_profil', $profil['id_profil'])
            ->where('kode_mk', 'BB010')
            ->get()
            ->getRowArray();

        // 🔹 Ambil semua data bimbingan magang (kode_mk = BB010)
        $bimbingan = $this->bimbinganModel
            ->select('bimbingan.*, profil_magang.nim, mahasiswa.nama_lengkap, mata_kuliah.nama_mk, mata_kuliah.sks')
            ->join('profil_magang', 'profil_magang.id_profil = bimbingan.id_profil', 'left')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim', 'left')
            ->join('mata_kuliah', 'mata_kuliah.kode_mk = bimbingan.kode_mk', 'left')
            ->where('bimbingan.id_profil', $profil['id_profil'])
            ->where('bimbingan.kode_mk', 'BB010')
            ->orderBy('bimbingan.tanggal_bimbingan', 'ASC')
            ->findAll();

        // 🔹 Tambahkan judul_ta dari tugas_akhir_magang ke setiap bimbingan
        foreach ($bimbingan as &$b) {
            $b['judul_ta'] = $tugasAkhir['judul_ta'] ?? '-';
        }

        return view('mahasiswa/bimbingan_magang', [
            'bimbingan' => $bimbingan,
            'tugasAkhir' => $tugasAkhir,
            'id_profil' => $profil['id_profil'],
            'user_name' => $this->getUserName(), 
            'foto'      => $this->getUserFoto()
        ]);
    }

    public function simpanJudul()
    {
        $id_profil = $this->request->getPost('id_profil');
        $judul_ta  = $this->request->getPost('judul_ta');

        if (!$id_profil) {
            return redirect()->back()->with('error', 'ID Profil tidak ditemukan.');
        }

        // Cek apakah sudah ada judul sebelumnya
        $existing = $this->taModel
            ->where('id_profil', $id_profil)
            ->where('kode_mk', 'BB010')
            ->first();

        if ($existing) {
            // Update judul
            $this->taModel->update($existing['id_ta'], [
                'judul_ta' => $judul_ta
            ]);
        } else {
            // Simpan baru
            $this->taModel->insert([
                'id_profil' => $id_profil,
                'kode_mk'   => 'BB010',
                'judul_ta'  => $judul_ta
            ]);
        }

        return redirect()->to('mahasiswa/bimbingan_magang')->with('success', 'Judul berhasil disimpan.');
    }


    public function asb()
    {
        $nim = $this->session->get('nim');

        if (!$nim) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 🔹 Ambil profil magang mahasiswa berdasarkan NIM
        $profil = $this->profilMagangModel
            ->select('id_profil')
            ->where('nim', $nim)
            ->first();

        if (!$profil) {
            return view('mahasiswa/bimbingan_asb', ['bimbingan' => []]);
        }

        // 🔹 Ambil tugas akhir mahasiswa untuk kode_mk = KK166
        $tugasAkhir = $this->bimbinganModel->db->table('tugas_akhir_magang')
            ->where('id_profil', $profil['id_profil'])
            ->where('kode_mk', 'KK166')
            ->get()
            ->getRowArray();

        // 🔹 Ambil semua data bimbingan magang (kode_mk = KK166)
        $bimbingan = $this->bimbinganModel
            ->select('bimbingan.*, profil_magang.nim, mahasiswa.nama_lengkap, mata_kuliah.nama_mk, mata_kuliah.sks')
            ->join('profil_magang', 'profil_magang.id_profil = bimbingan.id_profil', 'left')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim', 'left')
            ->join('mata_kuliah', 'mata_kuliah.kode_mk = bimbingan.kode_mk', 'left')
            ->where('bimbingan.id_profil', $profil['id_profil'])
            ->where('bimbingan.kode_mk', 'KK166')
            ->orderBy('bimbingan.tanggal_bimbingan', 'ASC')
            ->findAll();

        // 🔹 Tambahkan judul_ta dari tugas_akhir_magang ke setiap bimbingan
        foreach ($bimbingan as &$b) {
            $b['judul_ta'] = $tugasAkhir['judul_ta'] ?? '-';
        }

        return view('mahasiswa/bimbingan_asb', [
            'bimbingan' => $bimbingan,
        ]);
    }


    public function dsib()
    {
        $nim = $this->session->get('nim');

        if (!$nim) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 🔹 Ambil profil magang mahasiswa berdasarkan NIM
        $profil = $this->profilMagangModel
            ->select('id_profil')
            ->where('nim', $nim)
            ->first();

        if (!$profil) {
            return view('mahasiswa/bimbingan_dsib', ['bimbingan' => []]);
        }

        // 🔹 Ambil tugas akhir mahasiswa untuk kode_mk = KB319
        $tugasAkhir = $this->bimbinganModel->db->table('tugas_akhir_magang')
            ->where('id_profil', $profil['id_profil'])
            ->where('kode_mk', 'KB319')
            ->get()
            ->getRowArray();

        // 🔹 Ambil semua data bimbingan magang (kode_mk = KB319)
        $bimbingan = $this->bimbinganModel
            ->select('bimbingan.*, profil_magang.nim, mahasiswa.nama_lengkap, mata_kuliah.nama_mk, mata_kuliah.sks')
            ->join('profil_magang', 'profil_magang.id_profil = bimbingan.id_profil', 'left')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim', 'left')
            ->join('mata_kuliah', 'mata_kuliah.kode_mk = bimbingan.kode_mk', 'left')
            ->where('bimbingan.id_profil', $profil['id_profil'])
            ->where('bimbingan.kode_mk', 'KB319')
            ->orderBy('bimbingan.tanggal_bimbingan', 'ASC')
            ->findAll();

        // 🔹 Tambahkan judul_ta dari tugas_akhir_magang ke setiap bimbingan
        foreach ($bimbingan as &$b) {
            $b['judul_ta'] = $tugasAkhir['judul_ta'] ?? '-';
        }

        return view('mahasiswa/bimbingan_dsib', [
            'bimbingan' => $bimbingan,
        ]);
    }

    public function kombis()
    {
        $nim = $this->session->get('nim');

        if (!$nim) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 🔹 Ambil profil magang mahasiswa berdasarkan NIM
        $profil = $this->profilMagangModel
            ->select('id_profil')
            ->where('nim', $nim)
            ->first();

        if (!$profil) {
            return view('mahasiswa/bimbingan_kombis', ['bimbingan' => []]);
        }

        // 🔹 Ambil tugas akhir mahasiswa untuk kode_mk = KB299
        $tugasAkhir = $this->bimbinganModel->db->table('tugas_akhir_magang')
            ->where('id_profil', $profil['id_profil'])
            ->where('kode_mk', 'KB299')
            ->get()
            ->getRowArray();

        // 🔹 Ambil semua data bimbingan magang (kode_mk = KB299)
        $bimbingan = $this->bimbinganModel
            ->select('bimbingan.*, profil_magang.nim, mahasiswa.nama_lengkap, mata_kuliah.nama_mk, mata_kuliah.sks')
            ->join('profil_magang', 'profil_magang.id_profil = bimbingan.id_profil', 'left')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim', 'left')
            ->join('mata_kuliah', 'mata_kuliah.kode_mk = bimbingan.kode_mk', 'left')
            ->where('bimbingan.id_profil', $profil['id_profil'])
            ->where('bimbingan.kode_mk', 'KB299')
            ->orderBy('bimbingan.tanggal_bimbingan', 'ASC')
            ->findAll();

        // 🔹 Tambahkan judul_ta dari tugas_akhir_magang ke setiap bimbingan
        foreach ($bimbingan as &$b) {
            $b['judul_ta'] = $tugasAkhir['judul_ta'] ?? '-';
        }

        return view('mahasiswa/bimbingan_kombis', [
            'bimbingan' => $bimbingan,
        ]);
    }

    
}
