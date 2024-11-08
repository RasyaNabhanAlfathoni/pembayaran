<?php
session_start(); // Pastikan ini ada
include 'headersiswa.php';
include 'koneksi.php';

// Pastikan user sudah login sebagai siswa
if (isset($_SESSION['id_siswa'])) {
    $id_siswa = $_SESSION['id_siswa']; // Ambil ID siswa dari session

    // Ambil data siswa berdasarkan ID
    $query = "SELECT siswa.*, angkatan.*, jurusan.*, kelas.* 
              FROM siswa, angkatan, jurusan, kelas 
              WHERE siswa.id_angkatan = angkatan.id_angkatan 
              AND siswa.id_jurusan = jurusan.id_jurusan 
              AND siswa.id_kelas = kelas.id_kelas 
              AND siswa.id_siswa = '$id_siswa'";

    $exec = mysqli_query($conn, $query);

    // Cek apakah ada hasil query
    if (mysqli_num_rows($exec) > 0) {
        $siswa = mysqli_fetch_assoc($exec);
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

        <!-- Data Pembayaran -->
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
                            // Konfigurasi pagination
                            $limit = 12; // Jumlah data per halaman
                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Halaman saat ini
                            $offset = ($page - 1) * $limit; // Data awal yang diambil

                            // Query untuk menghitung total data
                            $queryTotal = "SELECT COUNT(*) AS total FROM pembayaran WHERE id_siswa = '$id_siswa'";
                            $resultTotal = mysqli_query($conn, $queryTotal);
                            $totalData = mysqli_fetch_assoc($resultTotal)['total'];

                            // Query data dengan batasan limit dan offset
                            $query = "SELECT * FROM pembayaran WHERE id_siswa = '$id_siswa' ORDER BY jatuhtempo ASC LIMIT $limit OFFSET $offset";
                            $exec = mysqli_query($conn, $query);
                            $no = $offset + 1;

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
                                        // Periksa status
                                        if ($res['ket'] == 'MENUNGGU') {
                                            echo "<span class='btn btn-warning btn-sm'>MENUNGGU</span>";
                                        } elseif ($res['nobayar'] == '') {
                                            echo "<a class='btn btn-primary btn-sm' href='simulasi_bayar.php?id={$res['idspp']}'>Bayar</a>";
                                        } else {
                                            echo "<a class='btn btn-success btn-sm ml-2' href='cetak_slip_pembayaran.php?nis={$siswa['nis']}&act=cetak&id={$res['idspp']}' target='_blank'>Cetak</a>";
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

                <!-- Navigasi Pagination -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <?php
                        $totalPages = ceil($totalData / $limit); // Hitung jumlah halaman
                        for ($i = 1; $i <= $totalPages; $i++) {
                            $active = ($i == $page) ? 'active' : '';
                            echo "<li class='page-item $active'><a class='page-link' href='?page=$i'>$i</a></li>";
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>

<?php
    } else {
        echo "<div class='alert alert-danger'>Data tidak ada</div>";
    }
} else {
    echo "<div class='alert alert-warning'>Silakan login untuk mengakses halaman ini.</div>";
}
?>
<?php include 'footer.php'; ?>
