<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login LSP Mudikal</title>
    <link rel="icon" type="image/png" href="../assets/Mudikal.png">
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> -->
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
        .form-group input,
        .form-group select {
            width: 100%;
            
            padding: 13px 1px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        .form-group input[type="password"] {
            font-family: 'Courier New', monospace;
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
            margin-bottom: 20px;
            border-left: 4px solid #e74c3c;
        }
        .success-message {
            background: #efe;
            color: #27ae60;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #2ecc71;
        }
    </style>
</head>
<body>
    
    <div class="l-container">
        <div class="header">
            <i class="fas fa-sign-in-alt"></i>
            <h1>Login</h1>
        </div>

        <div class="form-container">
            <form action="proses.php" method="POST" autocomplete="off">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Username" require
                        <?php if (isset($_GET['username'])): ?> value="<?php echo htmlspecialchars($_GET['username']); ?>" <?php endif; ?>
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Password" required >
                </div>

                <div class="form-group">
                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="">Pilih Role</option>
                        <option value="Admin" <?php if(isset($_GET['role']) && strtolower($_GET['role'])=='admin'){echo 'selected';} ?>>Admin</option>
                        <option value="Asesor" <?php if(isset($_GET['role']) && strtolower($_GET['role'])=='asesor'){echo 'selected';} ?>>Asesor</option>
                        <option value="Asesi" <?php if(isset($_GET['role']) && strtolower($_GET['role'])=='asesi'){echo 'selected';} ?>>Asesi</option>
                    </select>
                </div>

                <div class="btn-container">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>  
</body>
</html>