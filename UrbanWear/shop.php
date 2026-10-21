<?php
require_once 'config.php';
$page_title='Vsi izdelki';
$q=trim($_GET['q']??''); $category=trim($_GET['category']??''); $sale=isset($_GET['sale']);
$sql="SELECT * FROM products WHERE 1=1"; $params=[];
if ($q!=='') { $sql.=" AND (name LIKE ? OR description LIKE ?)"; $params[]="%$q%"; $params[]="%$q%"; }
if ($category!=='') { $sql.=" AND category=?"; $params[]=$category; }
if ($sale) $sql.=" AND sale_price IS NOT NULL AND sale_price>0";
$sql.=" ORDER BY featured DESC, name ASC";
$stmt=$pdo->prepare($sql); $stmt->execute($params); $products=$stmt->fetchAll();
require 'header.php';
?>
<section class="page-banner"><span class="eyebrow">URBANWEAR / KOLEKCIJA</span><h1><?= $sale?'Posebne ponudbe':($category?e($category):'Najdi svoj slog') ?><span>.</span></h1><p>Dobri kosi. Preprost izbor. Tvoj vsakdan.</p></section>
<form class="filters" method="get"><div class="search-box"><input type="search" name="q" placeholder="Išči majico, pulover ..." value="<?= e($q) ?>"><button aria-label="Išči">⌕</button></div><select name="category" aria-label="Kategorija"><option value="">Vse kategorije</option><option <?= $category==='Oblačila'?'selected':'' ?> value="Oblačila">Oblačila</option><option <?= $category==='Dodatki'?'selected':'' ?> value="Dodatki">Dodatki</option><option <?= $category==='Obutev'?'selected':'' ?> value="Obutev">Obutev</option></select><button class="button button-dark" type="submit">FILTRIRAJ</button><?php if($q||$category||$sale): ?><a class="clear-link" href="shop.php">Počisti</a><?php endif; ?></form>
<p class="result-count"><?= count($products) ?> izdelkov</p>
<div class="product-grid shop-grid">
<?php foreach($products as $p): ?>
<article class="product-card"><a class="product-image" href="product.php?id=<?= (int)$p['id'] ?>"><img src="<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>" loading="lazy"><?php if($p['sale_price']): ?><span class="tag">AKCIJA</span><?php endif; ?></a><div class="product-info"><div><span class="product-category"><?= e($p['category']) ?></span><h3><a href="product.php?id=<?= (int)$p['id'] ?>"><?= e($p['name']) ?></a></h3></div><strong><?= number_format((float)($p['sale_price'] ?: $p['price']),2,',','.') ?> €</strong></div><form method="post" action="cart.php" class="add-form"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="add"><input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>"><button class="button button-dark button-full">DODAJ V KOŠARICO +</button></form></article>
<?php endforeach; ?>
</div>
<?php if(!$products): ?><div class="empty-state"><h2>Ni najdenih izdelkov.</h2><p>Poskusi z drugim iskalnim izrazom ali kategorijo.</p><a class="button" href="shop.php">Prikaži vse izdelke</a></div><?php endif; ?>
<?php require 'footer.php'; ?>
