<?php
require_once 'Karyawan.php';

class KaryawanMagang extends Karyawan {
    private $uangSakuBulanan;
    private $sertifikatKampusMerdeka;

    public function __construct($data) {
        parent::__construct($data);
        $this->uangSakuBulanan = $data['uang_saku_bulanan'];
        $this->sertifikatKampusMerdeka = $data['sertifikat_kampus_merdeka'];
    }

    // Rumus: (hari_kerja_masuk * gaji_dasar_per_hari) * 0.80
    public function hitungGajiBersih() {
        return ($this->hari_kerja_masuk * $this->gaji_dasar_per_hari) * 0.80;
    }

    public function tampilkanProfilKaryawan() {
        return "💰 Uang Saku Bulanan: Rp" . number_format($this->uangSakuBulanan, 0, ',', '.') . " | 🎓 Program: " . $this->sertifikatKampusMerdeka;
    }
}
?>