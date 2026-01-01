<?php

namespace App\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{
    protected $table            = 'dosen';
    protected $primaryKey       = 'nppy';
    protected $returnType       = 'array';
    protected $useAutoIncrement = false; // karena primary key varchar
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'nppy',
        'nama_lengkap',
        'pendidikan_terakhir',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'kota',
        'kode_pos',
        'provinsi',
        'negara',
        'agama',
        'email',
        'no_hp',
        'status_dosen',
        'jabatan_fungsional',
        'foto'
    ];

    // Jika ada field tanggal otomatis
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
