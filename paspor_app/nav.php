<?php
$active = $active ?? '';
?>
<nav class="menu">
    <a href="daftar.php" class="<?= $active === 'daftar' ? 'active' : '' ?>">Daftar</a>
    <a href="daftar_ulang.php" class="<?= $active === 'daftar_ulang' ? 'active' : '' ?>">Daftar Ulang</a>
    <a href="pengurusan.php" class="<?= $active === 'pengurusan' ? 'active' : '' ?>">Pengurusan</a>
</nav>
