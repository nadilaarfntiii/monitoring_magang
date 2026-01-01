<?php

namespace App\Controllers;

use App\Models\ProgramMagangModel;
use CodeIgniter\Controller;

class ProgramMagang extends BaseController
{
    protected $programMagangModel;

    public function __construct()
    {
        $this->programMagangModel = new ProgramMagangModel();
    }

    public function index()
    {
        $data['user_name'] = $this->getUserName();
        $data['program_magang'] = $this->programMagangModel->findAll();
        return view('admin/program_magang', $data);
    }

    public function simpanAjax()
    {
        if ($this->request->isAJAX()) {
            $this->programMagangModel->save([
                'nama_program' => $this->request->getPost('nama_program'),
                'status'       => $this->request->getPost('status')
            ]);
            return $this->response->setJSON(['status' => true]);
        }
    }

    public function detail($id)
    {
        if ($this->request->isAJAX()) {
            $data = $this->programMagangModel->find($id);
            return $this->response->setJSON(['status' => true, 'data' => $data]);
        }
    }

    public function updateAjax()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id_program');
            $this->programMagangModel->update($id, [
                'nama_program' => $this->request->getPost('nama_program'),
                'status'       => $this->request->getPost('status')
            ]);

            // Set flashdata sukses edit
            session()->setFlashdata('success', 'Program magang berhasil diperbarui.');

            return $this->response->setJSON(['status' => true]);
        }
    }

    public function hapusAjax()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id_program');
            $this->programMagangModel->delete($id);

            // Set flashdata
            session()->setFlashdata('success', 'Program magang berhasil dihapus.');

            return $this->response->setJSON(['status' => true]);
        }
    }

}
