<?php
require_once 'functions.php';

// Cek apakah user sudah login
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Cek apakah ID agenda ada di URL
if (!isset($_GET['id'])) {
    header('Location: halaman_utama.php');
    exit;
}

// Ambil ID agenda dari URL
$agenda_id = $_GET['id'];

// Ambil data agenda berdasarkan ID
$agenda = ambil_agenda_by_id($agenda_id);
if (!$agenda) {
    header('Location: halaman_utama.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal_kegiatan = $_POST['tanggal_kegiatan'];
    $tempat_kegiatan = $_POST['tempat_kegiatan'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $tema_agenda = $_POST['tema_agenda'];
    $deskripsi_agenda = $_POST['deskripsi_agenda'];

    // Cek jika ada file yang diupload
    if (isset($_FILES['dokumentasi_kegiatan']) && $_FILES['dokumentasi_kegiatan']['error'] != UPLOAD_ERR_NO_FILE) {
        // Jika ada file baru yang diupload, simpan file baru
        $dokumentasi_kegiatan = $_FILES['dokumentasi_kegiatan'];
        // Tangani upload file di sini seperti pada tambah_agenda()
    } else {
        // Jika tidak ada file baru yang diupload, gunakan nama file lama
        $dokumentasi_kegiatan = $agenda['dokumentasi_kegiatan'];
    }

    // Update agenda
    if (update_agenda($agenda_id, $tanggal_kegiatan, $tempat_kegiatan, $waktu_mulai, $tema_agenda, $deskripsi_agenda, $dokumentasi_kegiatan)) {
        header('Location: halaman_utama.php'); // Redirect setelah berhasil
        exit;
    } else {
        echo "Terjadi kesalahan saat mengupdate agenda.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Agenda Kegiatan Rohis</title>
    <link rel="stylesheet" href="css/edit.css">
</head>

<body>

    <div class="container">
        <h2>Edit Agenda Kegiatan Rohis</h2>

        <form action="" method="post" enctype="multipart/form-data">
            <label for="tanggal_kegiatan">Tanggal Kegiatan:</label>
            <input type="date" name="tanggal_kegiatan" id="tanggal_kegiatan" value="<?php echo $agenda['tanggal_kegiatan']; ?>" required>

            <label for="tempat_kegiatan">Tempat Kegiatan:</label>
            <input type="text" name="tempat_kegiatan" id="tempat_kegiatan" value="<?php echo $agenda['tempat_kegiatan']; ?>" required>

            <label for="waktu_mulai">Waktu Mulai:</label>
            <input type="time" name="waktu_mulai" id="waktu_mulai" value="<?php echo $agenda['waktu_mulai']; ?>" required>

            <label for="tema_agenda">Tema Agenda:</label>
            <input type="text" name="tema_agenda" id="tema_agenda" value="<?php echo $agenda['tema_agenda']; ?>" required>

            <label for="deskripsi_agenda">Deskripsi Agenda:</label>
            <textarea name="deskripsi_agenda" id="deskripsi_agenda" required><?php echo $agenda['deskripsi_agenda']; ?></textarea>

            <label for="dokumentasi_kegiatan">Dokumentasi Kegiatan (Opsional):</label>
            <input type="file" name="dokumentasi_kegiatan" id="dokumentasi_kegiatan" accept="image/*">

            <button type="submit">Update Agenda</button>
        </form>

        <a href="halaman_utama.php">Kembali ke Daftar Agenda</a>
    </div>

</body>

</html>