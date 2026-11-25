<?php
require '../apoio/formador.php';
// Guardar explicitamente a ligação para usar nas consultas.
/** @var mysqli $ligacao */
$ligacao = require '../apoio/ligacao.php';
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: mensagens.php');
    exit;
}
$id = 0;
if (isset($_POST['id'])) {
    $id = $_POST['id'];
}
$id = (int) $id;
$resposta = '';
if (isset($_POST['resposta'])) {
    $resposta = $_POST['resposta'];
}
$resposta = trim($resposta);
$estado = '';
if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];
}
if (!in_array($estado, ['Recebida', 'Em análise', 'Respondida'])) {
    die('Estado inválido.');
}
if ($estado == 'Respondida' && $resposta == '') {
    die('Escreve a resposta antes de concluir.');
}
if (mb_strlen($resposta) > 2000) {
    die('Resposta demasiado longa.');
}

$sql = 'UPDATE mensagens SET resposta = ?, estado = ? WHERE id = ?';
$pedido = mysqli_prepare($ligacao, $sql);
mysqli_stmt_bind_param($pedido, 'ssi', $resposta, $estado, $id);
if (!mysqli_stmt_execute($pedido)) {
    die('Não foi possível guardar.');
}
header('Location: mensagens.php');
exit;
