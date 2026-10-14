<?php
require_once "povezava.php";
require_once "funkcije.php";

$iskanje = trim($_GET["iskanje"] ?? "");

if ($iskanje !== "") {
    $stmt = $pdo->prepare(
        "SELECT * FROM products
         WHERE naziv LIKE ? OR opis LIKE ?
         ORDER BY id DESC"
    );

    $vzorec = "%" . $iskanje . "%";
    $stmt->execute([$vzorec, $vzorec]);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
}

$izdelki = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Spletna trgovina</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Spletna trgovina</h1>

    <nav>
        <a href="index.php">Domov</a>
        <a href="kosarica.php">Košarica</a>

        <?php if (jePrijavljen()): ?>
            <span>Pozdravljen, <?= htmlspecialchars($_SESSION["ime"]) ?>!</span>

            <?php if (($_SESSION["vloga"] ?? "") === "administrator"): ?>
                <a href="admin/index.php">Administracija</a>
            <?php endif; ?>

            <a href="odjava.php">Odjava</a>
        <?php else: ?>
            <a href="prijava.php">Prijava</a>
            <a href="registracija.php">Registracija</a>
        <?php endif; ?>
    </nav>
</header>

<main>
    <form method="get" class="iskanje">
        <input type="text" name="iskanje"
               placeholder="Išči izdelek..."
               value="<?= htmlspecialchars($iskanje) ?>">
        <button type="submit">Išči</button>
    </form>

    <div class="izdelki">
        <?php foreach ($izdelki as $izdelek): ?>
            <article class="kartica">
                <?php if (!empty($izdelek["slika"])): ?>
                    <img src="<?= htmlspecialchars($izdelek["slika"]) ?>"
                         alt="<?= htmlspecialchars($izdelek["naziv"]) ?>">
                <?php endif; ?>

                <h2><?= htmlspecialchars($izdelek["naziv"]) ?></h2>

                <p>
                    <?= htmlspecialchars(mb_strimwidth(
                        $izdelek["opis"] ?? "", 0, 100, "..."
                    )) ?>
                </p>

                <p class="cena">
                    <?= number_format((float)$izdelek["cena"], 2, ",", ".") ?> €
                </p>

                <a href="izdelek.php?id=<?= (int)$izdelek["id"] ?>">
                    Podrobnosti
                </a>

                <form method="post" action="kosarica.php">
                    <input type="hidden" name="csrf"
                           value="<?= htmlspecialchars(csrfZeton()) ?>">
                    <input type="hidden" name="akcija" value="dodaj">
                    <input type="hidden" name="id"
                           value="<?= (int)$izdelek["id"] ?>">
                    <button type="submit"
                        <?= (int)$izdelek["zaloga"] < 1 ? "disabled" : "" ?>>
                        Dodaj v košarico
                    </button>
                </form>
            </article>
        <?php endforeach; ?>

        <?php if (!$izdelki): ?>
            <p>Ni najdenih izdelkov.</p>
        <?php endif; ?>
    </div>
</main>
</body>
</html>