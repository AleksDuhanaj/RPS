<?php
require_once 'config.php';
if(logged_in()){header('Location: index.php');exit;}
$page_title='Prijava';$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$identity=trim($_POST['identity']??'');$password=$_POST['password']??'';
 $stmt=$pdo->prepare('SELECT id,username,email,password_hash,role FROM users WHERE username=? OR email=? LIMIT 1');$stmt->execute([$identity,$identity]);$user=$stmt->fetch();
 if($user && password_verify($password,$user['password_hash'])){session_regenerate_id(true);$_SESSION['user']=['id'=>(int)$user['id'],'username'=>$user['username'],'email'=>$user['email'],'role'=>$user['role']];flash('Uspešno si prijavljen.');header('Location: index.php');exit;}
 $error='Napačno uporabniško ime/e-pošta ali geslo.';
}
require 'header.php';
?>
<section class="auth-layout"><div class="auth-art"><span class="eyebrow">DOBRODOŠEL NAZAJ</span><h2>Tvoj slog<br>te čaka.</h2><img src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=80" alt="Modna kolekcija"></div><div class="auth-card"><span class="eyebrow">MOJ RAČUN</span><h1>Prijava<span>.</span></h1><p>Vpiši se in nadaljuj z nakupovanjem.</p><?php if($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?><form method="post" class="form-stack"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label>Uporabniško ime ali e-pošta<input name="identity" required autocomplete="username" value="<?= e($_POST['identity']??'') ?>"></label><label>Geslo<input type="password" name="password" required autocomplete="current-password"></label><button class="button button-dark button-full">PRIJAVI SE</button></form><p class="auth-bottom">Nimaš računa? <a href="register.php">Registriraj se</a></p><p class="small-muted">Za demonstracijo administratorja glej README.txt.</p></div></section>
<?php require 'footer.php'; ?>
