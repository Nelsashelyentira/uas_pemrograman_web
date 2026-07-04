<?php
require "config.php";
require "helpers.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: daftar.php");
    exit;
}

$nama_pemohon = trim($_POST['nama_pemohon']);
$tgl_daftar   = $_POST['tgl_daftar'];
$mode         = $_POST['mode'];

if ($nama_pemohon === '' || $tgl_daftar === '') {
    header("Location: daftar.php?msg=" . urlencode("Nama pemohon dan tanggal daftar wajib diisi!"));
    exit;
}

// Hitung jadwal otomatis (hari, tanggal, jam) berdasarkan kapasitas 5 orang/hari
[$hari, $tanggal, $jam] = tentukanJadwal($conn, $tgl_daftar);

if ($mode === 'update') {
    $no_daftar = (int)$_POST['no_daftar'];
    $stmt = mysqli_prepare($conn, "UPDATE pendaftaran SET nama_pemohon=?, tgl_daftar=?, hari=?, tanggal=?, jam=? WHERE no_daftar=?");
    mysqli_stmt_bind_param($stmt, "sssssi", $nama_pemohon, $tgl_daftar, $hari, $tanggal, $jam, $no_daftar);
    mysqli_stmt_execute($stmt);
    $msg = "Data pendaftaran berhasil diperbarui.";
} else {
    $stmt = mysqli_prepare($conn, "INSERT INTO pendaftaran (nama_pemohon, tgl_daftar, hari, tanggal, jam) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssss", $nama_pemohon, $tgl_daftar, $hari, $tanggal, $jam);
    mysqli_stmt_execute($stmt);
    $msg = "Pendaftaran berhasil disimpan. Harus datang: $hari, " . formatTanggalIndo($tanggal) . " jam " . substr($jam,0,5) . ".";
}

header("Location: daftar.php?msg=" . urlencode($msg));
exit;
