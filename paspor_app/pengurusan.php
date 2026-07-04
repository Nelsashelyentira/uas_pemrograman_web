<?php
require "config.php";
require "helpers.php";

sinkronPengurusan($conn);

$data = mysqli_query($conn, "SELECT * FROM pengurusan ORDER BY no_antrian ASC");

$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(pembayaran),0) AS total FROM pengurusan"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pengajuan Paspor - Pengurusan</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Pengajuan Paspor</h1>
    <h2>Kantor Imigrasi Cabang</h2>
    <div class="programmer">Programmer: Nelsa Shelyentira_221011403374</div>

    <?php $active = 'pengurusan'; include "nav.php"; ?>

    <h3>Data Pengurusan Paspor</h3>
    <table>
        <thead>
        <tr>
            <th>No. Antrian</th>
            <th>No. Daftar</th>
            <th>Nama Pemohon</th>
            <th>Berkas</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Pembayaran</th>
        </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($data) === 0): ?>
            <tr><td colspan="7" style="text-align:center;">Belum ada data pengurusan</td></tr>
        <?php endif; ?>
        <?php while ($row = mysqli_fetch_assoc($data)): ?>
            <tr>
                <td><?= $row['no_antrian'] ?></td>
                <td><?= $row['no_daftar'] ?></td>
                <td><?= htmlspecialchars($row['nama_pemohon']) ?></td>
                <td><span class="badge <?= strtolower(str_replace(' ', '', $row['berkas'])) ?>"><?= $row['berkas'] ?></span></td>
                <td><span class="badge <?= strtolower($row['status']) ?>"><?= $row['status'] ?></span></td>
                <td><?= $row['keterangan'] !== '' ? '<span class="badge ' . strtolower($row['keterangan']) . '">' . $row['keterangan'] . '</span>' : '-' ?></td>
                <td>Rp <?= number_format($row['pembayaran'], 0, ',', '.') ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <div class="pendapatan-box">
        Pendapatan: Rp <?= number_format($total, 0, ',', '.') ?>
    </div>
</div>
</body>
</html>
