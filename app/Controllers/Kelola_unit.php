<?php

namespace App\Controllers;

use App\Models\UnitModel;
use App\Models\MitraModel;

class Kelola_unit extends BaseController
{
    protected $unitModel;
    protected $mitraModel;

    public function __construct()
    {
        $this->unitModel = new UnitModel();
        $this->mitraModel = new MitraModel();
    }

    public function index()
    {
        // ambil unit join dengan mitra
        $data['unit'] = $this->unitModel
            ->select('unit.*, mitra.nama_mitra')
            ->join('mitra', 'mitra.id_mitra = unit.id_mitra', 'left')
            ->findAll();

        $data['user_name'] = $this->getUserName();
        // untuk select di form
        $data['mitra'] = $this->mitraModel->findAll();

        return view('admin/kelola_unit', $data);
    }

    public function detail($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'ID Unit tidak ditemukan'
            ]);
        }

        $unit = $this->unitModel
            ->withDeleted() // tambahkan ini supaya bisa ambil unit yang sudah dihapus
            ->select('unit.*, mitra.nama_mitra')
            ->join('mitra', 'mitra.id_mitra = unit.id_mitra', 'left')
            ->where('unit.id_unit', $id)
            ->first();

        if (!$unit) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'data'   => $unit
        ]);
    }


    public function simpanAjax()
    {
        if ($this->request->isAJAX()) {
            $data = [
                'id_mitra'       => $this->request->getPost('id_mitra'),
                'nama_unit'      => $this->request->getPost('nama_unit'),
                'nama_pembimbing'=> $this->request->getPost('nama_pembimbing'),
                'jabatan'        => $this->request->getPost('jabatan'),
                'no_hp'          => $this->request->getPost('no_hp'),
                'email'          => $this->request->getPost('email'),
            ];

            $this->unitModel->insert($data);

            if ($this->unitModel->db->affectedRows() > 0) {
                session()->setFlashdata('success', 'Unit berhasil ditambahkan');
                return $this->response->setJSON(['status' => true]);
            } else {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Gagal menyimpan data',
                    'errors'  => $this->unitModel->errors() ?: $this->unitModel->db->error()
                ]);
            }
        }
    }

    public function updateAjax()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id_unit');

            if (!$id) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'ID Unit tidak ditemukan'
                ]);
            }

            $data = [
                'id_mitra'       => $this->request->getPost('id_mitra'),
                'nama_unit'      => $this->request->getPost('nama_unit'),
                'nama_pembimbing'=> $this->request->getPost('nama_pembimbing'),
                'jabatan'        => $this->request->getPost('jabatan'),
                'no_hp'          => $this->request->getPost('no_hp'),
                'email'          => $this->request->getPost('email'),
            ];

            if ($this->unitModel->update($id, $data)) {
                session()->setFlashdata('success', 'Unit berhasil diupdate');
                return $this->response->setJSON([
                    'status'  => true,
                    'message' => 'Data unit berhasil diupdate'
                ]);
            } else {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Gagal update data',
                    'errors'  => $this->unitModel->errors() ?: $this->unitModel->db->error()
                ]);
            }
        }
    }

    public function hapusAjax()
    {
        $id = $this->request->getPost('id_unit');

        if (!$id) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'ID Unit tidak ditemukan.'
            ]);
        }

        $unit = $this->unitModel->find($id);
        if (!$unit) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data unit tidak ditemukan.'
            ]);
        }

        if ($this->unitModel->delete($id)) {
            session()->setFlashdata('success', "Unit {$unit['nama_unit']} berhasil dihapus.");
            return $this->response->setJSON([
                'status'  => true,
                'message' => "Unit {$unit['nama_unit']} berhasil dihapus."
            ]);
        }

        return $this->response->setJSON([
            'status'  => false,
            'message' => 'Gagal menghapus unit.'
        ]);
    }

    public function arsip()
    {
        $data['user_name'] = $this->getUserName();

        $data['unit'] = $this->unitModel
            ->onlyDeleted()
            ->select('unit.*, mitra.nama_mitra')
            ->join('mitra', 'mitra.id_mitra = unit.id_mitra', 'left')
            ->findAll();

        return view('admin/arsip_unit', $data);
    }

    //Restore
    public function restoreAjax()
    {
        $id = $this->request->getPost('id_unit');
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID unit tidak ditemukan.'
            ]);
        }

        // cari unit, termasuk yang sudah dihapus
        $unit = $this->unitModel->withDeleted()->find($id);
        if (!$unit) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data unit tidak ditemukan.'
            ]);
        }

        // ambil mitra terkait
        $mitra = $this->mitraModel->withDeleted()->find($unit['id_mitra']);
        if (!$mitra) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data mitra tidak ditemukan.'
            ]);
        }

        // ❌ Jika mitra nonaktif, hentikan restore
        if ($mitra['status_mitra'] !== 'Aktif') {
            return $this->response->setJSON([
                'status'  => 'warning',
                'message' => "Unit tidak bisa direstore karena mitra {$mitra['nama_mitra']} Nonaktif."
            ]);
        }

        // ✅ Jika mitra aktif, lakukan restore unit
        $restored = $this->unitModel
            ->protect(false)
            ->withDeleted()
            ->update($id, [
                'deleted_at'  => null,
                'status_unit' => 'Aktif'
            ]);

        if ($restored) {
            session()->setFlashdata('success', "Unit {$unit['nama_unit']} berhasil direstore.");
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => "Unit {$unit['nama_unit']} berhasil direstore."
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal merestore unit.'
        ]);
    }


    }
