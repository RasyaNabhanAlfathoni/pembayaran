<?php
session_start();
if (isset($_SESSION['admin'])) {
    include 'koneksi.php';

?>

    <!DOCTYPE html>
    <html>

    <head>
        <title>Slip Pembayaran SPP</title>
        <style>
            body {
                font-family: arial;
            }

            table {
                border-collapse: collapse;
            }
        </style>
    </head>

    <body onload="window.print();">
        <h3>SMK NEGERI 01 CIBINONG<br>LAPORAN PEMBAYARAN SPP</h3>
        <hr><br>

        <?php
        $nis = $_GET['nis'];
        $siswa = mysqli_query($conn, "SELECT siswa.*, angkatan.*, jurusan.*, kelas.* FROM siswa, angkatan, jurusan, kelas 
WHERE siswa.id_angkatan = angkatan.id_angkatan 
AND siswa.id_jurusan = jurusan.id_jurusan 
AND siswa.id_kelas = kelas.id_kelas 
AND siswa.nis = '$nis'");

        $sw = mysqli_fetch_assoc($siswa);
        $idspp = $_GET['id'];
        ?>
        <table>
            <tr>
                <td>Nama Siswa</td>
                <td>:</td>
                <td><?= $sw['nama_siswa'] ?></td>
            </tr>
            <tr>
                <td>Nis</td>
                <td>:</td>
                <td><?= $sw['nis'] ?></td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td><?= $sw['nama_kelas'] ?></td>
            </tr>
            <tr>
                <td>Angkatan</td>
                <td>:</td>
                <td><?= $sw['nama_angkatan'] ?></td>
            </tr>
            <tr>
                <td>Jurusan</td>
                <td>:</td>
                <td><?= $sw['nama_jurusan'] ?></td>
            </tr>
        </table>
        <hr>
        <table border="1" cellspacing="0" cellpadding="4" width="100%">
            <tr>
                <th>NO.</th>
                <th>NO. BAYAR</th>
                <th>PEMBAYARAN BULAN</th>
                <th>KETERANGAN</th>
                <th>JUMLAH</th>
            </tr>
            <?php
            $spp = mysqli_query($conn, "SELECT siswa.*, pembayaran.* FROM siswa,pembayaran WHERE pembayaran.id_siswa = siswa.id_siswa AND pembayaran.idspp = '$idspp' ORDER by nobayar ASC");
            $i = 1;
            $total = 0;
            while ($dta = mysqli_fetch_assoc($spp)) :
            ?>
                <tr>
                    <td align="center"><?php echo $i++; ?></td>
                    <td align=""><?php echo $dta['nobayar']; ?></td>
                    <td align=""><?php echo $dta['bulan']; ?></td>
                    <td align="center"><?php echo $dta['ket']; ?></td>
                    <td align="right"><?php echo $dta['jumlah']; ?></td>
                </tr>
                <?php $i++; ?>
                <?php $total += $dta['jumlah']; ?>
            <?php endwhile; ?>
            <tr>
                <td colspan="4" align="right">Total</td>
                <td align="right"><b><?= $total ?></b></td>
            </tr>
        </table>
        <table width"100%">
            <td></td>
            <td width="200px">
                <BR />
                <p>CIBINONG <?= date('d/m/y') ?> <br />
                    <?= $_SESSION['nama_admin'] ?>,
                    <br />
                    <br />
                    <br />
                <p>______________________________</p>
            </td>
            </tr>
        </table>

    </html>
<?php
} else {
    header('location : loginauth.php');
}
?>