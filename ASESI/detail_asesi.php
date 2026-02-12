<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
session_start();
}

include "../koneksi.php";

if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Asesor') {
    echo "<script>alert('Akses ditolak! Hanya Admin dan Asesor yang dapat mengakses.'); window.location.href='../LOGIN/login.php';</script>";
    exit;
}


$asesi_data = [];
$error = '';
$success = '';


if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_asesi = intval($_GET['id']);
    
    
    $query = "SELECT * FROM tb_asesi WHERE id_asesi = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_asesi);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $asesi_data = mysqli_fetch_assoc($result);
        } else {
            $error = "Data asesi tidak ditemukan.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "Terjadi kesalahan dalam mengambil data.";
    }
} else {
    $error = "ID asesi tidak valid.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Data Asesi</title>
    <style>
       
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        
        .detail-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
       
        .detail-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #007bff;
        }
        
        .detail-header h1 {
            font-size: 2.2em;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .detail-header p {
            font-size: 1.1em;
            color: #7f8c8d;
        }
        
       
        .user-info {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .user-info span {
            font-weight: 600;
            color: #2c3e50;
        }
        
       
        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-weight: 500;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        
        .detail-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        
        .detail-section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .detail-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .section-title {
            font-size: 1.4em;
            color: #007bff;
            margin-bottom: 15px;
            padding-left: 10px;
            border-left: 4px solid #007bff;
        }
        
       
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
            font-size: 0.95em;
        }
        
        .info-value {
            padding: 10px 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            font-size: 1.05em;
            color: #333;
            min-height: 45px;
            display: flex;
            align-items: center;
        }
        
        
        .photo-section {
            text-align: center;
            padding: 20px;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            background-color: #fafafa;
        }
        
        .photo-placeholder {
            width: 200px;
            height: 200px;
            margin: 0 auto 15px;
            border-radius: 10px;
            overflow: hidden;
            border: 3px solid #e9ecef;
        }
        
        .photo-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .no-photo {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e9ecef;
            color: #6c757d;
            font-size: 0.9em;
        }
        
       
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 25px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            min-width: 120px;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,123,255,0.3);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(108,117,125,0.3);
        }
        
        .btn-edit {
            background-color: #28a745;
            color: white;
        }
        
        .btn-edit:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(40,167,69,0.3);
        }
        
      
        @media (max-width: 768px) {
            .detail-container {
                padding: 0 15px;
            }
            
            .detail-header h1 {
                font-size: 1.8em;
            }
            
            .user-info {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .photo-placeholder {
                width: 150px;
                height: 150px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
            }
        }
        
     
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .detail-card {
            animation: fadeIn 0.5s ease-out;
        }
        
 
        .icon {
            margin-right: 8px;
        }
        
       
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
    <!-- <script>
        
        function goBack() {
            window.history.back();
        }
        
    </script> -->
</head>
<body>
    <div class="detail-container">
        <div class="detail-header">
            <h1>Detail Data Asesi</h1>
            <p>Informasi lengkap data asesi</p>
        </div>
        
        <div class="user-info">
            <div>
                Logged in sebagai: <span><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span> 
                (Role: <span><?php echo htmlspecialchars($_SESSION['role'] ?? ''); ?></span>)
            </div>
            <div>
                <a href="../BERANDA/UTAMA.php?page=../ASESI/Table_asesi.php" class="btn btn-secondary" style="padding: 8px 15px; font-size: 14px;">
                    ← Kembali ke Daftar
                </a>
            </div>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
                <br>
                <a href="../BERANDA/UTAMA.php?page=../ASESI/Table_asesi.php" class="btn btn-secondary" style="margin-top: 10px; padding: 8px 15px; font-size: 14px;">
                    Kembali ke Daftar Asesi
                </a>
            </div>
        <?php elseif (!empty($asesi_data)): ?>
            <div class="detail-card">
               
                <div class="detail-section">
                    <h2 class="section-title">Informasi Pribadi</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">ID Asesi</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['id_asesi']); ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">NIK</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['nik']); ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Nama Lengkap</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['nama_asesi']); ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Jenis Kelamin</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['jenis_kelamin'] ?? 'Tidak tercantum'); ?>
                                <?php 
                                // $jk = $asesi_data['jenis_kelamin'] ?? '';
                                // if ($jk == 'L') {
                                //     echo 'Laki-laki';
                                // } elseif ($jk == 'P') {
                                //     echo 'Perempuan';
                                // } else {
                                //     echo 'Tidak tercantum';
                                // }
                                ?>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Alamat</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['alamat_rumah'] ?? 'Tidak tercantum'); ?></div>
                        </div>
                    </div>
                </div>
                
               
                <div class="detail-section">
                    <h2 class="section-title">Kontak & Pendidikan</h2>
                    <di  class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['email'] ?? 'Tidak tercantum'); ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">No. Telepon rumah</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['phone_rumah'] ?? 'Tidak tercantum'); ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">No. Telepon kantor</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['phone_kantor'] ?? 'Tidak tercantum'); ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">No. hp</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['hp'] ?? 'Tidak tercantum'); ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">kode pos</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['kode_pos'] ?? 'Tidak tercantum'); ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">kebangsaan</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['kebangsaan'] ?? 'Tidak tercantum'); ?></div>
                        </div>
                        
                        
                        <div class="info-item">
                            <div class="info-label">Pendidikan</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['pendidikan'] ?? 'Tidak tercantum'); ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Nama Institusi</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['nama_institusi'] ?? 'Tidak tercantum'); ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">alamat institusi</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['alamat_institusi'] ?? 'Tidak tercantum'); ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">No. Telepon institusi</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['telp_institusi'] ?? 'Tidak tercantum'); ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">fax</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['fax'] ?? 'Tidak tercantum'); ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Email Institusi</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['email_institusi'] ?? 'Tidak tercantum'); ?></div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Informasi Tambahan -->
                <div class="detail-section">
                    <h2 class="section-title">Informasi Tambahan</h2>
                    <div class="info-grid">
                        
                        <?php if (isset($asesi_data['jabatan'])): ?>
                        <div class="info-item">
                            <div class="info-label">Jabatan</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['jabatan']); ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isset($asesi_data['skema_sertifikasi'])): ?>
                        <div class="info-item">
                            <div class="info-label">Skema Sertifikasi</div>
                            <div class="info-value"><?php echo htmlspecialchars($asesi_data['skema_sertifikasi']); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Tombol Aksi -->
                <div class="action-buttons">
                    <a href="UTAMA.php?page=../ASESI/edit.php&id=<?php echo $asesi_data['id_asesi']; ?>" 
                       class="btn btn-edit">
                         Edit Data
                    </a>
                    <button class="btn btn-secondary">
                        <a href="UTAMA.php?page=../ASESI/Table_asesi.php" style="color: white; text-decoration: none;">
                        ← Kembali
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- <script>
        // Tambahkan efek hover pada info-value
        document.querySelectorAll('.info-value').forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#e9ecef';
                this.style.borderColor = '#007bff';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '#f8f9fa';
                this.style.borderColor = '#e9ecef';
            });
        });
        
        // Auto-hide alert setelah 5 detik
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script> -->
</body>
</html>