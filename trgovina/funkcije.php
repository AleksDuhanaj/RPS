<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function jePrijavljen(): bool {
    return isset($_SESSION['user_id']);
}

function zahtevajPrijavo(): void {
    if (!jePrijavljen()) {
        header("Location: /trgovina/prijava.php");
        exit;
    }
}

function zahtevajAdministratorja(): void {
    zahtevajPrijavo();

    if (($_SESSION['vloga'] ?? '') !== 'administrator') {
        http_response_code(403);
        exit("Dostop zavrnjen!");
    }
}

function csrfZeton(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf'];
}

function preveriCsrf(): void {
    $zeton = $_POST['csrf'] ?? '';

    if (!is_string($zeton) ||
        !hash_equals(csrfZeton(), $zeton)) {
        http_response_code(403);
        exit("Neveljavna zahteva.");
    }
}
?>