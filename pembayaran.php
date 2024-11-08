<?php
include 'header.php';
include 'koneksi.php';

// Tentukan berapa banyak data yang ingin ditampilkan per halaman
$limit = 12;

// Tentukan halaman saat ini. Jika tidak ada halaman yang dipilih, default adalah 1.
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Hitung offset untuk query SQL
$offset = ($page - 1) * $limit;
?>
<div class="card shadow mb-4">
    <div class="card-body">
        <form action="" method="get">
            <table class="table">
                <tr>
                    <td>NIS</td>
                    <td>:</td>
                    <td>
                        <input type="text" name="nis" placeholder="Masukan NIS Siswa" class="form-control">
                    </td>
                    <td><button type="submit" class="btn btn-primary" name="cari">Search</button></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<?php
if (isset($_GET['nis']) && $_GET['nis'] != '') {
    $nis = $_GET['nis'];
    $query = "SELECT siswa.*, angkatan.*, jurusan.*, kelas.* FROM siswa, angkatan, jurusan, kelas 
              WHERE siswa.id_angkatan = angkatan.id_angkatan 
              AND siswa.id_jurusan = jurusan.id_jurusan 
              AND siswa.id_kelas = kelas.id_kelas 
              AND siswa.nis = '$nis'";
    $exec = mysqli_query($conn, $query);

    // Cek apakah ada hasil query
    if (mysqli_num_rows($exec) > 0) {
        $siswa = mysqli_fetch_assoc($exec);
        $id_siswa = $siswa['id_siswa'];
        $nis = $siswa['nis'];
?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Biodata Siswa</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tr>
                            <td>NIS</td>
                            <td><?= $siswa['nis'] ?></td>
                        </tr>
                        <tr>
                            <td>Nama Siswa</td>
                            <td><?= $siswa['nama_siswa'] ?></td>
                        </tr>
                        <tr>
                            <td>Kelas</td>
                            <td><?= $siswa['nama_kelas'] ?></td>
                        </tr>
                        <tr>
                            <td>Tahun Ajaran</td>
                            <td><?= $siswa['nama_angkatan'] ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- DataTables Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Pembayaran</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Bulan</th>
                                <th>Jatuh Tempo</th>
                                <th>No Bayar</th>
                                <th>Tanggal Bayar</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = $offset + 1;
                            $query = "SELECT * FROM pembayaran WHERE id_siswa = '$id_siswa' ORDER BY jatuhtempo ASC LIMIT $limit OFFSET $offset";
                            $exec = mysqli_query($conn, $query);
                            while ($res = mysqli_fetch_assoc($exec)) {
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $res['bulan'] ?></td>
                                    <td><?= $res['jatuhtempo'] ?></td>
                                    <td><?= $res['nobayar'] ?? '-' ?></td>
                                    <td><?= $res['tglbayar'] ?? '-' ?></td>
                                    <td><?= $res['jumlah'] ?></td>
                                    <td><?= $res['ket'] ?></td>
                                    <td>
                                        <?php
                                        if ($res['nobayar'] == '') {
                                            echo "<a class='btn btn-primary btn-sm' href='proses_transaksi.php?nis=$nis&act=bayar&id=$res[idspp]'>Konfirmasi</a>";
                                        } else {
                                            echo "<a class='btn btn-danger btn-sm' href='proses_transaksi.php?nis=$nis&act=batal&id=$res[idspp]'  onclick='return confirm(\"Apakah Anda yakin ingin membatalkan pembayaran ini?\")'>Batal</a>";
                                            echo "<a class='btn btn-success btn-sm ml-2' href='cetak_slip_pembayaran.php?nis=$nis&act=cetak&id=$res[idspp]' target='_blank'>Cetak</a>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <?php
        // Hitung total data
        $queryTotal = "SELECT COUNT(*) AS total FROM pembayaran WHERE id_siswa = '$id_siswa'";
        $resultTotal = mysqli_query($conn, $queryTotal);
        $totalData = mysqli_fetch_assoc($resultTotal)['total'];

        // Hitung jumlah halaman
        $totalPages = ceil($totalData / $limit);

        // Tampilkan navigasi halaman
        if ($totalPages > 1) {
            echo '<nav>';
            echo '<ul class="pagination justify-content-center">';
            for ($i = 1; $i <= $totalPages; $i++) {
                $active = ($i == $page) ? 'active' : '';
                echo "<li class='page-item $active'><a class='page-link' href='?nis=$nis&page=$i'>$i</a></li>";
            }
            echo '</ul>';
            echo '</nav>';
        }
        ?>

<?php
    } else {
        // Jika tidak ada data, tampilkan pesan
        echo "<div class='alert alert-danger'>Data tidak ada</div>";
    }
} else {
    echo "";
}
?>
<?php include 'footer.php'; ?>
