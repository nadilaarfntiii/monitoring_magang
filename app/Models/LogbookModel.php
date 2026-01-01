<?php

namespace App\Models;

use CodeIgniter\Model;

class LogbookModel extends Model
{
    protected $table      = 'logbook';
    protected $primaryKey = 'id_logbook';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_profil',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'catatan_aktivitas',
        'foto_kegiatan',
        'approval_pembimbing',
        'catatan_pembimbing'
    ];

}