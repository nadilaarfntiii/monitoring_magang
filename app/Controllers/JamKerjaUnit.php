<?php

namespace App\Controllers;

use App\Models\JamKerjaUnitModel;
use App\Models\UnitModel;
use CodeIgniter\Controller;

class JamKerjaUnit extends BaseController
{
    protected $jamKerjaModel;
    protected $unitModel;
    protected $session;

    public function __construct()
    {
        $this->jamKerjaModel = new JamKerjaUnitModel();
        $this->unitModel = new UnitModel();
        $this->session = session();
    }

    public function index()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'mitra') {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $id_unit = $this->session->get('id_unit');

        $jamKerja = $this->jamKerjaModel
            ->where('id_unit', $id_unit)
            ->orderBy('FIELD(hari, "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu")', '', false)
            ->findAll();

        // Mengecek apakah hari sudah lengkap Senin-Minggu
        $hariLengkap = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
        $hariAda = array_column($jamKerja, 'hari'); // ambil semua hari yang sudah ada
        $sudahLengkap = empty(array_diff($hariLengkap, $hariAda)); // true jika semua ada

        $data = [
            'jamKerja' => $jamKerja,
            'sudahLengkap' => $sudahLengkap,
            'hariAda' => $hariAda,
            'user_name' => $this->getUserName(), 
            'foto'      => $this->getUserFoto(), 
        ];

        return view('mitra/kelola_jam_kerja', $data);
    }


    public function store()
    {
        $hariList  = $this->request->getPost('hari');
        $jamMasuk  = $this->request->getPost('jam_masuk');
        $jamPulang = $this->request->getPost('jam_pulang');
        $status    = $this->request->getPost('status'); // sesuaikan dengan name select
        $idUnit    = $this->session->get('id_unit');

        if (!$hariList) {
            return redirect()->back()->with('error', 'Pilih minimal satu hari!');
        }

        foreach ($hariList as $hari) {
            $data = [
                'id_unit'    => $idUnit,
                'hari'       => $hari,
                'jam_masuk'  => $status === 'Libur' ? null : $jamMasuk,
                'jam_pulang' => $status === 'Libur' ? null : $jamPulang,
                'status_hari'=> $status,
            ];
            $this->jamKerjaModel->insert($data);
        }

        return redirect()->back()->with('success', 'Jam kerja berhasil ditambahkan.');
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $jamMasuk  = $this->request->getPost('jam_masuk');
        $jamPulang = $this->request->getPost('jam_pulang');
        $status    = $this->request->getPost('status'); // sesuaikan dengan name select

        if (!$id) {
            return redirect()->back()->with('error', 'Data tidak valid.');
        }

        $data = [
            'jam_masuk'  => $status === 'Libur' ? null : $jamMasuk,
            'jam_pulang' => $status === 'Libur' ? null : $jamPulang,
            'status_hari'=> $status
        ];

        $this->jamKerjaModel->update($id, $data);

        return redirect()->back()->with('success', 'Jam kerja berhasil diperbarui.');
    }

}
