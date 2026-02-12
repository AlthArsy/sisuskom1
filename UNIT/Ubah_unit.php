<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Asesor'])) {
    header("Location: ../LOGIN/login.php");
    exit();
}

include '../koneksi.php';

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$id_skema = isset($_GET['id_skema']) ? intval($_GET['id_skema']) : 0;

$unit_data = null;
if ($id > 0) {
    $query = 'SELECT * FROM tb_unit_kompetensi WHERE id_unit = ?';
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $unit_data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

if (!$unit_data) {
    $_SESSION['error'] = 'Data unit kompetensi tidak ditemukan';
    if ($id_skema > 0) {
        header("Location: ../BERANDA/UTAMA.php?page=../UNIT/unit_kompetensi.php&id_skema=$id_skema");
    } else {
        header('Location: ../BERANDA/UTAMA.php?page=../UNIT/unit_kompetensi.php');
    }
    exit();
}

$query_skema = "SELECT id_skema, nomor_skema, judul_skema FROM tb_skema ORDER BY nomor_skema";
$result_skema = mysqli_query($koneksi, $query_skema);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_unit = mysqli_real_escape_string($koneksi, $_POST['kode_unit']);
    $judul_unit = mysqli_real_escape_string($koneksi, $_POST['judul_unit']);
    $id_skema_update = intval($_POST['id_skema']);

    if (empty($kode_unit) || empty($judul_unit)) {
        $_SESSION['error'] = 'Kode unit dan judul harus diisi!';
    } else {
        $query_update = 'UPDATE tb_unit_kompetensi SET
                        kode_unit = ?,
                        judul_unit = ?,
                        id_skema = ?
                        WHERE id_unit = ?';
        $stmt_update = mysqli_prepare($koneksi, $query_update);
        mysqli_stmt_bind_param($stmt_update, "ssii", $kode_unit, $judul_unit, $id_skema_update, $id);

        if(mysqli_stmt_execute($stmt_update)) {
            $_SESSION["success"] = "Data unit berhasil diupdate!";
            mysqli_stmt_close($stmt_update);
            
            if ($id_skema > 0) {
                header("Location: ../BERANDA/UTAMA.php?page=../UNIT/unit_kompetensi.php&id_skema=$id_skema");
            } else {
                header("Location: ../BERANDA/UTAMA.php?page=../UNIT/unit_kompetensi.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Gagal mengubah data: " . mysqli_error($koneksi);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Unit Kompetensi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #66afe9;
            outline: none;
        }
        .btn-group {
            text-align: right;
        }
        .btn {
            padding: 10px 15px;
            border: none; 
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
            font-size: 16px;
        }
        .btn-simpan {
            background-color: #28a745;
            color: #fff;
        }
        .btn-simpan:hover {
            background-color: #218838;
        }
        .btn-batal {
            background-color: #dc3545;
            color: #fff;
            text-decoration: none;
            line-height: 32px;
            display: inline-block;
            text-align: center;
        }
        .btn-batal:hover {
            background-color: #c82333;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .info-box {
            background-color: #e2e3e5;
            border-left: 5px solid #17a2b8;
            padding: 10px;
            margin-bottom: 15px;
        }
        .info-box h4 {
            margin-top: 0;
        }
        .required {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-edit"></i> Ubah Unit Kompetensi</h2>
        </div>
        
        <div class="form-container">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if ($id_skema > 0): ?>
                <div class="info-box">
                    <h4><i class="fas fa-info-circle"></i> Konteks Skema</h4>
                    <p>Anda sedang mengubah unit kompetensi dalam skema tertentu. Skema tidak dapat diubah dari halaman ini.</p>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <input type="hidden" name="id_skema" value="<?= $id_skema > 0 ? $id_skema : ($unit_data['id_skema'] ?? '') ?>">
                
                <div class="form-group">
                    <label for="kode_unit">Kode Unit <span class="required">*</span></label>
                    <input type="text" id="kode_unit" name="kode_unit" 
                           value="<?= htmlspecialchars($unit_data['kode_unit'] ?? '') ?>"
                           placeholder="Contoh: UNIT001" required>
                </div>
                
                <div class="form-group">
                    <label for="judul_unit">Judul Unit Kompetensi <span class="required">*</span></label>
                    <input type="text" id="judul_unit" name="judul_unit" 
                           value="<?= htmlspecialchars($unit_data['judul_unit'] ?? '') ?>"
                           placeholder="Contoh: Melakukan Instalasi Perangkat Jaringan" required>
                </div>
                
                <?php if ($id_skema == 0): ?>
                <div class="form-group">
                    <label for="id_skema">Skema Sertifikasi <span class="required">*</span></label>
                    <select id="id_skema" name="id_skema" required>
                        <option value="">-- Pilih Skema --</option>
                        <?php 
                        mysqli_data_seek($result_skema, 0);
                        while ($skema = mysqli_fetch_assoc($result_skema)): 
                        ?>
                            <option value="<?= $skema['id_skema'] ?>"
                                <?= ($skema['id_skema'] == ($unit_data['id_skema'] ?? 0)) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($skema['nomor_skema']) ?> - <?= htmlspecialchars($skema['judul_skema']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <?php endif; ?>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-simpan">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    
                    <?php if ($id_skema > 0): ?>
                        <a href="../BERANDA/UTAMA.php?page=../UNIT/unit_kompetensi.php&id_skema=<?= $id_skema ?>" 
                           class="btn btn-batal">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    <?php else: ?>
                        <a href="../BERANDA/UTAMA.php?page=../UNIT/unit_kompetensi.php" 
                           class="btn btn-batal">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.getElementById('kode_unit').focus();
        
        document.querySelector('form').addEventListener('submit', function(e) {
            const kodeUnit = document.getElementById('kode_unit').value.trim();
            const judulUnit = document.getElementById('judul_unit').value.trim();
            
            if (!kodeUnit || !judulUnit) {
                e.preventDefault();
                alert('Harap isi semua field yang wajib diisi!');
                return false;
            }
        });
    </script>
</body>
</html>

<?php
mysqli_close($koneksi);
?>