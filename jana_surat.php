<?php
include 'config/db.php';
require __DIR__ . '/vendor/autoload.php';
include 'config/db.php';
include 'config/auth.php';

$id = $_GET['id'];

$sql = "SELECT 
            murid.*, 
            urusan.kod_urusan,
            urusan.no_rujukan,
            urusan.tajuk_surat,
            pegawai.nama_pegawai,
            pegawai.jawatan,
            pegawai.unit_sektor
        FROM murid 
        JOIN urusan ON murid.urusan_id = urusan.id
        LEFT JOIN pegawai ON murid.pegawai_id = pegawai.id
        WHERE murid.id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<style>
@page {
    size: A4;
    margin: 8mm 12mm 8mm 12mm;
}

body {
    font-family: Arial, sans-serif;
    font-size: 11.5px;
    background: #eee;
    margin: 0;
}

.surat {
    width: 185mm;
    min-height: 270mm;
    margin: auto;
    padding: 8mm;
    background: white;
    box-sizing: border-box;
}

.header-surat {
    display: flex;
    align-items: flex-start;
    border-bottom: 1px solid #000;
    padding-bottom: 6px;
}

.logo {
    width: 115px;
    margin-right: 18px;
}

.alamat-ppd {
    width: 390px;
    line-height: 1.15;
    font-size: 14px;
}

.alamat-ppd h3 {
    margin: 0;
    font-size: 15px;
}

.kontak-ppd {
    margin-left: auto;
    padding-top: 42px;
    font-size: 10.5px;
    line-height: 1.4;
}

.info-surat {
    width: 340px;
    margin-left: 420px;
    margin-top: 15px;
    font-size: 14px;
}

.info-row {
    display: grid;
    grid-template-columns: 80px 18px 1fr;
}

.alamat-penerima {
    margin-top: 25px;
    margin-left: 5px;
    font-size: 14px;
    line-height: 1.35;
}

p {
    margin: 8px 0;
    line-height: 1.35;
}

.title {
    font-weight: bold;
    margin-top: 25px;
    margin-bottom: 25px;
    font-size: 15px;
}

.butiran-murid {
    width: 92%;
    margin-left: 5px;
    font-size: 14px;
    border-collapse: collapse;
}

.butiran-murid td {
    padding: 8px 12px;
    vertical-align: top;
}

.butiran-murid .label {
    width: 160px;
}

.butiran-murid .value {
    width: 260px;
}

.butiran-murid .kanan {
    padding-left: 60px;
}

.ruang-sign {
    height: 115px;
    margin-left: 25px;
}

.print-btn {
    margin-top: 20px;
}

@media print {
    body {
        background: white;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .surat {
        width: 100%;
        min-height: auto;
        margin: 0;
        padding: 0;
        box-shadow: none;
    }

    .print-btn {
        display: none;
    }
}
</style>

<div class="surat">

    <div class="header-surat">
        <img src="uploads/Logo-Korporat-KPM-BI-Tulisan-Putih.png" class="logo">

        <div class="alamat-ppd">
            <h3>KEMENTERIAN PENDIDIKAN</h3>
            Pejabat Pendidikan Daerah Besut<br>
            Tingkat 3, Bangunan Persekutuan Besut,<br>
            22200 Kampung Raja, Besut,<br>
            Terengganu Darul Iman.
        </div>

        <div class="kontak-ppd">
            Tel Pejabat Am&nbsp;&nbsp;: 09-695 6200<br>
            Faks&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 09-695 6100<br>
            E-mel&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ppd.besut@moe.gov.my
        </div>
    </div>

    <div class="info-surat">
        <div class="info-row">
            <div>Ruj. Kami</div>
            <div>:</div>
            <div><?= $data['no_rujukan'] ?> &nbsp; ( &nbsp;&nbsp;&nbsp; )</div>
        </div>

        <div class="info-row">
            <div>Tarikh</div>
            <div>:</div>
            <div><?= date("d/m/Y", strtotime($data['tarikh_surat'])) ?></div>
        </div>
    </div>

   <div class="alamat-penerima">
    <?= strtoupper($data['nama_penjaga']) ?><br>
    <?= strtoupper($data['alamat_baris1']) ?><br>
    <?= strtoupper($data['alamat_baris2']) ?><br>
    TERENGGANU

   <p style="
    margin-top:20px;
    margin-left:25px;
    font-size:13px;
">
Tuan,
</p>

</div>

    <p class="title"><?= strtoupper($data['tajuk_surat']) ?></p>

    <table class="butiran-murid">
        <tr>
            <td class="label">Nama Murid</td>
            <td class="value" colspan="3"><?= strtoupper($data['nama_murid']) ?></td>
        </tr>

        <tr>
            <td class="label">Sijil Lahir / KP</td>
            <td class="value"><?= $data['no_kp'] ?></td>
            <td class="label kanan">Tarikh Lahir</td>
            <td class="value"><?= date("d/m/Y", strtotime($data['tarikh_lahir'])) ?></td>
        </tr>

        <tr>
            <td class="label">Darjah/Tingkatan</td>
            <td class="value"><?= strtoupper($data['darjah']) ?></td>
            <td class="label kanan">Tahun Semasa</td>
            <td class="value"><?= $data['tahun_semasa'] ?></td>
        </tr>

        <tr>
            <td class="label">Sekolah Baharu</td>
            <td class="value" colspan="3"><?= strtoupper($data['sekolah_baru']) ?></td>
        </tr>

        <tr>
            <td class="label">Pertukaran</td>
            <td class="value">LULUS</td>
            <td class="label kanan">Mulai</td>
            <td class="value"><?= date("d/m/Y", strtotime($data['tarikh_pertukaran'])) ?></td>
        </tr>

        <tr>
            <td class="label">Kewarganegaraan</td>
            <td class="value" colspan="3"><?= strtoupper($data['kewarganegaraan']) ?></td>
        </tr>
    </table>

    <p>Dengan segala hormatnya perkara di atas dirujuk.</p>

    <table style="width:100%; margin-top:15px;">
        <tr>
            <td style="width:30px; vertical-align:top;">2.</td>
            <td style="text-align:justify; line-height:1.7;">
                Selanjutnya, sila bawa bersama surat beranak atau kad pengenalan murid berkenaan
                semasa melapor diri untuk urusan penempatan di sekolah yang baharu.
            </td>
        </tr>
    </table>

    <p>Sekian, terima kasih.</p>

    <p>
        <b>
            “MEMACU PRAKARSA PENDIDIKAN”<br>
            “MALAYSIA MADANI”<br>
            “BERKHIDMAT UNTUK NEGARA”
        </b>
    </p>

    <p>Saya yang menjalankan amanah,</p>

    <div class="ruang-sign"></div>

    <p>
        <b><?= strtoupper($data['nama_pegawai']) ?></b><br>
        <b><?= $data['jawatan'] ?></b><br>
        <b><?= $data['unit_sektor'] ?></b><br>
        b.p. Pegawai Pendidikan Daerah Besut,<br>
        Terengganu.
    </p>

    <table style="margin-top:20px; font-size:12px;">
        <tr>
            <td style="width:35px;">s.k.</td>
            <td>1. Pengarah Pendidikan Negeri Terengganu</td>
        </tr>

        <tr>
            <td></td>
            <td>
                2. GURU BESAR, <?= strtoupper($data['sekolah_baru']) ?><br>

                <div style="margin-left:20px; margin-top:5px;">
                    Sila tuan semak Sijil Kelahiran murid ini untuk mengesahkan
                    tahun yang betul mengikut kohort umurnya dan mengemaskini
                    maklumat murid dalam Sistem Pengurusan IDentiti (idMe)
                </div>
            </td>
        </tr>

        <tr>
            <td></td>
            <td style="padding-top:8px;">
                3. Sekolah Asal : <?= strtoupper($data['sekolah_asal']) ?>
            </td>
        </tr>
    </table>

    <div style="margin-top:20px; font-size:12px; font-weight:bold;">
        <?= strtoupper($data['kod_urusan']) ?>
    </div>

    <button class="print-btn" onclick="window.print()">Print Surat</button>

</div>