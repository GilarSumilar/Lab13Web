<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php"); // Kembalikan session ke login jika belum login.
    exit;
}

require 'functions.php';

// Pagination
// Konfigurasi
$jumlahDataPerHalaman = 2;
$jumlahData = count(query("SELECT * FROM daftarbuku"));
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = (isset($_GET["page"])) ? $_GET["page"] : 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;


// Ambil data dari table daftarbuku
$daftarbuku = query("SELECT * FROM daftarbuku LIMIT $awalData, $jumlahDataPerHalaman");

// Tombol cari di tekan
if (isset($_POST["cari"])) {
    $daftarbuku = cari($_POST["keyword"]);
}

// Ambil data daftarbuku dari object result

// mysqli_fecth_row() mengembalikan array numerik
// mysqli_fecth_assoc() mengembalikan array assosiatif
// mysqli_fect_array() gabungan 2 yg di atas
// mysqli_fect_object()

// $bku = mysqli_fetch_assoc($result);
// var_dump($bku);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin</title>
    <style>
        .navigasi a {
            text-decoration: none;
            color: brown;

        }
    </style>
</head>

<body>
    <h1>Daftar Mahasiswa</h1>
    <a href="tambah.php">Tambah Buku</a>
    <br><br>
    <form action="" method="post">
        <input type="text" name="keyword" size="30" autofocus placeholder="Enter serarch keyword..">
        <button type="submit" name="cari">Cari!</button>
    </form>
    <br>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>NO Urut</th>
            <th>Aksi</th>
            <th>Cover</th>
            <th>Nama Buku</th>
            <th>Penulis</th>
            <th>Tahun Terbit</th>
        </tr>
        <?php $i = $awalData + 1; ?>
        <?php foreach ($daftarbuku as $row) : ?>
            <tr>
                <td><?= $i; ?></td>
                <td>
                    <a href="ubah.php?id=<?= $row["no_bku"]; ?>">ubah</a> |
                    <a href="hapus.php?id=<?= $row["no_bku"]; ?>" onclick="return confirm('Anda yankin ingin menghapus ?');">hapus</a>
                </td>
                <td><img src="img/<?= $row["cover"]; ?>" width="50"></td>
                <td><?= $row["buku"]; ?></td>
                <td><?= $row["penulis"]; ?></td>
                <td><?= $row["tahun_terbit"]; ?></td>
            </tr>
            <?php $i++; ?>
        <?php endforeach; ?>

    </table>
    <!-- Navigasi -->
    <div class="navigasi">
        <?php if ($halamanAktif > 1) : ?>
            <a href="?page=<?= $halamanAktif - 1; ?>">&laquo;</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
            <?php if ($i == $halamanAktif) : ?>
                <a href="?page=<?= $i; ?>" style="font-weight: bold; background-color: blue;"><?= $i; ?></a>
            <?php else : ?>
                <a href="?page=<?= $i; ?>"><?= $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($halamanAktif < $jumlahHalaman) : ?>
            <a href="?page=<?= $halamanAktif + 1; ?>">&raquo;</a>
        <?php endif; ?>
    </div>
    <br><br>
    <a href="logout.php">logout</a>
</body>

</html>