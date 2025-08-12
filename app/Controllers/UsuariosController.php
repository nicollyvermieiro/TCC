<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Usuarios.php';

class UsuariosController
{
    public function existeAdministrador() {
        $database = new Database();
        $conn = $database->getConnection();

        $query = "SELECT COUNT(*) FROM usuario WHERE cargo_id = 1";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return $count > 0;
    }


    public function listar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_id'])) {
            header("Location: ?route=auth/loginForm");
            exit;
        }

        if ($_SESSION['cargo_id'] != 1) {
            echo "Acesso negado. Você não tem permissão para acessar esta página.";
            exit;
        }


        $database = new Database();
        $conn = $database->getConnection();

        $query = "SELECT u.id, u.nome, u.email, c.nome AS cargo 
                  FROM usuario u
                  JOIN cargo c ON u.cargo_id = c.id";

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../Views/usuarios/listar.php';
    }

    // Exibir formulário de criação de usuário
   public function criar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verifica se já existe administrador cadastrado
        if (!$this->existeAdministrador()) {
            // Exibe o formulário para cadastrar o primeiro admin, SEM exigir login
            require __DIR__ . '/../Views/usuarios/criar_primeiro_admin.php';
            exit; // para não continuar o fluxo normal
        }

        // A partir daqui, exige login
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: ?route=auth/loginForm");
            exit;
        }

        // Exige também que só admin possa criar usuário
        if ($_SESSION['cargo_id'] != 1) {
            echo "Acesso negado. Você não tem permissão para acessar esta página.";
            exit;
        }

        $database = new Database();
        $conn = $database->getConnection();

        // Buscar cargos
        $query = "SELECT id, nome FROM cargo ORDER BY nome ASC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $cargos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../Views/usuarios/criar.php';
    }

    public function salvarPrimeiroAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuarios();
            $usuario->nome = $_POST['nome'] ?? '';
            $usuario->email = $_POST['email'] ?? '';
            $senhaDigitada = $_POST['senha'] ?? '';
            $usuario->senha = password_hash($senhaDigitada, PASSWORD_DEFAULT);
            $usuario->cargo_id = 1; // Forçando admin

            if ($usuario->criar()) {
                header('Location: ?route=auth/loginForm');
                exit;
            } else {
                echo "Erro ao salvar o administrador.";
            }
        } else {
            echo "Requisição inválida.";
        }
    }


    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuarios();
            $usuario->nome = $_POST['nome'] ?? '';
            $usuario->email = $_POST['email'] ?? '';
            $senhaDigitada = $_POST['senha'] ?? '';
            $usuario->senha = password_hash($senhaDigitada, PASSWORD_DEFAULT);
            $usuario->cargo_id = $_POST['cargo_id'] ?? null;

            if ($usuario->criar()) {
                header('Location: ?route=usuarios/listar');
                exit;
            } else {
                echo "Erro ao salvar usuário.";
            }
        } else {
            echo "Requisição inválida.";
        }
    }

    public function editar() 
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo "ID do usuário não fornecido.";
            return;
        }

        $database = new Database();
        $conn = $database->getConnection();

        // Busca dados do usuário
        $usuarioModel = new Usuarios();
        $dados = $usuarioModel->buscarPorId($id);

        if (!$dados) {
            echo "Usuário não encontrado.";
            return;
        }

        // Busca cargos
        $query = "SELECT id, nome FROM cargo ORDER BY nome ASC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $cargos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../Views/usuarios/editar.php';
    }

    public function atualizar()
     {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuarios();
            $usuario->id = $_POST['id'];
            $usuario->nome = $_POST['nome'];
            $usuario->email = $_POST['email'];
            $usuario->cargo_id = $_POST['cargo_id'];

            if (!empty($_POST['senha'])) {
                $usuario->senha = $_POST['senha'];
            }

            if ($usuario->atualizar()) {
                header("Location: ?route=usuarios/listar");
                exit;
            } else {
                echo "Erro ao atualizar.";
            }
        }
    }


    // (Opcional) Excluir usuário
    public function excluir()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $usuario = new Usuarios();
            if ($usuario->excluir($id)) {
                header("Location: ?route=usuarios/listar");
                exit;
            } else {
                echo "Erro ao excluir.";
            }
        }
    }

}
