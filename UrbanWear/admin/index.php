<?php
require_once __DIR__.'/../config.php';require_admin();
$page_title='Administracija';
$countProducts=(int)$pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();$countUsers=(int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$products=$pdo->query('SELECT id,name,category,price,sale_price,stock FROM products ORDER BY id DESC')->fetchAll();
require __DIR__.'/../header.php';
?>
<section class="page-banner admin-banner"><span class="eyebrow">URBANWEAR / NADZOR</span><h1>Administracija<span>.</span></h1><p>Upravljaj katalogom izdelkov trgovine.</p></section>
<div class="stats-grid"><div class="stat-card"><span>Vsi izdelki</span><strong><?= $countProducts ?></strong><a href="#products-table">Upravljaj izdelke ↗</a></div><div class="stat-card"><span>Registrirani uporabniki</span><strong><?= $countUsers ?></strong><small>Vključno z administratorji</small></div><div class="stat-card"><span>Administratorski dostop</span><strong>AKTIVEN</strong><small>Prijavljen kot <?= e($_SESSION['user']['username']) ?></small></div></div>
<div class="admin-toolbar"><div><h2>Izdelki</h2><p>Dodaj, uredi ali odstrani izdelke iz trgovine.</p></div><a class="button" href="product-form.php">+ DODAJ IZDELEK</a></div>
<div class="table-wrap" id="products-table"><table><thead><tr><th>Izdelek</th><th>Kategorija</th><th>Cena</th><th>Zaloga</th><th>Dejanja</th></tr></thead><tbody><?php foreach($products as $p): ?><tr><td><strong><?= e($p['name']) ?></strong><small>#<?= (int)$p['id'] ?></small></td><td><?= e($p['category']) ?></td><td><?= number_format((float)($p['sale_price']?:$p['price']),2,',','.') ?> €</td><td><?= (int)$p['stock'] ?></td><td class="actions"><a href="product-form.php?id=<?= (int)$p['id'] ?>">Uredi</a><form method="post" action="delete-product.php" data-confirm="Res želiš izbrisati ta izdelek?"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="danger-link">Izbriši</button></form></td></tr><?php endforeach; ?></tbody></table></div>
<?php require __DIR__.'/../footer.php'; ?>
