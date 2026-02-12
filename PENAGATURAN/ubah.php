<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../LOGIN/login.php");
    exit();
}

include '../koneksi.php';

if (mysqli_connect_errno()) {
    die("Gagal koneksi ke database: " . mysqli_connect_error());
}

$message = '';
$message_type = ''; 
$user_data = [];


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $sql = "SELECT * FROM users WHERE id_user = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
        } else {
            $message = "Data user tidak ditemukan.";
            $message_type = 'error';
        }
        mysqli_stmt_close($stmt);
    }
} else {
    $message = "ID user tidak valid.";
    $message_type = 'error';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = intval($_POST['id_user']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);
    
  
    $errors = [];
    
    if (empty($username)) {
        $errors[] = "Username harus diisi";
    }
    
    if (empty($password)) {
        $errors[] = "Password harus diisi";
    }
    
    if (empty($role)) {
        $errors[] = "Role harus dipilih";
    }
    
    // Cek jika username sudah digunakan(kecuali yang lagi ngubah)
    $check_sql = "SELECT id_user FROM users WHERE username = ? AND id_user != ?";
    $check_stmt = mysqli_prepare($koneksi, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "si", $username, $id);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    
    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        $errors[] = "Username sudah digunakan oleh user lain";
    }
    mysqli_stmt_close($check_stmt);
    
    
    if (empty($errors)) {
        
        if (!empty($password) && $password !== $user_data['password']) {
            
            $password_hashed = $password; 
        } else {
            $password_hashed = $password;
        }
        
        $update_sql = "UPDATE users SET username = ?, password = ?, role = ? WHERE id_user = ?";
        $update_stmt = mysqli_prepare($koneksi, $update_sql);
        
        if ($update_stmt) {
            mysqli_stmt_bind_param($update_stmt, "sssi", $username, $password_hashed, $role, $id);
            
            if (mysqli_stmt_execute($update_stmt)) {
                $message = "Data user berhasil diperbarui!";
                $message_type = 'success';
                
                
                $user_data['username'] = $username;
                $user_data['password'] = $password;
                $user_data['role'] = $role;
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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data User</title>
      <style>
        .l-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            padding: 0;
            max-width: 800px;
            margin: 20px auto;
        }
        .header {
            padding: 30px 30px 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            border-bottom: 1px solid #ecf0f1;
        }
        .header i {
            font-size: 48px;
            color: #3498db;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 2em;
            margin: 0;
            font-weight: 600;
        }
        .header p {
            color: #7f8c8d;
            margin: 5px 0 0 0;
            font-size: 1em;
        }
        .user-info {
            background: #f8f9fa;
            padding: 15px 30px;
            margin: 0;
            border-bottom: 1px solid #ecf0f1;
            font-size: 0.9em;
        }
        .user-info span {
            font-weight: 600;
            color: #2c3e50;
        }
        .form-container {
            padding: 30px;
        }
        .form-group {
            margin-bottom: 20px;
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
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            background-color: #fafbfc;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            background-color: white;
        }
        .form-group input[type="password"] {
            font-family: 'Courier New', monospace;
        }
        .form-hint {
            display: block;
            margin-top: 5px;
            font-size: 12px;
            color: #7f8c8d;
        }
        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-primary {
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #2980b9, #1f639b);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }
        .btn-secondary {
            background: #ecf0f1;
            color: #2c3e50;
        }
        .btn-secondary:hover {
            background: #d5dbdb;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(236, 240, 241, 0.3);
        }
        .error-message {
            background: #fee;
            color: #c0392b;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 30px 0 30px;
            border-left: 4px solid #e74c3c;
        }
        .success-message {
            background: #efe;
            color: #27ae60;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 30px 0 30px;
            border-left: 4px solid #2ecc71;
        }
        .message {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 30px 0 30px;
            border-left: 4px solid;
        }
        .message.success {
            background: #efe;
            color: #27ae60;
            border-left-color: #2ecc71;
        }
        .message.error {
            background: #fee;
            color: #c0392b;
            border-left-color: #e74c3c;
        }
        select {
            appearance: none;
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 40px;
        }
        
        @media (max-width: 768px) {
            .l-container {
                margin: 10px;
                border-radius: 10px;
            }
            .header {
                padding: 20px;
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            .header i {
                font-size: 36px;
            }
            .header h1 {
                font-size: 1.5em;
            }
            .user-info {
                padding: 12px 20px;
            }
            .form-container {
                padding: 20px;
            }
            .btn-container {
                flex-direction: column;
            }
            .btn {
                justify-content: center;
            }
            .message {
                margin: 15px 20px 0 20px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="l-container">
        <div class="header">
            <i class="fas fa-user-edit"></i>
            <div>
                <h1>Ubah Data User</h1>
                <p>Perbarui informasi user sesuai kebutuhan</p>
            </div>
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
        
        <?php if (!empty($user_data)): ?>
            <div class="form-container">
                <form method="post" action="" id="editUserForm">
                    <input type="hidden" name="id_user" value="<?php echo $user_data['id_user']; ?>">
                    
                    <div class="form-group">
                        <label for="username" class="required">
                            <i class="fas fa-user"></i> Username
                        </label>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               value="<?php echo htmlspecialchars($user_data['username']); ?>"
                               required
                               maxlength="50">
                        <span class="form-hint">Username unik untuk login sistem</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="required">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <input type="text" 
                               id="password" 
                               name="password" 
                               value="<?php echo htmlspecialchars($user_data['password']); ?>"
                               required>
                        <span class="form-hint">Password untuk login user</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="role" class="required">
                            <i class="fas fa-user-tag"></i> Role
                        </label>
                        <select id="role" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="Admin" <?php echo ($user_data['role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="Asesor" <?php echo ($user_data['role'] == 'Asesor') ? 'selected' : ''; ?>>Asesor</option>
                            <option value="Asesi"  <?php echo ($user_data['role'] == 'Asesi') ? 'selected' : ''; ?>>Asesi</option>
                        </select>
                        <span class="form-hint">Hak akses user dalam sistem</span>
                    </div>
                    
                    <div class="btn-container">
                        <a href="../BERANDA/UTAMA.php?page=../MANAGEMENT/tampil2.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" name="update" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        <?php elseif (empty($message)): ?>
            <!-- <div class="message error" style="margin: 30px;">
                <i class="fas fa-exclamation-triangle"></i> 
                Data user tidak ditemukan. Silakan pilih user yang valid.
                <br><br>
                <a href="../BERANDA/UTAMA.php?page=../MANAGEMENT/tampil2.php" class="btn btn-secondary" style="margin-top: 10px;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar User
                </a>
            </div> -->
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
        
     
        document.getElementById('editUserForm')?.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            const role = document.getElementById('role').value;
            
            let errors = [];
            
            if (!username) {
                errors.push('Username harus diisi');
            }
            
            if (!password) {
                errors.push('Password harus diisi');
            }
            
            if (!role) {
                errors.push('Role harus dipilih');
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                alert('Harap perbaiki kesalahan berikut:\n\n' + errors.join('\n'));
                return false;
            }
        });
    </script>
</body>
</html>