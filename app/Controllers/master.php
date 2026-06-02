<?php

namespace App\Controllers;

use App\Models\SlotParkirModel;
use App\Models\ZonaParkirModel;
use App\Models\UserModel;
use App\Models\GerbangModel;

class Master extends BaseController
{
    // ── Slot ──────────────────────────────────────────────
    public function slot()
    {
        $slotModel = new SlotParkirModel();
        $zonaModel = new ZonaParkirModel();

        $slots = $slotModel
            ->select('slot_parkir.*, zona_parkir.nama_zona, zona_parkir.tarif_jam')
            ->join('zona_parkir', 'zona_parkir.id_zona = slot_parkir.id_zona')
            ->orderBy('zona_parkir.nama_zona', 'ASC')
            ->orderBy('slot_parkir.no_slot', 'ASC')
            ->findAll();

        return view('master/slot', [
            'title'  => 'Kelola Slot Parkir',
            'slots'  => $slots,
            'zonas'  => $zonaModel->findAll(),
        ]);
    }

    public function simpanSlot()
    {
        $model = new SlotParkirModel();
        $model->insert([
            'id_zona'         => $this->request->getPost('id_zona'),
            'no_slot'         => strtoupper($this->request->getPost('no_slot')),
            'status_tersedia' => $this->request->getPost('status_tersedia'),
        ]);
        return redirect()->to(base_url('master/slot'))->with('success', 'Slot berhasil ditambahkan');
    }

    public function updateSlot()
    {
        $model = new SlotParkirModel();
        $model->update($this->request->getPost('id_slot'), [
            'id_zona'         => $this->request->getPost('id_zona'),
            'no_slot'         => strtoupper($this->request->getPost('no_slot')),
            'status_tersedia' => $this->request->getPost('status_tersedia'),
        ]);
        return redirect()->to(base_url('master/slot'))->with('success', 'Slot berhasil diupdate');
    }

    public function hapusSlot($id)
    {
        $model = new SlotParkirModel();
        $model->delete($id);
        return redirect()->to(base_url('master/slot'))->with('success', 'Slot berhasil dihapus');
    }

    // ── Zona ──────────────────────────────────────────────
    public function zona()
    {
        $model = new ZonaParkirModel();
        return view('master/zona', [
            'title' => 'Zona & Tarif',
            'zonas' => $model->findAll(),
        ]);
    }

    public function simpanZona()
    {
        $model = new ZonaParkirModel();
        $model->insert([
            'nama_zona' => $this->request->getPost('nama_zona'),
            'tarif_jam' => $this->request->getPost('tarif_jam'),
        ]);
        return redirect()->to(base_url('master/zona'))->with('success', 'Zona berhasil ditambahkan');
    }

    public function updateZona()
    {
        $model = new ZonaParkirModel();
        $model->update($this->request->getPost('id_zona'), [
            'nama_zona' => $this->request->getPost('nama_zona'),
            'tarif_jam' => $this->request->getPost('tarif_jam'),
        ]);
        return redirect()->to(base_url('master/zona'))->with('success', 'Zona berhasil diupdate');
    }

    public function hapusZona($id)
    {
        (new ZonaParkirModel())->delete($id);
        return redirect()->to(base_url('master/zona'))->with('success', 'Zona berhasil dihapus');
    }

    // ── User ──────────────────────────────────────────────
    public function user()
    {
        return view('master/user', [
            'title' => 'Manajemen User',
            'users' => (new UserModel())->findAll(),
        ]);
    }

    public function simpanUser()
    {
        $model = new UserModel();
        $model->insert([
            'username' => $this->request->getPost('username'),
            'password' => hash('sha256', $this->request->getPost('password')),
            'role'     => $this->request->getPost('role'),
        ]);
        return redirect()->to(base_url('master/user'))->with('success', 'User berhasil ditambahkan');
    }

    public function hapusUser($id)
    {
        (new UserModel())->delete($id);
        return redirect()->to(base_url('master/user'))->with('success', 'User berhasil dihapus');
    }

    // ── Gerbang ───────────────────────────────────────────
    public function gerbang()
    {
        return view('master/gerbang', [
            'title'   => 'Data Gerbang',
            'gerbang' => (new GerbangModel())->findAll(),
        ]);
    }

    public function simpanGerbang()
    {
        (new GerbangModel())->insert([
            'nama_gerbang'  => $this->request->getPost('nama_gerbang'),
            'tipe_gerbang'  => $this->request->getPost('tipe_gerbang'),
            'status_palang' => 'tutup',
            'kamera_lpr'    => $this->request->getPost('kamera_lpr'),
        ]);
        return redirect()->to(base_url('master/gerbang'))->with('success', 'Gerbang berhasil ditambahkan');
    }

    public function hapusGerbang($id)
    {
        (new GerbangModel())->delete($id);
        return redirect()->to(base_url('master/gerbang'))->with('success', 'Gerbang berhasil dihapus');
    }
}