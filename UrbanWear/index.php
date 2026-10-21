<?php
require_once 'config.php';
$page_title = 'Sodobna moda';
$stmt = $pdo->query("SELECT * FROM products ORDER BY featured DESC, id DESC LIMIT 4");
$products = $stmt->fetchAll();
require 'header.php';
?>
<section class="hero">
  <div class="hero-copy"><span class="eyebrow">KOLEKCIJA 2026</span><h1>Tvoj slog.<br><span>Tvoja pravila.</span></h1>
  <p>Premišljeni kosi za vsak dan. Odkrij udobje, preprostost in samozavesten slog.</p>
  <a class="button" href="shop.php">RAZIŠČI KOLEKCIJO <span>↗</span></a>
  <div class="hero-note"><span class="dot"></span> Novi kosi, svež videz</div></div>
  <div class="hero-art"><div class="hero-shape"></div><img src="https://images.unsplash.com/photo-1523398002811-999ca8dec234?auto=format&fit=crop&w=1100&q=85" alt="Moderna ulična moda"></div>
  <div class="hero-index">01 — URBAN ESSENTIALS</div>
</section>
<section class="benefits">
 <div><span>✳</span><strong>Izbran slog</strong><small>Kosi za vsak dan</small></div>
 <div><span>↗</span><strong>Enostavno nakupovanje</strong><small>Pregledna spletna trgovina</small></div>
 <div><span>♡</span><strong>Udobje na prvem mestu</strong><small>Moda brez kompliciranja</small></div>
</section>
<section class="section">
 <div class="section-heading"><div><span class="eyebrow">IZBRANO ZATE</span><h2>Priljubljeni kosi<span>.</span></h2></div><a class="arrow-link" href="shop.php">Vsi izdelki ↗</a></div>
 <div class="product-grid">
 <?php foreach ($products as $p): ?>
  <article class="product-card">
   <a class="product-image" href="product.php?id=<?= (int)$p['id'] ?>"><img src="<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>" loading="lazy"><?php if ($p['sale_price']): ?><span class="tag">AKCIJA</span><?php endif; ?></a>
   <div class="product-info"><div><span class="product-category"><?= e($p['category']) ?></span><h3><a href="product.php?id=<?= (int)$p['id'] ?>"><?= e($p['name']) ?></a></h3></div><strong><?= number_format((float)($p['sale_price'] ?: $p['price']),2,',','.') ?> €</strong></div>
   <form method="post" action="cart.php" class="add-form"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="add"><input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>"><button class="button button-dark button-full">DODAJ V KOŠARICO +</button></form>
  </article>
 <?php endforeach; ?>
 </div>
</section>
<section class="promo"><div><span class="eyebrow">MANJ JE VEČ</span><h2>Osnove, ki<br>naredijo razliko.</h2><p>Ustvari svojo kombinacijo z vsakodnevnimi kosi UrbanWear.</p><a class="button button-light" href="shop.php">POGLEJ PONUDBO ↗</a></div><img src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=1000&q=85" alt="Urban modni videz" loading="lazy"></section>
<?php require 'footer.php'; ?>
