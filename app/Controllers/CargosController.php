<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Cargo.php';

class CargosController
{
    public function listar()
    {
        $cargo = new Cargo();
        $cargos = $cargo->listarTodos();
        require __DIR__ . '/../Views/cargos/listar.php';
    }

    public function criar()
    {
        require __DIR__ . '/../Views/cargos/criar.php';
    }

    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cargo = new Cargo();
            $cargo->nome = $_POST['nome'] ?? '';

            if ($cargo->criar()) {
                header('Location: ?route=cargos/listar');
                exit;
            } else {
                echo "Erro ao salvar cargo.";
            }
        }
    }

    public function editar()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $cargo = new Cargo();
            $dados = $cargo->buscarPorId($id);

            if ($dados) {
                require __DIR__ . '/../Views/cargos/editar.php';
            } else {
                echo "Cargo não encontrado.";
            }
        }
    }

    public function atualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cargo = new Cargo();
            $cargo->id = $_POST['id'];
            $cargo->nome = $_POST['nome'];

            if ($cargo->atualizar()) {
                header('Location: ?route=cargos/listar');
                exit;
            } else {
                echo "Erro ao atualizar.";
            }
        }
    }

    public function excluir()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $cargo = new Cargo();
            if ($cargo->excluir($id)) {
                header('Location: ?route=cargos/listar');
                exit;
            } else {
                echo "Erro ao excluir.";
            }
        }
    }
}
