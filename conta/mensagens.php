<?php
require '../apoio/sessao.php';
// Guardar explicitamente a ligação para usar nas consultas.
/** @var mysqli $ligacao */
$ligacao = require '../apoio/ligacao.php';
if (!isset($_SESSION['estudante_id'])) {
    header('Location: entrar.php');
    exit;
}
// Uma conta só lista as mensagens identificadas que ela enviou.
$id = $_SESSION['estudante_id'];
$pedido = mysqli_prepare($ligacao, 'SELECT codigo, tipo, categoria, mensagem, estado, resposta FROM mensagens WHERE estudante_id = ? ORDER BY id DESC');
mysqli_stmt_bind_param($pedido, 'i', $id);
mysqli_stmt_execute($pedido);
$resultado = mysqli_stmt_get_result($pedido);
$titulo = 'As minhas mensagens';
$tema = 'pagina-voz';
require '../apoio/topo.php';
?>
<p class="etiqueta">
    ACOMPANHAMENTO IDENTIFICADO
</p>
<h1>
    As tuas ideias têm um percurso.
</h1>
<p>
    Esta lista inclui apenas os envios associados à tua conta. Envios anónimos exigem o código privado.
</p>
<?php if (mysqli_num_rows($resultado) == 0): ?>
<section class="cartao vazio">
    <h2>
        Ainda não enviaste uma mensagem identificada.
    </h2>
    <a class="botao botao-principal" href="../voz/index.php?modo=identificada">
        Partilhar uma ideia
    </a>
</section>
<?php endif; ?>
<?php while ($mensagem = mysqli_fetch_assoc($resultado)): ?>
<section class="cartao">
    <span class="estado <?= classe_estado($mensagem['estado']) ?>">
        <?= htmlspecialchars($mensagem['estado']) ?>
    </span>
    <h2>
        <?= htmlspecialchars($mensagem['tipo']) ?> · <?= htmlspecialchars($mensagem['categoria']) ?>
    </h2>
    <p class="mensagem">
        <?= htmlspecialchars($mensagem['mensagem']) ?>
    </p>
    <div class="resposta">
        <b>
            Resposta da equipa
        </b>
        <p class="mensagem">
            <?= htmlspecialchars($mensagem['resposta'] ?: 'A mensagem ainda aguarda resposta.') ?>
        </p>
    </div>
</section>
<?php endwhile; ?>
<?php require '../apoio/fim.php'; ?>
