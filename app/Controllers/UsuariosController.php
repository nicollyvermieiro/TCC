<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Usuario.php';

class UsuariosController {

    public function listar() {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT u.id, u.nome, u.email, c.nome as cargo 
              FROM usuario u
              JOIN cargo c ON u.cargo_id = c.id";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    require __DIR__ . '/../Views/usuarios/listar.php';
}


    public function criar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario();
            $usuario->nome = $_POST['nome'] ?? '';
            $usuario->email = $_POST['email'] ?? '';
            $usuario->senha = $_POST['senha'] ?? '';
            $usuario->cargo_id = $_POST['cargo_id'] ?? null;

            if ($usuario->criar()) {
                header('Location: ?route=usuarios/listar');
                exit;
            } else {
                $erro = "Erro ao criar usuário.";
            }
        }

        // Se não POST ou erro, exibe formulário de criação
        require __DIR__ . '/../Views/usuarios/criar.php';
    }
}
