<?php
require "config.php";
require "helpers.php";

$list_pendaftar = mysqli_query($conn, "SELECT no_daftar, nama_pemohon, hari, tanggal FROM pendaftaran ORDER BY no_daftar ASC");

$selected = null;
if (isset($_GET['no_daftar']) && $_GET['no_daftar'] !== '') {
    $nd = (int)$_GET['no_daftar'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM pendaftaran WHERE no_daftar = ?");
    mysqli_stmt_bind_param($stmt, "i", $nd);
    mysqli_stmt_execute($stmt);
    $selected = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$data = mysqli_query($conn, "SELECT * FROM daftar_ulang ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pengajuan Paspor - Daftar Ulang</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Pengajuan Paspor</h1>
    <h2>Kantor Imigrasi Cabang</h2>
    <div class="programmer">Programmer: Nelsa Shelyentira_221011403374</div>

    <?php $active = 'daftar_ulang'; include "nav.php"; ?>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <fieldset>
        <legend>Input Daftar Ulang</legend>

        <form method="GET" action="daftar_ulang.php">
            <div class="form-row">
                <label>No. Daftar</label>
                <select name="no_daftar" onchange="this.form.submit()" required>
                    <option value="">-- pilih no. daftar --</option>
                    <?php mysqli_data_seek($list_pendaftar, 0); while ($p = mysqli_fetch_assoc($list_pendaftar)): ?>
                        <option value="<?= $p['no_daftar'] ?>" <?= ($selected && $selected['no_daftar'] == $p['no_daftar']) ? 'selected' : '' ?>>
                            <?= $p['no_daftar'] ?> - <?= htmlspecialchars($p['nama_pemohon']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </form>

        <?php if ($selected): ?>
        <form action="daftar_ulang_proses.php" method="POST" style="margin-top:15px;">
            <input type="hidden" name="no_daftar" value="<?= $selected['no_daftar'] ?>">
            <input type="hidden" name="nama_pemohon" value="<?= htmlspecialchars($selected['nama_pemohon']) ?>">
            <input type="hidden" name="hari_harus_datang" value="<?= $selected['hari'] ?>">
            <input type="hidden" name="tgl_harus_datang" value="<?= $selected['tanggal'] ?>">

            <div class="form-row">
                <label>Nama Pemohon</label>
                <div class="readonly-box"><?= htmlspecialchars($selected['nama_pemohon']) ?></div>
            </div>
            <div class="form-row">
                <label>Hari Harus Datang</label>
                <div class="readonly-box"><?= $selected['hari'] ?></div>
            </div>
            <div class="form-row">
                <label>Tgl Harus Datang</label>
                <div class="readonly-box"><?= formatTanggalIndo($selected['tanggal']) ?></div>
            </div>
            <div class="form-row">
                <label>Tgl Datang (aktual)</label>
                <input type="date" name="tgl_datang" required>
            </div>
            <div class="form-row checks">
                <label>Berkas</label>
                <label><input type="checkbox" name="ktp" value="ada"> KTP</label>
                <label><input type="checkbox" name="kk" value="ada"> KK</label>
                <label><input type="checkbox" name="ijazah_akte" value="ada"> Ijazah/Akte</label>
            </div>
            <div class="form-row">
                <label>Keperluan</label>
                <select name="keperluan" required>
                    <option value="">-- pilih keperluan --</option>
                    <option value="buat paspor">buat paspor</option>
                    <option value="perpanjang paspor">perpanjang paspor</option>
                </select>
            </div>

            <button type="submit">Simpan</button>
        </form>
        <?php endif; ?>
    </fieldset>

    <h3>Data Pendaftar Ulang</h3>
    <table>
        <thead>
        <tr>
            <th>No. Daftar</th>
            <th>Nama Pemohon</th>
            <th>Keperluan</th>
            <th>KTP</th>
            <th>KK</th>
            <th>Ijazah/Akte</th>
            <th>Keterangan</th>
            <th>No. Antrian</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($data) === 0): ?>
            <tr><td colspan="9" style="text-align:center;">Belum ada data daftar ulang</td></tr>
        <?php endif; ?>
        <?php while ($row = mysqli_fetch_assoc($data)): ?>
            <tr>
                <td><?= $row['no_daftar'] ?></td>
                <td><?= htmlspecialchars($row['nama_pemohon']) ?></td>
                <td><?= $row['keperluan'] ?></td>
                <td><?= $row['ktp'] === 'ada' ? 'Ada' : 'Tidak' ?></td>
                <td><?= $row['kk'] === 'ada' ? 'Ada' : 'Tidak' ?></td>
                <td><?= $row['ijazah_akte'] === 'ada' ? 'Ada' : 'Tidak' ?></td>
                <td><span class="badge <?= strtolower($row['keterangan']) ?>"><?= $row['keterangan'] ?></span></td>
                <td><?= $row['no_antrian'] ?? '-' ?></td>
                <td class="action">
                    <a class="hapus" href="daftar_ulang_hapus.php?id=<?= $row['id'] ?>"
                       onclick="return confirm('Yakin hapus data ini?');">hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
