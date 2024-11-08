<?php
include 'koneksi.php';

if (isset($_POST['id_siswa'])) {
    $id_siswa = $_POST['id_siswa'];
    $query = "SELECT siswa.*, angkatan.*, jurusan.*, kelas.* 
              FROM siswa, angkatan, jurusan, kelas 
              WHERE siswa.id_angkatan = angkatan.id_angkatan 
              AND siswa.id_jurusan = jurusan.id_jurusan 
              AND siswa.id_kelas = kelas.id_kelas 
              AND siswa.id_siswa = '$id_siswa'";

    $exec = mysqli_query($conn, $query);

    // Tambahkan pengecekan error
    if (!$exec) {
        die('Query Error: ' . mysqli_error($conn)); // Akan tampilkan error query jika ada
    }

    $res = mysqli_fetch_assoc($exec);        
    if (!$res) {
        die('Data not found!'); // Akan tampilkan pesan jika data tidak ditemukan
    }
    // Jika sampai disini berarti data ditemukan
    echo "Data retrieved successfully.";
?>
                    <form action="editdatasiswa.php" method="POST">
                            <input type="hidden" name ="id_siswa" value="<?= $res['id_siswa'] ?>">
                            <input type="hidden" name ="nis" value="<?= $res['nis'] ?>">
                            <input type="text" name ="" class="form-control mb-2" disabled value="<?= $res['nis'] ?>">
                            <input type="text" name ="nama_siswa" class="form-control mb-2" value="<?= $res['nama_siswa'] ?>">
                            <select class="form-control mb-2" name="id_angkatan">
                                <option selected="">--Pilih Angkatan--</option>
                                <?php

                                    $exec = mysqli_query($conn, "SELECT * FROM angkatan order by id_angkatan");
                                    while ($angkatan = mysqli_fetch_assoc($exec)) :
                                      if($res['id_angkatan'] == $angkatan['id_angkatan']){
                                        $selected="selected";
                                      }else{
                                        $selected="";
                                      }
                                        echo "<option value=".$angkatan['id_angkatan'].">".$angkatan['nama_angkatan']."
                                        </option>";
                                    endwhile;
                                ?>
                            </select>
                            <select class="form-control mb-2" name="id_kelas">
                                <option selected="">--Pilih Kelas--</option>
                                <?php
                                    $exec = mysqli_query($conn, "SELECT * FROM kelas order by id_kelas");
                                    while ($angkatan = mysqli_fetch_assoc($exec)) :
                                        if($res['id_kelas'] == $angkatan['id_kelas']){
                                            $selected="selected";
                                        }else{
                                            $selected="";
                                        }
                                        echo "<option value=".$angkatan['id_kelas'].">".$angkatan['nama_kelas']."
                                        </option>";
                                    endwhile;
                                ?>
                            </select>
                            <select class="form-control mb-2" name="id_jurusan">
                                <option selected="">--Pilih Jurusan--</option>
                                <?php
                                    $exec = mysqli_query($conn, "SELECT * FROM jurusan order by id_jurusan");
                                    while ($angkatan = mysqli_fetch_assoc($exec)) :
                                        if($res['id_jurusan'] == $angkatan['id_jurusan']){
                                            $selected="selected";
                                        }else{
                                            $selected="";
                                        }
                                        echo "<option value=".$angkatan['id_jurusan'].">".$angkatan['nama_jurusan']."
                                        </option>";
                                    endwhile;
                                ?>
                            </select>
                            <textarea class="form-control mt-2" name="alamat" placeholder="Alamat Siswa"><?= $res['alamat'] ?></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="Submit" name="edit" class="btn btn-primary">Simpan</button>
                            

                        </form>

                        <?php
                        if(isset($_POST['id_kelas'])){
                            $id_kelas = $_POST['id_kelas'];
                            $exec = mysqli_query($conn, "SELECT * FROM kelas WHERE id_kelas= '$id_kelas'");
                            $res = mysqli_fetch_assoc($exec);
                            ?>
                         <form action="editdatakelas.php" method="POST">
                         <input type="hidden" name ="id_kelas" value="<?= $res['id_kelas'] ?>">
                         <input type="text" name="nama_kelas" class="form-control" value="<?= $res['nama_kelas'] ?>">
                         <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="Submit" name="edit" class="btn btn-primary">Simpan</button>
                        </form>

                        <?php } 
                        if(isset($_POST['id_jurusan'])){
                            $id_jurusan = $_POST['id_jurusan'];
                            $exec = mysqli_query($conn, "SELECT * FROM jurusan WHERE id_jurusan= '$id_jurusan'");
                            $res = mysqli_fetch_assoc($exec);
                        ?>
                         <form action="editdatajurusan.php" method="POST">
                         <input type="hidden" name ="id_jurusan" value="<?= $res['id_jurusan'] ?>">
                         <input type="text" name="nama_jurusan" class="form-control" value="<?= $res['nama_jurusan'] ?>">
                         <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="Submit" name="edit" class="btn btn-primary">Simpan</button>
                        </form>

                        <?php } 
                        if(isset($_POST['id_angkatan'])){
                            $id_angkatan = $_POST['id_angkatan'];
                            $exec = mysqli_query($conn, "SELECT * FROM angkatan WHERE id_angkatan= '$id_angkatan'");
                            $res = mysqli_fetch_assoc($exec);
                        ?>
                         <form action="editdataangkatan.php" method="POST">
                         <input type="hidden" name ="id_angkatan" value="<?= $res['id_angkatan'] ?>">
                         <input type="text" name="nama_angkatan" class="form-control" value="<?= $res['nama_angkatan'] ?>">
                         <input type="text" name="biaya" class="form-control mt-2" value="<?= $res['biaya'] ?>">
                         <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="Submit" name="edit" class="btn btn-primary">Simpan</button>
                        </form>
                        <?php } ?>

                    <?php    
                    
} else {
    echo "ID Siswa not set!";
}
?>            
            