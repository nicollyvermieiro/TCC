<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/HistoricoStatus.php';

class HistoricoStatusController
{
    public function listar() {
        $historico = new HistoricoStatus();

        $perfil = $_SESSION['cargo_id'] ?? 3;
        $usuario_id = $_SESSION['usuario_id'] ?? null;

        // Pegando filtros do GET
        $filtros = [
            'descricao' => $_GET['q'] ?? '',
            'setor' => $_GET['setor'] ?? '',
            'categoria' => $_GET['categoria'] ?? '',
            'prioridade' => $_GET['prioridade'] ?? '',
            'localizacao' => $_GET['localizacao'] ?? ''
        ];

        // Buscar históricos filtrados
        $chamados = $historico->listarConcluidos($usuario_id, $perfil, $filtros);

        // Para popular os selects de filtro
        $setores = $historico->listarSetores();
        $tecnicos = $historico->listarTecnicos();
        $categorias = $historico->listarCategorias();
        $prioridades = $historico->listarPrioridades();
        if ($perfil == 2) {
            $localizacoes = $historico->listarLocalizacoes($usuario_id);
}


        require __DIR__ . '/../Views/historicos/listar.php';
    }
}
