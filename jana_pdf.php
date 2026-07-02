<?php
require __DIR__ . '/vendor/autoload.php';
include 'config/db.php';
include 'config/auth.php';

use Dompdf\Dompdf;
use Dompdf\Options;

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

if (!$data) {
    die("Rekod murid tidak dijumpai.");
}

/* =========================
   NAMA FAIL PDF AUTOMATIK
   ========================= */

$namaMurid = strtoupper(trim($data['nama_murid']));
$namaFailMurid = preg_replace('/[^A-Z0-9]+/', '_', $namaMurid);
$namaFailMurid = trim($namaFailMurid, '_');

$kodUrusan = strtoupper($data['kod_urusan']);

$namaFail = $kodUrusan . "_" . $namaFailMurid . ".pdf";

$pdfTitle = htmlspecialchars($namaMurid, ENT_QUOTES, 'UTF-8');

/* =========================
   DOMPDF SETTING
   ========================= */

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

/* =========================
   LOGO
   ========================= */

$logoPath = __DIR__ . '/uploads/Logo-Korporat-KPM-BI-Tulisan-Putih.png';

if (!file_exists($logoPath)) {
    die("Logo tidak dijumpai: " . $logoPath);
}

$logoData = base64_encode(file_get_contents($logoPath));
$logo = 'data:image/png;base64,' . $logoData;

/* =========================
   HTML PDF
   ========================= */

$html = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>'.$pdfTitle.'</title>

<style>
@page {
    margin: 9mm 13mm 7mm 13mm;
}

body {
    font-family: Arial, sans-serif;
    font-size: 12.5px;
    margin: 0;
    color: #000;
}

.header-table {
    width: 100%;
    border-bottom: 1px solid #000;
    padding-bottom: 5px;
    border-collapse: collapse;
}

.logo {
    width: 100px;
}

.alamat-ppd {
    font-size: 12.5px;
    line-height: 1.18;
}

.alamat-ppd strong {
    font-size: 14.5px;
}

.kontak {
    font-size: 9.5px;
    line-height: 1.45;
}

.info-surat {
    margin-left: 365px;
    margin-top: 15px;
    font-size: 12.5px;
    line-height: 1.8;
    border-collapse: collapse;
}

.info-surat td {
    padding: 2px 4px;
}

.alamat-penerima {
    margin-top: 18px;
    margin-left: 15px;
    font-size: 12.8px;
    line-height: 1.35;
}

p {
    margin: 8px 0;
    line-height: 1.35;
}

.title {
    font-weight: bold;
    margin-top: 18px;
    margin-bottom: 25px;
    margin-left: 25px;
    font-size: 12.8px;
}

.butiran-murid {
    width: 95%;
    font-size: 12.8px;
    border-collapse: collapse;
    margin-left: 15px;
}

.butiran-murid td {
    padding: 5px 8px;
    vertical-align: top;
}

.label {
    width: 145px;
}

.value {
    width: 245px;
}

.kanan {
    width: 135px;
    padding-left: 35px;
}

.perenggan2 {
    width: 100%;
    margin-top: 10px;
    font-size: 12.5px;
    border-collapse: collapse;
}

.perenggan2 td {
    vertical-align: top;
    line-height: 1.5;
}

.slogan {
    font-weight: bold;
    line-height: 1.45;
    margin-top: 15px;
    margin-left: 25px;
}

.ruang-sign {
    height: 90px;
}

.blok-tandatangan {
    margin-left: 25px;
    line-height: 1.25;
}

.sk-table {
    margin-top: 15px;
    font-size: 10.6px;
    width: 100%;
    border-collapse: collapse;
}

.sk-table td {
    vertical-align: top;
    padding: 1px 2px;
}

.remark {
    margin-top: 12px;
    margin-left: 15px;
    font-size: 10.8px;
    font-weight: bold;
}
</style>
</head>

<body>

<table class="header-table">
<tr>
    <td style="width:115px; vertical-align:top;">
        <img src="'.$logo.'" class="logo">
    </td>

    <td class="alamat-ppd" style="vertical-align:top; width:360px;">
        <strong>KEMENTERIAN PENDIDIKAN</strong><br>
        Pejabat Pendidikan Daerah Besut<br>
        Tingkat 3, Bangunan Persekutuan Besut,<br>
        22200 Kampung Raja, Besut,<br>
        Terengganu Darul Iman.
    </td>

    <td class="kontak" style="width:210px; padding-top:37px; vertical-align:top;">
        Tel Pejabat Am&nbsp;&nbsp;: 09-695 6200<br>
        Faks&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 09-695 6100<br>
        E-mel&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ppd.besut@moe.gov.my
    </td>
</tr>
</table>

<table class="info-surat">
<tr>
    <td style="width:78px;">Ruj. Kami</td>
    <td style="width:12px;">:</td>
    <td>'.$data['no_rujukan'].' &nbsp; ( &nbsp;&nbsp;&nbsp; )</td>
</tr>
<tr>
    <td>Tarikh</td>
    <td>:</td>
    <td>'.date("d/m/Y", strtotime($data['tarikh_surat'])).'</td>
</tr>
</table>

<div class="alamat-penerima">
    '.strtoupper($data['nama_penjaga']).'<br>
    '.strtoupper($data['alamat_baris1']).'<br>
    '.strtoupper($data['alamat_baris2']).'<br>
    TERENGGANU
</div>

<p style="
    margin-top:20px;
    margin-left:18px;
    font-size:13px;
">
Tuan,
</p>

<p class="title">'.strtoupper($data['tajuk_surat']).'</p>

<table class="butiran-murid">
<tr>
    <td class="label">Nama Murid</td>
    <td class="value" colspan="3">'.strtoupper($data['nama_murid']).'</td>
</tr>

<tr>
    <td class="label">Sijil Lahir / KP</td>
    <td class="value">'.$data['no_kp'].'</td>
    <td class="label kanan">Tarikh Lahir</td>
    <td class="value">'.date("d/m/Y", strtotime($data['tarikh_lahir'])).'</td>
</tr>

<tr>
    <td class="label">Darjah/Tingkatan</td>
    <td class="value">'.strtoupper($data['darjah']).'</td>
    <td class="label kanan">Tahun Semasa</td>
    <td class="value">'.$data['tahun_semasa'].'</td>
</tr>

<tr>
    <td class="label">Sekolah Baharu</td>
    <td class="value" colspan="3">'.strtoupper($data['sekolah_baru']).'</td>
</tr>

<tr>
    <td class="label">Pertukaran</td>
    <td class="value">LULUS</td>
    <td class="label kanan">Mulai</td>
    <td class="value">'.date("d/m/Y", strtotime($data['tarikh_pertukaran'])).'</td>
</tr>

<tr>
    <td class="label">Kewarganegaraan</td>
    <td class="value" colspan="3">'.strtoupper($data['kewarganegaraan']).'</td>
</tr>
</table>

<p style="
    margin-top:20px;
    margin-left:25px;
    line-height:1.5;
">
Dengan segala hormatnya perkara di atas dirujuk.
</p>

<table class="perenggan2">
<tr>
    <td style="width:28px;">2.</td>
    <td style="text-align:justify;">
        Selanjutnya, sila bawa bersama surat beranak atau kad pengenalan murid berkenaan
        semasa melapor diri untuk urusan penempatan di sekolah yang baharu.
    </td>
</tr>
</table>

<p style="
    margin-top:15px;
    margin-left:25px;
">
Sekian, terima kasih.
</p>

<p class="slogan">
“MEMACU PRAKARSA PENDIDIKAN”<br>
“MALAYSIA MADANI”<br>
“BERKHIDMAT UNTUK NEGARA”
</p>

<p style="
    margin-top:15px;
    margin-left:25px;
">
Saya yang menjalankan amanah,
</p>

<div class="ruang-sign"></div>

<div class="blok-tandatangan">
<b>'.strtoupper($data['nama_pegawai']).'</b><br>
<b>'.$data['jawatan'].'</b><br>
<b>'.$data['unit_sektor'].'</b><br>
b.p. Pegawai Pendidikan Daerah Besut,<br>
Terengganu.
</div>

<table class="sk-table">
<tr>
    <td style="width:35px;">s.k.:</td>
    <td>1. Pengarah Pendidikan Negeri Terengganu</td>
</tr>

<tr>
    <td></td>
    <td>
        2. GURU BESAR, '.strtoupper($data['sekolah_baru']).'<br>
        <div style="margin-left:20px; margin-top:3px;">
            Sila tuan semak Sijil Kelahiran murid ini untuk mengesahkan tahun yang betul
            mengikut kohort umurnya dan mengemaskini maklumat murid dalam Sistem Pengurusan IDentiti (idMe)
        </div>
    </td>
</tr>

<tr>
    <td></td>
    <td style="padding-top:4px;">
        3. Sekolah Asal : '.strtoupper($data['sekolah_asal']).'
    </td>
</tr>
</table>

<div class="remark">'.strtoupper($data['kod_urusan']).'</div>

</body>
</html>
';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream($namaFail, [
    "Attachment" => false
]);
?>