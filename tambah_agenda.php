<?php
require_once 'config.php'; 
require_once 'functions.php'; 

// Cek apakah pengguna sudah login
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $tanggal_kegiatan = $_POST['tanggal_kegiatan'];
    $tempat_kegiatan = $_POST['tempat_kegiatan'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $tema_agenda = $_POST['tema_agenda'];
    $deskripsi_agenda = $_POST['deskripsi_agenda'];
    $dokumentasi_kegiatan = $_FILES['dokumentasi_kegiatan'];

    // Panggil fungsi untuk menambahkan agenda
    if (tambah_agenda($user_id, $tanggal_kegiatan, $tempat_kegiatan, $waktu_mulai, $tema_agenda, $deskripsi_agenda, $dokumentasi_kegiatan)) {
        header('Location: halaman_utama.php');
        exit;
    } else {
        echo "Terjadi kesalahan saat menambahkan agenda.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Agenda</title>
    <link rel="stylesheet" href="css/tambah.css">
</head>

<body>
    <div class="container">
        <h2>Tambah Agenda Kegiatan</h2>
        <form action="tambah_agenda.php" method="POST" enctype="multipart/form-data">
            <label for="tanggal_kegiatan">Tanggal Kegiatan:</label>
            <input type="date" name="tanggal_kegiatan" required>

            <label for="tempat_kegiatan">Tempat Kegiatan:</label>
            <input type="text" name="tempat_kegiatan" required>

            <label for="waktu_mulai">Waktu Mulai:</label>
            <input type="time" name="waktu_mulai" required>

            <label for="tema_agenda">Tema Agenda:</label>
            <input type="text" name="tema_agenda" required>

            <label for="deskripsi_agenda">Deskripsi Agenda:</label>
            <textarea name="deskripsi_agenda" required></textarea>

            <label for="dokumentasi_kegiatan">Dokumentasi Kegiatan (gambar):</label>
            <input type="file" name="dokumentasi_kegiatan" accept="image/*" required>

            <input type="submit" value="Tambah Agenda">
        </form>
        <a href="halaman_utama.php">Kembali ke Halaman Utama</a>
    </div>
</body>

</html>