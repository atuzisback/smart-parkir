<?php namespace App\Models;
use CodeIgniter\Model;
class TransaksiModel extends Model {
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    protected $allowedFields = ['id_gerbang','id_tiket','no_plat','id_pembayaran','id_user','id_metode','waktu_masuk','waktu_keluar','durasi','total_biaya','status_pembayaran'];
    protected $useTimestamps = false;
}