<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../login.php"); // Kembalikan session ke login jika belum login.
    exit;
}

require '../../class/functions.php';

// Pagination
$per_page = 3;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $per_page;

// Tombol cari ditekan
$q = "";
$sql_where = cari(isset($_GET['submit']) ? $_GET['q'] : '');
$title = 'Data Barang';

// Query data buku
$sql = "SELECT * FROM daftarbuku $sql_where LIMIT $offset, $per_page";
$result = mysqli_query($conn, $sql);

$daftarbuku = array(); // Inisialisasi array

while ($row = mysqli_fetch_assoc($result)) {
    $daftarbuku[] = $row;
}

// Query total data untuk pagination
$sql_count = "SELECT COUNT(*) FROM daftarbuku $sql_where";
$result_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_row($result_count);
$total_rows = $row_count[0];
$num_page = ceil($total_rows / $per_page);

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

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
        }

        .pagination li {
            margin-right: 5px;
        }

        .pagination a {
            display: block;
            padding: 5px 10px;
            background-color: #eee;
            text-decoration: none;
            color: #333;
        }

        .pagination a:hover {
            background-color: #ddd;
        }

        .pagination .active {
            background-color: #007bff;
            color: #fff;
        }
    </style>

</head>

<body>
    <h1>Daftar Buku</h1>
    <a href="tambah.php">Tambah Buku</a>
    <br><br>
    <form action="" method="get">
        <label for="q">Cari Buku:</label>
        <input type="text" id="q" name="q" class="input-q" value="<?php echo $q ?>">
        <input type="submit" name="submit" value="cari" class="btn-btn primary">
    </form>

    <?php if ($result) : ?>
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
            <?php $i = $offset + 1; ?>
            <?php foreach ($daftarbuku as $row) : ?>
                <tr>
                    <td><?= $i; ?></td>
                    <td>
                        <a href="ubah.php?id=<?= $row["no_bku"]; ?>">ubah</a> |
                        <a href="hapus.php?id=<?= $row["no_bku"]; ?>" onclick="return confirm('Anda yakin ingin menghapus?');">hapus</a>
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
        <ul class="pagination">
            <li><a href="?page=<?= ($page > 1) ? ($page - 1) : 1; ?>">&laquo;</a></li>
            <?php for ($i = 1; $i <= $num_page; $i++) : ?>
                <li>
                    <a class="<?= ($page == $i) ? 'active' : ''; ?>" href="?page=<?= $i; ?>">
                        <?= $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
            <li><a href="?page=<?= ($page < $num_page) ? ($page + 1) : $num_page; ?>">&raquo;</a></li>
        </ul>

    <?php else : ?>
        <p>Data tidak ditemukan.</p>
    <?php endif; ?>

    <br><br>
    <a href="../logout.php">Logout</a>
</body>

</html>