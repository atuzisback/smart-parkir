<?php namespace App\Models;
use CodeIgniter\Model;

class ZonaParkirModel extends Model {
    protected $table = 'zona_parkir';
    protected $primaryKey = 'id_zona';
    protected $allowedFields = ['nama_zona', 'tarif_jam'];
    protected $useTimestamps = false;
}