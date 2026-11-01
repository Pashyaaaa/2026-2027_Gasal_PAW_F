<?php
include 'koneksi.php';

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM supplier WHERE id = '$id'");
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Master Supplier</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-tambah {
            background-color: #5cb85c;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-edit {
            background-color: #f0ad4e;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-hapus {
            background-color: #d9534f;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <h2>Data Master Supplier</h2>
    <a href="tambah.php" class="btn-tambah">Tambah Data</a>

    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Telp</th>
            <th>Alamat</th>
            <th>Tindakan</th>
        </tr>
        <?php
        $query = mysqli_query($conn, "SELECT * FROM supplier ORDER BY id ASC");
        $no = 1;
        while ($row = mysqli_fetch_assoc($query)) {
        ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['telp']; ?></td>
                <td><?= $row['alamat']; ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn-edit">Edit</a>
                    <a href="index.php?hapus=<?= $row['id']; ?>" class="btn-hapus" onclick="return confirm('Anda yakin akan menghapus supplier ini?');">Hapus</a>
                </td>
            </tr>
        <?php } ?>
    </table>

</body>

</html>