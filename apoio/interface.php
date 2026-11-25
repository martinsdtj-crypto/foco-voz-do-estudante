<?php
// As cores representam estados. O texto continua sempre visível.
function classe_estado($estado) {
    if ($estado == 'Respondida' || $estado == 'Devolvido') {
        return 'estado-sucesso';
    }
    if ($estado == 'Em análise' || $estado == 'Encontrado') {
        return 'estado-informacao';
    }
    return 'estado-neutro';
}

// Escolher uma ilustração conhecida, sem aceitar um caminho enviado pelo utilizador.
function imagem_objecto($titulo, $categoria) {
    $texto = mb_strtolower($titulo);
    // strpos encontra a palavra; false significa que não a encontrou.
    if (strpos($texto, 'estojo') !== false) {
        return 'estojo.svg';
    }
    if (strpos($texto, 'garrafa') !== false) {
        return 'garrafa.svg';
    }
    if (strpos($texto, 'caderno') !== false || strpos($texto, 'livro') !== false) {
        return 'caderno.svg';
    }
    if (strpos($texto, 'mochila') !== false) {
        return 'mochila.svg';
    }
    if ($categoria == 'Electrónica') {
        return 'telefone.svg';
    }
    return 'objecto.svg';
}
