<?php

namespace App\Models;

use CodeIgniter\Model;

class NilaiMagangModel extends Model
{
    protected $table            = 'nilai_magang';
    protected $primaryKey       = 'id_nilai_magang';

    protected $returnType       = 'array';

    protected $allowedFields = [
        'id_nilai_magang',
        'id_profil',
        'id_nilai',
        'nilai',
        'nilai_akhir',
        'role'
    ];

    // Handle created_at & updated_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function cekStatus($idProfil, $kodeMK, $role)
    {
        $diisi = $this->where('id_profil', $idProfil)
                    ->like('id_nilai', $kodeMK, 'after')
                    ->where('role', $role)
                    ->countAllResults();

        return $diisi > 0; // kalau ada minimal 1, centang
    }

}
