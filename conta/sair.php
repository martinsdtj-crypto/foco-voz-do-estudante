<?php
require '../apoio/sessao.php';
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: index.php');
    exit;
}
unset($_SESSION['estudante_id'], $_SESSION['estudante_nome'], $_SESSION['estudante_email']);
session_regenerate_id(true);
header('Location: ../index.php');
exit;
