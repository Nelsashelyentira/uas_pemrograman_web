<?php
require "config.php";
require "helpers.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: daftar_ulang.php");
    exit;
}

$no_daftar         = (int)$_POST['no_daftar'];
$nama_pemohon      = $_POST['nama_pemohon'];
$hari_harus_datang = $_POST['hari_harus_datang'];
$tgl_harus_datang  = $_POST['tgl_harus_datang'];
$tgl_datang        = $_POST['tgl_datang'];
$keperluan         = $_POST['keperluan'] ?? '';
$hari_datang       = namaHariIndo($tgl_datang);

$ktp         = isset($_POST['ktp']) ? 'ada' : 'tidak';
$kk          = isset($_POST['kk']) ? 'ada' : 'tidak';
$ijazah_akte = isset($_POST['ijazah_akte']) ? 'ada' : 'tidak';

if ($tgl_datang === '') {
    header("Location: daftar_ulang.php?no_daftar=$no_daftar&msg=" . urlencode("Tanggal datang wajib diisi!"));
    exit;
}


$keterangan = ($hari_datang === $hari_harus_datang && $tgl_datang === $tgl_harus_datang) ? 'OK' : 'Tidak';

$no_antrian = null;
if ($keterangan === 'OK') {
    $res = mysqli_query($conn, "SELECT MAX(no_antrian) AS mx FROM daftar_ulang");
    $mx = mysqli_fetch_assoc($res)['mx'];
    $no_antrian = $mx ? $mx + 1 : 1;
}

$stmt = mysqli_prepare($conn, "INSERT INTO daftar_ulang
    (no_daftar, nama_pemohon, hari_harus_datang, tgl_harus_datang, hari_datang, tgl_datang, ktp, kk, ijazah_akte, keperluan, keterangan, no_antrian)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param(
    $stmt, "issssssssssi",
    $no_daftar, $nama_pemohon, $hari_harus_datang, $tgl_harus_datang,
    $hari_datang, $tgl_datang, $ktp, $kk, $ijazah_akte, $keperluan, $keterangan, $no_antrian
);
mysqli_stmt_execute($stmt);

$msg = $keterangan === 'OK'
    ? "Daftar ulang berhasil! Sesuai jadwal, No. Antrian: $no_antrian"
    : "Daftar ulang disimpan, tetapi TIDAK sesuai jadwal yang ditentukan (keterangan: Tidak).";

header("Location: daftar_ulang.php?msg=" . urlencode($msg));
exit;
