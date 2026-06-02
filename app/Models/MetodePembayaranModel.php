<?php namespace App\Models;
use CodeIgniter\Model;
class MetodePembayaranModel extends Model {
    protected $table = 'metode_pembayaran';
    protected $primaryKey = 'id_metode';
    protected $allowedFields = ['nama_metode'];
    protected $useTimestamps = false;
}