<?php
require 'classes.php';
$nav = 'my';
$pm = new Pemesanan;
$r = $pm->cari((int) ($_GET['id'] ?? 0));
if (!$r || $r['status_bayar'] === 'lunas') {
    flash('Pesanan tidak ditemukan atau sudah lunas.');
    go('my_booth.php');
}
$err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $n = trim($_POST['nama'] ?? '');
    $k = trim($_POST['komunitas'] ?? '');
    $m = $_POST['metode'] ?? '';
    if ($n === '' || $k === '' || !in_array($m, METODE))
        $err = 'Lengkapi semua kolom.';
    else {
        $pm->ubah($r['id_pemesanan'], $n, $k, $m);
        flash('Data pesanan diperbarui.');
        go('my_booth.php');
    }
}
$judul = 'Ubah Pesanan';
include 'header.php'; ?>
<a class="back" href="my_booth.php">&lsaquo; Kembali ke My Booth</a>
<h1>Ubah pesanan booth <?= e($r['kode_booth']) ?></h1>
<p class="loc"><?= e($r['nama_event']) ?></p>
<?php if ($err): ?>
    <div class="flash err"><?= e($err) ?></div><?php endif; ?>
<form method="post" class="form">
    <label>Nama penyewa<input name="nama" required value="<?= e($_POST['nama'] ?? $r['nama_penyewa']) ?>"></label>
    <label>Komunitas<input name="komunitas" required
            value="<?= e($_POST['komunitas'] ?? $r['asal_komunitas']) ?>"></label>
    <label>Metode pembayaran<select name="metode"><?php foreach (METODE as $m): ?>
                <option <?= ($_POST['metode'] ?? $r['metode']) === $m ? 'selected' : '' ?>><?= $m ?></option>
            <?php endforeach; ?>
        </select></label>
    <button>Simpan perubahan</button>
</form>
<?php include 'footer.php'; ?>