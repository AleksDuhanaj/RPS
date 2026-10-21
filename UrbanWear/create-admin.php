<?php
require_once 'config.php';
if ((int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn() > 0) {
    exit('Administrator že obstaja. Zaradi varnosti izbriši datoteko create-admin.php in se prijavi.');
}
$errors=[];$username='';$email='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$username=trim($_POST['username']??'');$email=trim($_POST['email']??'');$password=$_POST['password']??'';$confirm=$_POST['confirm']??'';
 if(!preg_match('/^[\p{L}\p{N}_.-]{3,30}$/u',$username))$errors[]='Uporabniško ime mora imeti 3–30 znakov.';
 if(!filter_var($email,FILTER_VALIDATE_EMAIL))$errors[]='Vnesi veljaven e-poštni naslov.';
 if(strlen($password)<12)$errors[]='Administratorsko geslo mora imeti vsaj 12 znakov.';
 if($password!==$confirm)$errors[]='Gesli se ne ujemata.';
 $stmt=$pdo->prepare('SELECT id FROM users WHERE username=? OR email=?');$stmt->execute([$username,$email]);if($stmt->fetch())$errors[]='Uporabniško ime ali e-pošta že obstaja.';
 if(!$errors){$stmt=$pdo->prepare("INSERT INTO users(username,email,password_hash,role) VALUES(?,?,?,'admin')");$stmt->execute([$username,$email,password_hash($password,PASSWORD_DEFAULT)]);exit('Administrator je ustvarjen. Zdaj izbriši datoteko create-admin.php in se prijavi na login.php.');}
}
?><!doctype html><html lang="sl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Ustvari administratorja – UrbanWear</title><link rel="stylesheet" href="assets/style.css"></head><body><main class="main"><section class="auth-layout"><div class="auth-art"><h2>Prva nastavitev<br>administratorja.</h2></div><div class="auth-card"><span class="eyebrow">ENKRATNA NASTAVITEV</span><h1>Admin račun<span>.</span></h1><p>Ta stran je namenjena samo prvi lokalni namestitvi. Po uporabi jo izbriši.</p><?php if($errors): ?><div class="notice error"><ul><?php foreach($errors as $er): ?><li><?= e($er) ?></li><?php endforeach; ?></ul></div><?php endif; ?><form method="post" class="form-stack"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label>Uporabniško ime<input name="username" required minlength="3" maxlength="30" value="<?= e($username) ?>"></label><label>E-pošta<input type="email" name="email" required value="<?= e($email) ?>"></label><label>Geslo (najmanj 12 znakov)<input type="password" name="password" required minlength="12"></label><label>Ponovi geslo<input type="password" name="confirm" required minlength="12"></label><button class="button button-dark button-full">USTVARI ADMIN RAČUN</button></form></div></section></main></body></html>
