<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/ManutencaoChamado.php';

class ManutencoesController
{
    // Listar todas as manutenções com info do técnico e do chamado
    public function listar()
    {
        $manutencao = new ManutencaoChamado();
        $manutencoes = $manutencao->listarTodos();

        require __DIR__ . '/../Views/manutencoes/listar.php';
    }

    // Exibir formulário de criação de manutenção
    public function criar()
    {
        require __DIR__ . '/../Views/manutencoes/criar.php';
    }

    // Salvar nova manutenção
    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $manutencao = new ManutencaoChamado();
            $manutencao->descricao = $_POST['descricao'] ?? '';
            $manutencao->data_manutencao = $_POST['data_manutencao'] ?? '';
            $manutencao->tecnico_id = $_POST['tecnico_id'] ?? null;
            $manutencao->chamado_id = $_POST['chamado_id'] ?? null;

            if ($manutencao->criar()) {
                header('Location: ?route=manutencoes/listar');
                exit;
            } else {
                echo "Erro ao salvar manutenção.";
            }
        } else {
            echo "Requisição inválida.";
        }
    }

    // Exibir formulário de edição
    public function editar()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $manutencao = new ManutencaoChamado();
            $dados = $manutencao->buscarPorId($id);

            if ($dados) {
                require __DIR__ . '/../Views/manutencoes/editar.php';
            } else {
                echo "Manutenção não encontrada.";
            }
        }
    }

    // Atualizar manutenção
    public function atualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $manutencao = new ManutencaoChamado();
            $manutencao->id = $_POST['id'];
            $manutencao->descricao = $_POST['descricao'];
            $manutencao->data_manutencao = $_POST['data_manutencao'];
            $manutencao->tecnico_id = $_POST['tecnico_id'];
            $manutencao->chamado_id = $_POST['chamado_id'];

            if ($manutencao->atualizar()) {
                header('Location: ?route=manutencoes/listar');
                exit;
            } else {
                echo "Erro ao atualizar manutenção.";
            }
        }
    }

    // Excluir manutenção
    public function excluir()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $manutencao = new ManutencaoChamado();
            if ($manutencao->excluir($id)) {
                header('Location: ?route=manutencoes/listar');
                exit;
            } else {
                echo "Erro ao excluir manutenção.";
            }
        }
    }
}
