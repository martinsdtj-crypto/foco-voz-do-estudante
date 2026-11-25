            <!-- Cada perfil volta ao seu painel, mesmo a partir do quiz. -->
            <?php if (isset($_SESSION['estudante_id'])): ?>
            <div class="retorno-dashboard">
                <a class="botao botao-secundario" href="<?= $raiz ?>conta/index.php">← Voltar à dashboard</a>
            </div>
            <?php elseif (isset($_SESSION['formador'])): ?>
            <div class="retorno-dashboard">
                <a class="botao botao-secundario" href="<?= $raiz ?>formador/index.php">← Voltar à dashboard</a>
            </div>
            <?php endif; ?>
    </main>
    <footer class="rodape">
        <span>Uma ideia tua pode tornar-se uma aplicação.</span>
        <span>Instituto Foco · Educação responsável</span>
    </footer>
</body>
</html>
