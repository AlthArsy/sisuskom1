<?php 
ob_start();
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../LOGIN/login.php");
    exit;
}
$role = $_SESSION['role'];
$username = $_SESSION['username'] ?? 'User';
$nama_user = $_SESSION['nama_user'];


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
        'role_name' => '',
        'role_desc' => 'Administrator',
        'menu' => [
            [
                'href' => '../BERANDA/UTAMA.php',
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
                'href' => '../ASESI/Table_asesi.php',
                'icon' => 'fas fa-user-graduate',
                'label' => 'Manajemen Asesi'
            ],
                        [
                'href' => '../ASESOR/Table_asesor.php',
                'icon' => 'fas fa-user-tie',
                'label' => 'Manajemen Asesor'
            ],
            [
                'href' => '#',
                'icon' => 'fas fa-book',
                'label' => 'Manajemen Skema',
                'has_dropdown' => true,
                'submenu' => [
                    [
                        'href' => '../SKEMA/list_skema.php',
                        'icon' => 'fas fa-book',
                        'label' => 'Kelola Skema'
                    ],
                    [
                        'href' => '../SKEMA/list_skema2.php',
                        'icon' => 'fas fa-tasks',
                        'label' => 'Data Skema'
                    ]
                    // [
                    //     'href' => '../ELEMEN/elemen.php',
                    //     'icon' => 'fas fa-puzzle-piece',
                    //     'label' => 'Element'
                    // ],
                    // [
                    //     'href' => '../KUK/KUK.php',
                    //     'icon' => 'fas fa-check-circle',
                    //     'label' => 'KUK'
                    // ]
                ]
            ]
        ]
    ],
    'Asesor' => [
        'icon' => 'fas fa-user-tie',
        'role_name' => '',
        'role_desc' => '',
        'menu' => [
            [
                'href' => 'UTAMA.php',
                'icon' => 'fas fa-home',
                'label' => 'Dashboard',
                'active' => true
            ],            
            [
                'href' => '#',
                'icon' => 'fas fa-book',
                'label' => 'Skema',
                'has_dropdown' => true,
                'submenu' => [
                    [
                        'href' => '../SKEMA/list_skema.php',
                        'icon' => 'fas fa-book',
                        'label' => 'Skema'
                    ],
                    [
                        'href' => '../UNIT/unit_kompetensi.php',
                        'icon' => 'fas fa-tasks',
                        'label' => 'Kompetensi'
                    ]
                    // [
                    //     'href' => '../ELEMEN/elemen.php',
                    //     'icon' => 'fas fa-puzzle-piece',
                    //     'label' => 'Element'
                    // ],
                    // [
                    //     'href' => '../KUK/KUK.php',
                    //     'icon' => 'fas fa-check-circle',
                    //     'label' => 'KUK'
                    // ]
                ]
            ]
        ]
    ],
    'Asesi' => [
        'icon' => 'fas fa-user-graduate',
        'role_name' => '',
        'role_desc' => '',
        'menu' => [
            [
                'href' => 'UTAMA.php',
                'icon' => 'fas fa-home',
                'label' => 'Dashboard',
                'active' => true
            ]
        ]
    ]
];

$user_data = isset($roles_data[$role]) ? $roles_data[$role] : $roles_data['Admin'];
$init = get_initials($username);
if (!$init) $init = strtoupper(substr($role,0,2));

$current_page = basename($_SERVER['PHP_SELF']);
$allowed_pages = [
    'UTAMA.php',
    '../MANAGEMENT/tampil2.php',
    '../SKEMA/list_skema.php',
    '../SKEMA/list_skema2.php',
    '../UNIT/unit_kompetensi.php',
    '../SKEMA/Form_Skema.php',
    '../ELEMEN/elemen.php',
    '../KUK/KUK.php',
    '../SKEMA/simpan_skema.php',
    '../ASESI/Table_asesi.php',
    '../ASESOR/Table_asesor.php',
    '../PENAGATURAN/tambah-user-baru.php',
    '../PENAGATURAN/ubah.php',
    '../UNIT/From_unit_kompetensi.php',
    '../SKEMA/Ubah_Skema.php',
    '../ELEMEN/From_elemen.php',
    '../KUK/From_kuk.php',
    '../PENAGATURAN/ubah.php',
    '../PENAGATURAN/hapus.php',
    '../ASESOR/edit.php',
    '../ASESOR/hapus_asesor.php',
    '../',
    '../ASESI/detail_asesi.php',
    '../ASESI/edit.php',
    '../ASESI/hapus_asesi.php',
    '../PROFIL/profil.php',
    '../'
];

$page_to_include = '';
if (isset($_GET['page']) && in_array($_GET['page'], $allowed_pages)) {
    $page_to_include = $_GET['page'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Manajemen LSP</title>
    <link rel="icon" type="image/png" href="../assets/Mudikal.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        body {
            background-color: #e0e0e0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            display: flex;
            flex: 1;
            position: relative;
        }

        .sidebar {
            width: 280px;
            background: #f5f5f5;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            z-index: 100;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .profile-section {
            background: linear-gradient(135deg, rgba(255, 0, 0, 5), rgba(114, 0, 99, 5), rgba(0, 21, 141, 5));
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
            padding: 30px 20px;
            color: white;
            text-align: left;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .profile-section h2 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .profile-section p {
            font-size: 14px;
            opacity: 0.95;
        }

        .nav-header {
            padding: 15px 20px;
            background: #e8e8e8;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            color: #555;
        }

        .nav-menu {
            flex: 1;
            overflow-y: auto;
        }

        .nav-menu a, .nav-menu .menu-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            color: #333;
            text-decoration: none;
            transition: background 0.2s;
            border-left: 4px solid transparent;
            cursor: pointer;
        }

        .nav-menu a:hover, .nav-menu .menu-item:hover {
            background: #e8e8e8;
        }

        .nav-menu a.active, .nav-menu .menu-item.active {
            background: #fff;
            border-left-color: rgb(60, 220, 231);
            color: rgb(60, 177, 231);
            font-weight: 600;
        }

        .nav-item-content {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .nav-menu i {
            width: 20px;
            text-align: center;
        }

        .dropdown-arrow {
            transition: transform 0.3s ease;
            font-size: 12px;
            margin-left: auto;
        }

        .dropdown-arrow.rotated {
            transform: rotate(180deg);
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: #ececec;
        }

        .submenu.open {
            max-height: 500px;
        }

        .submenu a {
            padding: 12px 20px 12px 52px;
            font-size: 14px;
            border-left: 4px solid transparent;
        }

        .submenu a:hover {
            background: #e0e0e0;
        }

        .submenu a.active {
            background: #d8d8d8;
            border-left-color: rgb(60, 220, 231);
            color: rgb(60, 177, 231);
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }

        .sidebar-footer a {
            color: rgb(60, 77, 231);
            text-decoration: none;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99;
        }

        .sidebar-overlay.active {
            display: block;
        }

        .main-content {
            flex: 1;
            padding: 20px 30px;
            overflow-y: auto;
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        .breadcrumb {
            background: #f3f1f1ff;
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
            padding: 12px 20px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .toggle-sidebar-btn {
            background: rgba(96, 125, 139, 0.15);
            border: none;
            color: #555;
            cursor: pointer;
            font-size: 18px;
            padding: 8px 12px;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .toggle-sidebar-btn:hover {
            background: rgba(96, 125, 139, 0.25);
        }

        .content-card {
            background: white;
            border-radius: 4px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .content-card h2 {
            font-size: 22px;
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }

        .welcome-text {
            color: #666;
            font-size: 16px;
            margin-bottom: 25px;
        }

        .notice-text {
            font-size: 24px;
            font-weight: 700;
            color: #555;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .warning-text {
            font-size: 24px;
            font-weight: 700;
            color: #555;
            line-height: 1.5;
        }
        @media screen and (max-width: 768px) {
            .sidebar {
                position: fixed;
                height: 100vh;
                z-index: 1000;
            }

            .main-content {
                margin-left: 0 !important;
                padding: 15px !important;
            }

            .notice-text, .warning-text {
                font-size: 16px !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
        <aside class="sidebar" id="sidebar">
            <div class="profile-section">
                <h2><?=htmlspecialchars(strtoupper($username))?></h2>
                <p><?=htmlspecialchars($user_data['role_desc'])?></p>
            </div>

            <div class="nav-header">NAVIGASI UTAMA</div>

            <nav class="nav-menu">
                <?php
                foreach ($user_data['menu'] as $index => $item) {
                    $href = $item['href'];
                    $is_active = '';
                    $has_dropdown = isset($item['has_dropdown']) && $item['has_dropdown'];
                    
                    $submenu_active = false;
                    if ($has_dropdown && isset($item['submenu'])) {
                        foreach ($item['submenu'] as $sub) {
                            if ($page_to_include === $sub['href']) {
                                $submenu_active = true;
                                break;
                            }
                        }
                    }
                    
                    if ($href === 'UTAMA.php') {
                        if (!$page_to_include) {
                            $is_active = 'active';
                        }
                    } elseif ($page_to_include === $href || $submenu_active) {
                        $is_active = 'active';
                    }
                    
                    if ($has_dropdown) {
                        echo '<div class="menu-item '.$is_active.'" onclick="toggleDropdown('.$index.')">';
                        echo '<div class="nav-item-content">';
                        echo '<i class="'.$item['icon'].'"></i>';
                        echo '<span>'.$item['label'].'</span>';
                        echo '</div>';
                        echo '<i class="fas fa-chevron-down dropdown-arrow" id="arrow-'.$index.'"></i>';
                        echo '</div>';
                        
                        $submenu_open = $submenu_active ? 'open' : '';
                        echo '<div class="submenu '.$submenu_open.'" id="submenu-'.$index.'">';
                        foreach ($item['submenu'] as $subitem) {
                            $sub_active = ($page_to_include === $subitem['href']) ? 'active' : '';
                            echo '<a href="?page='.htmlspecialchars($subitem['href']).'" class="'.$sub_active.'">';
                            echo '<div class="nav-item-content">';
                            echo '<i class="'.$subitem['icon'].'"></i>';
                            echo '<span>'.$subitem['label'].'</span>';
                            echo '</div>';
                            echo '</a>';
                        }
                        echo '</div>';
                    } else {
                        if ($href === 'UTAMA.php') {
                            $href = '?';
                        } elseif ($href !== '#') {
                            $href = '?page=' . $href;
                        }
                        
                        echo '<a href="'.htmlspecialchars($href).'" class="'.$is_active.'">';
                        echo '<div class="nav-item-content">';
                        echo '<i class="'.$item['icon'].'"></i>';
                        echo '<span>'.$item['label'].'</span>';
                        echo '</div>';
                        echo '</a>';
                    }
                }
                ?>
                <a href="#" onclick="event.preventDefault(); { window.location.href = 'UTAMA.php?page=../PROFIL/profil.php'; }">
                    <div class="nav-item-content">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </div>
                </a>
                <a href="#" onclick="event.preventDefault(); { window.location.href = '../LOGIN/logout.php'; }">
                    <div class="nav-item-content">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Keluar</span>
                    </div>
                </a>
            </nav>
        </aside>

        <main class="main-content" id="mainContent">
            <div class="breadcrumb">
                <button class="toggle-sidebar-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <?php 
                if ($page_to_include) {
                    $breadcrumb_found = false;
                    foreach ($user_data['menu'] as $item) {
                        if ($item['href'] === $page_to_include) {
                            echo htmlspecialchars($item['label']);
                            $breadcrumb_found = true;
                            break;
                        }
                        if (isset($item['submenu'])) {
                            foreach ($item['submenu'] as $subitem) {
                                if ($subitem['href'] === $page_to_include) {
                                    echo htmlspecialchars($item['label']) . ' / ' . htmlspecialchars($subitem['label']);
                                    $breadcrumb_found = true;
                                    break 2;
                                }
                            }
                        }
                    }
                    if (!$breadcrumb_found) {
                        echo 'Dashboard';
                    }
                } else {
                    echo 'Dashboard';
                }
                ?>
            </div>

            <?php
            if ($page_to_include && file_exists($page_to_include)) {
                include $page_to_include;
            } else {
            ?>
            <div class="content-card">
                <h2>Beranda</h2>
                
                <p class="welcome-text">
                    Selamat datang <strong><?=htmlspecialchars($nama_user)?></strong>!
                </p>
            </div>
            <?php } ?>
        </main>
    </div>

    <script>
        function toggleDropdown(index) {
            const submenu = document.getElementById('submenu-' + index);
            const arrow = document.getElementById('arrow-' + index);
            
            submenu.classList.toggle('open');
            arrow.classList.toggle('rotated');
        }

        function initSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            overlay.classList.toggle('active');
            
            if (window.innerWidth > 768) {
                mainContent.classList.toggle('expanded');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.nav-menu a').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        const sidebar = document.getElementById('sidebar');
                        const overlay = document.getElementById('sidebarOverlay');
                        
                        if (!sidebar.classList.contains('collapsed')) {
                            sidebar.classList.add('collapsed');
                            overlay.classList.remove('active');
                        }
                    }
                });
            });
        });

        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            
            if (window.innerWidth > 768) {
                overlay.classList.remove('active');
                sidebar.classList.remove('collapsed');
            } else {
                mainContent.classList.remove('expanded');
                sidebar.classList.add('collapsed');
            }
        });

        initSidebar();
    </script>
</body>
</html>