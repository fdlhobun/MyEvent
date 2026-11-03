<?php require 'classes.php';
$nav = 'home';
$a = (new Acara)->cari((int) ($_GET['id'] ?? 0));
if (!$a)
    go('index.php');
$bs = (new Booth)->denah($a['id_acara']);
$terisi = count(array_filter($bs, fn($b) => $b['status'] === 'terisi'));
$judul = $a['nama_event'];
include 'header.php'; ?>
<a class="back" href="index.php">&lsaquo; Kembali ke daftar event</a>
<h1><?= e($a['nama_event']) ?></h1>
<p class="loc"><?= e($a['lokasi']) ?>, <?= tgl($a['tanggal_event']) ?>. Terisi <?= $terisi ?>/<?= count($bs) ?> Booth
    <?= rp($a['harga_booth']) ?> per booth.
</p>
<h2>Denah Event</h2>
<div class="legend"><span class="ok"></span>Tersedia <span class="full"></span>Tidak tersedia</div>
<div class="denah">
    <?php foreach ($bs as $b):
        $ada = $b['status'] === 'tersedia';
        $area = strtolower(preg_replace('/\W/', '', $b['kode_booth']));
        $tip = 'Status: ' . ($ada ? 'Tersedia' : 'Terisi') . ($ada ? '' : "\nPenyewa: {$b['nama_penyewa']}\nKomunitas: {$b['asal_komunitas']}");
        $tag = $ada ? 'a' : 'div'; ?>
        <<?= $tag ?> class="booth <?= $ada ? 'ok' : 'full' ?>" style="grid-area:<?= $area ?>" data-tip="<?= e($tip) ?>"<?= $ada ? ' href="pesan.php?booth=' . $b['id_booth'] . '"' : '' ?>><?= e($b['kode_booth']) ?></<?= $tag ?>>
    <?php endforeach; ?>
    <div class="jalan">&rarr; &rarr; &rarr;</div>
</div>
<p class="hint">Klik booth hijau untuk menyewa. Arahkan kursor ke booth untuk melihat statusnya.</p>
<?php include 'footer.php'; ?>