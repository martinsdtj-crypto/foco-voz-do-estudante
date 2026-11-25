<?php
require '../apoio/sessao.php';
// Guardar explicitamente a ligação para usar nas consultas.
/** @var mysqli $ligacao */
$ligacao = require '../apoio/ligacao.php';
$codigo = '';
if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
}
$codigo = trim($codigo);
$mensagem = false;
$restrita = false;
if ($codigo != '') {
    $sql = 'SELECT mensagem, estado, resposta, estudante_id FROM mensagens WHERE codigo = ?';
    $pedido = mysqli_prepare($ligacao, $sql);
    mysqli_stmt_bind_param($pedido, 's', $codigo);
    mysqli_stmt_execute($pedido);
    $resultadoPedido = mysqli_stmt_get_result($pedido);
    $mensagem = mysqli_fetch_assoc($resultadoPedido);

    // Uma mensagem identificada só é visível à conta que enviou ou ao formador.
    if ($mensagem && $mensagem['estudante_id'] !== null) {
        $dono = 0;
        if (isset($_SESSION['estudante_id'])) {
            $dono = $_SESSION['estudante_id'];
        }
        if ($dono != $mensagem['estudante_id'] && !isset($_SESSION['formador'])) {
            $mensagem = false;
            $restrita = true;
            http_response_code(403);
        }
    }
}
$titulo = 'Acompanhar a resposta';
$tema = 'pagina-voz';
require '../apoio/topo.php';
?>
<p class="etiqueta">
    ACOMPANHAR
</p>
<h1>
    O que aconteceu
    <br>
    à tua
    <em>
        mensagem?
    </em>
</h1>
<div class="grelha formulario-layout">
    <section class="cartao formulario">
        <form method="get">
            <label>
                Código privado
                <input name="codigo" required maxlength="100" value="<?= htmlspecialchars($codigo) ?>" placeholder="Introduz o código completo">
            </label>
            <button class="botao botao-secundario">
                Consultar →
            </button>
        </form>
        <?php if ($mensagem): ?>
        <hr>
        <span class="estado <?= classe_estado($mensagem['estado']) ?>">
            <?= htmlspecialchars($mensagem['estado']) ?>
        </span>
        <h2>
            A tua mensagem
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
        <?php elseif ($restrita): ?>
        <p class="erro">
            Esta mensagem é identificada. Entra na conta que a enviou e consulta As minhas mensagens.
        </p>
        <a href="../conta/entrar.php">
            Entrar como estudante →
        </a>
        <?php elseif ($codigo != ''): ?>
        <p class="erro">
            Código não encontrado. Confirma o código completo.
        </p>
        <?php endif; ?>
    </section>
    <aside class="cartao percurso">
        <h2>
            Guarda a chave
            <br>
            da conversa.
        </h2>
        <p>
            No envio anónimo, o código permite consultar a resposta sem conta.
        </p>
        <p class="nota">
            Não partilhes o código. A equipa responde pelo sistema, sem precisar de um email.
        </p>
    </aside>
</div>
<?php require '../apoio/fim.php'; ?>
