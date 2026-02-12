<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Asesor') {
    echo "<script>alert('Akses ditolak! Silakan login sebagai Asesor.'); window.location.href='../LOGIN/login.php';</script>";
    exit();
}
include '../koneksi.php';

if (mysqli_connect_errno()) {
    die("Gagal koneksi ke database: " . mysqli_connect_error());
}

$id_user = isset($_SESSION['id_user']) ? (int)$_SESSION['id_user'] : 0;
$role = $_SESSION['role'];
$redirect_home = '../BERANDA/UTAMA.php';

if ($id_user <= 0) {
    echo "<script>alert('Sesi tidak valid. Silakan login ulang.'); window.location.href='../LOGIN/login.php';</script>";
    exit();
}

$id_asesor = null;
$stmt_check = mysqli_prepare($koneksi, "SELECT id_asesor FROM users WHERE id_user = ? LIMIT 1");
if ($stmt_check) {
    mysqli_stmt_bind_param($stmt_check, 'i', $id_user);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_bind_result($stmt_check, $id_asesor);
    mysqli_stmt_fetch($stmt_check);
    mysqli_stmt_close($stmt_check);
} else {
    echo "<script>alert('Gagal menyiapkan query cek profil: " . addslashes(mysqli_error($koneksi)) . "');</script>";
}

if (!empty($id_asesor)) {
    header("Location: $redirect_home");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_reg = isset($_POST['no_reg']) ? trim($_POST['no_reg']) : '';
    $nama_asesor = isset($_POST['nama_asesor']) ? trim($_POST['nama_asesor']) : '';
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? trim($_POST['jenis_kelamin']) : '';
    $alamat = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';

    if ($no_reg && $nama_asesor && $jenis_kelamin && $alamat) {
        $sql = "INSERT INTO tb_asesor (no_reg, nama_asesor, jenis_kelamin, alamat) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ssss', $no_reg, $nama_asesor, $jenis_kelamin, $alamat);
            if (mysqli_stmt_execute($stmt)) {
                $id_asesor = mysqli_insert_id($koneksi);
                mysqli_stmt_close($stmt);

                if ($id_asesor > 0) {
                    $stmt_up = mysqli_prepare($koneksi, "UPDATE users SET id_asesor = ? WHERE id_user = ?");
                    if ($stmt_up) {
                        mysqli_stmt_bind_param($stmt_up, 'ii', $id_asesor, $id_user);
                        if (mysqli_stmt_execute($stmt_up)) {
                            $_SESSION['id_asesor'] = $id_asesor;
                            $_SESSION['id_referensi'] = $id_asesor;
                            echo "<script>alert('Profil berhasil disimpan.'); window.location.href='$redirect_home';</script>";
                            exit();
                        }
                        $err = mysqli_stmt_error($stmt_up);
                        mysqli_stmt_close($stmt_up);
                        echo "<script>alert('Profil tersimpan, tetapi gagal menghubungkan user ke profil. Error: " . addslashes($err) . "');</script>";
                    } else {
                        echo "<script>alert('Profil tersimpan, tetapi gagal menyiapkan query update user: " . addslashes(mysqli_error($koneksi)) . "');</script>";
                    }
                } else {
                    echo "<script>alert('Profil tersimpan, tetapi gagal mengambil ID profil (insert_id). Pastikan kolom id_asesor AUTO_INCREMENT.');</script>";
                }
            } else {
                $err = mysqli_stmt_error($stmt);
                mysqli_stmt_close($stmt);
                echo "<script>alert('Gagal menyimpan profil. Error: " . addslashes($err) . "');</script>";
            }
        } else {
            echo "<script>alert('Prepare error: " . addslashes(mysqli_error($koneksi)) . "');</script>";
        }
    } else {
        echo "<script>alert('Semua field wajib diisi!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Input Profil Asesor</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f7fb;
            margin: 0;
            padding: 0;
        }
        .form-container {
            max-width: 430px;
            width: 97vw;
            margin: 42px auto 0 auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 6px 25px #0002;
            padding: 30px 32px 22px 32px;
        }
        h2 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 27px;
            font-size: 23px;
            color: #252B3A;
            letter-spacing: 1px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            font-weight: 600;
            color: #27314D;
            margin-bottom: 6px;
            display: block;
            font-size: 15px;
        }
        .required {
            color: #e3304d;
            font-weight: 400;
            font-size: 13px;
            margin-left: 3px;
        }
        input[type="text"], textarea, select {
            width: 100%;
            border: 1.5px solid #cdcdde;
            background: #f7f9fd;
            color: #242B30;
            padding: 9px 11px;
            border-radius: 4px;
            font-size: 15px;
            margin-top: 3px;
            margin-bottom: 2px;
            transition: border .2s;
            box-sizing: border-box;
            resize: none;
        }
        input[type="text"]:focus, textarea:focus, select:focus {
            border: 1.5px solid #4A7AFF;
            outline: none;
            background: #fff;
        }
        textarea {
            min-height: 60px;
            max-height: 180px;
        }
        .btn-submit {
            width: 100%;
            background: #275dfa;
            color: #fff;
            border: none;
            padding: 11px 0;
            margin-top: 8px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background .18s;
            letter-spacing: 0.5px;
        }
        .btn-submit:hover {
            background: #1f48bd;
        }
        .info-box {
            background: #e8f4ff;
            border-left: 4px solid #4A7AFF;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
        }
        @media (max-width: 530px) {
            .form-container {
                padding: 15px 7vw 13px 7vw;
            }
            h2 {
                font-size: 19px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Input Profil Asesor</h2>
        
        <div class="info-box">
            <strong>Informasi:</strong> Anda harus melengkapi profil ini sebelum dapat mengakses halaman lainnya.
        </div>
        
        <form method="post" autocomplete="off">
            <div class="form-group">
                <label for="no_reg">No Reg:<span class="required">*</span></label>
                <input type="text" id="no_reg" name="no_reg" value="<?php echo isset($no_reg) ? htmlspecialchars($no_reg) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="nama_asesor">Nama Asesor:<span class="required">*</span></label>
                <input type="text" id="nama_asesor" name="nama_asesor" value="<?php echo isset($nama_asesor) ? htmlspecialchars($nama_asesor) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin:<span class="required">*</span></label>
                <select id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" <?php echo (isset($jenis_kelamin) && $jenis_kelamin == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php echo (isset($jenis_kelamin) && $jenis_kelamin == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat:<span class="required">*</span></label>
                <textarea id="alamat" name="alamat" required><?php echo isset($alamat) ? htmlspecialchars($alamat) : ''; ?></textarea>
            </div>
            <button type="submit" class="btn-submit">Simpan Profil</button>
        </form>
    </div>
</body>
</html>