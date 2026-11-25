<?php

require '../apoio/formador.php';

// Guardar explicitamente a ligação para usar nas consultas.

/** @var mysqli $ligacao */

$ligacao = require '../apoio/ligacao.php';

$resultado = mysqli_query($ligacao, 'SELECT * FROM actividades ORDER BY id DESC LIMIT 100');

$titulo = 'Interacções identificadas';

require '../apoio/topo.php';

?>

<p class="etiqueta">

    CONTAS E PARTICIPAÇÃO

</p>

<h1>

    Quem participou

    <br>

    nas acções

    <em>

        identificadas?

    </em>

</h1>

<p>

    Últimas 100 actividades registadas: entrada de estudante e envio de mensagem identificada.

</p>

<p class="nota">

    Este painel não regista envios ou consultas de mensagens anónimas. Não é um registo de tudo o que acontece no computador.

</p>

<section class="cartao tabela-scroll">

    <table>

        <thead>

            <tr>

                <th>

                    Conta

                </th>

                <th>

                    Perfil

                </th>

                <th>

                    Acção

                </th>

                <th>

                    Quando

                </th>

            </tr>

        </thead>

        <tbody>

            <?php while ($actividade = mysqli_fetch_assoc($resultado)): ?>
            <?php
            // Compatibilidade com actividades guardadas pela versão anterior.
            if ($actividade['perfil'] == 'Formador') {
                $actividade['perfil'] = 'Funcionário';
                if ($actividade['nome'] == 'Formador') {
                    $actividade['nome'] = 'Funcionário';
                }
            }
            ?>

            <tr>

                <td>

                    <b>

                        <?= htmlspecialchars($actividade['nome']) ?>

                    </b>

                    <br>

                    <small>

                        <?= htmlspecialchars($actividade['email']) ?>

                    </small>

                </td>

                <td>

                    <?= htmlspecialchars($actividade['perfil']) ?>

                </td>

                <td>

                    <?= htmlspecialchars($actividade['acao']) ?>

                </td>

                <td>

                    <?= htmlspecialchars($actividade['criado_em']) ?>

                </td>

            </tr>

            <?php endwhile; ?>

        </tbody>

    </table>

</section>

<?php require '../apoio/fim.php'; ?>

