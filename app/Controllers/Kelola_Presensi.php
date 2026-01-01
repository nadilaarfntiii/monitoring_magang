<?php 
namespace App\Controllers;

use App\Models\PresensiMahasiswaModel;
use App\Models\ProfilMagangModel;
use CodeIgniter\Controller;

class Kelola_Presensi extends BaseController
{
    protected $presensiModel;
    protected $profilMagangModel;
    protected $session;

    public function __construct()
    {
        $this->presensiModel = new PresensiMahasiswaModel();
        $this->profilMagangModel = new ProfilMagangModel();
        $this->session = session();
    }

    public function index()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'mitra') {
            return redirect()->to('/login');
        }

        // Ambil seluruh presensi yang menunggu validasi dengan nama lengkap mahasiswa
        $db = db_connect();
        $data = [
            'user_name' => $this->getUserName(),
            'foto'      => $this->getUserFoto(),
            'presensi'  => $db->table('presensi_mahasiswa p')
                ->select('p.*, m.nama_lengkap')
                ->join('profil_magang pm', 'pm.nim = p.nim')
                ->join('mahasiswa m', 'm.nim = pm.nim')
                ->where('p.status_presensi', 'Menunggu Validasi')
                ->orderBy('p.tanggal', 'DESC')
                ->get()
                ->getResultArray()
        ];
            

        return view('mitra/kelola_presensi', $data);
    }

    // Validasi presensi
    public function validasi($id_presensi)
    {
        $this->presensiModel->update($id_presensi, [
            'status_presensi' => 'Disetujui',
            'updated_at'      => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Presensi berhasil divalidasi!');
    }

    // Tolak presensi dengan catatan
    public function tolak()
    {
        $id_presensi = $this->request->getPost('id_presensi');
        $catatan = $this->request->getPost('catatan_validasi');

        $this->presensiModel->update($id_presensi, [
            'status_presensi' => 'Tidak Disetujui',
            'catatan_validasi' => $catatan,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setStatusCode(200)->setBody('success');
    }

    // ==========================
    // 🔹 DETAIL PRESENSI MAHASISWA UNTUK MITRA
    // ==========================
    public function detailPresensi($nim)
    {
        // Pastikan login sebagai mitra
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'mitra') {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data mahasiswa + relasi dengan dosen dan unit
        $profilMagang = $this->profilMagangModel
            ->select('
                profil_magang.*, 
                mahasiswa.nim, 
                mahasiswa.nama_lengkap, 
                dosen.nama_lengkap AS nama_dosen, 
                unit.nama_unit
            ')
            ->join('mahasiswa', 'mahasiswa.nim = profil_magang.nim')
            ->join('dosen', 'dosen.nppy = profil_magang.nppy', 'left')
            ->join('unit', 'unit.id_unit = profil_magang.id_unit', 'left')
            ->where('profil_magang.nim', $nim)
            ->first();

        if (!$profilMagang) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Ambil data presensi berdasarkan NIM
        $presensi = $this->presensiModel
            ->where('nim', $nim)
            ->orderBy('tanggal', 'ASC')
            ->findAll();

        // Mapping hari dan penomoran "hari ke"
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
            $hari = $hariMap[date('l', strtotime($tgl))] ?? '-';

            $dataPresensi[] = [
                'hari_ke' => $hariKe,
                'hari' => $hari,
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

        // Kirim ke view mitra/detail_presensi
        return view('mitra/detail_presensi', [
            'mahasiswa' => $profilMagang,
            'presensi' => $dataPresensi
        ]);
    }



}
