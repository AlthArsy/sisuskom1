<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../LOGIN/login.php");
    exit();
}

include '../koneksi.php';

if (mysqli_connect_errno()) {
    die("Gagal koneksi ke database: " . mysqli_error($koneksi));
}

$message = '';
$message_type = ''; 


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
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
    
    
    if (strlen($username) > 50) {
        $errors[] = "Username maksimal 50 karakter";
    }
    
    if (strlen($password) > 255) {
        $errors[] = "Password terlalu panjang";
    }
    
    
    $check_sql = "SELECT id_user FROM users WHERE username = ?";
    $check_stmt = mysqli_prepare($koneksi, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $username);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    
    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        $errors[] = "Username sudah digunakan, silakan pilih username lain";
    }
    mysqli_stmt_close($check_stmt);
    
    
    if (empty($errors)) {

        // $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $password_hashed = $password;
        
        $allowed_roles = ['Admin', 'Asesor', 'Asesi'];
        // if (!in_array($role, $allowed_roles)) {
        //     // Coba lowercase jika uppercase error
        //     $role_lower = strtolower($role);
        //     if (in_array($role_lower, array_map('strtolower', $allowed_roles))) {
        //         $role = ucfirst($role_lower);
        //     } else {
        //         $errors[] = "Role tidak valid. Pilih antara: Admin, Asesor, atau Asesi";
        //     }
        // }
        
        if (empty($errors)) {
            $insert_sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
            $insert_stmt = mysqli_prepare($koneksi, $insert_sql);
            
            if ($insert_stmt) {
                mysqli_stmt_bind_param($insert_stmt, "sss", $username, $password_hashed, $role);
                
                try {
                    if (mysqli_stmt_execute($insert_stmt)) {
                        $message = "User baru berhasil ditambahkan!";
                        $message_type = 'success';
                        
                        
                        $_POST = [];
                    }
                } catch (mysqli_sql_exception $e) {
                   
                    if (strpos($e->getMessage(), 'Data truncated for column') !== false) {
                        $message = "Error: Nilai role tidak valid untuk database. Silakan pilih role yang sesuai.";
                    } else {
                        $message = "Gagal menambahkan user: " . $e->getMessage();
                    }
                    $message_type = 'error';
                }
                mysqli_stmt_close($insert_stmt);
            } else {
                $message = "Gagal mempersiapkan statement: " . mysqli_error($koneksi);
                $message_type = 'error';
            }
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
    <title>Tambah User Baru</title>
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
            color: #2ecc71;
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
            background: linear-gradient(45deg, #2ecc71, #27ae60);
            color: white;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #27ae60, #219653);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
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
            <i class="fas fa-user-plus"></i>
            <div>
                <h1>Tambah User Baru</h1>
                <p>Tambahkan user baru ke dalam sistem</p>
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
        
        <div class="form-container">
            <form method="post" action="" id="tambahUserForm">
                <div class="form-group">
                    <label for="username" class="required">
                        <i class="fas fa-user"></i> Username
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                           required
                           maxlength="50"
                           placeholder="Masukkan username">
                    <span class="form-hint">Username harus unik dan maksimal 50 karakter</span>
                </div>
                
                <div class="form-group">
                    <label for="password" class="required">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="text" 
                           id="password" 
                           name="password" 
                           value="<?php echo htmlspecialchars($_POST['password'] ?? ''); ?>"
                           required
                           maxlength="255"
                           placeholder="Masukkan password">
                    <span class="form-hint">Password untuk login user</span>
                    <div id="password-strength" class="password-strength"></div>
                </div>
                
                <div class="form-group">
                    <label for="role" class="required">
                        <i class="fas fa-user-tag"></i> Role
                    </label>
                    <select id="role" name="role" required>
                        <option value="">Pilih Role</option>
                        <option value="Admin" <?php echo (isset($_POST['role']) && $_POST['role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                        <option value="Asesor" <?php echo (isset($_POST['role']) && $_POST['role'] == 'Asesor') ? 'selected' : ''; ?>>Asesor</option>
                        <option value="Asesi" <?php echo (isset($_POST['role']) && $_POST['role'] == 'Asesi') ? 'selected' : ''; ?>>Asesi</option>
                    </select>
                    <span class="form-hint">Hak akses user dalam sistem</span>
                </div>
                
                <div class="btn-container">
                    <a href="../BERANDA/UTAMA.php?page=../MANAGEMENT/tampil2.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" name="tambah" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah User
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.getElementById('tambahUserForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            const role = document.getElementById('role').value;
            
            let errors = [];
            
            if (!username) {
                errors.push('Username harus diisi');
            } else if (username.length > 50) {
                errors.push('Username maksimal 50 karakter');
            }
            
            if (!password) {
                errors.push('Password harus diisi');
            } else if (password.length > 255) {
                errors.push('Password terlalu panjang');
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