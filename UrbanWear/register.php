<?php
require_once 'config.php';
if(logged_in()){header('Location: index.php');exit;}
$page_title='Registracija';$errors=[];$username='';$email='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$username=trim($_POST['username']??'');$email=trim($_POST['email']??'');$password=$_POST['password']??'';$confirm=$_POST['confirm']??'';
 if(!preg_match('/^[\p{L}\p{N}_.-]{3,30}$/u',$username))$errors[]='Uporabniško ime naj ima 3–30 znakov (črke, številke, . _ -).';
 if(!filter_var($email,FILTER_VALIDATE_EMAIL))$errors[]='Vnesi veljaven e-poštni naslov.';
 if(strlen($password)<8)$errors[]='Geslo mora imeti vsaj 8 znakov.';
 if($password!==$confirm)$errors[]='Gesli se ne ujemata.';
 if(!$errors){$stmt=$pdo->prepare('SELECT id FROM users WHERE username=? OR email=?');$stmt->execute([$username,$email]);if($stmt->fetch())$errors[]='To uporabniško ime ali e-poštni naslov že obstaja.';}
 if(!$errors){$stmt=$pdo->prepare("INSERT INTO users(username,email,password_hash,role) VALUES(?,?,?,'user')");$stmt->execute([$username,$email,password_hash($password,PASSWORD_DEFAULT)]);flash('Registracija je uspela. Zdaj se lahko prijaviš.');header('Location: login.php');exit;}
}
require 'header.php';
?>
<section class="auth-layout"><div class="auth-art"><span class="eyebrow">POSTANI DEL SKUPNOSTI</span><h2>Najdi svoj<br>vsakdanji slog.</h2><img src="https://images.unsplash.com/photo-1523398002811-999ca8dec234?auto=format&fit=crop&w=900&q=80" alt="UrbanWear moda"></div><div class="auth-card"><span class="eyebrow">NOV RAČUN</span><h1>Ustvari račun<span>.</span></h1><p>Pridruži se UrbanWear skupnosti.</p><?php if($errors): ?><div class="notice error"><ul><?php foreach($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div><?php endif; ?><form method="post" class="form-stack" data-validate><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label>Uporabniško ime<input name="username" required minlength="3" maxlength="30" value="<?= e($username) ?>" autocomplete="username"></label><label>E-pošta<input type="email" name="email" required value="<?= e($email) ?>" autocomplete="email"></label><label>Geslo<input type="password" name="password" required minlength="8" autocomplete="new-password"></label><label>Ponovi geslo<input type="password" name="confirm" required minlength="8" autocomplete="new-password"></label><button class="button button-dark button-full">USTVARI RAČUN</button></form><p class="auth-bottom">Že imaš račun? <a href="login.php">Prijavi se</a></p></div></section>
<?php require 'footer.php'; ?>
