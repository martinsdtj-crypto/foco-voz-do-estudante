<?php
require '../apoio/sessao.php';
if (!isset($_SESSION['estudante_id'])) {
    header('Location: entrar.php');
    exit;
}
$titulo = 'A minha conta';
require '../apoio/topo.php';
?>
<section class="hero compacto">
    <div>
        <p class="etiqueta">CONTA DO ESTUDANTE</p>
        <h1>Olá, <?= htmlspecialchars($_SESSION['estudante_nome']) ?>.</h1>
        <p>Voz do Estudante</p>
    </div>
</section>
<div class="grelha tres">
    <section class="cartao experiencia">
        <h2>Enviar mensagem identificada</h2>
        <p>A mensagem fica associada à tua conta.</p>
        <a href="../voz/index.php?modo=identificada">Abrir →</a>
    </section>
    <section class="cartao experiencia">
        <h2>As minhas mensagens</h2>
        <p>Acompanha os teus envios identificados.</p>
        <a href="mensagens.php">Abrir →</a>
    </section>
    <section class="cartao experiencia">
        <h2>Enviar mensagem anónima</h2>
        <p>Guarda o código privado para consultar a resposta.</p>
        <a href="../voz/index.php?modo=anonima">Abrir →</a>
    </section>
</div>
<?php require '../apoio/fim.php'; ?>
