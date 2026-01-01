<?php

namespace App\Models;

use CodeIgniter\Model;

class LearningPlanModel extends Model
{
    protected $table      = 'learning_plan';
    protected $primaryKey = 'id_lp';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_profil',
        'deskripsi_pekerjaan',
        'capaian_magang',
        'capaian_mata_kuliah',
        'nama_kegiatan',
        'pelaksana_kegiatan',
        'uraian_kegiatan',
        'metode_media',
        'rtl_kegiatan',
        'situation',
        'task',
        'action',
        'result',
        'status_approval_pembimbing',
        'catatan_pembimbing',
        'status_approval_kaprodi',
        'catatan_kaprodi'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Ambil LP lengkap termasuk data profil magang
    public function getAllLP()
    {
        return $this->select('learning_plan.*, profil_magang.nim, profil_magang.nppy, profil_magang.id_mitra, profil_magang.id_unit, profil_magang.id_program')
                    ->join('profil_magang', 'profil_magang.id_profil = learning_plan.id_profil', 'left')
                    ->findAll();
    }

    // Ambil LP berdasarkan id_lp
    public function getLPById($id_lp)
    {
        return $this->select('learning_plan.*, profil_magang.nim, profil_magang.nppy, profil_magang.id_mitra, profil_magang.id_unit, profil_magang.id_program')
                    ->join('profil_magang', 'profil_magang.id_profil = learning_plan.id_profil', 'left')
                    ->where('learning_plan.id_lp', $id_lp)
                    ->first();
    }
}
