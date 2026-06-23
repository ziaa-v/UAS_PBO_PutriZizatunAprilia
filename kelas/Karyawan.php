<?php
abstract class Karyawan {
    // Properti wajib protected terenkapsulasi
    protected $id_karyawan;
    protected $nama_karyawan;
    protected $departemen;
    protected $hari_kerja_masuk;
    protected $gaji_dasar_per_hari;

    public function __construct($data) {
        $this->id_karyawan = $data['id_karyawan'];
        $this->nama_karyawan = $data['nama_karyawan'];
        $this->departemen = $data['departemen'];
        $this->hari_kerja_masuk = $data['hari_kerja_masuk'];
        $this->gaji_dasar_per_hari = $data['gaji_dasar_per_hari'];
    }

    public function getNama() { return $this->nama_karyawan; }
    public function getDepartemen() { return $this->departemen; }
    public function getHariKerja() { return $this->hari_kerja_masuk; }
    public function getGajiDasar() { return $this->gaji_dasar_per_hari; }

    // Abstract method wajib tanpa body
    abstract public function hitungGajiBersih();
    abstract public function tampilkanProfilKaryawan();
}
?>