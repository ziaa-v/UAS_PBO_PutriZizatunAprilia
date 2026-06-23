<?php
// kelas/KaryawanMagang.php
require_once 'Karyawan.php';

class KaryawanMagang extends Karyawan {
    private $uangSakuBulanan;
    private $sertifikatKampusMerdeka;

    public function __construct($data) {
        parent::__construct($data);
        $this->uangSakuBulanan = $data['uang_saku_bulanan'] ?? 0;
        $this->sertifikatKampusMerdeka = $data['sertifikat_kampus_merdeka'] ?? '-';
    }

    // Query internal spesifik ber-WHERE + Mendukung parameter Pencarian
    public static function ambilDataPerJabatan($pdo, $cari = '') {
        if ($cari != '') {
            $stmt = $pdo->prepare("SELECT * FROM tabel_karyawan WHERE jenis_karyawan = 'Magang' AND nama_karyawan LIKE :cari");
            $stmt->execute(['cari' => '%' . $cari . '%']);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM tabel_karyawan WHERE jenis_karyawan = 'Magang'");
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function hitungGajiBersih() {
        return ($this->hari_kerja_masuk * $this->gaji_dasar_per_hari) * 0.80;
    }

    public function tampilkanProfilKaryawan() {
        return "💰 Uang Saku Bulanan: Rp" . number_format($this->uangSakuBulanan, 0, ',', '.') . " | 🎓 Program: " . $this->sertifikatKampusMerdeka;
    }
}
?>