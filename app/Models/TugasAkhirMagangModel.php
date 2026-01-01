<?php

namespace App\Models;

use CodeIgniter\Model;

class TugasAkhirMagangModel extends Model
{
    protected $table = 'tugas_akhir_magang';
    protected $primaryKey = 'id_ta';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_ta',
        'id_profil',
        'kode_mk',
        'judul_ta',
        'file_ta'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getByProfil($id_profil)
    {
        return $this->where('id_profil', $id_profil)->findAll();
    }

    public function getJudulMagang($id_profil)
    {
        return $this->where('id_profil', $id_profil)
                    ->where('kode_mk', 'BB010')
                    ->first();
    }

}
