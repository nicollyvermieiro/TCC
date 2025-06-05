<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Usuario.php';

class AuthController
{
    public function loginForm()
    {
        include __DIR__ . '/../Views/auth/login.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            $usuarioModel = new Usuario();
            $stmt = $usuarioModel->listarTodos();

            session_start();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['email'] === $email && password_verify($senha, $row['senha'])) {
                    $_SESSION['usuario_id'] = $row['id'];
                    $_SESSION['cargo_id'] = $row['cargo_id'];
                    header("Location: ?route=usuarios/listar");
                    exit;
                }
            }

            echo "Email ou senha inválidos.";
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: ?route=auth/loginForm");
        exit;
    }
}
