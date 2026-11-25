<?php
require '../apoio/sessao.php';
// Guardar explicitamente a ligação para usar nas consultas.
/** @var mysqli $ligacao */
$ligacao = require '../apoio/ligacao.php';
$erro = '';
$nome = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = '';
    if (isset($_POST['nome'])) {
        $nome = $_POST['nome'];
    }
    $nome = trim($nome);
    $email = '';
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    }
    $email = trim($email);
    $senha = '';
    if (isset($_POST['senha'])) {
        $senha = $_POST['senha'];
    }
    $confirmacao = '';
    if (isset($_POST['confirmacao'])) {
        $confirmacao = $_POST['confirmacao'];
    }

    if (!$nome || mb_strlen($nome) > 120 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 160) {
        $erro = 'Preenche um nome e um email válidos.';
    } elseif (strlen($senha) < 8 || strlen($senha) > 128 || $senha !== $confirmacao) {
        $erro = 'Usa pelo menos 8 caracteres e confirma a mesma senha.';
    } else {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $pedido = mysqli_prepare($ligacao, 'INSERT INTO estudantes (nome, email, senha) VALUES (?, ?, ?)');
        mysqli_stmt_bind_param($pedido, 'sss', $nome, $email, $hash);
        if (mysqli_stmt_execute($pedido)) {
            header('Location: entrar.php?criada=1');
            exit;
        }
        $erro = 'Não foi possível criar a conta. Esse email pode já estar registado.';
    }
}
$titulo = 'Criar conta de estudante';
$tema = 'pagina-conta';
require '../apoio/topo.php';
?>
<div class="acesso-layout">
    <section class="acesso-convite">
        <p class="etiqueta">
            PREPARAR A PARTICIPAÇÃO
        </p>
        <h1>
            Começa por
            <br>
            uma
            <em>
                conta tua.
            </em>
        </h1>
        <p>
            Este registo identifica as mensagens que enviares com assinatura.
        </p>
        <p class="nota">
            Cria a tua conta para enviar mensagens identificadas e acompanhar as respostas.
        </p>
    </section>
    <section class="cartao formulario">
        <h2>
            Criar conta
        </h2>
        <p class="erro" role="alert">
            <?= htmlspecialchars($erro) ?>
        </p>
        <form method="post">
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
            <label>
                Nome
                <input name="nome" required maxlength="120" value="<?= htmlspecialchars($nome) ?>">
            </label>
            <label>
                Email
                <input type="email" name="email" required maxlength="160" value="<?= htmlspecialchars($email) ?>" autocomplete="username">
            </label>
            <label>
                Senha
                <input type="password" name="senha" required minlength="8" maxlength="128" autocomplete="new-password">
            </label>
            <label>
                Confirmar senha
                <input type="password" name="confirmacao" required minlength="8" autocomplete="new-password">
            </label>
            <button class="botao botao-principal largo">
                Criar conta →
            </button>
        </form>
        <p>
            <a href="entrar.php">
                Já tenho conta
            </a>
        </p>
    </section>
</div>
<?php require '../apoio/fim.php'; ?>
