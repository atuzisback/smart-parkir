<?php namespace App\Models;
use CodeIgniter\Model;
class TiketParkirModel extends Model {
    protected $table = 'tiket_parkir';
    protected $primaryKey = 'id_tiket';
    protected $allowedFields = ['id_slot','id_transaksi','barcode','plat_nomor'];
    protected $useTimestamps = false;
}