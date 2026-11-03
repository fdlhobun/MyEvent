<?php
session_start();
const METODE = ['Transfer Bank', 'QRIS', 'Tunai'];
function e($s) {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}
function rp($n) {
    return 'Rp ' . number_format((float) $n, 0, ',', '.');
}
function tgl($d) {
    return date('d-m-Y', strtotime($d));
}
function go($u) {
    header("Location: $u");
    exit;
}
function flash($m = null) {
    if ($m !== null) {
        $_SESSION['f'] = $m;
        return;
    }
    $x = $_SESSION['f'] ?? null;
    unset($_SESSION['f']);
    return $x;
}
class Database {
    private static ?PDO $pdo = null;
    public static function conn(): PDO {
        if (!self::$pdo) {
            self::$pdo = new PDO('mysql:host=localhost;dbname=my_event;charset=utf8mb4', 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        return self::$pdo;
    }
}
abstract class Model {
    protected PDO $db;
    public function __construct() {
        $this->db = Database::conn();
    }
    protected function q(string $sql, array $p = []): PDOStatement {
        $s = $this->db->prepare($sql);
        $s->execute($p);
        return $s;
    }
}
class Acara extends Model {
    public function semua() {
        return $this->q("SELECT a.*, COUNT(b.id_booth) total, COALESCE(SUM(b.status='terisi'),0) terisi
            FROM acara a LEFT JOIN booth b USING(id_acara) GROUP BY a.id_acara ORDER BY a.tanggal_event")->fetchAll();
    }
    public function cari($id) {
        return $this->q("SELECT * FROM acara WHERE id_acara=?", [$id])->fetch();
    }
}
class Booth extends Model {
    public function denah($idAcara) {
        return $this->q("SELECT b.*, p.nama_penyewa, p.asal_komunitas FROM booth b
            LEFT JOIN pemesanan m ON m.id_booth=b.id_booth
            LEFT JOIN penyewa p ON p.id_penyewa=m.id_penyewa
            WHERE b.id_acara=? ORDER BY b.id_booth", [$idAcara])->fetchAll();
    }
    public function cari($id) {
        return $this->q("SELECT b.*, a.nama_event, a.lokasi, a.tanggal_event, a.harga_booth
            FROM booth b JOIN acara a USING(id_acara) WHERE b.id_booth=?", [$id])->fetch();
    }
}
class Pemesanan extends Model {
    private const SQL = "SELECT m.*, b.kode_booth, b.id_acara, a.nama_event, a.lokasi, p.nama_penyewa, p.asal_komunitas,
        y.id_pembayaran, y.metode, y.jumlah_bayar, y.status_bayar
        FROM pemesanan m JOIN booth b USING(id_booth) JOIN acara a USING(id_acara)
        JOIN penyewa p USING(id_penyewa) JOIN pembayaran y USING(id_pemesanan)";

    public function semua() {
        return $this->q(self::SQL . " ORDER BY m.id_pemesanan DESC")->fetchAll();
    }
    public function cari($id) {
        return $this->q(self::SQL . " WHERE m.id_pemesanan=?", [$id])->fetch();
    }
    public function buat($idBooth, $nama, $komunitas, $metode) {
        $this->db->beginTransaction();
        try {
            $b = $this->q("SELECT b.status, a.tanggal_event, a.harga_booth FROM booth b
                JOIN acara a USING(id_acara) WHERE b.id_booth=? FOR UPDATE", [$idBooth])->fetch();
            if (!$b || $b['status'] !== 'tersedia')
                throw new Exception('Booth sudah tidak tersedia.');
            $this->q("INSERT INTO penyewa(nama_penyewa, asal_komunitas) VALUES(?,?)", [$nama, $komunitas]);
            $idPenyewa = $this->db->lastInsertId();
            $this->q("INSERT INTO pemesanan(id_booth, id_penyewa, tanggal_sewa) VALUES(?,?,?)", [$idBooth, $idPenyewa, $b['tanggal_event']]);
            $idM = $this->db->lastInsertId();
            $this->q("INSERT INTO pembayaran(id_pemesanan, metode, jumlah_bayar) VALUES(?,?,?)", [$idM, $metode, $b['harga_booth']]);
            $this->q("UPDATE booth SET status='terisi' WHERE id_booth=?", [$idBooth]);
            $this->db->commit();
            return $idM;
        } catch (Exception $x) {
            $this->db->rollBack();
            throw $x;
        }
    }
    public function ubah($idM, $nama, $komunitas, $metode) {
        $this->q("UPDATE penyewa p JOIN pemesanan m USING(id_penyewa) SET p.nama_penyewa=?, p.asal_komunitas=? WHERE m.id_pemesanan=?", [$nama, $komunitas, $idM]);
        $this->q("UPDATE pembayaran SET metode=? WHERE id_pemesanan=? AND status_bayar='belum'", [$metode, $idM]);
    }
    public function bayar($idM, $jumlah) {
        $m = $this->cari($idM);
        if (!$m || $m['status_bayar'] === 'lunas')
            throw new Exception('Pesanan tidak ditemukan atau sudah lunas.');
        $ok = (float) $jumlah === (float) $m['jumlah_bayar'];
        $this->db->beginTransaction();
        $this->q(
            "INSERT INTO transaksi(id_pembayaran, no_transaksi, nominal, status) VALUES(?,?,?,?)",
            [$m['id_pembayaran'], 'TRX' . date('ymdHis') . mt_rand(10, 99), $jumlah, $ok ? 'berhasil' : 'gagal']
        );
        if ($ok)
            $this->q("UPDATE pembayaran SET status_bayar='lunas' WHERE id_pembayaran=?", [$m['id_pembayaran']]);
        $this->db->commit();
        if (!$ok)
            throw new Exception('Nominal harus sama dengan total tagihan (' . rp($m['jumlah_bayar']) . '). Transaksi gagal tercatat.');
    }
    public function riwayat($idM) {
        return $this->q("SELECT t.* FROM transaksi t JOIN pembayaran y USING(id_pembayaran) WHERE y.id_pemesanan=? ORDER BY t.id_transaksi DESC", [$idM])->fetchAll();
    }
    public function hapus($idM) {
        $m = $this->cari($idM);
        if (!$m)
            throw new Exception('Pesanan tidak ditemukan.');
        if ($m['status_bayar'] === 'lunas')
            throw new Exception('Pesanan yang sudah lunas tidak dapat dibatalkan.');
        $this->db->beginTransaction();
        $this->q("DELETE FROM pemesanan WHERE id_pemesanan=?", [$idM]);
        $this->q("DELETE FROM penyewa WHERE id_penyewa=?", [$m['id_penyewa']]);
        $this->q("UPDATE booth SET status='tersedia' WHERE id_booth=?", [$m['id_booth']]);
        $this->db->commit();
    }
}