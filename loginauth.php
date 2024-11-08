<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $role = $_POST['role']; // Role yang dipilih: admin atau siswa

    if ($role == "admin") {
        // Proses login untuk Admin
        $user = htmlentities(strip_tags($_POST['user']));
        $pass = htmlentities(strip_tags($_POST['pass']));

        $query = "SELECT * FROM admin WHERE user_admin = '$user'";
        $exec = mysqli_query($conn, $query);
        if (mysqli_num_rows($exec) !== 0) {
            $res = mysqli_fetch_assoc($exec);
            if ($res['pass_admin'] == $pass) {
                $_SESSION['role'] = 'admin';
                $_SESSION['admin'] = $res['id_admin'];
                $_SESSION['nama_admin'] = $res['nama_admin'];
                header('location: index.php');
            } else {
                echo "<script>alert('Password salah'); document.location = 'loginauth.php';</script>";
            }
        } else {
            echo "<script>alert('User Admin Tidak Ditemukan'); document.location = 'loginauth.php';</script>";
        }
    } elseif ($role == "siswa") {
        // Proses login untuk Siswa
        $nis = htmlentities(strip_tags($_POST['nis']));
        $nama_siswa = htmlentities(strip_tags($_POST['nama_siswa']));

        $query = "SELECT * FROM siswa WHERE nis = '$nis' AND nama_siswa = '$nama_siswa'";
        $exec = mysqli_query($conn, $query);
        if (mysqli_num_rows($exec) !== 0) {
            $res = mysqli_fetch_assoc($exec);
            $_SESSION['role'] = 'siswa';
            $_SESSION['id_siswa'] = $res['id_siswa'];
            $_SESSION['nis'] = $res['nis'];
            $_SESSION['nama_siswa'] = $res['nama_siswa'];
            header('location: siswa_dashboard.php');
        } else {
            echo "<script>alert('NIS atau Nama Siswa salah'); document.location = 'loginauth.php';</script>";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login</title>
    <link href="foto/logos.png" rel="icon" type="image/x-icon">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                <img width="100%" height="100%" src="img/kampak.jpeg">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Silahkan Login</h1>
                                    </div>
                                    <form class="user" method="post" action="">
                                        <div class="form-group">
                                            <select name="role" id="role" class="form-control" onchange="toggleFormFields()">
                                                <option value="" disabled selected>--Pilih Role--</option>
                                                <option value="admin">Admin</option>
                                                <option value="siswa">Siswa</option>
                                            </select>
                                        </div>

                                        <div id="adminFields">
                                            <div class="form-group">
                                                <input type="text" name="user" class="form-control form-control-user" placeholder="Enter Username (Admin)">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="pass" class="form-control form-control-user" placeholder="Enter Password (Admin)">
                                            </div>
                                        </div>

                                        <div id="siswaFields" style="display:none;">
                                            <div class="form-group">
                                                <input type="text" name="nis" class="form-control form-control-user" placeholder="Enter NIS (Siswa)">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="nama_siswa" class="form-control form-control-user" placeholder="Enter Nama Siswa">
                                            </div>
                                        </div>

                                        <button type="submit" name="login" class="btn btn-primary btn-user btn-block">Login</button>
                                    </form>

                                    <script>
                                        function toggleFormFields() {
                                            var role = document.getElementById('role').value;
                                            if (role == 'admin') {
                                                document.getElementById('adminFields').style.display = 'block';
                                                document.getElementById('siswaFields').style.display = 'none';
                                            } else {
                                                document.getElementById('adminFields').style.display = 'none';
                                                document.getElementById('siswaFields').style.display = 'block';
                                            }
                                        }
                                    </script>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script type="text/javascript">
        $("input").attr("autocomplete", "off");
    </script>
</body>

</html>