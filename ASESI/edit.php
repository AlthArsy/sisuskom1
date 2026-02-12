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

$id_asesi = isset($_GET['id']) ? intval($_GET['id']) : 0;
$success_message = '';
$error_message = '';

if ($id_asesi > 0) {
    $query = "SELECT * FROM tb_asesi WHERE id_asesi = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_asesi);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $asesi = mysqli_fetch_assoc($result);
    } else {
        $error_message = "Data asesi tidak ditemukan!";
    }
    mysqli_stmt_close($stmt);
} else {
    $error_message = "ID asesi tidak valid!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id_asesi = isset($_POST['id_asesi']) ? intval($_POST['id_asesi']) : $id_asesi;
    $nama_asesi = mysqli_real_escape_string($koneksi, $_POST['nama_asesi']);
    $nik = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $kebangsaan = mysqli_real_escape_string($koneksi, $_POST['kebangsaan']);
    $alamat_rumah = mysqli_real_escape_string($koneksi, $_POST['alamat_rumah']);
    $kode_pos = mysqli_real_escape_string($koneksi, $_POST['kode_pos']);
    $phone_rumah = mysqli_real_escape_string($koneksi, $_POST['phone_rumah']);
    $phone_kantor = mysqli_real_escape_string($koneksi, $_POST['phone_kantor']);
    $hp = mysqli_real_escape_string($koneksi, $_POST['hp']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $pendidikan = mysqli_real_escape_string($koneksi, $_POST['pendidikan']);
    $nama_institusi = mysqli_real_escape_string($koneksi, $_POST['nama_institusi']);
    $jabatan = mysqli_real_escape_string($koneksi, $_POST['jabatan']);
    $alamat_institusi = mysqli_real_escape_string($koneksi, $_POST['alamat_institusi']);
    $kode_pos_institusi = mysqli_real_escape_string($koneksi, $_POST['kode_pos_institusi']);
    $telp_institusi = mysqli_real_escape_string($koneksi, $_POST['telp_institusi']);
    $fax = mysqli_real_escape_string($koneksi, $_POST['fax']);
    $email_institusi = mysqli_real_escape_string($koneksi, $_POST['email_institusi']);
    
    $errors = [];

    
    $email_institusi_max = 50;
    if (!empty($email_institusi)) {
        if (!filter_var($email_institusi, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email institusi tidak valid!";
        } elseif (strlen($email_institusi) > $email_institusi_max) {
            $errors[] = "Email institusi maksimal {$email_institusi_max} karakter!";
        }
    }
    
    if (empty($nama_asesi)) {
        $errors[] = "Nama asesi harus diisi!";
    }
    
    if (empty($nik)) {
        $errors[] = "NIK harus diisi!";
    } elseif (!is_numeric($nik)) {
        $errors[] = "NIK harus berupa angka!";
    } elseif (strlen($nik) > 16) {
        $errors[] = "NIK maksimal 16 digit!";
    }
    
    if (empty($jenis_kelamin)) {
        $errors[] = "Jenis kelamin harus dipilih!";
    }
    
    if (empty($kebangsaan)) {
        $errors[] = "Kebangsaan harus diisi!";
    }
    
    if (empty($alamat_rumah)) {
        $errors[] = "Alamat rumah harus diisi!";
    }
    
    if (empty($kode_pos)) {
        $errors[] = "Kode pos harus diisi!";
    } elseif (!is_numeric($kode_pos)) {
        $errors[] = "Kode pos harus berupa angka!";
    }
    
    if (empty($hp)) {
        $errors[] = "Nomor HP harus diisi!";
    } elseif (strlen($hp) > 20) {
        $errors[] = "Nomor HP maksimal 20 karakter!";
    }
    
    if (empty($email)) {
        $errors[] = "Email harus diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid!";
    }
    
    if (empty($nama_institusi)) {
        $errors[] = "Nama institusi harus diisi!";
    }
    
    if (empty($jabatan)) {
        $errors[] = "Jabatan harus diisi!";
    }
    
    if (empty($alamat_institusi)) {
        $errors[] = "Alamat institusi harus diisi!";
    }
    
    if (empty($kode_pos_institusi)) {
        $errors[] = "Kode pos institusi harus diisi!";
    } elseif (!is_numeric($kode_pos_institusi)) {
        $errors[] = "Kode pos institusi harus berupa angka!";
    }
    
    if (empty($errors)) {
        $query = "UPDATE tb_asesi SET 
                  nama_asesi = ?,
                  nik = ?,
                  jenis_kelamin = ?,
                  kebangsaan = ?,
                  alamat_rumah = ?,
                  kode_pos = ?,
                  phone_rumah = ?,
                  phone_kantor = ?,
                  hp = ?,
                  email = ?,
                  pendidikan = ?,
                  nama_institusi = ?,
                  jabatan = ?,
                  alamat_institusi = ?,
                  kode_pos_institusi = ?,
                  telp_institusi = ?,
                  fax = ?,
                  email_institusi = ?
                  WHERE id_asesi = ?";
        
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssssssssssssi", 
            $nama_asesi, $nik, $jenis_kelamin, $kebangsaan,
            $alamat_rumah, $kode_pos, $phone_rumah, $phone_kantor,
            $hp, $email, $pendidikan, $nama_institusi, $jabatan,
            $alamat_institusi, $kode_pos_institusi, $telp_institusi,
            $fax, $email_institusi, $id_asesi);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Data asesi berhasil diperbarui!";
            
            $query = "SELECT * FROM tb_asesi WHERE id_asesi = ?";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "i", $id_asesi);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $asesi = mysqli_fetch_assoc($result);
        } else {
            $error_message = "Gagal memperbarui data: " . mysqli_error($koneksi);
        }
        mysqli_stmt_close($stmt);
    } else {
        $error_message = implode("<br>", $errors);
    }
}


if (empty($asesi) && empty($error_message)) {
    $error_message = "Data asesi tidak ditemukan!";
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

.form-section h3 {
    color: #2d3748;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #cbd5e0;
}


.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #4a5568;
}

label.required::after {
    content: " *";
    color: #e53e3e;
}

input[type="text"],
input[type="email"],
input[type="number"],
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
    
    .col-md-6,
    .col-md-4 {
        flex: 0 0 100%;
        max-width: 100%;
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
<script>
    function validateForm(){
        var hp = document.getElementById('hp').value.trim();
        if (hp.length > 20) {
            alert('Nomor HP maksimal 20 karakter');
            return false;
        }
            return true;
        }
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<div class="edit-container">
    <div class="edit-header">
        <h1><i class="fas fa-user-edit"></i> Edit Data Asesi</h1>
        <p>Perbarui informasi data asesi sesuai dengan struktur database</p>
    </div>
    
    <div class="user-info">
        <div>
            <i class="fas fa-user"></i> Logged in sebagai: 
            <span><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span> 
            (<span><?php echo htmlspecialchars($_SESSION['role'] ?? ''); ?></span>)
        </div>
        <div>
            <a href="../BERANDA/UTAMA.php?page=../ASESI/Table_asesi.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali ke Pencarian
            </a>
        </div>
    </div>
    
    <?php if (!empty($error_message) && !isset($asesi)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> 
            <?php echo $error_message; ?>
            <br><br>
            <a href="../BERANDA/UTAMA.php?page=../ASESI/Table_asesi.php" class="btn btn-secondary btn-sm">
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
        
        <?php if (!empty($error_message) && isset($asesi)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> 
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($asesi)): ?>
        <form method="post" action="" onsubmit="return validateForm()">
            <input type="hidden" name="id_asesi" value="<?php echo intval($id_asesi); ?>">
            <div class="form-section">
                <h3><i class="fas fa-id-card section-icon"></i> Informasi Pribadi</h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_asesi" class="required">Nama Lengkap</label>
                            <input type="text" id="nama_asesi" name="nama_asesi" 
                                   value="<?php echo htmlspecialchars($asesi['nama_asesi'] ?? ''); ?>" 
                                   required maxlength="100">
                            <small>Maksimal 100 karakter</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nik" class="required">NIK</label>
                            <input type="text" id="nik" name="nik" 
                                   value="<?php echo htmlspecialchars($asesi['nik'] ?? ''); ?>" 
                                   required maxlength="16" pattern="[0-9]*">
                            <small>16 digit angka</small>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jenis_kelamin" class="required">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" <?php echo (isset($asesi['jenis_kelamin']) && $asesi['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="Perempuan" <?php echo (isset($asesi['jenis_kelamin']) && $asesi['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kebangsaan" class="required">Kebangsaan</label>
                            <input type="text" id="kebangsaan" name="kebangsaan" 
                                   value="<?php echo htmlspecialchars($asesi['kebangsaan'] ?? ''); ?>" 
                                   required maxlength="20">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3><i class="fas fa-home section-icon"></i> Alamat Rumah</h3>
                
                <div class="form-group">
                    <label for="alamat_rumah" class="required">Alamat Rumah</label>
                    <textarea id="alamat_rumah" name="alamat_rumah" rows="3" required maxlength="100"><?php echo htmlspecialchars($asesi['alamat_rumah'] ?? ''); ?></textarea>
                    <small>Maksimal 100 karakter</small>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode_pos" class="required">Kode Pos</label>
                            <input type="text" id="kode_pos" name="kode_pos" 
                                   value="<?php echo htmlspecialchars($asesi['kode_pos'] ?? ''); ?>" 
                                   required maxlength="6" pattern="[0-9]*">
                            <small>6 digit angka</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3><i class="fas fa-phone-alt section-icon"></i> Informasi Kontak</h3>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone_rumah">Telepon Rumah</label>
                            <input type="text" id="phone_rumah" name="phone_rumah" 
                                   value="<?php echo htmlspecialchars($asesi['phone_rumah'] ?? ''); ?>" 
                                   maxlength="20">
                            <small>Opsional</small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone_kantor">Telepon Kantor</label>
                            <input type="text" id="phone_kantor" name="phone_kantor" 
                                   value="<?php echo htmlspecialchars($asesi['phone_kantor'] ?? ''); ?>" 
                                   maxlength="20">
                            <small>Opsional</small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="hp" class="required">No. HP/WhatsApp</label>
                            <input type="text" id="hp" name="hp" 
                                   value="<?php echo htmlspecialchars($asesi['hp'] ?? ''); ?>" 
                                   required maxlength="20">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="required">Email Pribadi</label>
                            <input type="email" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($asesi['email'] ?? ''); ?>" 
                                   required maxlength="50">
                            <small>Maksimal 50 karakter</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pendidikan">Pendidikan Terakhir</label>
                            <input type="text" id="pendidikan" name="pendidikan" 
                                   value="<?php echo htmlspecialchars($asesi['pendidikan'] ?? ''); ?>" 
                                   maxlength="50">
                            <small>Opsional</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3><i class="fas fa-university section-icon"></i> Informasi Institusi</h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_institusi" class="required">Nama Institusi</label>
                            <input type="text" id="nama_institusi" name="nama_institusi" 
                                   value="<?php echo htmlspecialchars($asesi['nama_institusi'] ?? ''); ?>" 
                                   required maxlength="30">
                            <small>Maksimal 30 karakter</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jabatan" class="required">Jabatan</label>
                            <input type="text" id="jabatan" name="jabatan" 
                                   value="<?php echo htmlspecialchars($asesi['jabatan'] ?? ''); ?>" 
                                   required maxlength="17">
                            <small>Maksimal 17 karakter</small>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="alamat_institusi" class="required">Alamat Institusi</label>
                    <textarea id="alamat_institusi" name="alamat_institusi" rows="3" required maxlength="100"><?php echo htmlspecialchars($asesi['alamat_institusi'] ?? ''); ?></textarea>
                    <small>Maksimal 100 karakter</small>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode_pos_institusi" class="required">Kode Pos Institusi</label>
                            <input type="text" id="kode_pos_institusi" name="kode_pos_institusi" 
                                   value="<?php echo htmlspecialchars($asesi['kode_pos_institusi'] ?? ''); ?>" 
                                   required maxlength="6" pattern="[0-9]*">
                            <small>6 digit angka</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telp_institusi">Telepon Institusi</label>
                            <input type="text" id="telp_institusi" name="telp_institusi" 
                                   value="<?php echo htmlspecialchars($asesi['telp_institusi'] ?? ''); ?>" 
                                   maxlength="20">
                            <small>Opsional</small>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fax">Fax Institusi</label>
                            <input type="text" id="fax" name="fax" 
                                   value="<?php echo htmlspecialchars($asesi['fax'] ?? ''); ?>" 
                                   maxlength="20">
                            <small>Opsional</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email_institusi">Email Institusi</label>
                            <input type="email" id="email_institusi" name="email_institusi" 
                                   value="<?php echo htmlspecialchars($asesi['email_institusi'] ?? ''); ?>" 
                                   maxlength="50">
                            <small>Maksimal 50 karakter</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="btn-container">
                <div>
                    <a href="../BERANDA/UTAMA.php?page=../ASESI/Table_asesi.php" class="btn btn-secondary">
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
