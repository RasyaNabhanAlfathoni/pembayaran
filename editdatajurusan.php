<?php include 'header.php'; 
include 'koneksi.php';

if(isset($_GET['id_jurusan'])){
    $id_jurusan = $_GET['id_jurusan'];
    $exec1 = mysqli_query($conn, "DELETE FROM jurusan WHERE id_jurusan='$id_jurusan'");
    if($exec1){
        echo "<script>alert('Data jurusan Berhasil dihapus')
        document.location = 'editdatajurusan.php';
        </script>";
    }else{
        echo "<script>alert('Data jurusan gagal dihapus')
        document.location = 'editdatajurusan.php';
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
                            <h6 class="m-0 font-weight-bold text-primary">Data jurusan</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama jurusan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <?php
                                        $no=0;
                                        $query= "SELECT * FROM jurusan";
                                        $exec= mysqli_query($conn, $query);
                                        while($res = mysqli_fetch_assoc($exec)) :
                                    ?>
                                    <tbody>
                                        <tr>
                                            <td><?= $no+=1 ?></td>
                                            <td><?= $res['nama_jurusan'] ?></td>
                                            <td>
                                                <a href="editdatajurusan.php?id_jurusan=<?= $res['id_jurusan']?>" class="btn btn-danger" onclick="return confirm('Apakah Yakin Ingin Menghapus Data?')">Hapus</a>
                                                <a href="#" class="view_data btn  btn-warning" data-bs-toggle="modal" data-bs-target="#myModal" id="<?php echo $res['id_jurusan']; ?>">Edit</a>
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
                            <h5 class="modal-title" id="exampleModalLabel">Data jurusan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                            <input type="text" name ="nama_jurusan" placeholder="Nama jurusan" class="form-control mb-2">
                           
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
                            <h5 class="modal-title" id="myModal">Edit Data jurusan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                        </div>
                        <div class="modal-body" id="datajurusan">
                                    
                                </div>
                        </div>
                    </div>
                    </div>

                <?php
                    if(isset($_POST['simpan'])){
                        $nama_jurusan = htmlentities(strip_tags(strtoupper($_POST['nama_jurusan'])));
                        $query = "INSERT INTO jurusan (nama_jurusan) VALUES ('$nama_jurusan')";
                        $exec = mysqli_query($conn, $query);
                        if ($exec) {
                            echo "<script>alert('Data jurusan berhasil disimpan')
                            document.location = 'editdatajurusan.php';
                            </script>";
                            
                        } else {
                            echo "<script>alert('Data jurusan gagal disimpan')
                            document.location = 'editdatajurusan.php';
                            </script>";
                        }
                        
                    }
                ?>
<?php include 'footer.php'; ?>

<script type="text/javascript">
    $('.view_data').on('click', function() {
    // Gunakan 'data-id' atau 'id' tergantung pada atribut yang kamu gunakan di HTML
    var id_jurusan = $(this).data('id') || $(this).attr('id'); 
    
    if (!id_jurusan) {
        console.error("ID Siswa is undefined or null!");
        return; // Berhenti jika id_siswa tidak ditemukan
    }
    
    $.ajax({
        url: 'view.php',
        type: 'POST',
        data: { id_jurusan: id_jurusan },
        success: function(response) {
            $('#datajurusan').html(response);
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
    $id_jurusan = $_POST['id_jurusan'];
    $nama_jurusan = htmlentities(strip_tags(strtoupper($_POST['nama_jurusan'])));
    $query = "UPDATE jurusan SET nama_jurusan = '$nama_jurusan' WHERE id_jurusan = '$id_jurusan'";
    $exec = mysqli_query($conn, $query);
    if ($exec) {
        echo "<script>alert('Data jurusan berhasil disimpan')
        document.location = 'editdatajurusan.php';
        </script>";
        
    } else {
        echo "<script>alert('Data jurusan gagal disimpan')
        document.location = 'editdatajurusan.php';
        </script>";
    }
}
?>