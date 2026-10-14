<?php
require_once "povezava.php";
require_once "funkcije.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit("Neveljaven izdelek.");
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$izdelek = $stmt->fetch();

if (!$izdelek) {
    http_response_code(404);
    exit("Izdelek ne obstaja.");
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($izdelek["naziv"]) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1><?= htmlspecialchars($izdelek["naziv"]) ?></h1>

    <?php if (!empty($izdelek["slika"])): ?>
        <img class="slika-izdelka"
             src="<?= htmlspecialchars($izdelek["slika"]) ?>"
             alt="<?= htmlspecialchars($izdelek["naziv"]) ?>">
    <?php endif; ?>

    <p><?= nl2br(htmlspecialchars($izdelek["opis"] ?? "")) ?></p>

    <p class="cena">
        Cena: <?= number_format((float)$izdelek["cena"], 2, ",", ".") ?> €
    </p>

    <p>Zaloga: <?= (int)$izdelek["zaloga"] ?></p>

    <?php if ((int)$izdelek["zaloga"] > 0): ?>
        <form action="kosarica.php" method="post">
            <input type="hidden" name="csrf"
                   value="<?= htmlspecialchars(csrfZeton()) ?>">
            <input type="hidden" name="akcija" value="dodaj">
            <input type="hidden" name="id" value="<?= (int)$izdelek["id"] ?>">
            <button type="submit">Dodaj v košarico</button>
        </form>
    <?php endif; ?>

    <a href="index.php">Nazaj v trgovino</a>
</div>
</body>
</html>