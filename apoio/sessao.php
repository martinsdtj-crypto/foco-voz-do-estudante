<?php
// Apoio ao acesso do funcionário e à protecção dos formulários.
// Um nome próprio impede partilhar o login com outras versões no mesmo XAMPP.
session_name('FOCOVOZ');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Strict');
session_start();

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(16));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = '';
    if (isset($_POST['token'])) {
        $token = $_POST['token'];
    }
    if (!is_string($token) || !hash_equals($_SESSION['token'], $token)) {
        http_response_code(403);
        die('Actualize a página e tente novamente.');
    }
}
