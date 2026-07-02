<?php
include 'config/db.php';
include 'config/auth.php';

$sql = "INSERT INTO murid (
    urusan_id,
    pegawai_id,
    nama_penjaga,

    alamat_baris1,
    alamat_baris2,
    alamat_baris3,

    poskod,
    bandar,
    negeri,
    daerah,

    nama_murid,
    no_kp,
    tarikh_lahir,
    darjah,
    tahun_semasa,

    sekolah_baru,
    sekolah_asal,

    tarikh_pertukaran,
    kewarganegaraan,
    tarikh_surat
) VALUES (
    ?, ?, ?,
    ?, ?, ?,
    ?, ?, ?, ?,
    ?, ?, ?, ?, ?,
    ?, ?,
    ?, ?, ?
)";

$stmt = $conn->prepare($sql);

$stmt->execute([
    $_POST['urusan_id'],
    $_POST['pegawai_id'],
    $_POST['nama_penjaga'],

    $_POST['alamat_baris1'],
    $_POST['alamat_baris2'],
    '',

    '',
    '',
    'TERENGGANU',
    '',

    $_POST['nama_murid'],
    $_POST['no_kp'],
    $_POST['tarikh_lahir'],
    $_POST['darjah'],
    $_POST['tahun_semasa'],

    $_POST['sekolah_baru'],
    $_POST['sekolah_asal'],

    $_POST['tarikh_pertukaran'],
    $_POST['kewarganegaraan'],
    $_POST['tarikh_surat']
]);

header("Location: senarai_murid.php");
exit;
?>