<?php
require 'classes.php';
$nav = 'home';
$bo = (new Booth)->cari((int) ($_GET['booth'] ?? 0));
if (!$bo)
    go('index.php');
if ($bo['status'] !== 'tersedia') {
    flash('Booth ' . $bo['kode_booth'] . ' sudah terisi.');
    go('denah.php?id=' . $bo['id_acara']);
}
$err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $n = trim($_POST['nama'] ?? '');
    $k = trim($_POST['komunitas'] ?? '');
    $m = $_POST['metode'] ?? '';
    if ($n === '' || $k === '' || !in_array($m, METODE))
        $err = 'Lengkapi semua kolom.';
    else
        try {
            (new Pemesanan)->buat($bo['id_booth'], $n, $k, $m);
            flash('Booth berhasil dipesan. Selesaikan pembayaran lewat status di bawah.');
            go('my_booth.php');
        } catch (Exception $x) {
            $err = $x->getMessage();
        }
}
$judul = 'Sewa Booth';
include 'header.php'; ?>
<a class="back" href="denah.php?id=<?= $bo['id_acara'] ?>">&lsaquo; Kembali ke denah</a>
<h1>Sewa Booth <?= e($bo['kode_booth']) ?></h1>
<p class="loc"><?= e($bo['nama_event']) ?> - <?= rp($bo['harga_booth']) ?></p>
<?php if ($err): ?>
    <div class="flash err"><?= e($err) ?></div><?php endif; ?>
<form method="post" class="form">
    <label>Nama penyewa<input name="nama" required value="<?= e($_POST['nama'] ?? '') ?>"></label>
    <label>Komunitas<input name="komunitas" required value="<?= e($_POST['komunitas'] ?? '') ?>"></label>
    <label>Tanggal sewa<input value="<?= tgl($bo['tanggal_event']) ?>" disabled><small>Mengikuti tanggal
            event.</small></label>
    <label>Metode pembayaran<select name="metode"><?php foreach (METODE as $m): ?>
                <option><?= $m ?></option><?php endforeach; ?>
        </select></label>
    <button>Pesan booth</button>
</form>
<?php include 'footer.php'; ?>