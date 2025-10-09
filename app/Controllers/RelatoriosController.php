<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/RelatorioGerado.php';
require_once __DIR__ . '/../Models/Chamado.php';

class RelatoriosController
{
    // Listar todos os relatórios gerados
    public function listar()
    {
        $relatorio = new RelatorioGerado();
        $relatorios = $relatorio->listarTodos();

        require __DIR__ . '/../Views/relatorios/listar.php';
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

    public function filtroPeriodo()
    {
        require __DIR__ . '/../Views/relatorios/filtro_periodo.php';
    }

    public function gerarPorPeriodo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inicio = $_POST['inicio'] ?? null;
            $fim = $_POST['fim'] ?? null;
            $setor = $_POST['setor'] ?? null;
            $status = $_POST['status'] ?? null;
            $tecnico = $_POST['tecnico'] ?? null;

            if ($inicio && $fim) {
                $chamado = new Chamado();
                $dadosChamados = $chamado->listarPorPeriodo($inicio, $fim, $setor, $status, $tecnico);

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
            } else {
                setFlashMessage("Por favor, selecione o período inicial e final.", "warning");
                header("Location: ?route=relatorios/filtroPeriodo");
                exit;
            }
        } else {
            header("Location: ?route=relatorios/filtroPeriodo");
            exit;
        }
    }

    public function exportarPdf()
        {
            require_once __DIR__ . '/../../vendor/autoload.php';
            $chamado = new Chamado();
            $dadosChamados = $chamado->listarTodos(); // ou adapte para o período se quiser filtrar

            $pdf = new \TCPDF();
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('ManutSmart');
            $pdf->SetTitle('Relatório de Chamados');
            $pdf->SetMargins(15, 15, 15);
            $pdf->AddPage();

            $html = '<h2>Relatório de Chamados</h2>';
            $html .= '<table border="1" cellpadding="4">
                        <thead>
                            <tr style="background-color:#f2f2f2;">
                                <th>ID</th>
                                <th>Descrição</th>
                                <th>Localização</th>
                                <th>Setor</th>
                                <th>Tipo</th>
                                <th>Técnico</th>
                                <th>Status</th>
                                <th>Prioridade</th>
                                <th>Data</th>
                            </tr>
                        </thead><tbody>';

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
                            <td>'.$d['criado_em'].'</td>
                        </tr>';
            }

            $html .= '</tbody></table>';
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->Output('relatorio_chamados.pdf', 'D'); // força download
        }

    public function exportarCsv()
    {
        $inicio  = $_GET['periodo_inicio'] ?? null;
        $fim     = $_GET['periodo_fim'] ?? null;
        $setor   = $_GET['setor'] ?? null;
        $status  = $_GET['status'] ?? null;
        $tecnico = $_GET['tecnico'] ?? null;

        if (!$inicio || !$fim) {
            setFlashMessage("Período inválido para exportação.", "warning");
            header("Location: ?route=relatorios/filtroPeriodo");
            exit;
        }

        $chamado = new Chamado();
        $dadosChamados = $chamado->listarPorPeriodo($inicio, $fim, $setor, $status, $tecnico);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="relatorio_chamados.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID','Descrição','Localização','Setor','Tipo','Técnico','Status','Prioridade','Data']);

        foreach ($dadosChamados as $d) {
            fputcsv($output, [
                $d['id'],
                $d['descricao'],
                $d['localizacao'],
                $d['setor_nome'],
                $d['tipo_nome'],
                $d['tecnico_nome'],
                $d['status'],
                $d['prioridade_nome'],
                $d['criado_em']
            ]);
        }

        fclose($output);
        exit;
    }

   
}

