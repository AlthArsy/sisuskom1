<?php

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

$role_filter = isset($_GET['role_filter']) ? $_GET['role_filter'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$allowed_roles = ['Admin', 'Asesor', 'Asesi'];

$sql = "SELECT * FROM users";
$conditions = [];
$params = [];

if ($role_filter && in_array($role_filter, $allowed_roles)) {
    $conditions[] = "role = ?";
    $params[] = $role_filter;
}

if ($search !== '') {
    $conditions[] = "(username LIKE ?)";
    $params[] = '%' . $search . '%';
}

if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

if (!empty($params)) {
    $stmt = mysqli_prepare($koneksi, $sql);
    if ($stmt) {
        $types = str_repeat('s', count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $hasil = mysqli_stmt_get_result($stmt);
    } else {
        die("Prepare error: " . mysqli_error($koneksi));
    }
} else {
    $hasil = mysqli_query($koneksi, $sql);
}

if (!$hasil) {
    die("Query error: " . mysqli_error($koneksi));
}

// Fungsi untuk membuat URL dengan parameter yang benar
function buildSearchUrl($params) {
    $base_url = '';
    
    // Jika ada parameter 'page', gunakan itu sebagai base
    if (isset($_GET['page'])) {
        $base_url = '?page=' . urlencode($_GET['page']);
        
        // Tambahkan parameter pencarian
        if (!empty($params['search'])) {
            $base_url .= '&search=' . urlencode($params['search']);
        }
        if (!empty($params['role_filter'])) {
            $base_url .= '&role_filter=' . urlencode($params['role_filter']);
        }
    } else {
        // Jika tidak ada parameter page, gunakan URL biasa
        $query_params = [];
        if (!empty($params['search'])) {
            $query_params[] = 'search=' . urlencode($params['search']);
        }
        if (!empty($params['role_filter'])) {
            $query_params[] = 'role_filter=' . urlencode($params['role_filter']);
        }
        
        if (!empty($query_params)) {
            $base_url = '?' . implode('&', $query_params);
        }
    }
    
    return $base_url;
}
?>
<style>
    .jdm {
        color: #14305c;
        margin-bottom: 18px;
        font-size: 1.4em;
    }
    .cari {
        margin-bottom:20px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }
    .cari input[type="text"] {
        padding:7px 14px;
        width:210px;
        border:1px solid #b0b6bd;
        border-radius:4px;
        font-size:14px;
        color:#252525;
        background:#fafbfc;
        transition: border-color .22s;
    }
    .cari input[type="text"]:focus {
        border-color: #549edb;
        outline: none;
    }
    .cari select {
        padding:7px 14px;
        border:1px solid #b0b6bd;
        border-radius:4px;
        font-size:14px;
        color:#252525;
        background:#fafbfc;
        transition: border-color .22s;
    }
    .cari select:focus {
        border-color: #549edb;
        outline: none;
    }
    .cari button {
        padding:7px 18px;
        font-size:14px;
        background:#4186e0;
        border:none;
        border-radius:4px;
        color:#fff;
        cursor:pointer;
        font-weight:500;
    }
    .cari button:hover {
        background: #2761ba;
    }
    .cari .btn-reset {
        background: #95a5a6;
    }
    .cari .btn-reset:hover {
        background: #7f8c8d;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
    }
    th, td {
        border: 1px solid #e7e7eb;
        padding: 10px 14px;
        text-align: left;
    }
    th {
        background: #24365e;
        color: #fff;
        font-weight: 600;
    }
    tr:nth-child(even) {
        background: #f4f7fd;
    }
    tr:nth-child(odd) {
        background: #fff;
    }
    tbody tr:last-child td {
        border-bottom: none;
    }
    .aksi a {
        display: inline-block;
        font-size: 13px;
        padding: 6px 14px;
        border-radius: 5px;
        margin-right: 5px;
        background: #f4f8fd;
        color: #1877cc;
        text-decoration: none;
        border: 1px solid #c9e1fb;
    }
    .aksi a.btn-ubah:hover {
        background: #2081e5;
        color: #fff;
    }
    .aksi a.btn-hapus {
        color: #b50000;
        border-color: #fad0d0;
        background: #fcf3f3;
    }
    .aksi a.btn-hapus:hover {
        background: #e43d3d;
        color: #fff;
    }
    @media screen and (max-width: 768px) {
        .cari {
            flex-direction: column;
            align-items: stretch;
        }
        
        .cari input[type="text"],
        .cari select,
        .cari button {
            width: 100%;
        }
        
        table {
            border: 0;
        }
        
        table thead {
            display: none;
        }
        
        table tbody {
            display: block;
        }
        
        table tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff !important;
        }
        
        table td {
            display: block;
            text-align: right;
            border: none;
            border-bottom: 1px solid #eee;
            padding: 10px;
            position: relative;
            padding-left: 50%;
        }
        
        table td:last-child {
            border-bottom: none;
        }
        
        table td:before {
            content: attr(data-label);
            position: absolute;
            left: 10px;
            font-weight: bold;
            text-align: left;
            color: #24365e;
        }
        
        .aksi {
            text-align: center !important;
            padding-left: 10px !important;
        }
        
        .aksi:before {
            display: none;
        }
        
        .aksi a {
            display: inline-block;
            margin: 5px 3px;
        }
        
        .jdm {
            font-size: 1.1em;
        }
    }
</style>
<div class="konten-user">
    <h2 class="jdm">Data User</h2>
    
    <form method="get" action="" class="cari">
        <?php if (isset($_GET['page'])): ?>
            <input type="hidden" name="page" value="<?php echo htmlspecialchars($_GET['page']); ?>">
        <?php endif; ?>
        
        <input 
            type="text" 
            name="search" 
            placeholder="Cari username" 
            value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
        
        <select name="role_filter">
            <option value="">-- Semua Role --</option> 
            <?php
                foreach ($allowed_roles as $role) {
                    $selected = ($role_filter === $role) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($role) . "\" $selected>" . htmlspecialchars($role) . "</option>";
                }
            ?>
        </select>
        
        <button type="submit">Cari</button>
        
        <?php if (!empty($search) || !empty($role_filter)): ?>
            <a href="<?php echo isset($_GET['page']) ? '?page=' . urlencode($_GET['page']) : $_SERVER['PHP_SELF']; ?>" 
               class="btn-reset" 
               style="padding:7px 18px;font-size:14px;background:#95a5a6;border:none;border-radius:4px;color:#fff;text-decoration:none;display:inline-block;">
                Reset
            </a>
        <?php endif; ?>
        <div style="margin: 15px 0; text-align: right;">
            <a href="../BERANDA/UTAMA.php?page=../PENAGATURAN/tambah-user-baru.php" 
                style="display: inline-block; padding: 10px 20px; background: #8157ceff; color: white; 
                       text-decoration: none; border-radius: 5px; font-weight: 600;">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
        </div>
    </form>
    
    <?php if (!empty($search) || !empty($role_filter)): ?>
        <div style="margin-bottom: 15px; padding: 10px; background: #e8f4f8; border-left: 4px solid #3498db; border-radius: 4px;">
            <strong>Filter aktif:</strong>
            <?php if (!empty($search)): ?>
                Username: "<?php echo htmlspecialchars($search); ?>"
            <?php endif; ?>
            <?php if (!empty($role_filter)): ?>
                <?php echo !empty($search) ? ' | ' : ''; ?>
                Role: <?php echo htmlspecialchars($role_filter); ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>Username</th>
                <th>Password</th>
                <th>Role</th>
                <th style="width: 175px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $rows = [];
            while ($row = mysqli_fetch_assoc($hasil)) {
                $rows[] = $row;
            }
            if (count($rows) > 0) {
                foreach ($rows as $row) {
                    echo "<tr>";
                    echo "<td data-label='NO'>" . $no++ . "</td>";
                    echo "<td data-label='Username'>" . htmlspecialchars($row['username'] ?? '') . "</td>";
                    echo "<td data-label='Password'>" . htmlspecialchars($row['password']) . "</td>";
                    echo "<td data-label='Role'>" . strtoupper(htmlspecialchars($row['role'])) . "</td>";
                    echo "<td data-label='Aksi' class='aksi'>
                        <a href='UTAMA.php?page=../PENAGATURAN/ubah.php&id=" . $row['id_user'] . "' class='btn-ubah'>Ubah</a>
                        <a href='../BERANDA/UTAMA.php?page=../PENAGATURAN/hapus.php&id=" . $row['id_user'] . "' 
                           class='btn-hapus'
                           onclick=\"return confirm('Yakin ingin menghapus user ini?');\">Hapus</a>
                        </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center;color:#8692af;padding:32px;background:#fcfdff;font-size:16px;border-radius:7px;'>
                    Tidak ada data user yang sesuai dengan pencarian.
                    </td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.cari');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submitted');
        });
    }
});
</script>