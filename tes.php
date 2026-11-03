<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
echo "PHP jalan<br>";
require 'classes.php';
try {
    print_r((new Acara)->semua());
} catch (Throwable $t) {
    echo $t->getMessage();
}