<?php
require_once __DIR__.'/../config.php';require_admin();
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: index.php');exit;}
verify_csrf();$id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
if($id){$stmt=$pdo->prepare('DELETE FROM products WHERE id=?');$stmt->execute([$id]);flash($stmt->rowCount()?'Izdelek je izbrisan.':'Izdelek ni bil najden.',$stmt->rowCount()?'success':'error');}
header('Location: index.php');exit;
