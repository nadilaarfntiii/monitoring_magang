<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Models\MitraModel;
use App\Models\UnitModel;

class Kelola_mitra extends BaseController
{
    protected $mitraModel;
    protected $unitModel;

    public function __construct()
    {
        $this->mitraModel = new MitraModel();
        $this->unitModel  = new UnitModel();
    }

    public function index()
    {
        $data['user_name'] = $this->getUserName();
        $data['mitra'] = $this->mitraModel->findAll();
        return view('admin/kelola_mitra', $data);
    }

    public function detail($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'ID Mitra tidak ditemukan'
            ]);
        }

        // gunakan withDeleted agar data arsip juga bisa diambil
        $mitra = $this->mitraModel->withDeleted()->find($id);

        if (!$mitra) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'data'   => $mitra
        ]);
    }

    public function simpanAjax()
    {
        if ($this->request->isAJAX()) {
            $data = [
                'nama_mitra'   => $this->request->getPost('nama_mitra'),
                'bidang_usaha' => $this->request->getPost('bidang_usaha'),
                'alamat'       => $this->request->getPost('alamat'),
                'kota'         => $this->request->getPost('kota'),
                'kode_pos'     => $this->request->getPost('kode_pos'),
                'provinsi'     => $this->request->getPost('provinsi'),
                'negara'       => $this->request->getPost('negara'),
                'no_telp'      => $this->request->getPost('no_telp'),
                'email'        => $this->request->getPost('email'),
                'status_mitra' => $this->request->getPost('status_mitra'),
            ];

            $this->mitraModel->insert($data);

            if ($this->mitraModel->db->affectedRows() > 0) {
                // ✅ berhasil
                session()->setFlashdata('success', 'Mitra berhasil ditambahkan');
                return $this->response->setJSON(['status' => true]);
            } else {
                // ❌ gagal
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Gagal menyimpan data',
                    'errors'  => $this->mitraModel->errors() ?: $this->mitraModel->db->error()
                ]);
            }
        }
    }

    public function updateAjax()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id_mitra');

            if (!$id) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'ID Mitra tidak ditemukan'
                ]);
            }

            $data = [
                'nama_mitra'   => $this->request->getPost('nama_mitra'),
                'bidang_usaha' => $this->request->getPost('bidang_usaha'),
                'alamat'       => $this->request->getPost('alamat'),
                'kota'         => $this->request->getPost('kota'),
                'kode_pos'     => $this->request->getPost('kode_pos'),
                'provinsi'     => $this->request->getPost('provinsi'),
                'negara'       => $this->request->getPost('negara'),
                'no_telp'      => $this->request->getPost('no_telp'),
                'email'        => $this->request->getPost('email'),
                'status_mitra' => $this->request->getPost('status_mitra'),
            ];

            if ($this->mitraModel->update($id, $data)) {
                session()->setFlashdata('success', 'Mitra berhasil diupdate');
                return $this->response->setJSON([
                    'status'  => true,
                    'message' => 'Data mitra berhasil diupdate'
                ]);
            } else {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Gagal update data',
                    'errors'  => $this->mitraModel->errors() ?: $this->mitraModel->db->error()
                ]);
            }
        }
    }

    // Soft Delete Mitra + Unit
    public function hapusAjax()
    {
        $id = $this->request->getPost('id_mitra');

        if (!$id) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ID mitra tidak ditemukan.'
            ]);
        }

        $mitra = $this->mitraModel->find($id);
        if (!$mitra) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data mitra tidak ditemukan.'
            ]);
        }

        // Ubah status mitra jadi Nonaktif dulu
        $this->mitraModel->update($id, ['status_mitra' => 'Nonaktif']);

        // Soft delete mitra
        $deletedMitra = $this->mitraModel->delete($id);

        if ($deletedMitra) {
            // Ubah status semua unit terkait jadi Nonaktif
            $this->unitModel->where('id_mitra', $id)
                ->set(['status_unit' => 'Nonaktif'])
                ->update();

            // Soft delete unit terkait
            $this->unitModel->where('id_mitra', $id)->delete();

            session()->setFlashdata('success', "Mitra {$mitra['nama_mitra']} dan unit terkait berhasil diarsipkan.");
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => "Mitra {$mitra['nama_mitra']} dan unit terkait berhasil diarsipkan."
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Gagal mengarsipkan mitra.'
        ]);
    }


    // Restore Mitra + Unit
    public function restoreAjax()
    {
        $id = $this->request->getPost('id_mitra');

        if (!$id) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ID mitra tidak ditemukan.'
            ]);
        }

        $mitra = $this->mitraModel->withDeleted()->find($id);
        if (!$mitra) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Mitra tidak ditemukan.'
            ]);
        }

        // Restore mitra
        $restored = $this->mitraModel
            ->protect(false)
            ->withDeleted()
            ->update($id, [
                'status_mitra' => 'Aktif',
                'deleted_at'   => null
            ]);

        if ($restored) {
            // Restore semua unit terkait
            $this->unitModel->protect(false)
                ->withDeleted()
                ->where('id_mitra', $id)
                ->set([
                    'status_unit' => 'Aktif',
                    'deleted_at'  => null
                ])
                ->update();

            session()->setFlashdata('success', "Mitra {$mitra['nama_mitra']} dan unit terkait berhasil direstore.");
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => "Mitra {$mitra['nama_mitra']} dan unit terkait berhasil direstore."
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Gagal merestore mitra.'
        ]);
    }

    // Halaman Arsip Mitra
    public function arsip()
    {
        $data['user_name'] = $this->getUserName();

        $data['mitra'] = $this->mitraModel
            ->onlyDeleted()
            ->where('status_mitra', 'Nonaktif')
            ->findAll();

        return view('admin/arsip_mitra', $data);
    }

    // Import Excel
    public function importExcel()
    {
        $file = $this->request->getFile('file_excel');

        if (!$file->isValid()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'File tidak valid.'
            ]);
        }

        $ext = $file->getClientExtension();
        if (!in_array($ext, ['xls', 'xlsx'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Format file harus .xls atau .xlsx.'
            ]);
        }

        try {
            $reader = ($ext === 'xls') ? new Xls() : new Xlsx();
            $spreadsheet = $reader->load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet()->toArray();

            // skip header (mulai dari baris kedua)
            foreach (array_slice($sheet, 1) as $row) {
                if (empty($row[0])) continue; // skip jika nama mitra kosong

                $data = [
                    'nama_mitra'   => $row[0],
                    'bidang_usaha' => $row[1],
                    'kota'         => $row[2],
                ];

                $this->mitraModel->insert($data);
            }

            session()->setFlashdata('success', 'Data mitra berhasil diimport dari Excel');
            return $this->response->setJSON(['status' => true]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Terjadi kesalahan saat membaca file: ' . $e->getMessage()
            ]);
        }
    }
}