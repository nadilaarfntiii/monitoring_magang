<?php
namespace App\Controllers;

use App\Models\LogbookModel;
use App\Models\ProfilMagangModel;
use CodeIgniter\Controller;

class KelolaLogbook extends BaseController
{
    protected $logbookModel;
    protected $profilMagangModel;
    protected $session;

    public function __construct()
    {
        $this->logbookModel = new LogbookModel();
        $this->profilMagangModel = new ProfilMagangModel();
        $this->session = session();
    }

    // ==========================
    // 🔹 TAMPILKAN DAFTAR LOGBOOK
    // ==========================
    public function index()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'mitra') {
            return redirect()->to('/login');
        }

        $idUnit = $this->session->get('id_unit'); // unit pembimbing

        // Ambil seluruh logbook mahasiswa unit pembimbing
        $db = db_connect();
        $data = [
            'user_name' => $this->getUserName(),
            'foto'      => $this->getUserFoto(),
            'logbooks'  => $db->table('logbook lb')
                ->select('lb.*, m.nama_lengkap, pm.nim')
                ->join('profil_magang pm', 'pm.id_profil = lb.id_profil')
                ->join('mahasiswa m', 'm.nim = pm.nim')
                ->where('pm.id_unit', $idUnit)
                ->orderBy("CASE WHEN lb.approval_pembimbing = 'Pending' THEN 0 ELSE 1 END", "ASC")
                ->orderBy('lb.tanggal', 'DESC')
                ->get()
                ->getResultArray()
        ];

        return view('mitra/kelola_logbook', $data);
    }

    // ==========================
    // 🔹 VALIDASI LOGBOOK (SETUJU)
    // ==========================
    public function validasi($id_logbook)
    {
        $logbook = $this->logbookModel->find($id_logbook);
        if (!$logbook) {
            return redirect()->back()->with('error', 'Logbook tidak ditemukan.');
        }

        $this->logbookModel->update($id_logbook, [
            'approval_pembimbing' => 'Disetujui',
            'tanggal_approval' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Logbook berhasil divalidasi.');
    }

    // ==========================
    // 🔹 TOLAK LOGBOOK
    // ==========================
    public function tolak()
    {
        $id_logbook = $this->request->getPost('id_logbook');
        $catatan = $this->request->getPost('catatan');

        $logbook = $this->logbookModel->find($id_logbook);
        if (!$logbook) {
            return redirect()->back()->with('error', 'Logbook tidak ditemukan.');
        }

        $this->logbookModel->update($id_logbook, [
            'approval_pembimbing' => 'Ditolak',
            'catatan_pembimbing' => $catatan,
            'tanggal_approval' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setStatusCode(200)->setBody('success');
    }

    // Menampilkan daftar logbook mahasiswa untuk mitra
    public function logbook($nim)
    {
        // Pastikan role mitra
        if ($this->session->get('role') !== 'mitra') {
            return redirect()->to('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $idUnitLogin = $this->session->get('id_unit');

        // Ambil profil mahasiswa dari unit mitra yang login
        $profil = $this->profilMagangModel
            ->where('nim', $nim)
            ->where('id_unit', $idUnitLogin)
            ->first();

        if (!$profil) {
            return redirect()->back()->with('error', 'Mahasiswa tidak ditemukan atau bukan bagian dari unit Anda.');
        }

        // Ambil semua logbook mahasiswa tersebut
        $data = [
            'title' => 'Logbook Harian Mahasiswa',
            'logbooks' => $this->logbookModel
                ->where('id_profil', $profil['id_profil'])
                ->orderBy('tanggal', 'DESC')
                ->findAll(),
            'profil' => $profil
        ];

        return view('mitra/logbook', $data);
    }

}
