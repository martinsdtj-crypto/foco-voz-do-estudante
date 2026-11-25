<?php
require __DIR__ . '/sessao.php';

// Só permite continuar depois de entrar como formador.
if (!isset($_SESSION['formador'])) {
    header('Location: ../formador/entrar.php');
    exit;
}
