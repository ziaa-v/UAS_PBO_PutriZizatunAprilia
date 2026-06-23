<?php
// index.php
require_once 'koneksi.php';
require_once 'kelas/KaryawanKontrak.php';
require_once 'kelas/KaryawanTetap.php';
require_once 'kelas/KaryawanMagang.php';

// Mengambil input cari dan mengamankannya dari SQL Injection
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';

// PERBAIKAN OOP MURNI: Memanggil koneksi database lewat metode statis class Database
$pdo = Database::getConnection();

// Memanggil query internal spesifik ber-WHERE milik masing-masing subclass
$data_kontrak = KaryawanKontrak::ambilDataPerJabatan($pdo, $cari);
$data_tetap   = KaryawanTetap::ambilDataPerJabatan($pdo, $cari);
$data_magang  = KaryawanMagang::ambilDataPerJabatan($pdo, $cari);

$dashboard = [
    'Kontrak' => [],
    'Tetap' => [],
    'Magang' => []
];

// Instansiasi objek langsung dari hasil filter query modular masing-masing anak
foreach ($data_kontrak as $row) { $dashboard['Kontrak'][] = new KaryawanKontrak($row); }
foreach ($data_tetap as $row)   { $dashboard['Tetap'][]   = new KaryawanTetap($row); }
foreach ($data_magang as $row)  { $dashboard['Magang'][]  = new KaryawanMagang($row); }

// Menghitung total data karyawan yang berhasil ditemukan
$total_karyawan = count($dashboard['Kontrak']) + count($dashboard['Tetap']) + count($dashboard['Magang']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pink Office - Payroll System Dashboard</title>
    <style>
        :root {
            --hot-pink: #ff1493;
            --soft-pink: #fff0f5;
            --pastel-pink: #ff69b4;
            --glass-bg: rgba(255, 255, 255, 0.90);
        }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 25px;
            background: linear-gradient(rgba(255, 240, 245, 0.45), rgba(255, 105, 180, 0.35)), 
                        url('https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1920') no-repeat center center fixed;
            background-size: cover;
        }

        .wrapper {
            max-width: 1300px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 20px;
            margin-bottom: 30px;
            border-bottom: 5px solid var(--hot-pink);
            box-shadow: 0 10px 25px rgba(255, 20, 147, 0.15);
        }

        header h1 {
            margin: 0;
            color: var(--hot-pink);
            font-size: 2.4em;
            letter-spacing: 1px;
        }

        header p {
            margin: 7px 0 0;
            color: #555;
            font-weight: 500;
        }

        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .search-input {
            width: 300px;
            padding: 12px 18px;
            border: 2px solid #ffccd5;
            border-radius: 12px;
            outline: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--hot-pink);
            box-shadow: 0 0 8px rgba(255, 20, 147, 0.2);
        }

        .btn-search {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--pastel-pink), var(--hot-pink));
            color: white;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            transition: transform 0.2s;
        }

        .btn-search:hover {
            transform: translateY(-2px);
        }

        .btn-reset {
            display: inline-block;
            padding: 12px 24px;
            background: #ffffff;
            color: var(--hot-pink);
            text-decoration: none;
            border-radius: 12px;
            font-weight: bold;
            font-size: 14px;
            border: 2px solid var(--pastel-pink);
            transition: all 0.2s;
        }

        .btn-reset:hover {
            background: var(--soft-pink);
            transform: translateY(-2px);
        }

        .card-section {
            background: var(--glass-bg);
            border-radius: 18px;
            padding: 25px;
            margin-bottom: 35px;
            backdrop-filter: blur(8px);
            border-top: 4px solid var(--pastel-pink);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        .section-title {
            color: var(--hot-pink);
            font-size: 1.5em;
            margin-top: 0;
            margin-bottom: 20px;
            text-transform: uppercase;
            border-left: 5px solid var(--hot-pink);
            padding-left: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        th {
            background-color: var(--pastel-pink);
            color: white;
            padding: 14px;
            font-size: 0.95em;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #f2f2f2;
            color: #333;
            font-size: 0.95em;
        }

        tr:hover {
            background-color: var(--soft-pink);
            transition: 0.2s ease;
        }

        /* PERBAIKAN ALIGNMENT STANDAR SLIP GAJI */
        .text-left {
            text-align: left;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* Mengatur nominal keuangan rata kanan agar sejajar presisi */
        .text-right {
            text-align: right;
            font-family: 'Courier New', Courier, monospace; /* Font monospace opsional agar angka sejajar sempurna */
            font-weight: 600;
        }

        .badge-info {
            display: inline-block;
            background: #fff5f8;
            color: #db2777;
            padding: 6px 14px;
            border-radius: 30px;
            border: 1px solid var(--pastel-pink);
            font-size: 0.88em;
            font-weight: 500;
            text-align: left;
        }

        .salary-text {
            color: #c2185b;
            font-weight: 700;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <header>
        <h1>🌸 ZIA OFFICE PAYROLL DASHBOARD 🌸</h1>
        <p>Sistem Informasi Manajemen Slip Gaji </p>

        <form action="" method="GET" class="search-container">
            <input 
                type="text" 
                name="cari" 
                class="search-input" 
                placeholder="🔍 Cari nama karyawan..." 
                value="<?php echo htmlspecialchars($cari); ?>"
            >
            <button type="submit" class="btn-search">Cari</button>
            <?php if ($cari != ''): ?>
                <a href="?" class="btn-reset">🔄 Reset Dashboard</a>
            <?php endif; ?>
        </form>
    </header>

    <?php if ($total_karyawan == 0 && $cari != ''): ?>
        <div class="card-section" style="text-align: center; padding: 40px 20px;">
            <h2 style="color: var(--hot-pink); margin: 0 0 10px 0;">😢 Karyawan Tidak Ditemukan</h2>
            <p style="color: #666; margin: 0;">Tidak ada nama karyawan yang cocok dengan kata kunci "<b><?php echo htmlspecialchars($cari); ?></b>".</p>
        </div>
    <?php endif; ?>

    <?php foreach ($dashboard as $status => $karyawan_list): ?>
        <?php if (!empty($karyawan_list) || $cari == ''): ?>
            <div class="card-section">
                <h2 class="section-title">💼 KARYAWAN STATUS: <?php echo $status; ?></h2>
                
                <table>
                    <thead>
                        <tr>
                            <th class="text-left">Nama Karyawan</th>
                            <th class="text-left">Departemen</th>
                            <th class="text-center">Kehadiran</th>
                            <th class="text-right">Gaji Dasar / Hari</th>
                            <th class="text-left">Atribut Spesifik Kategori</th>
                            <th class="text-right">Gaji Bersih (Polimorfisme)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($karyawan_list)): ?>
                            <tr><td colspan="6" style="text-align:center; color: #777; padding: 20px;">Tidak ada data karyawan pada kategori ini.</td></tr>
                        <?php else: ?>
                            <?php foreach ($karyawan_list as $k): ?>
                                <tr>
                                    <td class="text-left"><strong><?php echo htmlspecialchars($k->getNama()); ?></strong></td>
                                    <td class="text-left"><?php echo htmlspecialchars($k->getDepartemen()); ?></td>
                                    <td class="text-center"><b><?php echo htmlspecialchars($k->getHariKerja()); ?></b> Hari</td>
                                    <td class="text-right">Rp <?php echo number_format($k->getGajiDasar(), 0, ',', '.'); ?></td>
                                    <td class="text-left"><span class="badge-info"><?php echo $k->tampilkanProfilKaryawan(); ?></span></td>
                                    <td class="text-right salary-text">Rp <?php echo number_format($k->hitungGajiBersih(), 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

</body>
</html>