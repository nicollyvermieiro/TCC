<?php
require_once __DIR__ . '/../Models/Chamado.php';

class ChamadosController
{
    // Listar todos os chamados com dados relacionados (tipo, setor, usuário)
    public function listar()
    {
        $chamadoModel = new Chamado();
        $chamados = $chamadoModel->listarTodos();
        require __DIR__ . '/../Views/chamados/listar.php';
    }

    // Exibir formulário para criar chamado
    public function criar()
    {
        require __DIR__ . '/../Views/chamados/criar.php';
    }

    // Salvar novo chamado
    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chamado = new Chamado();
            $chamado->descricao = $_POST['descricao'] ?? '';
            $chamado->prioridade = $_POST['prioridade'] ?? '';
            $chamado->tipo_chamado_id = $_POST['tipo_chamado_id'] ?? null;
            $chamado->usuario_id = $_SESSION['usuario_id'] ?? null;  // Usuário logado
            $chamado->setor_id = $_POST['setor_id'] ?? null;
            $chamado->status = 'Aberto';

            if ($chamado->criar()) {
                header('Location: ?route=chamados/listar');
                exit;
            } else {
                echo "Erro ao salvar chamado.";
            }
        } else {
            echo "Requisição inválida.";
        }
    }

    // Exibir formulário para editar chamado
    public function editar()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $chamado = new Chamado();
            $dados = $chamado->buscarPorId($id);

            if ($dados) {
                require __DIR__ . '/../Views/chamados/editar.php';
            } else {
                echo "Chamado não encontrado.";
            }
        }
    }

    // Atualizar chamado
    public function atualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chamado = new Chamado();
            $chamado->id = $_POST['id'];
            $chamado->descricao = $_POST['descricao'];
            $chamado->prioridade = $_POST['prioridade'];
            $chamado->tipo_chamado_id = $_POST['tipo_chamado_id'];
            $chamado->setor_id = $_POST['setor_id'];
            $chamado->status = $_POST['status'];

            if ($chamado->atualizar()) {
                header('Location: ?route=chamados/listar');
                exit;
            } else {
                echo "Erro ao atualizar chamado.";
            }
        }
    }

    // Excluir chamado
    public function excluir()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $chamado = new Chamado();
            if ($chamado->excluir($id)) {
                header('Location: ?route=chamados/listar');
                exit;
            } else {
                echo "Erro ao excluir chamado.";
            }
        }
    }
}
