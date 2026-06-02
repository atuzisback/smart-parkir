<?php namespace App\Models;
use CodeIgniter\Model;
class SlotParkirModel extends Model {
    protected $table = 'slot_parkir';
    protected $primaryKey = 'id_slot';
    protected $allowedFields = ['id_zona', 'status_tersedia', 'no_slot'];
    protected $useTimestamps = false;
}