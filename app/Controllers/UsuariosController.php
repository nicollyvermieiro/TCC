<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Usuario.php';

class UsuariosController
{
    // Listar todos os usuários com o nome do cargo
    public function listar()
    {
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
        require __DIR__ . '/../Views/usuarios/criar.php';
    }

    // Salvar novo usuário
    public function salvar()
    {
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
                echo "Erro ao salvar usuário.";
            }
        } else {
            echo "Requisição inválida.";
        }
    }

    // (Opcional) Editar usuário
    public function editar() 
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $usuario = new Usuario();
            $dados = $usuario->buscarPorId($id);

            if ($dados) {
                require __DIR__ . '/../Views/usuarios/editar.php';
            } else {
                echo "Usuário não encontrado.";
            }
        }
    }


    // (Opcional) Atualizar usuário
    public function atualizar()
     {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario();
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
            $usuario = new Usuario();
            if ($usuario->excluir($id)) {
                header("Location: ?route=usuarios/listar");
                exit;
            } else {
                echo "Erro ao excluir.";
            }
        }
    }

}
