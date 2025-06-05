<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/HistoricoStatus.php';

class HistoricoStatusController
{
    // Listar todos os históricos
    public function listar()
    {
        $historico = new HistoricoStatus();
        $historicos = $historico->listarTodos();

        require __DIR__ . '/../Views/historicos/listar.php';
    }

    // Registrar novo histórico (normalmente chamado via POST ao alterar status)
    public function registrar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chamado_id = $_POST['chamado_id'] ?? null;
            $status = $_POST['status'] ?? null;
            $observacao = $_POST['observacao'] ?? null;

            if ($chamado_id && $status) {
                $historico = new HistoricoStatus();
                if ($historico->registrar($chamado_id, $status, $observacao)) {
                    header('Location: ?route=historico/listar');
                    exit;
                } else {
                    echo "Erro ao registrar histórico.";
                }
            } else {
                echo "Parâmetros insuficientes.";
            }
        } else {
            echo "Requisição inválida.";
        }
    }
}
