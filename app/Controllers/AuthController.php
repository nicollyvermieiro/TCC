<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Usuarios.php';

class AuthController
{
    public function loginForm()
    {
        include __DIR__ . '/../Views/auth/login.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $usuarioModel = new Usuarios();
            $usuarioEncontrado = $usuarioModel->buscarPorEmail($email);

            if ($usuarioEncontrado && password_verify($senha, $usuarioEncontrado['senha'])) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['usuario_id'] = $usuarioEncontrado['id'];
                $_SESSION['cargo_id'] = $usuarioEncontrado['cargo_id'];
                $_SESSION['usuario_nome'] = $usuarioEncontrado['nome'];  
                
                header("Location: ?route=auth/dashboard");
                exit;
            } else {
                setFlashMessage("Email ou senha inválidos.", "danger");
                header("Location: ?route=auth/loginForm");
                exit;
            }
        } else {
            // Requisição inválida, redirecionar para o loginForm
            header("Location: ?route=auth/loginForm");
            exit;
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: ?route=auth/loginForm");
        exit;
    }

    public function dashboard()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_id'])) {
            header("Location: ?route=auth/loginForm");
            exit;
        }

        require __DIR__ . '/../Views/dashboard/home.php';
    }
}
