<?php
// 1. Koneksi ke database MySQL
$hostname = "localhost";
$username = "root";
$password = "";

$db = new mysqli($hostname, $username, $password);

// Cek koneksi
if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
} else {
    echo "Koneksi berhasil" . "<br>";
}

// 2. Buat database jika belum ada
$sql_buat_db = "CREATE DATABASE IF NOT EXISTS db_agenda_rohis";
$eksekusi_buat_db = $db->query($sql_buat_db);

if ($eksekusi_buat_db) {
    echo "Database 'db_agenda_rohis' berhasil dibuat atau sudah ada" . "<br>";
} else {
    die("Gagal membuat database: " . $db->error);
}

// 3. Pilih database
$sql_masuk_db = "USE db_agenda_rohis";
$eksekusi_masuk_db = $db->query($sql_masuk_db);

if ($eksekusi_masuk_db) {
    echo "Database 'db_agenda_rohis' berhasil dipilih" . "<br>";
} else {
    die("Gagal memilih database: " . $db->error);
}

// 4. Buat tabel 'users' jika belum ada
$sql_buat_tabel_users = "CREATE TABLE IF NOT EXISTS users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
)";

$eksekusi_buat_tabel_users = $db->query($sql_buat_tabel_users);

if ($eksekusi_buat_tabel_users) {
    echo "Tabel 'users' berhasil dibuat" . "<br>";
} else {
    die("Gagal membuat tabel 'users': " . $db->error);
}

// 5. Buat tabel 'agenda' jika belum ada
$sql_buat_tabel_agenda = "CREATE TABLE IF NOT EXISTS agenda (
    agenda_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    tanggal_kegiatan DATE NOT NULL,
    tempat_kegiatan VARCHAR(255) NOT NULL,
    waktu_mulai TIME NOT NULL,
    tema_agenda VARCHAR(255) NOT NULL,
    deskripsi_agenda TEXT,
    dokumentasi_kegiatan VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
)";

$eksekusi_buat_tabel_agenda = $db->query($sql_buat_tabel_agenda);

if ($eksekusi_buat_tabel_agenda) {
    echo "Tabel 'agenda' berhasil dibuat" . "<br>";
} else {
    die("Gagal membuat tabel 'agenda': " . $db->error);
}
