<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Asesor'])) {
    header("Location: ../LOGIN/login.php");
    exit();
}

include '../koneksi.php';

if (mysqli_connect_errno()) {
    die("Gagal koneksi ke database: " . mysqli_connect_error());
}

$message = '';
$message_type = ''; 
$skema_data = [];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $sql = "SELECT s.*, a.nama_asesor 
            FROM tb_skema s 
            LEFT JOIN tb_asesor a ON s.id_asesor = a.id_asesor 
            WHERE s.id_skema = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $skema_data = mysqli_fetch_assoc($result);
            
            if ($_SESSION['role'] === 'Asesor') {
                $id_asesor_login = $_SESSION['id_referensi'] ?? 0;
                
                if ($skema_data['id_asesor'] != $id_asesor_login) {
                    $message = "Anda tidak memiliki akses untuk mengubah skema ini.";
                    $message_type = 'error';
                    $skema_data = [];
                }
            }
        } else {
            $message = "Data skema tidak ditemukan.";
            $message_type = 'error';
        }
        mysqli_stmt_close($stmt);
    }
} else {
    $message = "ID skema tidak valid.";
    $message_type = 'error';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nomor_skema = mysqli_real_escape_string($koneksi, trim($_POST['nomor_skema']));
    $judul_skema = mysqli_real_escape_string($koneksi, trim($_POST['judul_skema']));
    $standar_kompetensi = mysqli_real_escape_string($koneksi, trim($_POST['standar_kompetensi_kerja']));
    
    $errors = [];
    
    if (empty($nomor_skema)) {
        $errors[] = "Nomor skema harus diisi";
    }
    
    if (empty($judul_skema)) {
        $errors[] = "Judul skema harus diisi";
    }
    
    if (empty($standar_kompetensi)) {
        $errors[] = "Standar kompetensi harus diisi";
    }
    
    $check_sql = "SELECT id_skema FROM tb_skema WHERE nomor_skema = ? AND id_skema != ?";
    $check_stmt = mysqli_prepare($koneksi, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "si", $nomor_skema, $id);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    
    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        $errors[] = "Nomor skema sudah digunakan";
    }
    mysqli_stmt_close($check_stmt);
    
    if (empty($errors)) {
        $update_sql = "UPDATE tb_skema SET nomor_skema = ?, judul_skema = ?, standar_kompetensi_kerja = ? WHERE id_skema = ?";
        $update_stmt = mysqli_prepare($koneksi, $update_sql);
        
        if ($update_stmt) {
            mysqli_stmt_bind_param($update_stmt, "sssi", $nomor_skema, $judul_skema, $standar_kompetensi, $id);
            
            if (mysqli_stmt_execute($update_stmt)) {
                $_SESSION['pesan'] = "Data skema berhasil diperbarui!";
                $_SESSION['tipe'] = 'success';
                header("Location: ../BERANDA/UTAMA.php?page=../SKEMA/list_skema.php");
                exit();
            } else {
                $message = "Gagal memperbarui data: " . mysqli_error($koneksi);
                $message_type = 'error';
            }
            mysqli_stmt_close($update_stmt);
        }
    } else {
        $message = implode("<br>", $errors);
        $message_type = 'error';
    }
}
?>

<style>
    .ubah-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .ubah-header {
        background: linear-gradient(135deg, #24365e 0%, #14305c 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 10px 10px 0 0;
        text-align: center;
    }
    
    .ubah-header h1 {
        font-size: 1.8em;
        margin-bottom: 8px;
    }
    
    .ubah-header p {
        opacity: 0.9;
        font-size: 0.95em;
    }
    
    .user-info {
        background: #e8f0fe;
        padding: 15px 20px;
        margin: 20px 0;
        border-radius: 8px;
        border-left: 4px solid #24365e;
    }
    
    .user-info span {
        font-weight: 600;
        color: #14305c;
    }
    
    .form-container {
        background: white;
        padding: 30px;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }
    
    .message {
        padding: 15px 20px;
        margin-bottom: 25px;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .message.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .message.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .form-group label.required::after {
        content: " *";
        color: #e74c3c;
    }
    
    .form-control {
        width: 100%;
        padding: 14px;
        border: 1px solid #dce1e6;
        border-radius: 6px;
        font-size: 15px;
        transition: all 0.3s ease;
        background-color: #fafbfc;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #4186e0;
        box-shadow: 0 0 0 3px rgba(65, 134, 224, 0.1);
        background-color: white;
    }
    
    .form-control[readonly] {
        background-color: #f5f7fa;
        color: #7f8c8d;
        cursor: not-allowed;
    }
    
    textarea.form-control {
        min-height: 100px;
        resize: vertical;
        font-family: inherit;
    }
    
    .form-hint {
        display: block;
        margin-top: 8px;
        font-size: 13px;
        color: #7f8c8d;
    }
    
    .button-group {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        padding-top: 25px;
        border-top: 1px solid #eee;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 14px 28px;
        border: none;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        min-width: 140px;
    }
    
    .btn i {
        margin-right: 8px;
    }
    
    .btn-primary {
        background-color: #4186e0;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #2761ba;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(65, 134, 224, 0.3);
    }
    
    .btn-secondary {
        background-color: #95a5a6;
        color: white;
    }
    
    .btn-secondary:hover {
        background-color: #7f8c8d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(149, 165, 166, 0.3);
    }
    
    @media (max-width: 768px) {
        body {
            padding: 15px;
        }
        
        .header {
            padding: 20px 15px;
        }
        
        .form-container {
            padding: 20px;
        }
        
        .button-group {
            flex-direction: column;
            gap: 15px;
        }
        
        .btn {
            width: 100%;
        }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .form-container {
        animation: fadeIn 0.5s ease-out;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<div class="ubah-container">
    <div class="ubah-header">
        <h1><i class="fas fa-edit"></i> Ubah Data Skema</h1>
        <p>Perbarui informasi skema sertifikasi</p>
    </div>
    
    <div class="user-info">
        <i class="fas fa-user-circle"></i> Logged in sebagai: 
        <span><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span> 
        (Role: <span><?php echo htmlspecialchars($_SESSION['role'] ?? ''); ?></span>)
    </div>
    
    <?php if (!empty($message)): ?>
        <div class="message <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($skema_data)): ?>
        <div class="form-container">
            <form method="post" action="" id="editSkemaForm">
                <input type="hidden" name="id" value="<?php echo $skema_data['id_skema']; ?>">
                
                <div class="form-group">
                    <label for="nomor_skema" class="required">
                        <i class="fas fa-hashtag"></i> Nomor Skema
                    </label>
                    <input type="text" 
                           id="nomor_skema" 
                           name="nomor_skema" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($skema_data['nomor_skema']); ?>"
                           required
                           maxlength="100">
                    <span class="form-hint">Nomor unik identifikasi skema</span>
                </div>
                
                <div class="form-group">
                    <label for="judul_skema" class="required">
                        <i class="fas fa-heading"></i> Judul Skema
                    </label>
                    <input type="text" 
                           id="judul_skema" 
                           name="judul_skema" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($skema_data['judul_skema']); ?>"
                           required
                           maxlength="100">
                    <span class="form-hint">Nama lengkap skema sertifikasi</span>
                </div>
                
                <div class="form-group">
                    <label for="standar_kompetensi_kerja" class="required">
                        <i class="fas fa-clipboard-list"></i> Standar Kompetensi Kerja
                    </label>
                    <textarea 
                        id="standar_kompetensi_kerja" 
                        name="standar_kompetensi_kerja" 
                        class="form-control" 
                        required><?php echo htmlspecialchars($skema_data['standar_kompetensi_kerja']); ?></textarea>
                    <span class="form-hint">Deskripsi standar kompetensi yang digunakan</span>
                </div>
                
                <div class="form-group">
                    <label for="nama_asesor">
                        <i class="fas fa-user-tie"></i> Asesor
                    </label>
                    <input type="text" 
                           id="nama_asesor" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($skema_data['nama_asesor'] ?? '-'); ?>"
                           readonly>
                    <span class="form-hint">Asesor yang bertanggung jawab (tidak dapat diubah)</span>
                </div>
                
                <div class="button-group">
                    <a href="../BERANDA/UTAMA.php?page=../SKEMA/list_skema.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" name="update" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    <?php elseif (empty($message)): ?>
        <div class="message error">
            <i class="fas fa-exclamation-triangle"></i> 
            Data skema tidak ditemukan. Silakan pilih skema yang valid.
            <br><br>
            <a href="../BERANDA/UTAMA.php?page=../SKEMA/list_skema.php" class="btn btn-secondary" style="padding: 10px 20px; display: inline-block;">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Skema
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
    setTimeout(function() {
        const messages = document.querySelectorAll('.message');
        messages.forEach(message => {
            message.style.opacity = '0';
            message.style.transition = 'opacity 0.5s ease';
            setTimeout(() => message.remove(), 500);
        });
    }, 5000);
    
    document.getElementById('editSkemaForm')?.addEventListener('submit', function(e) {
        const nomor_skema = document.getElementById('nomor_skema').value.trim();
        const judul_skema = document.getElementById('judul_skema').value.trim();
        const standar = document.getElementById('standar_kompetensi_kerja').value.trim();
        
        let errors = [];
        
        if (!nomor_skema) {
            errors.push('Nomor skema harus diisi');
        }
        
        if (!judul_skema) {
            errors.push('Judul skema harus diisi');
        }
        
        if (!standar) {
            errors.push('Standar kompetensi harus diisi');
        }
        
        if (errors.length > 0) {
            e.preventDefault();
            alert('Harap perbaiki kesalahan berikut:\n\n' + errors.join('\n'));
            return false;
        }
    });
</script>