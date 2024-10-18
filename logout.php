<?php
session_start();

session_destroy();

// Alihkan pengguna kembali ke halaman login (index.php)
header("Location: index.php");
exit();
