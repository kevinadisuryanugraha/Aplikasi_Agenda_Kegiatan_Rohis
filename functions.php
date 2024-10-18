<?php
require_once 'config.php'; // Menghubungkan ke database

// Fungsi untuk menambahkan pengguna baru
function tambah_user($username, $email)
{
    global $db;
    $stmt = $db->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $email);
    if ($stmt->execute()) {
        return $stmt->insert_id;
    } else {
        return false;
    }
}

// Fungsi untuk mendapatkan pengguna berdasarkan email
function ambil_user_by_email($email)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : false;
}

// Fungsi untuk menambahkan agenda baru
function tambah_agenda($user_id, $tanggal_kegiatan, $tempat_kegiatan, $waktu_mulai, $tema_agenda, $deskripsi_agenda, $dokumentasi_kegiatan)
{
    global $db;

    if (!isset($dokumentasi_kegiatan) || $dokumentasi_kegiatan['error'] != UPLOAD_ERR_OK) {
        echo "Tidak ada file yang diupload atau terjadi kesalahan saat mengupload.";
        return false;
    }

    // Penanganan upload gambar
    $target_dir = "uploads/agenda/"; // Sesuaikan direktori untuk upload
    $file_name = basename($dokumentasi_kegiatan["name"]);
    $target_file = $target_dir . $file_name;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah file adalah gambar
    $check = getimagesize($dokumentasi_kegiatan["tmp_name"]);
    if ($check === false) {
        echo "File yang diupload bukan gambar.";
        return false;
    }

    // Validasi format file
    if ($image_file_type != "jpg" && $image_file_type != "jpeg" && $image_file_type != "png") {
        echo "Hanya file JPG, JPEG, dan PNG yang diperbolehkan.";
        return false;
    }

    // Validasi ukuran file
    if ($dokumentasi_kegiatan["size"] > 2000000) {
        echo "Ukuran file terlalu besar. Maksimal 2MB.";
        return false;
    }

    // Upload file gambar
    if (move_uploaded_file($dokumentasi_kegiatan["tmp_name"], $target_file)) {
        // Query untuk menambahkan agenda baru
        $stmt = $db->prepare("INSERT INTO agenda (user_id, tanggal_kegiatan, tempat_kegiatan, waktu_mulai, tema_agenda, deskripsi_agenda, dokumentasi_kegiatan)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $user_id, $tanggal_kegiatan, $tempat_kegiatan, $waktu_mulai, $tema_agenda, $deskripsi_agenda, $file_name);
        return $stmt->execute();
    } else {
        echo "Terjadi kesalahan saat mengupload gambar.";
        return false;
    }
}

// Fungsi untuk mengambil semua agenda
function ambil_semua_agenda()
{
    global $db;
    $sql = "SELECT agenda.*, users.username FROM agenda JOIN users ON agenda.user_id = users.user_id";
    $result = $db->query($sql);
    return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Fungsi untuk mengambil agenda berdasarkan ID
function ambil_agenda_by_id($agenda_id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM agenda WHERE agenda_id = ?");
    $stmt->bind_param("i", $agenda_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false; // Kembalikan false jika tidak ditemukan
    }
}

// Fungsi untuk mengupdate agenda
function update_agenda($agenda_id, $tanggal_kegiatan, $tempat_kegiatan, $waktu_mulai, $tema_agenda, $deskripsi_agenda, $dokumentasi_kegiatan = null)
{
    global $db;

    // Jika dokumentasi adalah array (file baru diupload), lakukan upload
    if (is_array($dokumentasi_kegiatan)) {
        // Penanganan upload file gambar
        $target_dir = "uploads/agenda/";
        $file_name = basename($dokumentasi_kegiatan["name"]);
        $target_file = $target_dir . $file_name;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Cek apakah file adalah gambar
        $check = getimagesize($dokumentasi_kegiatan["tmp_name"]);
        if ($check === false) {
            echo "File yang diupload bukan gambar.";
            return false;
        }

        // Validasi format file
        if ($image_file_type != "jpg" && $image_file_type != "jpeg" && $image_file_type != "png") {
            echo "Hanya file JPG, JPEG, dan PNG yang diperbolehkan.";
            return false;
        }

        // Validasi ukuran file
        if ($dokumentasi_kegiatan["size"] > 2000000) {
            echo "Ukuran file terlalu besar. Maksimal 2MB.";
            return false;
        }

        // Upload file gambar
        if (!move_uploaded_file($dokumentasi_kegiatan["tmp_name"], $target_file)) {
            echo "Terjadi kesalahan saat mengupload gambar.";
            return false;
        }

        // Gunakan file_name untuk update gambar baru
        $dokumentasi_kegiatan = $file_name;
    }

    // Query dasar untuk update
    $query = "UPDATE agenda SET tanggal_kegiatan = ?, tempat_kegiatan = ?, waktu_mulai = ?, tema_agenda = ?, deskripsi_agenda = ?";

    // Jika ada dokumentasi, tambahkan ke query
    if ($dokumentasi_kegiatan) {
        $query .= ", dokumentasi_kegiatan = ?";
    }

    $query .= " WHERE agenda_id = ?";

    $stmt = $db->prepare($query);

    // Bind parameter dengan benar
    if ($dokumentasi_kegiatan) {
        $stmt->bind_param("ssssssi", $tanggal_kegiatan, $tempat_kegiatan, $waktu_mulai, $tema_agenda, $deskripsi_agenda, $dokumentasi_kegiatan, $agenda_id);
    } else {
        $stmt->bind_param("sssssi", $tanggal_kegiatan, $tempat_kegiatan, $waktu_mulai, $tema_agenda, $deskripsi_agenda, $agenda_id);
    }

    return $stmt->execute();
}



// Fungsi untuk menghapus agenda
function hapus_agenda($agenda_id)
{
    global $db;
    $stmt = $db->prepare("DELETE FROM agenda WHERE agenda_id = ?");
    $stmt->bind_param("i", $agenda_id);
    return $stmt->execute();
}
