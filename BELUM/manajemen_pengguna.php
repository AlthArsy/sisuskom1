<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: LOGIN/login.php");
    exit();
}
include 'koneksi.php';

if (mysqli_connect_errno()) {
    die("Gagal koneksi ke database: " . mysqli_connect_error());
}
$sql = "SELECT * FROM users";
$hasil = mysqli_query($koneksi, $sql);
if (!$hasil) {
    die("Query error: " . mysqli_error($koneksi));
}
?>
<style>
    .container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        padding: 0;
        width: auto;
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
    .content-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 30px;
        padding: 30px;
    }
    .left-panel {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .stats {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .tampil-users-wrapper .stat-admin i { color: #e23d98ff; }
    .tampil-users-wrapper .stat-petugas i { color: #11998e; }
    .tampil-users-wrapper .stat-anggota i { color: #8A2387; }
    .tampil-users-wrapper .stat-card:last-of-type i { color: #34495e; }
    .tampil-users-wrapper .stat-card h3 {
        font-size: 28px;
        color: #2c3e50;
        margin: 0 0 5px 0;
        font-weight: 700;
    }
    .search-box {
        position: relative;
        margin-top: 10px;
    }
    .search-box input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border: 2px solid #e0e0e0;
        border-radius: 25px;
        font-size: 14px;
        transition: all 0.3s;
    }
    .search-box input:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #95a5a6;
    }
    .right-panel {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .btn-tambah {
        background: linear-gradient(45deg, #4CAF50, #2E7D32);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(76, 175, 80, 0.3);
        font-size: 14px;
    }
    .btn-tambah:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        background: linear-gradient(45deg, #43A047, #1B5E20);
    }
    .table-container {
        overflow-x: auto;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        background: white;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }
    thead {
        background: linear-gradient(135deg, #2575fc, #6a11cb);
        color: white;
    }
    th {
        padding: 15px 12px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        padding: 14px 12px;
        color: #2c3e50;
        font-size: 14px;
        vertical-align: middle;
    }
    td:first-child {
        padding-left: 15px;
    }
    td:last-child {
        padding-right: 15px;
    }
    td code {
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        color: #7f8c8d;
    }
    td small {
        display: block;
        color: #95a5a6;
        font-size: 11px;
        margin-top: 4px;
    }
    .role-badge {
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }

    .aksi-buttons {
        display: flex;
        gap: 8px;
    }
    .btn-aksi {
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        font-size: 12px;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-ubah {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
    }
    .btn-hapus {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: white;
    }
    .btn-ubah:hover {
        background: linear-gradient(135deg, #2980b9, #1f639b);
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(52, 152, 219, 0.3);
    }
    .btn-hapus:hover {
        background: linear-gradient(135deg, #c0392b, #a93226);
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(231, 76, 60, 0.3);
    }
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #7f8c8d;
    }
    .empty-state i {
        font-size: 60px;
        margin-bottom: 20px;
        color: #bdc3c7;
    }
    .empty-state h3 {
        font-size: 24px;
        margin-bottom: 10px;
        color: #2c3e50;
    }

</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<div class="tampil-users-wrapper">
    <div class="container">
        <?php if (isset($_GET['added'])): ?>
            <div class="success-message" style="padding: 15px; margin: 20px; border-radius: 8px; background: #efe; color: #27ae60; border-left: 4px solid #2ecc71;">
                <i class="fas fa-check-circle"></i> User baru berhasil ditambahkan!
            </div>
        <?php elseif (isset($_GET['success'])): ?>
            <div class="success-message" style="padding: 15px; margin: 20px; border-radius: 8px; background: #efe; color: #27ae60; border-left: 4px solid #2ecc71;">
                <i class="fas fa-check-circle"></i> User berhasil diupdate!
            </div>
        <?php elseif (isset($_GET['deleted'])): ?>
            <div class="success-message" style="padding: 15px; margin: 20px; border-radius: 8px; background: #efe; color: #27ae60; border-left: 4px solid #2ecc71;">
                <i class="fas fa-check-circle"></i> User berhasil dihapus!
            </div>
        <?php endif; ?>
        <?php
        $sql_stats = "SELECT 
            SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as total_admin,
            SUM(CASE WHEN role = 'asesor' THEN 1 ELSE 0 END) as total_asesor,
            SUM(CASE WHEN role = 'assesi' THEN 1 ELSE 0 END) as total_assesi,
            COUNT(*) as total_users
            FROM users";
        $result_stats = mysqli_query($koneksi, $sql_stats);
        $stats = mysqli_fetch_assoc($result_stats);
        ?>

        <div class="content-layout">
            <div class="left-panel">
                <div class="stats">
                    <div class="stat-card stat-admin">
                        <i class="fas fa-crown"></i>
                        <h3><?php echo $stats['total_admin'] ?? 0; ?></h3>
                        <p>Admin</p>
                    </div>
                    <div class="stat-card stat-petugas">
                        <i class="fas fa-user-tie"></i>
                        <h3><?php echo $stats['total_asesor'] ?? 0; ?></h3>
                        <p>Asesor</p>
                    </div>
                    <div class="stat-card stat-anggota">
                        <i class="fas fa-user-friends"></i>
                        <h3><?php echo $stats['total_assesi'] ?? 0; ?></h3>
                        <p>Assesi</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-users"></i>
                        <h3><?php echo $stats['total_users'] ?? 0; ?></h3>
                        <p>Total Users</p>
                    </div>
                </div>

                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari user">
                </div>
            </div>

            <div class="right-panel">
                <div class="actions">
                    <a href="tambah-user-baru.php" class="btn-tambah">
                        <i class="fas fa-user-plus"></i> Tambah User Baru
                    </a>
                    
                </div>  
                <div class="table-container">
                    <table id="usersTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Password</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($hasil) > 0) {
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($hasil)) {
                                    $role_class = '';
                                    switch (strtolower($row['role'])) {
                                        case 'admin':
                                            $role_class = 'role-admin';
                                            break;
                                        case 'asesor':
                                            $role_class = 'role-asesor';
                                            break;
                                        case 'assesi':
                                            $role_class = 'role-assesi';
                                            break;
                                        default:
                                            $role_class = '';
                                    }
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td>" . htmlspecialchars($row['nik'] ?? '') . "</td>";
                                    echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                    echo "<td><code>********</code><small>(tersimpan terenkripsi)</small></td>";
                                    echo "<td><span class='role-badge $role_class'>" . strtoupper(htmlspecialchars($row['role'])) . "</span></td>";
                                    echo "<td>
                                            <div class='aksi-buttons'>
                                                <a href='ubah.php?id=" . $row['id'] . "' class='btn-aksi btn-ubah'>
                                                    <i class='fas fa-edit'></i> Ubah
                                                </a>
                                                <a href='hapus.php?id=" . $row['id'] . "' 
                                                   class='btn-aksi btn-hapus'
                                                   onclick=\"return confirm('Yakin ingin menghapus user ini?');\">
                                                    <i class='fas fa-trash'></i> Hapus
                                                </a>
                                            </div>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>
                                        <div class='empty-state'>
                                            <i class='fas fa-users-slash'></i>
                                            <h3>Belum ada data user</h3>
                                            <p>Tambahkan user baru untuk memulai</p> 
                                        </div>
                                      </td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const table = document.getElementById('usersTable');
                if (!table) return;
                
                const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                
                for (let i = 0; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td');
                    let found = false;
                    
                    if (searchTerm === '') {
                        found = true;
                    } else {
                        for (let j = 0; j < cells.length; j++) {
                            const cellText = cells[j].textContent.toLowerCase();
                            if (cellText.includes(searchTerm)) {
                                found = true;
                                break;
                            }
                        }
                    }
                    
                    rows[i].style.display = found ? '' : 'none';
                }
            });
        }
    });
</script>

<?php
mysqli_close($koneksi);
?>