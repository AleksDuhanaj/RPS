<?php
// Osnovna povezava s podatkovno bazo UrbanWear.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$host = 'localhost';
$dbname = 'urbanwear';
$dbuser = 'root';
$dbpass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Povezava s podatkovno bazo ni uspela. Preveri, ali v XAMPP delujeta Apache in MySQL ter ali si uvozil database.sql.');
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function logged_in() { return isset($_SESSION['user']); }
function is_admin() { return logged_in() && ($_SESSION['user']['role'] ?? '') === 'admin'; }
function require_login() {
    if (!logged_in()) { header('Location: login.php'); exit; }
}
function require_admin() {
    if (!is_admin()) {
        http_response_code(403);
        exit('Dostop zavrnjen. Ta stran je samo za administratorja. <a href="index.php">Nazaj v trgovino</a>');
    }
}
function csrf_token() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function verify_csrf() {
    if (!isset($_POST['csrf']) || !hash_equals(csrf_token(), $_POST['csrf'])) {
        http_response_code(419); exit('Obrazec je potekel. Osveži stran in poskusi znova.');
    }
}
function cart_count() {
    $n = 0;
    foreach (($_SESSION['cart'] ?? []) as $qty) $n += (int)$qty;
    return $n;
}
function flash($message, $type='success') { $_SESSION['flash'] = ['message'=>$message, 'type'=>$type]; }
function show_flash() {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash']; unset($_SESSION['flash']);
        echo '<div class="notice '.e($f['type']).'">'.e($f['message']).'</div>';
    }
}
