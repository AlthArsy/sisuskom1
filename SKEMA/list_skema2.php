<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Asesor'])) {
    header("Location: ../LOGIN/login.php");
    exit();
}

include '../koneksi.php';

if (mysqli_connect_errno()) {
    die("Gagal koneksi ke database: " . mysqli_connect_error());
}

if ($_SESSION['role'] === 'Admin') {
    $query = "
        SELECT 
            tb_skema.id_skema, 
            tb_skema.nomor_skema, 
            tb_skema.judul_skema, 
            tb_skema.standar_kompetensi_kerja, 
            tb_asesor.nama_asesor,
            COUNT(tb_unit_kompetensi.id_unit) as jumlah_unit
        FROM tb_skema
        LEFT JOIN tb_asesor ON tb_skema.id_asesor = tb_asesor.id_asesor
        LEFT JOIN tb_unit_kompetensi ON tb_skema.id_skema = tb_unit_kompetensi.id_skema
        GROUP BY tb_skema.id_skema
        ORDER BY tb_skema.id_skema DESC
    ";
    $result = mysqli_query($koneksi, $query);
} else if ($_SESSION['role'] === 'Asesor') {
    if (!isset($_SESSION['id_referensi'])) {
        $username = $_SESSION['username'];
        $get_asesor = "SELECT id_asesor FROM tb_asesor WHERE nama_asesor = ?";
        $stmt_asesor = mysqli_prepare($koneksi, $get_asesor);
        mysqli_stmt_bind_param($stmt_asesor, "s", $username);
        mysqli_stmt_execute($stmt_asesor);
        $result_asesor = mysqli_stmt_get_result($stmt_asesor);

        if ($row_asesor = mysqli_fetch_assoc($result_asesor)) {
            $_SESSION['id_referensi'] = $row_asesor['id_asesor'];
        } else {
            $_SESSION['id_referensi'] = 0;
        }
        mysqli_stmt_close($stmt_asesor);
    }
    $id_asesor_login = intval($_SESSION['id_referensi']);
    if ($id_asesor_login > 0) {
        $query = "
            SELECT 
                tb_skema.id_skema, 
                tb_skema.nomor_skema, 
                tb_skema.judul_skema, 
                tb_skema.standar_kompetensi_kerja, 
                tb_asesor.nama_asesor,
                COUNT(tb_unit_kompetensi.id_unit) as jumlah_unit
            FROM tb_skema
            LEFT JOIN tb_asesor ON tb_skema.id_asesor = tb_asesor.id_asesor
            LEFT JOIN tb_unit_kompetensi ON tb_skema.id_skema = tb_unit_kompetensi.id_skema
            WHERE tb_skema.id_asesor = ?
            GROUP BY tb_skema.id_skema
            ORDER BY tb_skema.id_skema DESC
        ";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_asesor_login);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
    } else {
        $result = mysqli_query($koneksi, "SELECT * FROM tb_skema WHERE 1=0");
    }
}

function getUnitButtonColor($jumlah) {
    $colors = [
        0  => '#54b4bbff',
        1  => '#00b9f1ff',
        2  => '#6067c7ff',
        3  => '#1947a8ff',
        4  => '#082da7ff',
        5  => '#b14b4bff',
        6  => '#d13f3fff',
        7  => '#cf3333ff',
        8  => '#ab00eeff',
        9  => '#4f0db8ff',
        10 => '#1e023dff'
    ];
    
    if ($jumlah > 10) $jumlah = 10;
    return $colors[$jumlah];
}
?>
<style>
.jdm {
    color: #14305c;
    margin-bottom: 18px;
    font-size: 1.4em;
}

.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.btn-tambah {
    display: inline-block;
    padding: 10px 20px;
    background: #4186e0;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}

.btn-tambah:hover {
    background: #2761ba;
}

.message {
    padding: 15px 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    font-weight: 500;
}

.message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
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

.aksi {
    white-space: nowrap;
}

.aksi a {
    display: inline-block;
    font-size: 13px;
    padding: 6px 14px;
    border-radius: 5px;
    margin-right: 5px;
    margin-bottom: 5px;
    text-decoration: none;
    border: 1px solid;
}

.aksi a.btn-ubah {
    background: #f4f8fd;
    color: #1877cc;
    border-color: #c9e1fb;
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

.btn-unit-empty {
    background: #95a5a6;
    color: white;
    border-color: #7f8c8d;
    padding: 8px 16px;
    font-weight: 600;
    cursor: not-allowed;
    opacity: 0.7;
}

.btn-unit-empty:hover {
    background: #7f8c8d;
}

.btn-unit-badge {
    position: relative;
    padding: 8px 16px;
    color: white;
    border: none;
    font-weight: 600;
    min-width: 50px;
    text-align: center;
}

.btn-unit-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.btn-unit-badge .badge-number {
    position: absolute;
    background: #fff;
    color: #333;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.btn-lihat-unit {
    background: #3498db;
    color: white;
    border-color: #2980b9;
    padding: 8px 16px;
}

.btn-lihat-unit:hover {
    background: #19405aff;
}

.btn-lihat-unit i {
    margin-right: 5px;
}

@media screen and (max-width: 768px) {
    .header-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .btn-tambah {
        width: 100%;
        text-align: center;
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
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="s-container">
    <div class="header-container">
        <h2 class="jdm">Daftar Skema Sertifikasi</h2>
        <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Asesor'): ?>
            <a href="UTAMA.php?page=../SKEMA/Form_Skema.php" class="btn-tambah">
                <i class="fas fa-plus"></i> Tambah Skema
            </a>
        <?php endif; ?>
    </div>
    
    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="message <?php echo $_SESSION['tipe']; ?>">
            <?php 
                echo htmlspecialchars($_SESSION['pesan']); 
                unset($_SESSION['pesan']);
                unset($_SESSION['tipe']);
            ?>
        </div>
    <?php endif; ?>
    
    <table>
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>Nomor Skema</th>
                <th>Judul Skema</th>
                <th>Standar Kompetensi Kerja</th>
                <th>Asesor</th>
                <th style="width: 100px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($result) && mysqli_num_rows($result) > 0):
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)): 
                    $jumlah_unit = intval($row['jumlah_unit']);
                    $color = getUnitButtonColor($jumlah_unit);
            ?>
                    <tr>
                        <td data-label="No"><?= $no++; ?></td>
                        <td data-label="Nomor Skema"><?= htmlspecialchars($row['nomor_skema']) ?></td>
                        <td data-label="Judul Skema"><?= htmlspecialchars($row['judul_skema']) ?></td>
                        <td data-label="Standar Kompetensi Kerja"><?= htmlspecialchars($row['standar_kompetensi_kerja']) ?></td>
                        <td data-label="Asesor"><?= htmlspecialchars($row['nama_asesor'] ?? '-') ?></td>
                        <td data-label="Aksi" class="aksi">
                                <a href="UTAMA.php?page=../UNIT/unit_kompetensi.php&id_skema=<?= $row['id_skema'] ?>" 
                                   class="btn-lihat-unit"
                                   title="Lihat Unit Kompetensi">
                                   Lihat Unit
                                </a>
                        </td>
                    </tr>
                <?php endwhile;
            else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;color:#8692af;padding:32px;background:#fcfdff;font-size:16px;border-radius:7px;">
                        <?php if ($_SESSION['role'] === 'Asesor'): ?>
                            Anda belum memiliki skema sertifikasi.
                        <?php else: ?>
                            Belum ada data skema.
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
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
</script>

<?php
mysqli_close($koneksi);
?>