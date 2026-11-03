<?php require 'classes.php';
$nav = 'home';
$a = (new Acara)->cari((int) ($_GET['id'] ??0));
if (!$a)
    go('index.php');
$bs = (new Booth)->denah($a['id_acara']);
$terisi = count(array_filter($bs, fn($b) => $b['status'] === 'terisi'));
$judul = $a['nama_event'];
include 'header.php'; ?>
<a class="back" href="index.php">&lsaquo; Kembali ke daftar event</a>
<h1><?= e($a['nama_event']) ?></h1>