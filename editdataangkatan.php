<?php 
include 'function/function_rupiah.php';
include 'header.php'; 
include 'koneksi.php';

if(isset($_GET['id_angkatan'])){
    $id_angkatan = $_GET['id_angkatan'];
    $exec1 = mysqli_query($conn, "DELETE FROM angkatan WHERE id_angkatan='$id_angkatan'");
    if($exec1){
        echo "<script>alert('Data angkatan Berhasil dihapus')
        document.location = 'editdataangkatan.php';
        </script>";
    }else{
        echo "<script>alert('Data angkatan gagal dihapus')
        document.location = 'editdataangkatan.php';
        </script>";
    }
}

?>
<!-- button triger -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah Data</button>
<!-- button triger -->
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data angkatan</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama angkatan</th>
                                            <th>Biaya</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <?php
                                        $no=0;
                                        $query= "SELECT * FROM angkatan";
                                        $exec= mysqli_query($conn, $query);
                                        while($res = mysqli_fetch_assoc($exec)) :
                                    ?>
                                    <tbody>
                                        <tr>
                                            <td><?= $no+=1 ?></td>
                                            <td><?= $res['nama_angkatan'] ?></td>
                                            <td><?= format_rupiah($res['biaya']) ?></td>
                                            <td>
                                                <a href="editdataangkatan.php?id_angkatan=<?= $res['id_angkatan']?>" class="btn btn-danger" onclick="return confirm('Apakah Yakin Ingin Menghapus Data?')">Hapus</a>
                                                <a href="#" class="view_data btn  btn-warning" data-bs-toggle="modal" data-bs-target="#myModal" id="<?php echo $res['id_angkatan']; ?>">Edit</a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Data angkatan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                            <input type="text" name ="nama_angkatan" placeholder="Nama angkatan" class="form-control mb-2">
                            <input type="text" name ="biaya" placeholder="Biaya SPP" class="form-control mb-2">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="Submit" name="simpan" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                        </div>
                    </div>
                    </div>

                     
                

                    <!-- Modal -->
                    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModal">Edit Data angkatan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                        </div>
                        <div class="modal-body" id="dataangkatan">
                                    
                                </div>
                        </div>
                    </div>
                    </div>

                <?php
                    if(isset($_POST['simpan'])){
                        $nama_angkatan = htmlentities(strip_tags(strtoupper($_POST['nama_angkatan'])));
                        $biaya = htmlentities(strip_tags(strtoupper($_POST['biaya'])));
                        $query = "INSERT INTO angkatan (nama_angkatan, biaya) VALUES ('$nama_angkatan','$biaya')";
                        $exec = mysqli_query($conn, $query);
                        if ($exec) {
                            echo "<script>alert('Data angkatan berhasil disimpan')
                            document.location = 'editdataangkatan.php';
                            </script>";
                            
                        } else {
                            echo "<script>alert('Data angkatan gagal disimpan')
                            document.location = 'editdataangkatan.php';
                            </script>";
                        }
                        
                    }
                ?>
<?php include 'footer.php'; ?>

<script type="text/javascript">
    $('.view_data').on('click', function() {
    // Gunakan 'data-id' atau 'id' tergantung pada atribut yang kamu gunakan di HTML
    var id_angkatan = $(this).data('id') || $(this).attr('id'); 
    
    if (!id_angkatan) {
        console.error("ID Siswa is undefined or null!");
        return; // Berhenti jika id_siswa tidak ditemukan
    }
    
    $.ajax({
        url: 'view.php',
        type: 'POST',
        data: { id_angkatan: id_angkatan },
        success: function(response) {
            $('#dataangkatan').html(response);
            $('#myModal').modal('show');
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});


</script>

<?php
if(isset($_POST['edit'])){
    $id_angkatan = $_POST['id_angkatan'];
    $nama_angkatan = htmlentities(strip_tags(strtoupper($_POST['nama_angkatan'])));
    $biaya = htmlentities(strip_tags(strtoupper($_POST['biaya'])));
    $query = "UPDATE angkatan SET nama_angkatan = '$nama_angkatan', biaya = '$biaya' WHERE id_angkatan = '$id_angkatan'";
    $exec = mysqli_query($conn, $query);
    if ($exec) {
        echo "<script>alert('Data angkatan berhasil disimpan')
        document.location = 'editdataangkatan.php';
        </script>";
        
    } else {
        echo "<script>alert('Data angkatan gagal disimpan')
        document.location = 'editdataangkatan.php';
        </script>";
    }
}
?>