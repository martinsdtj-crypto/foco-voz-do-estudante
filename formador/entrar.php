<?php
require '../apoio/sessao.php';
// Guardar explicitamente a ligação para usar nas consultas.
/** @var mysqli $ligacao */
$ligacao = require '../apoio/ligacao.php';
$resultado = mysqli_query($ligacao, 'SELECT senha FROM formador WHERE id = 1');
$formador = mysqli_fetch_assoc($resultado);
$erro = '';

// A primeira utilização cria o acesso; as seguintes verificam a senha.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senha = '';
    if (isset($_POST['senha'])) {
        $senha = $_POST['senha'];
    }
    if (!$formador) {
        $confirmacao = '';
        if (isset($_POST['confirmacao'])) {
            $confirmacao = $_POST['confirmacao'];
        }
        if (strlen($senha) < 8 || $senha != $confirmacao) {
            $erro = 'Usa pelo menos 8 caracteres e confirma a mesma senha.';
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $pedido = mysqli_prepare($ligacao, 'INSERT IGNORE INTO formador (id, senha) VALUES (1, ?)');
            mysqli_stmt_bind_param($pedido, 's', $hash);
            mysqli_stmt_execute($pedido);
            if (mysqli_stmt_affected_rows($pedido) == 1) {
                session_regenerate_id(true);
                unset($_SESSION['estudante_id'], $_SESSION['estudante_nome'], $_SESSION['estudante_email']);
                $_SESSION['formador'] = true;
                header('Location: index.php'); exit;
            }
            $erro = 'O acesso já foi criado. Actualiza a página.';
        }
    } elseif (password_verify($senha, $formador['senha'])) {
        session_regenerate_id(true);
        unset($_SESSION['estudante_id'], $_SESSION['estudante_nome'], $_SESSION['estudante_email']);
        $_SESSION['formador'] = true;
        header('Location: index.php'); exit;
    } else {
        $erro = 'Senha incorrecta.';
    }
}
$titulo = 'Acesso do funcionário';
$tema = 'pagina-conta';
require '../apoio/topo.php';
?>
<div class="acesso-layout">
    <section class="acesso-convite">
        <p class="etiqueta">
            DO OUTRO LADO DA APLICAÇÃO
        </p>
        <h1>
            Escutar.
            <br>
            Organizar.
            <br>
            <em>
                Responder.
            </em>
        </h1>
        <p>
            Acede ao painel para gerir Voz do Estudante.
        </p>
        <p class="nota">
            Na primeira utilização, define a senha do formador. Nas seguintes, entra com essa senha.
        </p>
    </section>
    <section class="cartao formulario">
        <p class="etiqueta">
            PAINEL RESERVADO
        </p>
        <h2>
            <?php
            if ($formador) {
                echo 'Entrar como funcionário';
            } else {
                echo 'Criar acesso do funcionário';
            }
            ?>
        </h2>
        <p class="erro" role="alert">
            <?= htmlspecialchars($erro) ?>
        </p>
        <form method="post">
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
            <label>
                Senha
                <input type="password" name="senha" required minlength="8" maxlength="128" autocomplete="<?php
                if ($formador) {
                    echo 'current-password';
                } else {
                    echo 'new-password';
                }
                ?>">
            </label>
            <?php if (!$formador): ?>
            <label>
                Confirmar senha
                <input type="password" name="confirmacao" required minlength="8" autocomplete="new-password">
            </label>
            <?php endif; ?>
            <button class="botao botao-principal largo">
                Continuar →
            </button>
        </form>
        <p class="nota">
            <a href="../conta/entrar.php">
                Sou estudante →
            </a>
        </p>
    </section>
</div>
<?php require '../apoio/fim.php'; ?>
