<?php
require_once __DIR__ . '/../Models/ManutencaoChamado.php';
require_once __DIR__ . '/../Models/Usuarios.php';
require_once __DIR__ . '/../Models/Chamado.php';
require_once __DIR__ . '/../Models/AnexoChamado.php';
require_once __DIR__ . '/../../config/database.php';

class ManutencoesController
{
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function listar() {
        $manutencao = new ManutencaoChamado($this->db);
        $manutencoes = $manutencao->listarTodos();

        $pendentes = [];
        $concluidas = [];
        foreach ($manutencoes as $m) {
            $status = strtolower($m['status_chamado']);
            if ($status === 'concluída' || $status === 'concluida') {
                $concluidas[] = $m;
            } else {
                $pendentes[] = $m;
            }
        }

        require __DIR__ . '/../Views/chamados/listar.php';
    }

    public function criar() {
        $chamadoId = $_GET['id'] ?? null;
        if (!$chamadoId) {
            setFlashMessage("Chamado não encontrado.", "warning");
            header('Location: ?route=chamados/listar');
            exit;
        }

        $chamadoModel = new Chamado($this->db);
        $dados = $chamadoModel->buscarPorId($chamadoId); 
        $anexoModel = new AnexoChamado();
        $dados['anexos'] = $anexoModel->listarPorChamado($chamadoId);

        foreach ($dados['anexos'] as &$anexo) {
            $anexo['nome_arquivo'] = basename($anexo['caminho_arquivo']);
            $anexo['caminho'] = $anexo['caminho_arquivo']; // caminho relativo usado na view
        }

        require __DIR__ . '/../Views/manutencoes/criar.php';
    }

   public function salvar() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $chamado_id = $_POST['chamado_id'] ?? null;
    $descricao_servico = $_POST['descricao_servico'] ?? null;
    $pecas_trocadas = $_POST['pecas_trocadas'] ?? null;
    $observacoes = $_POST['observacoes'] ?? null;
    $tecnico_id = $_SESSION['usuario_id'] ?? null; // quem está logado

    if (!$tecnico_id) {
        setFlashMessage("danger", "Técnico não autenticado.");
        header("Location: ?route=login");
        exit;
    }

    if ($chamado_id && $descricao_servico) {
        $db = $this->db; // usa a conexão do controller

        try {
            $db->beginTransaction();

            // 1. Inserir em manutencao_chamado
            $stmt = $db->prepare("
                INSERT INTO manutencao_chamado 
                (chamado_id, tecnico_id, descricao_servico, pecas_trocadas, observacoes) 
                VALUES (:chamado_id, :tecnico_id, :descricao_servico, :pecas_trocadas, :observacoes)
            ");
            $stmt->execute([
                ":chamado_id" => $chamado_id,
                ":tecnico_id" => $tecnico_id,
                ":descricao_servico" => $descricao_servico,
                ":pecas_trocadas" => $pecas_trocadas,
                ":observacoes" => $observacoes,
            ]);

            // 2. Inserir no histórico de status
            $stmtOld = $db->prepare("SELECT status FROM chamado WHERE id = :id");
            $stmtOld->execute([":id" => $chamado_id]);
            $statusAntigo = $stmtOld->fetchColumn();

            $stmtHist = $db->prepare("
                INSERT INTO historico_status 
                (chamado_id, status_anterior, novo_status, alterado_por) 
                VALUES (:chamado_id, :status_anterior, :novo_status, :alterado_por)
            ");
            $stmtHist->execute([
                ":chamado_id" => $chamado_id,
                ":status_anterior" => $statusAntigo,
                ":novo_status" => "Concluído",
                ":alterado_por" => $tecnico_id,
            ]);

            // 3. Atualizar o chamado para concluído
            $stmtUpdate = $db->prepare("UPDATE chamado SET status = 'Concluído' WHERE id = :id");
            $stmtUpdate->execute([":id" => $chamado_id]);

            $db->commit();

            // Flash message e redirecionamento
            setFlashMessage("Manutenção registrada e chamado concluído.", "success");
            header("Location: ?route=chamados/listar");
            exit;

        } catch (Exception $e) {
            $db->rollBack();
            setFlashMessage("Erro ao registrar manutenção: " . $e->getMessage(), "danger");
            header("Location: ?route=manutencoes/criar&id=" . $chamado_id);
            exit;
        }
    } else {
        setFlashMessage("Preencha os campos obrigatórios.", "warning");
        header("Location: ?route=manutencoes/criar&id=" . $chamado_id);
        exit;
    }
}
    
}
