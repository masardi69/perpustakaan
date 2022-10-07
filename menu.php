<?php
include 'koneksi.php';
session_start();

function cekAkses($page, $login) {
  global $db;
  $sql =  "select find_in_set('$page',replace(akses,' ','')) as ada from login where user_name='$login'";
  $result = mysqli_query ($db,$sql);
  mysqli_close ($db);
  if (!mysqli_num_rows($result)) return 0;
  else {
    $check = mysqli_fetch_assoc($result);
    if (!$check['ada']) return 0;
  }
  return 1;
}


?>

<html>
<head>
<style>
  table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
    margin: 5px;
    padding: 5px;
  }
  ul {
	  list-style-type : none;
  }
  }
</style>
</head>
<body>
<header>Perpustakaan SMAK St. Vincentius 
<br><a href='index.php'>Index</a> | <a href='index.php?logout'>Logout</a><br>
<?php
include "koneksi.php";
if ( isset ($_SESSION['username']) )  {
  echo "
  <ul>
  <li><a href='menu.php?page=masterBuku'>Master Buku</a></li>
    <li><a href='menu.php?page=masterPeminjam'>Master Peminjam</a></li>
    <li><a href='menu.php?page=trPinjam'>Transaksi Peminjaman</a></li>
    <li><a href='menu.php?page=trKembali'>Transaksi Pengembalian</a></li>
    <li><a href='menu.php?page=report'>Report</a></li>
    <li><a href='index.php?logout'>Logout</a>
</ul>";
}
?>
<hr><br>
</header>

<?php
include "koneksi.php";

$_ref = 1;

if ( isset ($_SESSION['username'])
  && isset ($_GET['page'])
  && file_exists ($_GET['page'].'.php')
) {
  include $_GET['page'].'.php';

} else echo "<script>alert('System error!'); window.location.href = 'index.php';</script>";

?>

<footer>
<br><hr>
SMAK St. Vincentius
</footer>
</body>
</html>

