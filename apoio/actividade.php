<?php
// Usada apenas em acções identificadas. Nunca em mensagens anónimas.
function registar_actividade($ligacao, $acao) {
    $nome = 'Funcionário';
    $email = '';
    $perfil = 'Funcionário';

    if (isset($_SESSION['estudante_id'])) {
        $nome = $_SESSION['estudante_nome'];
        $email = $_SESSION['estudante_email'];
        $perfil = 'Estudante';
    }

    $pedido = mysqli_prepare($ligacao, 'INSERT INTO actividades (nome, email, perfil, acao) VALUES (?, ?, ?, ?)');
    mysqli_stmt_bind_param($pedido, 'ssss', $nome, $email, $perfil, $acao);
    mysqli_stmt_execute($pedido);
}
