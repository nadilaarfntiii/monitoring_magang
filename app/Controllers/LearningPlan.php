<?php

namespace App\Controllers;


use App\Models\LearningPlanModel;
use App\Models\ProfilMagangModel;
use App\Models\AktivitasPembelajaranModel;
use App\Models\JamKerjaUnitModel;
use CodeIgniter\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;

class LearningPlan extends BaseController
{
    protected $jamKerjaModel;
    protected $learningPlanModel;
    protected $profilMagangModel;
    protected $aktivitasModel;
    protected $session;

    public function __construct()
    {
        $this->jamKerjaModel = new JamKerjaUnitModel();
        $this->learningPlanModel = new LearningPlanModel();
        $this->profilMagangModel = new ProfilMagangModel();
        $this->aktivitasModel = new AktivitasPembelajaranModel();
        $this->session = session();
    }

    // ============================
    // INDEX (Daftar LP Mahasiswa)
    // ============================
    public function index()
    {
        $nim = $this->session->get('nim');
        $profil = $this->profilMagangModel->getProfilFull($nim);

        if (!$profil) {
            return redirect()->back()->with('error', 'Profil magang tidak ditemukan.');
        }

        $profil['semester'] = $this->hitungSemester($profil['angkatan'] ?? null);

        // === CEK APAKAH SUDAH ADA DRAFT LP ===
        $existingPlan = $this->learningPlanModel
            ->where('id_profil', $profil['id_profil'])
            ->orderBy('id_lp', 'DESC')
            ->first();

            $isReadonly = false;

            if ($existingPlan) {
                $pembimbing = $existingPlan['status_approval_pembimbing'];
                $kaprodi = $existingPlan['status_approval_kaprodi'];
            
                // Kalau status LP Menunggu atau Disetujui di salah satu reviewer -> readonly
                if (
                    ($pembimbing === 'Menunggu' || $pembimbing === 'Disetujui') &&
                    ($kaprodi === 'Menunggu' || $kaprodi === 'Disetujui')
                ) {
                    $isReadonly = true;
                } else {
                    $isReadonly = false; // termasuk Draft atau Ditolak
                }
            }
            

        $aktivitas = [];
        if ($existingPlan) {
            // Ambil aktivitas yang terkait dengan draft
            $aktivitas = $this->aktivitasModel
                ->where('id_lp', $existingPlan['id_lp'])
                ->findAll();
        }

        // Ambil jam kerja unit
        $jamKerjaList = $this->jamKerjaModel
            ->where('id_unit', $profil['id_unit'])
            ->findAll();

        $jamKerjaText = $this->formatJamKerja($jamKerjaList);

        $data = [
            'title' => 'Buat Learning Plan',
            'user_name' => $this->getUserName(),
            'profil' => $profil,
            'learningPlan' => $existingPlan,
            'aktivitas' => $aktivitas,
            'jamKerja' => $jamKerjaText,
            'isReadonly' => $isReadonly
        ];

        // Tentukan view berdasarkan id_program
        switch ($profil['id_program']) {
            case 2:
                $view = 'mahasiswa/learning_plan_mengajar';
                break;
            case 3:
                $view = 'mahasiswa/learning_plan_adopsi';
                break;
            default:
                $view = 'mahasiswa/learning_plan';
        }

        return view($view, $data);
    }

    // ============================
    // CREATE (Form Buat LP)
    // ============================
    public function create()
    {
        $nim = $this->session->get('nim');
        $profil = $this->profilMagangModel->getProfilFull($nim);

        if (!$profil) {
            return redirect()->back()->with('error', 'Profil magang tidak ditemukan.');
        }

        $profil['semester'] = $this->hitungSemester($profil['angkatan'] ?? null);

        // === CEK APAKAH SUDAH ADA DRAFT LP ===
        $existingPlan = $this->learningPlanModel
            ->where('id_profil', $profil['id_profil'])
            ->orderBy('id_lp', 'DESC')
            ->first();

            $isReadonly = false;

            if ($existingPlan) {
                $pembimbing = $existingPlan['status_approval_pembimbing'];
                $kaprodi = $existingPlan['status_approval_kaprodi'];
            
                // Kalau status LP Menunggu atau Disetujui di salah satu reviewer -> readonly
                if (
                    ($pembimbing === 'Menunggu' || $pembimbing === 'Disetujui') &&
                    ($kaprodi === 'Menunggu' || $kaprodi === 'Disetujui')
                ) {
                    $isReadonly = true;
                } else {
                    $isReadonly = false; // termasuk Draft atau Ditolak
                }
            }
            

        $aktivitas = [];
        if ($existingPlan) {
            // Ambil aktivitas yang terkait dengan draft
            $aktivitas = $this->aktivitasModel
                ->where('id_lp', $existingPlan['id_lp'])
                ->findAll();
        }

        // Ambil jam kerja unit
        $jamKerjaList = $this->jamKerjaModel
            ->where('id_unit', $profil['id_unit'])
            ->findAll();

        $jamKerjaText = $this->formatJamKerja($jamKerjaList);

        $data = [
            'title' => 'Buat Learning Plan',
            'profil' => $profil,
            'learningPlan' => $existingPlan,
            'aktivitas' => $aktivitas,
            'jamKerja' => $jamKerjaText,
            'isReadonly' => $isReadonly
        ];

        return view('mahasiswa/learning_plan', $data);
    }

    // ============================
    // STORE (Simpan LP) MAGANG DU/DI
    // ============================
    public function store()
    {
        try {
            $post = $this->request->getPost();

            if (empty($post['id_profil'])) {
                return redirect()->back()->with('error', 'Profil mahasiswa tidak ditemukan.');
            }

            $id_profil = $post['id_profil'];
            $action = $post['action'] ?? 'draft'; // tombol draft / submit

            // ✅ Validasi hanya saat "Kirim"
            if ($action === 'submit' && empty(trim($post['deskripsi_pekerjaan'] ?? ''))) {
                return redirect()->back()->with('error', 'Deskripsi pekerjaan wajib diisi sebelum mengirim data.');
            }

            // Tentukan status berdasarkan tombol
            if ($action === 'submit') {
                $statusPembimbing = 'Menunggu';
                $statusKaprodi = 'Menunggu';
                $pesan = 'Learning Plan berhasil dikirim untuk persetujuan.';
            } else {
                $statusPembimbing = 'Draft';
                $statusKaprodi = 'Draft';
                $pesan = 'Learning Plan disimpan sebagai draft.';
            }

            // Cek apakah sudah ada LP sebelumnya
            $existingLP = $this->learningPlanModel
                ->where('id_profil', $id_profil)
                ->first();

            $dataLP = [
                'id_profil' => $id_profil,
                'deskripsi_pekerjaan' => $post['deskripsi_pekerjaan'] ?? null,
                'capaian_magang' => $post['capaian_magang'] ?? null,
                'capaian_mata_kuliah' => $post['capaian_mk_khusus'] ?? null,
                'status_approval_pembimbing' => $statusPembimbing,
                'status_approval_kaprodi' => $statusKaprodi,
                // Jika status draft/menunggu, catatan otomatis null
                'catatan_pembimbing' => ($statusPembimbing === 'Menunggu' || $statusPembimbing === 'Draft') ? null : ($post['catatan_pembimbing'] ?? null),
                'catatan_kaprodi' => ($statusKaprodi === 'Menunggu' || $statusKaprodi === 'Draft') ? null : ($post['catatan_kaprodi'] ?? null)
            ];

            if ($existingLP) {
                $this->learningPlanModel->update($existingLP['id_lp'], $dataLP);
                $id_lp = $existingLP['id_lp'];
                // hapus aktivitas lama dulu biar tidak duplikat
                $this->aktivitasModel->where('id_lp', $id_lp)->delete();
            } else {
                $this->learningPlanModel->insert($dataLP);
                $id_lp = $this->learningPlanModel->where('id_profil', $id_profil)->get()->getRowArray()['id_lp'];
            }

            // =========================
            // SIMPAN AKTIVITAS MAGANG
            // =========================

            $kompetensiMagang = [
                1 => 'Kemampuan dalam merekam data/mengelola sebuah aplikasi dalam organisasi',
                2 => 'Kemampuan dalam menganalisis permasalahan pada sistem yang berjalan yang berkaitan dengan Teknik Informatika/Sistem Informasi',
                3 => 'Kemampuan dalam menganalisis kebutuhan sistem/persyaratan sistem baru/aplikasi baru yang dibutuhkan organisasi',
                4 => 'Kemampuan dalam mengevaluasi proses bisnis dan strategi perusahaan untuk merancang sistem bagi organisasi',
                5 => 'Kemampuan dalam merancang sistem baru/aplikasi baru atau pengembangan sistem/aplikasi yang sudah ada sesuai dengan kebutuhan organisasi'
            ];

            $urutan = 1;
            for ($i = 1; $i <= 5; $i++) {
                $dicentang = isset($post['dicentang'][$i]) ? 1 : 0;

                $this->aktivitasModel->insert([
                    'id_lp' => $id_lp,
                    'tipe' => 'Magang',
                    'kompetensi' => $kompetensiMagang[$i],
                    'dicentang' => $dicentang,
                    'urutan' => $urutan++
                ]);
            }


            // =========================
            // SIMPAN AKTIVITAS MATA KULIAH
            // =========================

            $kompetensiMK = [
                1 => 'Kemampuan memahami jalur komunikasi antara pimpinan dan staff dalam organisasi',
                2 => 'Kemampuan menganalisis sistem yang berjalan dalam perusahaan/instansi guna mengetahui permasalahan dan persyaratan sistem baru/hasil pengembangan dari sistem yang sudah ada',
                3 => 'Kemampuan menggambarkan sistem yang berjalan dengan menggunakan tools tertentu (Flowchart/UML)',
                4 => 'Kemampuan mendesain sistem baru/aplikasi baru dengan menggunakan tools tertentu (Flowchart/Storyboard/UML/LKT)'
            ];

            $urutan = 1;
            for ($i = 1; $i <= 4; $i++) {
                $dicentang = isset($post['mk_khusus'][$i]) ? 1 : 0;

                $this->aktivitasModel->insert([
                    'id_lp' => $id_lp,
                    'tipe' => 'Mata Kuliah',
                    'kompetensi' => $kompetensiMK[$i],
                    'dicentang' => $dicentang,
                    'urutan' => $urutan++
                ]);
            }

            return redirect()->to('mahasiswa/learning_plan')->with('success', $pesan);

        } catch (\Throwable $e) {
            // tampilkan error real untuk debugging sementara
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    // ==============================================
    // MENYIMPAN IWIMA MENGAJAR | TEACHING ASSISTANT
    // ==============================================
    public function storeMengajar()
    {
        try {
            $post = $this->request->getPost();

            if (empty($post['id_profil'])) {
                return redirect()->back()->with('error', 'Profil mahasiswa tidak ditemukan.');
            }

            $id_profil = $post['id_profil'];
            $submitType = $post['submit_type'] ?? 'draft'; // nama tombol baru
            $isSubmit = ($submitType === 'submit');

            // Validasi wajib isi jika submit
            if ($isSubmit && empty(trim($post['nama_kegiatan'] ?? ''))) {
                return redirect()->back()->with('error', 'Nama kegiatan wajib diisi sebelum mengirim data.');
            }

            // Tentukan status approval
            if ($isSubmit) {
                $statusPembimbing = 'Menunggu';
                $statusKaprodi = 'Menunggu';
                $pesan = 'Learning Plan Teaching Assistant berhasil dikirim untuk persetujuan.';
            } else {
                $statusPembimbing = 'Draft';
                $statusKaprodi = 'Draft';
                $pesan = 'Learning Plan Teaching Assistant disimpan sebagai draft.';
            }

            // Cek apakah sudah ada LP sebelumnya
            $existingLP = $this->learningPlanModel
                ->where('id_profil', $id_profil)
                ->orderBy('id_lp', 'DESC')
                ->first();

            // Data utama LP
            $dataLP = [
                'id_profil' => $id_profil,
                'nama_kegiatan'      => $post['nama_kegiatan'] ?? null,
                'pelaksana_kegiatan' => $post['pelaksana_kegiatan'] ?? null,
                'uraian_kegiatan'    => $post['uraian_kegiatan'] ?? null,
                'metode_media'       => $post['metode_media'] ?? null,
                'rtl_kegiatan'       => $post['rtl_kegiatan'] ?? null,
                'situation'          => $post['situation'] ?? null,
                'task'               => $post['task'] ?? null,
                'action'             => $post['action'] ?? null, // 🟢 isi dari textarea Action (Aksi)
                'result'             => $post['result'] ?? null,
                'capaian_magang'     => $post['capaian_magang'] ?? null,
                'status_approval_pembimbing' => $statusPembimbing,
                'status_approval_kaprodi'    => $statusKaprodi,
                'catatan_pembimbing' => null,
                'catatan_kaprodi'    => null,
                'updated_at'         => date('Y-m-d H:i:s')
            ];

            // Simpan / update learning plan
            if ($existingLP) {
                $this->learningPlanModel->update($existingLP['id_lp'], $dataLP);
                $id_lp = $existingLP['id_lp'];
                $this->aktivitasModel->where('id_lp', $id_lp)->delete();
            } else {
                $this->learningPlanModel->insert($dataLP);
                $id_lp = $this->learningPlanModel->where('id_profil', $id_profil)->get()->getRowArray()['id_lp'];
                // dd($id_lp);
            }

            // ============================================
            // SIMPAN AKTIVITAS (Hard Skills dan Soft Skills)
            // ============================================
            if (!empty($post['dicentang'])) {
                $kompetensiMengajar = [
                    1 => 'Kemampuan menyusun RPP sesuai kurikulum dan kebutuhan peserta didik',
                    2 => 'Kemampuan melaksanakan pengajaran sesuai bidang kompetensi',
                    3 => 'Penguasaan materi ajar',
                    4 => 'Kemampuan mengintegrasikan teknologi dalam pengajaran',
                    5 => 'Kemampuan menghasilkan laporan dari aktivitas mengajar',
                    6 => 'Kemampuan berinteraksi dengan peserta didik',
                    7 => 'Kemampuan bekerjasama dalam tim',
                    8 => 'Kepemimpinan, kedisiplinan, dan tanggung jawab dalam melaksanakan tugas'
                ];

                $kategori = [
                    1 => 'Hard Skills',
                    2 => 'Hard Skills',
                    3 => 'Hard Skills',
                    4 => 'Hard Skills',
                    5 => 'Hard Skills',
                    6 => 'Soft Skills',
                    7 => 'Soft Skills',
                    8 => 'Soft Skills'
                ];

                $selected = $post['dicentang'] ?? []; // yg dicentang

                $urutan = 1;
                foreach ($post['dicentang'] as $key => $val) {
                    if (isset($kompetensiMengajar[$key])) {
                        $this->aktivitasModel->insert([
                            'id_lp' => $id_lp,
                            'tipe'  => 'Magang',
                            'kompetensi' => $kategori[$key],
                            'kompetensi_teknis' => $kompetensiMengajar[$key],
                            'dicentang' => isset($selected[$key]) ? 1 : 0,
                            'urutan' => $urutan++
                        ]);
                    }
                }
            }

            return redirect()->to('mahasiswa/learning_plan')->with('success', $pesan);

        } catch (\Throwable $e) {
            dd($e->getMessage(), $e->getFile(), $e->getLine());
        }
    }

    // ============================
    // STORE (Simpan LP) IWIMA MENGAJAR - ADOPSI TEKNOLOGI
    // ============================
    public function storeAdopsi()
    {
        try {
            $post = $this->request->getPost();

            if (empty($post['id_profil'])) {
                return redirect()->back()->with('error', 'Profil mahasiswa tidak ditemukan.');
            }

            $id_profil = $post['id_profil'];
            $action = $post['action'] ?? 'draft'; // tombol draft / submit

            // ✅ Validasi hanya saat "Kirim"
            if ($action === 'submit' && empty(trim($post['deskripsi_pekerjaan'] ?? ''))) {
                return redirect()->back()->with('error', 'Deskripsi tema adopsi teknologi wajib diisi sebelum mengirim data.');
            }

            // Tentukan status berdasarkan tombol
            if ($action === 'submit') {
                $statusPembimbing = 'Menunggu';
                $statusKaprodi = 'Menunggu';
                $pesan = 'Learning Plan IWIMA Mengajar berhasil dikirim untuk persetujuan.';
            } else {
                $statusPembimbing = 'Draft';
                $statusKaprodi = 'Draft';
                $pesan = 'Learning Plan IWIMA Mengajar disimpan sebagai draft.';
            }

            // Cek apakah sudah ada LP sebelumnya
            $existingLP = $this->learningPlanModel
                ->where('id_profil', $id_profil)
                ->first();

            $dataLP = [
                'id_profil' => $id_profil,
                'deskripsi_pekerjaan' => $post['deskripsi_pekerjaan'] ?? null,
                'capaian_magang' => $post['capaian_magang'] ?? null,
                'capaian_mata_kuliah' => $post['capaian_mk_khusus'] ?? null,
                'status_approval_pembimbing' => $statusPembimbing,
                'status_approval_kaprodi' => $statusKaprodi,
                'catatan_pembimbing' => ($statusPembimbing === 'Menunggu' || $statusPembimbing === 'Draft') ? null : ($post['catatan_pembimbing'] ?? null),
                'catatan_kaprodi' => ($statusKaprodi === 'Menunggu' || $statusKaprodi === 'Draft') ? null : ($post['catatan_kaprodi'] ?? null),
            ];

            if ($existingLP) {
                $this->learningPlanModel->update($existingLP['id_lp'], $dataLP);
                $id_lp = $existingLP['id_lp'];

                // Hapus aktivitas lama biar tidak duplikat
                $this->aktivitasModel->where('id_lp', $id_lp)->delete();
            } else {
                $this->learningPlanModel->insert($dataLP);
                $id_lp = $this->learningPlanModel->where('id_profil', $id_profil)->get()->getRowArray()['id_lp'];
            }

            // =============================
            // SIMPAN AKTIVITAS MAGANG
            // =============================
            if (!empty($post['dicentang'])) {
                $kompetensiMagang = [
                    1 => 'Kemampuan dalam merekam data/mengelola sebuah aplikasi dalam organisasi',
                    2 => 'Kemampuan dalam menganalisis permasalahan pada sistem yang berjalan yang berkaitan dengan Teknik Informatika/Sistem Informasi',
                    3 => 'Kemampuan dalam menganalisis kebutuhan sistem/persyaratan sistem baru/aplikasi baru yang dibutuhkan organisasi',
                    4 => 'Kemampuan dalam mengevaluasi proses bisnis dan strategi Sekolah untuk merancang sistem bagi organisasi',
                    5 => 'Kemampuan dalam merancang sistem baru/aplikasi baru atau pengembangan sistem/aplikasi yang sudah ada sesuai dengan kebutuhan organisasi',
                ];

                $urutan = 1;
                foreach ($post['dicentang'] as $key => $val) {
                    $this->aktivitasModel->insert([
                        'id_lp' => $id_lp,
                        'tipe' => 'Magang',
                        'kompetensi' => $kompetensiMagang[$key] ?? '-',
                        'dicentang' => 1,
                        'urutan' => $urutan++,
                    ]);
                }
            }

            // =============================
            // SIMPAN AKTIVITAS MATA KULIAH
            // =============================
            if (!empty($post['mk_khusus'])) {
                $kompetensiMK = [
                    1 => 'Kemampuan memahami jalur komunikasi antara pimpinan dan staff dalam organisasi',
                    2 => 'Kemampuan menganalisis sistem yang berjalan dalam Sekolah/instansi guna mengetahui permasalahan dan persyaratan sistem baru/hasil pengembangan dari sistem yang sudah ada',
                    3 => 'Kemampuan menggambarkan sistem yang berjalan dengan menggunakan tools tertentu (Flowchart/UML)',
                    4 => 'Kemampuan mendesain sistem baru/aplikasi baru dengan menggunakan tools tertentu (Flowchart/Storyboard/UML/LKT)',
                    5 => 'Kemampuan membangun sistem berdasarkan desain sistem yang dihasilkan',
                ];

                $urutan = 1;
                foreach ($post['mk_khusus'] as $key => $val) {
                    $this->aktivitasModel->insert([
                        'id_lp' => $id_lp,
                        'tipe' => 'Mata Kuliah',
                        'kompetensi' => $kompetensiMK[$key] ?? '-',
                        'dicentang' => 1,
                        'urutan' => $urutan++,
                    ]);
                }
            }

            return redirect()->to('mahasiswa/learning_plan')->with('success', $pesan);

        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    } 

    // ============================
    // HELPER: Hitung Semester
    // ============================
    private function hitungSemester($angkatan)
    {
        if (empty($angkatan)) return '-';

        $angkatan = (int) substr($angkatan, 0, 4);
        $currentYear = date('Y');
        $currentMonth = date('n');
        $yearsPassed = $currentYear - $angkatan;

        // Ganjil: Sep–Feb, Genap: Mar–Agu
        return ($currentMonth >= 9 || $currentMonth <= 2)
            ? ($yearsPassed * 2) + 1
            : ($yearsPassed * 2) + 2;
    }

    // ============================
    // HELPER: Format Tanggal Indo
    // ============================
    private function formatTanggal($tanggal)
    {
        if (!$tanggal || $tanggal == "0000-00-00") return "-";

        $bulanIndo = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $tgl = date('d', strtotime($tanggal));
        $bln = $bulanIndo[(int) date('m', strtotime($tanggal))];
        $thn = date('Y', strtotime($tanggal));

        return "$tgl $bln $thn";
    }


    // ============================
    // HELPER: Format Jam Kerja
    // ============================
    private function formatJamKerja($jamKerjaList)
    {
        if (empty($jamKerjaList)) {
            return '-';
        }

        // Filter hanya hari kerja
        $hariKerja = array_filter($jamKerjaList, fn($j) => strtolower($j['status_hari']) === 'kerja');

        if (empty($hariKerja)) {
            return '-';
        }

        // Urutkan hari agar berurutan Senin–Minggu
        $urutanHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        usort($hariKerja, function ($a, $b) use ($urutanHari) {
            return array_search($a['hari'], $urutanHari) <=> array_search($b['hari'], $urutanHari);
        });

        // Kelompokkan berdasarkan jam_masuk dan jam_pulang
        $grup = [];
        foreach ($hariKerja as $row) {
            $key = "{$row['jam_masuk']}-{$row['jam_pulang']}";
            $grup[$key][] = $row['hari'];
        }

        // Buat teks hasil (tiap grup di baris baru)
        $hasil = [];
        foreach ($grup as $key => $days) {
            [$masuk, $pulang] = explode('-', $key);
            $masuk = substr($masuk, 0, 5);
            $pulang = substr($pulang, 0, 5);

            if (count($days) > 1) {
                $hasil[] = reset($days) . ' – ' . end($days) . " {$masuk} – {$pulang}";
            } else {
                $hasil[] = $days[0] . " {$masuk} – {$pulang}";
            }
        }

        // Gunakan baris baru (\n) antar jadwal
        return implode("\n", $hasil);
    }

    public function cetak($id_lp)
    {
        $lp = $this->learningPlanModel->find($id_lp);

        if (!$lp) {
            return redirect()->back()->with('error', 'Learning Plan tidak ditemukan.');
        }

        // PROFIL DASAR
        $profilDasar = $this->profilMagangModel->find($lp['id_profil']);
        if (!$profilDasar) {
            return redirect()->back()->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        // PROFIL LENGKAP
        $profil = $this->profilMagangModel->getProfilFull($profilDasar['nim']);
        if (!$profil) {
            return redirect()->back()->with('error', 'Profil lengkap tidak ditemukan.');
        }

        // AMBIL JAM KERJA
        $jamKerjaList = $this->jamKerjaModel
            ->where('id_unit', $profil['id_unit'])
            ->findAll();

        $jamKerja = $this->formatJamKerja($jamKerjaList);

        // AKTIVITAS (khusus Magang)
        $aktivitasMagang = $this->aktivitasModel
        ->where('id_lp', $id_lp)
        ->where('tipe', 'Magang')
        ->orderBy('urutan', 'ASC')
        ->findAll();

        $aktivitasMK = $this->aktivitasModel
        ->where('id_lp', $id_lp)
        ->where('tipe', 'Mata Kuliah')
        ->orderBy('urutan', 'ASC')
        ->findAll();

        // PILIH VIEW
        switch ($profil['id_program']) {
            case 2:  $view = 'mahasiswa/cetak_lp_mengajar'; break;
            case 3:  $view = 'mahasiswa/cetak_lp_adopsi'; break;
            default: $view = 'mahasiswa/cetak_lp_magang';
        }

        // Format tanggal mulai & selesai
        $profil['tanggal_mulai'] = $this->formatTanggal($profil['tanggal_mulai'] ?? null);
        $profil['tanggal_selesai'] = $this->formatTanggal($profil['tanggal_selesai'] ?? null);

        // =============================================
        //  KONVERSI LOGO KE BASE64 AGAR DOMPDF BISA BACA
        // =============================================
        $pathLogo = FCPATH . 'assets/images/logo-iwima.jpg';
        if (file_exists($pathLogo)) {
            $logoBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($pathLogo));
        } else {
            $logoBase64 = '';
        }

        // =============================================
        //  RENDER VIEW KE HTML
        // =============================================
        $html = view($view, [
            'lp' => $lp,
            'profil' => $profil,
            'aktivitasMagang' => $aktivitasMagang,
            'aktivitasMK' => $aktivitasMK,
            'jamKerja' => $jamKerja,
            'logoBase64' => $logoBase64
        ]);

        // DOMPDF
        $dompdf = new Dompdf();
        $dompdf->setBasePath(base_url());
        $dompdf->loadHtml($html);

        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->set_option('defaultMediaType', 'print');
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);

        $dompdf->set_option('defaultFont', 'DejaVu Sans');

        // KERTAS LANDSCAPE
        $dompdf->setPaper('F4', 'landscape');

        $dompdf->render();

        return $dompdf->stream(
            'LearningPlan_' . $profil['nama_lengkap'] . '.pdf',
            ["Attachment" => false]
        );
    }
}
