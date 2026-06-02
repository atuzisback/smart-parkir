<?php

namespace App\Controllers;

use App\Models\TransaksiModel;

class Laporan extends BaseController
{
    protected $transaksiModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
    }

    public function pendapatan()
    {
        $dari   = $this->request->getGet('dari')   ?? date('Y-m-01');
        $sampai = $this->request->getGet('sampai') ?? date('Y-m-d');
        $per    = $this->request->getGet('per')    ?? 'hari';

        $groupBy = $per == 'bulan' ? 'DATE_FORMAT(waktu_masuk, \'%Y-%m\')' : 'DATE(waktu_masuk)';

        $laporan = $this->transaksiModel->db->query("
            SELECT
                {$groupBy} AS tanggal,
                COUNT(*) AS jumlah_transaksi,
                SUM(total_biaya) AS total_pendapatan
            FROM transaksi
            WHERE DATE(waktu_masuk) BETWEEN ? AND ?
              AND status_pembayaran = 'lunas'
            GROUP BY {$groupBy}
            ORDER BY tanggal DESC
        ", [$dari, $sampai])->getResultArray();

        $total_pendapatan = array_sum(array_column($laporan, 'total_pendapatan'));
        $total_transaksi  = array_sum(array_column($laporan, 'jumlah_transaksi'));
        $rata_rata        = count($laporan) > 0 ? $total_pendapatan / count($laporan) : 0;

        $tertinggi    = !empty($laporan) ? max(array_column($laporan, 'total_pendapatan')) : 0;
        $tgl_tertinggi = '';
        foreach ($laporan as $row) {
            if ($row['total_pendapatan'] == $tertinggi) {
                $tgl_tertinggi = $row['tanggal'];
                break;
            }
        }

        $chart_labels = array_column(array_reverse($laporan), 'tanggal');
        $chart_values = array_column(array_reverse($laporan), 'total_pendapatan');

        return view('laporan/pendapatan', compact(
            'laporan','dari','sampai','per',
            'total_pendapatan','total_transaksi','rata_rata',
            'tertinggi','tgl_tertinggi','chart_labels','chart_values'
        ) + ['title' => 'Laporan Pendapatan']);
    }

    public function statistik()
    {
        $dari   = $this->request->getGet('dari')   ?? date('Y-m-01');
        $sampai = $this->request->getGet('sampai') ?? date('Y-m-d');

        $statistik = $this->transaksiModel->db->query("
            SELECT
                k.jenis_kendaraan,
                COUNT(*) AS jumlah,
                SUM(t.total_biaya) AS total_pendapatan,
                AVG(t.durasi) AS avg_durasi
            FROM transaksi t
            JOIN kendaraan k ON t.no_plat = k.no_plat
            WHERE DATE(t.waktu_masuk) BETWEEN ? AND ?
            GROUP BY k.jenis_kendaraan
            ORDER BY jumlah DESC
        ", [$dari, $sampai])->getResultArray();

        return view('laporan/statistik', compact('statistik','dari','sampai') + ['title' => 'Statistik Kendaraan']);
    }

    public function histori()
    {
        $q      = $this->request->getGet('q')      ?? '';
        $status = $this->request->getGet('status') ?? '';
        $dari   = $this->request->getGet('dari')   ?? '';
        $sampai = $this->request->getGet('sampai') ?? '';
        $page   = (int)($this->request->getGet('page') ?? 1);
        $limit  = 20;

        $builder = $this->transaksiModel
            ->select('transaksi.*, tiket_parkir.barcode, zona_parkir.nama_zona, k.jenis_kendaraan')
            ->join('tiket_parkir', 'tiket_parkir.id_tiket = transaksi.id_tiket', 'left')
            ->join('slot_parkir',  'slot_parkir.id_slot = tiket_parkir.id_slot', 'left')
            ->join('zona_parkir',  'zona_parkir.id_zona = slot_parkir.id_zona', 'left')
            ->join('kendaraan k',  'k.no_plat = transaksi.no_plat', 'left');

        if ($q) {
            $builder->groupStart()
                ->like('transaksi.no_plat', $q)
                ->orLike('tiket_parkir.barcode', $q)
                ->groupEnd();
        }
        if ($status) $builder->where('transaksi.status_pembayaran', $status);
        if ($dari)   $builder->where('DATE(transaksi.waktu_masuk) >=', $dari);
        if ($sampai) $builder->where('DATE(transaksi.waktu_masuk) <=', $sampai);

        $total  = $builder->countAllResults(false);
        $histori = $builder->orderBy('transaksi.waktu_masuk', 'DESC')
                           ->findAll($limit, ($page - 1) * $limit);

        return view('laporan/histori', compact(
            'histori','q','status','dari','sampai','page','limit','total'
        ) + ['title' => 'Histori Parkir']);
    }

    public function kepadatan()
    {
        $kepadatan = $this->transaksiModel->db->query("
            SELECT
                HOUR(waktu_masuk) AS jam,
                COUNT(*) AS jumlah_masuk,
                SUM(CASE WHEN waktu_keluar IS NOT NULL THEN 1 ELSE 0 END) AS jumlah_keluar
            FROM transaksi
            WHERE waktu_masuk >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY HOUR(waktu_masuk)
            ORDER BY jam ASC
        ")->getResultArray();

        return view('laporan/kepadatan', compact('kepadatan') + ['title' => 'Laporan Kepadatan Lahan']);
    }
}