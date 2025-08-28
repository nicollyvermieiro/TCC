<?php
require_once __DIR__ . '/../helpers/session.php';

class HomeController {

    public function index() {
        // Se o usuário estiver logado, podemos redirecionar para o dashboard
        if (isset($_SESSION['usuario_id'])) {
            header("Location: ?route=auth/dashboard");
            exit;
        }

        // Se não estiver logado, exibe a tela inicial/index.php
        require __DIR__ . '/../Views/index.php';
    }
}
