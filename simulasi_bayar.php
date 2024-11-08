<?php
session_start(); // Pastikan session sudah dimulai
if (!isset($_SESSION['id_siswa'])) {
    header('Location: loginauth.php');
    exit();
}

include 'headersiswa.php';
include 'koneksi.php';
require_once 'midtrans-php-master/Midtrans.php'; // Pastikan path ini sesuai dengan struktur direktori Anda

// Set your Merchant Server Key
\Midtrans\Config::$serverKey = 'SB-Mid-server-ZERs9SBm4s3qrPbz1yObHeNE';
\Midtrans\Config::$isProduction = false; // Set to true for production
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

$snapToken = null; // Inisialisasi token pembayaran

if (isset($_GET['id'])) {
    $id_spp = $_GET['id'];

    // Ambil data pembayaran dari database
    $query = "SELECT * FROM pembayaran WHERE idspp = '$id_spp'";
    $exec = mysqli_query($conn, $query);
    $res = mysqli_fetch_assoc($exec);

    // Pastikan data pembayaran ditemukan
    if (!$res) {
        echo '<div class="alert alert-danger">Data pembayaran tidak ditemukan.</div>';
        exit();
    }

    // Ambil data siswa
    $id_siswa = $_SESSION['id_siswa'];
    $querySiswa = "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'";
    $execSiswa = mysqli_query($conn, $querySiswa);
    $siswa = mysqli_fetch_assoc($execSiswa);

    // Tentukan jumlah yang akan dibayar
    $amount = $res['jumlah'];

    // Siapkan data transaksi
    $order_id = uniqid();
    $params = array(
        'transaction_details' => array(
            'order_id' => $order_id,
            'gross_amount' => $amount,
        ),
        'customer_details' => array(
            'first_name' => isset($siswa['nama_siswa']) ? $siswa['nama_siswa'] : 'Siswa',
            'last_name' => '',
            'email' => isset($siswa['email']) ? $siswa['email'] : 'default@example.com', // Email default jika tidak ada
            'phone' => isset($siswa['phone']) ? $siswa['phone'] : '0000000000', // Nomor telepon default jika tidak ada
        ),
        'custom_fields' => array(
        array(
            'name' => 'callback_url',
            'value' => 'http://localhost/bayar_spp/midtrans_callback.php' // Ganti dengan URL lengkap yang benar
        ),
    ),
    );

    // Ambil Snap Token
    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        // Set zona waktu sesuai kebutuhan Anda
        date_default_timezone_set('Asia/Jakarta');

        // Set tanggal bayar ke waktu saat ini
        $tgl_bayar = date('Y-m-d');
        
        // Update status pembayaran di database menjadi 'MENUNGGU'
        $updateQuery = "UPDATE pembayaran SET ket = 'MENUNGGU', order_id = '$order_id', tglbayar = '$tgl_bayar' WHERE idspp = '$id_spp'";
        if (!mysqli_query($conn, $updateQuery)) {
            echo '<div class="alert alert-danger">Gagal memperbarui status pembayaran: ' . mysqli_error($conn) . '</div>';
            exit();
        }
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">Gagal mendapatkan token pembayaran: ' . htmlspecialchars($e->getMessage()) . '</div>';
        exit();
    }
    
} else {
    echo '<div class="alert alert-danger">ID pembayaran tidak ditemukan.</div>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulasi Pembayaran</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

     <!-- Custom styles for this template-->
     <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #343a40;
        }

        .button-group {
            display: flex;
            justify-content: space-between; /* Membuat tombol berada di ujung kiri dan kanan */
            margin-top: 20px; /* Tambahkan jarak atas untuk button group */
        }

        .btn-primary, .btn-secondary {
            width: 48%; /* Lebar tombol 48% agar keduanya muat dalam satu baris */
        }
    </style>
</head>

<body>

<div class="container">
    <h1>Lakukan Pembayaran</h1>
    <p>Silakan klik tombol di bawah ini untuk melanjutkan pembayaran:</p>
    <div class="button-group"> <!-- Group tombol -->
        <a href="siswa_dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        <button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
    </div>
</div>

<script type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="SB-Mid-client-fGj9vEf56K_XSA2j"></script>
<script>
    document.getElementById('pay-button').onclick = function() {
        window.snap.pay('<?= $snapToken ?>', {
            onSuccess: function(result) {
                // Arahkan ke halaman dashboard siswa setelah pembayaran berhasil
                window.location.href = 'siswa_dashboard.php';
            },
            onPending: function(result) {
                console.log('Pending:', result);
                alert("Pembayaran Anda sedang dalam proses. Silakan cek status pembayaran Anda.");
                // Anda bisa tetap di halaman ini
            },
            onError: function(result) {
                console.log('Error:', result);
                alert("Terjadi kesalahan saat memproses pembayaran.");
                // Anda bisa tetap di halaman ini
            },
            onClose: function() {
                console.log('Pembayaran ditutup oleh pengguna');
                // Anda bisa tetap di halaman ini
            }
        });
    };
</script>

</body>
</html>
