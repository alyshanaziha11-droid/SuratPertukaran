<?php
include 'config/auth.php';
include 'config/db.php';

$tahunKini = date('Y');

$urusan = $conn->query("SELECT * FROM urusan")->fetchAll(PDO::FETCH_ASSOC);

$pegawai = $conn->query("
    SELECT * FROM pegawai 
    WHERE status_aktif = 'AKTIF'
    ORDER BY nama_pegawai ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tambah Murid - Sistem Surat PPD Besut</title>

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
            max-width: 1150px;
            margin: 30px auto;
        }

        .page-header {
            background: white;
            border-left: 6px solid #9B0000;
            padding: 22px 24px;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            margin-bottom: 25px;
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

        .form-card {
            background: white;
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }

        .section-title {
            font-size: 18px;
            color: #9B0000;
            margin: 10px 0 18px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #f1dada;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px 22px;
            margin-bottom: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full {
            grid-column: span 2;
        }

        label {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 7px;
            color: #333;
        }

        input,
        select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #cfcfcf;
            border-radius: 9px;
            font-size: 14px;
            outline: none;
            background: white;
        }

        input:focus,
        select:focus {
            border-color: #9B0000;
            box-shadow: 0 0 0 3px rgba(155,0,0,0.10);
        }

        input[readonly] {
            background: #f4f4f4;
            color: #555;
        }

        .hint {
            font-size: 12px;
            color: #777;
            margin-top: 5px;
        }

        .button-row {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 25px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .btn {
            text-decoration: none;
            border: none;
            padding: 12px 18px;
            border-radius: 9px;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            display: inline-block;
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
            background: #e8c7c7;
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

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: span 1;
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
        <a href="senarai_murid.php">Senarai Murid</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="page-header">
        <h2>Borang Maklumat Murid</h2>
        <p>Sila lengkapkan maklumat murid untuk menjana surat pertukaran secara automatik.</p>
    </div>

    <div class="form-card">

        <form action="simpan_murid.php" method="POST">

            <div class="section-title">Maklumat Urusan</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Kod Urusan</label>
                    <select name="urusan_id" required>
                        <option value="">Pilih Urusan</option>
                        <?php foreach($urusan as $u): ?>
                            <option value="<?= $u['id'] ?>">
                                <?= $u['kod_urusan'] ?> - <?= $u['nama_urusan'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Pegawai Penandatangan</label>
                    <select name="pegawai_id" required>
                        <option value="">Pilih Pegawai</option>
                        <?php foreach($pegawai as $p): ?>
                            <option value="<?= $p['id'] ?>">
                                <?= $p['nama_pegawai'] ?> - <?= $p['jawatan'] ?> - <?= $p['unit_sektor'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="section-title">Maklumat Penjaga & Alamat</div>

            <div class="form-grid">
                <div class="form-group full">
                    <label>Nama Penjaga</label>
                    <input type="text" name="nama_penjaga" required>
                </div>

                <div class="form-group full">
                    <label>Alamat Tetap - Baris 1</label>
                    <input 
                        type="text" 
                        name="alamat_baris1" 
                        placeholder="Contoh: LOT 54 KG LIMBONGAN" 
                        required
                    >
                </div>

                <div class="form-group full">
                    <label>Alamat Tetap - Baris 2</label>
                    <input 
                        type="text" 
                        name="alamat_baris2" 
                        placeholder="Contoh: 22200 KAMPUNG RAJA" 
                        required
                    >
                    <div class="hint">Negeri akan ditetapkan secara automatik sebagai TERENGGANU.</div>
                </div>
            </div>

            <input type="hidden" name="alamat_baris3" value="">
            <input type="hidden" name="poskod" value="">
            <input type="hidden" name="bandar" value="">
            <input type="hidden" name="negeri" value="TERENGGANU">
            <input type="hidden" name="daerah" value="">

            <div class="section-title">Maklumat Murid</div>

            <div class="form-grid">
                <div class="form-group full">
                    <label>Nama Murid</label>
                    <input type="text" name="nama_murid" required>
                </div>

                <div class="form-group">
                    <label>No KP / MyKid</label>
                    <input 
                        type="text" 
                        name="no_kp" 
                        id="no_kp"
                        placeholder="Contoh: 190612-03-0201"
                        maxlength="14"
                        required
                    >
                    <div class="hint">Tarikh lahir dan darjah/tingkatan akan diisi secara automatik.</div>
                </div>

                <div class="form-group">
                    <label>Tarikh Lahir</label>
                    <input 
                        type="date" 
                        name="tarikh_lahir" 
                        id="tarikh_lahir"
                        readonly
                    >
                </div>

                <div class="form-group">
                    <label>Darjah / Tingkatan</label>
                    <input 
                        type="text" 
                        name="darjah" 
                        id="darjah"
                        placeholder="Auto ikut tahun lahir"
                    >
                    <div class="hint">Boleh diedit sekiranya terdapat kes khas.</div>
                </div>

                <div class="form-group">
                    <label>Tahun Semasa</label>
                    <input 
                        type="number" 
                        name="tahun_semasa" 
                        id="tahun_semasa"
                        value="<?= $tahunKini ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Sekolah Baharu</label>
                    <input type="text" name="sekolah_baru">
                </div>

                <div class="form-group">
                    <label>Sekolah Asal</label>
                    <input type="text" name="sekolah_asal">
                </div>

                <div class="form-group">
                    <label>Tarikh Pertukaran</label>
                    <input type="date" name="tarikh_pertukaran">
                </div>

                <div class="form-group">
                    <label>Kewarganegaraan</label>
                    <input type="text" name="kewarganegaraan" value="MALAYSIA">
                </div>

                <div class="form-group">
                    <label>Tarikh Surat</label>
                    <input type="date" name="tarikh_surat">
                </div>
            </div>

            <div class="button-row">
                <a href="senarai_murid.php" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Rekod</button>
            </div>

        </form>

    </div>

    <div class="footer-note">
        © <?= date('Y') ?> Sistem Penjanaan Surat Pertukaran Murid - PPD Besut
    </div>

</div>

<script>
function kiraTarikhDanDarjah() {
    const noKpInput = document.getElementById("no_kp");
    const tarikhInput = document.getElementById("tarikh_lahir");
    const darjahInput = document.getElementById("darjah");
    const tahunSemasaInput = document.getElementById("tahun_semasa");

    let ic = noKpInput.value.replace(/\D/g, "");
    let tahunSemasa = parseInt(tahunSemasaInput.value);

    if (ic.length < 6 || isNaN(tahunSemasa)) {
        tarikhInput.value = "";
        darjahInput.value = "";
        return;
    }

    let yy = ic.substring(0, 2);
    let mm = ic.substring(2, 4);
    let dd = ic.substring(4, 6);

    let currentYY = tahunSemasa % 100;
    let tahunLahir;

    if (parseInt(yy) <= currentYY) {
        tahunLahir = parseInt("20" + yy);
    } else {
        tahunLahir = parseInt("19" + yy);
    }

    let tarikhLahir = tahunLahir + "-" + mm + "-" + dd;
    let testDate = new Date(tarikhLahir);

    let validDate =
        testDate.getFullYear() === tahunLahir &&
        (testDate.getMonth() + 1) === parseInt(mm) &&
        testDate.getDate() === parseInt(dd);

    if (!validDate) {
        tarikhInput.value = "";
        darjahInput.value = "";
        return;
    }

    tarikhInput.value = tarikhLahir;

    let umurKohort = tahunSemasa - tahunLahir;
    let darjah = "";

    if (umurKohort === 7) {
        darjah = "TAHUN 1";
    } else if (umurKohort === 8) {
        darjah = "TAHUN 2";
    } else if (umurKohort === 9) {
        darjah = "TAHUN 3";
    } else if (umurKohort === 10) {
        darjah = "TAHUN 4";
    } else if (umurKohort === 11) {
        darjah = "TAHUN 5";
    } else if (umurKohort === 12) {
        darjah = "TAHUN 6";
    } else if (umurKohort === 13) {
        darjah = "TINGKATAN 1";
    } else if (umurKohort === 14) {
        darjah = "TINGKATAN 2";
    } else if (umurKohort === 15) {
        darjah = "TINGKATAN 3";
    } else if (umurKohort === 16) {
        darjah = "TINGKATAN 4";
    } else if (umurKohort === 17) {
        darjah = "TINGKATAN 5";
    } else if (umurKohort === 18) {
        darjah = "TINGKATAN 6";
    } else {
        darjah = "";
    }

    darjahInput.value = darjah;
}

document.getElementById("no_kp").addEventListener("input", kiraTarikhDanDarjah);
document.getElementById("tahun_semasa").addEventListener("input", kiraTarikhDanDarjah);
</script>

</body>
</html>