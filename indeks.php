<?php
require_once 'koneksi.php';
require_once 'kelas/KaryawanKontrak.php';
require_once 'kelas/KaryawanTetap.php';
require_once 'kelas/KaryawanMagang.php';

// Fetch data kolektif
$query = $pdo->query("SELECT * FROM tabel_karyawan");
$semua_karyawan = $query->fetchAll(PDO::FETCH_ASSOC);

$dashboard = [
    'Kontrak' => [],
    'Tetap' => [],
    'Magang' => []
];

// Instansiasi objek berbasis tipe data (Polimorfisme)
foreach ($semua_karyawan as $row) {
    if ($row['jenis_karyawan'] == 'Kontrak') {
        $dashboard['Kontrak'][] = new KaryawanKontrak($row);
    } elseif ($row['jenis_karyawan'] == 'Tetap') {
        $dashboard['Tetap'][] = new KaryawanTetap($row);
    } elseif ($row['jenis_karyawan'] == 'Magang') {
        $dashboard['Magang'][] = new KaryawanMagang($row);
    }
}
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
            --glass-bg: rgba(255, 255, 255, 0.88);
        }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 25px;
            /* Gambar Background Kantor Estetik Bernuansa Magenta/Pink Soft */
            background: linear-gradient(rgba(255, 240, 245, 0.45), rgba(255, 105, 180, 0.35)), 
                        url('https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1920') no-repeat center center fixed;
            background-size: cover;
        }

        .wrapper {
            max-width: 1250px;
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
            font-size: 2.6em;
            letter-spacing: 1px;
        }

        header p {
            margin: 7px 0 0;
            color: #555;
            font-weight: 500;
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
            font-size: 1.7em;
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
            text-align: left;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #f2f2f2;
            color: #333;
        }

        tr:hover {
            background-color: var(--soft-pink);
            transition: 0.2s ease;
        }

        .badge-info {
            background: #fff5f8;
            color: #db2777;
            padding: 6px 14px;
            border-radius: 30px;
            border: 1px solid var(--pastel-pink);
            font-size: 0.88em;
            font-weight: 500;
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
        <h1>🌸 PINK OFFICE PAYROLL DASHBOARD 🌸</h1>
        <p>Sistem Informasi Manajemen Slip Gaji TRPL 1B — Putri Zizatun Aprilia</p>
    </header>

    <?php foreach ($dashboard as $status => $karyawan_list): ?>
        <div class="card-section">
            <h2 class="section-title">💼 KARYAWAN STATUS: <?php echo $status; ?></h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Nama Karyawan</th>
                        <th>Departemen</th>
                        <th>Kehadiran (Hari)</th>
                        <th>Gaji / Hari</th>
                        <th>Atribut Spesifik Jabatan</th>
                        <th>Gaji Bersih (Polimorfisme)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($karyawan_list)): ?>
                        <tr><td colspan="6" style="text-align:center; color: #777;">Belum ada data pada kategori ini.</td></tr>
                    <?php else: ?>
                        <?php foreach ($karyawan_list as $k): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($k->getNama()); ?></strong></td>
                                <td><?php echo htmlspecialchars($k->getDepartemen()); ?></td>
                                <td><?php echo htmlspecialchars($k->getHariKerja()); ?> Hari</td>
                                <td>Rp <?php echo number_format($k->getGajiDasar(), 0, ',', '.'); ?></td>
                                <td><span class="badge-info"><?php echo $k->tampilkanProfilKaryawan(); ?></span></td>
                                <td class="salary-text">Rp <?php echo number_format($k->hitungGajiBersih(), 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>