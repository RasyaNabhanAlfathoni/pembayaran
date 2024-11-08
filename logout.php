<?php
session_start();
session_unset(); // Menghapus semua session
session_destroy();
header('location: loginauth.php');
exit();
?>