<?php
// Koneksi
$conn = mysqli_connect("localhost", "root", "", "phpdasar");

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $row = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function tambah($data)
{
    global $conn;

    // Ambil data dari tiap element dalam form
    $buku = htmlspecialchars($data["buku"]);
    $penulis = htmlspecialchars($data["penulis"]); // htmlspecialchars : biar kalo ngirim html jadi string lurd
    $tahun_terbit = htmlspecialchars($data["tahun_terbit"]);

    // Upload gambar dari file
    $cover = upload();
    if (!$cover) {
        return false;
    }

    // Query insert data
    $query = "INSERT INTO daftarbuku
                VALUES 
                ('', '$buku', '$penulis', '$tahun_terbit', '$cover')";

    mysqli_query($conn, $query);

    // Cek apakah data berhasil di tambahkan ?
    return mysqli_affected_rows($conn);
}

function upload()
{
    $namaFile = $_FILES['cover']['name'];
    $ukuranFile = $_FILES['cover']['size'];
    $error = $_FILES['cover']['error'];
    $tmpName = $_FILES['cover']['tmp_name'];

    // Cek apakah tidak ada gambar yg di upload
    if ($error === 4) {
        echo "<script>
                alert('Pilih gambar terlebih dahulu');
            </script>";
        return false;
    }

    // Cek apakah yang di upload adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>
                alert('Upload gambar!');
            </script>";
        return false;
    }

    // Cek jika ukuran terlalu besar
    if ($ukuranFile > 1000000) {
        echo "<script>
                alert('Gambar terlalu besar!');
            </script>";
        return false;
    }

    // Lolos pengecekan 
    // Generate nama gambar baru agar tidak sama
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;
    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);
    return $namaFileBaru;
}

function hapus($id)
{
    global $conn;
    mysqli_query($conn, "DELETE FROM daftarbuku WHERE no_bku = $id");
    return mysqli_affected_rows($conn);
}

function sanitizeInput($input)
{
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars($input));
}

function cari($input)
{
    $q = "";
    $sql_where = "";

    if (!empty($input)) {
        $q = sanitizeInput($input);

        $sql_where = " WHERE buku LIKE '%$q%' 
            OR penulis LIKE '%$q%' 
            OR tahun_terbit LIKE '%$q%'
        ";
    }

    return $sql_where;
}


function ubah($data)
{
    global $conn;

    // Ambil data dari tiap element dalam form
    $no_bku = ($data["no_bku"]);
    $buku = htmlspecialchars($data["buku"]);
    $penulis = htmlspecialchars($data["penulis"]); // htmlspecialchars : biar kalo ngirim html jadi string lurd
    $tahun_terbit = htmlspecialchars($data["tahun_terbit"]);
    $coverLama = htmlspecialchars($data["coverLama"]);

    // Cek user pilih gambar baru atau tidak
    if ($_FILES['cover']['error'] === 4) {
        $cover = $coverLama;
    } else {
        $cover = upload();
    }


    // Query insert data
    $query = "UPDATE daftarbuku SET 
            buku = '$buku',
            penulis = '$penulis',
            tahun_terbit = '$tahun_terbit',
            cover = '$cover'
        WHERE no_bku = $no_bku";

    mysqli_query($conn, $query);

    // Cek apakah data berhasil di tambahkan ?
    return mysqli_affected_rows($conn);
}

function registrasi($data)
{

    global $conn;

    $username = strtolower(stripslashes($data["username"])); // Agar input ke database huruf kecil
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);

    // Cek username udh ada / belum
    $result = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");

    if (mysqli_fetch_assoc($result)) {
        echo "<script>
                alert('username sudah terdaftar');
            </script>";
        return false;
    }

    // Cek konfirmasi password
    if ($password !== $password2) {
        echo "<script>
                alert('konfirmasi password tidak sesuai!');
            </script>";
        return false;
    }

    // Enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);
    // $password = md5($password); jgn pake itu gampang ke bobol

    // Tambahkan query ke database
    mysqli_query($conn, "INSERT INTO users VALUES('', '$username', '$password')");

    return mysqli_affected_rows($conn);
}
