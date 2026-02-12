<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'Asesor'])) {
    header("Location: ../LOGIN/login.php");
    exit();
}

include '../koneksi.php';

$id_unit = isset($_GET['id_unit']) ? intval($_GET['id_unit']) : 0;

if ($id_unit > 0) {
    $query_skema = "SELECT kode_unit, judul_unit FROM tb_unit_kompetensi WHERE id_unit = ?";
    $stmt_skema = mysqli_prepare($koneksi, $query_skema);
    mysqli_stmt_bind_param($stmt_skema, "i", $id_unit);
    mysqli_stmt_execute($stmt_skema);
    $result_skema = mysqli_stmt_get_result($stmt_skema);
    $skema_data = mysqli_fetch_assoc($result_skema);
    mysqli_stmt_close($stmt_skema);
    
    $query = " 
        SELECT 
            tb_elemen.id_elemen, 
            tb_elemen.nama_elemen, 
            tb_unit_kompetensi.kode_unit, 
            tb_elemen.no_elemen, 
            tb_unit_kompetensi.judul_unit,
            COUNT(tb_kuk.id_kuk) as jumlah_kuk
        FROM tb_elemen
        LEFT JOIN tb_unit_kompetensi ON tb_elemen.id_unit = tb_unit_kompetensi.id_unit
        LEFT JOIN tb_kuk ON tb_elemen.id_elemen = tb_kuk.id_elemen
        WHERE tb_elemen.id_unit = ?
        GROUP BY tb_elemen.id_elemen
        ORDER BY tb_elemen.id_elemen ASC
    ";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_unit);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    
} else {
    if ($_SESSION['role'] === 'Admin') {
    $query = "
        SELECT 
            tb_elemen.id_elemen, 
            tb_unit_kompetensi.id_unit,
            tb_unit_kompetensi.kode_unit, 
            tb_unit_kompetensi.judul_unit,
            tb_asesor.nama_asesor,
            tb_elemen.no_elemen, 
            tb_elemen.nama_elemen,
            COUNT(tb_kuk.id_kuk) as jumlah_kuk
        FROM tb_elemen
        LEFT JOIN tb_unit_kompetensi ON tb_elemen.id_unit = tb_unit_kompetensi.id_unit
        LEFT JOIN tb_skema ON tb_unit_kompetensi.id_skema = tb_skema.id_skema
        LEFT JOIN tb_asesor ON tb_skema.id_asesor = tb_asesor.id_asesor
        LEFT JOIN tb_kuk ON tb_elemen.id_elemen = tb_kuk.id_elemen
        GROUP BY tb_elemen.id_elemen
        ORDER BY tb_unit_kompetensi.id_unit ASC, tb_elemen.id_elemen ASC
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
                    tb_elemen.id_elemen, 
                    tb_unit_kompetensi.id_unit,
                    tb_unit_kompetensi.kode_unit,
                    tb_unit_kompetensi.judul_unit, 
                    tb_elemen.no_elemen, 
                    tb_elemen.nama_elemen,
                    COUNT(tb_kuk.id_kuk) as jumlah_kuk
                FROM tb_elemen
                LEFT JOIN tb_unit_kompetensi ON tb_elemen.id_unit = tb_unit_kompetensi.id_unit
                LEFT JOIN tb_skema ON tb_unit_kompetensi.id_skema = tb_skema.id_skema
                LEFT JOIN tb_asesor ON tb_skema.id_asesor = tb_asesor.id_asesor
                LEFT JOIN tb_kuk ON tb_elemen.id_elemen = tb_kuk.id_elemen
                WHERE tb_skema.id_asesor = ?
                GROUP BY tb_elemen.id_elemen
                ORDER BY tb_unit_kompetensi.id_unit ASC, tb_elemen.id_elemen ASC
            ";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "i", $id_asesor_login);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
        } else {
            $result = mysqli_query($koneksi, "SELECT * FROM tb_unit_kompetensi WHERE 1=0");
        }
    }
}

function getElemenButtonColor($jumlah){
    $color = [
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
    return $color[$jumlah];
}

$units_by_unit = [];
if (isset($result) && $result) {
    while ($row = mysqli_fetch_assoc($result)) {
        if ($id_unit > 0) {
            $units_by_unit[$id_unit][] = $row;
        } else {
            $unit_id = $row['id_unit'];
            if (!isset($units_by_unit[$unit_id])) {
                $units_by_unit[$unit_id] = [
                    'info' => [
                        'kode_unit' => $row['kode_unit'] ?? '',
                        'judul_unit' => $row['judul_unit'] ?? '',
                        'nama_asesor' => $row['nama_asesor'] ?? ''
                    ],
                    'units' => []
                ];
            }
            $units_by_unit[$unit_id]['units'][] = $row;
        }
    }
}
?>
<style>
.jd {
    color: #14305c;
    margin-bottom: 18px;
    font-size: 1.4em;
    font-weight: 600;
}

.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.btn-kembali {
    display: inline-block;
    padding: 10px 20px;
    background: #95a5a6;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-kembali:hover {
    background: #7f8c8d;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(149, 165, 166, 0.3);
}

.btn-tambah {
    display: inline-block;
    padding: 10px 20px;
    background: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-left: 10px;
}

.btn-tambah:hover {
    background: #45a049;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
}

.skema-info {
    background: #e8f4f8;
    padding: 15px 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    border-left: 4px solid #3498db;
}

.skema-info h3 {
    margin: 0 0 10px 0;
    color: #14305c;
    font-size: 1.2em;
}

.skema-info p {
    margin: 5px 0;
    color: #555;
}

.skema-group {
    margin-bottom: 30px;
}

.skema-header {
    background: #14305c;
    color: white;
    padding: 5px 10px;
    border-radius: 5px 5px 0 0;
    margin-top: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.skema-count {
    background: rgba(255, 255, 255, 0.3);
    padding: 5px 15px;
    border-radius: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

th, td {
    border: 1px solid #e7e7eb;
    padding: 10px 14px;
    text-align: left;
    font-size: 0.95em;
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
    text-decoration: none;
    border: 1px solid;
    transition: all 0.3s ease;
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

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #8692af;
    background: #fcfdff;
}

.empty-state i {
    font-size: 4em;
    margin-bottom: 20px;
    opacity: 0.3;
}
.btn-elemen-empty {
    background: #95a5a6;
    color: white;
    border-color: #7f8c8d;
    padding: 8px 16px;
    font-weight: 600;
    opacity: 0.7;
}

.btn-elemen-empty:hover {
    background: #7f8c8d;
}

.btn-elemen-badge {
    padding: 8px 16px;
    color: white;
    border: none;
    font-weight: 600;
    min-width: 50px;
    text-align: center;
}

.btn-elemen-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.btn-lihat-elemen {
    background: #3498db;
    color: white;
    border-color: #2980b9;
    padding: 8px 16px;
}

.btn-lihat-elemen:hover {
    background: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
}

.btn-lihat-elemen i {
    margin-right: 5px;
}

@media screen and (max-width: 768px) {
    .header-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .btn-kembali, .btn-tambah {
        width: 100%;
        text-align: center;
        margin-left: 0;
    }
    
    .skema-header {
        flex-direction: column;
        gap: 10px;
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
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="elemen-container">
    <div class="header-container">
        <h2 class="jd">
            <?php if ($id_unit > 0 && isset($skema_data)): ?>
                Elemen - <?= htmlspecialchars($skema_data['kode_unit']) ?>
            <?php else: ?>
                Daftar Elemen
            <?php endif; ?>
        </h2>
        <div>
            <?php if ($id_unit > 0): ?>
                <a href="../BERANDA/UTAMA.php?page=../UNIT/unit_kompetensi.php" class="btn-kembali">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="UTAMA.php?page=../ELEMEN/From_elemen.php?id_unit=<?= $id_unit ?>" class="btn-tambah">
                    <i class="fas fa-plus"></i> Tambah Elemen
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if ($id_unit > 0 && isset($skema_data)): ?>
        <div class="skema-info">
            <h3>Informasi unit</h3>
            <p><strong>Kode Unit:</strong> <?= htmlspecialchars($skema_data['kode_unit']) ?></p>
            <p><strong>Judul Unit:</strong> <?= htmlspecialchars($skema_data['judul_unit']) ?></p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>No Elemen</th>
                    <th>Nama Elemen</th>
                    <th style="width: 310px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
           <?php if (isset($units_by_unit[$id_unit]) && count($units_by_unit[$id_unit]) > 0):
                $no = 1;
                foreach ($units_by_unit[$id_unit] as $row): 
                    $jumlah_kuk = intval($row['jumlah_kuk'] ?? 0);
                    $color = getElemenButtonColor($jumlah_kuk);
                ?>
                    <tr>
                        <td data-label="No"><?= $no++; ?></td>
                        <td data-label="No Elemen"><?= htmlspecialchars($row['no_elemen']) ?></td>
                        <td data-label="Nama Elemen"><?= htmlspecialchars($row['nama_elemen']) ?></td>
                        <td data-label="Aksi" class="aksi">
                            <a href="../ELEMEN/ubah_elemen.php?id=<?= $row['id_elemen'] ?>" class="btn-ubah">
                                Ubah
                            </a>
                            <a href="../ELEMEN/hapus_elemen.php?id=<?= $row['id_elemen'] ?>" 
                               class="btn-hapus"
                               onclick="return confirm('Yakin ingin menghapus Elemen ini?');">
                                Hapus
                            </a>
                            <?php if ($jumlah_kuk == 0): ?>
                                <a href="UTAMA.php?page=../KUK/From_kuk.php&id_elemen=<?= $row['id_elemen'] ?>" 
                                   class="btn-elemen-empty">
                                    Tambah KuK
                                </a>
                            <?php else: ?>
                                <a href="UTAMA.php?page=../KUK/From_kuk.php&id_elemen=<?= $row['id_elemen'] ?>" 
                                   class="btn-elemen-badge"
                                   style="background-color: <?= $color ?>; border-color: <?= $color ?>;"
                                   title="Tambah KUK">
                                    <i class="fas fa-plus"></i>
                                </a>
                                <a href="UTAMA.php?page=../KUK/KUK.php&id_elemen=<?= $row['id_elemen'] ?>" 
                                   class="btn-lihat-elemen"
                                   title="Lihat KUK">
                                    Lihat
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach;
            else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;color:#8692af;padding:32px;background:#fcfdff;font-size:16px;">
                        Belum ada unit kompetensi untuk skema ini. 
                        <a href="UTAMA.php?page=../UNIT/From_unit_kompetensi.php&id_unit=<?= $id_unit ?>" style="color:#4186e0;">Tambah Unit</a>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
        
    <?php else: ?>
        <?php if (count($units_by_unit) > 0): ?>
            <?php foreach ($units_by_unit as $skema_id => $skema_group): ?>
                <div class="skema-group">
                    <div class="skema-header">
                        <h4>
                            <?= htmlspecialchars($skema_group['info']['kode_unit'] ?? 'N/A') ?>  -
                            <?= htmlspecialchars($skema_group['info']['judul_unit'] ?? 'N/A') ?>
                            <?php if (!empty($skema_group['info']['nama_asesor'])): ?>
                                - <?= htmlspecialchars($skema_group['info']['nama_asesor']) ?>
                            <?php endif; ?>
                        </h4>
                        <span class="skema-count">
                            <?= count($skema_group['units']) ?> Elemen
                        </span>
                    </div>
                            
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>No Elemen</th>
                                <th>Nama Elemen</th>
                                <th style="width: 310px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $no = 1;
                        foreach ($skema_group['units'] as $row): 
                            $jumlah_kuk = intval($row['jumlah_kuk']);
                            $color = getElemenButtonColor($jumlah_kuk);
                        ?> 
                            <tr>
                                <td data-label="No"><?= $no++; ?></td>
                                <td data-label="Kode Unit"><?= htmlspecialchars($row['no_elemen']) ?></td>
                                <td data-label="Judul Unit"><?= htmlspecialchars($row['nama_elemen']) ?></td>
                                <td data-label="Aksi" class="aksi">
                                    <a href="../ELEMEN/ubah_elemen.php?id=<?= $row['id_elemen'] ?>" class="btn-ubah">
                                        Ubah
                                    </a>
                                    <a href="../ELEMEN/hapus_elemen.php?id=<?= $row['id_elemen'] ?>" 
                                       class="btn-hapus"
                                       onclick="return confirm('Yakin ingin menghapus unit kompetensi ini?');">
                                        Hapus
                                    </a>

                                    <?php if ($jumlah_kuk == 0): ?>
                                        <a href="UTAMA.php?page=../KUK/From_kuk.php&id_elemen=<?= $row['id_elemen'] ?>" 
                                           class="btn-elemen-empty">
                                            Tambah KuK
                                        </a>
                                    <?php else: ?>
                                        <a href="UTAMA.php?page=../KUK/From_kuk.php&id_elemen=<?= $row['id_elemen'] ?>" 
                                           class="btn-elemen-badge"
                                           style="background-color: <?= $color ?>; border-color: <?= $color ?>;"
                                           title="Tambah KUK">
                                            <?= $jumlah_kuk > 10 ? '10+' : $jumlah_kuk ?>
                                        </a>
                                        <a href="UTAMA.php?page=../KUK/KUK.php&id_elemen=<?= $row['id_elemen'] ?>" 
                                           class="btn-lihat-elemen"
                                           title="Lihat KUK">
                                            Lihat
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
                        
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>Belum Ada Elemen</h3>
                <p>
                    <?php if ($_SESSION['role'] === 'Asesor'): ?>
                        Kamu belum memiliki Elemen, Silakan tambahkan Elemen ke Unit
                <?php else: ?>
                Belum ada Elemen
                <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
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
</script>

<?php
mysqli_close($koneksi);
?>