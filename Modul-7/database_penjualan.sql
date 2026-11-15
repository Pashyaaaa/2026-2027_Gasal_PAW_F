CREATE DATABASE IF NOT EXISTS penjualan
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE penjualan;

DROP TABLE IF EXISTS pembayaran;
DROP TABLE IF EXISTS transaksi_detail;
DROP TABLE IF EXISTS transaksi;
DROP TABLE IF EXISTS barang;
DROP TABLE IF EXISTS pelanggan;
DROP TABLE IF EXISTS supplier;
DROP TABLE IF EXISTS user;


CREATE TABLE supplier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    telp VARCHAR(12) NOT NULL,
    alamat TEXT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(10) NOT NULL UNIQUE,
    nama_barang VARCHAR(100) NOT NULL,
    harga INT NOT NULL,
    stok INT NOT NULL,
    supplier_id INT NOT NULL,
    CONSTRAINT fk_barang_supplier FOREIGN KEY (supplier_id)
        REFERENCES supplier(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE pelanggan (
    id VARCHAR(20) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    telp VARCHAR(12) NOT NULL,
    alamat TEXT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE user (
    id_user TINYINT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL UNIQUE,
    password VARCHAR(35) NOT NULL,
    nama VARCHAR(50) NOT NULL,
    alamat VARCHAR(150) NOT NULL,
    hp VARCHAR(20) NOT NULL,
    level TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    waktu_transaksi DATE NOT NULL,
    keterangan TEXT,
    total INT NOT NULL,
    pelanggan_id VARCHAR(20) NOT NULL,
    CONSTRAINT fk_transaksi_pelanggan FOREIGN KEY (pelanggan_id)
        REFERENCES pelanggan(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE transaksi_detail (
    transaksi_id INT NOT NULL,
    barang_id INT NOT NULL,
    harga INT NOT NULL,
    qty INT NOT NULL,
    PRIMARY KEY (transaksi_id, barang_id),
    CONSTRAINT fk_detail_transaksi FOREIGN KEY (transaksi_id)
        REFERENCES transaksi(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detail_barang FOREIGN KEY (barang_id)
        REFERENCES barang(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE pembayaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    waktu_bayar DATETIME NOT NULL,
    total INT NOT NULL,
    metode ENUM('TUNAI', 'TRANSFER', 'EDC') NOT NULL,
    transaksi_id INT NOT NULL,
    CONSTRAINT fk_pembayaran_transaksi FOREIGN KEY (transaksi_id)
        REFERENCES transaksi(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO supplier (id, nama, telp, alamat) VALUES
(1, 'PT Sumber Makmur Jaya', '081234567801', 'Jl. Raya Telang No. 12, Bangkalan'),
(2, 'CV Nusantara Distribusi', '081234567802', 'Jl. Pemuda Kaffa No. 45, Bangkalan'),
(3, 'UD Sejahtera Abadi', '081234567803', 'Jl. Trunojoyo No. 88, Surabaya');

INSERT INTO barang (id, kode_barang, nama_barang, harga, stok, supplier_id) VALUES
(1, 'BRG001', 'Kopi Arabika Sachet 100g', 13000, 50, 1),
(2, 'BRG002', 'Teh Hijau Melati Kotak', 15000, 40, 1),
(3, 'BRG003', 'Gula Pasir Premium 1kg', 20000, 60, 2),
(4, 'BRG004', 'Minyak Goreng Pouch 1L', 25000, 35, 2),
(5, 'BRG005', 'Roti Tawar Gandum', 10000, 25, 3),
(6, 'BRG006', 'Susu UHT Full Cream 1L', 18000, 45, 3);

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('PLG001', 'Ahmad Fauzi', 'L', '085711110001', 'Perum Telang Indah Blok A1'),
('PLG002', 'Siti Aminah', 'P', '085711110002', 'Jl. KH. Moh. Kholil No. 14'),
('PLG003', 'Budi Santoso', 'L', '085711110003', 'Jl. Panglima Sudirman No. 22'),
('PLG004', 'Dewi Lestari', 'P', '085711110004', 'Jl. Jokotole No. 9'),
('PLG005', 'Rizky Pratama', 'L', '085711110005', 'Jl. Soekarno Hatta No. 31'),
('PLG006', 'Nadia Putri', 'P', '085711110006', 'Perum Griya Utama Blok C4'),
('PLG007', 'Fajar Hidayat', 'L', '085711110007', 'Jl. Halim Perdanakusuma No. 7'),
('PLG008', 'Rina Kartika', 'P', '085711110008', 'Jl. Teuku Umar No. 19'),
('PLG009', 'Dimas Saputra', 'L', '085711110009', 'Jl. Hos Cokroaminoto No. 5');

INSERT INTO user (id_user, username, password, nama, alamat, hp, level) VALUES
(1, 'admin', MD5('admin123'), 'Administrator', 'Bangkalan', '081200001111', 1),
(2, 'kasir', MD5('kasir123'), 'Kasir Utama', 'Bangkalan', '081200002222', 2);

INSERT INTO transaksi (id, waktu_transaksi, keterangan, total, pelanggan_id) VALUES
(1,  '2026-11-01', 'Pembelian rutin pagi', 41000, 'PLG001'),
(2,  '2026-11-01', 'Belanja harian', 20000, 'PLG002'),
(3,  '2026-11-02', 'Stok kebutuhan dapur', 50000, 'PLG003'),
(4,  '2026-11-02', 'Belanja sarapan', 28000, 'PLG001'),
(5,  '2026-11-03', 'Pembelian kopi', 39000, 'PLG004'),
(6,  '2026-11-04', 'Belanja sore', 40000, 'PLG005'),
(7,  '2026-11-04', 'Pembelian gula', 20000, 'PLG006'),
(8,  '2026-11-05', 'Belanja susu UHT', 36000, 'PLG007'),
(9,  '2026-11-05', 'Pembelian minyak goreng', 25000, 'PLG003'),
(10, '2026-11-06', 'Belanja mingguan', 33000, 'PLG008'),
(11, '2026-11-07', 'Belanja akhir pekan', 70000, 'PLG009'),
(12, '2026-11-07', 'Pembelian teh', 15000, 'PLG002');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1, 1, 13000, 2), (1, 2, 15000, 1),
(2, 3, 20000, 1),
(3, 4, 25000, 2),
(4, 5, 10000, 1), (4, 6, 18000, 1),
(5, 1, 13000, 3),
(6, 2, 15000, 2), (6, 5, 10000, 1),
(7, 3, 20000, 1),
(8, 6, 18000, 2),
(9, 4, 25000, 1),
(10, 1, 13000, 1), (10, 3, 20000, 1),
(11, 4, 25000, 2), (11, 5, 10000, 2),
(12, 2, 15000, 1);

INSERT INTO pembayaran (id, waktu_bayar, total, metode, transaksi_id) VALUES
(1,  '2026-11-01 08:15:00', 41000, 'TUNAI', 1),
(2,  '2026-11-01 10:30:00', 20000, 'EDC', 2),
(3,  '2026-11-02 09:05:00', 50000, 'TRANSFER', 3),
(4,  '2026-11-02 16:40:00', 28000, 'TUNAI', 4),
(5,  '2026-11-03 11:20:00', 39000, 'EDC', 5),
(6,  '2026-11-04 14:10:00', 40000, 'TUNAI', 6),
(7,  '2026-11-04 17:00:00', 20000, 'TRANSFER', 7),
(8,  '2026-11-05 09:50:00', 36000, 'EDC', 8),
(9,  '2026-11-05 13:15:00', 25000, 'TUNAI', 9),
(10, '2026-11-06 15:25:00', 33000, 'TRANSFER', 10),
(11, '2026-11-07 10:00:00', 70000, 'EDC', 11),
(12, '2026-11-07 18:30:00', 15000, 'TUNAI', 12);