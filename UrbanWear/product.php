<?php
require_once 'config.php';
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
$stmt=$pdo->prepare('SELECT * FROM products WHERE id=?'); $stmt->execute([$id ?: 0]); $p=$stmt->fetch();
if(!$p){http_response_code(404);$page_title='Izdelek ni najden';require 'header.php';echo '<div class="empty-state"><h1>Izdelek ni najden.</h1><a class="button" href="shop.php">Nazaj v trgovino</a></div>';require 'footer.php';exit;}
$page_title=$p['name']; require 'header.php';
?>
<div class="breadcrumbs"><a href="index.php">Domov</a> / <a href="shop.php">Izdelki</a> / <?= e($p['name']) ?></div>
<section class="product-detail"><div class="detail-image"><img src="<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>"></div><div class="detail-copy"><span class="eyebrow"><?= e($p['category']) ?> / URBANWEAR</span><h1><?= e($p['name']) ?></h1><div class="detail-price"><?php if($p['sale_price']): ?><del><?= number_format((float)$p['price'],2,',','.') ?> €</del><?php endif; ?> <?= number_format((float)($p['sale_price'] ?: $p['price']),2,',','.') ?> €</div><p><?= nl2br(e($p['description'])) ?></p><p class="stock"><?= $p['stock']>0?'● Na zalogi':'○ Trenutno ni na zalogi' ?></p><?php if($p['stock']>0): ?><form method="post" action="cart.php" class="detail-add"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="add"><input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>"><label for="quantity">Količina</label><input id="quantity" type="number" name="quantity" min="1" max="<?= (int)$p['stock'] ?>" value="1"><button class="button button-dark">DODAJ V KOŠARICO +</button></form><?php endif; ?><p class="small-muted">Varno in preprosto nakupovanje · UrbanWear</p></div></section>
<?php require 'footer.php'; ?>
