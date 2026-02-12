<?php
session_start();
if (!isset($_SESSION['role']) || !isset($_SESSION['nama'])) {
    header("Location: ../LOGIN/login.php");
    exit;
}
$role = $_SESSION['role'];
$nama = $_SESSION['nama'];
function get_initials($name) {
    if(!$name) return '';
    $words = explode(' ', $name);
    $res = '';
    foreach ($words as $w) {
        if ($w !== '') {
            $res .= strtoupper($w[0]);
            if(strlen($res) >= 2) break;
        }
    }
    return $res;
}
$roles_data = [
    'Admin' => [
        'icon' => 'fas fa-user-shield',
        'role_name' => 'Admin',
        'role_desc' => '',
        'avatar_bg' => 'background: linear-gradient(135deg, #3498db, #2ecc71);',
        'menu' => [
            [
                'href' => '../BERANDA/beranda_admin.php',
                'icon' => 'fas fa-home',
                'label' => 'Dashboard',
                'active' => true
            ],
            [
                'href' => '../MANAGEMENT/tampil2.php',
                'icon' => 'fas fa-users',
                'label' => 'Manajemen Pengguna'
            ],
            [
                'href' => '../ASESOR/Table_asesor.php',
                'icon' => 'fas fa-user-tie',
                'label' => 'Kelola Asesor'
            ],
            [
                'href' => '../ASESI/Table_assesi.php',
                'icon' => 'fas fa-user-graduate',
                'label' => 'Kelola Assesi'
            ],
            [
                'href' => 'kelola_skema.php',
                'icon' => 'fas fa-book',
                'label' => 'Kelola Skema'
            ],
            [
                'href' => 'kelola_jadwal.php',
                'icon' => 'fas fa-calendar-alt',
                'label' => 'Kelola Jadwal'
            ],
            [
                'href' => 'laporan.php',
                'icon' => 'fas fa-chart-bar',
                'label' => 'Laporan'
            ]
        ]
     ]
    // 'Asesor' => [
    //     'icon' => 'fas fa-user-tie',
    //     'role_name' => 'Asesor',
    //     'role_desc' => '',
    //     'avatar_bg' => 'background: linear-gradient(135deg, #e67e22, #f1c40f);',
    //     'menu' => [
    //         [
    //             'href' => '../BERANDA/beranda_admin.php',
    //             'icon' => 'fas fa-home',
    //             'label' => 'Dashboard',
    //             'active' => true
    //         ],
    //         [
    //             'href' => '#',
    //             'icon' => 'fas fa-clipboard-list',
    //             'label' => 'Uji Kompetensi'
    //         ],
    //         [
    //             'href' => '#',
    //             'icon' => 'fas fa-user-graduate',
    //             'label' => 'Assesi Saya'
    //         ],
    //         [
    //             'href' => '#',
    //             'icon' => 'fas fa-book',
    //             'label' => 'Dokumen Penilaian'
    //         ],
    //         [
    //             'href' => '#',
    //             'icon' => 'fas fa-cog',
    //             'label' => 'Pengaturan'
    //         ]
    //     ]
    // ],
    // 'Assesi' => [
    //     'icon' => 'fas fa-user-graduate',
    //     'role_name' => 'Assesi',
    //     'role_desc' => '',
    //     'avatar_bg' => 'background: linear-gradient(135deg, #8e44ad, #6dd5ed);',
    //     'menu' => [
    //         [
    //             'href' => 'beranda_admin.php',
    //             'icon' => 'fas fa-home',
    //             'label' => 'Dashboard',
    //             'active' => true
    //         ],
    //         [
    //             'href' => '#',
    //             'icon' => 'fas fa-clipboard-list',
    //             'label' => 'Ujian Saya'
    //         ],
    //         [
    //             'href' => '#',
    //             'icon' => 'fas fa-certificate',
    //             'label' => 'Sertifikat'
    //         ],
    //         [
    //             'href' => '#',
    //             'icon' => 'fas fa-book',
    //             'label' => 'Materi & Dokumen'
    //         ],
    //         [
    //             'href' => '#',
    //             'icon' => 'fas fa-cog',
    //             'label' => 'Pengaturan'
    //         ]
    //     ]
    // ]
];
$user_data = isset($roles_data[$role]) ? $roles_data[$role] : $roles_data['Admin'];
$avatar_bg = $user_data['avatar_bg'];
$init = get_initials($nama);
if (!$init) $init = strtoupper(substr($role,0,2));
$user_data['role_desc'] = $nama;

$request_uri = $_SERVER['REQUEST_URI'];
if (($qPos = strpos($request_uri, '?')) !== false) {
    $request_uri = substr($request_uri, 0, $qPos);
}
$base_script = basename(__FILE__);
$base_path = '/' . $base_script;
$route = '';
if (strpos($request_uri, $base_path . '/') === 0) {
    $route = ltrim(substr($request_uri, strlen($base_path)), '/');
} else if ($request_uri == $base_path || $request_uri == $base_path . '/') {
    $route = '';
} else {
    $route = '';
}
$allowed_pages = [
    'dashboard_admin.php',
    'tampil2.php',
    'kelola_asesor.php',
    'kelola_assesi.php',
    'kelola_skema.php',
    'kelola_jadwal.php',
    'laporan.php'
];
$page_to_include = '';
if ($route && in_array($route, $allowed_pages)) {
    $page_to_include = $route;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda <?=htmlspecialchars($user_data['role_name'])?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f5f7fa;
            color: #333;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: linear-gradient(to bottom, #2c3e50, #1a2530);
            color: white;
            padding: 20px 0;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            padding: 0 20px 20px;
            border-bottom: 1px solid #34495e;
            margin-bottom: 20px;
        }
        .logo h1 {
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logo span {
            color: #3498db;
        }
        .nav-menu {
            list-style: none;
        }
        .nav-menu li {
            padding: 15px 25px;
            transition: background 0.3s;
        }
        .nav-menu li:hover {
            background-color: #34495e;
        }
        .nav-menu li.active,
        .nav-menu li[aria-current="page"] {
            background-color: #3498db;
            border-left: 4px solid #2980b9;
        }
        .nav-menu a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .header {
            background-color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            z-index: 10;
        }
        .search-bar {
            display: flex;
            align-items: center;
            background-color: #f5f7fa;
            border-radius: 30px;
            padding: 10px 20px;
            width: 400px;
        }
        .search-bar input {
            border: none;
            background: transparent;
            margin-left: 10px;
            width: 100%;
            outline: none;
            font-size: 1rem;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .notifications {
            position: relative;
        }
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            <?=$avatar_bg?>
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            color: white;
        }
        .user-details h3 {
            font-size: 1rem;
            margin-bottom: 3px;
        }
        .user-details p {
            font-size: 0.85rem;
            color: #7f8c8d;
        }
        .content {
            padding: 30px;
            flex: 1;
        }
        .welcome-section {
            margin-bottom: 30px;
        }
        .welcome-section h2 {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        .welcome-section p {
            color: #7f8c8d;
        }
        .footer {
            background-color: white;
            padding: 20px 30px;
            text-align: center;
            color: #7f8c8d;
            border-top: 1px solid #ecf0f1;
            font-size: 0.9rem;
        }

    </style>
</head>
<body>
    
        <div class="container">
            <aside class="sidebar">
                <div class="logo">
                    <h1><span>Dashboard</span></h1>
                </div>
                <ul class="nav-menu">
                    <?php
                    foreach ($user_data['menu'] as $item) {
                        $href = $item['href'];

                        if ($href === 'beranda_admin.php') {
                        } elseif ($href === 'beranda_admin.php') {
                            $href = $base_path . '/';
                        }
                        //assesor
                        $active = '';
                        if (
                            (isset($item['active']) && $item['active'] && ($route == '' || $route == 'dashboard_admin.php' || $route == 'beranda_admin.php')) ||
                            ($route && isset($item['href']) && $item['href'] != '#' && $route === $item['href'])
                        ) {
                            $active = 'class="active" aria-current="page"';
                        }
                        echo "<li $active>";
                        if ($item['href'] === '#') {
                            echo "<a href=\"#\"><i class=\"{$item['icon']}\"></i> <span>{$item['label']}</span></a>";
                        } else {
                            echo "<a href=\"".htmlspecialchars($href)."\"><i class=\"{$item['icon']}\"></i> <span>{$item['label']}</span></a>";
                        }
                        echo "</li>";
                    }
                    ?>
                    <li>
                        <form action="login.php" method="post" style="display:inline;">
                            <button style="background:none;border:none;color:white;cursor:pointer;padding:0;display:flex;align-items:center;gap:12px;" type="submit">
                                <i class="fa fa-sign-out-alt"></i> <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </aside>
            <main class="main-content">
                <header class="header">
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Cari...">
                    </div>
                    
                    <div class="user-info">
                        <div class="user-profile">
                            <div class="avatar"><?=htmlspecialchars($init)?></div>
                            <div class="user-details">
                                <h3><?=htmlspecialchars($nama)?></h3>
                                <p><?=htmlspecialchars($user_data['role_desc'])?></p>
                            </div>
                        </div>
                    </div>
                </header>
                <section class="content">
                    <?php
                    if ($page_to_include) {
                        include $page_to_include;
                    } else {
                    ?>
                    <div class="welcome-section">
                        <h2>Selamat Datang, <?=htmlspecialchars($nama)?>!</h2>
                        <p>Anda masuk sebagai <b><?=htmlspecialchars($user_data['role_name'])?></b>.</p>
                    </div>
                    <?php } ?>
                </section>
                <footer class="footer">
                    <p>&copy; 2023</p>
                </footer>
            </main>
        </div>
    
</body>
</html>
