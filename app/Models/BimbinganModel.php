<?php

namespace App\Models;

use CodeIgniter\Model;

class BimbinganModel extends Model
{
    protected $table            = 'bimbingan';
    protected $primaryKey       = 'id_bimbingan';

    protected $allowedFields = [
        'id_profil',
        'kode_mk',
        'tanggal_bimbingan',
        'progress_ta',
        'status_bimbingan',
        'catatan_detail',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Relasi join ke tabel profil_magang (untuk ambil nim, mitra, dll)
     */
    public function getWithProfil($id_profil = null)
    {
        $builder = $this->select('bimbingan.*, profil_magang.nim, profil_magang.id_mitra, profil_magang.id_unit')
                        ->join('profil_magang', 'profil_magang.id_profil = bimbingan.id_profil', 'left');

        if ($id_profil) {
            $builder->where('bimbingan.id_profil', $id_profil);
        }

        return $builder->findAll();
    }

    /**
     * Relasi join ke tabel mata_kuliah (untuk ambil nama_mk, sks, dll)
     */
    public function getWithMatakuliah($id_profil = null)
    {
        $builder = $this->select('bimbingan.*, mata_kuliah.nama_mk, mata_kuliah.sks')
                        ->join('mata_kuliah', 'mata_kuliah.kode_mk = bimbingan.kode_mk', 'left');

        if ($id_profil) {
            $builder->where('bimbingan.id_profil', $id_profil);
        }

        return $builder->findAll();
    }

    /**
     * Ambil semua data lengkap (join profil_magang + mata_kuliah)
     */
    public function getFullData($id_profil = null)
    {
        $builder = $this->db->table($this->table)
            ->select('bimbingan.*, profil_magang.nim, mahasiswa.nama_lengkap, tugas_akhir_magang.judul_ta, mata_kuliah.nama_mk, mata_kuliah.sks')
            ->join('profil_magang', 'profil_magang.id_profil = bimbingan.id_profil', 'left')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim', 'left')
            ->join('tugas_akhir_magang', 'tugas_akhir_magang.id_profil = profil_magang.id_profil', 'left')
            ->join('mata_kuliah', 'mata_kuliah.kode_mk = bimbingan.kode_mk', 'left');

        if ($id_profil) {
            $builder->where('bimbingan.id_profil', $id_profil);
        }

        return $builder->get()->getResultArray();
    }


}
