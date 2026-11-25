<?php
require '../apoio/formador.php';
// Guardar explicitamente a ligação para usar nas consultas.
/** @var mysqli $ligacao */
$ligacao = require '../apoio/ligacao.php';
$resultado = mysqli_query($ligacao, 'SELECT * FROM mensagens ORDER BY id DESC');
$titulo = 'Mensagens recebidas';
$tema = 'pagina-voz';
require '../apoio/topo.php';
?>
<p class="etiqueta">
    ESCUTAR E RESPONDER
</p>
<h1>
    Cada mensagem
    <br>
    merece
    <em>
        atenção.
    </em>
</h1>
<p class="nota">
    Consulta as mensagens e acompanha as respostas. Uma mensagem anónima não tem autor registado na aplicação.
</p>
<?php if (mysqli_num_rows($resultado) == 0): ?>
<section class="cartao vazio">
    <h2>
        Ainda não há mensagens.
    </h2>
    <p>
        As mensagens enviadas pelos estudantes aparecerão aqui.
    </p>
</section>
<?php endif; ?>
<?php while ($mensagem = mysqli_fetch_assoc($resultado)): ?>
<section class="cartao mensagem-cartao">
    <div class="titulo-seccao">
        <span class="estado <?= classe_estado($mensagem['estado']) ?>">
            <?= htmlspecialchars($mensagem['estado']) ?>
        </span>
        <span class="nota">
            <?php
            if ($mensagem['estudante_id'] === null) {
                echo 'Envio anónimo';
            } else {
                echo 'Envio identificado';
            }
            ?>
        </span>
    </div>
    <h2>
        <?= htmlspecialchars($mensagem['tipo']) ?> · <?= htmlspecialchars($mensagem['categoria']) ?>
    </h2>
    <p class="mensagem">
        <?= htmlspecialchars($mensagem['mensagem']) ?>
    </p>
    <?php if ($mensagem['estudante_id'] !== null): ?>
    <p class="nota">
        Conta: <?= htmlspecialchars($mensagem['nome']) ?> · <?= htmlspecialchars($mensagem['contacto']) ?>
    </p>
    <?php else: ?>
    <p class="nota">
        Sem nome, email ou ligação a uma conta.
    </p>
    <?php endif; ?>
    <details>
        <summary>
            Código privado de acompanhamento
        </summary>
        <code><?= htmlspecialchars($mensagem['codigo']) ?></code>
    </details>
    <form action="responder.php" method="post">
        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
        <input type="hidden" name="id" value="<?= (int) $mensagem['id'] ?>">
        <label>
            Estado
            <select name="estado">
                <option <?php
                if ($mensagem['estado'] == 'Recebida') {
                    echo 'selected';
                }
                ?>>
                    Recebida
                </option>
                <option <?php
                if ($mensagem['estado'] == 'Em análise') {
                    echo 'selected';
                }
                ?>>
                    Em análise
                </option>
                <option <?php
                if ($mensagem['estado'] == 'Respondida') {
                    echo 'selected';
                }
                ?>>
                    Respondida
                </option>
            </select>
        </label>
        <label>
            Resposta<textarea name="resposta" maxlength="2000" placeholder="Escreve a resposta ao estudante..."><?php
            if (isset($mensagem['resposta'])) {
                echo htmlspecialchars($mensagem['resposta']);
            }
            ?></textarea>
        </label>
        <button class="botao botao-principal">
            Guardar acompanhamento →
        </button>
    </form>
</section>
<?php endwhile; ?>
<?php require '../apoio/fim.php'; ?>
