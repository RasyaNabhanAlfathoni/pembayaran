<?php
session_start();
include 'koneksi.php';

// Pastikan admin sudah login
if (!isset($_SESSION['admin'])) {
    header('Location: loginauth.php');
    exit();
}

if (isset($_GET['act'])) {
    $idspp = $_GET['id'];
    $nis = $_GET['nis'];
    $id_admin = $_SESSION['admin']; // Mengambil ID admin dari sesi

    if ($_GET['act'] == 'bayar') {
        $tglbayar = date("Y-m-d");
        $nobayar = date('dmYHsis'); // Menggunakan format tanggal yang unik sebagai nomor bayar

        // Update data pembayaran untuk konfirmasi pembayaran
        $byr = mysqli_query($conn, "UPDATE pembayaran SET
            nobayar = '$nobayar',
            tglbayar = '$tglbayar',
            ket = 'LUNAS',
            id_admin = '$id_admin'
            WHERE idspp = '$idspp'");

        if ($byr) {
            header('Location: pembayaran.php?nis=' . $nis);
            exit();
        } else {
            echo "<script>alert('Gagal mengonfirmasi pembayaran.')</script>";
        }
    } elseif ($_GET['act'] == 'batal') {
        // Proses pembatalan pembayaran
        $batal = mysqli_query($conn, "UPDATE pembayaran SET
            nobayar = NULL,
            tglbayar = NULL,
            ket = NULL,
            id_admin = NULL
            WHERE idspp = '$idspp'");

        if ($batal) {
            header('Location: pembayaran.php?nis=' . $nis);
            exit();
        } else {
            echo "<script>alert('Gagal membatalkan pembayaran.')</script>";
        }
    }
}

