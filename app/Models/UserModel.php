<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'user';
    protected $primaryKey = 'id_user';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    // Soft delete
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at'; 

    // ✅ tambahkan deleted_at di allowedFields
    protected $allowedFields = [
        'nama_lengkap',
        'username',
        'password',
        'role',
        'nim',
        'nppy',
        'id_unit',
        'status',
        'foto',
        'deleted_at'
    ];

    protected $useTimestamps = false;

    // Rules
    protected $validationRules = [
        'username' => 'required|regex_match[/^[a-zA-Z0-9_.-]+$/]|min_length[3]|max_length[100]|is_unique[user.username,id_user,{id_user}]',
        'password' => 'required|min_length[8]',
        'role'     => 'required|in_list[mahasiswa,dospem,mitra,kaprodi,admin]',
        'status'   => 'required|in_list[aktif,tidak aktif]',
        'nim'      => 'permit_empty|alpha_numeric_punct|max_length[15]',
        'nppy'     => 'permit_empty|alpha_numeric_punct|max_length[50]',
        'id_unit'  => 'permit_empty|alpha_numeric_punct|max_length[25]',
    ];

    protected $validationMessages = [
        'username' => [
            'required'   => 'Username harus diisi.',
            'regex_match' => 'Username hanya boleh mengandung huruf, angka, underscore, dash, dan titik.',
            'is_unique'  => 'Username sudah digunakan.'
        ],
        'password' => [
            'required'   => 'Password harus diisi.',
            'min_length' => 'Password minimal 8 karakter.'
        ],
        'role' => [
            'required' => 'Role harus dipilih.',
            'in_list'  => 'Role tidak valid.'
        ],
        'status' => [
            'required' => 'Status harus dipilih.'
        ]
    ];

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && $data['data']['password'] !== '') {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        } else {
            // Hapus password jika kosong agar tidak menimpa saat update
            unset($data['data']['password']);
        }
        return $data;
    }
}
