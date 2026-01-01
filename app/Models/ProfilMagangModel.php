<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilMagangModel extends Model
{
    protected $table = 'profil_magang';
    protected $primaryKey = 'id_profil';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nim',
        'nppy',
        'id_mitra',
        'id_unit',
        'id_program',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'keterangan' 
        // semester & tahun_ajaran tidak perlu dimasukkan karena GENERATED
    ];

    // Aktifkan soft deletes
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    public function getAllProfil()
    {
        return $this->select('
                    profil_magang.*, 
                    mahasiswa.nama_lengkap, 
                    dosen.nama_lengkap AS nama_dosen,
                    mitra.nama_mitra, 
                    unit.nama_unit,
                    unit.nama_pembimbing,
                    unit.jabatan,
                    unit.no_hp,
                    unit.email,
                    program_magang.nama_program,
                    profil_magang.semester,
                    profil_magang.tahun_ajaran,
                    profil_magang.keterangan
                ')
                ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
                ->join('dosen', 'dosen.nppy = profil_magang.nppy', 'left')
                ->join('mitra', 'mitra.id_mitra = profil_magang.id_mitra', 'left')
                ->join('unit', 'unit.id_unit = profil_magang.id_unit', 'left')
                ->join('program_magang', 'program_magang.id_program = profil_magang.id_program', 'left')
                ->findAll();
    }

    public function getProfilFull($nim)
    {
        return $this->select('
                    profil_magang.*,
                    mahasiswa.nama_lengkap,
                    mahasiswa.fakultas,
                    mahasiswa.program_studi,
                    mahasiswa.angkatan,
                    mahasiswa.email,
                    mahasiswa.handphone,
                    mahasiswa.alamat,
                    program_magang.nama_program,
                    mitra.nama_mitra,
                    mitra.alamat AS mitra_alamat,
                    unit.nama_unit,
                    unit.nama_pembimbing,
                    unit.no_hp AS unit_no_hp,
                    unit.email AS unit_email
                ')
                ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
                ->join('program_magang', 'program_magang.id_program = profil_magang.id_program', 'left')
                ->join('mitra', 'mitra.id_mitra = profil_magang.id_mitra', 'left')
                ->join('unit', 'unit.id_unit = profil_magang.id_unit', 'left')
                ->where('profil_magang.nim', $nim)
                ->first();
    }

    public function getArsipMahasiswa()
    {
        return $this->select('
                    profil_magang.*, 
                    mahasiswa.nama_lengkap, 
                    dosen.nama_lengkap AS nama_dosen,
                    mitra.nama_mitra, 
                    unit.nama_unit,
                    program_magang.nama_program
                ')
                ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
                ->join('dosen', 'dosen.nppy = profil_magang.nppy', 'left')
                ->join('mitra', 'mitra.id_mitra = profil_magang.id_mitra', 'left')
                ->join('unit', 'unit.id_unit = profil_magang.id_unit', 'left')
                ->join('program_magang', 'program_magang.id_program = profil_magang.id_program', 'left')
                ->whereIn('profil_magang.status', ['selesai', 'tidak selesai', 'tidak aktif'])
                ->findAll();
    }



}
