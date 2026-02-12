<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id_user'])) {
    header("Location: ../LOGIN/login.php");
    exit;
}
?>
<!-- <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    body {
        font-family: 'Montserrat', Arial, sans-serif;
        background: #f5f8fa;
        min-height: 100vh;
        margin: 0;
    }
    .l-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        padding: 0;
        max-width: 520px;
        margin: 40px auto 0 auto;
    }
    .header {
        padding: 30px 30px 15px;
        display: flex;
        align-items: center;
        gap: 20px;
        border-bottom: 1px solid #ecf0f1;
    }
    .header i {
        font-size: 44px;
        color: #3751ea;
    }
    .header h2 {
        color: #24365e;
        font-size: 1.40em;
        margin: 0;
        font-weight: 700;
    }
    .form-container {
        padding: 30px;
    }
    .form-group {
        margin-bottom: 24px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2c3e50;
        letter-spacing: 0.1px;
    }
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
        box-sizing: border-box;
        background: #f8fafd;
        color: #24365e;
    }
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        background: #fff;
    }
    .form-group textarea {
        resize: vertical;
        min-height: 64px;
    }
    .form-group input[readonly] {
        background: #f1f3f9;
        color: #888;
    }
    .btn-container {
        display: flex;
        justify-content: center;
        margin-top: 34px;
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
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.23);
    }
    @media (max-width: 700px) {
        .l-container { max-width: 98vw; }
        .form-container { padding: 20px 8px; }
    }
</style>
<div class="l-container">
    <div class="header">
        <i class="fas fa-clipboard-list"></i>
        <h2>Pendaftaran Skema Sertifikasi</h2>
    </div>
    <div class="form-container">
        <form action="../SKEMA/simpan_skema.php" method="POST" autocomplete="off">
            <div class="form-group">
                <label for="no_skema">No Skema</label>
                <input type="text" id="no_skema" name="no_skema" required autocomplete="off" placeholder="Masukkan nomor skema">
            </div>
            <div class="form-group">
                <label for="judul_skema">Judul Skema</label>
                <input type="text" id="judul_skema" name="judul_skema" required placeholder="Masukkan judul skema">
            </div>
            <div class="form-group">
                <label for="standar_kompetensi">Standar Kompetensi Kerja</label>
                <textarea id="standar_kompetensi" name="standar_kompetensi" required placeholder="Masukkan standar kompetensi kerja"></textarea>
            </div>
            <div class="form-group">
                <label for="nama_asesor">Nama Asesor</label>
                <input type="text"
                       id="nama_asesor"
                       value="<?php echo isset($_SESSION['nama_user']) ? htmlspecialchars($_SESSION['nama_user']) : ''; ?>"
                       class="form-control"
                       readonly>
                <input type="hidden" name="id_referensi" value="<?php echo $_SESSION['id_referensi'] ?? ''; ?>">
            </div>
            <div class="btn-container">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Skema
                </button>
            </div>
        </form>
    </div>
</div>
