<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/HistoricoStatus.php';

class HistoricoStatusController
{
    public function listar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verifica se há sessão válida
        if (!isset($_SESSION['usuario_id'])) {
            setFlashMessage("Sessão expirada. Faça login novamente.", "danger");
            header("Location: ?route=auth/login");
            exit;
        }

        $perfil = $_SESSION['cargo_id'] ?? 3;
        $usuario_id = $_SESSION['usuario_id'] ?? null;

        // ✅ Mantém acesso para ADMIN (1) e TÉCNICO (2)
        if (!in_array($perfil, [1, 2])) {
            setFlashMessage("Acesso negado. Área restrita a administradores e técnicos.", "danger");
            header("Location: ?route=auth/dashboard");
            exit;
        }

        $historico = new HistoricoStatus();

        $filtros = [
            'descricao'   => $_GET['q'] ?? '',
            'setor'       => $_GET['setor'] ?? '',
            'categoria'   => $_GET['categoria'] ?? '',
            'prioridade'  => $_GET['prioridade'] ?? '',
            'localizacao' => $_GET['localizacao'] ?? ''
        ];

        $chamados = $historico->listarConcluidos($usuario_id, $perfil, $filtros);

        $setores     = $historico->listarSetores();
        $tecnicos    = $historico->listarTecnicos();
        $categorias  = $historico->listarCategorias();
        $prioridades = $historico->listarPrioridades();

        if ($perfil == 2) {
            $localizacoes = $historico->listarLocalizacoes($usuario_id);
        }

        require __DIR__ . '/../Views/historicos/listar.php';
    }
}
