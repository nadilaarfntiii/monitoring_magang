<?php

namespace App\Controllers;

use App\Models\ProfilMagangModel;
use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\MitraModel;
use App\Models\UnitModel;
use App\Models\ProgramMagangModel;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ProfilMagang extends BaseController
{
    protected $profilMagangModel;
    protected $mahasiswaModel;
    protected $dosenModel;
    protected $mitraModel;
    protected $unitModel;
    protected $programMagangModel;

    public function __construct()
    {
        $this->profilMagangModel = new ProfilMagangModel();
        $this->mahasiswaModel = new MahasiswaModel();
        $this->dosenModel = new DosenModel();
        $this->mitraModel = new MitraModel();
        $this->unitModel = new UnitModel();
        $this->programMagangModel = new ProgramMagangModel();
    }

    public function index()
    {
        $data = [
            'profil'    => $this->profilMagangModel->getAllProfil(),
            'mahasiswa' => $this->mahasiswaModel->findAll(),
            'dosen'     => $this->dosenModel->findAll(),
            'mitra'     => $this->mitraModel->findAll(),
            'unit'      => $this->unitModel->findAll(),
            'program'   => $this->programMagangModel->findAll(),
            'user_name' => $this->getUserName()
        ];

        return view('admin/kelola_profil_magang', $data);
    }

    public function detail($id)
    {
        $data = $this->profilMagangModel
        ->withDeleted() // 🔹 ini penting
        ->select('
            profil_magang.*,
            mahasiswa.nama_lengkap,
            dosen.nama_lengkap AS nama_dosen,
            mitra.nama_mitra,
            unit.nama_unit,
            unit.nama_pembimbing,
            unit.jabatan,
            unit.no_hp,
            unit.email,
            program_magang.nama_program,
            profil_magang.semester,
            profil_magang.tahun_ajaran,
            profil_magang.keterangan
        ')
        ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
        ->join('dosen', 'dosen.nppy = profil_magang.nppy', 'left')
        ->join('mitra', 'mitra.id_mitra = profil_magang.id_mitra', 'left')
        ->join('unit', 'unit.id_unit = profil_magang.id_unit', 'left')
        ->join('program_magang', 'program_magang.id_program = profil_magang.id_program', 'left')
        ->where('profil_magang.id_profil', $id)
        ->first();

        if ($data) {
            // jika unit tidak ada (NULL), tetap tampil "-"
            $data['nama_pembimbing'] = $data['nama_pembimbing'] ?? '-';
            $data['jabatan']         = $data['jabatan'] ?? '-';
            $data['no_hp']           = $data['no_hp'] ?? '-';
            $data['email']           = $data['email'] ?? '-';

            return $this->response->setJSON($data);
        } else {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data profil magang tidak ditemukan'
            ]);
        }
    }


    public function simpanAjax()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'nim' => 'required',
            'nppy' => 'required',
            'id_mitra' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $validation->getErrors()
            ]);
        }

        $data = [
            'nim' => $this->request->getPost('nim'),
            'nppy' => $this->request->getPost('nppy'),
            'id_mitra' => $this->request->getPost('id_mitra'),
            'id_unit' => $this->request->getPost('id_unit') ?: null,
            'id_program' => $this->request->getPost('id_program') ?: null,
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai') ?: null,
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai') ?: null,
            'status' => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan') ?: 'Baru', // ✅ Tambahan
        ];

        $profilModel = new \App\Models\ProfilMagangModel();
        $profilModel->insert($data);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data profil magang berhasil disimpan'
        ]);
    }


    // 🔍 Cari Mahasiswa
    public function searchMahasiswa()
    {
        $query = $this->request->getGet('q');
        if (!$query) return $this->response->setJSON([]);

        $data = $this->mahasiswaModel->like('nama_lengkap', $query)
                                    ->orLike('nim', $query)
                                    ->findAll(10);

        $results = [];
        foreach ($data as $m) {
            $results[] = [
                'label' => $m['nim'] . ' - ' . $m['nama_lengkap'],
                'value' => $m['nim'] . ' - ' . $m['nama_lengkap'],
                'id'    => $m['nim']
            ];
        }

        return $this->response->setJSON($results);
    }

    // 🔍 Cari Dosen
    public function searchDosen()
    {
        $query = $this->request->getGet('q');
        if (!$query) return $this->response->setJSON([]);

        $data = $this->dosenModel->like('nama_lengkap', $query)
                                ->orLike('nppy', $query)
                                ->findAll(10);

        $results = [];
        foreach ($data as $d) {
            $results[] = [
                'label' => $d['nppy'] . ' - ' . $d['nama_lengkap'],
                'value' => $d['nppy'] . ' - ' . $d['nama_lengkap'],
                'id'    => $d['nppy']
            ];
        }

        return $this->response->setJSON($results);
    }

    // 🔍 Cari Mitra
    public function searchMitra()
    {
        $query = $this->request->getGet('q');
        if (!$query) return $this->response->setJSON([]);

        $data = $this->mitraModel->like('nama_mitra', $query)->findAll(10);

        $results = [];
        foreach ($data as $m) {
            $results[] = [
                'label' => $m['nama_mitra'],
                'value' => $m['nama_mitra'],
                'id'    => $m['id_mitra']
            ];
        }

        return $this->response->setJSON($results);
    }

    // 🔍 Cari Unit
    public function searchUnit()
    {
        $query = $this->request->getGet('q');
        $id_mitra = $this->request->getGet('id_mitra'); // ambil id mitra

        if (!$query && !$id_mitra) {
            return $this->response->setJSON([]);
        }

        $builder = $this->unitModel->select('unit.id_unit, unit.nama_unit')
                                ->where('unit.id_mitra', $id_mitra);

        if ($query) {
            $builder->like('unit.nama_unit', $query);
        }

        $data = $builder->findAll(10);

        $results = [];
        foreach ($data as $u) {
            $results[] = [
                'label' => $u['nama_unit'],
                'value' => $u['nama_unit'],
                'id'    => $u['id_unit']
            ];
        }

        return $this->response->setJSON($results);
    }


    public function updateAjax()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'id_profil' => 'required',
            'nppy'      => 'required',
            'id_mitra'  => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $validation->getErrors()
            ]);
        }

        $id = $this->request->getPost('id_profil');
        $data = [
            'nppy'            => $this->request->getPost('nppy'),
            'id_mitra'        => $this->request->getPost('id_mitra'),
            'id_unit'         => $this->request->getPost('id_unit') ?: null,
            'id_program'      => $this->request->getPost('id_program') ?: null,
            'tanggal_mulai'   => $this->request->getPost('tanggal_mulai') ?: null,
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai') ?: null,
            'status'          => $this->request->getPost('status') ?: 'aktif',
            'keterangan'      => $this->request->getPost('keterangan') ?: 'Baru',
        ];

        try {
            $this->profilMagangModel->update($id, $data);

            // 🔹 Tambahkan flashdata untuk pesan sukses
            session()->setFlashdata('success', 'Data profil magang berhasil diperbarui.');

            return $this->response->setJSON([
                'status'   => 'success',
                'message'  => 'Data profil magang berhasil diperbarui.',
                'redirect' => base_url('kelola_profil_magang')
            ]);
        } catch (\Exception $e) {
            // 🔹 Tambahkan flashdata juga untuk pesan error
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function hapusAjax($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID tidak ditemukan.'
            ]);
        }

        try {
            // Ubah status jadi tidak aktif sebelum soft delete
            $this->profilMagangModel->update($id, ['status' => 'tidak aktif']);

            // Soft delete data
            $deleted = $this->profilMagangModel->delete($id);

            if ($deleted) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Data profil magang berhasil diarsipkan (soft delete).'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal menghapus data.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // Halaman Arsip Profil Magang
    public function arsip()
    {
        $data['user_name'] = $this->getUserName();

        $data['profil'] = $this->profilMagangModel
            ->select('profil_magang.*, mahasiswa.nama_lengkap, mitra.nama_mitra, dosen.nama_lengkap AS nama_dosen, program_magang.nama_program')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
            ->join('mitra', 'mitra.id_mitra = profil_magang.id_mitra', 'left')
            ->join('dosen', 'dosen.nppy = profil_magang.nppy', 'left')
            ->join('program_magang', 'program_magang.id_program = profil_magang.id_program', 'left')
            ->onlyDeleted()
            ->findAll();

        return view('admin/arsip_profil_magang', $data);
    }

    // ✅ Restore Profil Magang
    public function restoreAjax()
    {
        $id = $this->request->getPost('id_profil');

        if (!$id) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ID profil magang tidak ditemukan.'
            ]);
        }

        $profil = $this->profilMagangModel->withDeleted()->find($id);
        if (!$profil) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data profil magang tidak ditemukan.'
            ]);
        }

        // Update status & hapus deleted_at
        $restored = $this->profilMagangModel
            ->protect(false)
            ->withDeleted()
            ->update($id, [
                'status'     => 'aktif',
                'deleted_at' => null
            ]);

        if ($restored) {
            session()->setFlashdata('success', "Profil magang {$profil['nim']} berhasil direstore.");
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => "Profil magang {$profil['nim']} berhasil direstore."
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Gagal merestore data profil magang.'
        ]);
    }

    /**
     * Helper konversi tanggal Excel
    */

    private function parseExcelDate($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Jika numeric → Excel Date (misal: 45161)
        if (is_numeric($value)) {
            return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
        }

        // Jika string
        $value = trim($value);
        $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d'];

        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $value);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }

        return null;
    }

    public function importExcel()
    {
        $file = $this->request->getFile('file_excel');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'File tidak valid'
            ]);
        }

        $ext = $file->getClientExtension();
        $reader = $ext === 'xls' ? new Xls() : new Xlsx();

        try {
            $spreadsheet = $reader->load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();

            $highestRow = $sheet->getHighestRow();

            $berhasil = 0;
            $gagal = 0;

            for ($row = 2; $row <= $highestRow; $row++) {

                $nama_mahasiswa = trim((string) $sheet->getCell("A{$row}")->getValue());
                if ($nama_mahasiswa === '') continue;

                $nama_dospem    = trim((string) $sheet->getCell("B{$row}")->getValue());
                $nama_mitra     = trim((string) $sheet->getCell("C{$row}")->getValue());
                $program_magang = trim((string) $sheet->getCell("D{$row}")->getValue());

                // 🔑 AMBIL DATE LANGSUNG DARI CELL
                $tgl_mulai_raw   = $sheet->getCell("E{$row}")->getValue();
                $tgl_selesai_raw = $sheet->getCell("F{$row}")->getValue();

                $status     = trim((string) $sheet->getCell("G{$row}")->getValue());
                $keterangan = trim((string) $sheet->getCell("H{$row}")->getValue());

                $tgl_mulai   = $this->parseExcelDate($tgl_mulai_raw);
                $tgl_selesai = $this->parseExcelDate($tgl_selesai_raw);

                if (!$tgl_mulai || !$tgl_selesai) {
                    $gagal++;
                    continue;
                }

                $mahasiswa = $this->mahasiswaModel->where('nama_lengkap', $nama_mahasiswa)->first();
                $dosen     = $this->dosenModel->where('nama_lengkap', $nama_dospem)->first();
                $mitra     = $this->mitraModel->where('nama_mitra', $nama_mitra)->first();
                $program   = $this->programMagangModel->where('nama_program', $program_magang)->first();

                if (!$mahasiswa || !$dosen || !$mitra || !$program) {
                    $gagal++;
                    continue;
                }

                $unit = $this->unitModel->where('id_mitra', $mitra['id_mitra'])->first();

                $data = [
                    'nim'             => $mahasiswa['nim'],
                    'nppy'            => $dosen['nppy'],
                    'id_mitra'        => $mitra['id_mitra'],
                    'id_unit'         => $unit['id_unit'] ?? null,
                    'id_program'      => $program['id_program'],
                    'tanggal_mulai'   => $tgl_mulai,
                    'tanggal_selesai' => $tgl_selesai,
                    'status'          => $status ?: 'aktif',
                    'keterangan'      => $keterangan ?: 'Baru',
                ];

                $this->profilMagangModel->insert($data);

                if ($this->profilMagangModel->errors()) {
                    $gagal++;
                } else {
                    $berhasil++;
                }
            }

            return $this->response->setJSON([
                'status'  => true,
                'message' => "Import selesai! Berhasil: {$berhasil}, Gagal: {$gagal}"
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


}
