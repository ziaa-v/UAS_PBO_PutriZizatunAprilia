<?php
require_once 'Karyawan.php';

class KaryawanKontrak extends Karyawan {
    private $durasiKontrakBulan;
    private $agensiPenyalur;

    public function __construct($data) {
        parent::__construct($data);
        $this->durasiKontrakBulan = $data['durasi_kontrak_bulan'];
        $this->agensiPenyalur = $data['agensi_penyalur'];
    }

    // Rumus: hari_kerja_masuk * gaji_dasar_per_hari
    public function hitungGajiBersih() {
        return $this->hari_kerja_masuk * $this->gaji_dasar_per_hari;
    }

    public function tampilkanProfilKaryawan() {
        return "⏳ Kontrak: " . $this->durasiKontrakBulan . " Bulan | 🏢 Agensi: " . $this->agensiPenyalur;
    }
}
?>