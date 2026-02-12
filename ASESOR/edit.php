<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();

}
include "../koneksi.php";

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    echo "<script>alert('Akses ditolak! Silakan login terlebih dahulu.'); window.location.href='../LOGIN/login.php';</script>";
    exit;
}

$id_asesor = isset($_GET['id']) ? intval($_GET['id']) : 0;
$success_message = '';
$error_message = '';

if ($id_asesor > 0) {
    $query = "SELECT * FROM tb_asesor WHERE id_asesor = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_asesor);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $asesor = mysqli_fetch_assoc($result);
    } else {
        $error_message = "Data asesor tidak ditemukan!";
    }
    mysqli_stmt_close($stmt);
} else {
    $error_message = "ID asesor tidak valid!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id_asesor = isset($_POST['id_asesor']) ? intval($_POST['id_asesor']) : $id_asesor;
    $no_reg = mysqli_real_escape_string($koneksi, $_POST['no_reg']);
    $nama_asesor = mysqli_real_escape_string($koneksi, $_POST['nama_asesor']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    
    $errors = [];
    
    if (empty($no_reg)) {
        $errors[] = "No Reg asesor harus diisi!";
    } elseif (!is_numeric($no_reg)) {
        $errors[] = "No Reg harus berupa angka!";
    } elseif (strlen($no_reg) > 30) {
        $errors[] = "No Reg maksimal 16 digit!";
    }
    
    if (empty($nama_asesor)) {
        $errors[] = "Nama harus diisi!";
    }
    
    if (empty($jenis_kelamin)) {
        $errors[] = "Jenis kelamin harus dipilih!";
    }
    
    if (empty($alamat)) {
        $errors[] = "Alamat harus diisi!";
    }
    
    if (empty($errors)) {
        $query = "UPDATE tb_asesor SET 
                  no_reg = ?,
                  nama_asesor = ?,
                  jenis_kelamin = ?,
                  alamat = ?
                  WHERE id_asesor = ?";
        
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "ssssi", 
            $no_reg, $nama_asesor, $jenis_kelamin, $alamat, $id_asesor);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Data asesor berhasil diperbarui!";
            
            $query = "SELECT * FROM tb_asesor WHERE id_asesor = ?";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "i", $id_asesor);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $asesor = mysqli_fetch_assoc($result);
        } else {
            $error_message = "Gagal memperbarui data: " . mysqli_error($koneksi);
        }
        mysqli_stmt_close($stmt);
    } else {
        $error_message = implode("<br>", $errors);
    }
}


if (empty($asesor) && empty($error_message)) {
    $error_message = "Data asesor tidak ditemukan!";
}
?>
<style>
    .edit-container {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        max-width: 1200px;
        margin: 0 auto;
    }
    
    
    .edit-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #007bff;
    }
    
    .edit-header h1 {
        color: #2c3e50;
        margin-bottom: 10px;
    }
    
    .edit-header p {
        color: #666;
    }
    
   
    .user-info {
        background: #e9f7fe;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .user-info span {
        font-weight: 600;
    }
    
    
    .alert {
        padding: 15px;
        margin-bottom: 25px;
        border-radius: 6px;
        border: 1px solid transparent;
    }
    
    .alert-success {
        background: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }
    
    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }
    
    
    .form-section {
        background: #f8fafc;
        padding: 25px;
        margin-bottom: 25px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }
  
    .form-group {
        margin-bottom: 20px;
    }
    
    input[type="text"],
    textarea,
    select {
        width: 100%;
        padding: 12px;
        border: 1px solid #cbd5e0;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.2s;
    }
    
    input:focus,
    textarea:focus,
    select:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }
    
    small {
        display: block;
        margin-top: 5px;
        font-size: 12px;
        color: #718096;
    }
    
   
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }
    
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 0 10px;
    }
    
    .col-md-4 {
        flex: 0 0 33.333%;
        max-width: 33.333%;
        padding: 0 10px;
    }
    
    
    .btn {
        display: inline-block;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.2s;
    }
    
    .btn-sm {
        padding: 8px 16px;
        font-size: 13px;
    }
    
    .btn-primary {
        background: #007bff;
        color: white;
    }
    
    .btn-primary:hover {
        background: #0056b3;
    }
    
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #545b62;
    }
    
    
    .btn-container {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }
    
    
    .fas {
        margin-right: 8px;
    }
    
    
    @media (max-width: 768px) {
        .edit-container {
            padding: 20px;
        }
        
        .user-info {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .btn-container {
            flex-direction: column;
            gap: 10px;
        }
        
        .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<div class="edit-container">
    <div class="editheader">
        <h1><i class="fas fa-user-edit"></i> Edit Data Asesor</h1>
        <p>Perbarui informasi data asesor sesuai dengan struktur database</p>
    </div>
    
    <div class="user-info">
        <div>
            <i class="fas fa-user"></i> Logged in sebagai: 
            <span><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span> 
            (<span><?php echo htmlspecialchars($_SESSION['role'] ?? ''); ?></span>)
        </div>
    </div>
    
    <?php if (!empty($error_message) && !isset($asesor)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> 
            <?php echo $error_message; ?>
            <br><br>
            <a href="../ASESI/Table_asesi.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-search"></i> Kembali ke Pencarian
            </a>
        </div>
    <?php else: ?>
        
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> 
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message) && isset($asesor)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> 
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($asesor)): ?>
        <form method="post" action="" onsubmit="return validateForm()">
            <input type="hidden" name="id_asesor" value="<?php echo intval($id_asesor); ?>">
            <div class="form-section">
                <h3><i class="fas fa-id-card section-icon"></i> Informasi Pribadi</h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_reg" class="required">No Reg</label>
                            <input type="text" id="no_reg" name="no_reg" 
                                   value="<?php echo htmlspecialchars($asesor['no_reg'] ?? ''); ?>" 
                                   required maxlength="30" pattern="[0-30]*">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_asesor" class="required">Nama</label>
                            <input type="text" id="nama_asesor" name="nama_asesor" 
                                   value="<?php echo htmlspecialchars($asesor['nama_asesor'] ?? ''); ?>" 
                                   required maxlength="100">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jenis_kelamin" class="required">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" <?php echo (isset($asesor['jenis_kelamin']) && $asesor['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="Perempuan" <?php echo (isset($asesor['jenis_kelamin']) && $asesor['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alamat" class="required">Alamat</label>
                            <input type="text" id="alamat" name="alamat" 
                                   value="<?php echo htmlspecialchars($asesor['alamat'] ?? ''); ?>" >
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn-container">
                <div>
                    <a href="../BERANDA/UTAMA.php?page=../ASESOR/Table_asesor.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
                <div>
                    <button type="submit" name="update" class="btn btn-primary">
                        
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
        <?php endif; ?>
    <?php endif; ?>
</div>