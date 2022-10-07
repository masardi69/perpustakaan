<?php
session_start();
include "koneksi.php";
if(isset($_GET['login'])) {
$sql = "select * from login where user_name='".addslashes($_POST['username'])."' and password='".addslashes(md5($_POST['password']))."' ";
$result = mysqli_query($db,$sql);
if($check=mysqli_fetch_assoc($result)) {
$_SESSION['username'] = $check['user_name'];
$_SESSION['akses'] = $check['akses'];
}

  mysqli_close($db);
}

if (isset ($_GET['logout'])) {
  session_destroy();
  session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Perpustakaan SMAK St Vincentius</title>
</head>
<body>
<header>SMAK St Vincentius</header><br/><br/>

	<center><h2>Perpustakaan Sekolah</h2></center>
	<br/>

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
 else {
  echo "login"."<a href='loginAdmin.php'>admin</a><br>"; 
}

echo '<br>koleksi buku <br>';
echo "<form action='index.php' method='POST'>
cari:
<input type='text' name='cari'>
</form>";
 
  $sql = "select b.*, if (t.id_transaksi,'tidak ada','ada') as ketersediaan from data_buku b left join transaksi t on b.id_buku=t.id_buku and t.tanggal_kembali is null";
if(isset($_POST['cari']) ) {
$sql = $sql . " where b.judul_buku like '%".$_POST['cari']."%' "; }
$result = mysqli_query ($db,$sql);
mysqli_close ($db);
if (!mysqli_num_rows($result)) echo "tidak ada data"; 
else {
  echo "<table border='1'>";
  echo "<tr>
  <th>ID Buku</th>
  <th>Judul</th>
  <th>Kategori</th>
  <th>Ketersediaan</th>
  <th>Keterangan</th>
  </tr>";
  while ($check = mysqli_fetch_assoc($result)) {
    echo "<tr>
	<td>".$check['id_buku']."</td>
<td>".$check['judul_buku']."</td>
<td>".$check['kategori']."</td>
<td>".$check['ketersediaan']."</td>
<td>".$check['keterangan']."</td>
</tr>";
  }
  echo "</table>";
}
?>
</body>
</html>