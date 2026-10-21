<?php require_once __DIR__.'/config.php'; ?>
<!doctype html>
<html lang="sl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="UrbanWear – sodobna oblačila in modni dodatki.">
<title><?= isset($page_title) ? e($page_title).' | ' : '' ?>UrbanWear</title>
<link rel="stylesheet" href="assets/style.css">
<script defer src="assets/app.js"></script>
</head>
<body>
<div class="topline">BREZPLAČNA DOSTAVA NAD 70 € · NOVA KOLEKCIJA JE TUKAJ</div>
<header class="site-header">
  <a class="logo" href="index.php">urban<span>wear.</span></a>
  <button class="menu-toggle" aria-label="Odpri navigacijo" aria-expanded="false">☰</button>
  <nav class="nav">
    <a href="index.php">Domov</a>
    <a href="shop.php">Vsi izdelki</a>
    <a href="shop.php?category=Oblačila">Oblačila</a>
    <a href="shop.php?category=Dodatki">Dodatki</a>
    <a href="shop.php?sale=1">Akcije</a>
    <a href="about.php">O nas</a>
    <a href="contact.php">Kontakt</a>
  </nav>
  <div class="header-actions">
    <a class="cart-link" href="cart.php">Košarica <span class="cart-count"><?= cart_count() ?></span></a>
    <?php if (logged_in()): ?>
      <?php if (is_admin()): ?><a class="admin-link" href="admin/index.php">Admin</a><?php endif; ?>
      <span class="welcome">Živjo, <?= e($_SESSION['user']['username']) ?></span>
      <form class="inline-form" method="post" action="logout.php"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><button class="text-button">Odjava</button></form>
    <?php else: ?>
      <a href="login.php">Prijava</a><a class="button button-small" href="register.php">Registracija</a>
    <?php endif; ?>
  </div>
</header>
<main class="main">
<?php show_flash(); ?>
