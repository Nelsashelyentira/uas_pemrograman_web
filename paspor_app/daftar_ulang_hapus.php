<?php
require "config.php";

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = mysqli_prepare($conn, "SELECT no_antrian FROM daftar_ulang WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($row && $row['no_antrian']) {
        $stmt2 = mysqli_prepare($conn, "DELETE FROM pengurusan WHERE no_antrian = ?");
        mysqli_stmt_bind_param($stmt2, "i", $row['no_antrian']);
        mysqli_stmt_execute($stmt2);
    }

    $stmt3 = mysqli_prepare($conn, "DELETE FROM daftar_ulang WHERE id = ?");
    mysqli_stmt_bind_param($stmt3, "i", $id);
    mysqli_stmt_execute($stmt3);
}

header("Location: daftar_ulang.php?msg=" . urlencode("Data daftar ulang berhasil dihapus."));
exit;
