<?php
require_once "povezava.php";
require_once "funkcije.php";

$napaka = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ime = trim($_POST["ime"] ?? "");
    $priimek = trim($_POST["priimek"] ?? "");
    $uporabnisko = trim($_POST["uporabnisko_ime"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $geslo = $_POST["geslo"] ?? "";
    $ponovitev = $_POST["ponovitev"] ?? "";

    if ($ime === "" || $priimek === "") {
        $napaka = "Vnesi ime in priimek.";
    } elseif (!preg_match('/^[a-zA-Z0-9_.-]{3,50}$/', $uporabnisko)) {
        $napaka = "Uporabniško ime mora imeti 3–50 dovoljenih znakov.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)
        || strlen($email) > 150) {
        $napaka = "E-poštni naslov ni veljaven.";
    } elseif (strlen($geslo) < 8) {
        $napaka = "Geslo mora imeti vsaj 8 znakov.";
    } elseif ($geslo !== $ponovitev) {
        $napaka = "Gesli se ne ujemata.";
    } else {
        $stmt = $pdo->prepare(
            "SELECT id FROM users
             WHERE email = ? OR uporabnisko_ime = ?"
        );
        $stmt->execute([$email, $uporabnisko]);

        if ($stmt->fetch()) {
            $napaka = "E-pošta ali uporabniško ime že obstaja.";
        } else {
            $hash = password_hash($geslo, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                "INSERT INTO users
                (ime, priimek, uporabnisko_ime, email, geslo)
                VALUES (?, ?, ?, ?, ?)"
            );

            try {
                $stmt->execute([
                    $ime, $priimek, $uporabnisko, $email, $hash
                ]);

                header("Location: prijava.php?uspeh=1");
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() === "23000") {
                    $napaka = "Uporabnik s temi podatki že obstaja.";
                } else {
                    throw $e;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Registracija</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
<div class="container">
    <h1>Registracija</h1>

    <?php if ($napaka): ?>
        <p class="napaka"><?= htmlspecialchars($napaka) ?></p>
    <?php endif; ?>

    <form method="post" id="registracijaForm">
        <label>Ime</label>
        <input type="text" name="ime" maxlength="100" required>

        <label>Priimek</label>
        <input type="text" name="priimek" maxlength="100" required>

        <label>Uporabniško ime</label>
        <input type="text" name="uporabnisko_ime"
               minlength="3" maxlength="50" required>

        <label>E-pošta</label>
        <input type="email" name="email" maxlength="150" required>

        <label>Geslo</label>
        <input type="password" name="geslo" id="geslo"
               minlength="8" required>

        <label>Ponovi geslo</label>
        <input type="password" name="ponovitev" id="ponovitev"
               minlength="8" required>

        <button type="submit">Registracija</button>
    </form>

    <p>Že imaš račun? <a href="prijava.php">Prijava</a></p>
    <a href="index.php">Domov</a>
</div>
</body>
</html>