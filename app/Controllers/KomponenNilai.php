<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MatakuliahModel;
use App\Models\KomponenNilaiModel;
use App\Models\ProgramMagangModel;

class KomponenNilai extends BaseController
{
    protected $matakuliahModel;
    protected $komponenNilaiModel;
    protected $programMagangModel;

    public function __construct()
    {
        $this->matakuliahModel = new MatakuliahModel();
        $this->komponenNilaiModel = new KomponenNilaiModel();
        $this->programMagangModel = new ProgramMagangModel();
    }

    /**
     * Menampilkan halaman komponen_penilaian
     */
    public function index()
    {
        $kodeMk = $this->request->getGet('kode_mk');
        $idProgram = $this->request->getGet('id_program');

        $data['user_name'] = $this->getUserName();
        $data['mata_kuliah'] = $this->matakuliahModel->getActive();
        $data['program_magang'] = $this->programMagangModel->findAll(); // 🔹

        // Ambil data komponen nilai
        $query = $this->komponenNilaiModel
            ->select('komponen_nilai.*, mata_kuliah.nama_mk, program_magang.nama_program')
            ->join('mata_kuliah', 'mata_kuliah.kode_mk = komponen_nilai.kode_mk')
            ->join('program_magang', 'program_magang.id_program = komponen_nilai.id_program', 'left');

        if ($kodeMk) {
            $query->where('komponen_nilai.kode_mk', $kodeMk);
        }

        if ($idProgram) {
            $query->where('komponen_nilai.id_program', $idProgram); 
        }

        $data['komponen_nilai'] = $query->findAll();

        return view('kaprodi/komponen_penilaian', $data);
    }

    /**
     * Simpan data komponen nilai
     */
    public function save()
    {
        if ($this->request->isAJAX()) {
            $kode_mk    = $this->request->getPost('kode_mk');
            $id_program = $this->request->getPost('id_program');
            $id_program = ($id_program === '' ? null : $id_program);
            $komponen   = $this->request->getPost('komponen');
            $presentase = $this->request->getPost('presentase');
            $role       = $this->request->getPost('role');

            // =========================
            // NORMALISASI ROLE
            // =========================
            if (!in_array($role, ['dospem', 'mitra', 'kaprodi'])) {
                $role = 'dospem';
            }

            if ($kode_mk !== 'BB010') {
                $role = 'dospem';
            }

            if ($role === 'kaprodi') {
                $id_program = null;
            }

            // =========================
            // VALIDASI DASAR
            // =========================
            if (empty($kode_mk) || empty($komponen) || empty($presentase)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data tidak lengkap.'
                ]);
            }

            if ($role !== 'kaprodi' && empty($id_program)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Program magang wajib dipilih.'
                ]);
            }

            if (count($komponen) !== count($presentase)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data komponen dan presentase tidak cocok.'
                ]);
            }

            if (array_sum($presentase) != 100) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Total presentase harus 100%.'
                ]);
            }

            // =========================
            // TRANSAKSI
            // =========================
            try {
                $db = \Config\Database::connect();
                $db->transStart();

                foreach ($komponen as $i => $kom) {
                    $data = [
                        'kode_mk'    => $kode_mk,
                        'id_program' => $id_program,
                        'komponen'   => trim($kom),
                        'presentase' => (int) $presentase[$i],
                        'role'       => $role,
                    ];

                    $this->komponenNilaiModel->insert($data);
                }

                $db->transComplete();

                if ($db->transStatus() === false) {
                    throw new \Exception('Transaksi database gagal.');
                }

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Komponen nilai berhasil disimpan.'
                ]);

            } catch (\Throwable $e) {
                log_message('error', $e->getMessage());
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat menyimpan ke database: ' . $e->getMessage()
                ]);
            }
        }

        return redirect()->back();
    }

    public function updateGroup()
    {
        $kode_mk    = $this->request->getPost('kode_mk');
        $id_program = $this->request->getPost('id_program');
        $id_program = ($id_program === '' ? null : $id_program);
        $role       = $this->request->getPost('role');
        $komponen   = $this->request->getPost('komponen');
        $presentase = $this->request->getPost('presentase');

        // =====================
        // NORMALISASI ROLE
        // =====================
        if (!in_array($role, ['dospem', 'mitra', 'kaprodi'])) {
            $role = 'dospem';
        }

        if ($kode_mk !== 'BB010') {
            $role = 'dospem';
        }

        if ($role === 'kaprodi') {
            $id_program = null;
        }

        // =====================
        // FILTER DATA KOSONG
        // =====================
        $dataInsert = [];
        foreach ($komponen as $i => $kom) {

            if (!isset($presentase[$i])) {
                continue;
            }

            if (trim($kom) === '') {
                continue;
            }

            $dataInsert[] = [
                'kode_mk'    => $kode_mk,
                'id_program' => $role === 'kaprodi' ? null : $id_program,
                'komponen'   => trim($kom),
                'presentase' => (int) $presentase[$i],
                'role'       => $role,
            ];
        }


        // =====================
        // VALIDASI
        // =====================
        if (empty($kode_mk) || empty($dataInsert)) {
            return redirect()->back()->with('error', 'Data tidak lengkap.');
        }

        if ($role !== 'kaprodi' && empty($id_program)) {
            return redirect()->back()->with('error', 'Program magang wajib dipilih.');
        }

        if (array_sum(array_column($dataInsert, 'presentase')) != 100) {
            return redirect()->back()->with('error', 'Total presentase harus 100%.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // =====================
            // DELETE DATA LAMA (SATU GROUP)
            // =====================
            $builder = $this->komponenNilaiModel
                ->where('kode_mk', $kode_mk)
                ->where('role', $role);

            if ($role === 'kaprodi') {
                $builder->where('id_program IS NULL', null, false);
            } else {
                $builder->where('id_program', $id_program);
            }

            $builder->delete();

            // =====================
            // INSERT DATA BARU (TERMAsuk FIELD ADD)
            // =====================
            $this->komponenNilaiModel->insertBatch($dataInsert);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal memperbarui data.');
            }

            return redirect()->back()->with('success', 'Komponen nilai berhasil diperbarui.');

        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan.');
        }
    }


    public function deleteGroup()
    {
        $kode_mk    = $this->request->getPost('kode_mk');
        $id_program = $this->request->getPost('id_program');
        $id_program = ($id_program === '' ? null : $id_program);
        $role       = $this->request->getPost('role');

        // =====================
        // NORMALISASI ROLE
        // =====================
        if (!in_array($role, ['dospem', 'mitra', 'kaprodi'])) {
            $role = 'dospem';
        }

        if ($kode_mk !== 'BB010') {
            $role = 'dospem';
        }

        if ($role === 'kaprodi') {
            $id_program = null;
        }

        // =====================
        // VALIDASI
        // =====================
        if (empty($kode_mk)) {
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }

        if ($role !== 'kaprodi' && empty($id_program)) {
            return redirect()->back()->with('error', 'Program magang tidak valid.');
        }

        try {
            $builder = $this->komponenNilaiModel
                ->where('kode_mk', $kode_mk)
                ->where('role', $role);

            // =====================
            // KONDISI KHUSUS KAPRODI
            // =====================
            if ($role === 'kaprodi') {
                $builder->where('id_program IS NULL', null, false);
            } else {
                $builder->where('id_program', $id_program);
            }

            $builder->delete();

            return redirect()->back()->with('success', 'Komponen nilai berhasil dihapus.');

        } catch (\Throwable $e) {
            log_message('error', $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }



}
