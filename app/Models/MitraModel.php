<?php

namespace App\Models;

use CodeIgniter\Model;

class MitraModel extends Model
{
    protected $table            = 'mitra';
    protected $primaryKey       = 'id_mitra';

    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'nama_mitra',
        'bidang_usaha',
        'alamat',
        'kota',
        'kode_pos',
        'provinsi',
        'negara',
        'no_telp',
        'email',
        'status_mitra'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
