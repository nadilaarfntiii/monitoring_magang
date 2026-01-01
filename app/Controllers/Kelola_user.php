<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\UnitModel;

    class Kelola_user extends BaseController
    {

            public function index(): string
        {
            $userModel      = new UserModel();
            $mahasiswaModel = new MahasiswaModel();
            $dosenModel     = new DosenModel();
            $unitModel      = new UnitModel();

            $users = $userModel->findAll();

            foreach ($users as &$u) {
                if ($u['role'] === 'mahasiswa' && !empty($u['nim'])) {
                    $mhs = $mahasiswaModel->find($u['nim']);
                    $u['nama_lengkap'] = $mhs['nama_lengkap'] ?? '-';

                } elseif ($u['role'] === 'dospem' && !empty($u['nppy'])) {
                    $dsn = $dosenModel->find($u['nppy']);
                    $u['nama_lengkap'] = $dsn['nama_lengkap'] ?? '-';

                } elseif ($u['role'] === 'mitra' && !empty($u['id_unit'])) {
                    $unit = $unitModel->find($u['id_unit']);
                    $u['nama_lengkap'] = $unit['nama_pembimbing'] ?? '-'; 

                } elseif ($u['role'] === 'kaprodi' && !empty($u['nppy'])) {
                    $dsn = $dosenModel->where('nppy', $u['nppy'])
                                    ->where('jabatan_fungsional', 'kaprodi')
                                    ->first();
                    $u['nama_lengkap'] = $dsn['nama_lengkap'] ?? '-';

                } else {
                    $u['nama_lengkap'] = $u['username']; // admin atau fallback
                }
            }

            $data['user_name'] = $this->getUserName();
            $data['user']      = $users;
            $data['mahasiswa'] = $mahasiswaModel->findAll();
            $data['dosen']     = $dosenModel->findAll();
            $data['unit']      = $unitModel->findAll();

            return view('admin/kelola_user', $data);
        }


        public function importExcel()
        {
            $file = $this->request->getFile('file_excel');
        
            if (!$file || !$file->isValid()) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'File Excel tidak valid'
                ]);
            }
        
            $ext = $file->getExtension();
            $reader = ($ext == 'xls') ? new \PhpOffice\PhpSpreadsheet\Reader\Xls() : new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        
            $userModel = new \App\Models\UserModel();
            $inserted = 0;
            $skipped = 0;
            $importedAccounts = [];
        
            $header = array_map('strtolower', $sheet[1]);
        
            // Tentukan role
            if (in_array('nim', $header)) $role = 'mahasiswa';
            elseif (in_array('nppy', $header)) $role = 'dospem';
            elseif (in_array('id_unit', $header)) $role = 'mitra';
            elseif (in_array('nama_lengkap', $header)) $role = 'admin';
            else {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Format Excel tidak cocok dengan struktur role pengguna.'
                ]);
            }
        
            helper('user');
        
            for ($i = 2; $i <= count($sheet); $i++) {
                $row = $sheet[$i];
                $rowData = [];
                foreach ($header as $col => $key) {
                    $rowData[$key] = isset($row[$col]) ? trim($row[$col]) : null;
                }
        
                // Skip jika data penting kosong
                if (($role === 'mahasiswa' && empty($rowData['nim'])) ||
                    ($role === 'dospem' && empty($rowData['nppy'])) ||
                    ($role === 'mitra' && empty($rowData['id_unit'])) ||
                    ($role === 'admin' && empty($rowData['nama_lengkap']))) {
                    $skipped++;
                    continue;
                }
        
                // Buat akun default sesuai role
                switch ($role) {
                    case 'mahasiswa':
                        $mahasiswaModel = new \App\Models\MahasiswaModel();
                        $mhs = $mahasiswaModel->find($rowData['nim']);
                        if (!$mhs) { $skipped++; continue 2; }
                        $creds = buatAkunDefault('mahasiswa', $mhs);
                        break;
        
                    case 'dospem':
                        $dosenModel = new \App\Models\DosenModel();
                        $dsn = $dosenModel->where('nppy', $rowData['nppy'])->first();
                        if (!$dsn) { $skipped++; continue 2; }
                        $creds = buatAkunDefault('dospem', $dsn);
                        break;
        
                    case 'kaprodi':
                        $dosenModel = new \App\Models\DosenModel();
                        $dsn = $dosenModel->where('nppy', $rowData['nppy'] ?? '')->first();
                        if (!$dsn) { $skipped++; continue 2; }
                        $creds = buatAkunDefault('kaprodi', $dsn);
                        break;
        
                    case 'mitra':
                        $unitModel = new \App\Models\UnitModel();
                        $unit = $unitModel->find($rowData['id_unit']);
                        if (!$unit) { $skipped++; continue 2; }
                        $creds = buatAkunDefault('mitra', $unit);
                        break;
        
                    case 'admin':
                        // Admin wajib ada kolom nama_lengkap, username & password default bisa dari nama_lengkap
                        $creds = buatAkunDefault('admin', $rowData);
                        break;
        
                    default:
                        $skipped++;
                        continue 2;
                }
        
                $data = [
                    'username'     => $creds['username'],
                    'password'     => $creds['password'], // plain text agar login default berhasil
                    'nama_lengkap' => $rowData['nama_lengkap'] ?? '',
                    'role'         => $role,
                    'status'       => $rowData['status'] ?? 'aktif',
                    'nim'          => $rowData['nim'] ?? null,
                    'nppy'         => $rowData['nppy'] ?? null,
                    'id_unit'      => $rowData['id_unit'] ?? null,
                ];
        
                // Skip jika username sudah ada
                if ($userModel->where('username', $creds['username'])->first()) {
                    $skipped++;
                    continue;
                }
        
                $userModel->insert($data);
                $inserted++;
        
                $importedAccounts[] = [
                    'username' => $creds['username'],
                    'password' => $creds['password']
                ];
            }
        
            return $this->response->setJSON([
                'status' => true,
                'message' => "Import selesai. Berhasil: {$inserted}, Terlewat: {$skipped}",
                'accounts' => $importedAccounts
            ]);
        }
        


        public function simpanAjax()
        {
            helper('user');
            $request = $this->request->getPost();

             // 🔍 DEBUG: log semua isi POST
            log_message('debug', '=== DEBUG simpanAjax ===');
            foreach ($request as $k => $v) {
                log_message('debug', $k . ' => ' . $v);
            }
            log_message('debug', '=========================');

            $role    = $request['role'] ?? '';
            $status  = $request['status'] ?? '';

            if (empty($role) || empty($status)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Role dan Status wajib diisi'
                ]);
            }

            $userModel = new UserModel();
            $dataUser  = [
                'role'   => $role,
                'status' => $status
            ];

            // === ROLE MAHASISWA ===
            if ($role === 'mahasiswa' && !empty($request['nim'])) {
                $mahasiswaModel = new MahasiswaModel();
                $mhs = $mahasiswaModel->find($request['nim']);
                if (!$mhs) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Mahasiswa tidak ditemukan']);
                }
                $creds = buatAkunDefault('mahasiswa', $mhs);
                $dataUser['username'] = $creds['username'];
                $dataUser['password'] = $creds['password'];
                $dataUser['nim']      = $request['nim'];

            // === ROLE DOSEN (DOSPEM) ===
            } elseif ($role === 'dospem' && !empty(trim($request['nppy_dosen'] ?? ''))) {
                $nppy = trim($request['nppy_dosen']);
                $dosenModel = new DosenModel();
                $dsn = $dosenModel->where('nppy', $nppy)->first();
            
                if (!$dsn) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Dosen tidak ditemukan']);
                }
            
                $creds = buatAkunDefault('dospem', $dsn);
                $dataUser['username'] = $creds['username'];
                $dataUser['password'] = $creds['password'];
                $dataUser['nppy']     = $nppy;
            

            // === ROLE KAPRODI ===
            } elseif ($role === 'kaprodi' && !empty(trim($request['nppy_kaprodi'] ?? ''))) {
                $nppy = trim($request['nppy_kaprodi']);
                $dosenModel = new DosenModel();
                $dsn = $dosenModel->where('nppy', $nppy)->first();

                if (!$dsn) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Dosen Kaprodi tidak ditemukan']);
                }

                $creds = buatAkunDefault('kaprodi', $dsn);
                $dataUser['username'] = $creds['username'];
                $dataUser['password'] = $creds['password'];
                $dataUser['nppy']     = $nppy;

            // === ROLE MITRA (unit/perusahaan) ===
            } elseif ($role === 'mitra' && !empty($request['id_unit'])) {
                $unitModel = new UnitModel();
                $unit = $unitModel->find($request['id_unit']);
                if (!$unit) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Unit tidak ditemukan']);
                }
                $creds = buatAkunDefault('mitra', $unit);
                $dataUser['username'] = $creds['username'];
                $dataUser['password'] = $creds['password'];
                $dataUser['id_unit']  = $request['id_unit'];

            // === ROLE ADMIN (manual input) ===
            } elseif ($role === 'admin') {
                $usernameInput = trim($request['username'] ?? '');
                $passwordInput = trim($request['password'] ?? '');
                if (empty($usernameInput) || empty($passwordInput)) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Username dan password harus diisi']);
                }
                $dataUser['username'] = $usernameInput;
                $dataUser['password'] = $passwordInput;

            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data tidak valid atau field masih kosong'
                ]);
            }

            // === Cek apakah username sudah ada ===
            if (!empty($dataUser['username'])) {
                $cekUser = $userModel->where('username', $dataUser['username'])->first();
                if ($cekUser) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Username sudah digunakan'
                    ]);
                }
            }

            // === Insert ke database ===
            $id = $userModel->insert($dataUser);
            if ($id === false) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $userModel->errors()
                ]);
            }

            // Simpan pesan sukses ke flashdata
            session()->setFlashdata('success', 'Data pengguna berhasil ditambahkan');

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'User berhasil ditambahkan',
                'id' => $id,
                'data' => $dataUser
            ]);
        }



        //Cari Mahasiswa
        public function cariMahasiswa()
        {
            $query = $this->request->getGet('q');
            $mahasiswaModel = new MahasiswaModel();

            if (!$query) return $this->response->setJSON([]);

            $data = $mahasiswaModel->like('nama_lengkap', $query)
                                ->orLike('nim', $query)
                                ->findAll(10);

            $results = [];
            foreach ($data as $m) {
                $results[] = [
                    'label' => $m['nim'] . ' - ' . $m['nama_lengkap'],
                    'value' => $m['nim'] . ' - ' . $m['nama_lengkap'],
                    'id'    => $m['nim']
                ];
            }

            return $this->response->setJSON($results);
        }

        //Cari Dosen
        public function cariDosen()
        {
            $query = $this->request->getGet('q');
            $dosenModel = new DosenModel();

            if (!$query) return $this->response->setJSON([]);

            $data = $dosenModel->like('nama_lengkap', $query)
                            ->orLike('nppy', $query)
                            ->findAll(10);

            $results = [];
            foreach ($data as $d) {
                $results[] = [
                    'label' => $d['nppy'] . ' - ' . $d['nama_lengkap'],
                    'value' => $d['nppy'] . ' - ' . $d['nama_lengkap'],
                    'id'    => $d['nppy']
                ];
            }

            return $this->response->setJSON($results);
        }

        //Cari Unit
        public function cariUnit()
        {
            $query = $this->request->getGet('q');
            $unitModel = new UnitModel();

            if (!$query) return $this->response->setJSON([]);

            // Join dengan tabel mitra agar bisa cari berdasarkan nama_mitra
            $data = $unitModel->select('unit.id_unit, unit.nama_unit, mitra.nama_mitra')
                            ->join('mitra', 'mitra.id_mitra = unit.id_mitra', 'left')
                            ->groupStart()
                                ->like('unit.nama_unit', $query)
                                ->orLike('mitra.nama_mitra', $query)
                            ->groupEnd()
                            ->findAll(10);

            $results = [];
            foreach ($data as $u) {
                $results[] = [
                    'label' => $u['nama_mitra'] . ' - ' . $u['nama_unit'],
                    'value' => $u['nama_mitra'] . ' - ' . $u['nama_unit'],
                    'id'    => $u['id_unit']
                ];
            }

            // Debug: kalau masih kosong, tulis ke log
            if(empty($results)){
                log_message('debug', 'Autocomplete Unit kosong untuk query: ' . $query);
            }

            return $this->response->setJSON($results);
        }

        // Cari Kaprodi (khusus dosen dengan jabatan kaprodi)
        public function cariKaprodi()
        {
            $query = $this->request->getGet('q');
            $dosenModel = new DosenModel();

            if (!$query) return $this->response->setJSON([]);

            $data = $dosenModel->like('nama_lengkap', $query)
                            ->orLike('nppy', $query)
                            ->where('jabatan_fungsional', 'kaprodi')
                            ->findAll(10);

            $results = [];
            foreach ($data as $d) {
                $results[] = [
                    'label' => $d['nppy'].' - '.$d['nama_lengkap'],
                    'value' => $d['nppy'].' - '.$d['nama_lengkap'],
                    'id'    => $d['nppy']
                ];
            }

            return $this->response->setJSON($results);
        }

        /* EDIT */

        public function updateAjax()
        {
            $request = $this->request->getPost();
            $id_user = $request['id_user'] ?? null;

            if (!$id_user) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'ID user tidak ditemukan'
                ]);
            }

            $userModel = new UserModel();
            $user = $userModel->find($id_user);

            if (!$user) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'User tidak ditemukan'
                ]);
            }

            $username = trim($request['username']);
            $status   = trim($request['status']);
            $password = trim($request['password'] ?? '');

            if (empty($username) || empty($status)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Username dan Status harus diisi'
                ]);
            }

            // Cek username unik kecuali untuk user yang sama
            $cekUser = $userModel->where('username', $username)
                                ->where('id_user !=', $id_user)
                                ->first();
            if ($cekUser) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Username sudah digunakan'
                ]);
            }

            $data = [
                'username' => $username,
                'status'   => $status,
            ];

            // Update password hanya jika diisi
            if (!empty($password)) {
                $data['password'] = $password;
            }

            // Ambil nama lengkap sesuai role
            $nama_lengkap = $username; // default fallback

            switch ($user['role']) {
                case 'mahasiswa':
                    if (!empty($user['nim'])) {
                        $mahasiswaModel = new MahasiswaModel();
                        $mhs = $mahasiswaModel->find($user['nim']);
                        $nama_lengkap = $mhs['nama_lengkap'] ?? $username;
                    }
                    break;

                case 'dospem':
                    if (!empty($user['nppy'])) {
                        $dosenModel = new DosenModel();
                        $dsn = $dosenModel->find($user['nppy']);
                        $nama_lengkap = $dsn['nama_lengkap'] ?? $username;
                    }
                    break;

                case 'kaprodi':
                    if (!empty($user['nppy'])) {
                        $dosenModel = new DosenModel();
                        $dsn = $dosenModel->where('nppy', $user['nppy'])
                                        ->where('jabatan_fungsional', 'kaprodi')
                                        ->first();
                        $nama_lengkap = $dsn['nama_lengkap'] ?? $username;
                    }
                    break;

                case 'mitra':
                    if (!empty($user['id_unit'])) {
                        $unitModel = new UnitModel();
                        $unit = $unitModel->find($user['id_unit']);
                        $nama_lengkap = $unit['nama_pembimbing'] ?? $username;
                    }
                    break;

                default:
                    $nama_lengkap = $username; // admin atau fallback
            }

            // Gunakan skipValidation() supaya tidak terkena rule is_unique di model
            if ($userModel->skipValidation()->update($id_user, $data)) {

                // Set flashdata sukses pakai nama_lengkap
                session()->setFlashdata('success', "Data pengguna {$nama_lengkap} berhasil diperbarui");

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'User berhasil diperbarui'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $userModel->errors()
                ]);
            }
        }

        // Soft Delete User
        public function hapusAjax()
        {
            $id_user = $this->request->getPost('id_user');

            if (!$id_user) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'ID pengguna tidak ditemukan.'
                ]);
            }

            $userModel = new UserModel();
            $user      = $userModel->find($id_user);

            if (!$user) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Pengguna tidak ditemukan.'
                ]);
            }

            // default fallback
            $nama_lengkap = $user['username'];  

            switch ($user['role']) {
                case 'mahasiswa':
                    if (!empty($user['nim'])) {
                        $mhs = (new MahasiswaModel())->find($user['nim']);
                        if ($mhs) $nama_lengkap = $mhs['nama_lengkap'];
                    }
                    break;

                case 'dospem':
                case 'kaprodi':
                    if (!empty($user['nppy'])) {
                        $dsn = (new DosenModel())->find($user['nppy']);
                        if ($dsn) $nama_lengkap = $dsn['nama_lengkap'];
                    }
                    break;

                case 'mitra':
                    if (!empty($user['id_unit'])) {
                        $unit = (new UnitModel())->find($user['id_unit']);
                        if ($unit) $nama_lengkap = $unit['nama_pembimbing'];
                    }
                    break;
            }

            // 🔑 Soft delete + update status jadi "tidak aktif"
            $deleted = $userModel->update($id_user, ['status' => 'tidak aktif']) 
                        && $userModel->delete($id_user);

            if ($deleted) {
                // Set flashdata sukses (biar tampil di atas tabel)
                session()->setFlashdata('success', "Pengguna {$nama_lengkap} berhasil dihapus (diarsipkan).");

                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => "Pengguna {$nama_lengkap} berhasil dihapus (diarsipkan)."
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat menghapus pengguna.'
                ]);
            }
        }


        //Restore Ajax
        public function restoreAjax()
        {
            $id = $this->request->getPost('id_user');
            $userModel      = new \App\Models\UserModel();
            $mahasiswaModel = new \App\Models\MahasiswaModel();
            $dosenModel     = new \App\Models\DosenModel();
            $unitModel      = new \App\Models\UnitModel();

            if (!$id) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'ID user tidak ditemukan'
                ]);
            }

            // ambil data user termasuk yang soft delete
            $user = $userModel->withDeleted()->find($id);
            if (!$user) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'User tidak ditemukan.'
                ]);
            }

            // cari nama lengkap sesuai role
            $nama_lengkap = $user['username']; // fallback
            switch ($user['role']) {
                case 'mahasiswa':
                    if (!empty($user['nim'])) {
                        $mhs = $mahasiswaModel->find($user['nim']);
                        if ($mhs) $nama_lengkap = $mhs['nama_lengkap'];
                    }
                    break;

                case 'dospem':
                case 'kaprodi':
                    if (!empty($user['nppy'])) {
                        $dsn = $dosenModel->find($user['nppy']);
                        if ($dsn) $nama_lengkap = $dsn['nama_lengkap'];
                    }
                    break;

                case 'mitra':
                    if (!empty($user['id_unit'])) {
                        $unit = $unitModel->find($user['id_unit']);
                        if ($unit) $nama_lengkap = $unit['nama_pembimbing'];
                    }
                    break;
            }

            // ✅ update status & kosongkan deleted_at
            $restored = $userModel
                ->protect(false) // biar deleted_at ikut keupdate
                ->withDeleted()
                ->update($id, [
                    'status'     => 'aktif',
                    'deleted_at' => null
                ]);

            if ($restored) {
                session()->setFlashdata('success', "Pengguna {$nama_lengkap} berhasil direstore.");
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => "Pengguna {$nama_lengkap} berhasil direstore."
                ]);
            } else {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Gagal merestore pengguna.'
                ]);
            }
        }

        public function arsip()
        {
            $userModel      = new \App\Models\UserModel();
            $mahasiswaModel = new \App\Models\MahasiswaModel();
            $dosenModel     = new \App\Models\DosenModel();
            $unitModel      = new \App\Models\UnitModel();

            // Ambil data yang sudah nonaktif dan softdelete
            $users = $userModel
                ->onlyDeleted() // ambil yang deleted_at tidak NULL
                ->where('status', 'tidak aktif')
                ->findAll();

            // Tambahkan kolom nama_lengkap sesuai role
            foreach ($users as &$u) {
                if ($u['role'] === 'mahasiswa' && !empty($u['nim'])) {
                    $mhs = $mahasiswaModel->find($u['nim']);
                    $u['nama_lengkap'] = $mhs['nama_lengkap'] ?? $u['username'];

                } elseif ($u['role'] === 'dospem' && !empty($u['nppy'])) {
                    $dsn = $dosenModel->find($u['nppy']);
                    $u['nama_lengkap'] = $dsn['nama_lengkap'] ?? $u['username'];

                } elseif ($u['role'] === 'kaprodi' && !empty($u['nppy'])) {
                    $dsn = $dosenModel->where('nppy', $u['nppy'])
                                    ->where('jabatan_fungsional', 'kaprodi')
                                    ->first();
                    $u['nama_lengkap'] = $dsn['nama_lengkap'] ?? $u['username'];

                } elseif ($u['role'] === 'mitra' && !empty($u['id_unit'])) {
                    $unit = $unitModel->find($u['id_unit']);
                    $u['nama_lengkap'] = $unit['nama_pembimbing'] ?? $u['username'];

                } else {
                    $u['nama_lengkap'] = $u['username']; // fallback
                }
            }

            $data['user_name'] = $this->getUserName();

            $data['user'] = $users;

            return view('admin/arsip_pengguna', $data);
        }

    }
