<?php require 'classes.php';
$nav = 'home';
$a = (new Acara)->cari((int) ($_GET['id'] ??0));