<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LogbookModel;
use App\Models\ProfilMagangModel;
use App\Models\TugasAkhirMagangModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Logbook extends BaseController
{
    protected $logbookModel;
    protected $profilModel;
    protected $taModel;

    public function __construct()
    {
        $this->logbookModel = new LogbookModel();
        $this->profilModel = new ProfilMagangModel();
        $this->taModel = new TugasAkhirMagangModel();
    }

    // Menampilkan daftar logbook
    public function index()
    {
        $session = session();
        $nim = $session->get('nim'); // ambil NIM dari session
        $profil = $this->profilModel->where('nim', $nim)->first();

        $data = [
            'title' => 'Logbook Harian',
            'user_name' => $this->getUserName(),
            'logbooks' => $this->logbookModel->where('id_profil', $profil['id_profil'])->orderBy('tanggal', 'DESC')->findAll()
        ];

        return view('mahasiswa/logbook', $data);
    }

    public function store()
    {
        $session = session();
        $nim = $session->get('nim');

        if (!$nim) {
            return redirect()->to('/login');
        }

        // Ambil profil mahasiswa
        $profil = $this->profilModel->where('nim', $nim)->first();
        if (!$profil) {
            return redirect()->back()->with('error', 'Profil magang tidak ditemukan.');
        }

        $tanggal = $this->request->getPost('tanggal');

        // === Cek apakah logbook untuk tanggal ini sudah ada ===
        $cekLogbook = $this->logbookModel
            ->where('id_profil', $profil['id_profil'])
            ->where('tanggal', $tanggal)
            ->first();

        if ($cekLogbook) {
            return redirect()->back()->withInput()->with('error', 'Logbook untuk tanggal ini sudah dibuat.');
        }

        // Validasi input
        $validation = $this->validate([
            'tanggal' => 'required|valid_date',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'catatan_aktivitas' => 'required',
            'foto_kegiatan' => 'permit_empty|is_image[foto_kegiatan]|max_size[foto_kegiatan,10240]' // 10 MB
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
        }

        // 1. Insert logbook tanpa foto
        $this->logbookModel->insert([
            'id_profil' => $profil['id_profil'],
            'tanggal' => $tanggal,
            'jam_masuk' => $this->request->getPost('jam_masuk'),
            'jam_pulang' => $this->request->getPost('jam_pulang'),
            'catatan_aktivitas' => $this->request->getPost('catatan_aktivitas'),
            'approval_pembimbing' => 'Pending'
        ]);

        // 2. Ambil ID auto-increment
        $insertID = $this->logbookModel->getInsertID();

        // 3. Ambil record logbook untuk mendapatkan id_logbook final dari trigger
        $logbook = $this->logbookModel->find($insertID);
        $id_logbook_final = $logbook['id_logbook'];

        // 4. Upload foto jika ada
        $foto = $this->request->getFile('foto_kegiatan');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $ext = strtolower($foto->getClientExtension());

            if (!in_array($ext, $allowedExtensions)) {
                return redirect()->back()->with('error', 'Format foto tidak valid. Gunakan JPG, JPEG, atau PNG.');
            }

            if ($foto->getSize() > 10 * 1024 * 1024) { // 10 MB
                return redirect()->back()->with('error', 'Ukuran foto maksimal 10MB.');
            }

            // Gunakan id_logbook final untuk nama file
            $fotoName = $id_logbook_final . '.' . $ext;

            $uploadPath = 'uploads/logbook';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $foto->move($uploadPath, $fotoName);

            // Update record logbook dengan nama file foto
            $this->logbookModel->update($insertID, [
                'foto_kegiatan' => $fotoName
            ]);
        }

        return redirect()->to(base_url('logbook'))->with('success', 'Logbook berhasil disimpan.');
    }


    public function update()
    {
        $id_logbook = $this->request->getPost('id_logbook');
        if (!$id_logbook) {
            return redirect()->back()->with('error', 'ID Logbook tidak ditemukan.');
        }

        // Validasi
        $validation = $this->validate([
            'tanggal' => 'required|valid_date',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'catatan_aktivitas' => 'required',
            'foto_kegiatan' => 'permit_empty|is_image[foto_kegiatan]|max_size[foto_kegiatan,10240]'
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
        }

        // Update data dasar
        $this->logbookModel->update($id_logbook, [
            'tanggal' => $this->request->getPost('tanggal'),
            'jam_masuk' => $this->request->getPost('jam_masuk'),
            'jam_pulang' => $this->request->getPost('jam_pulang'),
            'catatan_aktivitas' => $this->request->getPost('catatan_aktivitas'),
            'approval_pembimbing' => 'Pending' // reset kembali
        ]);

        // === Jika ganti foto ===
        $foto = $this->request->getFile('foto_kegiatan');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {

            $ext = strtolower($foto->getClientExtension());
            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array($ext, $allowed)) {
                return redirect()->back()->with('error', 'Format foto tidak valid.');
            }

            $fotoName = $id_logbook . '.' . $ext;
            $foto->move('uploads/logbook', $fotoName, true);

            $this->logbookModel->update($id_logbook, [
                'foto_kegiatan' => $fotoName
            ]);
        }

        return redirect()->to('/logbook')->with('success', 'Logbook berhasil diperbarui.');
    }

    public function cetak()
    {
        $session = session();
        $nim = $session->get('nim');

        if (!$nim) {
            return redirect()->to('/login');
        }

        $profil = $this->profilModel->getProfilFull($nim);

        if (!$profil) {
            return redirect()->back()->with('error', 'Profil magang tidak ditemukan.');
        }

        // 🔥 Ambil Judul TA dari tabel tugas_akhir_magang
        $judulTA = $this->taModel->getJudulMagang($profil['id_profil']);
        $judulTA_final = $judulTA['judul_ta'] ?? '-';

        // =============================================
        //  KONVERSI LOGO KE BASE64 AGAR DOMPDF BISA BACA
        // =============================================
        $pathLogo = FCPATH . 'assets/images/logo-iwima.jpg';
        if (file_exists($pathLogo)) {
            $logoBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($pathLogo));
        } else {
            $logoBase64 = '';
        }

        // Ambil logbook yang disetujui
        $logbooks = $this->logbookModel
            ->where('id_profil', $profil['id_profil'])
            ->where('approval_pembimbing', 'Disetujui')
            ->orderBy('tanggal', 'ASC')
            ->findAll();

        $html = view('mahasiswa/cetak_lb', [
            'profil' => $profil,
            'logbooks' => $logbooks,
            'logoBase64' => $logoBase64,
            'judulTA' => $judulTA_final
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('F4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Logbook_Magang.pdf", ["Attachment" => false]);
    }

}