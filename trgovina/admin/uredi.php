<?php
require_once "../funkcije.php";
zahtevajAdministratorja();
require_once "../povezava.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit("Neveljaven ID.");
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$izdelek = $stmt->fetch();

if (!$izdelek) {
    http_response_code(404);
    exit("Izdelek ne obstaja.");
}

$napaka = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    preveriCsrf();

    $naziv = trim($_POST["naziv"] ?? "");
    $opis = trim($_POST["opis"] ?? "");
    $cena = filter_var($_POST["cena"] ?? "", FILTER_VALIDATE_FLOAT);
    $zaloga = filter_var($_POST["zaloga"] ?? "", FILTER_VALIDATE_INT);

    if ($naziv === "" || strlen($naziv) > 150 ||
        $cena === false || $cena < 0 || $cena > 99999999.99 ||
        $zaloga === false || $zaloga < 0) {
        $napaka = "Vneseni podatki niso veljavni.";
    } else {
        $stmt = $pdo->prepare(
            "UPDATE products
             SET naziv = ?, opis = ?, cena = ?, zaloga = ?
             WHERE id = ?"
        );

        $stmt->execute([$naziv, $opis, $cena, $zaloga, $id]);

        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Uredi izdelek</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Uredi izdelek</h1>

    <?php if ($napaka): ?>
        <p class="napaka"><?= htmlspecialchars($napaka) ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="csrf"
               value="<?= htmlspecialchars(csrfZeton()) ?>">

        <label>Ime izdelka</label>
        <input type="text" name="naziv"
               value="<?= htmlspecialchars($izdelek["naziv"]) ?>"
               maxlength="150" required>

        <label>Opis</label>
        <textarea name="opis"><?= htmlspecialchars($izdelek["opis"] ?? "") ?></textarea>

        <label>Cena (€)</label>
        <input type="number" name="cena" min="0"
               max="99999999.99" step="0.01"
               value="<?= htmlspecialchars((string)$izdelek["cena"]) ?>"
               required>

        <label>Zaloga</label>
        <input type="number" name="zaloga" min="0"
               max="2147483647" step="1"
               value="<?= (int)$izdelek["zaloga"] ?>" required>

        <button type="submit">Shrani spremembe</button>
    </form>

    <a href="index.php">Nazaj</a>
</div>
</body>
</html>