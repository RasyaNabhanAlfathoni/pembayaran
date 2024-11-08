<?php include 'header.php'; 
include 'koneksi.php';

if(isset($_GET['id_admin'])){
    $id_admin = $_GET['id_admin'];
    $exec1 = mysqli_query($conn, "DELETE FROM admin WHERE id_admin='$id_admin'");
    if($exec1){
        echo "<script>alert('Data admin Berhasil dihapus')
        document.location = 'editdataadmin.php';
        </script>";
    }else{
        echo "<script>alert('Data admin gagal dihapus')
        document.location = 'editdataadmin.php';
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
                            <h6 class="m-0 font-weight-bold text-primary">Data admin</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama admin</th>
                                            <th>User admin</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <?php
                                        $no=0;
                                        $query= "SELECT * FROM admin";
                                        $exec= mysqli_query($conn, $query);
                                        while($res = mysqli_fetch_assoc($exec)) :
                                    ?>
                                    <tbody>
                                        <tr>
                                            <td><?= $no+=1 ?></td>
                                            <td><?= $res['nama_admin'] ?></td>
                                            <td><?= $res['user_admin'] ?></td>
                                            <td>
                                                <a href="editdataadmin.php?id_admin=<?= $res['id_admin']?>" class="btn btn-danger" onclick="return confirm('Apakah Yakin Ingin Menghapus Data?')">Hapus</a>
                                                <a href="#" class="view_data btn  btn-warning" data-bs-toggle="modal" data-bs-target="#myModal" id="<?php echo $res['id_admin']; ?>">Edit</a>
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
                            <h5 class="modal-title" id="exampleModalLabel">Data admin</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                            <input type="text" name ="nama_admin" placeholder="Nama admin" class="form-control mb-2">
                            <input type="text" name ="user_admin" placeholder="User admin" class="form-control mb-2">
                            <input type="text" name ="pass_admin" placeholder="Password admin" class="form-control mb-2">
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
                            <h5 class="modal-title" id="myModal">Edit Data admin</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                        </div>
                        <div class="modal-body" id="dataadmin">
                                    
                                </div>
                        </div>
                    </div>
                    </div>

                <?php
                    if(isset($_POST['simpan'])){
                        $nama_admin = htmlentities(strip_tags(strtoupper($_POST['nama_admin'])));
                        $query = "INSERT INTO admin (nama_admin) VALUES ('$nama_admin')";
                        $exec = mysqli_query($conn, $query);
                        if ($exec) {
                            echo "<script>alert('Data admin berhasil disimpan')
                            document.location = 'editdataadmin.php';
                            </script>";
                            
                        } else {
                            echo "<script>alert('Data admin gagal disimpan')
                            document.location = 'editdataadmin.php';
                            </script>";
                        }
                        
                    }
                ?>
<?php include 'footer.php'; ?>

<script type="text/javascript">
    $('.view_data').on('click', function() {
    // Gunakan 'data-id' atau 'id' tergantung pada atribut yang kamu gunakan di HTML
    var id_admin = $(this).data('id') || $(this).attr('id'); 
    
    if (!id_admin) {
        console.error("ID Siswa is undefined or null!");
        return; // Berhenti jika id_siswa tidak ditemukan
    }
    
    $.ajax({
        url: 'view.php',
        type: 'POST',
        data: { id_admin: id_admin },
        success: function(response) {
            $('#dataadmin').html(response);
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
    $id_admin = $_POST['id_admin'];
    $nama_admin = htmlentities(strip_tags(strtoupper($_POST['nama_admin'])));
    $query = "UPDATE admin SET nama_admin = '$nama_admin' WHERE id_admin = '$id_admin'";
    $exec = mysqli_query($conn, $query);
    if ($exec) {
        echo "<script>alert('Data admin berhasil disimpan')
        document.location = 'editdataadmin.php';
        </script>";
        
    } else {
        echo "<script>alert('Data admin gagal disimpan')
        document.location = 'editdataadmin.php';
        </script>";
    }
}
?>