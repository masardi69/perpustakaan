<?php
include 'koneksi.php';
function cekKetersediaanBuku ($id_buku) {
   $db = mysqli_connect('localhost','root','','perpustakaan');
   //global $db;
   $sql = "select * from (
          select b.id_buku, if (id_transaksi,0,1) as ada from data_buku b
          left join transaksi t 
          on b.id_buku=t.id_buku and t.tanggal_kembali is null
        ) as _ where id_buku='$id_buku' and ada";
  $result = mysqli_query($db,$sql);
  mysqli_close($db);
  if (!mysqli_num_rows($result)) return 0;
  return 1;
}

function cekDataPeminjam ($no_induk) {
$db = mysqli_connect('localhost','root','','perpustakaan');
//global $db;
   $sql = "select no_induk from data_peminjam where no_induk='$no_induk'";
  $result = mysqli_query($db,$sql);
  mysqli_close($db);
  if (!mysqli_num_rows($result)) return 0;
  return 1;
}


if (isset($_GET['ubah'])) {
  if (!cekKetersediaanBuku ($_POST['id_buku']) or !cekDataPeminjam($_POST['no_induk']) ) {
    echo "<script>document.location.href = '".$_SERVER['HTTP_REFERER']."'; </script>";
    exit();
  }

   $sql = "update transaksi set 
        no_induk=". ($_POST['no_induk'] ? ("'".$_POST['no_induk']."'") : 'NULL') .",
        id_buku=". ($_POST['id_buku'] ? ($_POST['id_buku']) : 'NULL') ."
        where id_transaksi='".$_GET['ubah']."'";
  $result = mysqli_query($db,$sql);
  mysqli_close($db);

  echo "<script>document.location.href = '".$_SERVER['HTTP_REFERER']."'; </script>";
  exit();
}

if (isset($_GET['hapus'])) {
   $sql = "delete from transaksi where id_transaksi = '".$_GET['hapus']."' ";
  $result = mysqli_query($db,$sql);
  mysqli_close($db);

  echo "<script>document.location.href = '".$_SERVER['HTTP_REFERER']."'; </script>";
  exit();
}

if (isset($_GET['tambah'])) {
  if (!cekKetersediaanBuku ($_POST['id_buku']) or !cekDataPeminjam($_POST['no_induk']) ) {
    echo "<script>document.location.href = '".$_SERVER['HTTP_REFERER']."'; </script>";
    exit();
  }

   $sql = "insert into transaksi (no_induk,id_buku) values (
        ". ($_POST['no_induk'] ? ("'".$_POST['no_induk']."'") : 'NULL') .",
        ". ($_POST['id_buku'] ? ($_POST['id_buku']) : 'NULL') ."
       )";
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

<h1>Transaksi Peminjaman</h1>

<form method=post action='trPinjam.php?tambah'>
<label>No. Induk</label><br><input name=no_induk><br>
<label>ID Buku</label><br><input type=number name=id_buku><br>
<input type=submit value='TAMBAH DATA'>
</form>
<br>

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
<th>No. Induk</th>
<th>ID Buku</th>
<th>Tgl Pinjam</th>
<th colspan=2>Opsi</th>
    </tr>";
  
  while ($check = mysqli_fetch_assoc($result) ) {
    echo "
      <tr>
        <form method=post action='trPinjam.php?ubah=".$check['id_transaksi']."'>
          <td>".$check['id_transaksi']."</td>
          <td><input name='no_induk' type=text value='".$check['no_induk']."'</td>
          <td><input name='id_buku' type=number value='".$check['id_buku']."'</td>
          <td>".$check['tanggal_pinjam']."</td>
          <td><input type=submit value='UPDATE'></td>
        </form>
        <form method=post action='trPinjam.php?hapus=".$check['id_transaksi']."'>
          <td><input type=submit value='HAPUS'></td>
        </form>
      </tr>";
  }

  echo "</table><br>";
}

