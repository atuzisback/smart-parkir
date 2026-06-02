<?php namespace App\Models;
use CodeIgniter\Model;
class GerbangModel extends Model {
    protected $table = 'gerbang';
    protected $primaryKey = 'id_gerbang';
    protected $allowedFields = ['nama_gerbang', 'status_palang', 'tipe_gerbang', 'kamera_lpr'];
    protected $useTimestamps = false;
}