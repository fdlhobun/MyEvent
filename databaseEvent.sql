CREATE DATABASE IF NOT EXISTS my_event CHARACTER SET utf8mb4;
USE my_event;
DROP TABLE IF EXISTS transaksi, pembayaran, pemesanan, penyewa, booth, acara;

CREATE TABLE acara (
  id_acara INT AUTO_INCREMENT PRIMARY KEY,
  nama_event VARCHAR(100) NOT NULL,
  lokasi VARCHAR(100) NOT NULL,
  tanggal_event DATE NOT NULL,
  harga_booth DECIMAL(12,2) NOT NULL
);
CREATE TABLE booth (
  id_booth INT AUTO_INCREMENT PRIMARY KEY,
  id_acara INT NOT NULL,
  kode_booth VARCHAR(5) NOT NULL,
  status ENUM('tersedia','terisi') NOT NULL DEFAULT 'tersedia',
  UNIQUE (id_acara, kode_booth),
  FOREIGN KEY (id_acara) REFERENCES acara(id_acara) ON DELETE CASCADE
);
CREATE TABLE penyewa (
  id_penyewa INT AUTO_INCREMENT PRIMARY KEY,
  nama_penyewa VARCHAR(100) NOT NULL,
  asal_komunitas VARCHAR(100) NOT NULL
);
CREATE TABLE pemesanan (
  id_pemesanan INT AUTO_INCREMENT PRIMARY KEY,
  id_booth INT NOT NULL UNIQUE,
  id_penyewa INT NOT NULL,
  tanggal_sewa DATE NOT NULL,
  FOREIGN KEY (id_booth) REFERENCES booth(id_booth),
  FOREIGN KEY (id_penyewa) REFERENCES penyewa(id_penyewa)
);
CREATE TABLE pembayaran (
  id_pembayaran INT AUTO_INCREMENT PRIMARY KEY,
  id_pemesanan INT NOT NULL UNIQUE,
  metode ENUM('Transfer Bank','QRIS','Tunai') NOT NULL,
  jumlah_bayar DECIMAL(12,2) NOT NULL,
  status_bayar ENUM('belum','lunas') NOT NULL DEFAULT 'belum',
  FOREIGN KEY (id_pemesanan) REFERENCES pemesanan(id_pemesanan) ON DELETE CASCADE
);
CREATE TABLE transaksi (
  id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
  id_pembayaran INT NOT NULL,
  no_transaksi VARCHAR(25) NOT NULL UNIQUE,
  nominal DECIMAL(12,2) NOT NULL,
  tanggal_transaksi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  status ENUM('berhasil','gagal') NOT NULL,
  FOREIGN KEY (id_pembayaran) REFERENCES pembayaran(id_pembayaran) ON DELETE CASCADE
);

INSERT INTO acara (id_acara, nama_event, lokasi, tanggal_event, harga_booth) VALUES
(NULL, 'Aeris Matsuri', 'Aeris Hotel, Banjarbaru', '2026-11-14', 350000.00),
(NULL, 'IMAGI IV', 'Gedung Bina Satria BJB', '2026-11-29', 200000.00),
(NULL, 'YTTA Vol.5: Seishun! Your youth, Your Story', 'Wetland Square BJM', '2027-01-01', 500000.00);
INSERT INTO booth (id_acara, kode_booth)
SELECT a.id_acara, CONCAT('B', n.i)
FROM acara a
JOIN (
    SELECT 1 i UNION SELECT 2 UNION SELECT 3
    UNION SELECT 4 UNION SELECT 5 UNION SELECT 6
    UNION SELECT 7 UNION SELECT 8 UNION SELECT 9
) n;


INSERT INTO penyewa (nama_penyewa, asal_komunitas) VALUES ('Nadel', 'TokuFams');
INSERT INTO pemesanan (id_booth, id_penyewa, tanggal_sewa) VALUES (1, 1, '2026-11-14');
INSERT INTO pembayaran (id_pemesanan, metode, jumlah_bayar, status_bayar) VALUES (1, 'QRIS', 350000, 'lunas');
INSERT INTO transaksi (id_pembayaran, no_transaksi, nominal, status) VALUES (1, 'TRX-CONTOH-01', 350000, 'berhasil');
UPDATE booth SET status = 'terisi' WHERE id_booth = 1;