<?php
require ('classes.php');
$nav = 'my';
$pm = new Pemesanan;
$r = $pm->cari((int) ($_GET[''] ?? 0));
if (!$r || $r['status_bayar'] === 'lunas') {
  flash('Pesanan tidak ditemukan atau sudah lunas.');
  go('my_booth.php');
}
$err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $pm->bayar($r['id_pemesanan'], (float) preg_replace('/\D/', '', $_POST['jumlah'] ?? ''));
    flash('Pembayaran selesai. Booth ' . $r['kode_booth'] . ' kini lunas.');
    go('bukti.php?id=' . $r['id_pemesanan']);
  } catch (Exception $x) {
    $err = $x->getMessage();
  }
}
$rw = $pm->riwayat($r['id_pemesanan']);
$judul = 'Pembayaran';
include 'header.php'; ?>
<a class="back" href="my_booth.php">&lsaquo; Kembali ke My Booth</a>
<h1>Pembayaran booth <?= e($r['kode_booth']) ?></h1>
<dl class="ringkas">
  <div>
    <dt>Event</dt>
    <dd><?= e($r['nama_event']) ?></dd>
  </div>
  <div>
    <dt>Penyewa</dt>
    <dd><?= e($r['nama_penyewa']) ?>, <?= e($r['asal_komunitas']) ?></dd>
  </div>
  <div>
    <dt>Metode</dt>
    <dd><?= e($r['metode']) ?></dd>
  </div>
  <div>
    <dt>Total bayar</dt>
    <dd class="big"><?= rp($r['jumlah_bayar']) ?></dd>
  </div>
</dl>
<p class="hint">Status menjadi lunas hanya setelah transaksi diselesaikan di sini.</p>
<?php if ($err): ?>
  <div class="flash err"><?= e($err) ?></div><?php endif; ?>
<form method="post" class="form">
  <label>Nominal dibayar<input name="jumlah" inputmode="numeric" required
      placeholder="<?= (int) $r['jumlah_bayar'] ?>"><small>Isi sesuai total tagihan.</small></label>
  <button class="pay">Selesaikan transaksi</button>
</form>
<?php include 'riwayat.php';
include 'footer.php'; ?>