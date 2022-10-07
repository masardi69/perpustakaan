<?php
include 'koneksi.php';
if (isset($_GET['ubah'])) {
   $sql = "update data_peminjam set 
        nama=". ($_POST['nama'] ? ("'".$_POST['nama']."'") : 'NULL') ."
        where no_induk='".$_GET['ubah']."'";
  $sql = mysqli_query($db,$sql);
  mysqli_close($db);

  echo "<script>document.location.href = '".$_SERVER['HTTP_REFERER']."'; </script>";
  exit();
}

if (isset($_GET['hapus'])) {
   $sql = "delete from data_peminjam where no_induk = '".$_GET['hapus']."' ";
  $result = mysqli_query($db,$sql);
  mysqli_close($db);

  echo "<script>document.location.href = '".$_SERVER['HTTP_REFERER']."'; </script>";
  exit();
}

if (isset($_GET['tambah'])) {
   $sql = "insert into data_peminjam (no_induk,nama) values (
        ". ($_POST['no_induk'] ? ($_POST['no_induk']) : 'NULL') .",
        ". ($_POST['nama'] ? ("'".$_POST['nama']."'") : 'NULL') ."
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

<h1>Master Peminjam</h1>

<form method=post action='masterPeminjam.php?tambah'>
<label>No. Induk</label><br><input type=number name=no_induk><br>
<label>Nama</label><br><input name=nama><br>
<input type=submit value='TAMBAH DATA'>
</form>
<br>

<?php
include "koneksi.php";
 $sql = "select distinct(p.no_induk), p.nama, if (t.id_transaksi,1,0) as _rec from data_peminjam p left join transaksi t on p.no_induk=t.no_induk";
$result = mysqli_query($db,$sql);
mysqli_close($db);
if (!mysqli_num_rows($result)) echo 'tidak ada data';
else {
  echo "<table border='1'>"; 
  echo "
  <tr>
      <th>No. Induk</th>
  <th>Nama</th>
  <th colspan=2>Opsi</th>
    </tr>";
  
  while ($check = mysqli_fetch_assoc($result) ) {
    echo "
      <tr>
        <form method=post action='masterPeminjam.php?ubah=".$check['no_induk']."'>
          <td>".$check['no_induk']."</td>
          <td><input name='nama' type=text value='".$check['nama']."'</td>
          <td><input type=submit value='UPDATE'></td>
        </form>";
    if (!$check['_rec']) { echo "
        <form method=post action='masterPeminjam.php?hapus=".$check['no_induk']."'>
          <td><input type=submit value='HAPUS'></td>
        </form>";
    }
else{ echo "<td></td>";
    echo "
      </tr>";
  }

  }
  echo "</table><br>";
}

?>