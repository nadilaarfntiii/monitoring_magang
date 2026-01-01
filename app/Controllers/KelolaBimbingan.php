<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BimbinganModel;
use App\Models\ProfilMagangModel;
use App\Models\MatakuliahModel;
use App\Models\UserModel;
use App\Models\TugasAkhirMagangModel;

class KelolaBimbingan extends BaseController
{
    protected $bimbinganModel;
    protected $profilMagangModel;
    protected $matakuliahModel;
    protected $userModel;
    protected $taMagangModel;
    protected $session;

    public function __construct()
    {
        $this->bimbinganModel   = new BimbinganModel();
        $this->profilMagangModel = new ProfilMagangModel();
        $this->matakuliahModel  = new MatakuliahModel();
        $this->userModel        = new UserModel();
        $this->taMagangModel    = new TugasAkhirMagangModel();
        $this->session          = session();
    }

    public function magang()
    {
        // Ambil NPPY dospem dari session
        $nppy = $this->session->get('nppy');

        // Jika session nppy belum ada → ambil dari user login
        if (!$nppy && $this->session->get('role') === 'dospem') {
            $id_user = $this->session->get('id_user');
            $user = $this->userModel->find($id_user);

            if ($user && !empty($user['nppy'])) {
                $nppy = $user['nppy'];
                $this->session->set('nppy', $nppy);
            }
        }

        // Jika masih belum ada, redirect ke login
        if (!$nppy) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 🔹 Tentukan semester & tahun ajaran aktif
        $bulan = date('n');
        $semester = ($bulan >= 9 || $bulan <= 2) ? 'Gasal' : 'Genap';

        $tahunSekarang = date('Y');
        $tahunAjaran = ($semester === 'Gasal')
            ? $tahunSekarang . '/' . ($tahunSekarang + 1)
            : ($tahunSekarang - 1) . '/' . $tahunSekarang;

        // 🔹 Ambil data mahasiswa bimbingan dospem sesuai semester aktif
        $mahasiswaBimbingan = $this->profilMagangModel
            ->select('
                profil_magang.id_profil,
                profil_magang.nim,
                mahasiswa.nama_lengkap,
                dosen.nama_lengkap AS nama_dosen,
                tugas_akhir_magang.judul_ta
            ')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
            ->join('dosen', 'dosen.nppy = profil_magang.nppy')
            ->join(
                'tugas_akhir_magang',
                'tugas_akhir_magang.id_profil = profil_magang.id_profil AND tugas_akhir_magang.kode_mk = "BB010"',
                'left'
            )
            ->where('profil_magang.nppy', $nppy)
            ->where('profil_magang.deleted_at', null)
            ->where('profil_magang.semester', $semester)
            ->where('profil_magang.tahun_ajaran', $tahunAjaran)
            ->where('profil_magang.status', 'aktif')
            ->findAll();

        // 🔹 Hitung frekuensi bimbingan tiap mahasiswa (khusus MK Magang)
        foreach ($mahasiswaBimbingan as &$mhs) {
            $jumlahBimbingan = $this->bimbinganModel
                ->where('id_profil', $mhs['id_profil'])
                ->join('mata_kuliah', 'mata_kuliah.kode_mk = bimbingan.kode_mk')
                ->where('bimbingan.kode_mk', 'BB010')
                ->countAllResults();

            $mhs['frekuensi_bimbingan'] = $jumlahBimbingan;
        }

        $data = [
            'title' => 'Data Bimbingan Magang',
            'mahasiswa' => $mahasiswaBimbingan,
            'semester' => $semester,
            'tahun_ajaran' => $tahunAjaran
        ];

        return view('dospem/bimbingan_magang', $data);
    }

    public function tambah($id_profil)
    {
        return redirect()->to("/dospem/bimbingan/tambah/$id_profil");
    }

    public function simpanMagang()
    {
        $bimbinganModel = new \App\Models\BimbinganModel();

        // ✅ Ambil ID profil dulu
        $id_profil = $this->request->getPost('id_profil');

        $data = [
            'id_profil'         => $this->request->getPost('id_profil'),
            'kode_mk'           => 'BB010', 
            'tanggal_bimbingan' => $this->request->getPost('tanggal_bimbingan'),
            'progress_ta'       => $this->request->getPost('progress_ta'),
            'status_bimbingan'  => $this->request->getPost('status_bimbingan'),
            'catatan_detail'    => $this->request->getPost('catatan_detail'),
        ];

        $insertResult = $bimbinganModel->insert($data);

        if ($insertResult === false) {
            log_message('error', 'Insert bimbingan gagal: ' . json_encode($bimbinganModel->errors()));
            return redirect()->back()->with('error', 'Gagal menambahkan data bimbingan magang.');
        }

        // Setelah insert/update bimbingan
        $bab4 = $this->bimbinganModel
        ->where('id_profil', $id_profil)
        ->where('progress_ta', 'Bab 4') // sesuaikan dengan tabel
        ->where('status_bimbingan', 'ACC')
        ->first();

        if ($bab4) {
        // Update status profil magang menjadi 'selesai'
        $this->profilMagangModel->update($id_profil, ['status' => 'selesai']);
        }

        // ✅ Setelah berhasil, redirect ke halaman detail bimbingan
        return redirect()->to("/dospem/bimbingan/detail/" . $id_profil . "/BB010")
        ->with('success', 'Data bimbingan magang berhasil ditambahkan.');

    }


    public function detail($id_profil, $kode_mk = null)
    {
        // Jika tidak ada kode_mk dikirim, default ke 'BB010' (Magang)
        if ($kode_mk === null) {
            $kode_mk = 'BB010';
        }
    
        // 🔹 Ambil data bimbingan sesuai id_profil dan kode_mk
        $bimbingan = $this->bimbinganModel
            ->select('
                bimbingan.*, 
                profil_magang.nim,
                mahasiswa.nama_lengkap, 
                tugas_akhir_magang.judul_ta, 
                mata_kuliah.nama_mk, 
                mata_kuliah.kode_mk,
                mata_kuliah.sks
            ')
            ->join('profil_magang', 'profil_magang.id_profil = bimbingan.id_profil')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
            ->join(
                'tugas_akhir_magang', 
                'tugas_akhir_magang.id_profil = profil_magang.id_profil 
                 AND tugas_akhir_magang.kode_mk = bimbingan.kode_mk', 
                'left'
            )
            ->join('mata_kuliah', 'mata_kuliah.kode_mk = bimbingan.kode_mk', 'left')
            ->where('bimbingan.id_profil', $id_profil)
            ->where('bimbingan.kode_mk', $kode_mk)
            ->orderBy('bimbingan.tanggal_bimbingan', 'ASC')
            ->findAll();
    
        $data = [
            'title' => 'Detail Bimbingan Mahasiswa',
            'bimbingan' => $bimbingan,
            'kode_mk' => $kode_mk,
        ];
    
        return view('dospem/detail_bimbingan', $data);
    }
    

    public function update()
    {
        // Ambil ID bimbingan dari form
        $id_bimbingan = $this->request->getPost('id_bimbingan');

        if (!$id_bimbingan) {
            return redirect()->back()->with('error', 'ID bimbingan tidak valid.');
        }

        // Ambil data lama bimbingan
        $bimbingan = $this->bimbinganModel->find($id_bimbingan);
        if (!$bimbingan) {
            return redirect()->back()->with('error', 'Data bimbingan tidak ditemukan.');
        }

        // Ambil id_profil (karena diperlukan untuk pengecekan BAB4 ACC)
        $id_profil = $bimbingan['id_profil'];

        // Data baru dari form (SEMUA SUDAH SESUAI VIEW)
        $data = [
            'tanggal_bimbingan' => $this->request->getPost('tanggal_bimbingan'),
            'progress_ta'       => $this->request->getPost('progress_ta'),
            'status_bimbingan'  => $this->request->getPost('status_bimbingan'),
            'catatan_detail'    => $this->request->getPost('catatan_detail'),
        ];

        // Update data bimbingan
        $this->bimbinganModel->update($id_bimbingan, $data);

        // ========== CEK: Jika Bab 4 ACC, ubah status profil menjadi selesai ==========
        $bab4 = $this->bimbinganModel
            ->where('id_profil', $id_profil)
            ->where('progress_ta', 'Bab 4')
            ->where('status_bimbingan', 'Acc') // sesuai value di view, huruf besar kecil sama!
            ->first();

        if ($bab4) {
            $this->profilMagangModel->update($id_profil, [
                'status' => 'selesai'
            ]);
        }

        return redirect()->back()->with('success', 'Data bimbingan berhasil diperbarui.');
    }


    public function asb()
    {
        // Ambil NPPY dospem dari session
        $nppy = $this->session->get('nppy');

        // Jika session nppy belum ada → ambil dari user login
        if (!$nppy && $this->session->get('role') === 'dospem') {
            $id_user = $this->session->get('id_user');
            $user = $this->userModel->find($id_user);

            if ($user && !empty($user['nppy'])) {
                $nppy = $user['nppy'];
                $this->session->set('nppy', $nppy);
            }
        }

        // Jika masih belum ada, redirect ke login
        if (!$nppy) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 🔹 Tentukan semester & tahun ajaran aktif
        $bulan = date('n');
        $semester = ($bulan >= 9 || $bulan <= 2) ? 'Gasal' : 'Genap';

        $tahunSekarang = date('Y');
        $tahunAjaran = ($semester === 'Gasal')
            ? $tahunSekarang . '/' . ($tahunSekarang + 1)
            : ($tahunSekarang - 1) . '/' . $tahunSekarang;

        // 🔹 Ambil data mahasiswa bimbingan untuk MK "Analisa Sistem Informasi Bisnis"
        $mahasiswaBimbingan = $this->profilMagangModel
            ->select('
                profil_magang.id_profil,
                profil_magang.nim,
                mahasiswa.nama_lengkap,
                dosen.nama_lengkap AS nama_dosen,
                tugas_akhir_magang.judul_ta
            ')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
            ->join('dosen', 'dosen.nppy = profil_magang.nppy')
            ->join(
                'tugas_akhir_magang',
                'tugas_akhir_magang.id_profil = profil_magang.id_profil AND tugas_akhir_magang.kode_mk = "KK166"',
                'left'
            )
            ->where('profil_magang.nppy', $nppy)
            ->where('profil_magang.deleted_at', null)
            ->where('profil_magang.semester', $semester)
            ->where('profil_magang.tahun_ajaran', $tahunAjaran)
            ->where('profil_magang.status', 'aktif')
            ->findAll();

        // 🔹 Hitung frekuensi bimbingan tiap mahasiswa (khusus MK Analisa Sistem Informasi Bisnis)
        foreach ($mahasiswaBimbingan as &$mhs) {
            $jumlahBimbingan = $this->bimbinganModel
                ->where('id_profil', $mhs['id_profil'])
                ->join('mata_kuliah', 'mata_kuliah.kode_mk = bimbingan.kode_mk')
                ->where('bimbingan.kode_mk', 'KK166')
                ->countAllResults();

            $mhs['frekuensi_bimbingan'] = $jumlahBimbingan;
        }

        $data = [
            'title' => 'Data Bimbingan Analisa Sistem Informasi Bisnis',
            'mahasiswa' => $mahasiswaBimbingan,
            'semester' => $semester,
            'tahun_ajaran' => $tahunAjaran
        ];

        return view('dospem/bimbingan_asb', $data);
    }


    public function simpanAsb()
    {
        $bimbinganModel = new \App\Models\BimbinganModel();

        // ✅ Ambil ID profil dulu
        $id_profil = $this->request->getPost('id_profil');

        $data = [
            'id_profil'         => $this->request->getPost('id_profil'),
            'kode_mk'           => 'KK166', 
            'tanggal_bimbingan' => $this->request->getPost('tanggal_bimbingan'),
            'progress_ta'       => $this->request->getPost('progress_ta'),
            'status_bimbingan'  => $this->request->getPost('status_bimbingan'),
            'catatan_detail'    => $this->request->getPost('catatan_detail'),
        ];

        $insertResult = $bimbinganModel->insert($data);

        if ($insertResult === false) {
            log_message('error', 'Insert bimbingan gagal: ' . json_encode($bimbinganModel->errors()));
            return redirect()->back()->with('error', 'Gagal menambahkan data bimbingan Analisis Sistem.');
        }

        // ✅ Setelah berhasil, redirect ke halaman detail bimbingan
        return redirect()->to("/dospem/bimbingan/detail/" . $id_profil . "/KK166")
        ->with('success', 'Data bimbingan Analisis Sistem berhasil ditambahkan.');

    }

    public function dsib()
    {
        // Ambil NPPY dospem dari session
        $nppy = $this->session->get('nppy');

        // Jika session nppy belum ada → ambil dari user login
        if (!$nppy && $this->session->get('role') === 'dospem') {
            $id_user = $this->session->get('id_user');
            $user = $this->userModel->find($id_user);

            if ($user && !empty($user['nppy'])) {
                $nppy = $user['nppy'];
                $this->session->set('nppy', $nppy);
            }
        }

        // Jika masih belum ada, redirect ke login
        if (!$nppy) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 🔹 Tentukan semester & tahun ajaran aktif
        $bulan = date('n');
        $semester = ($bulan >= 9 || $bulan <= 2) ? 'Gasal' : 'Genap';

        $tahunSekarang = date('Y');
        $tahunAjaran = ($semester === 'Gasal')
            ? $tahunSekarang . '/' . ($tahunSekarang + 1)
            : ($tahunSekarang - 1) . '/' . $tahunSekarang;

        // 🔹 Ambil data mahasiswa bimbingan untuk MK "Desain Sistem Informasi Bisnis"
        $mahasiswaBimbingan = $this->profilMagangModel
            ->select('
                profil_magang.id_profil,
                profil_magang.nim,
                mahasiswa.nama_lengkap,
                dosen.nama_lengkap AS nama_dosen,
                tugas_akhir_magang.judul_ta
            ')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
            ->join('dosen', 'dosen.nppy = profil_magang.nppy')
            ->join(
                'tugas_akhir_magang',
                'tugas_akhir_magang.id_profil = profil_magang.id_profil AND tugas_akhir_magang.kode_mk = "KB319"',
                'left'
            )
            ->where('profil_magang.nppy', $nppy)
            ->where('profil_magang.deleted_at', null)
            ->where('profil_magang.semester', $semester)
            ->where('profil_magang.tahun_ajaran', $tahunAjaran)
            ->where('profil_magang.status', 'aktif')
            ->findAll();

        // 🔹 Hitung frekuensi bimbingan tiap mahasiswa (khusus MK Desain Sistem Informasi Bisnis)
        foreach ($mahasiswaBimbingan as &$mhs) {
            $jumlahBimbingan = $this->bimbinganModel
                ->where('id_profil', $mhs['id_profil'])
                ->join('mata_kuliah', 'mata_kuliah.kode_mk = bimbingan.kode_mk')
                ->where('bimbingan.kode_mk', 'KB319')
                ->countAllResults();

            $mhs['frekuensi_bimbingan'] = $jumlahBimbingan;
        }

        $data = [
            'title' => 'Data Bimbingan Desain Sistem Informasi Bisnis',
            'mahasiswa' => $mahasiswaBimbingan,
            'semester' => $semester,
            'tahun_ajaran' => $tahunAjaran
        ];

        return view('dospem/bimbingan_dsib', $data);
    }


    public function simpanDsib()
    {
        $bimbinganModel = new \App\Models\BimbinganModel();

        // ✅ Ambil ID profil dulu
        $id_profil = $this->request->getPost('id_profil');

        $data = [
            'id_profil'         => $this->request->getPost('id_profil'),
            'kode_mk'           => 'KB319', 
            'tanggal_bimbingan' => $this->request->getPost('tanggal_bimbingan'),
            'progress_ta'       => $this->request->getPost('progress_ta'),
            'status_bimbingan'  => $this->request->getPost('status_bimbingan'),
            'catatan_detail'    => $this->request->getPost('catatan_detail'),
        ];

        $insertResult = $bimbinganModel->insert($data);

        if ($insertResult === false) {
            log_message('error', 'Insert bimbingan gagal: ' . json_encode($bimbinganModel->errors()));
            return redirect()->back()->with('error', 'Gagal menambahkan data bimbingan Desain Sistem.');
        }

        // ✅ Setelah berhasil, redirect ke halaman detail bimbingan
        return redirect()->to("/dospem/bimbingan/detail/" . $id_profil . "/KB319")
        ->with('success', 'Data bimbingan Desain Sistem berhasil ditambahkan.');

    }


    public function kombis()
    {
        // Ambil NPPY dospem dari session
        $nppy = $this->session->get('nppy');

        // Jika session nppy belum ada → ambil dari user login
        if (!$nppy && $this->session->get('role') === 'dospem') {
            $id_user = $this->session->get('id_user');
            $user = $this->userModel->find($id_user);

            if ($user && !empty($user['nppy'])) {
                $nppy = $user['nppy'];
                $this->session->set('nppy', $nppy);
            }
        }

        // Jika masih belum ada, redirect ke login
        if (!$nppy) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 🔹 Tentukan semester & tahun ajaran aktif
        $bulan = date('n');
        $semester = ($bulan >= 9 || $bulan <= 2) ? 'Gasal' : 'Genap';

        $tahunSekarang = date('Y');
        $tahunAjaran = ($semester === 'Gasal')
            ? $tahunSekarang . '/' . ($tahunSekarang + 1)
            : ($tahunSekarang - 1) . '/' . $tahunSekarang;

        // 🔹 Ambil data mahasiswa bimbingan untuk MK "Komunikasi Bisnis"
        $mahasiswaBimbingan = $this->profilMagangModel
            ->select('
                profil_magang.id_profil,
                profil_magang.nim,
                mahasiswa.nama_lengkap,
                dosen.nama_lengkap AS nama_dosen,
                tugas_akhir_magang.judul_ta
            ')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
            ->join('dosen', 'dosen.nppy = profil_magang.nppy')
            ->join(
                'tugas_akhir_magang',
                'tugas_akhir_magang.id_profil = profil_magang.id_profil AND tugas_akhir_magang.kode_mk = "KB299"',
                'left'
            )
            ->where('profil_magang.nppy', $nppy)
            ->where('profil_magang.deleted_at', null)
            ->where('profil_magang.semester', $semester)
            ->where('profil_magang.tahun_ajaran', $tahunAjaran)
            ->where('profil_magang.status', 'aktif')
            ->findAll();

        // 🔹 Hitung frekuensi bimbingan tiap mahasiswa (khusus MK Komunikasi Bisnis)
        foreach ($mahasiswaBimbingan as &$mhs) {
            $jumlahBimbingan = $this->bimbinganModel
                ->where('id_profil', $mhs['id_profil'])
                ->join('mata_kuliah', 'mata_kuliah.kode_mk = bimbingan.kode_mk')
                ->where('bimbingan.kode_mk', 'KB299')
                ->countAllResults();

            $mhs['frekuensi_bimbingan'] = $jumlahBimbingan;
        }

        $data = [
            'title' => 'Data Bimbingan Komunikasi Bisnis',
            'mahasiswa' => $mahasiswaBimbingan,
            'semester' => $semester,
            'tahun_ajaran' => $tahunAjaran
        ];

        return view('dospem/bimbingan_kombis', $data);
    }


    public function simpanKombis()
    {
        $bimbinganModel = new \App\Models\BimbinganModel();

        // ✅ Ambil ID profil dulu
        $id_profil = $this->request->getPost('id_profil');

        $data = [
            'id_profil'         => $this->request->getPost('id_profil'),
            'kode_mk'           => 'KB299', 
            'tanggal_bimbingan' => $this->request->getPost('tanggal_bimbingan'),
            'progress_ta'       => $this->request->getPost('progress_ta'),
            'status_bimbingan'  => $this->request->getPost('status_bimbingan'),
            'catatan_detail'    => $this->request->getPost('catatan_detail'),
        ];

        $insertResult = $bimbinganModel->insert($data);

        if ($insertResult === false) {
            log_message('error', 'Insert bimbingan gagal: ' . json_encode($bimbinganModel->errors()));
            return redirect()->back()->with('error', 'Gagal menambahkan data bimbingan Komunikasi Bisnis.');
        }

        // ✅ Setelah berhasil, redirect ke halaman detail bimbingan
        return redirect()->to("/dospem/bimbingan/detail/" . $id_profil . "/KB299")
        ->with('success', 'Data bimbingan Komunikasi Bisnis berhasil ditambahkan.');

    }

}
