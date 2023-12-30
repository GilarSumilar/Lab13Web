<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../login.php"); // Kembalikan session ke login jika belum login.
    exit;
}


require '../../class/functions.php';
// Ambil data di URL
$id = $_GET["id"];
// Query data mahasiwa berdasarkan id
$bku = query("SELECT * FROM daftarbuku WHERE no_bku = $id")[0]; // kenapa 0 liat view page source

// Cek apakah tombol submit sudah di tekan ?
if (isset($_POST["submit"])) {

    // Cek apakah data berhasil di ubah ?
    if (ubah($_POST) > 0) {
        echo "
            <script>
                alert('data berhasil di ubah');
                document.location.href = 'index1.php';
            </script> 
        ";
        exit();
    } else {
        echo "Error adding record: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah data Buku</title>
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
    <h1>Ubah data Buku</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="no_bku" value="<?= $bku["no_bku"]; ?>"> <!-- input dengan tipe hidden / ga keliatan -->
        <input type="hidden" name="coverLama" value="<?= $bku["cover"]; ?>">
        <ul>
            <li>
                <label for="buku">Buku :</label>
                <input type="text" name="buku" id="buku" required value="<?= $bku["buku"]; ?>">
            </li>
            <li>
                <label for="penulis">Penulis :</label>
                <input type="text" name="penulis" id="penulis" required value="<?= $bku["penulis"]; ?>">
            </li>
            <li>
                <label for="tahun_terbit">Tahun Terbit :</label>
                <input type="date" name="tahun_terbit" id="tahun_terbit" value="<?= $bku["tahun_terbit"]; ?>">
            </li>
            <li>
                <label for="cover">Cover Buku :</label>
                <img src="img/<?= $bku["cover"]; ?>" alt="">
                <input type="file" name="cover" id="cover">
            </li>
            <li>
                <button type="submit" name="submit">Ubah Data</button>
            </li>
        </ul>
    </form>


</body>

</html>