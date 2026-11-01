<?php
include 'koneksi.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM supplier WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $update_query = "UPDATE supplier SET nama='$nama', telp='$telp', alamat='$alamat' WHERE id='$id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: index.php");
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Data</title>
</head>

<body>
    <h2>Edit Data Master Supplier</h2>
    <form method="POST" action="">
        <table style="border: none;">
            <tr>
                <td>Nama</td>
                <td><input type="text" name="nama" value="<?= $data['nama']; ?>" required></td>
            </tr>
            <tr>
                <td>Telp</td>
                <td><input type="text" name="telp" value="<?= $data['telp']; ?>" required></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td><input type="text" name="alamat" value="<?= $data['alamat']; ?>" required></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit" name="update" style="background-color:#5cb85c; color:white;">Update</button>
                    <a href="index.php"><button type="button" style="background-color:#d9534f; color:white;">Batal</button></a>
                </td>
            </tr>
        </table>
    </form>
</body>

</html>