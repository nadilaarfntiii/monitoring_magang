<?php
namespace App\Controllers;

use App\Models\ProfilMagangModel;
use App\Models\NilaiMagangModel;
use App\Models\KomponenNilaiModel;

class KelolaPenilaian extends BaseController
{
    protected $profilMagangModel;
    protected $session;

    public function __construct()
    {
        $this->profilMagangModel = new ProfilMagangModel();
        $this->nilaiMagangModel  = new NilaiMagangModel();
        $this->session = session(); 
    }

    public function index()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'dospem') {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // NPPY dospem dari session
        $nppy = $this->session->get('username');

        // 🔹 Ambil semester & tahun ajaran AKTIF dari database
        $profilAktif = $this->profilMagangModel
            ->select('semester, tahun_ajaran')
            ->where('nppy', $nppy)
            ->where('status', 'aktif')
            ->where('deleted_at', null)
            ->orderBy('tanggal_mulai', 'DESC')
            ->first();

        // Kalau belum ada mahasiswa aktif
        if (!$profilAktif) {
            return view('dospem/kelola_penilaian', [
                'mahasiswa'    => [],
                'semester'     => null,
                'tahun_ajaran' => null
            ]);
        }

        $semester    = $profilAktif['semester'];
        $tahunAjaran = $profilAktif['tahun_ajaran'];

        // 🔹 Ambil mahasiswa bimbingan sesuai data DB
        $data['mahasiswa'] = $this->profilMagangModel
            ->select('
                profil_magang.id_profil,
                mahasiswa.nim,
                mahasiswa.nama_lengkap,
                mitra.nama_mitra,
                unit.nama_unit,
                profil_magang.status
            ')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim', 'left')
            ->join('mitra', 'mitra.id_mitra = profil_magang.id_mitra', 'left')
            ->join('unit', 'unit.id_unit = profil_magang.id_unit', 'left')
            ->where('profil_magang.nppy', $nppy)
            ->where('profil_magang.status', 'aktif')
            ->where('profil_magang.semester', $semester)
            ->where('profil_magang.tahun_ajaran', $tahunAjaran)
            ->where('profil_magang.deleted_at', null)
            ->orderBy('profil_magang.tanggal_mulai', 'DESC')
            ->findAll();

        $data['semester']      = $semester;
        $data['tahun_ajaran']  = $tahunAjaran;

        return view('dospem/kelola_penilaian', $data);
    }


    public function inputNilaiDospem($nim)
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'dospem') {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $profil = $this->profilMagangModel->getProfilFull($nim);

        if (!$profil) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $komponenModel = new \App\Models\KomponenNilaiModel();

        // Ambil komponen nilai + nama MK
        $komponen = $komponenModel
            ->select('komponen_nilai.*, mata_kuliah.nama_mk')
            ->join('mata_kuliah', 'mata_kuliah.kode_mk = komponen_nilai.kode_mk', 'left')
            ->where('komponen_nilai.id_program', $profil['id_program'])
            ->where('komponen_nilai.role', 'dospem')
            ->findAll();

        // Ambil list Kode MK (distinct)
        $listKodeMK = $komponenModel
            ->select('komponen_nilai.kode_mk, mata_kuliah.nama_mk')
            ->join('mata_kuliah', 'mata_kuliah.kode_mk = komponen_nilai.kode_mk', 'left')
            ->where('komponen_nilai.id_program', $profil['id_program'])
            ->where('komponen_nilai.role', 'dospem')
            ->groupBy('komponen_nilai.kode_mk')
            ->findAll();


        // =======================================================
        // AMBIL NILAI YANG SUDAH TERSIMPAN
        // =======================================================
            $nilaiTersimpan = $this->nilaiMagangModel
            ->where('id_profil', $profil['id_profil'])
            ->where('role', 'dospem')
            ->findAll();

        // Format agar mudah dipakai di view: $nilaiTersimpan[id_nilai]['nilai']
        $nilaiTersimpan = array_column($nilaiTersimpan, null, 'id_nilai');

        // ===========================
        // HITUNG TOTAL NILAI AKHIR PER MK
        // ===========================
        $totalPerMK = [];

        foreach ($komponen as $k) {

            $mk = $k['kode_mk'];
            $id_nilai = $k['id_nilai'];

            if (!isset($totalPerMK[$mk])) {
                $totalPerMK[$mk] = 0;
            }

            if (isset($nilaiTersimpan[$id_nilai])) {
                $totalPerMK[$mk] += floatval($nilaiTersimpan[$id_nilai]['nilai_akhir']);
            }
        }

        // KIRIM DATA KE VIEW — SEMUA DI SATU ARRAY
        $data = [
            'mahasiswa'      => $profil,
            'komponen'       => $komponen,
            'listKodeMK'     => $listKodeMK,
            'nilaiTersimpan' => $nilaiTersimpan,
            'totalPerMK'     => $totalPerMK
        ];

        return view('dospem/input_nilai_magang', $data);
    }



    // ================================================
    // SIMPAN / UPDATE NILAI MAGANG DARI FORM
    // ================================================
    public function simpanNilaiMagang($nim)
    {
        // Ambil profil mahasiswa
        $profil = $this->profilMagangModel->getProfilFull($nim);
    
        if (!$profil) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }
    
        // Ambil input nilai
        $nilaiInput = $this->request->getPost('nilai'); // array: id_nilai => nilai
        if (!$nilaiInput) {
            return redirect()->back()->with('error', 'Tidak ada nilai yang dikirim.');
        }
    
        $komponenModel = new \App\Models\KomponenNilaiModel();
    
        // Tanggal hari ini
        $tgl = date('dmY');
    
        foreach ($nilaiInput as $id_nilai => $nilai) {
    
            if ($nilai === null || $nilai === '') continue;
    
            // Validasi nilai 0–100
            $nilai = max(0, min(100, floatval($nilai)));
    
            // Jika id_nilai tidak valid → skip
            if (empty($id_nilai) || empty($profil['id_profil'])) {
                continue;
            }
    
            // Buat ID unik
            $id_nilai_magang = $profil['id_profil'] . '_' . $id_nilai . '_' . $tgl;
    
            // Cek apakah data sudah ada (update)
            $cek = $this->nilaiMagangModel
                ->where('id_profil', $profil['id_profil'])
                ->where('id_nilai', $id_nilai)
                ->where('role', 'dospem')
                ->first();
    
            // Data dasar
            $data = [
                'id_nilai_magang' => $id_nilai_magang,
                'id_profil'       => $profil['id_profil'],
                'id_nilai'        => $id_nilai,
                'nilai'           => $nilai,
                'role'            => 'dospem',
            ];
    
            if (!$cek) {
                // INSERT
                $this->nilaiMagangModel->insert($data);
                $idToUpdate = $id_nilai_magang;
            } else {
                // UPDATE
                $idToUpdate = $cek['id_nilai_magang'];
                $data['id_nilai_magang'] = $cek['id_nilai_magang'];
                $data['updated_at'] = date('Y-m-d H:i:s');
    
                $this->nilaiMagangModel->update($cek['id_nilai_magang'], $data);
            }
    
            // ===============================
            // HITUNG NILAI AKHIR PER KOMPONEN
            // ===============================
    
            $komponen = $komponenModel
            ->where('id_nilai', $id_nilai)
            ->where('role', 'dospem') // <-- pastikan role sesuai
            ->first();
    
            if ($komponen) {
                $nilai_akhir = $nilai;
    
                // Update nilai akhir per komponen
                $this->nilaiMagangModel->update($idToUpdate, [
                    'nilai_akhir' => $nilai_akhir
                ]);
            }
        }
    
        return redirect()->back()->with('success', 'Nilai berhasil disimpan.');
    }
    
    // ==================================================
    // HALAMAN PENILAIAN DARI MITRA
    // ==================================================
    public function indexMitra()
    {
        if (
            !$this->session->get('isLoggedIn') ||
            $this->session->get('role') !== 'mitra'
        ) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil id_unit dari session
        $idUnit = $this->session->get('id_unit');

        /**
         * ==================================================
         * 🔹 Ambil semester & tahun ajaran dari DB
         * ==================================================
         */
        $profilAktif = $this->profilMagangModel
            ->select('semester, tahun_ajaran')
            ->where('id_unit', $idUnit)
            ->where('status', 'aktif')
            ->orderBy('tanggal_mulai', 'DESC')
            ->first();

        if (!$profilAktif) {
            return redirect()->back()->with('error', 'Tidak ada mahasiswa magang aktif.');
        }

        $semester     = $profilAktif['semester'];
        $tahunAjaran  = $profilAktif['tahun_ajaran'];

        /**
         * ==================================================
         * 🔹 Ambil data mahasiswa bimbingan mitra
         * ==================================================
         */
        $data['mahasiswa'] = $this->profilMagangModel
            ->select('
                profil_magang.id_profil,
                mahasiswa.nim,
                mahasiswa.nama_lengkap,
                mitra.nama_mitra,
                unit.nama_unit,
                profil_magang.status
            ')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim', 'left')
            ->join('mitra', 'mitra.id_mitra = profil_magang.id_mitra', 'left')
            ->join('unit', 'unit.id_unit = profil_magang.id_unit', 'left')
            ->where('profil_magang.id_unit', $idUnit)
            ->where('profil_magang.semester', $semester)
            ->where('profil_magang.tahun_ajaran', $tahunAjaran)
            ->orderBy('profil_magang.tanggal_mulai', 'DESC')
            ->findAll();

        $data['semester']      = $semester;
        $data['tahun_ajaran']  = $tahunAjaran;

        return view('mitra/kelola_penilaian', $data);
    }


    public function inputNilaiMitra($nim)
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'mitra') {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $profil = $this->profilMagangModel->getProfilFull($nim);

        if (!$profil) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $komponenModel = new \App\Models\KomponenNilaiModel();

        // Ambil komponen nilai + nama MK
        $komponen = $komponenModel
            ->select('komponen_nilai.*, mata_kuliah.nama_mk')
            ->join('mata_kuliah', 'mata_kuliah.kode_mk = komponen_nilai.kode_mk', 'left')
            ->where('komponen_nilai.id_program', $profil['id_program'])
            ->where('komponen_nilai.role', 'mitra')
            ->findAll();

        // Ambil list Kode MK (distinct)
        $listKodeMK = $komponenModel
            ->select('komponen_nilai.kode_mk, mata_kuliah.nama_mk')
            ->join('mata_kuliah', 'mata_kuliah.kode_mk = komponen_nilai.kode_mk', 'left')
            ->where('komponen_nilai.id_program', $profil['id_program'])
            ->where('komponen_nilai.role', 'mitra')
            ->groupBy('komponen_nilai.kode_mk')
            ->findAll();


        // =======================================================
        // AMBIL NILAI YANG SUDAH TERSIMPAN
        // =======================================================
            $nilaiTersimpan = $this->nilaiMagangModel
            ->where('id_profil', $profil['id_profil'])
            ->where('role', 'mitra')
            ->findAll();

        // Format agar mudah dipakai di view: $nilaiTersimpan[id_nilai]['nilai']
        $nilaiTersimpan = array_column($nilaiTersimpan, null, 'id_nilai');

        // ===========================
        // HITUNG TOTAL NILAI AKHIR PER MK
        // ===========================
        $totalPerMK = [];

        foreach ($komponen as $k) {

            $mk = $k['kode_mk'];
            $id_nilai = $k['id_nilai'];

            if (!isset($totalPerMK[$mk])) {
                $totalPerMK[$mk] = 0;
            }

            if (isset($nilaiTersimpan[$id_nilai])) {
                $totalPerMK[$mk] += floatval($nilaiTersimpan[$id_nilai]['nilai_akhir']);
            }
        }

        // KIRIM DATA KE VIEW — SEMUA DI SATU ARRAY
        $data = [
            'mahasiswa'      => $profil,
            'komponen'       => $komponen,
            'listKodeMK'     => $listKodeMK,
            'nilaiTersimpan' => $nilaiTersimpan,
            'totalPerMK'     => $totalPerMK
        ];

        return view('mitra/input_nilai_magang', $data);
    }



    // ================================================
    // SIMPAN / UPDATE NILAI MAGANG DARI FORM
    // ================================================
    public function simpanNilaiMagangMitra($nim)
    {
        // Ambil profil mahasiswa
        $profil = $this->profilMagangModel->getProfilFull($nim);
    
        if (!$profil) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }
    
        // Ambil input nilai
        $nilaiInput = $this->request->getPost('nilai'); // array: id_nilai => nilai
        if (!$nilaiInput) {
            return redirect()->back()->with('error', 'Tidak ada nilai yang dikirim.');
        }
    
        $komponenModel = new \App\Models\KomponenNilaiModel();
    
        // Tanggal hari ini
        $tgl = date('dmY');
    
        foreach ($nilaiInput as $id_nilai => $nilai) {
    
            if ($nilai === null || $nilai === '') continue;
    
            // Validasi nilai 0–100
            $nilai = max(0, min(100, floatval($nilai)));
    
            // Jika id_nilai tidak valid → skip
            if (empty($id_nilai) || empty($profil['id_profil'])) {
                continue;
            }
    
            // Buat ID unik
            $id_nilai_magang = $profil['id_profil'] . '_' . $id_nilai . '_' . $tgl;
    
            // Cek apakah data sudah ada (update)
            $cek = $this->nilaiMagangModel
                ->where('id_profil', $profil['id_profil'])
                ->where('id_nilai', $id_nilai)
                ->where('role', 'mitra')
                ->first();
    
            // Data dasar
            $data = [
                'id_nilai_magang' => $id_nilai_magang,
                'id_profil'       => $profil['id_profil'],
                'id_nilai'        => $id_nilai,
                'nilai'           => $nilai,
                'role'            => 'mitra',
            ];
    
            if (!$cek) {
                // INSERT
                $this->nilaiMagangModel->insert($data);
                $idToUpdate = $id_nilai_magang;
            } else {
                // UPDATE
                $idToUpdate = $cek['id_nilai_magang'];
                $data['id_nilai_magang'] = $cek['id_nilai_magang'];
                $data['updated_at'] = date('Y-m-d H:i:s');
    
                $this->nilaiMagangModel->update($cek['id_nilai_magang'], $data);
            }
    
            // ===============================
            // HITUNG NILAI AKHIR PER KOMPONEN
            // ===============================
    
            $komponen = $komponenModel->where('id_nilai', $id_nilai)->first();
    
            if ($komponen) {
                $nilai_akhir = $nilai;
    
                // Update nilai akhir per komponen
                $this->nilaiMagangModel->update($idToUpdate, [
                    'nilai_akhir' => $nilai_akhir
                ]);
            }
        }
    
        return redirect()->back()->with('success', 'Nilai berhasil disimpan.');
    }
}
