<?php
require_once 'functions.php';

// Cek apakah user sudah login
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Ambil semua agenda
$agendas = ambil_semua_agenda();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Kegiatan Rohis</title>
    <link rel="stylesheet" href="css/halaman_utama.css">
</head>

<body>

    <div class="container">
        <h2>Agenda Kegiatan Rohis</h2>

        <div class="add-agenda">
            <a href="tambah_agenda.php">Tambah Agenda</a>
            <a href="logout.php">Logout</a>
        </div>

        <div class="card-container">
            <?php if (count($agendas) > 0) : ?>
                <?php foreach ($agendas as $agenda) : ?>
                    <div class="card">
                        <img src="uploads/agenda/<?php echo $agenda['dokumentasi_kegiatan']; ?>" alt="Dokumentasi Kegiatan">
                        <h3><?php echo $agenda['tema_agenda']; ?></h3>
                        <p><strong>Tanggal:</strong> <?php echo $agenda['tanggal_kegiatan']; ?></p>
                        <p><strong>Tempat:</strong> <?php echo $agenda['tempat_kegiatan']; ?></p>

                        <div class="card-actions">
                            <a href="detail_agenda.php?id=<?php echo $agenda['agenda_id']; ?>" class="btn-detail">Detail</a>
                            <a href="edit_agenda.php?id=<?php echo $agenda['agenda_id']; ?>" class="btn-edit">Edit</a>
                            <a href="hapus_agenda.php?id=<?php echo $agenda['agenda_id']; ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus agenda ini?');">Hapus</a>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="no-data">Tidak ada agenda tersedia.</div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>