<?php

namespace App\Controllers;

use App\Models\PresensiMahasiswaModel;
use App\Models\ProfilMagangModel;
use CodeIgniter\Controller;

class Presensi extends BaseController
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
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'mahasiswa') {
            return redirect()->to('/login');
        }

        $nim = $this->session->get('nim');
        $this->tandaiTidakHadir();

        $db = db_connect();

        // Ambil profil magang mahasiswa
        $profil = $this->profilMagangModel
            ->where('nim', $nim)
            ->where('deleted_at', null)
            ->first();

        // Siapkan mapping hari Inggris → Indonesia
        $hariMap = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu'
        ];

        $hariSekarang = date('l'); // Output: Monday, Tuesday, ...
        $hariIndo = $hariMap[$hariSekarang]; // Konversi ke bahasa Indonesia

        if ($profil) {
            $id_unit = $profil['id_unit'];

            // Ambil jam kerja berdasarkan unit dan hari Indonesia
            $jamKerja = $db->table('jam_kerja_unit')
                ->where('id_unit', $id_unit)
                ->where('hari', $hariIndo)
                ->get()
                ->getRowArray();

            $data['jamKerjaHariIni'] = $jamKerja;
        } else {
            $data['jamKerjaHariIni'] = null;
        }

        // Ambil data presensi mahasiswa
        $data['presensi'] = $this->presensiModel
        ->where('nim', $nim)
        ->orderBy('tanggal', 'DESC')
        ->orderBy('waktu_masuk', 'DESC')
        ->findAll();

        $data['user_name'] = $this->getUserName();

        return view('mahasiswa/presensi', $data);
    }


    public function simpanMasuk()
    {
        date_default_timezone_set('Asia/Jakarta');
        $db = db_connect();

        $nim = $this->session->get('nim');
        if (!$nim) {
            return redirect()->to('/login');
        }

        $tanggal = date('Y-m-d');

        // Cek apakah mahasiswa sudah absen hari ini
        $cekPresensi = $this->presensiModel
            ->where('nim', $nim)
            ->where('tanggal', $tanggal)
            ->first();

        if ($cekPresensi) {
            return redirect()->back()->with('error', 'Anda sudah melakukan presensi hari ini!');
        }

        // Ambil profil magang mahasiswa
        $profil = $this->profilMagangModel
            ->where('nim', $nim)
            ->where('deleted_at', null)
            ->first();

        if (!$profil) {
            return redirect()->back()->with('error', 'Data profil magang tidak ditemukan.');
        }

        $id_unit = $profil['id_unit'];

        // === Tentukan hari ini (dalam Bahasa Indonesia) ===
        $hariMap = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu'
        ];
        $hariSekarang = date('l');
        $hariIndo = $hariMap[$hariSekarang];

        // === Ambil jam kerja berdasarkan unit dan hari ===
        $jamKerja = $db->table('jam_kerja_unit')
            ->where('id_unit', $id_unit)
            ->where('hari', $hariIndo)
            ->get()
            ->getRow();

        // === Validasi jika jam kerja tidak ditemukan atau hari libur ===
        if (!$jamKerja) {
            return redirect()->back()->with('error', 'Data jam kerja untuk hari ini belum diatur.');
        }

        if ($jamKerja->status_hari === 'Libur') {
            return redirect()->back()->with('error', 'Hari ini adalah hari libur. Anda tidak perlu melakukan presensi.');
        }

        $id_jam_kerja = $jamKerja->id_jam_kerja;

        // === Ambil input keterangan ===
        $keterangan = $this->request->getPost('keterangan');
        $waktu_masuk = date('Y-m-d H:i:s');
        $status_presensi = 'Menunggu Validasi';
        $status_kehadiran = null;

        // === Buat ID presensi unik ===
        $id_presensi = 'PRS' . time();

        // === Upload foto bukti (jika ada) ===
        $foto = $this->request->getFile('foto_bukti');
        $namaFoto = null;

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $ext = strtolower($foto->getClientExtension());

            if (!in_array($ext, $allowedExtensions)) {
                return redirect()->back()->with('error', 'Format foto tidak valid. Gunakan JPG, JPEG, atau PNG.');
            }

            if ($foto->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->with('error', 'Ukuran foto maksimal 2MB.');
            }

            $namaFoto = $id_presensi . '.' . $ext;
            $foto->move('uploads/presensi', $namaFoto);
        }

        // === Tentukan status kehadiran ===
        if ($keterangan == 'Masuk') {
            $jamMasukUnit = strtotime($jamKerja->jam_masuk);
            $jamMasukUser = strtotime(date('H:i:s'));
            $status_kehadiran = ($jamMasukUser > $jamMasukUnit) ? 'Telat' : 'Tepat Waktu';
        } elseif (in_array($keterangan, ['Izin', 'Sakit'])) {
            $status_kehadiran = 'Tidak Hadir';
        }

        // === Simpan presensi ke database ===
        $this->presensiModel->insert([
            'id_presensi'      => $id_presensi,
            'nim'              => $nim,
            'id_jam_kerja'     => $id_jam_kerja,
            'tanggal'          => $tanggal,
            'waktu_masuk'      => $waktu_masuk,
            'keterangan'       => $keterangan,
            'foto_bukti'       => $namaFoto,
            'status_kehadiran' => $status_kehadiran,
            'status_presensi'  => $status_presensi
        ]);

        return redirect()->to(base_url('presensi'))->with('success', 'Presensi berhasil disimpan!');
    }



    /**
     * Tandai otomatis mahasiswa yang tidak absen sama sekali hari ini
     */
    protected function tandaiTidakHadir()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tanggal = date('Y-m-d');
        $hariSekarang = date('l');

        // Mapping hari Inggris ke Indonesia
        $hariMap = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu'
        ];
        $hariIndo = $hariMap[$hariSekarang];

        $db = db_connect();

        // Ambil semua mahasiswa yang aktif magang
        $mahasiswa = $db->table('profil_magang')
            ->select('profil_magang.nim, profil_magang.id_unit')
            ->where('profil_magang.deleted_at', null)
            ->get()
            ->getResultArray();

        foreach ($mahasiswa as $m) {
            $nim = $m['nim'];
            $id_unit = $m['id_unit'];

            // Cek apakah sudah absen hari ini
            $cek = $this->presensiModel
                ->where('nim', $nim)
                ->where('tanggal', $tanggal)
                ->first();

            if ($cek) continue; // Sudah absen → skip

            // Ambil jam kerja berdasarkan hari ini
            $jamKerja = $db->table('jam_kerja_unit')
                ->where('id_unit', $id_unit)
                ->where('hari', $hariIndo)
                ->get()
                ->getRow();

            // Jika tidak ada data jam kerja atau status hari = Libur → skip
            if (!$jamKerja || $jamKerja->status_hari === 'Libur') {
                continue;
            }

            // Hanya tandai Alpha jika waktu sudah lewat jam pulang
            $jamSekarang = strtotime(date('H:i:s'));
            $jamPulangUnit = strtotime($jamKerja->jam_pulang);

            if ($jamSekarang > $jamPulangUnit) {
                $this->presensiModel->insert([
                    'id_presensi'      => 'PRS' . uniqid(),
                    'nim'              => $nim,
                    'id_jam_kerja'     => $jamKerja->id_jam_kerja,
                    'tanggal'          => $tanggal,
                    'waktu_masuk'      => null,
                    'status_kehadiran' => 'Tidak Hadir',
                    'keterangan'       => 'Alpha',
                    'status_presensi'  => 'Otomatis'
                ]);
            }
        }
    }


    public function simpanPulang()
    {
        date_default_timezone_set('Asia/Jakarta');
        $nim = $this->session->get('nim'); // Ambil NIM dari session
        $tanggal = date('Y-m-d');           // Tanggal hari ini
        $waktuKeluar = date('Y-m-d H:i:s'); // Waktu lengkap saat klik tombol

        // Ambil presensi mahasiswa hari ini
        $presensi = $this->presensiModel
            ->where('nim', $nim)
            ->where('tanggal', $tanggal)
            ->first();

        if (!$presensi) {
            return redirect()->back()->with('error', 'Anda belum melakukan absen masuk hari ini!');
        }

        // Cek apakah absen pulang sudah terisi
        if (!empty($presensi['waktu_keluar'])) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absen pulang hari ini!');
        }

        // Update waktu keluar
        $this->presensiModel->update($presensi['id_presensi'], [
            'waktu_keluar' => $waktuKeluar,
            'updated_at'   => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Absen pulang berhasil disimpan!');
    }

}
