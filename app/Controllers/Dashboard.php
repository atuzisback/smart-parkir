<?php

namespace App\Controllers;

use App\Models\SlotParkirModel;
use App\Models\ZonaParkirModel;
use App\Models\TransaksiModel;
use App\Models\GerbangModel;

class Dashboard extends BaseController
{
    protected $slotModel;
    protected $zonaModel;
    protected $transaksiModel;
    protected $gerbangModel;

    public function __construct()
    {
        $this->slotModel      = new SlotParkirModel();
        $this->zonaModel      = new ZonaParkirModel();
        $this->transaksiModel = new TransaksiModel();
        $this->gerbangModel   = new GerbangModel();
    }

    public function index()
    {
        $role = session()->get('role');
        $pendapatan_hari_ini = 0;
        $chart_labels = [];
        $chart_values = [];
        if ($role != 'petugas') {

            $pendapatan_hari_ini = $this->transaksiModel
                ->selectSum('total_biaya')
                ->where('DATE(waktu_masuk)', date('Y-m-d'))
                ->where('status_pembayaran', 'lunas')
                ->first()['total_biaya'] ?? 0;

            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));

                $chart_labels[] = date('d/m', strtotime($date));

                $result = $this->transaksiModel->db
                    ->query(
                        "SELECT COALESCE(SUM(total_biaya),0) AS total
                 FROM transaksi
                 WHERE DATE(waktu_masuk)=?
                 AND status_pembayaran='lunas'",
                        [$date]
                    )
                    ->getRow();

                $chart_values[] = (int)($result->total ?? 0);
            }
        }
        // Stat cards
        $total_slot     = $this->slotModel->countAll();
        $slot_terisi    = $this->slotModel->where('status_tersedia', 0)->countAllResults();
        $slot_tersedia  = $total_slot - $slot_terisi;
        $pct_terisi     = $total_slot > 0 ? round(($slot_terisi / $total_slot) * 100) : 0;

        $transaksi_hari_ini = $this->transaksiModel
            ->where('DATE(waktu_masuk)', date('Y-m-d'))
            ->countAllResults();

        $pendapatan_hari_ini = $this->transaksiModel
            ->selectSum('total_biaya')
            ->where('DATE(waktu_masuk)', date('Y-m-d'))
            ->where('status_pembayaran', 'lunas')
            ->first()['total_biaya'] ?? 0;

        // Zona data with slots
        $zonas = $this->zonaModel->findAll();
        $zona_data = [];
        foreach ($zonas as $zona) {
            $slots = $this->slotModel
                ->where('id_zona', $zona['id_zona'])
                ->findAll();

            $terisi    = count(array_filter($slots, fn($s) => !$s['status_tersedia']));
            $tersedia  = count(array_filter($slots, fn($s) => $s['status_tersedia']));

            $zona_data[] = array_merge($zona, [
                'slots'         => $slots,
                'total_slot'    => count($slots),
                'slot_terisi'   => $terisi,
                'slot_tersedia' => $tersedia,
            ]);
        }

        // Transaksi aktif (belum keluar)
        $transaksi_aktif = $this->transaksiModel
            ->select('transaksi.*, tiket_parkir.barcode, slot_parkir.no_slot, zona_parkir.nama_zona')
            ->join('tiket_parkir', 'tiket_parkir.id_tiket = transaksi.id_tiket', 'left')
            ->join('slot_parkir',  'slot_parkir.id_slot = tiket_parkir.id_slot', 'left')
            ->join('zona_parkir',  'zona_parkir.id_zona = slot_parkir.id_zona', 'left')
            ->where('transaksi.waktu_keluar IS NULL')
            ->where('transaksi.status_pembayaran', 'belum')
            ->orderBy('transaksi.waktu_masuk', 'DESC')
            ->findAll(20);

        // Tambahkan durasi display
        foreach ($transaksi_aktif as &$t) {
            $mins = (time() - strtotime($t['waktu_masuk'])) / 60;
            $t['durasi_display'] = floor($mins / 60) . 'j ' . (floor($mins) % 60) . 'm';
        }

        // Riwayat (sudah selesai)
        $riwayat_transaksi = $this->transaksiModel
            ->select('transaksi.*, tiket_parkir.barcode, zona_parkir.nama_zona')
            ->join('tiket_parkir', 'tiket_parkir.id_tiket = transaksi.id_tiket', 'left')
            ->join('slot_parkir',  'slot_parkir.id_slot = tiket_parkir.id_slot', 'left')
            ->join('zona_parkir',  'zona_parkir.id_zona = slot_parkir.id_zona', 'left')
            ->where('transaksi.waktu_keluar IS NOT NULL')
            ->orderBy('transaksi.waktu_keluar', 'DESC')
            ->findAll(10);

        // Gerbang
        $gerbang_data = $this->gerbangModel->findAll();

        // Live feed (6 terbaru)
        $live_feed = $this->transaksiModel
            ->select('transaksi.no_plat, transaksi.waktu_masuk, transaksi.waktu_keluar, zona_parkir.nama_zona')
            ->join('tiket_parkir', 'tiket_parkir.id_tiket = transaksi.id_tiket', 'left')
            ->join('slot_parkir',  'slot_parkir.id_slot = tiket_parkir.id_slot', 'left')
            ->join('zona_parkir',  'zona_parkir.id_zona = slot_parkir.id_zona', 'left')
            ->orderBy('transaksi.waktu_masuk', 'DESC')
            ->findAll(6);

        // Chart 7 hari
        $chart_labels = [];
        $chart_values = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $chart_labels[] = date('d/m', strtotime($date));
            $result = $this->transaksiModel->db
                ->query("SELECT COALESCE(SUM(total_biaya),0) AS total FROM transaksi WHERE DATE(waktu_masuk) = ? AND status_pembayaran = 'lunas'", [$date])
                ->getRow();
            $chart_values[] = (int)($result->total ?? 0);
        }

        // Return diposisikan sekali di sini dengan folder view yang tepat
        return view('dashboard/index', [
            'title'               => 'Dashboard',
            'total_slot'          => $total_slot,
            'slot_terisi'         => $slot_terisi,
            'slot_tersedia'       => $slot_tersedia,
            'pct_terisi'          => $pct_terisi,
            'transaksi_hari_ini'  => $transaksi_hari_ini,
            'pendapatan_hari_ini' => $pendapatan_hari_ini,
            'zona_data'           => $zona_data,
            'transaksi_aktif'     => $transaksi_aktif,
            'riwayat_transaksi'   => $riwayat_transaksi,
            'gerbang_data'        => $gerbang_data,
            'live_feed'           => $live_feed,
            'chart_labels'        => $chart_labels,
            'chart_values'        => $chart_values,
        ]);
    }
}
