<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table      = 'mahasiswa';
    protected $primaryKey = 'nim';

    protected $useAutoIncrement = false; // karena primary key = varchar (nim)
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'nim',
        'nik',
        'nama_lengkap',
        'fakultas',
        'program_studi',
        'program',
        'kelas',
        'angkatan',
        'dosen_pa',
        'alamat',
        'kota',
        'kode_pos',
        'provinsi',
        'negara',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'kewarganegaraan',
        'agama',
        'status_marital',
        'foto',
        'email',
        'handphone'
    ];
}
