<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $renderer = service('renderer');

        $renderer->setVar('user_name', $this->getUserName());
        $renderer->setVar('foto', $this->getUserFoto());
    }

    protected function getUserName()
    {
        $session = session();
        $role = $session->get('role');
        $id_user = $session->get('id_user');

        $mahasiswaModel = new \App\Models\MahasiswaModel();
        $dosenModel     = new \App\Models\DosenModel();
        $unitModel      = new \App\Models\UnitModel();
        $userModel      = new \App\Models\UserModel();

        // default (fallback)
        $name = 'User';

        /* === USER ADMIN (ambil dari user.nama_lengkap) === */
        if ($role === 'admin') {
            $user = $userModel->find($id_user);
            $name = $user['nama_lengkap'] ?? $user['username'] ?? $name;
        }

        /* === MAHASISWA === */
        elseif ($role === 'mahasiswa') {

            // Ambil user
            $user = $userModel->find($id_user);

            // Ambil NIM dari tabel user
            $nim = $user['nim'] ?? null;

            if ($nim) {
                $m = $mahasiswaModel->where('nim', $nim)->first();
                $name = $m['nama_lengkap'] ?? $name;
            }
        }

        /* === DOSEN PEMBIMBING === */
        elseif ($role === 'dospem') {
            // Ambil data user berdasarkan id_user
            $u = $userModel->find($id_user);
            $nppy = $u['nppy'] ?? null;

            // Jika user tidak punya NPPY, fallback
            if (!$nppy) {
                return $name;
            }

            // Cari data dosen berdasarkan nppy
            $d = $dosenModel->where('nppy', $nppy)->first();

            $name = $d['nama_lengkap'] ?? $name;
        }


        /* === KAPRODI === */
        elseif ($role === 'kaprodi') {

            // Ambil nppy dari tabel user berdasarkan id_user
            $u = $userModel->find($id_user);
            $nppy = $u['nppy'] ?? null;

            // Jika tidak ada NPPY, gunakan fallback:
            if (!$nppy) {
                return $name;
            }

            // Daftar jabatan fungsional yang dianggap sebagai Kaprodi
            $kaprodiRoles = [
                'kaprodi',
                'Kaprodi Sistem Informasi',
                'Kaprodi Teknik Informatika'
            ];

            $d = $dosenModel->where('nppy', $nppy)
                            ->whereIn('jabatan_fungsional', $kaprodiRoles)
                            ->first();

            $name = $d['nama_lengkap'] ?? $name;
        }


        /* === MITRA / PEMBIMBING UNIT === */
        if ($role === 'mitra') {
            $user = $userModel->find($id_user);

            if (!empty($user['id_unit'])) {
                $unit = $unitModel->where('id_unit', $user['id_unit'])->first();
                return $unit['nama_pembimbing'] ?? $name;
            }
        }

        return $name;
    }



    protected function getUserFoto()
    {
        $session = session();
        $role    = $session->get('role');
        $id_user = $session->get('id_user');

        $userModel  = new \App\Models\UserModel();
        $dosenModel = new \App\Models\DosenModel();
        $unitModel  = new \App\Models\UnitModel();

        $user = $userModel->find($id_user);

        /* === DOSEN & KAPRODI (foto dosen) === */
        if (in_array($role, ['dospem', 'kaprodi'])) {
            if (!empty($user['nppy'])) {
                $dosen = $dosenModel->where('nppy', $user['nppy'])->first();
                if (!empty($dosen['foto']) && file_exists(FCPATH . 'uploads/foto/' . $dosen['foto'])) {
                    return $dosen['foto'];
                }
            }
        }

        /* === MITRA (foto unit) === */
        if ($role === 'mitra') {
            if (!empty($user['id_unit'])) {
                $unit = $unitModel->where('id_unit', $user['id_unit'])->first();
                if (!empty($unit['foto']) && file_exists(FCPATH . 'uploads/foto/' . $unit['foto'])) {
                    return $unit['foto'];
                }
            }
        }

        /* === FALLBACK USER === */
        if (!empty($user['foto']) && file_exists(FCPATH . 'uploads/foto/' . $user['foto'])) {
            return $user['foto'];
        }

        /* === DEFAULT === */
        return 'pp.jpg';
    }


}
