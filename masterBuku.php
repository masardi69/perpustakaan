<?php
include 'koneksi.php';

if (isset($_GET['ubah'])) {
   $sql = "update data_buku set 
        judul_buku=". ($_POST['judul_buku'] ? ("'".$_POST['judul_buku']."'") : 'NULL') .",
        kategori=". ($_POST['kategori'] ? ("'".$_POST['kategori']."'") : 'NULL') .",
        keterangan=". ($_POST['keterangan'] ? ("'".$_POST['keterangan']."'")  : 'NULL') ."
        where id_buku='".$_GET['ubah']."'";
  $result = mysqli_query($db,$sql);
  mysqli_close($db);

  echo "<script>document.location.href = '".$_SERVER['HTTP_REFERER']."'; </script>";
  exit();
}

if (isset($_GET['hapus'])) {
   $sql = "delete from data_buku where id_buku = '".$_GET['hapus']."' ";
  $result = mysqli_query($db,$sql);
  mysqli_close($db);

  echo "<script>document.location.href = '".$_SERVER['HTTP_REFERER']."'; </script>";
  exit();
}

if (isset($_GET['tambah'])) {
   $sql = "insert into data_buku (judul_buku,kategori,keterangan) values (
        ". ($_POST['judul_buku'] ? ("'".$_POST['judul_buku']."'") : 'NULL') .",
        ". ($_POST['kategori'] ? ("'".$_POST['kategori']."'") : 'NULL') .",
        ". ($_POST['keterangan'] ? ("'".$_POST['keterangan']."'")  : 'NULL') ."
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

<h1>Master Buku</h1>

<form method=post action='masterBuku.php?tambah'>
<label>Judul Buku</label><br><input name=judul_buku><br>
<label>Kategori</label><br><input name=kategori><br>
<label>Keterangan</label><br><input name=keterangan><br>
<input type=submit value='TAMBAH DATA'>
</form>
<br>

<?php
include "koneksi.php";
 $sql = "select distinct(b.id_buku), b.judul_buku, b.kategori, b.keterangan, b.tgl_input, if(t.id_buku,1,0) as _rec from data_buku b left join transaksi t on t.id_buku=b.id_buku order by tgl_input desc";
$result = mysqli_query($db,$sql);
mysqli_close($db);
if (!mysqli_num_rows($result)) echo 'tidak ada data';
else {
  echo "<table border='1'>";
  echo "<tr>
      <th>ID Buku</th>
<th>Judul Buku</th>
<th>Kategori</th>
<th>Keterangan</th>
<th>Tgl Input</th>
<th colspan=2>Opsi</th>
    </tr>";
  
  while ($check = mysqli_fetch_assoc($result) ) {
    echo "
      <tr>
        <form method=post action='masterBuku.php?ubah=".$check['id_buku']."'>
          <td>".$check['id_buku']."</td>
          <td><input name='judul_buku' type=text value='".$check['judul_buku']."'</td>
          <td><input name='kategori' type=text value='".$check['kategori']."'</td>
          <td><input name='keterangan' type=text value='".$check['keterangan']."'</td>
          <td>".$check['tgl_input']."</td>
          <td><input type=submit value='UPDATE'></td>
        </form>";
    if (!$check['_rec']) { 
	echo "
        <form method=post action='masterBuku.php?hapus=".$check['id_buku']."'>
          <td><input type=submit value='HAPUS'></td>
        </form>";
    } else echo "<td></td>";
    echo "
      </tr>";
  }

  echo "</table><br>";
}
