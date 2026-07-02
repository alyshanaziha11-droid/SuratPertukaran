<?php
include 'config/auth.php';
include 'config/db.php';

$totalMurid = $conn->query("SELECT COUNT(*) FROM murid")->fetchColumn();
$totalUrusan = $conn->query("SELECT COUNT(*) FROM urusan")->fetchColumn();
$totalPegawai = $conn->query("SELECT COUNT(*) FROM pegawai WHERE status_aktif = 'AKTIF'")->fetchColumn();

$rekodTerkini = $conn->query("
    SELECT murid.nama_murid, murid.no_kp, murid.sekolah_baru, murid.tarikh_rekod, urusan.kod_urusan
    FROM murid
    JOIN urusan ON murid.urusan_id = urusan.id
    ORDER BY murid.id DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard Sistem Surat PPD Besut</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            color: #1f1f1f;
        }

        .topbar {
            background: linear-gradient(135deg, #9B0000, #6E0000);
            color: white;
            padding: 18px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 60px;
            height: auto;
        }

        .system-title {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .system-subtitle {
            margin: 4px 0 0 0;
            font-size: 13px;
            opacity: 0.95;
        }

        .logout-btn {
            text-decoration: none;
            background: white;
            color: #9B0000;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 14px;
        }

        .logout-btn:hover {
            background: #f3f3f3;
        }

        .container {
            width: 92%;
            max-width: 1200px;
            margin: 30px auto;
        }

        .welcome-box {
            background: white;
            border-left: 6px solid #9B0000;
            padding: 22px 24px;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            margin-bottom: 25px;
        }

        .welcome-box h2 {
            margin: 0 0 8px 0;
            font-size: 24px;
            color: #111;
        }

        .welcome-box p {
            margin: 0;
            color: #555;
            line-height: 1.5;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            border-top: 5px solid #9B0000;
        }

        .card h3 {
            margin: 0 0 12px 0;
            font-size: 15px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card .number {
            font-size: 34px;
            font-weight: bold;
            color: #9B0000;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .menu-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            border: 1px solid #eee;
        }

        .menu-card h3 {
            margin: 0 0 10px 0;
            color: #9B0000;
            font-size: 20px;
        }

        .menu-card p {
            margin: 0 0 18px 0;
            color: #555;
            line-height: 1.5;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 14px;
            margin-right: 8px;
        }

        .btn-primary {
            background: #9B0000;
            color: white;
        }

        .btn-primary:hover {
            background: #760000;
        }

        .btn-secondary {
            background: #f5dede;
            color: #7a0000;
        }

        .btn-secondary:hover {
            background: #efd1d1;
        }

        .section-title {
            margin: 0 0 16px 0;
            font-size: 22px;
            color: #111;
        }

        .table-box {
            background: white;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #9B0000;
            color: white;
            padding: 13px;
            text-align: left;
            font-size: 14px;
        }

        table td {
            padding: 12px 13px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        table tr:hover {
            background: #fcf4f4;
        }

        .footer-note {
            margin-top: 20px;
            text-align: center;
            color: #777;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .system-title {
                font-size: 20px;
            }

            .container {
                width: 94%;
            }
        }
    </style>
</head>
<body>

    <div class="topbar">
        <div class="topbar-left">
            <img src="uploads/Logo-Korporat-KPM-BI-Tulisan-Putih.png" alt="Logo KPM" class="logo">

            <div>
                <h1 class="system-title">Sistem Penjanaan Surat Pertukaran Murid</h1>
                <p class="system-subtitle">Pejabat Pendidikan Daerah Besut</p>
            </div>
        </div>

        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="container">

        <div class="welcome-box">
            <h2>Selamat Datang</h2>
            <p>
                Sistem ini digunakan untuk pengurusan maklumat murid dan penjanaan surat pertukaran murid
                secara sistematik, cepat, dan teratur bagi kegunaan Pejabat Pendidikan Daerah Besut.
            </p>
        </div>

        <div class="card-grid">
            <div class="card">
                <h3>Jumlah Rekod Murid</h3>
                <div class="number"><?= $totalMurid ?></div>
            </div>

            <div class="card">
                <h3>Jumlah Urusan</h3>
                <div class="number"><?= $totalUrusan ?></div>
            </div>

            <div class="card">
                <h3>Pegawai Aktif</h3>
                <div class="number"><?= $totalPegawai ?></div>
            </div>
        </div>

        <h2 class="section-title">Menu Utama</h2>

        <div class="menu-grid">
            <div class="menu-card">
                <h3>Tambah Murid</h3>
                <p>Masukkan maklumat murid baharu untuk tujuan penjanaan surat pertukaran.</p>
                <a href="tambah_murid.php" class="btn btn-primary">Buka Borang</a>
            </div>

            <div class="menu-card">
                <h3>Senarai Murid</h3>
                <p>Lihat semua rekod murid, cetak PDF surat, dan kemas kini maklumat murid.</p>
                <a href="senarai_murid.php" class="btn btn-primary">Lihat Senarai</a>
            </div>

            <div class="menu-card">
                <h3>Pengurusan Pegawai</h3>
                <p>Urus maklumat pegawai penandatangan termasuk jawatan dan unit/sektor.</p>
                <a href="pegawai.php" class="btn btn-secondary">Urus Pegawai</a>
            </div>
        </div>

        <h2 class="section-title">Rekod Terkini</h2>

        <div class="table-box">
            <table>
                <tr>
                    <th>Bil</th>
                    <th>Tarikh Rekod</th>
                    <th>Nama Murid</th>
                    <th>No KP</th>
                    <th>Sekolah Baharu</th>
                    <th>Kod Urusan</th>
                </tr>

                <?php if (count($rekodTerkini) > 0): ?>
                    <?php $bil = 1; ?>
                    <?php foreach ($rekodTerkini as $row): ?>
                        <tr>
                            <td><?= $bil++ ?></td>
                            <td>
                                <?= !empty($row['tarikh_rekod']) ? date("d/m/Y", strtotime($row['tarikh_rekod'])) : '-' ?>
                            </td>
                            <td><?= strtoupper($row['nama_murid']) ?></td>
                            <td><?= $row['no_kp'] ?></td>
                            <td><?= strtoupper($row['sekolah_baru']) ?></td>
                            <td><?= strtoupper($row['kod_urusan']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">Tiada rekod murid lagi.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <div class="footer-note">
            © <?= date('Y') ?> Sistem Penjanaan Surat Pertukaran Murid - PPD Besut
        </div>

    </div>

</body>
</html>