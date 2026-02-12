<?php
session_start();
include "../koneksi.php";

// Cek apakah sudah login sebagai Asesi
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Asesi') {
    echo "<script>alert('Akses ditolak! Silakan login sebagai Asesi.'); window.location.href='../LOGIN/login.php';</script>";
    exit;
}

// Jika sudah submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
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

    // Validasi wajib isi (untuk kolom NOT NULL)
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
        $email_esc              = mysqli_real_escape_string($koneksi, $email);
        $pendidikan_esc         = mysqli_real_escape_string($koneksi, $pendidikan);
        $nama_institusi_esc     = mysqli_real_escape_string($koneksi, $nama_institusi);
        $jabatan_esc            = mysqli_real_escape_string($koneksi, $jabatan);
        $alamat_institusi_esc   = mysqli_real_escape_string($koneksi, $alamat_institusi);
        $kode_pos_institusi_esc = mysqli_real_escape_string($koneksi, $kode_pos_institusi);
        $telp_institusi_esc     = mysqli_real_escape_string($koneksi, $telp_institusi);
        $fax_esc                = mysqli_real_escape_string($koneksi, $fax);
        $email_institusi_esc    = mysqli_real_escape_string($koneksi, $email_institusi);

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

        if ($query_insert) {
            $id_asesi = mysqli_insert_id($koneksi);

            // update users.id_referensi dengan id_asesi yang baru didaftarkan
            $id_user = intval($_SESSION['id_user']);
            mysqli_query($koneksi, "UPDATE users SET id_referensi='$id_asesi' WHERE id_user='$id_user'");

            // Sukses, redirect ke beranda
            echo "<script>alert('Profil berhasil disimpan!'); window.location.href='../BERANDA/UTAMA.php';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal menyimpan profil!');</script>";
        }
    } else {
        echo "<script>alert('Semua field yang bertanda * wajib diisi!');</script>";
    }
}
// Form input profil asesi
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FR.APL.01 - Formulir Permohonan Sertifikasi Kompetensi</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
        }
        h2 {
            margin: 0;
        }
        .form-box {
            background: #fff;
            margin: 32px auto;
            border-radius: 10px;
            max-width: 930px;
            box-shadow: 0 2px 16px rgb(4 100 160 / 8%), 0 1.5px 7px rgb(80 100 150 / 8%);
            padding: 32px;
        }
        .section-title {
            background: #e0e0e0;
            padding: 12px 22px;
            font-weight: bold;
            font-size: 1.1em;
            border-radius: 7px 7px 0 0;
            margin-bottom: 0px;
        }
        .info-table, .competency-table, .requirement-table, .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
            box-shadow: 0 0.5px 1px rgba(80 100 150 / 8%);
            background: #fff;
            border-radius: 7px;
            overflow: hidden;
        }
        .info-table td, .competency-table td, .competency-table th,
        .requirement-table th, .requirement-table td, .signature-table td {
            border: 1px solid #b6c7de;
            padding: 10px 10px;
            vertical-align: top;
            transition: background 0.2s;
        }
        .info-table .label, .competency-table th, .requirement-table th {
            background-color: aliceblue;
            font-weight: bold;
        }
        .info-table .label {
            width: 27%;
        }
        .form-label {
            font-weight: bold;
        }
        .form-control, select, textarea, input[type="text"], input[type="date"], input[type="number"], input[type="email"] {
            width: 98%;
            font-family: inherit;
            font-size: 1em;
            border: 1px solid #b6c7de;
            border-radius: 5px;
            padding: 6px 10px;
            margin-top: 3px;
            margin-bottom: 3px;
            background: #f8fcff;
            box-sizing: border-box;
        }
        select {
            height: 34px;
        }
        textarea {
            min-height: 45px;
            resize: vertical;
        }

        .competency-table th {
            text-align: center;
            font-weight: bold;
        }
        .requirement-table th {
            text-align: center;
            font-weight: bold;
        }
        .group-caption {
            font-weight: 600;
            background: #f4faff;
            letter-spacing: 1px;
            padding-left: 6px;
            padding-top: 14px;
        }
        .small-text {
            font-size: 0.90em;
            color: #888;
        }

        .signature-table {
            margin-top: 26px;
            border: none;
            background: none;
            box-shadow: none;
        }
        .signature-table-td {
            padding-left: 16px;
            padding-right: 16px;
            vertical-align: top;
            border: none;
            background: none;
        }
        .input-signature {
            width: 200px;
            margin: 4px 0 8px 0;
        }
        .ttd-preview {
            display:none; 
            max-width:200px; 
            max-height:100px; 
            margin-top:10px; 
            border-radius: 5px;
            box-shadow: 0 2px 10px #4c70ff18;
        }
        .form-note {
            color: #555;
            font-size: .9em;
        }
        .mt-2 {
            margin-top: 1em;
        }
        .mb-2 {
            margin-bottom: 1em;
        }
        .btn-submit {
            background: #2574a9;
            color: #fff;
            padding: 12px 0;
            border: none;
            width: 100%;
            font-size: 17px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s;
        }
        .btn-submit:hover {
            background: #245077;
        }
        .required {
            color: #D00;
            font-weight: bold;
        }
        .section-title { margin-top:25px; font-weight:600; color:#2574a9;}
    </style>
</head>
<body>
    <div class="form-box">
        <form action="prosesFR1.php" method="post" autocomplete="off" enctype="multipart/form-data">
            <table class="info-table">
                <tr>
                    <td colspan="4" style="text-align: center; background: #cadbfc;">
                        <h2>FR.APL.01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI</h2>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="section-title">
                        Bagian 1: Rincian Data Pemohon Sertifikasi<br>
                        <span class="small-text">Pada bagian ini, cantumkan data pribadi, data pendidikan formal serta data pekerjaan anda pada saat ini.</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Nama<span class="required">*</span></td>
                    <td>:</td>
                    <td colspan="2"><input type="text" name="nama_asesi" class="form-control" placeholder="Nama" required></td>
                </tr>
                <tr>
                    <td class="label">NIK<span class="required">*</span></td>
                    <td>:</td>
                    <td colspan="2"><input type="text" name="nik" class="form-control" placeholder="NIK" required></td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin<span class="required">*</span></td>
                    <td>:</td>
                    <td colspan="2">
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="label">Kebangsaan<span class="required">*</span></td>
                    <td>:</td>
                    <td colspan="2">
                        <input name="kebangsaan" class="form-control" placeholder="Kebangsaan" required>
                        </input>
                    </td>
                </tr>
                <tr>
                    <td class="label">Alamat Rumah <span class="required">*</span></td>
                    <td>:</td>
                    <td colspan="2"><textarea name="alamat_rumah" class="form-control" placeholder="Alamat Rumah" required></textarea></td>
                </tr>
                <tr>
                    <td class="label">Kode Pos Rumah <span class="required">*</span></td>
                    <td>:</td>
                    <td colspan="2"><input type="number" name="kode_pos" class="form-control" placeholder="Kode Pos" required></td>
                </tr>
                <tr>
                    <td class="label" rowspan="2">Phone/E-mail</td>
                    <td rowspan="2">:</td>
                    <td>
                        Rumah: 
                        <input type="text" name="phone_rumah" class="form-control" placeholder="Phone Rumah">
                    </td>
                    <td>
                        Kantor:
                        <input type="text" name="phone_kantor" class="form-control" placeholder="Phone Kantor">
                    </td>
                </tr>
                <tr>
                    <td>
                        HP:<span class="required">*</span>
                        <input type="number" name="hp" class="form-control" placeholder="HP" required>
                    </td>
                    <td>
                        E-mail:<span class="required">*</span>
                        <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                    </td>
                </tr>
                <tr>
                    <td class="label">Pendidikan <span class="required">*</span></td>
                    <td>:</td>
                    <td colspan="2"><input type="text" name="pendidikan" class="form-control" placeholder="Pendidikan" required></td>
                </tr>
                <tr>
                    <td class="label">Nama Institusi <span class="required">*</span></td>
                    <td>:</td>
                    <td colspan="2"><input type="text" name="nama_institusi" class="form-control" placeholder="Nama Institusi" required></td>
                </tr>
                <tr>
                    <td class="label">Jabatan <span class="required">*</span></td>
                    <td>:</td>
                    <td colspan="2"><input type="text" name="jabatan" class="form-control" placeholder="Jabatan" required></td>
                </tr>
                <tr>
                    <td class="label">Alamat Institusi <span class="required">*</span></td>
                    <td>:</td>
                    <td colspan="2"><input type="text" name="alamat_institusi" class="form-control" placeholder="Alamat Institusi" required></td>
                </tr>
                <tr>
                    <td class="label">Kode Pos Institusi</td>
                    <td>:</td>
                    <td colspan="2"><input type="number" name="kode_pos_institusi" class="form-control" placeholder="Kode Pos Institusi" required></td>
                </tr>
                <tr>
                    <td class="label" rowspan="2">No. Telp/Fax/E-mail</td>
                    <td rowspan="2">:</td>
                    <td>
                        Telp:
                        <input type="number" name="telp_institusi" class="form-control" placeholder="Telp">
                    </td>
                    <td>
                        Fax:
                        <input type="text" name="fax" class="form-control" placeholder="Fax">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        E-mail:
                        <input type="email" name="email_institusi" class="form-control" placeholder="E-mail Kantor">
                    </td>
                </tr>
            </table>
            <button type="submit" class="btn-submit">Simpan Profil</button>
        </form>
    </div>
</body>
</html>