<?php
require '../apoio/sessao.php';
// Guardar explicitamente a ligação para usar nas consultas.
/** @var mysqli $ligacao */
$ligacao = require '../apoio/ligacao.php';
require '../apoio/actividade.php';
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: index.php');
    exit;
}

// 1. Receber apenas o texto e a escolha de envio. Não aceitar identidade enviada pelo formulário.
$tipo = '';
if (isset($_POST['tipo'])) {
    $tipo = $_POST['tipo'];
}
$tipo = trim($tipo);
$categoria = '';
if (isset($_POST['categoria'])) {
    $categoria = $_POST['categoria'];
}
$categoria = trim($categoria);
$mensagem = '';
if (isset($_POST['mensagem'])) {
    $mensagem = $_POST['mensagem'];
}
$mensagem = trim($mensagem);
$modo = '';
if (isset($_POST['modo'])) {
    $modo = $_POST['modo'];
}
$estudante_id = null;
$nome = '';
$contacto = '';

// 2. Identificar apenas quando há pedido explícito e conta autenticada.
if ($modo == 'identificada') {
    if (!isset($_SESSION['estudante_id'])) {
        http_response_code(403);
        die('Entra na conta de estudante antes de enviar uma mensagem identificada.');
    }
    $estudante_id = $_SESSION['estudante_id'];
    $nome = $_SESSION['estudante_nome'];
    $contacto = $_SESSION['estudante_email'];
} elseif ($modo != 'anonima') {
    http_response_code(400);
    die('Escolhe um modo de envio válido.');
}

// No modo anónimo, os três campos acima continuam vazios, mesmo com sessão iniciada.
if ($mensagem == '' || $categoria == '' || !in_array($tipo, ['Sugestão', 'Reclamação'])) {
    die('Preenche os campos obrigatórios.');
}
if (mb_strlen($mensagem) > 2000 || mb_strlen($categoria) > 80) {
    die('Texto demasiado longo.');
}

// 3. Criar um código privado e executar a gravação com valores separados do SQL.
$codigo = bin2hex(random_bytes(12));
$sql = 'INSERT INTO mensagens (codigo, tipo, categoria, mensagem, estudante_id, nome, contacto) VALUES (?, ?, ?, ?, ?, ?, ?)';
$pedido = mysqli_prepare($ligacao, $sql);
mysqli_stmt_bind_param($pedido, 'ssssiss', $codigo, $tipo, $categoria, $mensagem, $estudante_id, $nome, $contacto);
if (!mysqli_stmt_execute($pedido)) {
    die('Não foi possível guardar.');
}

// Nunca registar uma actividade individual para o envio anónimo.
if ($modo == 'identificada') {
    registar_actividade($ligacao, 'Enviou uma mensagem identificada');
}
$titulo = 'Mensagem recebida';
$tema = 'pagina-voz';
require '../apoio/topo.php';
?>
<section class="cartao confirmacao">
    <div class="icone grande">
        ✓
    </div>
    <p class="etiqueta">
        MENSAGEM RECEBIDA
    </p>
    <h1>
        A tua ideia já
        <br>
        tem um
        <em>
            percurso.
        </em>
    </h1>
    <p>
        <?php
        if ($modo == 'anonima') {
            echo 'Este envio não ficou associado a uma conta.';
        } else {
            echo 'A mensagem ficou associada à tua conta de estudante.';
        }
        ?>
    </p>
    <p class="nota">
        Guarda o código em privado para acompanhar a resposta.
    </p>
    <p class="codigo">
        <?= $codigo ?>
    </p>
    <a class="botao botao-principal" href="consultar.php?codigo=<?= $codigo ?>">
        Acompanhar resposta →
    </a>
    <p>
        <a href="../index.php">
            Voltar às experiências
        </a>
    </p>
</section>
<?php require '../apoio/fim.php'; ?>
