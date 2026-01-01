<?php

namespace App\Models;

use CodeIgniter\Model;

class AktivitasPembelajaranModel extends Model
{
    protected $table      = 'aktivitas_pembelajaran';
    protected $primaryKey = 'id_aktivitas';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_lp',
        'tipe',
        'kompetensi',
        'kompetensi_teknis',
        'dicentang',
        'urutan'
    ];

    // Tambahan penting agar CI tidak kirim kolom id_aktivitas
    protected $skipValidation = true;
    protected $protectFields = true;
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Ambil semua aktivitas berdasarkan LP
    public function getByLP($id_lp)
    {
        return $this->where('id_lp', $id_lp)
                    ->orderBy('urutan', 'ASC')
                    ->findAll();
    }

    // Ambil satu aktivitas berdasarkan id_aktivitas
    public function getById($id_aktivitas)
    {
        return $this->where('id_aktivitas', $id_aktivitas)
                    ->first();
    }
}
