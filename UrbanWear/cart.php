<?php
require_once 'config.php';
if(!isset($_SESSION['cart']))$_SESSION['cart']=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$action=$_POST['action']??'';
 if($action==='add'){
  $id=filter_input(INPUT_POST,'product_id',FILTER_VALIDATE_INT);$qty=filter_input(INPUT_POST,'quantity',FILTER_VALIDATE_INT) ?: 1;
  $stmt=$pdo->prepare('SELECT id,stock FROM products WHERE id=?');$stmt->execute([$id ?: 0]);$p=$stmt->fetch();
  if(!$p||$p['stock']<1){flash('Izdelka ni na zalogi.','error');}
  else{$current=(int)($_SESSION['cart'][$id]??0);$_SESSION['cart'][$id]=min((int)$p['stock'],$current+max(1,min(99,$qty)));flash('Izdelek je dodan v košarico.');}
  header('Location: cart.php');exit;
 }
 if($action==='update'){
  foreach(($_POST['qty']??[]) as $id=>$qty){$id=(int)$id;$qty=(int)$qty;if($qty<=0){unset($_SESSION['cart'][$id]);continue;}$stmt=$pdo->prepare('SELECT stock FROM products WHERE id=?');$stmt->execute([$id]);$p=$stmt->fetch();if(!$p)unset($_SESSION['cart'][$id]);else $_SESSION['cart'][$id]=min($p['stock'],min(99,$qty));}
  flash('Košarica je posodobljena.');header('Location: cart.php');exit;
 }
 if($action==='remove'){$id=(int)($_POST['product_id']??0);unset($_SESSION['cart'][$id]);flash('Izdelek je odstranjen.');header('Location: cart.php');exit;}
}
$page_title='Košarica';$items=[];$total=0;
if($_SESSION['cart']){$ids=array_map('intval',array_keys($_SESSION['cart']));$in=implode(',',array_fill(0,count($ids),'?'));$stmt=$pdo->prepare("SELECT * FROM products WHERE id IN ($in)");$stmt->execute($ids);foreach($stmt->fetchAll() as $p){$qty=min((int)$_SESSION['cart'][$p['id']],(int)$p['stock']);if($qty<1){unset($_SESSION['cart'][$p['id']]);continue;}$p['quantity']=$qty;$p['unit_price']=(float)($p['sale_price']?:$p['price']);$p['line_total']=$p['unit_price']*$qty;$total+=$p['line_total'];$items[]=$p;}}
require 'header.php';
?>
<section class="page-banner"><span class="eyebrow">TVOJ IZBOR</span><h1>Košarica<span>.</span></h1><p>Preglej izbrane kose in skupni znesek.</p></section>
<?php if(!$items): ?><div class="empty-state"><div class="empty-icon">＋</div><h2>Košarica je prazna.</h2><p>Čas je, da najdeš nekaj zase.</p><a class="button" href="shop.php">RAZIŠČI IZDELKE ↗</a></div><?php else: ?>
<form method="post" id="cart-form"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="update"><div class="cart-layout"><div class="cart-items"><?php foreach($items as $p): ?><article class="cart-item"><img src="<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>"><div class="cart-item-info"><span class="product-category"><?= e($p['category']) ?></span><h3><?= e($p['name']) ?></h3><p><?= number_format($p['unit_price'],2,',','.') ?> € / kos</p><label>Količina <input type="number" name="qty[<?= (int)$p['id'] ?>]" min="0" max="<?= (int)$p['stock'] ?>" value="<?= (int)$p['quantity'] ?>"></label></div><strong><?= number_format($p['line_total'],2,',','.') ?> €</strong><button class="remove-button" type="button" data-remove="<?= (int)$p['id'] ?>">Odstrani</button></article><?php endforeach; ?></div><aside class="cart-summary"><h2>Povzetek naročila</h2><div class="summary-row"><span>Izdelki</span><strong><?= number_format($total,2,',','.') ?> €</strong></div><div class="summary-row"><span>Dostava</span><span><?= $total>=70?'Brezplačno':'Obračuna se ob naročilu' ?></span></div><div class="summary-total"><span>Skupaj</span><strong><?= number_format($total,2,',','.') ?> €</strong></div><button class="button button-dark button-full">POSODOBI KOŠARICO</button><a class="button button-light button-full" href="shop.php">NADALJUJ NAKUP</a><p class="small-muted">Šolski projekt: plačilo in dejanska dostava nista vključena.</p></aside></div></form>
<form method="post" id="remove-form" hidden><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="remove"><input type="hidden" name="product_id" id="remove-id"></form>
<?php endif; ?>
<?php require 'footer.php'; ?>
