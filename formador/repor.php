<?php
require '../apoio/formador.php';
/** @var mysqli $ligacao */
$ligacao = require '../apoio/ligacao.php';
$erro = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $confirmacao = '';
    if (isset($_POST['confirmacao'])) {
        $confirmacao = $_POST['confirmacao'];
    }
    if ($confirmacao == 'REPOR') {
        mysqli_begin_transaction($ligacao);
        $ok = mysqli_query($ligacao, 'DELETE FROM mensagens');
        $ok = $ok && mysqli_query($ligacao, 'DELETE FROM actividades');
        if ($ok) {
            mysqli_commit($ligacao);
            header('Location: index.php');
            exit;
        }
        mysqli_rollback($ligacao);
        $erro = 'Não foi possível limpar os registos.';
    } else {
        $erro = 'Escreve REPOR para confirmar.';
    }
}
$titulo = 'Limpar registos';
require '../apoio/topo.php';
?>
<section class="cartao estreito">
    <h1>Limpar registos</h1>
    <p>Apaga os registos de mensagens e as actividades deste sistema. As contas e senhas mantêm-se.</p>
    <p class="erro" role="alert"><?= htmlspecialchars($erro) ?></p>
    <form method="post">
        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
        <label>Escreve REPOR para confirmar<input name="confirmacao" required></label>
        <button class="botao botao-perigo">Limpar registos</button>
        <a class="botao botao-secundario" href="index.php">Cancelar</a>
    </form>
</section>
<?php require '../apoio/fim.php'; ?>
