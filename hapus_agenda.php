<?php
session_start();
include 'functions.php';

if (isset($_GET['id'])) {
    $agenda_id = $_GET['id'];
    $agendas = ambil_agenda_by_id($agenda_id);
    if ($agendas) {
        if (hapus_agenda($agenda_id)) {
            header("Location: halaman_utama.php");
            exit();
        } else {
            echo "Terjadi kesalahan saat menghapus agenda.";
        }
    } else {
        echo "Agenda tidak ditemukan.";
    }
} else {
    echo "ID agenda tidak ditentukan.";
}
