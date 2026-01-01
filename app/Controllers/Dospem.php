<?php

namespace App\Controllers;

use App\Models\MitraModel;
use App\Models\UnitModel;
use App\Models\ProfilMagangModel;
use App\Models\LogbookModel;
use App\Models\DosenModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class Dospem extends BaseController
{
    protected $session;
    protected $mitraModel;
    protected $unitModel;
    protected $profilMagangModel;
    protected $logbookModel;
    protected $dosenModel;
    protected $userModel; 

    public function __construct()
    {
        $this->session = session();
        $this->mitraModel = new MitraModel();
        $this->unitModel = new UnitModel();
        $this->profilMagangModel = new ProfilMagangModel();
        $this->logbookModel = new LogbookModel();
        $this->dosenModel = new DosenModel();
        $this->userModel = new UserModel();
    }

    public function dashboard()
    {
        $session = session();

        if (!$session->get('isLoggedIn') || $session->get('role') !== 'dospem') {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $id_user = $session->get('id_user');

        // Ambil user
        $user = $this->userModel->find($id_user);

        // Ambil dosen berdasarkan NPPY (FK di user)
        $dosen = null;
        if (!empty($user['nppy'])) {
            $dosen = $this->dosenModel
                ->where('nppy', $user['nppy'])
                ->first();
        }

        // ==============================
        // NAMA (Dosen → User → Default)
        // ==============================
        $user_name = $dosen['nama_lengkap']
            ?? $user['nama_lengkap']
            ?? 'Dosen Pembimbing';

        // ==============================
        // FOTO (Dosen → User → Default)
        // ==============================
        $foto = 'pp.jpg';

        if (!empty($dosen['foto']) && file_exists(FCPATH . 'uploads/foto/' . $dosen['foto'])) {
            $foto = $dosen['foto'];
        } elseif (!empty($user['foto']) && file_exists(FCPATH . 'uploads/foto/' . $user['foto'])) {
            $foto = $user['foto'];
        }

        return view('dospem/dashboard', [
            'user_name' => $user_name,
            'foto'      => $foto,
            'role_name' => 'Dosen Pembimbing'
        ]);
    }

    public function profil()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'dospem') {
            return redirect()->to('/login');
        }

        $id_user = $this->session->get('id_user');

        $user = $this->userModel->find($id_user);

        $dosen = null;
        if (!empty($user['nppy'])) {
            $dosen = $this->dosenModel
                ->where('nppy', $user['nppy'])
                ->first();
        }

        return view('dospem/profil', [
            'user'      => $user,
            'dosen'     => $dosen,
            'user_name' => $this->getUserName(), 
            'foto'      => $this->getUserFoto(), 
        ]);
    }


    // ===============================
    // UPDATE PROFIL DOSEN PEMBIMBING
    // ===============================
    public function update_profil()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'dospem') {
            return redirect()->to('/login');
        }

        $id_user = $this->request->getPost('id_user');

        // Ambil user
        $user = $this->userModel->find($id_user);
        $nppy = $user['nppy']; // FK ke tabel dosen

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
        $dosenFoto = null;

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $newName = $foto->getRandomName();
            $foto->move('uploads/foto/', $newName);

            // simpan ke tabel user
            $userData['foto'] = $newName;

            // update session foto
            $this->session->set('foto', $newName);

            // simpan juga ke dosen
            $dosenFoto = $newName;
        }

        $this->userModel->skipValidation(true)->update($id_user, $userData);

        // ===========================
        // UPDATE TABEL DOSEN
        // ===========================
        $dosenData = [
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'alamat'        => $this->request->getPost('alamat'),
            'email'         => $this->request->getPost('email'),
            'no_hp'         => $this->request->getPost('no_hp'),
        ];

        if ($dosenFoto !== null) {
            $dosenData['foto'] = $dosenFoto;
        }

        $this->dosenModel->update($nppy, $dosenData);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }


    // 🔹 Menampilkan daftar mahasiswa yang dibimbing dospem
    public function mahasiswa()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'dospem') {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil NPPY dospem yang login
        $nppy = $this->session->get('username'); // asumsi username = nppy

        // Ambil bulan saat ini untuk menentukan semester
        $bulan = date('n'); // (1-12)
        $semester = ($bulan >= 9 || $bulan <= 2) ? 'Gasal' : 'Genap';

        // Tentukan tahun ajaran aktif
        $tahunSekarang = date('Y');
        $tahunAjaran = ($semester === 'Gasal')
            ? $tahunSekarang . '/' . ($tahunSekarang + 1)
            : ($tahunSekarang - 1) . '/' . $tahunSekarang;

        // Ambil mahasiswa bimbingan berdasarkan nppy dospem login
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
        ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim', 'left')
        ->join('dosen', 'dosen.nppy = profil_magang.nppy', 'left')  // 🔹 Tambahkan ini
        ->join('mitra', 'mitra.id_mitra = profil_magang.id_mitra', 'left')
        ->join('unit', 'unit.id_unit = profil_magang.id_unit', 'left')
        ->join('program_magang', 'program_magang.id_program = profil_magang.id_program', 'left')
        ->where('profil_magang.nppy', $nppy)
        ->where('profil_magang.semester', $semester)
        ->where('profil_magang.tahun_ajaran', $tahunAjaran)
        ->where('profil_magang.status', 'aktif')
        ->orderBy('profil_magang.tanggal_mulai', 'DESC')
        ->findAll();

        $data['semester'] = $semester;
        $data['tahun_ajaran'] = $tahunAjaran;
        $data['user_name'] = $this->getUserName();
        $data['foto'] = $this->getUserFoto();

        return view('dospem/mahasiswa', $data);
    }

    // 🔹 Detail mahasiswa bimbingan
    public function detail($id)
    {
        // Hanya dospem yang login yang boleh mengakses
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'dospem') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Tidak memiliki akses.'
            ]);
        }

        $nppy = $this->session->get('username'); // NPPY dospem yang login

        // Ambil data lengkap mahasiswa bimbingan dospem yang login
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
            ->where('profil_magang.nppy', $nppy)
            ->first();

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Data tidak ditemukan atau bukan bimbingan Anda.'
        ]);
    }

    public function arsip_mahasiswa()
    {
        // Cek jika dospem sudah login
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') != 'dospem') {
            return redirect()->to('/login');
        }

        $data['mahasiswa'] = $this->profilMagangModel->getArsipMahasiswa();
        $data['user_name'] = $this->getUserName();
        $data['foto'] = $this->getUserFoto();

        return view('dospem/arsip_mahasiswa', $data);
    }


    public function dataPresensi()
    {
        // Pastikan login sebagai dospem
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'dospem') {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
    
        $nppy = $this->session->get('username'); // NPPY dospem yang login
        $db = \Config\Database::connect();
    
        $jamKerjaModel = new \App\Models\JamKerjaUnitModel();
        $profilMagangModel = new \App\Models\ProfilMagangModel();
    
        // Tentukan semester & tahun ajaran aktif
        $bulan = date('n');
        $semester = ($bulan >= 9 || $bulan <= 2) ? 'Gasal' : 'Genap';
        $tahunSekarang = date('Y');
        $tahunAjaran = ($semester === 'Gasal')
            ? $tahunSekarang . '/' . ($tahunSekarang + 1)
            : ($tahunSekarang - 1) . '/' . $tahunSekarang;
    
        // Query utama presensi
        $builder = $db->table('profil_magang')
            ->select('
                mahasiswa.nim, mahasiswa.nama_lengkap,
                dosen.nama_lengkap AS nama_dosen,
                profil_magang.id_unit,
                profil_magang.tanggal_mulai,
                profil_magang.tanggal_selesai,
                SUM(CASE WHEN presensi_mahasiswa.keterangan = "Masuk" THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN presensi_mahasiswa.keterangan = "Sakit" THEN 1 ELSE 0 END) AS sakit,
                SUM(CASE WHEN presensi_mahasiswa.keterangan = "Izin" THEN 1 ELSE 0 END) AS ijin,
                SUM(CASE WHEN presensi_mahasiswa.keterangan = "Alpha" THEN 1 ELSE 0 END) AS alpa,
                COUNT(presensi_mahasiswa.id_presensi) AS total_pertemuan
            ')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
            ->join('dosen', 'dosen.nppy = profil_magang.nppy')
            ->join('presensi_mahasiswa', 'presensi_mahasiswa.nim = mahasiswa.nim', 'left')
            ->where('profil_magang.nppy', $nppy)
            ->where('profil_magang.status', 'aktif')
            ->where('profil_magang.semester', $semester)
            ->where('profil_magang.tahun_ajaran', $tahunAjaran)
            ->groupBy('mahasiswa.nim');
    
        $result = $builder->get()->getResultArray();
    
        // Hitung total kehadiran, total hari kerja berjalan, dan persentase
        foreach ($result as &$r) {
            $r['total_kehadiran'] = $r['hadir'] + $r['sakit'] + $r['ijin'];
    
            // Ambil hari kerja unit
            $hariKerja = $jamKerjaModel
                ->where('id_unit', $r['id_unit'])
                ->where('status_hari', 'Kerja')
                ->findAll();
    
            $hariKerjaArr = array_column($hariKerja, 'hari'); // contoh: ['Senin','Selasa','Rabu']
    
            // Hitung jumlah hari kerja yang sudah dilewati (sampai hari ini)
            $tanggalMulai = new \DateTime($r['tanggal_mulai']);
            $tanggalSelesai = new \DateTime($r['tanggal_selesai']);
            $tanggalSekarang = new \DateTime(); // tanggal hari ini
            $interval = new \DateInterval('P1D');
    
            // Batas hitung: sampai hari ini atau tanggal selesai (mana yang lebih dulu)
            $tanggalAkhirHitung = ($tanggalSekarang < $tanggalSelesai)
                ? $tanggalSekarang
                : $tanggalSelesai;
    
            $periode = new \DatePeriod($tanggalMulai, $interval, $tanggalAkhirHitung->modify('+1 day'));
    
            $totalHariKerja = 0;
            foreach ($periode as $tgl) {
                $namaHari = $tgl->format('l'); // nama hari dalam bahasa Inggris
                $hariMap = [
                    'Monday' => 'Senin',
                    'Tuesday' => 'Selasa',
                    'Wednesday' => 'Rabu',
                    'Thursday' => 'Kamis',
                    'Friday' => 'Jumat',
                    'Saturday' => 'Sabtu',
                    'Sunday' => 'Minggu'
                ];
                if (in_array($hariMap[$namaHari], $hariKerjaArr)) {
                    $totalHariKerja++;
                }
            }
    
            // Sekarang total hari kerja hanya sampai hari ini
            $r['total_hari_kerja'] = $totalHariKerja;
    
            // Hitung persentase berdasarkan hari kerja yang sudah dilewati
            $r['persentase'] = ($totalHariKerja > 0)
                ? round(($r['total_kehadiran'] / $totalHariKerja) * 100, 1)
                : 0;
        }
    
        $data = [
            'rekap_presensi' => $result,
            'semester' => $semester,
            'tahun_ajaran' => $tahunAjaran,
            'user_name' => $this->getUserName(), 
            'foto'      => $this->getUserFoto()
        ];
    
        return view('dospem/data_presensi', $data);
    }
    

    public function detailPresensi($nim)
    {
        // Pastikan login sebagai dospem
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'dospem') {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $presensiModel = new \App\Models\PresensiMahasiswaModel();

        // Ambil data mahasiswa
        $profilMagang = $this->profilMagangModel
            ->select('mahasiswa.nim, mahasiswa.nama_lengkap')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
            ->where('profil_magang.nim', $nim)
            ->first();

        if (!$profilMagang) {
            return redirect()->back()->with('error', 'Mahasiswa tidak ditemukan.');
        }

        // Ambil data presensi mahasiswa
        $presensi = $presensiModel
            ->where('nim', $nim)
            ->orderBy('tanggal', 'ASC')
            ->findAll();

        // Mapping hari ke bahasa Indonesia & hitung hari ke
        $hariMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];

        $dataPresensi = [];
        $hariKe = 1;
        foreach ($presensi as $p) {
            $tgl = $p['tanggal'];

            $dataPresensi[] = [
                'hari_ke' => $hariKe,
                'tanggal' => $tgl,
                'waktu_masuk' => $p['waktu_masuk'],
                'status_kehadiran' => $p['status_kehadiran'],
                'keterangan' => $p['keterangan'],
                'foto_bukti' => $p['foto_bukti'],
                'status_presensi' => $p['status_presensi'],
                'catatan_validasi' => $p['catatan_validasi']
            ];

            $hariKe++;
        }

        return view('dospem/detail_presensi', [
            'mahasiswa' => $profilMagang,
            'presensi' => $dataPresensi,
            'user_name' => $this->getUserName(), 
            'foto'      => $this->getUserFoto()
        ]);
    }

    // Menampilkan daftar logbook
    public function logbook($nim)
    {
        // Pastikan dospem login
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'dospem') {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah mahasiswa tersebut termasuk bimbingan dospem yang login
        $nppy = $this->session->get('username'); // ambil NPPY dospem login
        $profil = $this->profilMagangModel
            ->where('nim', $nim)
            ->where('nppy', $nppy)
            ->first();

        if (!$profil) {
            return redirect()->back()->with('error', 'Mahasiswa tidak ditemukan atau bukan bimbingan Anda.');
        }

        // Ambil semua logbook mahasiswa tersebut
        $data = [
            'user_name' => $this->getUserName(), 
            'foto'      => $this->getUserFoto(), 
            'title' => 'Logbook Harian',
            'logbooks' => $this->logbookModel
                ->where('id_profil', $profil['id_profil'])
                ->orderBy('tanggal', 'DESC')
                ->findAll()
        ];

        return view('dospem/logbook', $data);
    }


}
