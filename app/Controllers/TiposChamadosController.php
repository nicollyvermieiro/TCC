<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/TipoChamado.php';

class TiposChamadosController
{
    public function listar()
    {
        $tipoChamado = new TipoChamado();
        $tipos = $tipoChamado->listarTodos();

        require __DIR__ . '/../Views/tipos_chamados/listar.php';
    }

    public function criar()
    {
        require __DIR__ . '/../Views/tipos_chamados/criar.php';
    }

    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo = new TipoChamado();
            $tipo->nome = $_POST['nome'] ?? '';

            if ($tipo->criar()) {
                header('Location: ?route=tipos_chamados/listar');
                exit;
            } else {
                echo "Erro ao salvar tipo de chamado.";
            }
        }
    }

    public function editar()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $tipo = new TipoChamado();
            $dados = $tipo->buscarPorId($id);

            if ($dados) {
                require __DIR__ . '/../Views/tipos_chamados/editar.php';
            } else {
                echo "Tipo de chamado não encontrado.";
            }
        }
    }

    public function atualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo = new TipoChamado();
            $tipo->id = $_POST['id'];
            $tipo->nome = $_POST['nome'];

            if ($tipo->atualizar()) {
                header("Location: ?route=tipos_chamados/listar");
                exit;
            } else {
                echo "Erro ao atualizar tipo de chamado.";
            }
        }
    }

    public function excluir()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $tipo = new TipoChamado();
            if ($tipo->excluir($id)) {
                header("Location: ?route=tipos_chamados/listar");
                exit;
            } else {
                echo "Erro ao excluir tipo de chamado.";
            }
        }
    }
}
