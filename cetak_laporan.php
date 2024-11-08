<?php
session_start();
if (isset($_SESSION['admin'])) {
    include 'koneksi.php';
    $awal = $_GET['awal'];
    $akhir = $_GET['akhir'];
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Laporan Penjualan</title>
        <style>
            body {
                font-family: arial;
                margin-top: 10px;
            }

            .print {
                margin-top: 10px;
            }

            @media print {
                .print {
                    display: none;
                }
            }

            table {
                border-collapse: collapse;
            }
        </style>
    </head>

    <body onload="window.print()">
        <h3>
            <center>SMKN 01 CIBINONG<br />LAPORAN PEMBAYARAN SPP</center>
        </h3>
        <br />
        <hr />
        Tanggal <?= $awal . " Sampai" . $akhir; ?>
        <br />
        <table border="1" cellspacing="4" cellpadding="4" width="100%">
            <tr>
                <th>NO</th>
                <th>NIS</th>
                <th>NAMA SISWA</th>
                <th>KELAS</th>
                <th>NO. BAYAR</th>
                <th>PEMBAYARAN BULAN/TH</th>
                <th>JUMLAH</th>
                <th>KETERANGAN</th>
            </tr>
            <?php
            $spp = mysqli_query($conn, "SELECT siswa.*, pembayaran.* FROM siswa, pembayaran WHERE pembayaran.id_siswa = siswa.id_siswa AND tglbayar BETWEEN '$awal' AND '$akhir' ORDER BY nobayar");

            $i = 1;
            $total = 0;
            while ($dta = mysqli_fetch_assoc($spp)) :
            ?>
                <tr>
                    <td align="center"><?= $i; ?></td>
                    <td align="center"><?= $dta['id_siswa']; ?></td>
                    <td align="center"><?= $dta['nis']; ?></td>
                    <td><?= $dta['nama_siswa']; ?></td>
                    <td><?= $dta['nobayar']; ?></td>
                    <td><?= $dta['bulan']; ?></td>
                    <td align="right"><?= $dta['jumlah']; ?></td>
                    <td align="center"><?= $dta['ket']; ?></td>
                </tr>
                <?php
                $total += $dta['jumlah'];
                $i++;
                ?>
            <?php endwhile; ?>
            <tr>
                <td colspan="7" align="right">TOTAL</td>
                <td align="right"><b><?= $total ?></b></td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td></td>
                <td width="200px">
                    <br />
                    <p>CIBINONG, <?= date('d/m/y') ?> <br />
                        <br />
                        <?= $_SESSION['nama_admin'] ?>,
                    </p>
                    <br />
                    <br />
                    <br />
                    <p>________________________</p>
                </td>
            </tr>
        </table>
    </body>

    </html>


<?php } else {
    header("location : loginauth.php");
}

?>