<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "perpustakaan";

$db = mysqli_connect($server,$username,$password,$database);
if (!$db) {
    die("<script>alert('Gagal tersambung dengan database.')</script>");
}

?>
