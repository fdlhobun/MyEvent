<?php
require 'classes.php';
$judul = 'My Booth';
$nav = 'my';
$pm = new Pemesanan;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['batal'])) {
    try {
        $pm->hapus((int) $_POST['batal']);
        flash('Pesanan dibatalkan, booth kembali tersedia.');
    } catch (Exception $x) {
        flash($x->getMessage());
        } go('my_booth.php');
}
