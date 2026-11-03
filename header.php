<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= e($judul ?? 'My Event') ?> - My Event</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;800&family=Figtree:wght@400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="awning" aria-hidden="true"></div>
        <div class="bar"><a class="brand" href="index.php">My Event</a>
            <p class="tag">Sewa booth untuk event hanya disini</p>
        </div>
        <nav><a href="index.php" class="<?= ($nav ?? '') === 'home' ? 'on' : '' ?>">Home</a><a href="my_booth.php"
                class="<?= ($nav ?? '') === 'my' ? 'on' : '' ?>">My Booth</a></nav>
    </header>
    <main>
        <?php if ($f = flash()): ?>
            <div class="flash"><?= e($f) ?></div><?php endif; ?>