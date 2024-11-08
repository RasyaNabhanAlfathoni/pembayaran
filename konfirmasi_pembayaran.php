<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login sebagai admin


// Proses konfirmasi pembayaran
if (isset($_GET['id'])) {
    $id_spp = $_GET['id'];

    // Ambil ID siswa dari pembayaran untuk redirect
    $query = "SELECT id_siswa FROM pembayaran WHERE idspp = '$id_spp'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    $id_siswa = $data['id_siswa'];

    // Update status pembayaran menjadi 'cetak'
    $updateQuery = "UPDATE pembayaran SET ket = 'LUNAS' WHERE idspp = '$id_spp'";
    if (mysqli_query($conn, $updateQuery)) {
        // Redirect ke halaman pembayaran siswa
        header("Location: pembayaran.php?id_siswa=$id_siswa");
        exit();
    } else {
        echo '<div class="alert alert-danger">Gagal mengkonfirmasi pembayaran: ' . mysqli_error($conn) . '</div>';
    }
}
?>
