<?php
require "config.php";
require "helpers.php";

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM pendaftaran WHERE no_daftar = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $edit_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$data = mysqli_query($conn, "SELECT * FROM pendaftaran ORDER BY no_daftar ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pengajuan Paspor - Daftar</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Pengajuan Paspor</h1>
    <h2>Kantor Imigrasi Cabang</h2>
    <div class="programmer">Programmer: Nelsa Shelyentira_221011403374</div>

    <?php $active = 'daftar'; include "nav.php"; ?>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <fieldset>
        <legend>Input Pendaftaran</legend>
        <form action="daftar_proses.php" method="POST">
            <?php if ($edit_data): ?>
                <input type="hidden" name="no_daftar" value="<?= $edit_data['no_daftar'] ?>">
                <input type="hidden" name="mode" value="update">
            <?php else: ?>
                <input type="hidden" name="mode" value="insert">
            <?php endif; ?>

            <div class="form-row">
                <label>Nama Pemohon</label>
                <input type="text" name="nama_pemohon" required
                    value="<?= $edit_data['nama_pemohon'] ?? '' ?>">
            </div>
            <div class="form-row">
                <label>Tanggal Daftar</label>
                <input type="date" name="tgl_daftar" required
                    value="<?= $edit_data['tgl_daftar'] ?? date('Y-m-d') ?>">
            </div>

            <button type="submit"><?= $edit_data ? 'Simpan Perubahan' : 'Simpan' ?></button>
            <?php if ($edit_data): ?>
                <a href="daftar.php" class="btn" style="text-decoration:none;">Batal</a>
            <?php endif; ?>
        </form>
    </fieldset>

    <h3>Data Pendaftar</h3>
    <table>
        <thead>
        <tr>
            <th>No. Daftar</th>
            <th>Nama Pemohon</th>
            <th>Tgl Daftar</th>
            <th>Hari</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($data) === 0): ?>
            <tr><td colspan="7" style="text-align:center;">Belum ada data pendaftar</td></tr>
        <?php endif; ?>
        <?php while ($row = mysqli_fetch_assoc($data)): ?>
            <tr>
                <td><?= $row['no_daftar'] ?></td>
                <td><?= htmlspecialchars($row['nama_pemohon']) ?></td>
                <td><?= formatTanggalIndo($row['tgl_daftar']) ?></td>
                <td><?= $row['hari'] ?></td>
                <td><?= formatTanggalIndo($row['tanggal']) ?></td>
                <td><?= substr($row['jam'], 0, 5) ?></td>
                <td class="action">
                    <a class="edit" href="daftar.php?edit=<?= $row['no_daftar'] ?>">edit</a>
                    <a class="hapus" href="daftar_hapus.php?id=<?= $row['no_daftar'] ?>"
                       onclick="return confirm('Yakin hapus data ini? Data daftar ulang terkait juga akan terhapus.');">hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
