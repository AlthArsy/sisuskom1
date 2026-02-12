<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../koneksi.php';

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Asesor'])) {
    header("Location: ../LOGIN/login.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Periksa apakah skema memiliki unit terkait
    $query_cek = "SELECT COUNT(*) as total FROM tb_unit_kompetensi WHERE id_skema = ?";
    $stmt_cek = mysqli_prepare($koneksi, $query_cek);
    mysqli_stmt_bind_param($stmt_cek, 'i', $id);
    mysqli_stmt_execute($stmt_cek);
    $result_cek = mysqli_stmt_get_result($stmt_cek);
    $data_cek = mysqli_fetch_assoc($result_cek);
    mysqli_stmt_close($stmt_cek);
    
    if ($data_cek['total'] > 0) {
        $_SESSION['error'] = "Skema tidak dapat dihapus karena masih memiliki " . $data_cek['total'] . " unit kompetensi terkait!";
    } else {
        // Hapus skema jika tidak ada unit terkait
        $query_hapus = "DELETE FROM tb_skema WHERE id_skema = ?";
        $stmt_hapus = mysqli_prepare($koneksi, $query_hapus);
        mysqli_stmt_bind_param($stmt_hapus, 'i', $id);
        
        if (mysqli_stmt_execute($stmt_hapus)) {
            $_SESSION['success'] = "Skema berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus skema: " . mysqli_error($koneksi);
        }
        mysqli_stmt_close($stmt_hapus);
    }
} else {
    $_SESSION['error'] = "ID skema tidak valid!";
}

// Redirect kembali ke halaman skema
header("Location: ../BERANDA/UTAMA.php?page=../SKEMA/skema.php");
exit();
?>