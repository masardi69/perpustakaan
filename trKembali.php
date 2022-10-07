<?php
include 'koneksi.php';
if (isset($_GET['kembali'])) {
     $sql = "update transaksi set 
        tanggal_kembali=CURRENT_TIMESTAMP
        where id_transaksi='".$_GET['kembali']."'";
  $result = mysqli_query($db,$sql);
  mysqli_close($db);

  echo "<script>document.location.href = '".$_SERVER['HTTP_REFERER']."'; </script>";
  exit();
}

if (isset($_GET['batal'])) {
     $sql = "update transaksi set tanggal_kembali=null where id_transaksi=".$_GET['batal'];
  $result = mysqli_query($db,$sql);
  mysqli_close($db);

  echo "<script>document.location.href = '".$_SERVER['HTTP_REFERER']."'; </script>";
  exit();
}


if (!cekAkses($_GET['page'], $_SESSION['username']) ) {
  echo "<script>document.location.href = '".$_SERVER['HTTP_REFERER']."'; </script>";
  exit();
}

if (!$_ref) exit;

?>

<h1>Transaksi Pengembalian</h1>

<h3>Daftar Belum Kembali</h3>

<?php
include "koneksi.php";
 $sql = "select * from transaksi where tanggal_kembali is null"; 
$result = mysqli_query($db,$sql);
mysqli_close($db);
if (!mysqli_num_rows($result)) echo 'tidak ada data';
else {
  echo "
    <table border='1'><tr>
      <th>ID Transaksi</th>
  <th>ID Buku</th>
  <th>No. Induk</th>
  <th>Tanggal Pinjam</th>
  <th>Opsi</th>
    </tr>
  ";
  
  while ($check = mysqli_fetch_assoc($result) ) {
    echo "
      <tr>
        <form method=post action='trKembali.php?kembali=".$check['id_transaksi']."'>
          <td>".$check['id_transaksi']."</td>
          <td>".$check['id_buku']."</td>
          <td>".$check['no_induk']."</td>
          <td>".$check['tanggal_pinjam']."</td>
          <td><input type=submit value='DIKEMBALIKAN'></td>
        </form>
      </tr>";
  }

  echo "</table><br>";
}
?>

<h3>Daftar Pengembalian Terakhir</h3>


<?php
include "koneksi.php";
 $sql = "select * from transaksi t1 where tanggal_kembali = (select max(tanggal_kembali) from transaksi t2 where t1.id_buku = t2.id_buku) 
        and id_buku not in (select id_buku from transaksi where tanggal_kembali is null) order by tanggal_kembali"; 
$result = mysqli_query($db,$sql);
mysqli_close($db);
if (!mysqli_num_rows($result)) echo 'tidak ada data';
else {
  echo "
    <table border='1'><tr>
      <th>ID Transaksi</th>
<th>ID Buku</th>
<th>No. Induk</th>
<th>Tanggal Pinjam</th>
<th>Opsi</th>
    </tr>
  ";
  
  while ($check = mysqli_fetch_assoc($result) ) {
    echo "
      <tr>
        <form method=post action='trKembali.php?batal=".$check['id_transaksi']."'>
          <td>".$check['id_transaksi']."</td>
          <td>".$check['id_buku']."</td>
          <td>".$check['no_induk']."</td>
          <td>".$check['tanggal_pinjam']."</td>
          <td><input type=submit value='BATAL'></td>
        </form>
      </tr>
    ";
  }

  echo "</table><br>";
}
?>


