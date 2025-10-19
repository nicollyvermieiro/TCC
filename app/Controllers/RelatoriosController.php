<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/RelatorioGerado.php';
require_once __DIR__ . '/../Models/Chamado.php';
require_once __DIR__ . '/../Models/Setor.php';
require_once __DIR__ . '/../Models/Usuarios.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RelatoriosController
{
    // Lista todos os relatórios gerados
    public function listar() {
        require_once __DIR__ . '/../Models/Setor.php';
        require_once __DIR__ . '/../Models/Usuarios.php';
        require_once __DIR__ . '/../Models/Chamado.php';
        require_once __DIR__ . '/../Models/RelatorioGerado.php';
        require_once __DIR__ . '/../../config/database.php';

        $conn = (new Database())->getConnection();

        $setorModel = new Setor($conn);
        $usuarioModel = new Usuarios($conn);
        $chamadoModel = new Chamado($conn);
        $relatorioModel = new RelatorioGerado($conn);

        // Buscar dados do banco
        $setores  = $setorModel->listarTodos();
        $tecnicos = $usuarioModel->listarPorCargo('Técnico');
        $statusList = $chamadoModel->listarStatusDistintos();
        $relatorios = $relatorioModel->listarTodos(); // Método para pegar histórico de relatórios

        require __DIR__ . '/../Views/relatorios/listar.php';
    }


    // Gera relatório e exibe resultado na tela
    public function gerarPorPeriodo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inicio  = $_POST['inicio'] ?? null;
            $fim     = $_POST['fim'] ?? null;
            $setor   = $_POST['setor'] ?? null;
            $status  = $_POST['status'] ?? null;
            $tecnico = $_POST['tecnico'] ?? null;

            if (!$inicio || !$fim) {
                setFlashMessage("Selecione um período válido.", "warning");
                header("Location: ?route=relatorios/filtroPeriodo");
                exit;
            }

            $chamado = new Chamado();
            $dadosChamados = $chamado->listarPorPeriodo($inicio, $fim, $setor, $status, $tecnico);

            // Salvar log do relatório gerado
            $relatorio = new RelatorioGerado();
            $relatorio->tipo = "Visualização";
            $relatorio->gerado_por = $_SESSION['usuario_id'] ?? null;
            $relatorio->periodo_inicio = $inicio;
            $relatorio->periodo_fim = $fim;
            $relatorio->data_geracao = date('Y-m-d H:i:s');
            $relatorio->criar();

            // Armazena filtros na sessão para exportação
            $_SESSION['filtros_relatorio'] = [
                'inicio'  => $inicio,
                'fim'     => $fim,
                'setor'   => $setor,
                'status'  => $status,
                'tecnico' => $tecnico
            ];

            $dados = [];
            foreach ($dadosChamados as $d) {
                $dados[] = [
                    'id'          => $d['id'],
                    'descricao'   => $d['descricao'] ?? '—',
                    'localizacao' => $d['localizacao'] ?? '—',
                    'prioridade'  => $d['prioridade_nome'] ?? '—',
                    'status'      => $d['status'] ?? '—',
                    'tipo'        => $d['tipo_nome'] ?? '—',
                    'setor'       => $d['setor_nome'] ?? '—',
                    'tecnico'     => $d['tecnico_nome'] ?? '—',
                    'data'        => $d['data_criacao'] ?? '—',
                ];
            }

            require __DIR__ . '/../Views/relatorios/resultado_periodo.php';
        }
    }

    // Exportar PDF
    public function exportarPdf() {
        require_once __DIR__ . '/../../vendor/autoload.php';

        // Recupera filtros salvos
        $filtros = $_SESSION['filtros_relatorio'] ?? [];
        $inicio  = $filtros['inicio'] ?? null;
        $fim     = $filtros['fim'] ?? null;
        $setor   = $filtros['setor'] ?? null;
        $status  = $filtros['status'] ?? null;
        $tecnico = $filtros['tecnico'] ?? null;

        $chamado = new Chamado();
        $dadosChamados = $chamado->listarPorPeriodo($inicio, $fim, $setor, $status, $tecnico);

        $pdf = new TCPDF();
        $pdf->SetCreator('ManutSmart');
        $pdf->SetAuthor('ManutSmart');
        $pdf->SetTitle('Relatório de Chamados');
        $pdf->AddPage();

        $html = '<h2>Relatório de Chamados</h2>
                 <table border="1" cellpadding="4">
                 <thead><tr>
                    <th>ID</th><th>Descrição</th><th>Localização</th><th>Setor</th>
                    <th>Tipo</th><th>Técnico</th><th>Status</th><th>Prioridade</th><th>Data</th>
                 </tr></thead><tbody>';

        foreach ($dadosChamados as $d) {
            $html .= '<tr>
                <td>'.$d['id'].'</td>
                <td>'.$d['descricao'].'</td>
                <td>'.$d['localizacao'].'</td>
                <td>'.$d['setor_nome'].'</td>
                <td>'.$d['tipo_nome'].'</td>
                <td>'.$d['tecnico_nome'].'</td>
                <td>'.$d['status'].'</td>
                <td>'.$d['prioridade_nome'].'</td>
                <td>'.$d['data_criacao'].'</td>
            </tr>';
        }

        $html .= '</tbody></table>';
        $pdf->writeHTML($html);
        $pdf->Output('relatorio_chamados.pdf', 'D');

        // Salvar no banco
        $relatorio = new RelatorioGerado();
        $relatorio->tipo = "PDF";
        $relatorio->gerado_por = $_SESSION['usuario_id'] ?? null;
        $relatorio->periodo_inicio = $inicio;
        $relatorio->periodo_fim = $fim;
        $relatorio->data_geracao = date('Y-m-d H:i:s');
        $relatorio->criar();
    }

    // Exportar Excel
    public function exportarExcel() {
        require_once __DIR__ . '/../../vendor/autoload.php';

        // Recupera filtros salvos
        $filtros = $_SESSION['filtros_relatorio'] ?? [];
        $inicio  = $filtros['inicio'] ?? null;
        $fim     = $filtros['fim'] ?? null;
        $setor   = $filtros['setor'] ?? null;
        $status  = $filtros['status'] ?? null;
        $tecnico = $filtros['tecnico'] ?? null;

        $chamado = new Chamado();
        $dadosChamados = $chamado->listarPorPeriodo($inicio, $fim, $setor, $status, $tecnico);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Relatório de Chamados');
        $sheet->fromArray(['ID','Descrição','Localização','Setor','Tipo','Técnico','Status','Prioridade','Data'], null, 'A1');

        $row = 2;
        foreach ($dadosChamados as $d) {
            $sheet->fromArray([
                $d['id'], $d['descricao'], $d['localizacao'],
                $d['setor_nome'], $d['tipo_nome'], $d['tecnico_nome'],
                $d['status'], $d['prioridade_nome'], $d['data_criacao']
            ], null, 'A'.$row++);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="relatorio_chamados.xlsx"');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        // Salvar no banco
        $relatorio = new RelatorioGerado();
        $relatorio->tipo = "Excel";
        $relatorio->gerado_por = $_SESSION['usuario_id'] ?? null;
        $relatorio->periodo_inicio = $inicio;
        $relatorio->periodo_fim = $fim;
        $relatorio->data_geracao = date('Y-m-d H:i:s');
        $relatorio->criar();
        exit;
    }

    // Excluir relatório
    public function excluir() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $relatorio = new RelatorioGerado();
            if ($relatorio->excluir($id)) {
                setFlashMessage("Relatório excluído com sucesso.", "success");
            }
        }
        header('Location: ?route=relatorios/listar');
        exit;
    }
}
