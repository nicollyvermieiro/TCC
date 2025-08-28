<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Setor.php';
require_once __DIR__ . '/../helpers/session.php'; // Importa funções de flash

class SetoresController
{
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    // Verifica se usuário é admin
    private function verificarAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['usuario_id']) || ($_SESSION['cargo_id'] ?? null) != 1) {
            setFlashMessage("Acesso negado. Apenas administradores podem acessar.", "danger");
            header("Location: ?route=auth/dashboard");
            exit;
        }
    }

    // Listar todos os setores
    public function listar() {
        $this->verificarAdmin();

        $setorModel = new Setor($this->conn);
        $setores = $setorModel->listarTodos();

        require __DIR__ . '/../Views/setores/listar.php';
    }

    // Exibir formulário de criação
    public function criar() {
        $this->verificarAdmin();
        require __DIR__ . '/../Views/setores/criar.php';
    }

    // Salvar novo setor
    public function salvar() {
        $this->verificarAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $setor = new Setor($this->conn);
            $setor->nome = trim($_POST['nome'] ?? '');

            if (empty($setor->nome)) {
                setFlashMessage("Nome do setor é obrigatório.", "warning");
                header("Location: ?route=setores/criar");
                exit;
            }

            if ($setor->criar()) {
                setFlashMessage("Setor criado com sucesso.", "success");
                header("Location: ?route=setores/listar");
                exit;
            } else {
                setFlashMessage("Erro ao salvar setor.", "danger");
                header("Location: ?route=setores/criar");
                exit;
            }
        } else {
            setFlashMessage("Requisição inválida.", "warning");
            header("Location: ?route=setores/listar");
            exit;
        }
    }

    // Editar setor
    public function editar() {
        $this->verificarAdmin();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            setFlashMessage("ID do setor não fornecido.", "warning");
            header("Location: ?route=setores/listar");
            exit;
        }

        $setor = new Setor($this->conn);
        $dados = $setor->buscarPorId($id);

        if ($dados) {
            require __DIR__ . '/../Views/setores/editar.php';
        } else {
            setFlashMessage("Setor não encontrado.", "warning");
            header("Location: ?route=setores/listar");
            exit;
        }
    }

    // Atualizar setor
    public function atualizar() {
        $this->verificarAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $setor = new Setor($this->conn);
            $setor->id = $_POST['id'] ?? null;
            $setor->nome = trim($_POST['nome'] ?? '');

            if (empty($setor->id) || empty($setor->nome)) {
                setFlashMessage("Dados inválidos.", "warning");
                header("Location: ?route=setores/listar");
                exit;
            }

            if ($setor->atualizar()) {
                setFlashMessage("Setor atualizado com sucesso.", "success");
                header("Location: ?route=setores/listar");
                exit;
            } else {
                setFlashMessage("Erro ao atualizar setor.", "danger");
                header("Location: ?route=setores/editar&id={$setor->id}");
                exit;
            }
        } else {
            setFlashMessage("Requisição inválida.", "warning");
            header("Location: ?route=setores/listar");
            exit;
        }
    }

    // Excluir setor
    public function excluir() {
        $this->verificarAdmin();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            setFlashMessage("ID do setor não fornecido.", "warning");
            header("Location: ?route=setores/listar");
            exit;
        }

        $setor = new Setor($this->conn);
        if ($setor->excluir($id)) {
            setFlashMessage("Setor excluído com sucesso.", "success");
            header("Location: ?route=setores/listar");
            exit;
        } else {
            setFlashMessage("Erro ao excluir setor. Verifique dependências.", "danger");
            header("Location: ?route=setores/listar");
            exit;
        }
    }
}
