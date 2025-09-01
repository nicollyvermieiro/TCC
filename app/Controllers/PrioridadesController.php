<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Prioridade.php';
require_once __DIR__ . '/../helpers/session.php';

class PrioridadesController
{
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    private function verificarAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['usuario_id']) || ($_SESSION['cargo_id'] ?? null) != 1) {
            setFlashMessage("Acesso negado. Área restrita a administradores.", "danger");
            header("Location: ?route=auth/dashboard");
            exit;
        }
    }

    public function listar() {
        $this->verificarAdmin();
        $prioridadeModel = new Prioridade($this->conn);
        $prioridades = $prioridadeModel->listarTodos();
        require __DIR__ . '/../Views/prioridades/listar.php';
    }

    public function criar() {
        $this->verificarAdmin();
        require __DIR__ . '/../Views/prioridades/criar.php';
    }

    public function salvar() {
        $this->verificarAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $prioridade = new Prioridade($this->conn);
            $prioridade->nome = trim($_POST['nome'] ?? '');
            $prioridade->nivel = intval($_POST['nivel'] ?? 1);

            if (empty($prioridade->nome)) {
                setFlashMessage("Nome obrigatório.", "warning");
                header('Location: ?route=prioridades/criar');
                exit;
            }

            if ($prioridade->criar()) {
                setFlashMessage("Prioridade criada com sucesso.", "success");
                header('Location: ?route=prioridades/listar');
                exit;
            } else {
                setFlashMessage("Erro ao salvar prioridade.", "danger");
                header('Location: ?route=prioridades/criar');
                exit;
            }
        }
    }

    public function editar() {
        $this->verificarAdmin();
        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage("ID inválido.", "warning");
            header('Location: ?route=prioridades/listar');
            exit;
        }

        $prioridadeModel = new Prioridade($this->conn);
        $dados = $prioridadeModel->buscarPorId($id);

        if (!$dados) {
            setFlashMessage("Prioridade não encontrada.", "warning");
            header('Location: ?route=prioridades/listar');
            exit;
        }

        require __DIR__ . '/../Views/prioridades/editar.php';
    }

    public function atualizar() {
        $this->verificarAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $prioridade = new Prioridade($this->conn);
            $prioridade->id = $_POST['id'] ?? null;
            $prioridade->nome = trim($_POST['nome'] ?? '');
            $prioridade->nivel = intval($_POST['nivel'] ?? 1);

            if (empty($prioridade->id) || empty($prioridade->nome)) {
                setFlashMessage("Dados inválidos.", "warning");
                header('Location: ?route=prioridades/listar');
                exit;
            }

            if ($prioridade->atualizar()) {
                setFlashMessage("Prioridade atualizada.", "success");
                header("Location: ?route=prioridades/listar");
                exit;
            } else {
                setFlashMessage("Erro ao atualizar prioridade.", "danger");
                header("Location: ?route=prioridades/editar&id={$prioridade->id}");
                exit;
            }
        }
    }

    public function excluir() {
        $this->verificarAdmin();
        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage("ID inválido.", "warning");
            header('Location: ?route=prioridades/listar');
            exit;
        }

        $prioridadeModel = new Prioridade($this->conn);
        if ($prioridadeModel->excluir($id)) {
            setFlashMessage("Prioridade excluída com sucesso.", "success");
        } else {
            setFlashMessage("Erro ao excluir prioridade. Verifique dependências.", "danger");
        }

        header("Location: ?route=prioridades/listar");
        exit;
    }
}
