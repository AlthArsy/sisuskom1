<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }

// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
//     header('Location: ../LOGIN/login.php');
//     exit;
// }

// include '../koneksi.php';

// if (mysqli_connect_errno()) {
//     die("Gagal koneksi ke database: " . mysqli_connect_error());
// }

// if (isset($_GET['id'])) {
//     $id = intval($_GET['id']);
    
//     $check_sql = "SELECT * FROM users WHERE id_user = ?";
//     $check_stmt = mysqli_prepare($koneksi, $check_sql);
    
//     if ($check_stmt) {
//         mysqli_stmt_bind_param($check_stmt, "i", $id);
//         mysqli_stmt_execute($check_stmt);
//         $result = mysqli_stmt_get_result($check_stmt);
        
//         if (mysqli_num_rows($result) > 0) {
//             // User ditemukan, lanjutkan penghapusan
//             $delete_sql = "DELETE FROM users WHERE id_user = ?";
//             $delete_stmt = mysqli_prepare($koneksi, $delete_sql);
            
//             if ($delete_stmt) {
//                 mysqli_stmt_bind_param($delete_stmt, "i", $id);
                
//                 if (mysqli_stmt_execute($delete_stmt)) {
//                     $_SESSION['pesan'] = "User berhasil dihapus";
//                     $_SESSION['tipe'] = "success";
//                 } else {
//                     $_SESSION['pesan'] = "Gagal menghapus user: " . mysqli_error($koneksi);
//                     $_SESSION['tipe'] = "error";
//                 }
//                 mysqli_stmt_close($delete_stmt);
//             } else {
//                 $_SESSION['pesan'] = "Error preparing delete statement: " . mysqli_error($koneksi);
//                 $_SESSION['tipe'] = "error";
//             }
//         } else {
//             $_SESSION['pesan'] = "User tidak ditemukan";
//             $_SESSION['tipe'] = "error";
//         }
//         mysqli_stmt_close($check_stmt);
//     } else {
//         $_SESSION['pesan'] = "Error preparing check statement: " . mysqli_error($koneksi);
//         $_SESSION['tipe'] = "error";
//     }
// } else {
//     $_SESSION['pesan'] = "ID user tidak valid";
//     $_SESSION['tipe'] = "error";
// }

// header('Location: ../BERANDA/UTAMA.php?page=../MANAGEMENT/tampil2.php');
// exit;

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    echo "<script>alert('Akses ditolak! Silakan login sebagai Admin.'); window.location.href='../LOGIN/login.php';</script>";
    exit();
}

include '../koneksi.php';

if (mysqli_connect_errno()) {
    die("Gagal koneksi ke database: " . mysqli_error($koneksi));
}

if (isset($_GET['id'])) {
    $id_user = intval($_GET['id']);
    
    
    $sql_select = "SELECT * FROM users WHERE id_user = ?";
    $stmt_select = mysqli_prepare($koneksi, $sql_select);
    
    if ($stmt_select) {
        mysqli_stmt_bind_param($stmt_select, "i", $id_user);
        mysqli_stmt_execute($stmt_select);
        $result = mysqli_stmt_get_result($stmt_select);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            $username = $user_data['username'];
            $role = $user_data['role'];
            $id_referensi = $user_data['id_referensi'] ?? null;
            
           
            mysqli_begin_transaction($koneksi);
            
            $success = true;
            $error_message = '';
            
            
            if ($id_referensi) {
                if ($role === 'Asesi') {
                    $sql_delete_asesi = "DELETE FROM tb_asesi WHERE id_asesi = ?";
                    $stmt_delete_asesi = mysqli_prepare($koneksi, $sql_delete_asesi);
                    if ($stmt_delete_asesi) {
                        mysqli_stmt_bind_param($stmt_delete_asesi, "i", $id_referensi);
                        if (!mysqli_stmt_execute($stmt_delete_asesi)) {
                            $success = false;
                            $error_message = mysqli_error($koneksi);
                        }
                        mysqli_stmt_close($stmt_delete_asesi);
                    }
                } elseif ($role === 'Asesor') {
                    $sql_delete_asesor = "DELETE FROM tb_asesor WHERE id_asesor = ?";
                    $stmt_delete_asesor = mysqli_prepare($koneksi, $sql_delete_asesor);
                    if ($stmt_delete_asesor) {
                        mysqli_stmt_bind_param($stmt_delete_asesor, "i", $id_referensi);
                        if (!mysqli_stmt_execute($stmt_delete_asesor)) {
                            $success = false;
                            $error_message = mysqli_error($koneksi);
                        }
                        mysqli_stmt_close($stmt_delete_asesor);
                    }
                }
            }
            
            
            if ($success) {
                $sql_delete_user = "DELETE FROM users WHERE id_user = ?";
                $stmt_delete_user = mysqli_prepare($koneksi, $sql_delete_user);
                
                if ($stmt_delete_user) {
                    mysqli_stmt_bind_param($stmt_delete_user, "i", $id_user);
                    
                    if (mysqli_stmt_execute($stmt_delete_user)) {
                        mysqli_commit($koneksi);
                        echo "<script>alert('User {$username} berhasil dihapus!'); window.location.href='../BERANDA/UTAMA.php?page=../MANAGEMENT/tampil2.php';</script>";
                    } else {
                        $success = false;
                        $error_message = mysqli_error($koneksi);
                    }
                    
                    mysqli_stmt_close($stmt_delete_user);
                }
            }
            
           
            if (!$success) {
                mysqli_rollback($koneksi);
                echo "<script>alert('Gagal menghapus user: {$error_message}'); window.location.href='../BERANDA/UTAMA.php?page=../MANAGEMENT/tampil2.php';</script>";
            }
            
        } else {
            echo "<script>alert('User tidak ditemukan!'); window.location.href='../BERANDA/UTAMA.php?page=../MANAGEMENT/tampil2.php';</script>";
        }
        mysqli_stmt_close($stmt_select);
    } else {
        echo "<script>alert('Gagal memproses penghapusan!'); window.location.href='../BERANDA/UTAMA.php?page=../MANAGEMENT/tampil2.php';</script>";
    }
} else {
    echo "<script>alert('ID tidak valid!'); window.location.href='../BERANDA/UTAMA.php?page=../MANAGEMENT/tampil2.php';</script>";
}
?>