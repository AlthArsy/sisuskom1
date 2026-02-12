<?php
include "koneksi.php";

$nama            = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$no_ktp          = isset($_POST['no_ktp']) ? trim($_POST['no_ktp']) : '';
$tempat_lahir    = isset($_POST['tempat_lahir']) ? trim($_POST['tempat_lahir']) : '';
$jenis_kelamin   = isset($_POST['jenis_kelamin']) ? trim($_POST['jenis_kelamin']) : '';
$kebangsaan      = isset($_POST['kebangsaan']) ? trim($_POST['kebangsaan']) : 'Indonesia';
$alamat_rumah    = isset($_POST['alamat_rumah']) ? trim($_POST['alamat_rumah']) : '';
$kode_pos        = isset($_POST['kode_pos']) ? trim($_POST['kode_pos']) : '';
$phone_rumah     = isset($_POST['phone_rumah']) ? trim($_POST['phone_rumah']) : '';
$phone_kantor    = isset($_POST['phone_kantor']) ? trim($_POST['phone_kantor']) : '';
$hp              = isset($_POST['hp']) ? trim($_POST['hp']) : '';
$email           = isset($_POST['email']) ? trim($_POST['email']) : '';
$kualifikasi     = isset($_POST['kualifikasi']) ? trim($_POST['kualifikasi']) : ''; // kualifikasi_pendidikan

$nama_institusi  = isset($_POST['nama_institusi']) ? trim($_POST['nama_institusi']) : '';
$jabatan         = isset($_POST['jabatan']) ? trim($_POST['jabatan']) : '';
$alamat_kantor   = isset($_POST['alamat_kantor']) ? trim($_POST['alamat_kantor']) : '';
$kode_pos_kantor = isset($_POST['kode_pos_kantor']) ? trim($_POST['kode_pos_kantor']) : $kode_pos;
$telp_kantor     = isset($_POST['telp']) ? trim($_POST['telp']) : '';
$fax             = isset($_POST['fax']) ? trim($_POST['fax']) : '';
$email_kantor    = isset($_POST['email_kantor']) ? trim($_POST['email_kantor']) : '';

$tgl_lahir = '';
if (!empty($tempat_lahir) && strpos($tempat_lahir, '/') !== false) {
    $parts = explode('/', $tempat_lahir, 2);
    $tempat_lahir = trim($parts[0]);
    $tgl_lahir = trim($parts[1]);
}

if (!empty($nama) && !empty($no_ktp) && !empty($tempat_lahir) && !empty($tgl_lahir) && !empty($jenis_kelamin) && !empty($alamat_rumah) && !empty($hp) && !empty($email) && !empty($kualifikasi)) {

    $sql1 = "INSERT INTO FR1a (
        nama, 
        no_ktp, 
        tempat_lahir,
        tgl_lahir,
        jenis_kelamin,
        kebangsaan,
        alamat_rumah,
        kode_pos,
        phone_rumah,
        phone_kantor,
        hp,
        email,
        kualifikasi_pendidikan
    ) VALUES (
        '".mysqli_real_escape_string($koneksi, $nama)."',
        '".mysqli_real_escape_string($koneksi, $no_ktp)."',
        '".mysqli_real_escape_string($koneksi, $tempat_lahir)."',
        '".mysqli_real_escape_string($koneksi, $tgl_lahir)."',
        '".mysqli_real_escape_string($koneksi, $jenis_kelamin)."',
        '".mysqli_real_escape_string($koneksi, $kebangsaan)."',
        '".mysqli_real_escape_string($koneksi, $alamat_rumah)."',
        '".mysqli_real_escape_string($koneksi, $kode_pos)."',
        '".mysqli_real_escape_string($koneksi, $phone_rumah)."',
        '".mysqli_real_escape_string($koneksi, $phone_kantor)."',
        '".mysqli_real_escape_string($koneksi, $hp)."',
        '".mysqli_real_escape_string($koneksi, $email)."',
        '".mysqli_real_escape_string($koneksi, $kualifikasi)."'
    )";

    $sql2 = "INSERT INTO FR1b (
        nama_institusi, 
        jabatan, 
        alamat_kantor,
        kode_pos_kantor,
        telp_kantor,
        fax,
        email_kantor
    ) VALUES (
        '".mysqli_real_escape_string($koneksi, $nama_institusi)."',
        '".mysqli_real_escape_string($koneksi, $jabatan)."',
        '".mysqli_real_escape_string($koneksi, $alamat_kantor)."',
        '".mysqli_real_escape_string($koneksi, $kode_pos_kantor)."',
        '".mysqli_real_escape_string($koneksi, $telp_kantor)."',
        '".mysqli_real_escape_string($koneksi, $fax)."',
        '".mysqli_real_escape_string($koneksi, $email_kantor)."'
    )";

    $result1 = mysqli_query($koneksi, $sql1);
    $result2 = mysqli_query($koneksi, $sql2);

    if ($result1 && $result2) {
        echo "<script>alert('Data berhasil disimpan!'); window.location.href='FR.APL.01.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan pada input data: ".mysqli_error($koneksi)."'); window.location.href='FR.APL.01.php';</script>";
    }
} else {

    echo "<script>alert('Harap isi data wajib dengan benar!'); window.location.href='FR.APL.01.php';</script>";
}

mysqli_close($koneksi);
?>
