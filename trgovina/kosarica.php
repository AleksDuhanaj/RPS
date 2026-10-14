<?php
require_once "povezava.php";
require_once "funkcije.php";

if (!isset($_SESSION["kosarica"])) {
    $_SESSION["kosarica"] = [];
}

$sporocilo = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    preveriCsrf();

    $akcija = $_POST["akcija"] ?? "";
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    if (!$id || $id < 1) {
        http_response_code(400);
        exit("Neveljaven izdelek.");
    }

    if ($akcija === "dodaj") {
        $stmt = $pdo->prepare(
            "SELECT id, zaloga FROM products WHERE id = ?"
        );
        $stmt->execute([$id]);
        $izdelek = $stmt->fetch();

        if (!$izdelek || (int)$izdelek["zaloga"] < 1) {
            $sporocilo = "Izdelek ni na zalogi.";
        } else {
            $trenutna = (int)($_SESSION["kosarica"][$id] ?? 0);

            $_SESSION["kosarica"][$id] = min(
                $trenutna + 1,
                (int)$izdelek["zaloga"]
            );

            header("Location: kosarica.php");
            exit;
        }
    } elseif ($akcija === "posodobi") {
        $kolicina = filter_input(
            INPUT_POST, "kolicina", FILTER_VALIDATE_INT
        );

        if ($kolicina === false || $kolicina === null) {
            $sporocilo = "Neveljavna količina.";
        } elseif ($kolicina <= 0) {
            unset($_SESSION["kosarica"][$id]);
        } else {
            $stmt = $pdo->prepare(
                "SELECT zaloga FROM products WHERE id = ?"
            );
            $stmt->execute([$id]);
            $izdelek = $stmt->fetch();

            if (!$izdelek || (int)$izdelek["zaloga"] < 1) {
                unset($_SESSION["kosarica"][$id]);
            } else {
                $_SESSION["kosarica"][$id] = min(
                    $kolicina, (int)$izdelek["zaloga"]
                );
            }
        }
    } elseif ($akcija === "odstrani") {
        unset($_SESSION["kosarica"][$id]);
    }

    if ($sporocilo === "") {
        header("Location: kosarica.php");
        exit;
    }
}

$kosarica = $_SESSION["kosarica"];
$izdelki = [];
$skupaj = 0;

if (!empty($kosarica)) {
    $ids = array_keys($kosarica);
    $mesta = implode(",", array_fill(0, count($ids), "?"));

    $stmt = $pdo->prepare(
        "SELECT * FROM products WHERE id IN ($mesta)"
    );
    $stmt->execute($ids);
    $izdelki = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Košarica</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Moja košarica</h1>

    <?php if ($sporocilo): ?>
        <p class="napaka"><?= htmlspecialchars($sporocilo) ?></p>
    <?php endif; ?>

    <?php if (!$izdelki): ?>
        <p>Košarica je prazna.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Izdelek</th>
                    <th>Cena</th>
                    <th>Količina</th>
                    <th>Skupaj</th>
                    <th>Akcija</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($izdelki as $izdelek):
                $id = (int)$izdelek["id"];
                $kolicina = (int)($kosarica[$id] ?? 0);
                $cena = (float)$izdelek["cena"];
                $vrstica = $cena * $kolicina;
                $skupaj += $vrstica;
            ?>
                <tr>
                    <td><?= htmlspecialchars($izdelek["naziv"]) ?></td>
                    <td><?= number_format($cena, 2, ",", ".") ?> €</td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="csrf"
                                   value="<?= htmlspecialchars(csrfZeton()) ?>">
                            <input type="hidden" name="akcija" value="posodobi">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <input type="number" name="kolicina"
                                   min="0" max="<?= (int)$izdelek["zaloga"] ?>"
                                   value="<?= $kolicina ?>" required>
                            <button type="submit">Posodobi</button>
                        </form>
                    </td>
                    <td><?= number_format($vrstica, 2, ",", ".") ?> €</td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="csrf"
                                   value="<?= htmlspecialchars(csrfZeton()) ?>">
                            <input type="hidden" name="akcija" value="odstrani">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <button type="submit">Odstrani</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Skupaj: <?= number_format($skupaj, 2, ",", ".") ?> €</h2>
    <?php endif; ?>

    <a href="index.php">Nazaj v trgovino</a>
</div>
</body>
</html>