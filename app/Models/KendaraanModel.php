<?php namespace App\Models;
use CodeIgniter\Model;
class KendaraanModel extends Model {
    protected $table = 'kendaraan';
    protected $primaryKey = 'no_plat';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['no_plat', 'id_gerbang', 'jenis_kendaraan'];
    protected $useTimestamps = false;
}