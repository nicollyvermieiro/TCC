<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Setor.php';

class SetoresController
{
    // Listar todos os setores
    public function listar()
    {
        $setorModel = new Setor();
        $setores = $setorModel->listarTodos();

        require __DIR__ . '/../Views/setores/listar.php';
    }

    // Exibir formulário de criação
    public function criar()
    {
        require __DIR__ . '/../Views/setores/criar.php';
    }

    // Salvar novo setor
    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $setor = new Setor();
            $setor->nome = $_POST['nome'] ?? '';

            if ($setor->criar()) {
                header('Location: ?route=setores/listar');
                exit;
            } else {
                echo "Erro ao salvar setor.";
            }
        } else {
            echo "Requisição inválida.";
        }
    }

    // Editar setor
    public function editar()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $setor = new Setor();
            $dados = $setor->buscarPorId($id);

            if ($dados) {
                require __DIR__ . '/../Views/setores/editar.php';
            } else {
                echo "Setor não encontrado.";
            }
        }
    }

    // Atualizar setor
    public function atualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $setor = new Setor();
            $setor->id = $_POST['id'];
            $setor->nome = $_POST['nome'];

            if ($setor->atualizar()) {
                header("Location: ?route=setores/listar");
                exit;
            } else {
                echo "Erro ao atualizar setor.";
            }
        }
    }

    // Excluir setor
    public function excluir()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $setor = new Setor();
            if ($setor->excluir($id)) {
                header("Location: ?route=setores/listar");
                exit;
            } else {
                echo "Erro ao excluir setor.";
            }
        }
    }
}
