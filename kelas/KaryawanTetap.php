<?php
// kelas/KaryawanTetap.php
require_once 'Karyawan.php';

class KaryawanTetap extends Karyawan {
    private $tunjanganKesehatan;
    private $opsiSahamId;

    public function __construct($data) {
        parent::__construct($data);
        $this->tunjanganKesehatan = $data['tunjangan_kesehatan'] ?? 0;
        $this->opsiSahamId = $data['opsi_saham_id'] ?? '-';
    }

    // Query internal spesifik ber-WHERE + Mendukung parameter Pencarian
    public static function ambilDataPerJabatan($pdo, $cari = '') {
        if ($cari != '') {
            $stmt = $pdo->prepare("SELECT * FROM tabel_karyawan WHERE jenis_karyawan = 'Tetap' AND nama_karyawan LIKE :cari");
            $stmt->execute(['cari' => '%' . $cari . '%']);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM tabel_karyawan WHERE jenis_karyawan = 'Tetap'");
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function hitungGajiBersih() {
        return ($this->hari_kerja_masuk * $this->gaji_dasar_per_hari) + $this->tunjanganKesehatan;
    }

    public function tampilkanProfilKaryawan() {
        return "🏥 Tunj. Sehat: Rp" . number_format($this->tunjanganKesehatan, 0, ',', '.') . " | 🔑 Opsi Saham ID: " . $this->opsiSahamId;
    }
}
?>