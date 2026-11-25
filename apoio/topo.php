<?php
require_once __DIR__ . '/sessao.php';
require_once __DIR__ . '/interface.php';
// $raiz indica como voltar à pasta principal a partir desta página.
if (!isset($raiz)) {
    $raiz = '../';
}
if (!isset($tema)) {
    $tema = '';
}
// O nome da pasta identifica a área actual, sem depender do endereço de instalação.
$area = basename(dirname($_SERVER['SCRIPT_NAME']));
$ficheiro = basename($_SERVER['SCRIPT_NAME']);
$inicio = $raiz == '';

?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
            <?= htmlspecialchars($titulo) ?> · Instituto Foco
        </title>
        <link rel="stylesheet" href="<?= $raiz ?>estilo.css">
    </head>
    <body class="<?= htmlspecialchars($tema) ?>">
        <header class="cabecalho">
            <a class="marca" href="<?= $raiz ?>index.php">
                <span class="logo-moldura">
                    <img src="<?= $raiz ?>assets/logo.png" alt="" width="70" height="57">
                </span>
                <span class="marca-texto">
                    <b>
                        Instituto Foco
                    </b>
                    <small>
                        Educação responsável
                    </small>
                </span>
            </a>
            <div class="acessos-conta">
                <?php if (isset($_SESSION['estudante_id'])): ?>
                <a class="nav-conta" href="<?= $raiz ?>conta/index.php">A minha conta</a>
                <form action="<?= $raiz ?>conta/sair.php" method="post">
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                    <button class="sair-menu">Sair</button>
                </form>
                <?php elseif (isset($_SESSION['formador'])): ?>
                <a class="nav-conta" href="<?= $raiz ?>formador/index.php">Painel do funcionário</a>
                <form action="<?= $raiz ?>formador/sair.php" method="post">
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                    <button class="sair-menu">Sair</button>
                </form>
                <?php else: ?>
                <a class="nav-conta" href="<?= $raiz ?>conta/entrar.php">Entrar</a>
                <a class="nav-conta" href="<?= $raiz ?>conta/criar.php">Criar conta</a>
                <?php endif; ?>
                <?php if (!isset($_SESSION['formador'])): ?>
                <a href="<?= $raiz ?>formador/entrar.php">Acesso do funcionário</a>
                <?php endif; ?>
            </div>
        </header>
        <nav class="menu-principal" aria-label="Menu principal">
            <a href="<?= $raiz ?>voz/index.php">Enviar mensagem</a>
            <a href="<?= $raiz ?>voz/consultar.php">Consultar pelo código</a>
        </nav>
        <main id="conteudo">
            <nav class="migalhas" aria-label="Localização">
                <a href="<?= $raiz ?>index.php">Voz do Estudante</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page"><?= htmlspecialchars($titulo) ?></span>
            </nav>
            <?php if ($area == 'formador' && isset($_SESSION['formador'])): ?>
            <nav class="menu-area" aria-label="Áreas do funcionário">
                <a href="index.php" <?php
                if ($ficheiro == 'index.php') {
                    echo 'aria-current="page"';
                }
                ?>>Painel</a>
                <a href="mensagens.php" <?php
                if ($ficheiro == 'mensagens.php') {
                    echo 'aria-current="page"';
                }
                ?>>Mensagens</a>
                <a href="actividades.php" <?php
                if ($ficheiro == 'actividades.php') {
                    echo 'aria-current="page"';
                }
                ?>>Actividades</a>
            </nav>
            <?php endif; ?>
