<?php
session_start(); // Pastikan session sudah dimulai
if (!isset($_SESSION['id_siswa'])) {
    header('Location: loginauth.php');
    exit();
}

include 'koneksi.php';
require_once 'midtrans-php-master/Midtrans.php'; // Pastikan path ini sesuai dengan struktur direktori Anda

// Set your Merchant Server Key
\Midtrans\Config::$serverKey = 'SB-Mid-server-ZERs9SBm4s3qrPbz1yObHeNE';
\Midtrans\Config::$isProduction = false; // Set to true for production
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Mendapatkan payload dari Midtrans
$payload = file_get_contents('php://input');

// Simpan payload ke log error PHP untuk debugging
error_log("Payload received: " . $payload);

// Decode payload JSON
$data = json_decode($payload, true);

// Pastikan status transaksi berhasil
if (isset($data['transaction_status'])) {
    $order_id = $data['order_id'];
    $transaction_id = $data['transaction_id'];
    $transaction_status = strtolower($data['transaction_status']); // Ambil status transaksi dan konversi ke huruf kecil
    $tglbayar = date('Y-m-d H:i:s');

    // Cari idspp berdasarkan order_id
    $query = "SELECT idspp FROM pembayaran WHERE order_id = '$order_id'";
    $result = mysqli_query($conn, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $idspp = $row['idspp'];

        // Update data pembayaran berdasarkan status transaksi
        switch ($transaction_status) {
            case 'settlement':
                // Pembayaran berhasil
                $updateQuery = "UPDATE pembayaran SET nobayar = '$transaction_id', tglbayar = '$tglbayar', ket = 'MENUNGGU' WHERE idspp = '$idspp'";
                break;
        
            case 'pending':
                // Pembayaran masih dalam proses
                $updateQuery = "UPDATE pembayaran SET ket = 'MENUNGGU' WHERE idspp = '$idspp'";
                break;
        
            case 'cancel':
            case 'expired':
                // Pembayaran dibatalkan atau kedaluwarsa
                $updateQuery = "UPDATE pembayaran SET ket = 'GAGAL' WHERE idspp = '$idspp'";
                break;
        
            default:
                error_log("Status transaksi tidak dikenali: $transaction_status");
                exit();
        }
        
        // Eksekusi query update
        if (mysqli_query($conn, $updateQuery)) {
            error_log("Pembayaran berhasil diupdate untuk Order ID: $order_id");
        } else {
            error_log('Error saat memperbarui database: ' . mysqli_error($conn));
        }
    } else {
        error_log('Error: Order ID tidak ditemukan di database: ' . $order_id);
    }
} else {
    error_log('Error: Status kode tidak ditemukan.');
}

// Kirim respons ke Midtrans
http_response_code(200);
?>
