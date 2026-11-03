<?php
require 'classes.php';
$nav = 'my';
$pm = new Pemesanan;
$r = $pm->cari((int) ($_GET['id'] ??0));
if (! $r)
    go('my_booth.php');
if ($r['status_bayar'] !== 'lunas')
    go('bayar.php?id=' . $r['id_pemesanan']);
$rw = $pm->riwayat($r['id_pemesanan']);
$t = current(array_filter($rw, fn($x) => $x['status'] === 'berhasil'));
$judul = 'Bukti Pembayaran';
include 'header.php'; ?>
<a class="back noprint" href="my_booth.php">&lsaquo; Kembali ke My Booth</a>
<h1>Bukti Pembayaran</h1>
<div class="bukti">
  <dl>
    <div>
      <dt>No. transaksi</dt>
      <dd><?= e($t['no_transaksi']) ?></dd>
    </div>
    <div>
      <dt>Event</dt>
      <dd><?= e($r['nama_event']) ?></dd>
    </div>
    <div>
      <dt>Booth</dt>
      <dd><?= e($r['kode_booth']) ?></dd>
    </div>
    <div>
      <dt>Penyewa</dt>
      <dd><?= e($r['nama_penyewa']) ?></dd>
    </div>
    <div>
      <dt>Komunitas</dt>
      <dd><?= e($r['asal_komunitas']) ?></dd>
    </div>
    <div>
      <dt>Metode</dt>
      <dd><?= e($r['metode']) ?></dd>
    </div>
    <div>
      <dt>Tanggal bayar</dt>
      <dd><?= date('d-m-Y H:i', strtotime($t['tanggal_transaksi'])) ?></dd>
    </div>
    <div>
      <dt>Total</dt>
      <dd class="big"><?= rp($t['nominal']) ?></dd>
    </div>
  </dl><span class="cap">LUNAS</span>
</div>
<p class="noprint"><button onclick="window.print()">Cetak bukti</button></p>
<div class="noprint"><?php include 'riwayat.php'; ?></div>
<?php include 'footer.php'; ?>