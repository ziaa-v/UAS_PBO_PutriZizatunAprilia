<?php
// index.php
require_once 'koneksi.php';
require_once 'kelas/KaryawanKontrak.php';
require_once 'kelas/KaryawanTetap.php';
require_once 'kelas/KaryawanMagang.php';

// Mengambil parameter pencarian dan filter kategori tab
$cari   = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'Semua';

// Memanggil koneksi database lewat metode statis class Database (OOP Murni)
$pdo = Database::getConnection();

// Mengambil data mentah dari database via query internal subclass
$data_kontrak = KaryawanKontrak::ambilDataPerJabatan($pdo, $cari);
$data_tetap   = KaryawanTetap::ambilDataPerJabatan($pdo, $cari);
$data_magang  = KaryawanMagang::ambilDataPerJabatan($pdo, $cari);

$dashboard = [
    'Kontrak' => [],
    'Tetap' => [],
    'Magang' => []
];

// Instansiasi objek langsung ke dalam array penampung
foreach ($data_kontrak as $row) { $dashboard['Kontrak'][] = new KaryawanKontrak($row); }
foreach ($data_tetap as $row)   { $dashboard['Tetap'][]   = new KaryawanTetap($row); }
foreach ($data_magang as $row)  { $dashboard['Magang'][]  = new KaryawanMagang($row); }

// Menghitung statistik untuk ringkasan komponen atas dashboard & grafik
$count_kontrak = count($dashboard['Kontrak']);
$count_tetap   = count($dashboard['Tetap']);
$count_magang  = count($dashboard['Magang']);
$total_karyawan = $count_kontrak + $count_tetap + $count_magang;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Penggajian Terintegrasi - PT. PUTRI ZIZATUN APRILIA</title>
    <!-- Load Chart.js CDN untuk Diagram Batang -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-pink: #f472b6; /* Soft Pink / Rose Pastel */
            --dark-pink: #db2777; /* Accent Pink untuk teks penting */
            --light-pink: #fdf2f8; /* Background Soft Pink Muda */
            --card-pink: #fff5f7;
            --bg-gray: #f8fafc;
            --dark-gray: #334155;
            --border-color: #fce7f3; /* Border Soft Pink */
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-gray);
            color: var(--dark-gray);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
        }

        /* Header Perusahaan Bertema Soft Pink Pastel */
        .corporate-header {
            background: linear-gradient(135deg, #fbcfe8 0%, #f472b6 100%);
            color: #4c0519;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(244, 114, 182, 0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 0.5px;
            font-weight: 700;
        }

        .header-title p {
            margin: 5px 0 0 0;
            opacity: 0.8;
            font-size: 14px;
            font-weight: 500;
        }

        .header-badge {
            background: rgba(255, 255, 255, 0.5);
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            color: #831843;
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        /* Layout Grid Atas untuk Statistik & Diagram */
        .top-layout-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        @media (max-width: 992px) {
            .top-layout-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Grid Ringkasan Data */
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .summary-card {
            background: white;
            padding: 20px;
            border-radius: 14px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
            border-left: 5px solid var(--primary-pink);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .summary-card.total { border-left-color: #cbd5e1; }
        .summary-card.kontrak { border-left-color: #fbcfe8; }
        .summary-card.tetap { border-left-color: #f472b6; }
        .summary-card.magang { border-left-color: #f9a8d4; }

        .summary-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
        }

        .summary-value {
            font-size: 28px;
            font-weight: 700;
            margin: 4px 0 0 0;
            color: #475569;
        }

        /* Card Wadah Diagram Batang */
        .chart-container {
            background: white;
            padding: 20px 25px;
            border-radius: 14px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
            display: flex;
            flex-direction: column;
            max-height: 240px;
        }

        .chart-title {
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        /* Filter Kategori Dan Form Pencarian */
        .controls-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .tab-group {
            display: flex;
            gap: 6px;
            background: #f1f5f9;
            padding: 6px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .tab-btn {
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            color: #475569;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }

        .tab-btn.active {
            background: white;
            color: var(--dark-pink);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(244, 114, 182, 0.3);
        }

        .search-box {
            display: flex;
            gap: 10px;
        }

        .search-input {
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            width: 280px;
            outline: none;
            font-size: 14px;
            background-color: white;
        }

        .search-input:focus {
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 3px rgba(244, 114, 182, 0.2);
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary-pink);
            color: white;
        }

        .btn-primary:hover { background: #ec4899; }

        /* Grid List Karyawan Bergaya Card - SEKARANG FULL BISA DIKLIK */
        .employee-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 25px;
        }

        .employee-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer; /* Mengubah kursor jadi tangan di seluruh area kartu */
            user-select: none; /* Mencegah teks ter-highlight berantakan pas diklik berkali-kali */
        }

        .employee-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -3px rgba(244, 114, 182, 0.15);
            border-color: var(--primary-pink);
        }

        .card-top {
            padding: 20px;
            background: linear-gradient(to bottom, var(--card-pink), white);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .emp-name {
            font-size: 18px;
            font-weight: 700;
            color: #db2777;
            margin: 0;
        }

        .employee-card:hover .emp-name {
            text-decoration: underline;
        }

        .emp-dept {
            font-size: 13px;
            color: #64748b;
            margin: 4px 0 0 0;
            font-weight: 500;
        }

        .badge-type {
            font-size: 11px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
            text-transform: uppercase;
        }

        .badge-type.kontrak { background: #fdf2f8; color: #db2777; border: 1px solid #fbcfe8; }
        .badge-type.tetap { background: #fce7f3; color: #be185d; border: 1px solid #f9a8d4; }
        .badge-type.magang { background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; }

        .card-body {
            padding: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .info-label { color: #64748b; }
        .info-value { font-weight: 600; text-align: right; }

        .card-footer-salary {
            background: var(--light-pink);
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--border-color);
        }

        .salary-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--dark-pink);
        }

        .salary-amount {
            font-size: 18px;
            font-weight: 800;
            color: #be185d;
            font-family: 'Courier New', monospace;
        }

        /* JENDELA POP-UP DETAIL SLIP GAJI (MODAL) */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(253, 242, 248, 0.6);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 40px;
            border-radius: 16px;
            width: 100%;
            max-width: 550px;
            box-shadow: 0 25px 50px -12px rgba(244, 114, 182, 0.15);
            border-top: 8px solid var(--primary-pink);
            position: relative;
        }

        .close-modal {
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 28px;
            font-weight: bold;
            color: #cbd5e1;
            cursor: pointer;
        }

        .close-modal:hover { color: #64748b; }

        .slip-header {
            text-align: center;
            border-bottom: 2px dashed var(--border-color);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .slip-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--dark-gray);
            margin: 0;
        }

        .slip-grid {
            margin-bottom: 20px;
        }

        .slip-section-title {
            font-size: 12px;
            font-weight: 700;
            color: var(--dark-pink);
            text-transform: uppercase;
            margin-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 4px;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header Resmi PT. PUTRI ZIZATUN APRILIA -->
    <header class="corporate-header">
        <div class="header-title">
            <h1>🏢 SLIP GAJI KARYAWAN</h1>
            <p>PT. PUTRI ZIZATUN APRILIA — Sistem Penggajian Terintegrasi </p>
        </div>
        <div class="header-badge">Periode: Juni 2026</div>
    </header>

    <!-- Layout Atas: Statistik Angka Kiri & Grafik Kanan -->
    <div class="top-layout-grid">
        <div class="summary-grid">
            <div class="summary-card total">
                <div class="summary-label">Total Karyawan</div>
                <div class="summary-value"><?php echo $total_karyawan; ?></div>
            </div>
            <div class="summary-card kontrak">
                <div class="summary-label">Kontrak</div>
                <div class="summary-value"><?php echo $count_kontrak; ?></div>
            </div>
            <div class="summary-card tetap">
                <div class="summary-label">Tetap</div>
                <div class="summary-value"><?php echo $count_tetap; ?></div>
            </div>
            <div class="summary-card magang">
                <div class="summary-label">Magang</div>
                <div class="summary-value"><?php echo $count_magang; ?></div>
            </div>
        </div>

        <!-- DIAGRAM BATANG SOFT PINK -->
        <div class="chart-container">
            <div class="chart-title">📊 Grafik Alokasi Karyawan Per Jabatan</div>
            <div style="flex: 1; position: relative;">
                <canvas id="barChartKaryawan"></canvas>
            </div>
        </div>
    </div>

    <!-- Filter Tab & Pencarian -->
    <div class="controls-row">
        <div class="tab-group">
            <a href="?filter=Semua&cari=<?php echo urlencode($cari); ?>" class="tab-btn <?php echo $filter === 'Semua' ? 'active' : ''; ?>">📂 Semua</a>
            <a href="?filter=Kontrak&cari=<?php echo urlencode($cari); ?>" class="tab-btn <?php echo $filter === 'Kontrak' ? 'active' : ''; ?>">⏳ Kontrak</a>
            <a href="?filter=Tetap&cari=<?php echo urlencode($cari); ?>" class="tab-btn <?php echo $filter === 'Tetap' ? 'active' : ''; ?>">🏥 Tetap</a>
            <a href="?filter=Magang&cari=<?php echo urlencode($cari); ?>" class="tab-btn <?php echo $filter === 'Magang' ? 'active' : ''; ?>">🎓 Magang</a>
        </div>

        <form action="" method="GET" class="search-box">
            <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filter); ?>">
            <input type="text" name="cari" class="search-input" placeholder="Cari nama karyawan perusahaan..." value="<?php echo htmlspecialchars($cari); ?>">
            <button type="submit" class="btn btn-primary">Cari Karyawan</button>
        </form>
    </div>

    <!-- Tampilan Card Karyawan dengan Aksi Klik di Seluruh Area Kolom -->
    <div class="employee-grid">
        <?php
        $no_data = true;
        foreach ($dashboard as $status => $karyawan_list) {
            if ($filter !== 'Semua' && $filter !== $status) {
                continue;
            }

            foreach ($karyawan_list as $k) {
                $no_data = false;
                $json_data = json_encode([
                    'nama' => $k->getNama(),
                    'dept' => $k->getDepartemen(),
                    'status' => $status,
                    'hadir' => $k->getHariKerja(),
                    'gaji_dasar' => "Rp " . number_format($k->getGajiDasar(), 0, ',', '.'),
                    'fasilitas' => $k->tampilkanProfilKaryawan(),
                    'gaji_bersih' => "Rp " . number_format($k->hitungGajiBersih(), 0, ',', '.')
                ]);
                ?>
                <!-- Event onclick sekarang dipasang langsung pada pembungkus utama kartu (.employee-card) -->
                <div class="employee-card" onclick='bukaSlipGaji(<?php echo htmlspecialchars($json_data, ENT_QUOTES, 'UTF-8'); ?>)'>
                    <div class="card-top">
                        <div>
                            <h3 class="emp-name"><?php echo htmlspecialchars($k->getNama()); ?></h3>
                            <p class="emp-dept"><?php echo htmlspecialchars($k->getDepartemen()); ?></p>
                        </div>
                        <span class="badge-type <?php echo strtolower($status); ?>"><?php echo $status; ?></span>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <span class="info-label">Total Kehadiran</span>
                            <span class="info-value"><?php echo htmlspecialchars($k->getHariKerja()); ?> Hari</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Gaji Dasar / Hari</span>
                            <span class="info-value">Rp <?php echo number_format($k->getGajiDasar(), 0, ',', '.'); ?></span>
                        </div>
                        <div class="info-row" style="margin-top: 15px;">
                            <span class="info-value" style="color: #64748b; font-size: 13px; text-align: left; font-weight: normal;">
                                <?php echo $k->tampilkanProfilKaryawan(); ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-footer-salary">
                        <span class="salary-label">GAJI BERSIH:</span>
                        <span class="salary-amount">Rp <?php echo number_format($k->hitungGajiBersih(), 0, ',', '.'); ?></span>
                    </div>
                </div>
                <?php
            }
        }

        if ($no_data) {
            echo "<div style='grid-column: 1/-1; text-align: center; padding: 50px; background: white; border-radius: 12px; border: 1px dashed var(--border-color);'>
                    <h3 style='color: #64748b; margin: 0;'>Data entri karyawan tidak ditemukan atau kosong.</h3>
                  </div>";
        }
        ?>
    </div>
</div>

<!-- POP-UP DETAIL SLIP GAJI -->
<div id="slipModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="tutupSlipGaji()">&times;</span>
        <div class="slip-header">
            <div class="slip-title">SLIP GAJI RESMI PERUSAHAAN</div>
            <div style="font-size: 12px; color: #64748b; margin-top: 5px; font-weight: bold; letter-spacing: 0.5px;">PT. PUTRI ZIZATUN APRILIA</div>
        </div>
        
        <div class="slip-grid">
            <div class="slip-section-title">Informasi Pegawai</div>
            <div class="info-row"><span class="info-label">Nama Karyawan</span><span id="modalNama" class="info-value" style="color:#db2777;"></span></div>
            <div class="info-row"><span class="info-label">Departemen / Divisi</span><span id="modalDept" class="info-value"></span></div>
            <div class="info-row"><span class="info-label">Status Ketenagakerjaan</span><span id="modalStatus" class="info-value"></span></div>
        </div>

        <div class="slip-grid">
            <div class="slip-section-title">Rincian Perhitungan</div>
            <div class="info-row"><span class="info-label">Jumlah Kehadiran Kerja</span><span id="modalHadir" class="info-value"></span></div>
            <div class="info-row"><span class="info-label">Tarif Gaji Dasar Pokok</span><span id="modalGajiDasar" class="info-value"></span></div>
            <div class="info-row"><span class="info-label">Komponen Tambahan</span><span id="modalFasilitas" class="info-value" style="font-size:12px; color:#475569;"></span></div>
        </div>

        <div class="slip-grid" style="margin-top: 30px; background: var(--light-pink); padding: 15px; border-radius: 10px; border: 1px solid var(--border-color);">
            <div class="info-row" style="margin-bottom: 0;">
                <span class="salary-label" style="font-size: 15px;">TOTAL GAJI DITERIMA:</span>
                <span id="modalGajiBersih" class="salary-amount" style="font-size: 22px;"></span>
            </div>
        </div>
        
        <div style="text-align: center; font-size: 11px; color: #94a3b8; margin-top: 25px; border-top: 1px solid var(--border-color); padding-top: 15px;">
            Sistem Keuangan Sah — Diunduh otomatis melalui aplikasi PBO.
        </div>
    </div>
</div>

<script>
// RENDER DIAGRAM BATANG
const ctx = document.getElementById('barChartKaryawan').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Kontrak', 'Tetap', 'Magang'],
        datasets: [{
            label: ' Jumlah Karyawan',
            data: [<?php echo $count_kontrak; ?>, <?php echo $count_tetap; ?>, <?php echo $count_magang; ?>],
            backgroundColor: [
                'rgba(251, 207, 232, 0.7)',
                'rgba(244, 114, 182, 0.7)',
                'rgba(249, 168, 212, 0.7)'
            ],
            borderColor: [
                '#fbcfe8',
                '#f472b6',
                '#f9a8d4'
            ],
            borderWidth: 1.5,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, color: '#64748b' },
                grid: { color: '#f1f5f9' }
            },
            x: {
                ticks: { color: '#64748b', font: { weight: '600' } },
                grid: { display: false }
            }
        }
    }
});

// Kontrol Modal Detail
function bukaSlipGaji(data) {
    document.getElementById('modalNama').innerText = data.nama;
    document.getElementById('modalDept').innerText = data.dept;
    document.getElementById('modalStatus').innerText = data.status;
    document.getElementById('modalHadir').innerText = data.hadir + " Hari";
    document.getElementById('modalGajiDasar').innerText = data.gaji_dasar;
    document.getElementById('modalFasilitas').innerHTML = data.fasilitas;
    document.getElementById('modalGajiBersih').innerText = data.gaji_bersih;
    
    document.getElementById('slipModal').style.display = 'flex';
}

function tutupSlipGaji() {
    document.getElementById('slipModal').style.display = 'none';
}

window.onclick = function(event) {
    var modal = document.getElementById('slipModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

</body>
</html>