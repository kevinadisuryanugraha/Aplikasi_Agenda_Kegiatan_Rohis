<?php
require_once 'functions.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: halaman_utama.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Cek apakah user dengan email ini sudah ada
    $existing_user = ambil_user_by_email($email);

    if ($existing_user) {
        // Jika user sudah terdaftar, set session dan alihkan ke halaman utama
        $_SESSION['user_id'] = $existing_user['user_id'];
        header("Location: halaman_utama.php");
        exit();
    } else {
        // Jika user belum terdaftar, tambahkan pengguna baru
        $user_id = tambah_user($username, $email);

        // Cek apakah penambahan pengguna berhasil
        if ($user_id) {
            // Simpan user_id di session
            $_SESSION['user_id'] = $user_id; // Simpan ID pengguna
            header("Location: halaman_utama.php"); // Alihkan ke halaman utama
            exit();
        } else {
            echo "Gagal menambahkan pengguna: " . $db->error;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Pengguna</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="container">
        <div class="form-section">
            <h2>Form Input Pengguna</h2>
            <form method="post" action="">
                <input type="text" name="username" placeholder="Username" required autofocus>
                <input type="email" name="email" placeholder="Email" required>
                <input type="submit" value="Submit">
            </form>
        </div>

        <div class="welcome-section">
            <h1>Selamat datang di Agenda Kegiatan Rohis</h1>
        </div>
    </div>
</body>

</html>