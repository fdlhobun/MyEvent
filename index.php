<?php require 'classes.php';
$judul = 'Home';
$nav = 'home';
$list = (new Acara)->semua();
include 'header.php'; ?>
<h1>Daftar Event</h1>
<div class = "cards">
    
</div>