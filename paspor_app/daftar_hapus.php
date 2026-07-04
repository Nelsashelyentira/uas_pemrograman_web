<?php
require "config.php";

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = mysqli_prepare($conn, "DELETE FROM pendaftaran WHERE no_daftar = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

header("Location: daftar.php?msg=" . urlencode("Data pendaftaran berhasil dihapus."));
exit;
