<?php
include "../koneksi.php";

$username = isset($_POST['username']) ? trim($_POST['username']) : (isset($_GET['username']) ? trim($_GET['username']) : '');
$password = isset($_POST['password']) ? trim($_POST['password']) : (isset($_GET['password']) ? trim($_GET['password']) : '');
$role_raw = isset($_POST['role']) ? trim($_POST['role']) : (isset($_GET['role']) ? trim($_GET['role']) : '');

$role_map = [
    'admin'  => 'Admin',
    'asesor' => 'Asesor',
    'asesi'  => 'Asesi'
];
$role_key = strtolower($role_raw);

if (!empty($role_key) && isset($role_map[$role_key]) && !empty($password) && (
        (($role_map[$role_key] == 'Admin' || $role_map[$role_key] == 'Asesor' || $role_map[$role_key] == 'Asesi') && !empty($username))
    )) {

    $role = $role_map[$role_key];
    $role_esc = mysqli_real_escape_string($koneksi, $role);

    if ($role === '') {
        $sql = "SELECT * FROM users WHERE role='$role_esc' LIMIT 1";
    } else {
        $username_esc = mysqli_real_escape_string($koneksi, $username);
        $sql = "SELECT * FROM users WHERE username='$username_esc' AND role='$role_esc' LIMIT 1";
    }

    $result = mysqli_query($koneksi, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (strlen($user['password']) > 40) {
            $pass_ok = password_verify($password, $user['password']);
        } else {
            $pass_ok = $password === $user['password'];
        }

        if ($pass_ok) {
            session_start();
            $_SESSION['username'] = $user['username'] ?? '';
            $_SESSION['role'] = $user['role'];
            $_SESSION['id_user'] = $user['id_user'] ?? 0;
            $_SESSION['id_asesor'] = $user['id_asesor'] ?? null;
            $_SESSION['id_asesi'] = $user['id_asesi'] ?? null;

            if (!empty($user['id_asesor']) && !is_null($user['id_asesor'])) {
                $id_asesor = $user['id_asesor'];
                
                if ($role === 'Asesor') {

                    $profil = mysqli_query($koneksi, "SELECT nama_asesor FROM tb_asesor WHERE id_asesor = '$id_asesor'");
                    $data_profil = mysqli_fetch_assoc($profil);
                    
                    if ($data_profil) {
                        $_SESSION['nama_user'] = $data_profil['nama_asesor'];
                    }
                }
            }

            if (!empty($user['id_asesi']) && !is_null($user['id_asesi'])) {
                $id_asesi = $user['id_asesi'];

                if ($role === 'Asesi') {
                    $profil = mysqli_query($koneksi, "SELECT nama_asesi FROM tb_asesi WHERE id_asesi = '$id_asesi'");
                    $data_profil = mysqli_fetch_assoc($profil);
                    
                    if ($data_profil) {
                        $_SESSION['nama_user'] = $data_profil['nama_asesi'];
                    }
                } 
            } 

            

            if ($role === 'Asesor') {
                if (empty($user['id_asesor']) || is_null($user['id_asesor'])) {
                    if ($role === 'Asesor') {
                        echo "<script>alert('Silakan lengkapi profil terlebih dahulu.'); window.location.href='../ASESOR/input_profil.php';</script>";
                        exit;
                    } 
                }
            }
            
            if ($role === 'Asesi') {
                if (empty($user['id_asesi']) || is_null($user['id_asesi'])) {
                    if ($role === 'Asesi') {
                        echo "<script>alert('Silakan lengkapi profil terlebih dahulu.'); window.location.href='../ASESI/input_profil.php';</script>";
                        exit;
                    }
                }
            }

            if ($role === 'Admin') {
                echo "<script>alert('Login berhasil sebagai ADMIN'); window.location.href='../BERANDA/UTAMA.php';</script>";
            } elseif ($role === 'Asesor') {
                echo "<script>alert('Login berhasil sebagai Asesor'); window.location.href='../BERANDA/UTAMA.php';</script>";
            } elseif ($role === 'Asesi') {
                echo "<script>alert('Login berhasil sebagai Asesi'); window.location.href='../BERANDA/UTAMA.php';</script>";
            } else {
                echo "<script>alert('Role tidak dikenali!'); window.location.href='../LOGIN/login.php';</script>";
            }
        } else {
            echo "<script>alert('Password salah!'); window.location.href='../LOGIN/login.php';</script>";
        }
    } else {
        echo "<script>alert('Username atau Role tidak sesuai!'); window.location.href='../LOGIN/login.php';</script>";
    }
} else {
    if (isset($role) && $role === 'Admin' && empty($password)) {
        echo "<script>alert('Harap isi password!'); window.location.href='../LOGIN/login.php';</script>";
    } else {
        echo "<script>alert('Harap isi semua field!'); window.location.href='../LOGIN/login.php';</script>";
    }
}

mysqli_close($koneksi);
?>
