<?php

namespace App\Models;

use CodeIgniter\Model;

class KomponenNilaiModel extends Model
{
    protected $table      = 'komponen_nilai';
    protected $primaryKey = 'id_nilai';

    protected $allowedFields = [
        'kode_mk',
        'id_program',
        'komponen',
        'presentase',
        'role',
    ];

    // Timestamp otomatis
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
 