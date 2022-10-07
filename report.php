<?php
include 'koneksi.php';
if (!cekAkses($_GET['page'], $_SESSION['username']) ) {
  echo "<script>document.location.href = '".$_SERVER['HTTP_REFERER']."'; </script>";
  exit();
}

if (!$_ref) exit;

?>

<h1>REPORT</h1>

<h3>Peminjam Paling Aktif</h3>

<?php
include "koneksi.php";
 $sql = "select d.no_induk, d.nama, count(*) as qty from data_peminjam d, transaksi t 
      where d.no_induk=t.no_induk
      group by d.no_induk
      order by qty desc
      limit 0,10
";

$result = mysqli_query ($db,$sql);
mysqli_close ($db);
if (!mysqli_num_rows($result)) echo "tidak ada data";
else {
  echo "<table border='1'>";
  echo "
  <tr><th>No Induk</th>
  <th>Nama</th>
  <th>Jumlah Transaksi</th>
  </tr>";
  while ($check = mysqli_fetch_assoc($result)) {
    echo "<tr>
<td>".$check['no_induk']."</td>
<td>".$check['nama']."</td>
<td>".$check['qty']."</td>";
  }
  echo "</table>";
}

?>

<h3>Buku Terlaris</h3>
<?php
include "koneksi.php";
 $sql =  "select b.id_buku, b.judul_buku, count(*) as qty from data_buku b, transaksi t 
       where b.id_buku=t.id_buku
       group by b.id_buku
       order by qty desc
       limit 0,10
";

$result = mysqli_query ($db,$sql);
mysqli_close ($db);
if (!mysqli_num_rows($result)) echo "tidak ada data";
else {
  echo "<table border='1'>";
  echo "<tr><th>ID Buku</th>
<th>Judul</th>
<th>Jumlah Peminjaman</th>
</tr>";
  while ($check = mysqli_fetch_assoc($result)) {
    echo "<tr><td>".$check['id_buku']."</td>
<td>".$check['judul_buku']."</td>
<td>".$check['qty']."</td></tr>";
  }
  echo "</table>";
}

?>
