<?php
require '../apoio/sessao.php';
$modo = '';
if (isset($_GET['modo'])) {
    $modo = $_GET['modo'];
}
$erro = '';

// O modo identificado exige conta. O modo anónimo não exige login.
if ($modo == 'identificada' && !isset($_SESSION['estudante_id'])) {
    header('Location: ../conta/entrar.php?destino=voz');
    exit;
}
$titulo = 'Voz do Estudante';
$tema = 'pagina-voz';
require '../apoio/topo.php';
?>
<div class="pagina-titulo">
    <div>
        <p class="etiqueta">
            02 / DAR VOZ
        </p>
        <h1>
            Uma ideia merece
            <br>
            ser
            <em>
                ouvida.
            </em>
        </h1>
        <p>
            Escolhe como participar. Acompanha o percurso da tua mensagem.
        </p>
    </div>
    <div class="ilustracao-voz" aria-hidden="true">
        <span>
            “
        </span>
        <p>
            A tua ideia
            <br>
            pode fazer a diferença.
        </p>
    </div>
</div>
<?php if ($modo != 'anonima' && $modo != 'identificada'): ?>
<div class="grelha duas">
    <section class="cartao escolha">
        <div class="icone">
            ↗
        </div>
        <span class="estado">
            COM LOGIN
        </span>
        <h2>
            Quero identificar-me.
        </h2>
        <p>
            A mensagem fica associada à tua conta. O funcionário saberá quem a enviou.
        </p>
        <a class="botao botao-principal" href="index.php?modo=identificada">
            Enviar com identificação →
        </a>
    </section>
    <section class="cartao escolha voz-cor">
        <div class="icone">
            ◎
        </div>
        <span class="estado">
            SEM LOGIN
        </span>
        <h2>
            Prefiro enviar anónima.
        </h2>
        <p>
            Sem nome, sem email e sem ligação à conta. Guardas um código para consultar a resposta.
        </p>
        <a class="botao botao-secundario" href="index.php?modo=anonima">
            Continuar sem identificação →
        </a>
    </section>
</div>
<?php else: ?>
<div class="grelha formulario-layout">
    <section class="cartao formulario">
        <div class="titulo-seccao">
            <h2>
                O que gostarias de partilhar?
            </h2>
            <span class="estado">
                <?php
                if ($modo == 'anonima') {
                    echo 'Anónima';
                } else {
                    echo 'Identificada';
                }
                ?>
            </span>
        </div>
        <?php if ($modo == 'identificada'): ?>
        <div class="identidade">
            <b>
                <?= htmlspecialchars($_SESSION['estudante_nome']) ?>
            </b>
            <span>
                <?= htmlspecialchars($_SESSION['estudante_email']) ?>
            </span>
        </div>
        <?php else: ?>
        <p class="nota">
            Não pedimos identificação. Mesmo se já tiveres sessão iniciada, este envio não será associado à conta.
        </p>
        <?php endif; ?>
        <!-- O formulário envia os dados para o PHP que os guarda. -->
        <form action="guardar.php" method="post">
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
            <input type="hidden" name="modo" value="<?= htmlspecialchars($modo) ?>">
            <div class="grelha duas">
                <label>
                    Tipo de mensagem
                    <select name="tipo">
                        <option>
                            Sugestão
                        </option>
                        <option>
                            Reclamação
                        </option>
                    </select>
                </label>
                <label>
                    Categoria
                    <select name="categoria">
                        <option>
                            Laboratório
                        </option>
                        <option>
                            Instalações
                        </option>
                        <option>
                            Actividades escolares
                        </option>
                        <option>
                            Outros
                        </option>
                    </select>
                </label>
            </div>
            <label>
                Mensagem<textarea name="mensagem" required maxlength="2000" placeholder="Escreve a tua sugestão ou reclamação..."></textarea>
            </label>
            <p class="nota">
                Explica a situação com clareza e respeito. Inclui apenas os dados necessários.
            </p>
            <button class="botao botao-principal largo">
                Enviar mensagem →
            </button>
        </form>
        <p>
            <a href="index.php">
                Mudar o modo de envio
            </a>
        </p>
    </section>
    <aside class="cartao percurso">
        <p class="etiqueta">
            O QUE ACONTECE DEPOIS?
        </p>
        <h2>
            Uma mensagem.
            <br>
            Um percurso.
        </h2>
        <div class="passo">
            <b>
                01
            </b>
            <span>
                Envias a mensagem.
            </span>
        </div>
        <div class="passo">
            <b>
                02
            </b>
            <span>
                Guardas o código privado.
            </span>
        </div>
        <div class="passo">
            <b>
                03
            </b>
            <span>
                A equipa analisa e responde.
            </span>
        </div>
        <div class="passo">
            <b>
                04
            </b>
            <span>
                Consultas o resultado.
            </span>
        </div>
    </aside>
</div>
<?php endif; ?>
<section class="faixa">
    <p>
        Já enviaste uma mensagem?
    </p>
    <a href="consultar.php">
        Consultar pelo código →
    </a>
</section>
<p class="nota">
    O texto pode revelar a identidade. O código é privado: quem o tiver poderá consultar uma mensagem anónima. Anonimato da aplicação não significa ausência de registos técnicos no servidor.
</p>
<?php require '../apoio/fim.php'; ?>
