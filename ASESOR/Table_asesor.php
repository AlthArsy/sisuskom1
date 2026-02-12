<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../koneksi.php";

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    echo "<script>alert('Akses ditolak! Silakan login terlebih dahulu.'); window.location.href='../LOGIN/login.php';</script>";
    exit;
}

$query_all = "SELECT FROM tb_asesor ORDER BY no_reg ASC";
$result_all = mysqli_query($koneksi, $query_all);
$all_asesor = [];
while ($row = mysqli_fetch_assoc($result_all)) {
    $all_asesor[] = $row; 
}

$display_asesor = $all_asesor;
$search_performed = false;
$search_criteria = [
    'id_asesor' => '',
    'no_reg' => '',
    'nama_asesor' => ''
];

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $id_asesor = isset($_POST['id_asesor']) ? trim($_POST['id_asesor']) : '';

    $search_criteria = [
        'is-asesor' => $id_asesor,
        'no_reg' => $no_reg,
        'nama_asesor' => $nama_asesor
    ];

    $search_performed = true;

    $query = "SELECT * FROM tb_asesor WHERE 1=1";
    $params = [];
    $types = '';

    if (!empty($id_asesor)) {
        $query .= " AND id_asesor = ?";
        $params[] = $id_asesor;
        $types .= 'i';
    }
    if (!empty($no_reg)) {
        $query .= " AND no_reg LIKE ?";
        $params[] = '%' . $no_reg . '%';
        $types .= 's';
    }
    if (!empty($nama_asesor)) {
        $query .= " AND nama_asesor LIKE ?";
        $params[] = '%' . $nama_asesor . '%';
        $types .= 's';
    }
    $query .= " ORDER BY no_reg ASC";
    if (empty($params)) {
        $search_results = $all_asesor;
    } else {
        $stmt = mysqli_prepare($koneksi, $query);
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars(mysqli_error($koneksi)));
        }

        if (!empty($types)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $search_results = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $search_results[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<style>
    
    .btn {
        display: inline-block;
        padding: 6px 14px;
        border: none;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background-color: #007bff;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #0056b3;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    
    .btn-secondary:hover {
        background-color: #545b62;
    }
    
    .btn-sm {
        padding: 4px 10px;
        font-size: 12px;
    }
    
    .btn-view {
        background-color: #17a2b8;
        color: white;
    }
    
    .btn-view:hover {
        background-color: #117a8b;
    }
    
    .btn-edit {
        background-color: #28a745;
        color: white;
    }
    
    .btn-edit:hover {
        background-color: #218838;
    }
    
    .btn-delete {
        background-color: #dc3545;
        color: white;
    }
    
    .btn-delete:hover {
        background-color: #c82333;
    }
    
    
    .form-control {
        display: block;
        width: 100%;
        padding: 8px 12px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    
    .alert {
        padding: 12px 20px;
        margin-bottom: 20px;
        border-radius: 4px;
        background-color: #d1ecf1;
        color: #004085;
        border: 1px solid #bee5eb;
    }
    
    .btn-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
   
    .content-card.TBS-full-width {
        margin-left: -30px;
        margin-right: -30px;
    }
    
    .TBS-container {
        max-width: 100%;
        margin: 0;
        padding: 30px;
    }

    .TBS-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .TBS-header h1 {
        font-size: 2.5em;
        margin-bottom: 10px;
        color: #333;
    }
    
    .TBS-header p {
        font-size: 1.2em;
        color: #666;
    }
    
 
    .TBS-user-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .TBS-user-info span {
        font-weight: bold;
    }
    
   
    .TBS-search-box {
        margin-bottom: 30px;
    }
    
    .search-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        align-items: flex-end;
    }
    
    .TBS-form-group {
        margin-bottom: 0;
    }
    
    .TBS-form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: #333;
    }
    
   
    .TBS-results-section {
        margin-top: 30px;
    }
    
    .TBS-results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .TBS-results-header h2 {
        margin: 0;
        color: #333;
    }
    
    .results-count {
        color: #666;
    }
    
    
    .table-container {
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    table th,
    table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    
    table th {
        background-color: #f5f5f5;
        font-weight: 600;
        color: #333;
    }
    
    table tbody tr:hover {
        background-color: #f9f9f9;
    }
    
    .TBS-field-label {
        display: none;
        font-weight: bold;
        color: #555;
    }
    
    .TBS-field-value {
        font-size: 1em;
        color: #333;
    }
    
    .TBS-action-buttons {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }
    
    .TBS-action-buttons a {
        text-decoration: none;
        white-space: nowrap;
    }
    
 
    @media (max-width: 768px) {
        .TBS-header h1 {
            font-size: 1.8em;
        }
        
        .TBS-user-info {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }
        
        .search-form {
            grid-template-columns: 1fr;
        }
        
        .TBS-results-header {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }
        
        .TBS-field-label {
            display: block;
        }
        
        table th {
            display: none;
        }
        
        table tbody tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        table td {
            display: block;
            text-align: right;
            border: none;
            padding-left: 50%;
            position: relative;
            padding-bottom: 10px;
        }
        
        table td:before {
            content: attr(data-label);
            position: absolute;
            left: 12px;
            font-weight: bold;
            text-align: left;
            color: #333;
        }
    }
</style>
<script>
function confirmDelete(id, nama) {
        if (confirm("Apakah Anda yakin ingin menghapus data asesor:\n" + nama + "?")) {
            window.location.href = "#?id=" + id;
        }
        return false;
    }
    
    function clearForm() {
        document.getElementById('id_asesor').value = '';
        document.getElementById('no_reg').value = '';
        document.getElementById('nama_asesor').value = '';
        return false;
    }
</script>
<div class="content-card TBS-full-width">
    <div class="TBS-container">
        <div class="TBS-header">
            <h1>Data Asesor</h1>
            <p>Pencarian dan Pengelolaan Data Asesor</p>
        </div>
        <!-- <div class="TBS-user-info">
            <div>
                Logged in sebagai: <span><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span> 
                (Role: <span><?php echo htmlspecialchars($_SESSION['role'] ?? ''); ?></span>)
            </div> -->
            <!-- <div>
                <a href="../BERANDA/UTAMA.php" class="btn btn-secondary btn-sm">← Kembali</a>
            </div> -->
        </div>
        <div class="TBS-search-box">
            <form method="post" action="" class="search-form">
                <div class="TBS-form-group">
                    <label for="id_asesor">ID Asesor</label>
                    <input type="number" id="id_asesor" name="id_asesor" class="form-control" 
                           placeholder="Masukkan ID Asesor" value="<?php echo htmlspecialchars($search_criteria['id_asesor']); ?>">
                </div>
                <div class="TBS-form-group">
                    <label for="no_reg">No Reg</label>
                    <input type="text" id="no_reg" name="no_reg" class="form-control" 
                           placeholder="Masukkan No Reg" value="<?php echo htmlspecialchars($search_criteria['no_reg']); ?>">
                </div>
                <div class="TBS-form-group">
                    <label for="nama_asesor">Nama Asesor</label>
                    <input type="text" id="nama_asesor" name="nama_asesor" class="form-control" 
                           placeholder="Masukkan Nama" value="<?php echo htmlspecialchars($search_criteria['nama_asesor']); ?>">
                </div>
                <div class="form-group btn-group">
                    <button type="submit" name="search" class="btn btn-primary">
                         Cari Data
                    </button>
                    <button type="button" onclick="clearForm()" class="btn btn-secondary">
                         Bersihkan
                    </button>
                </div>
            </form>
        </div>
        <div class="TBS-results-section">
            <div class="TBS-results-header">
                <h2>Hasil Pencarian</h2>
                <div class="results-count">
                    <?php if ($search_performed): ?>
                        Ditemukan: <span><?php echo count($search_results); ?></span> data asesor
                    <?php else: ?>
                        Silakan masukkan kriteria pencarian
                    <?php endif; ?>
                </div>
            </div>
                    
            <?php if ($search_performed && count($search_results) === 0): ?>
                <div class="alert alert-info">
                     Tidak ditemukan data asesor yang sesuai dengan kriteria pencarian.
                    <?php if (empty($search_criteria['id_asesor']) && empty($search_criteria['no_reg']) && empty($search_criteria['nama_asesor'])): ?>
                        Silakan isi salah satu kriteria pencarian.
                    <?php endif; ?>
                </div>
            <?php endif; ?>
                    
            <?php if (count($search_results) > 0): ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="15%">No Reg</th>
                                <th width="10%">Nama Asesor</th>
                                <th width="10%">Jenis Kelamin</th>
                                <th width="10%">Alamat</th>
                                <th width="20%">Aksi</th>
                        </thead>
                        <tbody>
                            <?php foreach ($search_results as $row): ?>
                                <tr>
                                    <td>
                                        <span class="TBS-field-label">ID</span>
                                        <div class="TBS-field-value"><?php echo htmlspecialchars($row['id_asesor']); ?></div>
                                    </td>
                                    <td>
                                        <span class="TBS-field-label">No Reg</span>
                                        <div class="TBS-field-value"><?php echo htmlspecialchars($row['no_reg']); ?></div>
                                    </td>
                                    <td>
                                        <span class="TBS-field-label">NIK</span>
                                        <div class="TBS-field-value"><?php echo htmlspecialchars($row['nama_asesor']); ?></div>
                                    </td>
                                    <td>
                                        <span class="TBS-field-label">Jenis Kelamin</span>
                                        <div class="TBS-field-value"><?php echo htmlspecialchars($row['jenis_kelamin']); ?></div>
                                    </td>
                                    <td>
                                        <span class="TBS-field-label">Alamat</span>
                                        <div class="TBS-field-value"><?php echo htmlspecialchars($row['alamat']); ?></div>
                                    </td>
                                    <td>
                                        <div class="TBS-action-buttons">
                                            <a href="UTAMA.php?page=../ASESOR/edit.php&id=<?php echo $row['id_asesor']; ?>" 
                                               class="btn btn-edit btn-sm"> Edit</a>
                                            <a href="#" onclick="return confirmDelete(<?php echo $row['id_asesor']; ?>, '<?php echo addslashes($row['nama_asesor']); ?>')" 
                                               class="btn btn-delete btn-sm"> Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>