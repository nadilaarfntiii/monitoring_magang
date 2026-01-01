<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\DosenModel;
use App\Models\MitraModel;
use App\Models\UnitModel;
use App\Models\ProfilMagangModel;
use App\Models\NilaiMagangModel;
use App\Models\KomponenNilaiModel;

class Kaprodi extends BaseController
{
    protected $session;
    protected $mitraModel;
    protected $unitModel;
    protected $profilMagangModel;
    protected $nilaiModel;
    protected $userModel;
    protected $komponenNilaiModel;

    public function __construct()
    {
        $this->session = session();
        $this->mitraModel = new MitraModel();
        $this->unitModel = new UnitModel();
        $this->profilMagangModel = new ProfilMagangModel();
        $this->nilaiModel = new NilaiMagangModel();
        $this->userModel = new UserModel();
        $this->komponenNilaiModel = new KomponenNilaiModel();
    }

    // 🔹 Dashboard Kaprodi
    public function dashboard()
    {
        // Pastikan user login dan rolenya kaprodi
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'kaprodi') {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Kirim data ke view
        $data = [
            'user_name' => $this->getUserName(),
            'role_name' => 'Kepala Program Studi'
        ];

        return view('kaprodi/dashboard', $data);
    }

    // =============================================
    // PROFIL KAPRODI
    // =============================================
    public function profil()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'kaprodi') {
            return redirect()->to('/login');
        }

        $id = $this->session->get('id_user'); 

        $data['user_name'] = $this->session->get('user_name');
        $data['user'] = $this->userModel->find($id);

        // Ambil NPPY dari username
        $nppy = $data['user']['username'];

        // Ambil data dosen
        $dosenModel = new DosenModel();
        $data['dosen'] = $dosenModel->find($data['user']['nppy']);

        return view('kaprodi/profil', $data);
    }


    // ===============================
    // UPDATE PROFIL KAPRODI
    // ===============================
    public function update_profil()
    {
        // Pastikan login sebagai kaprodi
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'kaprodi') {
            return redirect()->to('/login');
        }

        $id_user = $this->request->getPost('id_user');

        // Ambil user
        $user = $this->userModel->find($id_user);
        $nppy = $user['nppy']; // PK dosen

        $dosenModel = new \App\Models\DosenModel();

        // =======================
        // UPDATE TABEL USER
        // =======================
        $userData = [
            'username' => $this->request->getPost('username')
        ];

        // =======================
        // HANDLE UPLOAD FOTO
        // =======================
        $foto = $this->request->getFile('foto');

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $newName = $foto->getRandomName();
            $foto->move('uploads/foto/', $newName);

            // simpan foto ke tabel user
            $userData['foto'] = $newName;

            // Update session foto
            $this->session->set('foto', $newName);

            // simpan foto ke tabel dosen
            $dosenFoto = $newName;
        } else {
            $dosenFoto = null;
        }

        // Simpan perubahan user
        $this->userModel->skipValidation(true)->update($id_user, $userData);

        // ===========================
        // UPDATE TABEL DOSEN (BIODATA)
        // ===========================
        $dosenData = [
            'jenis_kelamin'       => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir'        => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'       => $this->request->getPost('tanggal_lahir'),
            'alamat'              => $this->request->getPost('alamat'),
            'kota'                => $this->request->getPost('kota'),
            'kode_pos'            => $this->request->getPost('kode_pos'),
            'provinsi'            => $this->request->getPost('provinsi'),
            'negara'              => $this->request->getPost('negara'),
            'agama'               => $this->request->getPost('agama'),
            'email'               => $this->request->getPost('email'),
            'no_hp'               => $this->request->getPost('no_hp')
        ];

        // Jika upload foto berhasil → simpan ke tabel dosen
        if ($dosenFoto !== null) {
            $dosenData['foto'] = $dosenFoto;
        }

        $dosenModel->update($nppy, $dosenData);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }


    //MENAMPILKAN DATA MAHASISWA
    public function mahasiswa()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'kaprodi') {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // ============================
        // 1. Ambil data kaprodi login
        // ============================
        $dosenModel = new \App\Models\DosenModel();
        $nppy = $this->session->get('nppy');

        $kaprodi = $dosenModel->where('nppy', $nppy)->first();
        if (!$kaprodi) {
            return redirect()->back()->with('error', 'Data Kaprodi tidak ditemukan.');
        }

        $jabatan = $kaprodi['jabatan_fungsional'];
        $prodiFilter = null;

        if (stripos($jabatan, 'Kaprodi Sistem Informasi') !== false) {
            $prodiFilter = 'Sistem informasi';
        } elseif (stripos($jabatan, 'Kaprodi Teknik Informatika') !== false) {
            $prodiFilter = 'Teknik informatika';
        } else {
            return redirect()->to('/login')->with('error', 'Anda bukan Kaprodi.');
        }

        // ============================
        // 2. Ambil semester & tahun ajaran AKTIF dari database
        // ============================
        $periodeAktif = $this->profilMagangModel
            ->select('semester, tahun_ajaran')
            ->where('status', 'aktif')
            ->where('deleted_at', null)
            ->orderBy('tanggal_mulai', 'DESC')
            ->first();

        if (!$periodeAktif) {
            return view('kaprodi/mahasiswa', [
                'mahasiswa'    => [],
                'semester'     => null,
                'tahun_ajaran' => null,
                'prodi'        => $prodiFilter,
                'user_name'    => $this->getUserName()
            ]);
        }

        $semester    = $periodeAktif['semester'];
        $tahunAjaran = $periodeAktif['tahun_ajaran'];

        // ============================
        // 3. Query mahasiswa magang
        // ============================
        $data['mahasiswa'] = $this->profilMagangModel
            ->select('
                profil_magang.id_profil,
                mahasiswa.nim,
                mahasiswa.nama_lengkap,
                dosen.nama_lengkap AS nama_dosen,
                mitra.nama_mitra,
                unit.nama_unit,
                program_magang.nama_program,
                profil_magang.tanggal_mulai,
                profil_magang.tanggal_selesai,
                profil_magang.status,
                profil_magang.semester,
                profil_magang.tahun_ajaran
            ')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
            ->join('dosen', 'dosen.nppy = profil_magang.nppy', 'left')
            ->join('mitra', 'mitra.id_mitra = profil_magang.id_mitra', 'left')
            ->join('unit', 'unit.id_unit = profil_magang.id_unit', 'left')
            ->join('program_magang', 'program_magang.id_program = profil_magang.id_program', 'left')
            ->where('profil_magang.status', 'aktif')
            ->where('profil_magang.semester', $semester)
            ->where('profil_magang.tahun_ajaran', $tahunAjaran)
            ->where('mahasiswa.program_studi', $prodiFilter)
            ->where('profil_magang.deleted_at', null)
            ->orderBy('profil_magang.tanggal_mulai', 'DESC')
            ->findAll();

        $data['semester']      = $semester;
        $data['tahun_ajaran']  = $tahunAjaran;
        $data['prodi']         = $prodiFilter;
        $data['user_name']     = $this->getUserName();

        return view('kaprodi/mahasiswa', $data);
    }

    public function detail($id)
    {
        // ✅ Pastikan hanya kaprodi yang bisa akses
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'kaprodi') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Tidak memiliki akses.'
            ]);
        }

        $today = date('Y-m-d');

        // ✅ Ambil data lengkap mahasiswa magang (termasuk mitra & unit)
        $data = $this->profilMagangModel
            ->select('
                profil_magang.*, 
                mahasiswa.nim, mahasiswa.nama_lengkap, mahasiswa.email AS email_mahasiswa, mahasiswa.handphone,
                dosen.nppy, dosen.nama_lengkap AS nama_dosen, dosen.no_hp AS no_hp_dosen, dosen.email AS email_dosen,
                program_magang.nama_program,
                unit.nama_unit, unit.nama_pembimbing, unit.no_hp AS no_hp_pembimbing, unit.email AS email_pembimbing,
                mitra.nama_mitra, mitra.alamat AS alamat_mitra
            ')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim', 'left')
            ->join('dosen', 'dosen.nppy = profil_magang.nppy', 'left')
            ->join('program_magang', 'program_magang.id_program = profil_magang.id_program', 'left')
            ->join('unit', 'unit.id_unit = profil_magang.id_unit', 'left')
            ->join('mitra', 'mitra.id_mitra = unit.id_mitra', 'left')
            ->where('profil_magang.id_profil', $id)
            ->first();

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Data tidak ditemukan.'
        ]);
    }

    public function nilai_mahasiswa()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'kaprodi') {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // ============================================
        // 🔥 1. Ambil data kaprodi (nama + jabatan)
        // ============================================
        $nppy = $this->session->get('nppy');

        $userModel  = new \App\Models\UserModel();
        $dosenModel = new \App\Models\DosenModel();

        $user  = $userModel->where('nppy', $nppy)->first();
        $dosen = $dosenModel->where('nppy', $nppy)->first();

        $kaprodiNama    = $dosen['nama_lengkap'] ?? '-';
        $kaprodiJabatan = $dosen['jabatan_fungsional'] ?? '-';

        // ============================================
        // 🔥 2. Tentukan Prodi Kaprodi
        // ============================================
        $jabatan = $this->session->get('jabatan_fungsional');
        $prodiFilter = null;

        if (stripos($jabatan, 'Kaprodi Sistem Informasi') !== false) {
            $prodiFilter = 'Sistem Informasi';
        } elseif (stripos($jabatan, 'Kaprodi Teknik Informatika') !== false) {
            $prodiFilter = 'Teknik Informatika';
        } else {
            return redirect()->to('/login')->with('error', 'Anda bukan Kaprodi.');
        }

        // ============================================
        // 🔥 3. AMBIL SEMESTER & TAHUN AJARAN DARI DATABASE
        // ============================================
        $periodeAktif = $this->profilMagangModel
            ->select('semester, tahun_ajaran')
            ->whereIn('status', ['aktif', 'selesai'])
            ->where('deleted_at', null)
            ->orderBy('tanggal_mulai', 'DESC')
            ->first();

        if (!$periodeAktif) {
            return view('kaprodi/nilai_mahasiswa', [
                'mahasiswa' => [],
                'semester' => '-',
                'tahun_ajaran' => '-',
                'prodi' => $prodiFilter,
                'user_name' => $this->getUserName(),
                'kaprodi_nama' => $kaprodiNama,
                'kaprodi_jabatan' => $kaprodiJabatan
            ]);
        }

        $semester    = $periodeAktif['semester'];
        $tahunAjaran = $periodeAktif['tahun_ajaran'];

        // ============================================
        // 🔥 4. Ambil mahasiswa sesuai periode DB
        // ============================================
        $mahasiswa = $this->profilMagangModel
            ->select('
                profil_magang.id_profil,
                mahasiswa.nim,
                mahasiswa.nama_lengkap
            ')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
            ->where('mahasiswa.program_studi', $prodiFilter)
            ->where('profil_magang.semester', $semester)
            ->where('profil_magang.tahun_ajaran', $tahunAjaran)
            ->whereIn('profil_magang.status', ['aktif', 'selesai'])
            ->orderBy('mahasiswa.nama_lengkap', 'ASC')
            ->findAll();

        // ============================================
        // 🔥 5. PROSES PERHITUNGAN NILAI (TIDAK DIUBAH)
        // ============================================
        $nilaiModel = new \App\Models\NilaiMagangModel();

        foreach ($mahasiswa as &$m) {

            $idProfil = $m['id_profil'];

            $komponen = $nilaiModel
                ->select("
                    nilai_magang.id_nilai_magang,
                    nilai_magang.role,
                    nilai_magang.nilai,
                    k.kode_mk,
                    k.presentase
                ")
                ->join('komponen_nilai k', 'k.id_nilai = nilai_magang.id_nilai')
                ->where('nilai_magang.id_profil', $idProfil)
                ->findAll();

            $totalPerMK = [];

            foreach ($komponen as $k) {
                if (!isset($totalPerMK[$k['kode_mk']])) {
                    $totalPerMK[$k['kode_mk']] = 0;
                }
                $totalPerMK[$k['kode_mk']] += $k['nilai'];
            }

            // Khusus BB010
            if (isset($totalPerMK['BB010'])) {

                $nilaiMitra = 0;
                $nilaiDospem = 0;

                foreach ($komponen as $k) {
                    if ($k['kode_mk'] === 'BB010') {
                        if ($k['role'] === 'mitra')  $nilaiMitra  += $k['nilai'];
                        if ($k['role'] === 'dospem') $nilaiDospem += $k['nilai'];
                    }
                }

                $komponenKaprodi = $this->komponenNilaiModel
                    ->where('kode_mk', 'BB010')
                    ->where('role', 'kaprodi')
                    ->findAll();

                $bobotMitra = 0;
                $bobotDospem = 0;

                foreach ($komponenKaprodi as $k) {
                    if ($k['id_nilai'] === 'BB010-1') $bobotMitra  = $k['presentase'];
                    if ($k['id_nilai'] === 'BB010-2') $bobotDospem = $k['presentase'];
                }

                $totalPerMK['BB010'] =
                    ($nilaiMitra * $bobotMitra / 100) +
                    ($nilaiDospem * $bobotDospem / 100);
            }

            $m['magang_final'] = $totalPerMK['BB010'] ?? null;
            $m['kombis']       = $totalPerMK['KB299'] ?? null;
            $m['asib']         = $totalPerMK['KB319'] ?? null;
            $m['dsib']         = $totalPerMK['KK166'] ?? null;

            $m['grade_magang'] = isset($m['magang_final']) ? $this->getGrade($m['magang_final']) : '-';
            $m['grade_kombis'] = isset($m['kombis'])       ? $this->getGrade($m['kombis'])       : '-';
            $m['grade_asib']   = isset($m['asib'])         ? $this->getGrade($m['asib'])         : '-';
            $m['grade_dsib']   = isset($m['dsib'])         ? $this->getGrade($m['dsib'])         : '-';
        }

        // ============================================
        // 🔥 6. KIRIM KE VIEW
        // ============================================
        return view('kaprodi/nilai_mahasiswa', [
            'mahasiswa'       => $mahasiswa,
            'semester'        => $semester,
            'tahun_ajaran'    => $tahunAjaran,
            'prodi'           => $prodiFilter,
            'user_name'       => $this->getUserName(),
            'kaprodi_nama'    => $kaprodiNama,
            'kaprodi_jabatan' => $kaprodiJabatan
        ]);
    }


    public function cekStatus($id_profil, $kode_mk, $penilai)
    {
        return $this->where('id_profil', $id_profil)
                    ->where('kode_mk', $kode_mk)
                    ->where('penilai', $penilai)
                    ->select('nilai_akhir_mk')
                    ->get()
                    ->getRow('nilai_akhir_mk');
    }


    private function getGrade($nilai)
    {
        if ($nilai >= 80) return 'A';
        if ($nilai >= 70) return 'B';
        if ($nilai >= 60) return 'C';
        if ($nilai >= 50) return 'D';
        return 'E';
    }


    public function detail_nilai($nim)
    {
        // Cek login & role Kaprodi
        if (
            !$this->session->get('isLoggedIn') ||
            $this->session->get('role') !== 'kaprodi'
        ) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        /**
         * ======================================================
         * 🔹 Ambil data profil magang AKTIF dari database
         *     Semester & Tahun Ajaran DIAMBIL LANGSUNG DARI DB
         * ======================================================
         */
        $profil = $this->profilMagangModel
            ->where('nim', $nim)
            ->where('status', 'aktif')
            ->first();

        if (!$profil) {
            return redirect()->back()->with('error', 'Profil magang aktif tidak ditemukan.');
        }

        $semester     = $profil['semester'];
        $tahunAjaran  = $profil['tahun_ajaran'];

        /**
         * ======================================================
         * 🔹 Ambil data mahasiswa
         * ======================================================
         */
        $data['mahasiswa'] = $this->profilMagangModel
            ->select('
                profil_magang.id_profil,
                mahasiswa.nim,
                mahasiswa.nama_lengkap,
                mitra.nama_mitra,
                unit.nama_unit,
                program_magang.nama_program,
                profil_magang.semester,
                profil_magang.tahun_ajaran
            ')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim', 'left')
            ->join('mitra', 'mitra.id_mitra = profil_magang.id_mitra', 'left')
            ->join('unit', 'unit.id_unit = profil_magang.id_unit', 'left')
            ->join('program_magang', 'program_magang.id_program = profil_magang.id_program', 'left')
            ->where('profil_magang.nim', $nim)
            ->where('profil_magang.semester', $semester)
            ->where('profil_magang.tahun_ajaran', $tahunAjaran)
            ->first();

        if (!$data['mahasiswa']) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        /**
         * ======================================================
         * 🔹 Ambil komponen nilai mahasiswa
         * ======================================================
         */
        $data['komponen'] = $this->nilaiModel
            ->select('
                nilai_magang.id_nilai_magang,
                nilai_magang.id_profil,
                nilai_magang.nilai,
                nilai_magang.role,
                k.id_nilai,
                k.kode_mk,
                k.komponen,
                k.presentase,
                mk.nama_mk
            ')
            ->join('komponen_nilai k', 'k.id_nilai = nilai_magang.id_nilai', 'left')
            ->join('mata_kuliah mk', 'mk.kode_mk = k.kode_mk', 'left')
            ->where('nilai_magang.id_profil', $data['mahasiswa']['id_profil'])
            ->orderBy('k.kode_mk', 'ASC')
            ->orderBy('nilai_magang.role', 'ASC')
            ->orderBy('nilai_magang.id_nilai_magang', 'ASC')
            ->findAll();

        /**
         * ======================================================
         * 🔹 Ambil nilai tersimpan
         * ======================================================
         */
        $nilaiTersimpan = $this->nilaiModel
            ->where('id_profil', $data['mahasiswa']['id_profil'])
            ->findAll();

        $data['nilaiTersimpan'] = [];
        foreach ($nilaiTersimpan as $n) {
            $data['nilaiTersimpan'][$n['id_nilai_magang']] = [
                'nilai' => $n['nilai']
            ];
        }

        /**
         * ======================================================
         * 🔹 Hitung total nilai per MK + Role
         * ======================================================
         */
        $totalPerMKRole = [];
        foreach ($data['komponen'] as $k) {
            $key = $k['kode_mk'] . '_' . $k['role'];
            $nilai = $data['nilaiTersimpan'][$k['id_nilai_magang']]['nilai'] ?? 0;

            if (!isset($totalPerMKRole[$key])) {
                $totalPerMKRole[$key] = 0;
            }
            $totalPerMKRole[$key] += $nilai;
        }
        $data['totalPerMKRole'] = $totalPerMKRole;

        /**
         * ======================================================
         * 🔹 Hitung total nilai per Mata Kuliah
         * ======================================================
         */
        $totalPerMK = [];
        foreach ($data['komponen'] as $k) {
            $kodeMK = $k['kode_mk'];
            $nilai  = $data['nilaiTersimpan'][$k['id_nilai_magang']]['nilai'] ?? 0;

            if (!isset($totalPerMK[$kodeMK])) {
                $totalPerMK[$kodeMK] = 0;
            }
            $totalPerMK[$kodeMK] += $nilai;
        }

        /**
         * ======================================================
         * 🔹 Aturan khusus MK BB010 (Magang)
         * ======================================================
         */
        if (isset($totalPerMK['BB010'])) {
            $nilaiMitra  = 0;
            $nilaiDospem = 0;

            foreach ($data['komponen'] as $k) {
                if ($k['kode_mk'] === 'BB010') {
                    $nilai = $data['nilaiTersimpan'][$k['id_nilai_magang']]['nilai'] ?? 0;
                    if ($k['role'] === 'mitra')   $nilaiMitra  += $nilai;
                    if ($k['role'] === 'dospem')  $nilaiDospem += $nilai;
                }
            }

            $komponenKaprodi = $this->komponenNilaiModel
                ->where('kode_mk', 'BB010')
                ->where('role', 'kaprodi')
                ->findAll();

            $bobotMitra  = 0;
            $bobotDospem = 0;
            foreach ($komponenKaprodi as $k) {
                if ($k['id_nilai'] === 'BB010-1') $bobotMitra  = $k['presentase'];
                if ($k['id_nilai'] === 'BB010-2') $bobotDospem = $k['presentase'];
            }

            $totalPerMK['BB010'] =
                ($nilaiMitra * $bobotMitra / 100) +
                ($nilaiDospem * $bobotDospem / 100);
        }

        $data['totalPerMK'] = $totalPerMK;

        /**
         * ======================================================
         * 🔹 Hitung grade
         * ======================================================
         */
        $data['gradePerMK'] = [];
        foreach ($data['totalPerMK'] as $kodeMK => $nilaiAkhir) {
            $data['gradePerMK'][$kodeMK] = $this->getGrade($nilaiAkhir);
        }

        $data['user_name'] = $this->getUserName();

        return view('kaprodi/detail_nilai', $data);
    }


}
