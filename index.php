<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'classes.php';
$judul = 'Home';
$nav = 'home';
$list = (new Acara)->semua();
include 'header.php'; ?>
<h1>Daftar Event</h1>
<div class="cards">
    <?php foreach ($list as $a): ?>
        <a class="card" href="denah.php?id=<?= $a['id_acara'] ?>">
            <h2><?= e($a['nama_event']) ?></h2>
            <p class="loc"><?= e($a['lokasi']) ?></p>
            <dl>
                <div>
                    <dt>Tanggal event</dt>
                    <dd><?= tgl($a['tanggal_event']) ?></dd>
                </div>
                <div>
                    <dt>Booth terisi</dt>
                    <dd><?= $a['terisi'] ?>/<?= $a['total'] ?></dd>
                </div>
                <div>
                    <dt>Harga Booth</dt>
                    <dd><?= rp($a['harga_booth']) ?></dd>
                </div>
            </dl>
        </a>
    <?php endforeach; ?>
</div>
<?php include 'footer.php'; ?>