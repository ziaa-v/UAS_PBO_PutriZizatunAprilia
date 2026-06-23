<?php
require_once 'Karyawan.php';

class KaryawanTetap extends Karyawan {
    private $tunjanganKesehatan;
    private $opsiSahamId;

    public function __construct($data) {
        parent::__construct($data);
        $this->tunjanganKesehatan = $data['tunjangan_kesehatan'];
        $this->opsiSahamId = $data['opsi_saham_id'];
    }

    // Rumus: (hari_kerja_masuk * gaji_dasar_per_hari) + tunjangan_kesehatan
    public function hitungGajiBersih() {
        return ($this->hari_kerja_masuk * $this->gaji_dasar_per_hari) + $this->tunjanganKesehatan;
    }

    public function tampilkanProfilKaryawan() {
        return "🏥 Tunj. Sehat: Rp" . number_format($this->tunjanganKesehatan, 0, ',', '.') . " | 🔑 Opsi Saham ID: " . $this->opsiSahamId;
    }
}
?>