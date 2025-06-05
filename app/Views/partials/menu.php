<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav>
    <a href="?route=usuarios/listar">Usuários</a> |
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <a href="?route=auth/logout">Sair</a>
    <?php else: ?>
        <a href="?route=auth/loginForm">Login</a>
    <?php endif; ?>
</nav>
<hr>
