<?php
require_once __DIR__ . '/koneksi.php';

if (!isset($koneksi) || !$koneksi) {
    die('Koneksi database tidak tersedia.');
}

$tanggal_mulai   = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

if (empty($tanggal_mulai) || empty($tanggal_selesai)) {
    die("Parameter rentang tanggal tidak lengkap.");
}

$nama_file = "Rekap_Laporan_Penjualan_" . $tanggal_mulai . "_sd_" . $tanggal_selesai . ".xls";
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$nama_file\"");
header("Pragma: no-cache");
header("Expires: 0");

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian
     FROM transaksi
     WHERE waktu_transaksi BETWEEN ? AND ?
     GROUP BY DATE(waktu_transaksi)
     ORDER BY tgl ASC"
);
mysqli_stmt_bind_param($stmt, "ss", $tanggal_mulai, $tanggal_selesai);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

$rekap = [];
$total_pendapatan = 0;
while ($row = mysqli_fetch_assoc($res)) {
    $rekap[] = $row;
    $total_pendapatan += (int) $row['total_harian'];
}
mysqli_stmt_close($stmt);

// Query jumlah pelanggan unik
$stmt_p = mysqli_prepare(
    $koneksi,
    "SELECT COUNT(DISTINCT pelanggan_id) AS jml_pelanggan
     FROM transaksi
     WHERE waktu_transaksi BETWEEN ? AND ?"
);
mysqli_stmt_bind_param($stmt_p, "ss", $tanggal_mulai, $tanggal_selesai);
mysqli_stmt_execute($stmt_p);
$res_p = mysqli_stmt_get_result($stmt_p);
$row_p = mysqli_fetch_assoc($res_p);
$total_pelanggan = (int) ($row_p['jml_pelanggan'] ?? 0);
mysqli_stmt_close($stmt_p);

function formatTanggalIndo($tgl)
{
    $bulan = [
        '01' => 'Jan',
        '02' => 'Feb',
        '03' => 'Mar',
        '04' => 'Apr',
        '05' => 'Mei',
        '06' => 'Jun',
        '07' => 'Jul',
        '08' => 'Agu',
        '09' => 'Sep',
        '10' => 'Okt',
        '11' => 'Nov',
        '12' => 'Des'
    ];
    $parts = explode('-', $tgl);
    return $parts[2] . ' ' . ($bulan[$parts[1]] ?? $parts[1]) . ' ' . $parts[0];
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body>
    <h3>Rekap Laporan Penjualan <?= htmlspecialchars($tanggal_mulai); ?> sampai <?= htmlspecialchars($tanggal_selesai); ?></h3>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr style="background-color: #d9edf7; font-weight: bold;">
                <th>No</th>
                <th>Total Pendapatan</th>
                <th>Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($rekap as $row): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td>Rp. <?= number_format($row['total_harian'], 0, ',', '.'); ?></td>
                    <td><?= formatTanggalIndo($row['tgl']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>

    <table border="1" cellpadding="6" cellspacing="0">
        <tr style="background-color: #d9edf7; font-weight: bold;">
            <th>Jumlah Pelanggan</th>
            <th>Jumlah Pendapatan</th>
        </tr>
        <tr>
            <td><?= $total_pelanggan; ?> Orang</td>
            <td>Rp. <?= number_format($total_pendapatan, 0, ',', '.'); ?></td>
        </tr>
    </table>
</body>

</html>