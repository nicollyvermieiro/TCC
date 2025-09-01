<?php
require_once __DIR__ . '/../Models/Chamado.php';
require_once __DIR__ . '/../Models/TipoChamado.php';
require_once __DIR__ . '/../Models/Setor.php';
require_once __DIR__ . '/../Models/Prioridade.php';

class ChamadosController
{
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    // Listar todos os chamados
    public function listar()
    {
        $chamadoModel = new Chamado($this->db);
        $chamados = $chamadoModel->listarTodos();
        require __DIR__ . '/../Views/chamados/listar.php';
    }

    // Exibir formulário para criar chamado
    public function criar()
    {
        $tipoModel = new TipoChamado($this->db);
        $setorModel = new Setor($this->db);
        $prioridadeModel = new Prioridade($this->db);

        $tipos = $tipoModel->listarTodos();
        $setores = $setorModel->listarTodos();
        $prioridades = $prioridadeModel->listarTodos();

        require __DIR__ . '/../Views/chamados/criar.php';
    }

    // Salvar novo chamado
    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chamado = new Chamado($this->db);
            $chamado->descricao     = $_POST['descricao'] ?? '';
            $chamado->prioridade_id = $_POST['prioridade_id'] ?? null;
            $chamado->tipo_id       = $_POST['tipo_id'] ?? null;
            $chamado->usuario_id    = $_SESSION['usuario_id'] ?? null;
            $chamado->setor_id      = $_POST['setor_id'] ?? null;
            $chamado->status        = 'Aberto';

            if ($chamado->criar()) {
                setFlashMessage("Chamado criado com sucesso.", "success");
                header('Location: ?route=chamados/listar');
                exit;
            } else {
                setFlashMessage("Erro ao criar chamado.", "danger");
                header('Location: ?route=chamados/criar');
                exit;
            }
        }
    }

    // Exibir formulário para editar chamado
    public function editar()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            setFlashMessage("Chamado não encontrado.", "warning");
            header("Location: ?route=chamados/listar");
            exit;
        }

        $chamadoModel = new Chamado($this->db);
        $dados = $chamadoModel->buscarPorId($id);

        if (!$dados) {
            setFlashMessage("Chamado não encontrado.", "warning");
            header("Location: ?route=chamados/listar");
            exit;
        }

        // Buscar opções para selects
        $tipoModel = new TipoChamado($this->db);
        $setorModel = new Setor($this->db);
        $prioridadeModel = new Prioridade($this->db);

        $tipos = $tipoModel->listarTodos();
        $setores = $setorModel->listarTodos();
        $prioridades = $prioridadeModel->listarTodos();

        require __DIR__ . '/../Views/chamados/editar.php';
    }

    // Atualizar chamado
    public function atualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chamado = new Chamado($this->db);
            $chamado->id            = $_POST['id'];
            $chamado->descricao     = $_POST['descricao'];
            $chamado->tipo_id       = $_POST['tipo_id'];
            $chamado->setor_id      = $_POST['setor_id'];
            $chamado->prioridade_id = $_POST['prioridade_id'];
            $chamado->status        = $_POST['status'];

            if ($chamado->atualizar()) {
                setFlashMessage("Chamado atualizado com sucesso.", "success");
                header('Location: ?route=chamados/listar');
                exit;
            } else {
                setFlashMessage("Erro ao atualizar chamado.", "danger");
                header("Location: ?route=chamados/editar&id={$chamado->id}");
                exit;
            }
        }
    }

    // Excluir chamado
    public function excluir()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $chamado = new Chamado($this->db);
            if ($chamado->excluir($id)) {
                setFlashMessage("Chamado excluído com sucesso.", "success");
                header('Location: ?route=chamados/listar');
                exit;
            } else {
                setFlashMessage("Erro ao excluir chamado.", "danger");
                header('Location: ?route=chamados/listar');
                exit;
            }
        }
    }

    // Tela intermediária de Gerenciar Chamados
    public function gerenciar()
    {
        // Verifica se é admin
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['usuario_id']) || ($_SESSION['cargo_id'] ?? null) != 1) {
            setFlashMessage("Acesso negado. Área restrita a administradores.", "danger");
            header("Location: ?route=auth/dashboard");
            exit;
        }

        require __DIR__ . '/../Views/chamados/gerenciar.php';
    }

}
