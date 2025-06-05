<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/RelatorioGerado.php';

class RelatoriosController
{
    // Listar todos os relatórios gerados
    public function listar()
    {
        $relatorio = new RelatorioGerado();
        $relatorios = $relatorio->listarTodos();

        require __DIR__ . '/../Views/relatorios/listar.php';
    }

    // Exibir formulário para gerar relatório
    public function criar()
    {
        require __DIR__ . '/../Views/relatorios/criar.php';
    }

    // Salvar novo relatório gerado
    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $relatorio = new RelatorioGerado();
            $relatorio->titulo = $_POST['titulo'] ?? '';
            $relatorio->descricao = $_POST['descricao'] ?? '';
            $relatorio->data_geracao = $_POST['data_geracao'] ?? date('Y-m-d');

            if ($relatorio->criar()) {
                header('Location: ?route=relatorios/listar');
                exit;
            } else {
                echo "Erro ao salvar relatório.";
            }
        } else {
            echo "Requisição inválida.";
        }
    }

    // Exibir formulário para edição
    public function editar()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $relatorio = new RelatorioGerado();
            $dados = $relatorio->buscarPorId($id);

            if ($dados) {
                require __DIR__ . '/../Views/relatorios/editar.php';
            } else {
                echo "Relatório não encontrado.";
            }
        }
    }

    // Atualizar relatório
    public function atualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $relatorio = new RelatorioGerado();
            $relatorio->id = $_POST['id'];
            $relatorio->titulo = $_POST['titulo'];
            $relatorio->descricao = $_POST['descricao'];
            $relatorio->data_geracao = $_POST['data_geracao'];

            if ($relatorio->atualizar()) {
                header('Location: ?route=relatorios/listar');
                exit;
            } else {
                echo "Erro ao atualizar relatório.";
            }
        }
    }

    // Excluir relatório
    public function excluir()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $relatorio = new RelatorioGerado();
            if ($relatorio->excluir($id)) {
                header('Location: ?route=relatorios/listar');
                exit;
            } else {
                echo "Erro ao excluir relatório.";
            }
        }
    }
}
