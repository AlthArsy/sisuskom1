<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../LOGIN/login.php");
    exit();
}
include '../koneksi.php';

if (mysqli_connect_errno()) {
    die("Gagal koneksi ke database: " . mysqli_connect_error());
}


if (isset($_GET['all']) && $_GET['all'] == '1') {
    if (!isset($_GET['confirm']) || $_GET['confirm'] != '1') {
        echo '<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><title>Konfirmasi Hapus Semua Asesi</title></head><body>';
        echo '<h2>Konfirmasi: Hapus Semua Data Asesi</h2>';
        echo '<p>Semua data pada tabel <strong>tb_asesi</strong> akan dihapus dan referensi pada tabel <strong>users</strong> akan di-set NULL. Tindakan ini tidak dapat dibatalkan.</p>';
        echo '<p><a href="?all=1&confirm=1">Ya, hapus semua</a> &nbsp; <a href="../ASESI/Table_asesi.php">Batal</a></p>';
        echo '</body></html>';
        exit;
    }


    mysqli_begin_transaction($koneksi);
    try {
       
        $stmt = mysqli_prepare($koneksi, "UPDATE users SET id_referensi = NULL WHERE id_referensi IS NOT NULL");
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $stmt2 = mysqli_prepare($koneksi, "DELETE FROM tb_asesi");
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);

        mysqli_commit($koneksi);
        header("Location: ../ASESI/Table_asesi.php?deleted_all=1");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        die("Gagal menghapus semua data asesi: " . mysqli_error($koneksi));
    }
}


$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID tidak valid.");
}
                        

mysqli_begin_transaction($koneksi);
try {
    $stmt = mysqli_prepare($koneksi, "UPDATE users SET id_referensi = NULL WHERE id_referensi = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($koneksi, "DELETE FROM tb_asesi WHERE id_asesi = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception(mysqli_error($koneksi));
    }
    mysqli_stmt_close($stmt);

    mysqli_commit($koneksi);
    header("Location: ../ASESI/Table_asesi.php?deleted=1");
    exit;
} catch (Exception $e) {
    mysqli_rollback($koneksi);
    die("Gagal menghapus asesi: " . $e->getMessage());
}

mysqli_close($koneksi);
?>