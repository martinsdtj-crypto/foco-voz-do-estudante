<?php
require '../apoio/sessao.php';
// Guardar explicitamente a ligação para usar nas consultas.
/** @var mysqli $ligacao */
$ligacao = require '../apoio/ligacao.php';
require '../apoio/actividade.php';
$erro = '';
$destino = '';
if (isset($_GET['destino'])) {
    $destino = $_GET['destino'];
} elseif (isset($_POST['destino'])) {
    $destino = $_POST['destino'];
}
$email = '';

// Procurar a conta pelo email e comparar a senha com o hash guardado.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = '';
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    }
    $email = trim($email);
    $senha = '';
    if (isset($_POST['senha'])) {
        $senha = $_POST['senha'];
    }
    $pedido = mysqli_prepare($ligacao, 'SELECT * FROM estudantes WHERE email = ?');
    mysqli_stmt_bind_param($pedido, 's', $email);
    mysqli_stmt_execute($pedido);
    $resultadoPedido = mysqli_stmt_get_result($pedido);
    $estudante = mysqli_fetch_assoc($resultadoPedido);

    $tentar_depois = 0;
    if (isset($_SESSION['tentar_depois'])) {
        $tentar_depois = $_SESSION['tentar_depois'];
    }
    if ($tentar_depois > time()) {
        $erro = 'Aguarda alguns segundos antes de tentar novamente.';
    } elseif ($estudante && password_verify($senha, $estudante['senha'])) {
        session_regenerate_id(true);
        unset($_SESSION['formador']);
        $_SESSION['estudante_id'] = $estudante['id'];
        $_SESSION['estudante_nome'] = $estudante['nome'];
        $_SESSION['estudante_email'] = $estudante['email'];
        registar_actividade($ligacao, 'Entrou na conta de estudante');

        // Destinos fixos evitam redireccionamento para sites externos.
        if ($destino == 'voz') {
            header('Location: ../voz/index.php?modo=identificada');
        }
        else {
            header('Location: index.php');
        }
        exit;
    } else {
        $_SESSION['tentar_depois'] = time() + 2;
        $erro = 'Email ou senha incorrectos.';
    }
}
$titulo = 'Entrar como estudante';
$tema = 'pagina-conta';
require '../apoio/topo.php';
?>
<div class="acesso-layout">
    <section class="acesso-convite">
        <p class="etiqueta">
            O TEU ESPAÇO NA EXPERIÊNCIA
        </p>
        <h1>
            Uma conta.
            <br>
            Ideias com
            <em>
                assinatura.
            </em>
        </h1>
        <p>
            Envia mensagens identificadas e acompanha as respostas.
        </p>
        <div class="passo">
            <b>
                01
            </b>
            <span>
                Entra com o teu email.
            </span>
        </div>
        <div class="passo">
            <b>
                02
            </b>
            <span>
                Escolhe onde queres participar.
            </span>
        </div>
        <div class="passo">
            <b>
                03
            </b>
            <span>
                Vê o percurso da tua contribuição.
            </span>
        </div>
    </section>
    <section class="cartao formulario">
        <p class="etiqueta">
            ACESSO DO ESTUDANTE
        </p>
        <h2>
            Bem-vindo de volta.
        </h2>
        <p class="nota">
            Entra com o email e a senha da tua conta.
        </p>
        <?php if (isset($_GET['criada'])): ?>
        <p class="sucesso">
            Conta criada. Já podes entrar.
        </p>
        <?php endif; ?>
        <p class="erro" role="alert">
            <?= htmlspecialchars($erro) ?>
        </p>
        <form method="post">
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
            <input type="hidden" name="destino" value="<?php
            if (is_string($destino)) {
                echo htmlspecialchars($destino);
            }
            ?>">
            <label>
                Email
                <input type="email" name="email" required maxlength="160" autocomplete="username" value="<?= htmlspecialchars($email) ?>" placeholder="O teu email">
            </label>
            <label>
                Senha
                <input type="password" name="senha" required maxlength="128" autocomplete="current-password">
            </label>
            <button class="botao botao-principal largo">
                Entrar →
            </button>
        </form>
        <p>
            Ainda não tens conta?
            <a href="criar.php">
                Criar conta
            </a>
        </p>
        <p>
            És funcionário?
            <a href="../formador/entrar.php">Entrar como funcionário →</a>
        </p>
        <p class="nota">
            Para uma mensagem anónima,
            <a href="../voz/index.php?modo=anonima">
                continua sem login
            </a>
            .
        </p>
    </section>
</div>
<?php require '../apoio/fim.php'; ?>
