<?php namespace App\Models;
use CodeIgniter\Model;

class SensorAnprModel extends Model {
    protected $table = 'sensor_anpr';
    protected $primaryKey = 'id_perangkat';
    protected $allowedFields = ['id_transaksi', 'id_gerbang', 'ip_address', 'time_capture'];
    protected $useTimestamps = false;
}