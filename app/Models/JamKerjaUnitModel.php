<?php

namespace App\Models;

use CodeIgniter\Model;

class JamKerjaUnitModel extends Model
{
    protected $table = 'jam_kerja_unit';
    protected $primaryKey = 'id_jam_kerja';
    protected $allowedFields = ['id_unit', 'hari', 'jam_masuk', 'jam_pulang', 'status_hari'];
    public $useTimestamps = false;
}
