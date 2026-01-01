<?php

namespace App\Models;

use CodeIgniter\Model;

class PresensiMahasiswaModel extends Model
{
    protected $table = 'presensi_mahasiswa';
    protected $primaryKey = 'id_presensi';
    protected $allowedFields = [
        'id_presensi',
        'nim',
        'id_jam_kerja',
        'tanggal',
        'waktu_masuk',
        'waktu_keluar',
        'status_kehadiran',
        'keterangan',
        'foto_bukti',
        'status_presensi',
        'catatan_validasi',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = false;
}
