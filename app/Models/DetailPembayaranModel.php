<?php namespace App\Models;
use CodeIgniter\Model;
class DetailPembayaranModel extends Model {
    protected $table = 'detail_pembayaran';
    protected $primaryKey = 'id_pembayaran';
    protected $allowedFields = ['id_transaksi','id_metode','waktu_bayar','jumlah_bayar','status_bayar'];
    protected $useTimestamps = false;
}