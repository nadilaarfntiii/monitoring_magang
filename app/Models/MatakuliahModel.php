<?php

namespace App\Models;

use CodeIgniter\Model;

class MatakuliahModel extends Model
{
    protected $table            = 'mata_kuliah';
    protected $primaryKey       = 'kode_mk';
    protected $returnType       = 'array';
    protected $useAutoIncrement = false;

    protected $allowedFields = [
        'kode_mk',
        'nama_mk',
        'sks',
        'tipe',
        'deskripsi',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil semua mata kuliah aktif
     */
    public function getActive()
    {
        return $this->where('status', 'aktif')->findAll();
    }

    /**
     * Ambil semua mata kuliah berdasarkan tipe (Teori, Praktik, Magang)
     */
    public function getByTipe($tipe)
    {
        return $this->where('tipe', $tipe)
                    ->where('status', 'aktif')
                    ->findAll();
    }

    /**
     * Ambil detail mata kuliah berdasarkan kode
     */
    public function getDetail($kode_mk)
    {
        return $this->where('kode_mk', $kode_mk)->first();
    }
}
