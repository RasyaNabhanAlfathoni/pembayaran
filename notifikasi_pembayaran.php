<?php

include 'header.php';
include 'koneksi.php';

// Pastikan user sudah login sebagai admin
// if (!isset($_SESSION['id_admin'])) {
//     header('Location: loginauth.php');
//     exit();
// }

// Ambil data notifikasi pembayaran
$query = "SELECT * FROM pembayaran WHERE ket = 'MENUNGGU'"; // Ambil pembayaran yang menunggu konfirmasi
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Pembayaran</title>
   
</head>
<body>

<div class="container">
    <h1>Notifikasi Pembayaran</h1>

        <table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Bulan</th>
            <th>Jumlah</th>
            <th>No Bayar</th>
            <th>Tanggal Bayar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $id_siswa = $row['id_siswa'];
            $querySiswa = "SELECT nama_siswa FROM siswa WHERE id_siswa = '$id_siswa'";
            $resultSiswa = mysqli_query($conn, $querySiswa);
            $siswa = mysqli_fetch_assoc($resultSiswa);
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $siswa['nama_siswa'] ?></td>
                <td><?= $row['bulan'] ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td><?= !empty($row['nobayar']) ? $row['nobayar'] : 'Belum ada nomor bayar' ?></td>
                <td><?= !empty($row['tglbayar']) ? $row['tglbayar'] : 'Belum ada tanggal bayar' ?></td>

                <td>
                    <?php
                    // Tampilkan tombol konfirmasi jika pembayaran belum memiliki nomor bayar (nobayar)
                    if (empty($row['nobayar'])) {
                        echo "<a class='btn btn-success btn-sm' href='proses_transaksi.php?id_siswa=$id_siswa&act=bayar&id=" . $row['idspp'] . "'>Konfirmasi</a>";
                    }
                    // Tambahkan tombol batal jika ket=Menunggu
                    if ($row['ket'] == 'MENUNGGU') {
                        echo "<a class='btn btn-danger btn-sm ml-2' href='proses_transaksi.php?id_siswa=$id_siswa&act=batal&id=" . $row['idspp'] . "' onclick='return confirm(\"Apakah Anda yakin ingin membatalkan pembayaran ini?\")'>Batal</a>";
                    }
                    ?>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

    </table>
</div>

</body>
</html>

<?php include 'footer.php'; ?>