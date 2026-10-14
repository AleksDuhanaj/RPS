<?php
require_once "povezava.php";
require_once "funkcije.php";

$napaka = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $uporabnik = trim($_POST["uporabnik"] ?? "");
    $geslo = $_POST["geslo"] ?? "";

    $stmt = $pdo->prepare(
        "SELECT * FROM users
         WHERE email = ? OR uporabnisko_ime = ?
         LIMIT 1"
    );
    $stmt->execute([$uporabnik, $uporabnik]);
    $user = $stmt->fetch();

    if ($user && password_verify($geslo, $user["geslo"])) {
        session_regenerate_id(true);

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["ime"] = $user["ime"];
        $_SESSION["vloga"] = $user["vloga"];

        header("Location: index.php");
        exit;
    } else {
        $napaka = "Napačno uporabniško ime ali geslo.";
    }
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Prijava</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Prijava</h1>

    <?php if (isset($_GET["uspeh"])): ?>
        <p class="uspeh">Registracija je uspela. Prijavi se.</p>
    <?php endif; ?>

    <?php if ($napaka): ?>
        <p class="napaka"><?= htmlspecialchars($napaka) ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Uporabniško ime ali e-pošta</label>
        <input type="text" name="uporabnik" required>

        <label>Geslo</label>
        <input type="password" name="geslo" required>

        <button type="submit">Prijava</button>
    </form>

    <p>Nimaš računa? <a href="registracija.php">Registracija</a></p>
    <a href="index.php">Domov</a>
</div>
</body>
</html>