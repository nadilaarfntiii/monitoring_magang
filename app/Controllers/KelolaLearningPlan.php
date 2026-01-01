<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LearningPlanModel;
use App\Models\ProfilMagangModel;
use App\Models\AktivitasPembelajaranModel;
use App\Models\JamKerjaUnitModel;

class KelolaLearningPlan extends BaseController
{
    protected $learningPlanModel;
    protected $profilMagangModel;
    protected $aktivitasModel;
    protected $jamKerjaModel;
    protected $session;

    public function __construct()
    {
        $this->learningPlanModel = new LearningPlanModel();
        $this->profilMagangModel = new ProfilMagangModel();
        $this->aktivitasModel = new AktivitasPembelajaranModel();
        $this->jamKerjaModel = new JamKerjaUnitModel();
        $this->session = session();
    }

    // ==========================
    // 🔹 TAMPILKAN DAFTAR LEARNING PLAN UNTUK MITRA
    // ==========================
    public function index()
    {
        // 🔐 Hanya role mitra yang boleh mengakses
        if ($this->session->get('role') !== 'mitra') {
            return redirect()->to('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $idUnit = $this->session->get('id_unit');

        if (!$idUnit) {
            return redirect()->to('/login')->with('error', 'ID unit tidak ditemukan. Silakan login kembali.');
        }

        // 🔹 Ambil hanya data learning plan milik unit mitra yang login
        $learningPlans = $this->learningPlanModel
            ->select('
                learning_plan.*, 
                mahasiswa.nama_lengkap, 
                mahasiswa.program_studi, 
                profil_magang.nim, 
                profil_magang.tanggal_mulai, 
                profil_magang.tanggal_selesai
            ')
            ->join('profil_magang', 'profil_magang.id_profil = learning_plan.id_profil')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
            ->where('profil_magang.id_unit', $idUnit)
            ->whereNotIn('learning_plan.status_approval_pembimbing', ['Draft'])
            ->whereNotIn('learning_plan.status_approval_kaprodi', ['Draft'])
            ->orderBy("
                CASE 
                    WHEN learning_plan.status_approval_pembimbing = 'Menunggu' THEN 1 
                    ELSE 2 
                END
            ", 'ASC', false)
            ->orderBy('learning_plan.id_lp', 'ASC')
            ->findAll();

        return view('mitra/kelola_learning_plan', [
            'learningPlans' => $learningPlans,
            /* 'user_name' => $this->getUserName(), */
        ]);
    }


    // ==========================
    // 🔹 DETAIL LEARNING PLAN UNTUK MITRA
    // ==========================
    public function detail($id_lp)
    {
        // 🔐 Cegah akses non-mitra
        if ($this->session->get('role') !== 'mitra') {
            return redirect()->to('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $idUnitLogin = $this->session->get('id_unit');

        $learningPlan = $this->learningPlanModel
            ->select('learning_plan.*, profil_magang.nim, profil_magang.id_unit')
            ->join('profil_magang', 'profil_magang.id_profil = learning_plan.id_profil')
            ->where('learning_plan.id_lp', $id_lp)
            ->first();

        if (!$learningPlan) {
            return redirect()->back()->with('error', 'Learning Plan tidak ditemukan.');
        }

        // 🚫 Pastikan mitra hanya bisa melihat LP yang sesuai unit-nya
        if ($learningPlan['id_unit'] != $idUnitLogin) {
            return redirect()->to('/mitra/dashboard')->with('error', 'Anda tidak memiliki izin untuk melihat data ini.');
        }

        // 🚫 Jangan tampilkan jika masih draft
        if (
            ($learningPlan['status_approval_pembimbing'] ?? 'Draft') === 'Draft' ||
            ($learningPlan['status_approval_kaprodi'] ?? 'Draft') === 'Draft'
        ) {
            return redirect()->to('/mitra/kelola_learning_plan')->with('error', 'Learning Plan belum disubmit oleh mahasiswa.');
        }

        // 🔹 Ambil profil lengkap
        $profil = $this->profilMagangModel->getProfilFull($learningPlan['nim']);
        if (!$profil) {
            return redirect()->back()->with('error', 'Profil magang tidak ditemukan.');
        }

        $profil['semester'] = $this->hitungSemester($profil['angkatan'] ?? null);

        // 🔹 Ambil aktivitas & jam kerja
        $aktivitas = $this->aktivitasModel->where('id_lp', $learningPlan['id_lp'])->findAll();
        $jamKerjaList = $this->jamKerjaModel->where('id_unit', $profil['id_unit'])->findAll();
        $jamKerjaText = $this->formatJamKerja($jamKerjaList);

        $data = [
            'title' => 'Detail Learning Plan',
            'profil' => $profil,
            'learningPlan' => $learningPlan,
            'aktivitas' => $aktivitas,
            'jamKerja' => $jamKerjaText,
            'isReadonly' => true
        ];

        // 🔹 Tentukan view berdasarkan program
        switch ($profil['id_program']) {
            case 2:
                $view = 'mitra/detail_learning_plan_mengajar';
                break;
            case 3:
                $view = 'mitra/detail_learning_plan_adopsi';
                break;
            default:
                $view = 'mitra/detail_learning_plan';
        }

        return view($view, $data);
    }

    // ==========================
    // 🔹 SETUJUI LEARNING PLAN UNTUK MITRA
    // ==========================
    public function setuju($id)
    {
        if ($this->session->get('role') !== 'mitra') {
            return redirect()->to('/login')->with('error', 'Anda tidak memiliki akses.');
        }

        $learningPlan = $this->learningPlanModel
            ->select('learning_plan.*, profil_magang.id_unit')
            ->join('profil_magang', 'profil_magang.id_profil = learning_plan.id_profil')
            ->find($id);

        if (!$learningPlan || $learningPlan['id_unit'] != $this->session->get('id_unit')) {
            return redirect()->to('/mitra/dashboard')->with('error', 'Data tidak ditemukan atau Anda tidak berhak mengubahnya.');
        }

        $this->learningPlanModel->update($id, [
            'status_approval_pembimbing' => 'Disetujui',
            'tanggal_approval_pembimbing' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/mitra/kelola_learning_plan')->with('success', 'Learning Plan berhasil disetujui.');
    }

    // ==========================
    // 🔹 TOLAK LEARNING PLAN UNTUK MITRA
    // ==========================
    public function tolak($id)
    {
        if ($this->session->get('role') !== 'mitra') {
            return redirect()->to('/login')->with('error', 'Anda tidak memiliki akses.');
        }

        $learningPlan = $this->learningPlanModel
            ->select('learning_plan.*, profil_magang.id_unit')
            ->join('profil_magang', 'profil_magang.id_profil = learning_plan.id_profil')
            ->find($id);

        if (!$learningPlan || $learningPlan['id_unit'] != $this->session->get('id_unit')) {
            return redirect()->to('/mitra/dashboard')->with('error', 'Data tidak ditemukan atau Anda tidak berhak menolak.');
        }

        $catatan = $this->request->getPost('catatan_pembimbing');

        $this->learningPlanModel->update($id, [
            'status_approval_pembimbing' => 'Ditolak',
            'tanggal_approval_pembimbing' => date('Y-m-d H:i:s'),
            'catatan_pembimbing' => $catatan
        ]);

        return redirect()->to('/mitra/kelola_learning_plan')->with('error', 'Learning Plan telah ditolak.');
    }


    // ===============================
    // 🔹 DETAIL LEARNING PLAN MAHASISWA UNTUK MITRA
    // ===============================
    public function Detailmhs($nim)
    {
        // 🔐 Pastikan role mitra
        if ($this->session->get('role') !== 'mitra') {
            return redirect()->to('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $idUnitLogin = $this->session->get('id_unit');

        // 1️⃣ Ambil profil mahasiswa
        $profil = $this->profilMagangModel->where('nim', $nim)->first();
        if (!$profil) {
            return redirect()->back()->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        // 🚫 Pastikan mahasiswa dari unit mitra
        if ($profil['id_unit'] != $idUnitLogin) {
            return redirect()->to('/mitra/dashboard')->with('error', 'Anda tidak berhak melihat mahasiswa ini.');
        }

        // 2️⃣ Ambil Learning Plan
        $learningPlan = $this->learningPlanModel
            ->where('id_profil', $profil['id_profil'])
            ->first();

        if (!$learningPlan) {
            return redirect()->back()->with('error', 'Learning Plan mahasiswa belum dibuat.');
        }

        // 🚫 Jangan tampilkan jika masih draft
        if (
            ($learningPlan['status_approval_pembimbing'] ?? 'Draft') === 'Draft' ||
            ($learningPlan['status_approval_kaprodi'] ?? 'Draft') === 'Draft'
        ) {
            return redirect()->back()->with('error', 'Learning Plan belum disubmit oleh mahasiswa.');
        }

        // 3️⃣ Ambil data lengkap profil
        $profilFull = $this->profilMagangModel->getProfilFull($nim);
        $profilFull['semester'] = $this->hitungSemester($profilFull['angkatan'] ?? null);

        // 4️⃣ Ambil aktivitas pembelajaran
        $aktivitas = $this->aktivitasModel->where('id_lp', $learningPlan['id_lp'])->findAll();

        // 5️⃣ Ambil jam kerja unit
        $jamKerjaList = $this->jamKerjaModel->where('id_unit', $profilFull['id_unit'])->findAll();
        $jamKerjaText = $this->formatJamKerja($jamKerjaList);

        // 6️⃣ Siapkan data untuk view
        $data = [
            'profil' => $profilFull,
            'learningPlan' => $learningPlan,
            'aktivitas' => $aktivitas,
            'jamKerja' => $jamKerjaText,
            'isReadonly' => true
        ];

        // 7️⃣ Tentukan view berdasarkan program
        switch ($profilFull['id_program']) {
            case 2:
                $view = 'mitra/detail_learning_plan_mengajar';
                break;
            case 3:
                $view = 'mitra/detail_learning_plan_adopsi';
                break;
            default:
                $view = 'mitra/detail_learning_plan';
        }

        return view($view, $data);
    }



    // ===============================
    // 🔹 KAPRODI INDEX - Tampilkan semua mahasiswa aktif magang semester ini
    // ===============================
    public function kaprodiIndex()
    {
        // ===========================
        // 🔹 Tentukan Prodi Kaprodi
        // ===========================
        $jabatan = $this->session->get('jabatan_fungsional');
        $prodiFilter = null;

        if (stripos($jabatan, 'Kaprodi Sistem Informasi') !== false) {
            $prodiFilter = 'Sistem Informasi';
        } elseif (stripos($jabatan, 'Kaprodi Teknik Informatika') !== false) {
            $prodiFilter = 'Teknik Informatika';
        } else {
            return redirect()->to('/login')->with('error', 'Anda bukan Kaprodi.');
        }

        // ===========================
        // 🔹 Ambil semester & tahun ajaran aktif dari DB
        // ===========================
        $periodeAktif = $this->profilMagangModel
            ->select('semester, tahun_ajaran')
            ->where('status', 'aktif')
            ->where('deleted_at', null)
            ->orderBy('tanggal_mulai', 'DESC')
            ->first();

        if (!$periodeAktif) {
            return view('kaprodi/kelola_learning_plan', [
                'learningPlans' => [],
                'user_name'     => $this->getUserName(),
            ]);
        }

        $semester    = $periodeAktif['semester'];
        $tahunAjaran = $periodeAktif['tahun_ajaran'];

        // ===========================
        // 🔹 Query Learning Plan
        // ===========================
        $learningPlans = $this->learningPlanModel
            ->select('
                learning_plan.*, 
                mahasiswa.nama_lengkap, 
                mahasiswa.program_studi, 
                profil_magang.nim, 
                profil_magang.tanggal_mulai, 
                profil_magang.tanggal_selesai,
                profil_magang.semester,
                profil_magang.tahun_ajaran
            ')
            ->join('profil_magang', 'profil_magang.id_profil = learning_plan.id_profil')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
            ->where('profil_magang.status', 'aktif')
            ->where('profil_magang.semester', $semester)
            ->where('profil_magang.tahun_ajaran', $tahunAjaran)
            ->where('mahasiswa.program_studi', $prodiFilter)
            // ❗ hanya tampil selain Draft
            ->whereNotIn('learning_plan.status_approval_pembimbing', ['Draft'])
            ->whereNotIn('learning_plan.status_approval_kaprodi', ['Draft'])
            ->orderBy("
                CASE 
                    WHEN learning_plan.status_approval_kaprodi = 'Menunggu' THEN 1
                    ELSE 2
                END
            ", 'ASC', false)
            ->orderBy('learning_plan.id_lp', 'ASC')
            ->findAll();

        return view('kaprodi/kelola_learning_plan', [
            'learningPlans' => $learningPlans,
            'semester'      => $semester,
            'tahun_ajaran'  => $tahunAjaran,
            'user_name'     => $this->getUserName(),
        ]);
    }


    // ===============================
    // 🔹 KAPRODI DETAIL - Detail Learning Plan
    // ===============================
    public function kaprodiDetail($id_lp)
    {
        $learningPlan = $this->learningPlanModel
            ->select('learning_plan.*, profil_magang.nim')
            ->join('profil_magang', 'profil_magang.id_profil = learning_plan.id_profil')
            ->where('learning_plan.id_lp', $id_lp)
            ->first();

        if (!$learningPlan) {
            return redirect()->back()->with('error', 'Learning Plan tidak ditemukan.');
        }

        $profil = $this->profilMagangModel->getProfilFull($learningPlan['nim']);
        if (!$profil) {
            return redirect()->back()->with('error', 'Profil magang tidak ditemukan.');
        }

        $profil['semester'] = $this->hitungSemester($profil['angkatan'] ?? null);

        $aktivitas = $this->aktivitasModel
            ->where('id_lp', $learningPlan['id_lp'])
            ->findAll();

        $jamKerjaList = $this->jamKerjaModel
            ->where('id_unit', $profil['id_unit'])
            ->findAll();

        $jamKerjaText = $this->formatJamKerja($jamKerjaList);

        // ✅ Siapkan semua data yang akan dikirim ke view
        $data = [
            'profil' => $profil,
            'learningPlan' => $learningPlan,
            'aktivitas' => $aktivitas,
            'jamKerja' => $jamKerjaText,
            'user_name' => $this->getUserName(),
        ];

        // ✅ Tentukan view berdasarkan id_program
        switch ($profil['id_program']) {
            case 2:
                $view = 'kaprodi/detail_learning_plan_mengajar';
                break;
            case 3:
                $view = 'kaprodi/detail_learning_plan_adopsi';
                break;
            default:
                $view = 'kaprodi/detail_learning_plan';
                break;
        }

        return view($view, $data);
    }


    // ===============================
    // 🔹 KAPRODI SETUJUI LEARNING PLAN
    // ===============================
    public function kaprodiSetuju($id_lp)
    {
        $lp = $this->learningPlanModel->find($id_lp);
        if (!$lp) {
            return redirect()->to('kaprodi/kelola_learning_plan')->with('error', 'Learning Plan tidak ditemukan.');
        }

        $this->learningPlanModel->update($id_lp, [
            'status_approval_kaprodi' => 'Disetujui',
            'tanggal_approval_kaprodi' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('kaprodi/kelola_learning_plan')->with('success', 'Learning Plan berhasil disetujui.');
    }

    // ===============================
    // 🔹 KAPRODI TOLAK LEARNING PLAN
    // ===============================
    public function kaprodiTolak($id_lp)
    {
        $lp = $this->learningPlanModel->find($id_lp);
        if (!$lp) {
            return redirect()->to('kaprodi/kelola_learning_plan')->with('error', 'Learning Plan tidak ditemukan.');
        }

        $catatan = $this->request->getPost('catatan_kaprodi');

        $this->learningPlanModel->update($id_lp, [
            'status_approval_kaprodi' => 'Ditolak',
            'tanggal_approval_kaprodi' => date('Y-m-d H:i:s'),
            'catatan_kaprodi' => $catatan
        ]);

        return redirect()->to('kaprodi/kelola_learning_plan')->with('error', 'Learning Plan telah ditolak.');
    }

    // ===============================
    // 🔹 DETAIL LEARNING PLAN MAHASISWA (DARI DROPDOWN KAPRODI)
    // ===============================
    public function kaprodiDetailmhs($nim)
    {
        // 1️⃣ Cari profil berdasarkan NIM
        $profil = $this->profilMagangModel->where('nim', $nim)->first();

        if (!$profil) {
            return redirect()->back()->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        // 2️⃣ Cari Learning Plan berdasarkan ID profil
        $learningPlan = $this->learningPlanModel
            ->where('id_profil', $profil['id_profil'])
            ->first();

        if (!$learningPlan) {
            return redirect()->back()->with('error', 'Learning Plan mahasiswa belum dibuat.');
        }

        // 🚫 Tambahkan pengecekan status approval (jika masih Draft)
        if (
            ($learningPlan['status_approval_pembimbing'] ?? 'Draft') === 'Draft' ||
            ($learningPlan['status_approval_kaprodi'] ?? 'Draft') === 'Draft'
        ) {
            return redirect()->back()->with('error', 'Learning Plan belum disubmit oleh mahasiswa.');
        }

        // 3️⃣ Ambil data lengkap profil
        $profilFull = $this->profilMagangModel->getProfilFull($nim);
        $profilFull['semester'] = $this->hitungSemester($profilFull['angkatan'] ?? null);

        // 4️⃣ Ambil aktivitas pembelajaran (berdasarkan id_lp)
        $aktivitas = $this->aktivitasModel
            ->where('id_lp', $learningPlan['id_lp'])
            ->findAll();

        // 5️⃣ Ambil jam kerja unit (berdasarkan id_unit dari profil)
        $jamKerjaList = $this->jamKerjaModel
            ->where('id_unit', $profilFull['id_unit'])
            ->findAll();

        $jamKerjaText = $this->formatJamKerja($jamKerjaList);

        // 6️⃣ Siapkan data untuk dikirim ke view
        $data = [
            'profil' => $profilFull,
            'learningPlan' => $learningPlan,
            'aktivitas' => $aktivitas,
            'jamKerja' => $jamKerjaText,
            'isReadonly' => true // ✅ karena kaprodi hanya melihat
        ];

        // ✅ Tentukan view berdasarkan id_program
        switch ($profilFull['id_program']) {
            case 2:
                $view = 'kaprodi/detail_learning_plan_mengajar';
                break;
            case 3:
                $view = 'kaprodi/detail_learning_plan_adopsi';
                break;
            default:
                $view = 'kaprodi/detail_learning_plan';
        }

        // 7️⃣ Tampilkan view yang sesuai
        return view($view, $data);
    }
    
    // ===============================
    // HELPER: Hitung Semester 
    // ===============================
    private function hitungSemester($angkatan)
    {
        if (empty($angkatan)) return '-';
        $angkatan = (int) substr($angkatan, 0, 4);
        $tahunSekarang = date('Y');
        $bulanSekarang = date('n');
        $selisihTahun = $tahunSekarang - $angkatan;
        return ($bulanSekarang >= 9 || $bulanSekarang <= 2)
            ? ($selisihTahun * 2) + 1
            : ($selisihTahun * 2) + 2;
    }

    // ===============================
    // HELPER: Format Jam Kerja 
    // ===============================
    private function formatJamKerja($jamKerjaList)
    {
        if (empty($jamKerjaList)) {
            return '-';
        }

        $hariKerja = array_filter($jamKerjaList, fn($j) => strtolower($j['status_hari']) === 'kerja');

        if (empty($hariKerja)) {
            return '-';
        }

        $urutanHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        usort($hariKerja, function ($a, $b) use ($urutanHari) {
            return array_search($a['hari'], $urutanHari) <=> array_search($b['hari'], $urutanHari);
        });

        $grup = [];
        foreach ($hariKerja as $row) {
            $key = "{$row['jam_masuk']}-{$row['jam_pulang']}";
            $grup[$key][] = $row['hari'];
        }

        $hasil = [];
        foreach ($grup as $key => $days) {
            [$masuk, $pulang] = explode('-', $key);
            $masuk = substr($masuk, 0, 5);
            $pulang = substr($pulang, 0, 5);
            $hasil[] = (count($days) > 1)
                ? reset($days) . ' – ' . end($days) . " {$masuk} – {$pulang}"
                : $days[0] . " {$masuk} – {$pulang}";
        }

        return implode("\n", $hasil);
    }


    // ===============================
    // 🔹 DOSPEM INDEX - Tampilkan semua mahasiswa aktif magang semester ini
    // ===============================
    public function dospemIndex()
    {
        // Pastikan login sebagai dospem
        if (
            !$this->session->get('isLoggedIn') ||
            $this->session->get('role') !== 'dospem'
        ) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // NPPY dospem
        $nppy = $this->session->get('username');

        // 🔹 Ambil semester & tahun ajaran dari database
        $profilAktif = $this->profilMagangModel
            ->select('semester, tahun_ajaran')
            ->where('nppy', $nppy)
            ->where('status', 'aktif')
            ->orderBy('tanggal_mulai', 'DESC')
            ->first();

        // Jika belum ada mahasiswa aktif
        if (!$profilAktif) {
            return view('dospem/data_learning_plan', [
                'learningPlans' => [],
                'semester' => null,
                'tahun_ajaran' => null,
                'user_name' => $this->getUserName(),
                'foto' => $this->getUserFoto(),
            ]);
        }

        $semester    = $profilAktif['semester'];
        $tahunAjaran = $profilAktif['tahun_ajaran'];

        // 🔹 Query Learning Plan mahasiswa bimbingan dospem
        $learningPlans = $this->learningPlanModel
            ->select('
                learning_plan.*, 
                mahasiswa.nim,
                mahasiswa.nama_lengkap,
                mahasiswa.program_studi,
                profil_magang.tanggal_mulai,
                profil_magang.tanggal_selesai,
                profil_magang.status,
                profil_magang.semester,
                profil_magang.tahun_ajaran
            ')
            ->join('profil_magang', 'profil_magang.id_profil = learning_plan.id_profil')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
            ->where('profil_magang.nppy', $nppy)
            ->where('profil_magang.status', 'aktif')
            ->where('profil_magang.semester', $semester)
            ->where('profil_magang.tahun_ajaran', $tahunAjaran)
            ->whereNotIn('learning_plan.status_approval_pembimbing', ['Draft'])
            ->whereNotIn('learning_plan.status_approval_kaprodi', ['Draft'])
            ->orderBy("
                CASE 
                    WHEN learning_plan.status_approval_pembimbing = 'Menunggu' THEN 1
                    ELSE 2
                END
            ", 'ASC', false)
            ->orderBy('learning_plan.id_lp', 'ASC')
            ->findAll();

        return view('dospem/data_learning_plan', [
            'learningPlans' => $learningPlans,
            'semester' => $semester,
            'tahun_ajaran' => $tahunAjaran,
            'user_name' => $this->getUserName(),
            'foto' => $this->getUserFoto(),
        ]);
    }


    // ===============================
    // 🔹 DOSPEM DETAIL - Detail Learning Plan
    // ===============================
    public function dospemDetail($id_lp)
    {
        $learningPlan = $this->learningPlanModel
            ->select('learning_plan.*, profil_magang.nim')
            ->join('profil_magang', 'profil_magang.id_profil = learning_plan.id_profil')
            ->where('learning_plan.id_lp', $id_lp)
            ->first();

        if (!$learningPlan) {
            return redirect()->back()->with('error', 'Learning Plan tidak ditemukan.');
        }

        $profil = $this->profilMagangModel->getProfilFull($learningPlan['nim']);
        if (!$profil) {
            return redirect()->back()->with('error', 'Profil magang tidak ditemukan.');
        }

        $profil['semester'] = $this->hitungSemester($profil['angkatan'] ?? null);

        $aktivitas = $this->aktivitasModel
            ->where('id_lp', $learningPlan['id_lp'])
            ->findAll();

        $jamKerjaList = $this->jamKerjaModel
            ->where('id_unit', $profil['id_unit'])
            ->findAll();

        $jamKerjaText = $this->formatJamKerja($jamKerjaList);

        // ✅ Kumpulkan semua data ke dalam satu array
        $data = [
            'profil' => $profil,
            'learningPlan' => $learningPlan,
            'aktivitas' => $aktivitas,
            'jamKerja' => $jamKerjaText,
            'user_name' => $this->getUserName(), 
            'foto'      => $this->getUserFoto(), 
        ];

        // ✅ Tentukan view berdasarkan id_program
        switch ($profil['id_program']) {
            case 2:
                $view = 'dospem/detail_learning_plan_mengajar';
                break;
            case 3:
                $view = 'dospem/detail_learning_plan_adopsi';
                break;
            default:
                $view = 'dospem/detail_learning_plan';
        }

        return view($view, $data);
    }


    // ===============================
    // 🔹 DETAIL LEARNING PLAN MAHASISWA (DARI DROPDOWN DOSPEM)
    // ===============================
    public function dospemDetailmhs($nim)
    {
        // 1️⃣ Cari profil berdasarkan NIM
        $profil = $this->profilMagangModel->where('nim', $nim)->first();

        if (!$profil) {
            return redirect()->back()->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        // 2️⃣ Cari Learning Plan berdasarkan ID profil
        $learningPlan = $this->learningPlanModel
            ->where('id_profil', $profil['id_profil'])
            ->first();

        if (!$learningPlan) {
            return redirect()->back()->with('error', 'Learning Plan mahasiswa belum dibuat.');
        }

        // 🚫 Tambahkan pengecekan status approval (jika masih Draft)
        if (
            ($learningPlan['status_approval_pembimbing'] ?? 'Draft') === 'Draft' ||
            ($learningPlan['status_approval_kaprodi'] ?? 'Draft') === 'Draft'
        ) {
            return redirect()->back()->with('error', 'Learning Plan belum disubmit oleh mahasiswa.');
        }

        // 3️⃣ Ambil data lengkap profil
        $profilFull = $this->profilMagangModel->getProfilFull($nim);
        $profilFull['semester'] = $this->hitungSemester($profilFull['angkatan'] ?? null);

        // 4️⃣ Ambil aktivitas pembelajaran (berdasarkan id_lp)
        $aktivitas = $this->aktivitasModel
            ->where('id_lp', $learningPlan['id_lp'])
            ->findAll();

        // 5️⃣ Ambil jam kerja unit (berdasarkan id_unit dari profil)
        $jamKerjaList = $this->jamKerjaModel
            ->where('id_unit', $profilFull['id_unit'])
            ->findAll();

        $jamKerjaText = $this->formatJamKerja($jamKerjaList);

        // 6️⃣ Siapkan data untuk dikirim ke view
        $data = [
            'profil' => $profilFull,
            'learningPlan' => $learningPlan,
            'aktivitas' => $aktivitas,
            'jamKerja' => $jamKerjaText,
            'isReadonly' => true,
            'user_name' => $this->getUserName(), 
            'foto'      => $this->getUserFoto(),  
        ];

        // ✅ Tentukan view berdasarkan id_program
        switch ($profilFull['id_program']) {
            case 2:
                $view = 'dospem/detail_learning_plan_mengajar';
                break;
            case 3:
                $view = 'dospem/detail_learning_plan_adopsi';
                break;
            default:
                $view = 'dospem/detail_learning_plan';
        }

        // 7️⃣ Tampilkan view yang sesuai
        return view($view, $data);
    }


}
