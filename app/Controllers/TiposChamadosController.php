<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/TipoChamado.php';
require_once __DIR__ . '/../helpers/session.php';

class TiposChamadosController
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
        $tipoChamado = new TipoChamado();
        $tipos = $tipoChamado->listarTodos();
        require __DIR__ . '/../Views/tipos_chamados/listar.php';
    }

    public function criar() {
        $this->verificarAdmin();
        require __DIR__ . '/../Views/tipos_chamados/criar.php';
    }

    public function salvar() {
        $this->verificarAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo = new TipoChamado();
            $tipo->nome = trim($_POST['nome'] ?? '');

            if (empty($tipo->nome)) {
                setFlashMessage("Nome obrigatório.", "warning");
                header('Location: ?route=tipos_chamados/criar');
                exit;
            }

            if ($tipo->criar()) {
                setFlashMessage("Tipo de chamado criado com sucesso.", "success");
                header('Location: ?route=tipos_chamados/listar');
                exit;
            } else {
                setFlashMessage("Erro ao salvar tipo de chamado.", "danger");
                header('Location: ?route=tipos_chamados/criar');
                exit;
            }
        }
    }

    public function editar() {
        $this->verificarAdmin();
        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage("ID inválido.", "warning");
            header('Location: ?route=tipos_chamados/listar');
            exit;
        }

        $tipo = new TipoChamado();
        $dados = $tipo->buscarPorId($id);

        if (!$dados) {
            setFlashMessage("Tipo de chamado não encontrado.", "warning");
            header('Location: ?route=tipos_chamados/listar');
            exit;
        }

        require __DIR__ . '/../Views/tipos_chamados/editar.php';
    }

    public function atualizar() {
        $this->verificarAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo = new TipoChamado();
            $tipo->id = $_POST['id'] ?? null;
            $tipo->nome = trim($_POST['nome'] ?? '');

            if (empty($tipo->id) || empty($tipo->nome)) {
                setFlashMessage("Dados inválidos.", "warning");
                header('Location: ?route=tipos_chamados/listar');
                exit;
            }

            if ($tipo->atualizar()) {
                setFlashMessage("Tipo de chamado atualizado.", "success");
                header("Location: ?route=tipos_chamados/listar");
                exit;
            } else {
                setFlashMessage("Erro ao atualizar tipo de chamado.", "danger");
                header("Location: ?route=tipos_chamados/editar&id={$tipo->id}");
                exit;
            }
        }
    }

    public function excluir() {
        $this->verificarAdmin();
        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage("ID inválido.", "warning");
            header('Location: ?route=tipos_chamados/listar');
            exit;
        }

        $tipo = new TipoChamado();
        if ($tipo->excluir($id)) {
            setFlashMessage("Tipo de chamado excluído.", "success");
        } else {
            setFlashMessage("Erro ao excluir tipo de chamado. Verifique dependências.", "danger");
        }

        header("Location: ?route=tipos_chamados/listar");
        exit;
    }
}
