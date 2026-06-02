<?php

namespace App\Controllers;

use App\Models\KendaraanModel;
use App\Models\TransaksiModel;
use App\Models\TiketParkirModel;
use App\Models\SlotParkirModel;
use App\Models\ZonaParkirModel;
use App\Models\GerbangModel;
use App\Models\MetodePembayaranModel;
use App\Models\DetailPembayaranModel;

class Transaksi extends BaseController
{
    protected $kendaraanModel;
    protected $transaksiModel;
    protected $tiketModel;
    protected $slotModel;
    protected $zonaModel;
    protected $gerbangModel;
    protected $metodeModel;
    protected $detailModel;

    public function __construct()
    {
        $this->kendaraanModel = new KendaraanModel();
        $this->transaksiModel = new TransaksiModel();
        $this->tiketModel     = new TiketParkirModel();
        $this->slotModel      = new SlotParkirModel();
        $this->zonaModel      = new ZonaParkirModel();
        $this->gerbangModel   = new GerbangModel();
        $this->metodeModel    = new MetodePembayaranModel();
        $this->detailModel    = new DetailPembayaranModel();
    }

    // ── Gerbang Masuk ──────────────────────────────────────
    public function masuk()
    {
        $gerbang_masuk  = $this->gerbangModel->where('tipe_gerbang', 'masuk')->findAll();
        $slot_tersedia  = $this->slotModel
            ->select('slot_parkir.*, zona_parkir.nama_zona')
            ->join('zona_parkir', 'zona_parkir.id_zona = slot_parkir.id_zona')
            ->where('status_tersedia', 1)
            ->findAll();

        return view('transaksi/masuk', [
            'title'         => 'Gerbang Masuk',
            'gerbang_masuk' => $gerbang_masuk,
            'slot_tersedia' => $slot_tersedia,
        ]);
    }

    public function prosesMasuk()
    {
        $rules = [
            'no_plat'       => 'required|max_length[10]',
            'jenis_kendaraan' => 'required',
            'id_gerbang'    => 'required|integer',
            'id_slot'       => 'required|integer',
            'id_user'       => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $no_plat    = strtoupper($this->request->getPost('no_plat'));
            $id_gerbang = $this->request->getPost('id_gerbang');
            $id_slot    = $this->request->getPost('id_slot');
            $id_user    = session('id_user') ?? 1;

            // Upsert kendaraan
            $existing = $this->kendaraanModel->find($no_plat);
            if ($existing) {
                $this->kendaraanModel->update($no_plat, [
                    'id_gerbang'      => $id_gerbang,
                    'jenis_kendaraan' => $this->request->getPost('jenis_kendaraan'),
                ]);
            } else {
                $this->kendaraanModel->insert([
                    'no_plat'         => $no_plat,
                    'id_gerbang'      => $id_gerbang,
                    'jenis_kendaraan' => $this->request->getPost('jenis_kendaraan'),
                ]);
            }

            // Insert transaksi
            $id_transaksi = $this->transaksiModel->insert([
                'id_gerbang'        => $id_gerbang,
                'no_plat'           => $no_plat,
                'id_user'           => $id_user,
                'waktu_masuk'       => date('Y-m-d H:i:s'),
                'status_pembayaran' => 'belum',
                'total_biaya'       => 0,
            ]);

            $barcode = 'BC' . str_pad($id_transaksi, 8, '0', STR_PAD_LEFT);

            // Insert tiket
            $id_tiket = $this->tiketModel->insert([
                'id_slot'       => $id_slot,
                'id_transaksi'  => $id_transaksi,
                'barcode'       => $barcode,
                'plat_nomor'    => $no_plat,
            ]);

            // Update id_tiket di transaksi
            $this->transaksiModel->update($id_transaksi, ['id_tiket' => $id_tiket]);

            // Tandai slot terisi
            $this->slotModel->update($id_slot, ['status_tersedia' => 0]);

            // Buka palang
            $this->gerbangModel->update($id_gerbang, ['status_palang' => 'buka']);

            $db->transComplete();

            return redirect()->to(base_url("transaksi/struk/$id_transaksi"))
                             ->with('success', "Kendaraan $no_plat berhasil masuk. Barcode: $barcode");

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function struk($id_transaksi)
    {
        $transaksi = $this->transaksiModel
            ->select('transaksi.*, tiket_parkir.barcode, slot_parkir.no_slot, zona_parkir.nama_zona, zona_parkir.tarif_jam, gerbang.nama_gerbang')
            ->join('tiket_parkir', 'tiket_parkir.id_tiket = transaksi.id_tiket', 'left')
            ->join('slot_parkir',  'slot_parkir.id_slot = tiket_parkir.id_slot', 'left')
            ->join('zona_parkir',  'zona_parkir.id_zona = slot_parkir.id_zona', 'left')
            ->join('gerbang',      'gerbang.id_gerbang = transaksi.id_gerbang', 'left')
            ->find($id_transaksi);

        if (!$transaksi) {
            return redirect()->to(base_url('transaksi/masuk'))->with('error', 'Transaksi tidak ditemukan');
        }

        return view('transaksi/struk', ['title' => 'Struk Masuk', 'transaksi' => $transaksi]);
    }

    // ── Gerbang Keluar & Billing ───────────────────────────
    public function keluar()
    {
        $barcode = $this->request->getGet('barcode') ?? '';
        $transaksi = null;
        $metode = $this->metodeModel->findAll();

        if ($barcode) {
            $tiket = $this->tiketModel->where('barcode', $barcode)->first();
            if ($tiket) {
                $transaksi = $this->transaksiModel
                    ->select('transaksi.*, tiket_parkir.barcode, slot_parkir.no_slot, zona_parkir.nama_zona, zona_parkir.tarif_jam, gerbang.nama_gerbang')
                    ->join('tiket_parkir', 'tiket_parkir.id_tiket = transaksi.id_tiket', 'left')
                    ->join('slot_parkir',  'slot_parkir.id_slot = tiket_parkir.id_slot', 'left')
                    ->join('zona_parkir',  'zona_parkir.id_zona = slot_parkir.id_zona', 'left')
                    ->join('gerbang',      'gerbang.id_gerbang = transaksi.id_gerbang', 'left')
                    ->find($tiket['id_transaksi']);

                if ($transaksi) {
                    // Hitung estimasi biaya
                    $menit = (time() - strtotime($transaksi['waktu_masuk'])) / 60;
                    $jam   = max(1, ceil($menit / 60));
                    $transaksi['estimasi_biaya']   = $jam * ($transaksi['tarif_jam'] ?? 0);
                    $transaksi['durasi_display']   = floor($menit / 60) . 'j ' . (floor($menit) % 60) . 'm';
                    $transaksi['gerbang_keluar']   = $this->gerbangModel->where('tipe_gerbang', 'keluar')->findAll();
                }
            }
        }

        $gerbang_keluar = $this->gerbangModel->where('tipe_gerbang', 'keluar')->findAll();

        return view('transaksi/keluar', [
            'title'          => 'Gerbang Keluar & Billing',
            'transaksi'      => $transaksi,
            'metode'         => $metode,
            'gerbang_keluar' => $gerbang_keluar,
            'barcode_scan'   => $barcode,
        ]);
    }

    public function prosesKeluar()
    {
        $id_transaksi      = $this->request->getPost('id_transaksi');
        $id_metode         = $this->request->getPost('id_metode');
        $id_gerbang_keluar = $this->request->getPost('id_gerbang_keluar');

        $transaksi = $this->transaksiModel
            ->select('transaksi.*, zona_parkir.tarif_jam, tiket_parkir.id_slot, tiket_parkir.barcode')
            ->join('tiket_parkir', 'tiket_parkir.id_tiket = transaksi.id_tiket', 'left')
            ->join('slot_parkir',  'slot_parkir.id_slot = tiket_parkir.id_slot', 'left')
            ->join('zona_parkir',  'zona_parkir.id_zona = slot_parkir.id_zona', 'left')
            ->find($id_transaksi);

        if (!$transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $menit   = (time() - strtotime($transaksi['waktu_masuk'])) / 60;
            $jam     = max(1, ceil($menit / 60));
            $total   = $jam * ($transaksi['tarif_jam'] ?? 0);

            // Insert detail pembayaran
            $id_pembayaran = $this->detailModel->insert([
                'id_transaksi' => $id_transaksi,
                'id_metode'    => $id_metode,
                'waktu_bayar'  => date('Y-m-d H:i:s'),
                'jumlah_bayar' => $total,
                'status_bayar' => 'sukses',
            ]);

            // Update transaksi
            $this->transaksiModel->update($id_transaksi, [
                'id_pembayaran'     => $id_pembayaran,
                'id_metode'         => $id_metode,
                'waktu_keluar'      => date('Y-m-d H:i:s'),
                'durasi'            => (int) $menit,
                'total_biaya'       => $total,
                'status_pembayaran' => 'lunas',
            ]);

            // Kosongkan slot
            if (!empty($transaksi['id_slot'])) {
                $this->slotModel->update($transaksi['id_slot'], ['status_tersedia' => 1]);
            }

            // Buka palang keluar
            if ($id_gerbang_keluar) {
                $this->gerbangModel->update($id_gerbang_keluar, ['status_palang' => 'buka']);
            }

            $db->transComplete();

            return redirect()->to(base_url('transaksi/keluar'))
                             ->with('success', "Pembayaran berhasil. Total: Rp " . number_format($total, 0, ',', '.'));

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // ── Monitoring Slot ────────────────────────────────────
    public function monitoring()
    {
        $zonas = (new ZonaParkirModel())->findAll();
        $zona_data = [];
        foreach ($zonas as $zona) {
            $slots = $this->slotModel
                ->select('slot_parkir.*, transaksi.no_plat, transaksi.waktu_masuk')
                ->join('tiket_parkir', 'tiket_parkir.id_slot = slot_parkir.id_slot AND tiket_parkir.id_transaksi IN (SELECT id_transaksi FROM transaksi WHERE waktu_keluar IS NULL)', 'left')
                ->join('transaksi', 'transaksi.id_tiket = tiket_parkir.id_tiket', 'left')
                ->where('slot_parkir.id_zona', $zona['id_zona'])
                ->findAll();
            $zona_data[] = array_merge($zona, ['slots' => $slots]);
        }

        return view('transaksi/monitoring', [
            'title'      => 'Monitoring Slot',
            'zona_data'  => $zona_data,
        ]);
    }
}