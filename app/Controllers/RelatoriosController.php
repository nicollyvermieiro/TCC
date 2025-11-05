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

    
    date_default_timezone_set('America/Campo_Grande');

    // Recupera filtros
    $filtros = $_SESSION['filtros_relatorio'] ?? [];
    $inicio = $filtros['inicio'] ?? null;
    $fim = $filtros['fim'] ?? null;
    $setor = $filtros['setor'] ?? null;
    $status = $filtros['status'] ?? null;
    $tecnico = $filtros['tecnico'] ?? null;

    $chamado = new Chamado();
    $dadosChamados = $chamado->listarPorPeriodo($inicio, $fim, $setor, $status, $tecnico);

    // Orientação paisagem (L)
    $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator('ManutSmart');
    $pdf->SetAuthor('ManutSmart');
    $pdf->SetTitle('Relatório de Chamados');
    $pdf->SetMargins(10, 15, 10);
    $pdf->AddPage();

    // Cabeçalho bonito
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'RELATÓRIO DE CHAMADOS - MANUTSMART', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 8, 'Período: ' . ($inicio ?: '---') . ' até ' . ($fim ?: '---'), 0, 1, 'C');
    $pdf->Ln(4);

    // Cabeçalho da tabela
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->SetFillColor(60, 141, 188); // azul
    $pdf->SetTextColor(255, 255, 255);

    $pdf->Cell(10, 8, 'ID', 1, 0, 'C', 1);
    $pdf->Cell(50, 8, 'Descrição', 1, 0, 'C', 1);
    $pdf->Cell(35, 8, 'Localização', 1, 0, 'C', 1);
    $pdf->Cell(25, 8, 'Setor', 1, 0, 'C', 1);
    $pdf->Cell(25, 8, 'Tipo', 1, 0, 'C', 1);
    $pdf->Cell(35, 8, 'Técnico', 1, 0, 'C', 1);
    $pdf->Cell(25, 8, 'Status', 1, 0, 'C', 1);
    $pdf->Cell(25, 8, 'Prioridade', 1, 0, 'C', 1);
    $pdf->Cell(35, 8, 'Data Criação', 1, 1, 'C', 1);

    // Corpo da tabela
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetTextColor(0, 0, 0);

    $fill = 0;
    foreach ($dadosChamados as $d) {
        $pdf->SetFillColor($fill ? 245 : 255, $fill ? 245 : 255, $fill ? 245 : 255); // cor alternada

        $pdf->Cell(10, 8, $d['id'], 1, 0, 'C', $fill);
        $pdf->MultiCell(50, 8, $d['descricao'], 1, 'L', $fill, 0);
        $pdf->MultiCell(35, 8, $d['localizacao'], 1, 'L', $fill, 0);
        $pdf->Cell(25, 8, $d['setor_nome'], 1, 0, 'L', $fill);
        $pdf->Cell(25, 8, $d['tipo_nome'], 1, 0, 'L', $fill);
        $pdf->Cell(35, 8, $d['tecnico_nome'], 1, 0, 'L', $fill);
         $pdf->MultiCell(25, 8, $d['status'], 1, 'C', $fill, 0);
        $pdf->Cell(25, 8, $d['prioridade_nome'], 1, 0, 'C', $fill);
        $pdf->Cell(35, 8, $d['data_criacao'], 1, 1, 'C', $fill);

        $fill = !$fill; // alterna cor da linha
    }

    // Rodapé
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', 'I', 8);
    $pdf->Cell(0, 10, 'Gerado por ManutSmart em ' . date('d/m/Y H:i'), 0, 0, 'R');

    $pdf->Output('relatorio_chamados.pdf', 'D');

    // Registro no banco
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
