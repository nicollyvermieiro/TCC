<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cargo_id = $_SESSION['cargo_id'] ?? null;
$usuario_nome = $_SESSION['usuario_nome'] ?? '';

function isAdmin() {
    global $cargo_id;
    return $cargo_id == 1;
}

function isLogged() {
    return isset($_SESSION['usuario_id']);
}
?>


<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="?">ManutSmart</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if (isLogged()): ?>
            <!-- Dashboard -->
            <li class="nav-item">
              <a class="nav-link" href="?route=auth/dashboard">Dashboard</a>
            </li>

            <?php if (isAdmin()): ?>
                <!-- Usuários -->
                <li class="nav-item">
                  <a class="nav-link" href="?route=usuarios/listar">Usuários</a>
                </li>

                <!-- Chamados -->
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="chamadosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Chamados
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="chamadosDropdown">
                    <li><a class="dropdown-item" href="?route=chamados/listar">📋 Listar Chamados</a></li>
                    <li><a class="dropdown-item" href="?route=chamados/criar">➕ Criar Chamado</a></li>
                  </ul>
                </li>

                <!-- Gerenciar Chamados -->
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="gerenciarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Gerenciar Chamados
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="gerenciarDropdown">
                    <li><a class="dropdown-item" href="?route=setores/listar">🏢 Gerenciar Setores</a></li>
                    <li><a class="dropdown-item" href="?route=tipochamado/listar">📂 Gerenciar Categorias</a></li>
                    <li><a class="dropdown-item" href="?route=prioridades/listar">⚡ Gerenciar Prioridades</a></li>
                  </ul>
                </li>
            <?php endif; ?>
        <?php endif; ?>
      </ul>

      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php if (isLogged()): ?>
          <!-- Usuário logado -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Olá, <?= htmlspecialchars($usuario_nome) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="?route=auth/logout">Sair</a></li>
            </ul>
          </li>
        <?php else: ?>
          <!-- Login -->
          <li class="nav-item">
            <a class="nav-link" href="?route=auth/loginForm">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
