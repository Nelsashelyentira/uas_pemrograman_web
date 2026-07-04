<?php
function namaHariIndo($tanggal) {
    $hari_en = date("l", strtotime($tanggal));
    $map = [
        "Monday"    => "Senin",
        "Tuesday"   => "Selasa",
        "Wednesday" => "Rabu",
        "Thursday"  => "Kamis",
        "Friday"    => "Jumat",
        "Saturday"  => "Sabtu",
        "Sunday"    => "Minggu",
    ];
    return $map[$hari_en];
}

function formatTanggalIndo($tanggal) {
    if (!$tanggal) return "-";
    $bulan = [
        1=>"Januari",2=>"Februari",3=>"Maret",4=>"April",5=>"Mei",6=>"Juni",
        7=>"Juli",8=>"Agustus",9=>"September",10=>"Oktober",11=>"November",12=>"Desember"
    ];
    $ts = strtotime($tanggal);
    return date("d", $ts) . " " . $bulan[(int)date("n", $ts)] . " " . date("Y", $ts);
}

/**
 * @param mysqli 
 * @param string 
 * @return array 
 */
function tentukanJadwal($conn, $tgl_daftar) {
    $slot_jam = ["08:00:00", "09:00:00", "10:00:00", "11:00:00", "13:00:00"];
    $kapasitas = count($slot_jam); // 5 orang / hari

    $tanggal_cek = $tgl_daftar;

    while (true) {
        $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS jumlah FROM pendaftaran WHERE tanggal = ?");
        mysqli_stmt_bind_param($stmt, "s", $tanggal_cek);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($res);
        $jumlah = (int)$row["jumlah"];

        if ($jumlah < $kapasitas) {
            $jam = $slot_jam[$jumlah]; 
            $hari = namaHariIndo($tanggal_cek);
            return [$hari, $tanggal_cek, $jam];
        }

        $tanggal_cek = date("Y-m-d", strtotime($tanggal_cek . " +1 day"));
    }
}

function sinkronPengurusan($conn) {
    $q = mysqli_query($conn, "SELECT * FROM daftar_ulang WHERE keterangan = 'OK' AND no_antrian IS NOT NULL");
    while ($row = mysqli_fetch_assoc($q)) {
        $lengkap = ($row["ktp"] == "ada" && $row["kk"] == "ada" && $row["ijazah_akte"] == "ada");
        $berkas = $lengkap ? "Lengkap" : "Belum Lengkap";
        $status = $lengkap ? "Diterima" : "Pelanggan";
        $ket = $lengkap ? "OK" : "";
        $bayar = $lengkap ? 355000 : 0;

        $stmt = mysqli_prepare($conn, "INSERT INTO pengurusan
            (no_antrian, no_daftar, nama_pemohon, berkas, status, keterangan, pembayaran)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            berkas = VALUES(berkas), status = VALUES(status),
            keterangan = VALUES(keterangan), pembayaran = VALUES(pembayaran)");
        mysqli_stmt_bind_param(
            $stmt, "iissssi",
            $row["no_antrian"], $row["no_daftar"], $row["nama_pemohon"],
            $berkas, $status, $ket, $bayar
        );
        mysqli_stmt_execute($stmt);
    }
}
