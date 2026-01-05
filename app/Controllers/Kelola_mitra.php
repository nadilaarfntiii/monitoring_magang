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
        if (!$this->request->isAJAX()) {
            return;
        }

        $namaMitra = trim($this->request->getPost('nama_mitra'));
        $kota      = trim($this->request->getPost('kota'));

        // 🔍 CEK DUPLIKAT (nama + kota)
        $cekMitra = $this->mitraModel
            ->where('LOWER(nama_mitra)', strtolower($namaMitra))
            ->where('LOWER(kota)', strtolower($kota))
            ->where('deleted_at', null) // penting kalau pakai soft delete
            ->first();

        if ($cekMitra) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Mitra dengan nama dan kota yang sama sudah terdaftar.'
            ]);
        }

        // ✅ DATA AMAN → LANJUT SIMPAN
        $data = [
            'nama_mitra'   => $namaMitra,
            'bidang_usaha' => $this->request->getPost('bidang_usaha'),
            'alamat'       => $this->request->getPost('alamat'),
            'kota'         => $kota,
            'kode_pos'     => $this->request->getPost('kode_pos'),
            'provinsi'     => $this->request->getPost('provinsi'),
            'negara'       => $this->request->getPost('negara'),
            'no_telp'      => $this->request->getPost('no_telp'),
            'email'        => $this->request->getPost('email'),
            'status_mitra' => $this->request->getPost('status_mitra'),
        ];

        $this->mitraModel->insert($data);

        if ($this->mitraModel->db->affectedRows() > 0) {
            session()->setFlashdata('success', 'Mitra berhasil ditambahkan');
            return $this->response->setJSON(['status' => true]);
        }

        return $this->response->setJSON([
            'status'  => false,
            'message' => 'Gagal menyimpan data'
        ]);
    }


    public function updateAjax()
    {
        if (!$this->request->isAJAX()) {
            return;
        }

        $id = $this->request->getPost('id_mitra');

        if (!$id) {
            session()->setFlashdata('error', 'ID Mitra tidak ditemukan.');
            return $this->response->setJSON(['status' => false]);
        }

        $namaMitra = trim($this->request->getPost('nama_mitra'));
        $kota      = trim($this->request->getPost('kota'));

        // 🔍 CEK DUPLIKAT (KECUALI ID SENDIRI)
        $cekDuplikat = $this->mitraModel
            ->where('LOWER(nama_mitra)', strtolower($namaMitra))
            ->where('LOWER(kota)', strtolower($kota))
            ->where('id_mitra !=', $id) // ⬅️ KUNCI UTAMA
            ->where('deleted_at', null)
            ->first();

        if ($cekDuplikat) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Mitra dengan nama dan kota yang sama sudah terdaftar.'
            ]);
        }

        // ✅ DATA AMAN → UPDATE
        $data = [
            'nama_mitra'   => $namaMitra,
            'bidang_usaha' => $this->request->getPost('bidang_usaha'),
            'alamat'       => $this->request->getPost('alamat'),
            'kota'         => $kota,
            'kode_pos'     => $this->request->getPost('kode_pos'),
            'provinsi'     => $this->request->getPost('provinsi'),
            'negara'       => $this->request->getPost('negara'),
            'no_telp'      => $this->request->getPost('no_telp'),
            'email'        => $this->request->getPost('email'),
            'status_mitra' => $this->request->getPost('status_mitra'),
        ];

        if ($this->mitraModel->update($id, $data)) {
            session()->setFlashdata('success', 'Mitra berhasil diupdate.');
            return $this->response->setJSON(['status' => true]);
        }

        session()->setFlashdata('error', 'Gagal mengupdate data mitra.');
        return $this->response->setJSON(['status' => false]);
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

    public function importExcel()
    {
        $file = $this->request->getFile('file_excel');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'File Excel tidak valid'
            ]);
        }

        $ext = strtolower($file->getExtension());
        if (!in_array($ext, ['xls', 'xlsx'])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Format file harus .xls atau .xlsx'
            ]);
        }

        try {
            $reader = $ext === 'xls'
                ? new \PhpOffice\PhpSpreadsheet\Reader\Xls()
                : new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

            $spreadsheet = $reader->load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

            $inserted = 0;
            $skipped  = 0;

            for ($i = 1; $i < count($sheet); $i++) {

                $row = $sheet[$i];

                $namaMitra = trim($row[0] ?? '');
                $bidang    = trim($row[1] ?? '');
                $kota      = trim($row[2] ?? '');

                if ($namaMitra === '' || $kota === '') {
                    $skipped++;
                    continue;
                }

                // cek duplikat
                $cek = $this->mitraModel
                    ->where('nama_mitra', $namaMitra)
                    ->where('kota', $kota)
                    ->where('deleted_at', null)
                    ->first();

                if ($cek) {
                    $skipped++;
                    continue;
                }

                $data = [
                    'nama_mitra'   => $namaMitra,
                    'bidang_usaha' => $bidang,
                    'alamat'       => '-',
                    'kota'         => $kota,
                    'kode_pos'     => '-',
                    'provinsi'     => '-',
                    'negara'       => 'Indonesia',
                    'no_telp'      => '-',
                    'email'        => '-',
                    'status_mitra' => 'Aktif'
                ];

                if ($this->mitraModel->insert($data) !== false) {
                    $inserted++;
                } else {
                    $skipped++;
                }
            }

            return $this->response->setJSON([
                'status'  => true,
                'message' => "Import selesai. Berhasil: {$inserted}, Terlewat: {$skipped}"
            ]);

        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }



}