<?php
require_once "../funkcije.php";
zahtevajAdministratorja();
require_once "../povezava.php";

$napaka = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    preveriCsrf();

    $naziv = trim($_POST["naziv"] ?? "");
    $opis = trim($_POST["opis"] ?? "");
    $cena = filter_var($_POST["cena"] ?? "", FILTER_VALIDATE_FLOAT);
    $zaloga = filter_var($_POST["zaloga"] ?? "", FILTER_VALIDATE_INT);

    if ($naziv === "" || strlen($naziv) > 150 ||
        $cena === false || $cena < 0 ||
        $zaloga === false || $zaloga < 0) {
        $napaka = "Preveri vnesene podatke.";
    } elseif (
        !isset($_FILES["slika"]) ||
        $_FILES["slika"]["error"] !== UPLOAD_ERR_OK
    ) {
        $napaka = "Izberi sliko izdelka.";
    } elseif ($_FILES["slika"]["size"] > 3 * 1024 * 1024) {
        $napaka = "Slika je lahko velika največ 3 MB.";
    } else {
        $tmp = $_FILES["slika"]["tmp_name"];
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);

        $dovoljene = [
            "image/jpeg" => "jpg",
            "image/png" => "png",
            "image/webp" => "webp"
        ];

        if (!isset($dovoljene[$mime]) || !getimagesize($tmp)) {
            $napaka = "Dovoljene so slike JPG, PNG in WEBP.";
        } else {
            $mapa = __DIR__ . "/../slike/";

            if (!is_dir($mapa)) {
                mkdir($mapa, 0755, true);
            }

            $imeSlike = bin2hex(random_bytes(16))
                . "." . $dovoljene[$mime];

            if (!move_uploaded_file($tmp, $mapa . $imeSlike)) {
                $napaka = "Slike ni bilo mogoče shraniti.";
            } else {
                $pot = "slike/" . $imeSlike;

                $stmt = $pdo->prepare(
                    "INSERT INTO products
                    (naziv, opis, cena, zaloga, slika)
                    VALUES (?, ?, ?, ?, ?)"
                );

                $stmt->execute([
                    $naziv, $opis, $cena, $zaloga, $pot
                ]);

                header("Location: index.php");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj izdelek</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Dodaj izdelek</h1>

    <?php if ($napaka): ?>
        <p class="napaka"><?= htmlspecialchars($napaka) ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf"
               value="<?= htmlspecialchars(csrfZeton()) ?>">

        <label>Ime izdelka</label>
        <input type="text" name="naziv" maxlength="150" required>

        <label>Opis</label>
        <textarea name="opis"></textarea>

        <label>Cena (€)</label>
        <input type="number" name="cena" min="0"
               max="99999999.99" step="0.01" required>

        <label>Zaloga</label>
        <input type="number" name="zaloga" min="0"
               max="2147483647" step="1" value="0" required>

        <label>Slika izdelka</label>
        <input type="file" name="slika"
               accept=".jpg,.jpeg,.png,.webp" required>

        <button type="submit">Shrani izdelek</button>
    </form>

    <a href="index.php">Nazaj</a>
</div>
</body>
</html>