<?php
require_once __DIR__ . '/koneksi.php';

$tanggal_mulai   = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

$labels           = [];
$data_total       = [];
$rekap            = [];
$total_pendapatan = 0;
$total_pelanggan  = 0;
$sudah_filter     = false;

if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {
    $sudah_filter = true;

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

    while ($row = mysqli_fetch_assoc($res)) {
        $labels[]          = $row['tgl'];
        $data_total[]      = (int) $row['total_harian'];
        $rekap[]           = $row;
        $total_pendapatan += (int) $row['total_harian'];
    }
    mysqli_stmt_close($stmt);

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
}

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
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Laporan Penjualan</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 24px;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            background: #fff;
            padding: 24px 28px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        h2 {
            margin-top: 0;
            color: #1e293b;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
        }

        .filter-box {
            background: #f8fafc;
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin-bottom: 18px;
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #475569;
        }

        .form-group input[type="date"] {
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            font-size: 14px;
        }

        .btn {
            padding: 9px 16px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background-color: #16a34a;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #15803d;
        }

        .btn-back {
            background-color: #1d4ed8;
            color: #fff;
            margin-bottom: 14px;
        }

        .btn-action {
            background-color: #ea580c;
            color: #fff;
        }

        .btn-action:hover {
            background-color: #c2410c;
        }

        .action-bar {
            display: flex;
            gap: 8px;
            margin-bottom: 18px;
        }

        .hint {
            font-size: 12px;
            color: #64748b;
            margin-top: 8px;
        }

        .chart-wrapper {
            position: relative;
            width: 100%;
            max-width: 750px;
            height: 320px;
            margin: 16px 0 24px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #cbd5e1;
            padding: 10px 12px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #e0f2fe;
            color: #0f172a;
            font-weight: 700;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .summary-cards {
            display: flex;
            gap: 16px;
            margin-top: 20px;
            max-width: 520px;
        }

        .card {
            flex: 1;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            overflow: hidden;
        }

        .card-header {
            background-color: #e0f2fe;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .card-body {
            padding: 14px 12px;
            font-size: 18px;
            font-weight: 700;
            color: #1d4ed8;
            text-align: right;
        }

        .alert-empty {
            padding: 14px;
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            border-radius: 6px;
        }

        .print-title {
            display: none;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }

            .no-print {
                display: none !important;
            }

            .print-title {
                display: block;
                font-size: 20px;
                font-weight: 700;
                margin-bottom: 16px;
            }

            .chart-wrapper {
                max-width: 100%;
                height: 280px;
            }

            th,
            .card-header {
                background-color: #e0f2fe !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if ($sudah_filter): ?>
            <div class="print-title">
                Rekap Laporan Penjualan <?= htmlspecialchars($tanggal_mulai); ?> sampai <?= htmlspecialchars($tanggal_selesai); ?>
            </div>
        <?php endif; ?>

        <h2 class="no-print">Rekap Laporan Penjualan</h2>

        <div class="filter-box no-print">
            <form method="GET" action="" class="filter-form">
                <div class="form-group">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?= htmlspecialchars($tanggal_mulai); ?>" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_selesai">Tanggal Selesai</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="<?= htmlspecialchars($tanggal_selesai); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </form>
            <div class="hint">Contoh rentang data dummy tersedia: <b>2026-11-01</b> s/d <b>2026-11-07</b></div>
        </div>

        <?php if ($sudah_filter): ?>
            <div class="no-print">
                <a href="index.php" class="btn btn-back">&laquo; Kembali</a>
                <div class="action-bar">
                    <button type="button" onclick="window.print()" class="btn btn-action">&#128424; Cetak</button>
                    <a href="export_excel.php?tanggal_mulai=<?= urlencode($tanggal_mulai); ?>&tanggal_selesai=<?= urlencode($tanggal_selesai); ?>" class="btn btn-action">&#128196; Excel</a>
                </div>
            </div>

            <?php if (!empty($rekap)): ?>
                <div class="chart-wrapper">
                    <canvas id="grafikPenjualan"></canvas>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
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

                <div class="summary-cards">
                    <div class="card">
                        <div class="card-header">Jumlah Pelanggan</div>
                        <div class="card-body"><?= $total_pelanggan; ?> Orang</div>
                    </div>
                    <div class="card">
                        <div class="card-header">Jumlah Pendapatan</div>
                        <div class="card-body">Rp. <?= number_format($total_pendapatan, 0, ',', '.'); ?></div>
                    </div>
                </div>

                <script>
                    const ctx = document.getElementById('grafikPenjualan').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: <?= json_encode($labels); ?>,
                            datasets: [{
                                label: 'Total Pendapatan (Rp)',
                                data: <?= json_encode($data_total); ?>,
                                backgroundColor: 'rgba(59, 130, 246, 0.65)',
                                borderColor: 'rgba(29, 78, 216, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return 'Rp ' + value.toLocaleString('id-ID');
                                        }
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Total: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                        }
                                    }
                                }
                            }
                        }
                    });
                </script>
            <?php else: ?>
                <div class="alert-empty">
                    Tidak ada data transaksi ditemukan pada rentang tanggal <b><?= htmlspecialchars($tanggal_mulai); ?></b> sampai <b><?= htmlspecialchars($tanggal_selesai); ?></b>.
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>

</html>