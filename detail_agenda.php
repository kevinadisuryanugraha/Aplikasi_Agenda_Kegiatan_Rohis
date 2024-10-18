<?php
session_start();
include 'functions.php';

// Cek apakah ID agenda ada di URL
if (isset($_GET['id'])) {
    $agenda_id = $_GET['id'];
    $agenda = ambil_agenda_by_id($agenda_id);
} else {
    header("Location: halaman_utama.php"); // Redirect jika tidak ada ID
    exit();
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Agenda</title>
    <link rel="stylesheet" href="css/detail.css">
</head>

<body>
    <div class="container">
        <h1>Detail Agenda</h1>
        <?php if ($agenda): ?>
            <div class="agenda-detail">
                <h2><?php echo $agenda['tema_agenda']; ?></h2>
                <p><strong>Tanggal Kegiatan:</strong> <?php echo $agenda['tanggal_kegiatan']; ?></p>
                <p><strong>Tempat Kegiatan:</strong> <?php echo $agenda['tempat_kegiatan']; ?></p>
                <p><strong>Waktu Mulai:</strong> <?php echo $agenda['waktu_mulai']; ?></p>
                <p><strong>Deskripsi:</strong> <?php echo nl2br($agenda['deskripsi_agenda']); ?></p>
                <?php if ($agenda['dokumentasi_kegiatan']): ?>
                    <p><strong>Dokumentasi:</strong></p>
                    <img src="uploads/agenda/<?php echo $agenda['dokumentasi_kegiatan']; ?>" alt="Dokumentasi Kegiatan" class="agenda-image">
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>Agenda tidak ditemukan.</p>
        <?php endif; ?>
        <a href="halaman_utama.php" class="btn-back">Kembali ke Halaman Utama</a>
    </div>
</body>

</html>