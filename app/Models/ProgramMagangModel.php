<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgramMagangModel extends Model
{
    protected $table            = 'program_magang';
    protected $primaryKey       = 'id_program';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_program', 'status'];

    protected $useTimestamps = true; 
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
