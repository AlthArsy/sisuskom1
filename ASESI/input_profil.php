<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

session_start();
include "../koneksi.php"; 


if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Asesi') {
    echo "<script>alert('Akses ditolak! Silakan login sebagai Asesi.'); window.location.href='../LOGIN/login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_asesi         = isset($_POST['nama_asesi']) ? trim($_POST['nama_asesi']) : '';
    $nik                = isset($_POST['nik']) ? trim($_POST['nik']) : '';
    $jenis_kelamin      = isset($_POST['jenis_kelamin']) ? trim($_POST['jenis_kelamin']) : '';
    $kebangsaan         = isset($_POST['kebangsaan']) ? trim($_POST['kebangsaan']) : '';
    $alamat_rumah       = isset($_POST['alamat_rumah']) ? trim($_POST['alamat_rumah']) : '';
    $kode_pos           = isset($_POST['kode_pos']) ? trim($_POST['kode_pos']) : '';
    $phone_rumah        = isset($_POST['phone_rumah']) ? trim($_POST['phone_rumah']) : '';
    $phone_kantor       = isset($_POST['phone_kantor']) ? trim($_POST['phone_kantor']) : '';
    $hp                 = isset($_POST['hp']) ? trim($_POST['hp']) : '';
    $email              = isset($_POST['email']) ? trim($_POST['email']) : '';
    $pendidikan         = isset($_POST['pendidikan']) ? trim($_POST['pendidikan']) : '';
    $nama_institusi     = isset($_POST['nama_institusi']) ? trim($_POST['nama_institusi']) : '';
    $jabatan            = isset($_POST['jabatan']) ? trim($_POST['jabatan']) : '';
    $alamat_institusi   = isset($_POST['alamat_institusi']) ? trim($_POST['alamat_institusi']) : '';
    $kode_pos_institusi = isset($_POST['kode_pos_institusi']) ? trim($_POST['kode_pos_institusi']) : '';
    $telp_institusi     = isset($_POST['telp_institusi']) ? trim($_POST['telp_institusi']) : '';
    $fax                = isset($_POST['fax']) ? trim($_POST['fax']) : '';
    $email_institusi    = isset($_POST['email_institusi']) ? trim($_POST['email_institusi']) : '';

    if (
        $nama_asesi && $nik && $jenis_kelamin && $kebangsaan &&
        $alamat_rumah && $kode_pos && $hp && $email && $pendidikan &&
        $nama_institusi && $jabatan && $alamat_institusi && $kode_pos_institusi
    ) {
        $nama_asesi_esc         = mysqli_real_escape_string($koneksi, $nama_asesi);
        $nik_esc                = mysqli_real_escape_string($koneksi, $nik);
        $jenis_kelamin_esc      = mysqli_real_escape_string($koneksi, $jenis_kelamin);
        $kebangsaan_esc         = mysqli_real_escape_string($koneksi, $kebangsaan);
        $alamat_rumah_esc       = mysqli_real_escape_string($koneksi, $alamat_rumah);
        $kode_pos_esc           = mysqli_real_escape_string($koneksi, $kode_pos);
        $phone_rumah_esc        = mysqli_real_escape_string($koneksi, $phone_rumah);
        $phone_kantor_esc       = mysqli_real_escape_string($koneksi, $phone_kantor);
        $hp_esc                 = mysqli_real_escape_string($koneksi, $hp);
        $hp_max_len = 15;
        if (strlen($hp_esc) > $hp_max_len) {
            echo "<script>alert('Nomor HP terlalu panjang. Maksimum {$hp_max_len} karakter.'); window.history.back();</script>";
            exit;
        }
        $email_esc              = mysqli_real_escape_string($koneksi, $email);
        $pendidikan_esc         = mysqli_real_escape_string($koneksi, $pendidikan);
        $nama_institusi_esc     = mysqli_real_escape_string($koneksi, $nama_institusi);
        $jabatan_esc            = mysqli_real_escape_string($koneksi, $jabatan);
        $alamat_institusi_esc   = mysqli_real_escape_string($koneksi, $alamat_institusi);
        $kode_pos_institusi_esc = mysqli_real_escape_string($koneksi, $kode_pos_institusi);
        $telp_institusi_esc     = mysqli_real_escape_string($koneksi, $telp_institusi);
        // Validasi panjang telp institusi (maks 15 sesuai DB)
        if (!empty($telp_institusi_esc) && strlen($telp_institusi_esc) > 15) {
            echo "<script>alert('Nomor telepon institusi terlalu panjang. Maksimum 15 karakter.'); window.history.back();</script>";
            exit;
        }
        $fax_esc                = mysqli_real_escape_string($koneksi, $fax);
        $email_institusi_esc    = mysqli_real_escape_string($koneksi, $email_institusi);
        $email_institusi_max = 15;
        if (!empty($email_institusi_esc)) {
            if (!filter_var($email_institusi_esc, FILTER_VALIDATE_EMAIL)) {
                echo "<script>alert('Format email institusi tidak valid!'); window.history.back();</script>";
                exit;
            }
            if (strlen($email_institusi_esc) > $email_institusi_max) {
                echo "<script>alert('Email institusi terlalu panjang. Maksimum {$email_institusi_max} karakter.'); window.history.back();</script>";
                exit;
            }
        }

        $sql_insert = "INSERT INTO tb_asesi
            (nama_asesi, nik, jenis_kelamin, kebangsaan, alamat_rumah, kode_pos, phone_rumah, phone_kantor, hp, email, pendidikan, nama_institusi, jabatan, alamat_institusi, kode_pos_institusi, telp_institusi, fax, email_institusi)
            VALUES (
                '$nama_asesi_esc', '$nik_esc', '$jenis_kelamin_esc', '$kebangsaan_esc', '$alamat_rumah_esc', '$kode_pos_esc',
                " . ($phone_rumah_esc ? "'$phone_rumah_esc'" : "NULL") . ",
                " . ($phone_kantor_esc ? "'$phone_kantor_esc'" : "NULL") . ",
                '$hp_esc', '$email_esc', '$pendidikan_esc', '$nama_institusi_esc', '$jabatan_esc', '$alamat_institusi_esc', '$kode_pos_institusi_esc',
                " . ($telp_institusi_esc ? "'$telp_institusi_esc'" : "NULL") . ",
                " . ($fax_esc ? "'$fax_esc'" : "NULL") . ",
                " . ($email_institusi_esc ? "'$email_institusi_esc'" : "NULL") . "
            )";
        $query_insert = mysqli_query($koneksi, $sql_insert);

        if (!$query_insert) {
            $error_msg = mysqli_error($koneksi);
            echo "<script>alert('Gagal menyimpan profil!\\n\\nTerjadi error pada query ke database. Silakan cek koneksi, struktur tabel, atau hubungi admin.\\nPesan error: " . addslashes($error_msg) . "');</script>";
        } else {
            $id_asesi = mysqli_insert_id($koneksi);

            $id_user = intval($_SESSION['id_user']);
            $update_q = mysqli_query($koneksi, "UPDATE users SET id_asesi='$id_asesi' WHERE id_user='$id_user'");

            if (!$update_q) {
                $update_err = mysqli_error($koneksi);
                echo "<script>alert('Profil tersimpan, tetapi gagal menghubungkan user ke profil asesi.\\nPesan error: " . addslashes($update_err) . "');</script>";
            } else {
                echo "<script>alert('Profil berhasil disimpan!'); window.location.href='../BERANDA/UTAMA.php';</script>";
                exit;
            }
        }
    } else {
        echo "<script>alert('Semua field yang bertanda * wajib diisi!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .form-box {
            margin: 35px auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 25px 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .form-control {
            width: 99%;
            padding: 5px 7px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-submit {
            margin-top: 16px;
            background: #4A7AFF;
            color: #fff;
            border: none;
            padding: 8px 22px;
            border-radius: 4px;
            font-size: 15px;
            cursor: pointer;
        }
        .btn-submit:hover { background: #325fd6; }
        .section-title {
            font-weight: bold;
            background: #f8faff;
            font-size: 15px;
            border-radius: 3px;
            padding-bottom: 7px;
        }
        .label {
            font-weight: bold;
        }
        .required {
            color: red;
            font-weight: normal;
        }
        .small-text {
            font-size: 12px;
            color: #444;
        }
        @media screen and (max-width: 768px) {
            .form-box {
                min-width: unset;
                margin: 6vw auto;
                padding: 14px 4vw;
            }
            .form-control, select, input[type="text"], input[type="number"] {
                width: 100% !important;
            }

            h2 {
                font-size: 20px;
            }
            .btn-submit {
                width: 100%;
                padding: 10px;
                font-size: 16px;
            }
            td {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }
            .section-title {
                font-size: 14px;
                padding-bottom: 10px;
            }
        }
        input[type="text"],
        select,
        .btn-submit {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <form method="post" autocomplete="off">
            <h2 style="text-align:center; background: #cadbfc; padding: 18px 0 12px 0; border-radius:6px 6px 0 0;">
                FORMULIR ASESI
            </h2>
            <div class="section-title" style="margin-bottom:18px;">
                1: Rincian Data Mohon Agar Mengisi Bagian ( <span style="color:red">*</span> )<br>
                <span class="small-text">Pada bagian ini, cantumkan data pribadi, data pendidikan formal serta data Institusi anda pada saat ini.</span>
                <br>
                <span style="color:red;">Apabila terjadi trouble saat simpan profil, silakan cek pesan error di atas atau hubungi admin/operator untuk memastikan data atau koneksi database sudah sesuai.</span>
            </div>

            <div style="display:flex; flex-direction:column; gap:13px;">
                <div>
                    <label class="label" for="nama_asesi">Nama <span class="required">*</span></label>
                    <input type="text" id="nama_asesi" name="nama_asesi" class="form-control" placeholder="Nama" required>
                </div>
                <div>
                    <label class="label" for="nik">NIK <span class="required">*</span></label>
                    <input type="number" id="nik" name="nik" class="form-control" placeholder="NIK"  value="<?php echo isset($no_reg) ? htmlspecialchars($no_reg) : ''; ?>" required>
                </div>
                <div>
                    <label class="label" for="jenis_kelamin">Jenis Kelamin <span class="required">*</span></label>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="label" for="kebangsaan">Kebangsaan <span class="required">*</span></label>
                    <select id="kebangsaan" name="kebangsaan" class="form-control" placeholder="Kebangsaan" required>
                        <option>Kebangsaan</option>
                        <option value="WNI">WNI</option>
                        <option value="WNA">WNA</option>
                    </select>
                </div>
                <div>
                    <label class="label" for="alamat_rumah">Alamat Rumah <span class="required">*</span></label>
                    <textarea id="alamat_rumah" name="alamat_rumah" class="form-control" placeholder="Alamat Rumah" required></textarea>
                </div>
                <div>
                    <label class="label" for="kode_pos">Kode Pos Rumah <span class="required">*</span></label>
                    <input type="number" id="kode_pos" name="kode_pos" class="form-control" placeholder="Kode Pos" required>
                </div>
                
                <div class="label" style="margin-bottom:2px;">Phone/E-mail</div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <div style="flex:1; min-width:120px;">
                        <label for="phone_rumah" class="small-text">Rumah</label>
                        <input type="text" id="phone_rumah" name="phone_rumah" class="form-control" placeholder="Phone Rumah">
                    </div>
                    <div style="flex:1; min-width:120px;">
                        <label for="phone_kantor" class="small-text">Kantor</label>
                        <input type="text" id="phone_kantor" name="phone_kantor" class="form-control" placeholder="Phone Kantor">
                    </div>
                </div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <div style="flex:1; min-width:120px;">
                        <label for="hp" class="small-text">HP <span class="required">*</span></label>
                        <input type="text" id="hp" name="hp" class="form-control" placeholder="HP" maxlength="15" required> 
                    </div>
                    <div style="flex:1; min-width:120px;">
                        <label for="email" class="small-text">E-mail <span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="E-mail" required>
                    </div>
                </div>

                <div>
                    <label class="label" for="pendidikan">Pendidikan <span class="required">*</span></label>
                    <input type="text" id="pendidikan" name="pendidikan" class="form-control" placeholder="Pendidikan" required>
                </div>
                <div>
                    <label class="label" for="nama_institusi">Nama Institusi <span class="required">*</span></label>
                    <input type="text" id="nama_institusi" name="nama_institusi" class="form-control" placeholder="Nama Institusi" required>
                </div>
                <div>
                    <label class="label" for="jabatan">Jabatan <span class="required">*</span></label>
                    <input type="text" id="jabatan" name="jabatan" class="form-control" placeholder="Jabatan" required>
                </div>
                <div>
                    <label class="label" for="alamat_institusi">Alamat Institusi <span class="required">*</span></label>
                    <input type="text" id="alamat_institusi" name="alamat_institusi" class="form-control" placeholder="Alamat Institusi" required>
                </div>
                <div>
                    <label class="label" for="kode_pos_institusi">Kode Pos Institusi</label>
                    <input type="number" id="kode_pos_institusi" name="kode_pos_institusi" class="form-control" placeholder="Kode Pos Institusi" required>
                </div>

                
                <div class="label" style="margin-bottom:2px;">No. Telp/Fax/E-mail</div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <div style="flex:1; min-width:120px;">
                        <label for="telp_institusi" class="small-text">Telp</label>
                        <input type="text" id="telp_institusi" name="telp_institusi" class="form-control" placeholder="Telp" maxlength="15">
                    </div>
                    <div style="flex:1; min-width:120px;">
                        <label for="fax" class="small-text">Fax</label>
                        <input type="text" id="fax" name="fax" class="form-control" placeholder="Fax">
                    </div>
                </div>
                <div>
                    <label for="email_institusi" class="small-text">E-mail</label>
                    <input type="email" id="email_institusi" name="email_institusi" class="form-control" placeholder="E-mail Kantor" maxlength="15">
                </div>
            </div>
            <button type="submit" class="btn-submit" style="margin-top: 18px;">Simpan Profil</button>
        </form>
    </div>
</body>
</html>