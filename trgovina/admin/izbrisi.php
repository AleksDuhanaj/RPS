<?php
require_once "../funkcije.php";
zahtevajAdministratorja();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Nedovoljena metoda.");
}

preveriCsrf();

require_once "../povezava.php";

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit("Neveljaven ID.");
}

$stmt = $pdo->prepare("SELECT slika FROM products WHERE id = ?");
$stmt->execute([$id]);
$izdelek = $stmt->fetch();

if ($izdelek) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);

    if (!empty($izdelek["slika"])) {
        $pot = realpath(__DIR__ . "/../" . $izdelek["slika"]);
        $mapaSlik = realpath(__DIR__ . "/../slike");

        if ($pot && $mapaSlik &&
            dirname($pot) === $mapaSlik &&
            is_file($pot)) {
            unlink($pot);
        }
    }
}

header("Location: index.php");
exit;
?>