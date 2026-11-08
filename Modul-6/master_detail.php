<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_modul_6");

$id_transaksi_aktif = isset($_GET['id_transaksi']) ? (int)$_GET['id_transaksi'] : 1;

if (isset($_GET['hapus_barang_id'])) {
    $id_barang = (int)$_GET['hapus_barang_id'];

    $cek_relasi = mysqli_query($koneksi, "SELECT * FROM transaksi_detail WHERE barang_id = $id_barang");

    if (mysqli_num_rows($cek_relasi) > 0) {
        echo "<script>alert('Gagal! Barang ini tidak bisa dihapus karena masih digunakan pada data transaksi.'); window.location='master_detail.php';</script>";
    } else {
        mysqli_query($koneksi, "DELETE FROM barang WHERE id = $id_barang");
        echo "<script>alert('Berhasil! Data barang telah dihapus.'); window.location='master_detail.php';</script>";
    }
}

if (isset($_POST['tambah_detail'])) {
    $barang_id = (int)$_POST['barang_id'];
    $qty = (int)$_POST['qty'];

    $cek_harga = mysqli_query($koneksi, "SELECT harga FROM barang WHERE id = $barang_id");
    $harga = mysqli_fetch_assoc($cek_harga)['harga'];

    $query_insert = "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) 
                     VALUES ($id_transaksi_aktif, $barang_id, $harga, $qty)";
    mysqli_query($koneksi, $query_insert);

    $query_update_total = "
        UPDATE transaksi SET total = (
            SELECT SUM(harga * qty) FROM transaksi_detail WHERE transaksi_id = $id_transaksi_aktif
        ) WHERE id = $id_transaksi_aktif
    ";
    mysqli_query($koneksi, $query_update_total);

    echo "<script>window.location='master_detail.php?id_transaksi=$id_transaksi_aktif';</script>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tugas Praktikum PAW</title>
</head>

<body>

    <h1>Aplikasi Pengelolaan Penjualan</h1>
    <hr>

    <h2>Daftar Barang</h2>
    <table border="1">
        <tr>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php
        $query_barang = mysqli_query($koneksi, "SELECT * FROM barang");
        while ($b = mysqli_fetch_assoc($query_barang)) {
            echo "<tr>
                    <td>{$b['kode_barang']}</td>
                    <td>{$b['nama_barang']}</td>
                    <td>Rp {$b['harga']}</td>
                    <td>{$b['stok']}</td>
                    <td>
                        <a href='?hapus_barang_id={$b['id']}' onclick='return confirm(\"Hapus barang {$b['nama_barang']}?\")'>Hapus</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>

    <br>
    <hr><br>

    <h2>Master-Detail Transaksi</h2>

    <?php
    $q_master = mysqli_query($koneksi, "SELECT t.*, p.nama FROM transaksi t JOIN pelanggan p ON t.pelanggan_id = p.id WHERE t.id = $id_transaksi_aktif");
    $data_master = mysqli_fetch_assoc($q_master);

    if ($data_master) {
    ?>
        <div>
            <p>ID Transaksi: <?= $data_master['id'] ?></p>
            <p>Pelanggan: <?= $data_master['nama'] ?></p>
            <p>Waktu Transaksi: <?= $data_master['waktu_transaksi'] ?></p>
            <p>Total Transaksi: Rp <?= $data_master['total'] ?></p>
        </div>

        <h3>Tambah Detail Barang</h3>
        <form method="POST">
            <label>Pilih Barang:</label>
            <select name="barang_id" required>
                <option value="">-- Pilih Barang --</option>
                <?php
                $q_dropdown = "SELECT * FROM barang WHERE id NOT IN (
                                SELECT barang_id FROM transaksi_detail WHERE transaksi_id = $id_transaksi_aktif
                             )";
                $res_dropdown = mysqli_query($koneksi, $q_dropdown);
                while ($opt = mysqli_fetch_assoc($res_dropdown)) {
                    echo "<option value='{$opt['id']}'>{$opt['nama_barang']} - Rp {$opt['harga']}</option>";
                }
                ?>
            </select>

            <label>Qty:</label>
            <input type="number" name="qty" min="1" required>

            <button type="submit" name="tambah_detail">Tambah Data</button>
        </form>

        <br>

        <table border="1">
            <tr>
                <th>Barang</th>
                <th>Harga Satuan</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
            <?php
            $q_detail = "SELECT d.*, b.nama_barang FROM transaksi_detail d JOIN barang b ON d.barang_id = b.id WHERE d.transaksi_id = $id_transaksi_aktif";
            $res_detail = mysqli_query($koneksi, $q_detail);

            if (mysqli_num_rows($res_detail) > 0) {
                while ($d = mysqli_fetch_assoc($res_detail)) {
                    $subtotal = $d['harga'] * $d['qty'];
                    echo "<tr>
                            <td>{$d['nama_barang']}</td>
                            <td>Rp {$d['harga']}</td>
                            <td>{$d['qty']}</td>
                            <td>Rp {$subtotal}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Belum ada barang di transaksi ini.</td></tr>";
            }
            ?>

            <br>
        </table>
    <?php } else {
        echo "<p>Data transaksi dengan ID $id_transaksi_aktif tidak ditemukan.</p>";
    } ?>
    <br>
    <br>
    <br>
    <br>
</body>

</html>