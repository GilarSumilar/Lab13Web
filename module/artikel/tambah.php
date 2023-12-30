<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../login.php"); // Kembalikan session ke login jika belum login.
    exit;
}


require '../../class/functions.php';

// Cek apakah tombol submit sudah di tekan ?
if (isset($_POST["submit"])) {

    // Cek apakah data berhasil di tambahkan ?
    if (tambah($_POST) > 0) {
        echo "
            <script>
                alert('data berhasil di tambahkan');
                document.location.href = 'index1.php';
            </script> 
        ";
    } else {
        echo "
            <script>
                alert('data gagal di tambahkan');
                document.location.href = 'tambah.php';
            </script> 
        ";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah data Buku</title>
    <style>
        form {
            max-width: 400px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input,
        date,
        file {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>

</head>

<body>
    <h1>Tambah data Buku</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <ul>
            <li>
                <label for="buku">Buku :</label>
                <input type="text" name="buku" id="buku" required>
            </li>
            <li>
                <label for="penulis">Penulis :</label>
                <input type="text" name="penulis" id="penulis" required>
            </li>
            <li>
                <label for="tahun_terbit">Tahun Terbit :</label>
                <input type="date" name="tahun_terbit" id="tahun_terbit">
            </li>
            <li>
                <label for="cover">Cover Buku :</label>
                <input type="file" name="cover" id="cover">
            </li>
            <li>
                <button type="submit" name="submit">Tambah Data</button>
            </li>
        </ul>
    </form>


</body>

</html>