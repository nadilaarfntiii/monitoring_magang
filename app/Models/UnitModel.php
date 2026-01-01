<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitModel extends Model
{
    protected $table            = 'unit';
    protected $primaryKey       = 'id_unit';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'id_unit',
        'id_mitra',
        'nama_unit',
        'nama_pembimbing',
        'jabatan',
        'no_hp',
        'email',
        'status_unit'
    ];

    // Timestamps (opsional kalau tabel punya created_at & updated_at)
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';


    public function getUnitWithMitra()
    {
        return $this->db->table($this->table)
            ->select('unit.id_unit, unit.nama_unit, mitra.nama_mitra')
            ->join('mitra', 'mitra.id_mitra = unit.id_mitra', 'left')
            ->get()
            ->getResultArray();
    }

    public function getDetailUnit($id)
    {
        return $this->db->table($this->table)
            ->select('unit.*, mitra.nama_mitra')
            ->join('mitra', 'mitra.id_mitra = unit.id_mitra', 'left')
            ->where('unit.id_unit', $id)
            ->get()
            ->getRowArray();
    }

}
