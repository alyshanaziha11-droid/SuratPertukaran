<?php
include 'config/auth.php';
include 'config/db.php';

$sql = "SELECT 
            murid.*, 
            urusan.kod_urusan 
        FROM murid 
        JOIN urusan ON murid.urusan_id = urusan.id
        ORDER BY murid.id DESC";

$data = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Senarai Murid - Sistem Surat PPD Besut</title>

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
            width: 58px;
        }

        .system-title {
            margin: 0;
            font-size: 23px;
            font-weight: bold;
        }

        .system-subtitle {
            margin: 4px 0 0 0;
            font-size: 13px;
            opacity: 0.95;
        }

        .topbar-menu a {
            color: white;
            text-decoration: none;
            margin-left: 18px;
            font-weight: bold;
            font-size: 14px;
        }

        .topbar-menu a:hover {
            text-decoration: underline;
        }

        .container {
            width: 94%;
            max-width: 1250px;
            margin: 30px auto;
        }

        .page-header {
            background: white;
            border-left: 6px solid #9B0000;
            padding: 22px 24px;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h2 {
            margin: 0;
            font-size: 26px;
            color: #111;
        }

        .page-header p {
            margin: 6px 0 0 0;
            color: #666;
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 9px;
            font-weight: bold;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #9B0000;
            color: white;
        }

        .btn-primary:hover {
            background: #760000;
        }

        .btn-pdf {
            background: #9B0000;
            color: white;
            padding: 8px 12px;
            border-radius: 7px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
        }

        .btn-edit {
            background: #f5dede;
            color: #7a0000;
            padding: 8px 12px;
            border-radius: 7px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            margin-left: 5px;
        }

        .btn-pdf:hover {
            background: #760000;
        }

        .btn-edit:hover {
            background: #e8c7c7;
        }

        .table-card {
            background: white;
            padding: 22px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #9B0000;
            color: white;
            padding: 14px 12px;
            font-size: 14px;
            text-align: left;
            white-space: nowrap;
        }

        td {
            padding: 13px 12px;
            border-bottom: 1px solid #eeeeee;
            font-size: 14px;
            vertical-align: middle;
        }

        tr:hover {
            background: #fcf4f4;
        }

        .bil {
            text-align: center;
            width: 60px;
            font-weight: bold;
        }

        .kod {
            font-weight: bold;
            color: #9B0000;
        }

        .tindakan {
            white-space: nowrap;
        }

        .empty {
            text-align: center;
            color: #777;
            padding: 25px;
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

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .system-title {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>

<div class="topbar">
    <div class="topbar-left">
        <img src="uploads/Logo-Korporat-KPM-BI-Tulisan-Putih.png" class="logo">

        <div>
            <h1 class="system-title">Sistem Penjanaan Surat Pertukaran Murid</h1>
            <p class="system-subtitle">Pejabat Pendidikan Daerah Besut</p>
        </div>
    </div>

    <div class="topbar-menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="tambah_murid.php">Tambah Murid</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="page-header">
        <div>
            <h2>Senarai Murid</h2>
            <p>Senarai rekod murid yang telah didaftarkan untuk penjanaan surat pertukaran.</p>
        </div>

        <a href="tambah_murid.php" class="btn btn-primary">+ Tambah Murid</a>
    </div>

    <div class="table-card">
        <table>
            <tr>
                <th>Bil</th>
                <th>Tarikh Rekod</th>
                <th>Nama Murid</th>
                <th>No KP</th>
                <th>Sekolah Baharu</th>
                <th>Kod Urusan</th>
                <th>Tindakan</th>
            </tr>

            <?php if (count($data) > 0): ?>
                <?php 
                $bil = 1;
                foreach($data as $row): 
                ?>
                <tr>
                    <td class="bil"><?= $bil++ ?></td>

                    <td>
                        <?php 
                        if (!empty($row['tarikh_rekod'])) {
                            echo date("d/m/Y", strtotime($row['tarikh_rekod']));
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>

                    <td><?= strtoupper($row['nama_murid']) ?></td>
                    <td><?= $row['no_kp'] ?></td>
                    <td><?= strtoupper($row['sekolah_baru']) ?></td>
                    <td class="kod"><?= strtoupper($row['kod_urusan']) ?></td>

                    <td class="tindakan">
                        <a href="jana_pdf.php?id=<?= $row['id'] ?>" target="_blank" class="btn-pdf">PDF</a>
                        <a href="edit_murid.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>

            <?php else: ?>
                <tr>
                    <td colspan="7" class="empty">Tiada rekod murid lagi.</td>
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