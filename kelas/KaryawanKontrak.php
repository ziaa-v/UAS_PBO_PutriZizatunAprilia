<?php
// kelas/KaryawanKontrak.php
require_once 'Karyawan.php';

class KaryawanKontrak extends Karyawan {
    private $durasiKontrakBulan;
    private $agensiPenyalur;

    public function __construct($data) {
        parent::__construct($data);
        $this->durasiKontrakBulan = $data['durasi_kontrak_bulan'] ?? 0;
        $this->agensiPenyalur = $data['agensi_penyalur'] ?? '-';
    }

    // Query internal spesifik ber-WHERE + Mendukung parameter Pencarian
    public static function ambilDataPerJabatan($pdo, $cari = '') {
        if ($cari != '') {
            $stmt = $pdo->prepare("SELECT * FROM tabel_karyawan WHERE jenis_karyawan = 'Kontrak' AND nama_karyawan LIKE :cari");
            $stmt->execute(['cari' => '%' . $cari . '%']);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM tabel_karyawan WHERE jenis_karyawan = 'Kontrak'");
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function hitungGajiBersih() {
        return $this->hari_kerja_masuk * $this->gaji_dasar_per_hari;
    }

    public function tampilkanProfilKaryawan() {
        return "⏳ Kontrak: " . $this->durasiKontrakBulan . " Bulan | 🏢 Agensi: " . $this->agensiPenyalur;
    }
}
?>