<?php
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

define('MAX_UNIT_PER_SKEMA', 10);

$count_sql = "SELECT COUNT(*) as total FROM tb_unit_kompetensi WHERE id_skema = ?";
$count_stmt = mysqli_prepare($koneksi, $count_sql);
mysqli_stmt_bind_param($count_stmt, "i", $id_skema);
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt);
$count_data = mysqli_fetch_assoc($count_result);
$current_unit_count = $count_data['total'];
mysqli_stmt_close($count_stmt);

$is_max_reached = ($current_unit_count >= MAX_UNIT_PER_SKEMA);

$message = '';
$message_type = '';
$skema_data = [];

if (isset($_GET['id_skema'])) {
    $id_skema = intval($_GET['id_skema']);
    
    $sql = "SELECT * FROM tb_skema WHERE id_skema = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_skema);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $skema_data = mysqli_fetch_assoc($result);
        } else {
            $message = "Data skema tidak ditemukan.";
            $message_type = 'error';
        }
        mysqli_stmt_close($stmt);
    }
} else {
    header("Location: ../BERANDA/UTAMA.php?page=../SKEMA/list_skema.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $id_skema = intval($_POST['id_skema']);
    $kode_unit = $_POST['kode_unit'] ?? [];
    $judul_unit = $_POST['judul_unit'] ?? [];
    
    $errors = [];
    $success_count = 0;
    
    $has_data = false;
    foreach ($kode_unit as $index => $kode) {
        if (!empty(trim($kode)) || !empty(trim($judul_unit[$index] ?? ''))) {
            $has_data = true;
            break;
        }
    }
    
    if (!$has_data) {
        $errors[] = "Minimal harus menambahkan satu unit kompetensi";
    }
    
    if (empty($errors)) {
        foreach ($kode_unit as $index => $kode) {
            $kode = trim($kode);
            $judul = trim($judul_unit[$index] ?? '');
            
            if (empty($kode) && empty($judul)) {
                continue;
            }
            
            if (empty($kode) || empty($judul)) {
                $errors[] = "Unit #" . ($index + 1) . ": Kode dan Judul  id_unit harus diisi"; 
                continue;
            }
            
            $check_sql = "SELECT id_unit FROM tb_unit_kompetensi WHERE id_skema = ? AND kode_unit = ?";
            $check_stmt = mysqli_prepare($koneksi, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "is", $id_skema, $kode);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            
            if (mysqli_stmt_num_rows($check_stmt) > 0) {
                $errors[] = "Kode unit '$kode' sudah ada dalam skema ini";
                mysqli_stmt_close($check_stmt);
                continue;
            }
            mysqli_stmt_close($check_stmt);
            
            $insert_sql = "INSERT INTO tb_unit_kompetensi (id_skema, kode_unit, judul_unit) VALUES (?, ?, ?)";
            $insert_stmt = mysqli_prepare($koneksi, $insert_sql);
            
            if ($insert_stmt) {
                mysqli_stmt_bind_param($insert_stmt, "iss", $id_skema, $kode, $judul);
                
                if (mysqli_stmt_execute($insert_stmt)) {
                    $success_count++;
                } else {
                    $errors[] = "Gagal menyimpan unit '$kode': " . mysqli_error($koneksi);
                }
                mysqli_stmt_close($insert_stmt);
            }
        }
        
        if ($success_count > 0) {
            $_SESSION['pesan'] = "$success_count unit kompetensi berhasil ditambahkan!";
            $_SESSION['tipe'] = 'success';
            header("Location: ../BERANDA/UTAMA.php?page=../SKEMA/list_skema.php");
            exit();
        }
    }
    
    if (!empty($errors)) {
        $message = implode("<br>", $errors);
        $message_type = 'error';
    }
}
?>
<style>  
    .unit-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .unit-header {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 10px 10px 0 0;
        text-align: center;
    }
    
    .unit-header h1 {
        font-size: 1.8em;
        margin-bottom: 8px;
    }
    
    .unit-header p {
        opacity: 0.9;
        font-size: 0.95em;
    }
    
    .skema-info {
        background: #e8f5e9;
        padding: 20px;
        margin: 20px 0;
        border-radius: 8px;
        border-left: 4px solid #28a745;
    }
    
    .skema-info h3 {
        color: #1b5e20;
        margin-bottom: 10px;
    }
    
    .skema-info p {
        margin: 5px 0;
        color: #2e7d32;
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
    
    .unit-container {
        margin-bottom: 30px;
    }
    
    .unit-item {
        background: #f8f9fa;
        padding: 20px;
        margin-bottom: 15px;
        border-radius: 8px;
        border: 2px solid #e9ecef;
        position: relative;
    }
    
    .unit-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .unit-number {
        font-weight: 600;
        color: #28a745;
        font-size: 1.1em;
    }
    
    .btn-remove {
        background: #dc3545;
        color: white;
        border: none;
        padding: 5px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.3s ease;
    }
    
    .btn-remove:hover {
        background: #c82333;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .form-group {
        margin-bottom: 0;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .form-group label.required::after {
        content: " *";
        color: #e74c3c;
    }
    
    .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #dce1e6;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s ease;
        background-color: #fafbfc;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #28a745;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        background-color: white;
    }
    
    .form-hint {
        display: block;
        margin-top: 5px;
        font-size: 12px;
        color: #7f8c8d;
    }
    
    .btn-add-more {
        display: inline-flex;
        align-items: center;
        padding: 12px 24px;
        background: #17a2b8;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    
    .btn-add-more:hover {
        background: #138496;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
    }
    
    .btn-add-more i {
        margin-right: 8px;
    }
    
    .button-group {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
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
        background-color: #28a745;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
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
        
        .form-row {
            grid-template-columns: 1fr;
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
<div class="unit-container">
<div class="unit-header">
    <h1>Tambah Unit Kompetensi</h1>
    <p>Tambahkan unit kompetensi untuk skema sertifikasi</p>
</div>

<?php if (!empty($skema_data)): ?>
    <div class="skema-info">
        <h3><i class="fas fa-certificate"></i> Informasi Skema</h3>
            <p><strong>Nomor Skema:</strong> <?php echo htmlspecialchars($skema_data['nomor_skema']); ?></p>
            <p><strong>Judul Skema:</strong> <?php echo htmlspecialchars($skema_data['judul_skema']); ?></p>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($message)): ?>
        <div class="message <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($skema_data)): ?>
        <div class="form-container">
            <form method="post" action="" id="formUnit">
                <input type="hidden" name="id_skema" value="<?php echo $skema_data['id_skema']; ?>">
                
                <div class="unit-container" id="unitContainer">
                    <div class="unit-item" data-unit="1">
                        <div class="unit-item-header">
                            <span class="unit-number"><i class="fas fa-list-ol"></i> Unit #1</span>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="kode_unit_1" class="required">
                                    <i class="fas fa-barcode"></i> Kode Unit
                                </label>
                                <input type="text" 
                                       id="kode_unit_1" 
                                       name="kode_unit[]" 
                                       class="form-control" 
                                       placeholder="Contoh: J.62PUS02.001.1"
                                       maxlength="100">
                                <span class="form-hint">Kode unik unit kompetensi</span>
                            </div>
                            <div class="form-group">
                                <label for="judul_unit_1" class="required">
                                    <i class="fas fa-heading"></i> Judul Unit
                                </label>
                                <input type="text" 
                                       id="judul_unit_1" 
                                       name="judul_unit[]" 
                                       class="form-control" 
                                       placeholder="Contoh: Melakukan Komunikasi di Tempat Kerja">
                                <span class="form-hint">Nama lengkap unit kompetensi</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="button" class="btn-add-more" onclick="addUnit()">
                    <i class="fas fa-plus"></i> Tambah Unit Lagi
                </button>
                
                <div class="button-group">
                    <a href="../BERANDA/UTAMA.php?page=../SKEMA/list_skema.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" name="simpan" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Semua Unit
                    </button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="message error">
            <i class="fas fa-exclamation-triangle"></i> 
            Data skema tidak ditemukan.
            <br><br>
            <a href="../BERANDA/UTAMA.php?page=../SKEMA/list_skema.php" class="btn btn-secondary" style="padding: 10px 20px; display: inline-block;">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Skema
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
    let unitCount = 1;
    
    function addUnit() {
        unitCount++;
        const container = document.getElementById('unitContainer');
        
        const unitHtml = `
            <div class="unit-item" data-unit="${unitCount}">
                <div class="unit-item-header">
                    <span class="unit-number"><i class="fas fa-list-ol"></i> Unit #${unitCount}</span>
                    <button type="button" class="btn-remove" onclick="removeUnit(this)">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="required">
                            <i class="fas fa-barcode"></i> Kode Unit
                        </label>
                        <input type="text" 
                               name="kode_unit[]" 
                               class="form-control" 
                               placeholder="Contoh: J.62PUS02.001.1"
                               maxlength="100">
                        <span class="form-hint">Kode unik unit kompetensi</span>
                    </div>
                    <div class="form-group">
                        <label class="required">
                            <i class="fas fa-heading"></i> Judul Unit
                        </label>
                        <input type="text" 
                               name="judul_unit[]" 
                               class="form-control" 
                               placeholder="Contoh: Melakukan Komunikasi di Tempat Kerja">
                        <span class="form-hint">Nama lengkap unit kompetensi</span>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', unitHtml);
    }
    
    function removeUnit(button) {
        const unitItem = button.closest('.unit-item');
        unitItem.remove();
        
        const units = document.querySelectorAll('.unit-item');
        units.forEach((unit, index) => {
            const number = index + 1;
            unit.querySelector('.unit-number').innerHTML = `<i class="fas fa-list-ol"></i> Unit #${number}`;
        });
        
        unitCount = units.length;
    }
    
    setTimeout(function() {
        const messages = document.querySelectorAll('.message');
        messages.forEach(message => {
            message.style.opacity = '0';
            message.style.transition = 'opacity 0.5s ease';
            setTimeout(() => message.remove(), 500);
        });
    }, 5000);
</script>