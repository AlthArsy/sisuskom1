<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . "/../koneksi.php";

if (!isset($_SESSION['id_referensi'])) {
    die("Akses GAGAL");
}

$id_asesor = (int)$_SESSION['id_referensi']; 

$no_skema  = trim($_POST['no_skema'] ?? '');
$judul     = trim($_POST['judul_skema'] ?? '');
$standar   = trim($_POST['standar_kompetensi'] ?? '');

if ($no_skema === '' || $judul === '' || $standar === '') {
    die("Input tidak lengkap.");
}

$stmt = mysqli_prepare(
    $koneksi,
    "INSERT INTO tb_skema (nomor_skema, judul_skema, standar_kompetensi_kerja, id_asesor)
     VALUES (?, ?, ?, ?)"
);

if (!$stmt) {
    die("Gagal prepare: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "sssi", $no_skema, $judul, $standar, $id_asesor);
$query = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if ($query) {
        header("Location: ../BERANDA/UTAMA.php?page=../SKEMA/list_skema.php");}        
else {
    echo "Gagal simpan data: " . mysqli_error($koneksi);
}
