<?php
require '../apoio/formador.php';
/** @var mysqli $ligacao */
$ligacao = require '../apoio/ligacao.php';
$resultadoContagem = mysqli_query($ligacao, 'SELECT COUNT(*) FROM mensagens');
$contagem = mysqli_fetch_row($resultadoContagem);
$total = $contagem[0];
$titulo = 'Painel do funcionário';
require '../apoio/topo.php';
?>
<section class="hero compacto">
    <div>
        <p class="etiqueta">ÁREA DO FUNCIONÁRIO</p>
        <h1>Voz do Estudante</h1>
        <p>Gere os registos deste sistema.</p>
    </div>
    <div class="metricas"><div><b><?= (int) $total ?></b><span>mensagens</span></div></div>
</section>
<div class="grelha tres">
    <section class="cartao experiencia">
        <h2>Responder às mensagens</h2>
        <p>Consulta as mensagens anónimas e identificadas.</p>
        <a href="mensagens.php">Abrir →</a>
    </section>
    <section class="cartao experiencia">
        <h2>Actividades identificadas</h2>
        <p>Consulta as acções que o sistema regista.</p>
        <a href="actividades.php">Abrir →</a>
    </section>
    <section class="cartao experiencia">
        <h2>Limpar registos</h2>
        <p>Apaga mensagens e actividades mediante confirmação.</p>
        <a href="repor.php">Abrir →</a>
    </section>
</div>
<?php require '../apoio/fim.php'; ?>
