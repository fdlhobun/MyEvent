#My Event

Web sewa booth untuk event, dibuat pakai PHP dan MySQL sebagai tugas kuliah (CRUD dengan konsep OOP).

Alurnya simpel: pilih event, pilih booth hijau di denah, isi data penyewa, lalu bayar lewat halaman My Booth.

Fitur
Daftar event di halaman Home
Denah booth: hijau berarti tersedia, merah berarti sudah terisi (hover untuk lihat info)
Sewa booth, ubah data pesanan, dan batalkan pesanan
Pembayaran dengan pencatatan transaksi, status baru jadi lunas kalau nominalnya pas
Bukti pembayaran yang bisa dicetak
Yang dipakai

PHP 8, MySQL/MariaDB (PDO), HTML dan CSS biasa tanpa framework.

Struktur file
File	Isinya
classes.php	Class Database, Model, Acara, Booth, Pemesanan dan fungsi bantu
index.php	Home, daftar event
denah.php	Denah booth per event
pesan.php	Form sewa booth
my_booth.php	Daftar pesanan, ada aksi ubah dan batalkan
edit.php	Form ubah pesanan
bayar.php	Pembayaran dan riwayat transaksi
bukti.php	Bukti pembayaran
header.php, footer.php, riwayat.php	Potongan tampilan yang dipakai bersama
style.css	Tampilan
database.sql	Struktur tabel dan data contoh
Database

Ada 6 tabel: acara, booth, penyewa, pemesanan, pembayaran, dan transaksi. Satu event punya banyak booth, satu booth punya satu pesanan, satu pesanan punya satu tagihan, dan satu tagihan bisa punya banyak transaksi.

Catatan
Belum ada login, jadi halaman My Booth menampilkan semua pesanan.
Pesanan yang sudah lunas tidak bisa diubah atau dibatalkan.
Data event dan booth diisi lewat database.sql, belum ada halaman admin untuk mengelolanya.
Pembuat

Kelompok:
1. Akhmad Abizar Ghifari - 2510131310016
2. Muhammad Fadillah - 2510131310010
