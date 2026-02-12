<?php
$koneksi = mysqli_connect("localhost", "root", "Admin123", "sisuskom1");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>