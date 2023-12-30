<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../login.php"); // Kembalikan session ke login jika belum login.
    exit;
}

require '../../class/functions.php';

$id = $_GET["id"];

if (hapus($id) > 0) {
    echo "
            <script>
                alert('data berhasil di hapus');
                document.location.href = 'index1.php';
            </script> 
        ";
    exit();
} else {
    echo "Error adding record: " . mysqli_error($conn);
}
