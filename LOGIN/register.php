<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: radial-gradient(ellipse at 40% 20%, #B6E8F2 0%, #84A9AC 60%, #274060 100%);
            font-family: 'Montserrat', 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(63px);
            opacity: 0.21;
            pointer-events: none;
            z-index: 0;
        }
        .blob1 {
            width: 340px; height: 250px;
            background: #51a7de;
            top: 80px; left: 10vw;
            animation: blobAnim1 9s ease-in-out infinite alternate;
        }
        .blob2 {
            width: 280px; height: 210px;
            background: #67e8a7;
            top: 56vh; left: 65vw;
            animation: blobAnim2 10s ease-in-out infinite alternate-reverse;
        }

        .login-box {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,0.97);
            border-radius: 18px;
            padding: 36px 30px 28px 30px;
            box-shadow: 0 8px 36px 4px rgba(60,75,130,0.10), 0 4px 18px 0 rgba(60,150,90,0.07);
            width: 352px;
            box-sizing: border-box;
            overflow: visible;
            animation: floatBox 1.9s cubic-bezier(.23,.76,.62,.99);
        }

        .login-box h2 {
            text-align: center;
            font-size: 25px;
            font-weight: 700;
            color: #213555;
            margin-bottom: 18px;
            letter-spacing: 1.2px;
            text-shadow: 0 2px 14px #50aeea0c;
            transition: color 0.28s;
            user-select: none;
        }
        .login-box h2 span {
            color: #279e84;
        }

        .login-box table {
            width: 100%;
            margin-bottom: 13px;
            border-collapse: separate;
            border-spacing: 0 9px;
        }

        .login-box td {
            font-size: 16px;
            color: #365e7d;
            padding: 0 0 1px 0;
            vertical-align: middle;
            font-weight: 500;
        }

        .login-box input[type="text"],
        .login-box input[type="password"],
        .login-box select {
            width: 96%;
            padding: 9px 12px;
            border: 2px solid #d3eafd;
            border-radius: 6.5px;
            background: #fafdff;
            font-size: 15px;
            transition: border-color 0.24s, background 0.29s;
            outline: none;
            box-shadow: 0 1px 5px #c3f4e660;
        }

        .login-box input:focus,
        .login-box select:focus {
            border: 2.5px solid #279e84;
            background: #e6fcf7;
        }

        .login-box input[type="text"]:hover,
        .login-box input[type="password"]:hover,
        .login-box select:hover {
            background: #f0faff;
        }

        .btn-login {
            background: linear-gradient(90deg, #279e84 55%, #1b6c5d 100%);
            color: #fff;
            padding: 12px 0;
            border: none;
            border-radius: 7px;
            width: 100%;
            font-size: 16.5px;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.45px;
            margin-top: 12px;
            margin-bottom: 2px;
            transition: box-shadow 0.22s, background 0.2s, transform 0.13s;
            box-shadow: 0 2.5px 11px rgba(60,150,90,0.13);
        }

        .btn-login:hover, .btn-login:focus {
            background: linear-gradient(90deg, #1b6c5d 70%, #279e84);
            box-shadow: 0 6px 18px 0 rgba(60,150,90,0.17);
            outline: none;
            transform: translateY(-2px) scale(1.025,1.04);
        }   

    </style>
</head>
<body>
    <div class="blob blob1"></div>
    <div class="blob blob2"></div>
    <div class="login-box">
        <h2><span></span> Login</h2>
        <form action="proses.php" method="post" autocomplete="off">
            <table>
                <tr>
                    <td>Username</td>
                    <td>
                        <input type="text" name="nik" placeholder="NIK" autocomplete="username">
                    </td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td>
                        <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
                    </td>
                </tr>
                <tr>
                    <td>Role</td>
                    <td>
                        <select name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Asesor">Asesor</option>
                            <option value="Assesi">Assesi</option>
                        </select>
                    </td>
                </tr>
            </table>
            <button type="submit" class="btn-login">Masuk</button>
        </form>
    <div style="text-align: center; margin-top: 18px;">
        <a href="login.php" class="btn-login" style="background: #ff9800; color: #fff; display: inline-block; text-decoration: none; padding: 0.45em 2.1em; border-radius: 5px; font-weight: 500; margin-top: 3px; transition: background 0.19s;">
            Daftar
        </a>
    </div>
    </div>
</body>
</html>