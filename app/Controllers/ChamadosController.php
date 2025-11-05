<?php
require_once __DIR__ . '/../Models/Chamado.php';
require_once __DIR__ . '/../Models/TipoChamado.php';
require_once __DIR__ . '/../Models/Setor.php';
require_once __DIR__ . '/../Models/Prioridade.php';
require_once __DIR__ . '/../Models/AnexoChamado.php';
require_once __DIR__ . '/../Models/Usuarios.php';

class ChamadosController
{
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    // ========================
    // FORMULÁRIO DE CRIAÇÃO
    // ========================
    public function criarUsuario() {
        $isGuest = isset($_GET['guest']) && $_GET['guest'] == 1;

        if($isGuest){
            // Identificação temporária para visitante via QR
            $_SESSION['is_guest'] = true;
            $usuarioNome = "Visitante";
        } else {
            $_SESSION['is_guest'] = false;
            $usuarioNome = $_SESSION['usuario']['nome'] ?? 'Usuário';
        }

        require __DIR__ . '/../Views/chamados/criar_usuario.php';
    }
// ========================
// SALVAR CHAMADO
// ========================
public function salvarUsuario() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $chamado = new Chamado($this->db);

        $chamado->descricao   = $_POST['descricao'] ?? '';
        $chamado->localizacao = $_POST['localizacao'] ?? '';
        $chamado->status      = 'Aguardando Atendimento';

        if (!empty($_SESSION['usuario_id']) && !$_SESSION['is_guest']) {
            // Usuário logado
            $chamado->usuario_id = $_SESSION['usuario_id'];
            $chamado->usuario_temporario = null;
            $chamado->origem = 'sistema';
        } else {
            // Visitante via QR Code
            $chamado->usuario_id = null;
            $chamado->usuario_temporario = $_POST['nome'] ?? 'Visitante';
            $chamado->origem = 'qrcode';
        }

        // Cria chamado básico e pega o ID gerado
        $chamado_id = $chamado->criarBasico();

        if ($chamado_id) {
            // ==========================
            // UPLOAD DE ANEXO
            // ==========================
            if (!empty($_FILES['anexo']['name'])) {
                $arquivoNome = time() . "_" . basename($_FILES['anexo']['name']);
                $caminho = __DIR__ . '/../../public/uploads/' . $arquivoNome;

                if (move_uploaded_file($_FILES['anexo']['tmp_name'], $caminho)) {
                    $anexoModel = new AnexoChamado();
                    $caminhoRelativo = 'uploads/' . $arquivoNome;

                    // Identificar tipo do arquivo
                    $tipo = mime_content_type($caminho);

                    $anexoModel->salvar($chamado_id, $caminhoRelativo, $tipo);
                }
            }

            // ==========================
            // REDIRECIONAMENTO
            // ==========================
            if (!empty($_SESSION['usuario_id']) && !$_SESSION['is_guest']) {
                // Usuário logado -> volta pro dashboard
                setFlashMessage("Chamado registrado com sucesso!", "success");
                header('Location: ?route=auth/dashboard');
            } else {
                // Visitante (via QR Code) -> mostra protocolo na consulta
                setFlashMessage("Chamado registrado com sucesso. Protocolo: {$chamado->protocolo}", "success");
                // Passa protocolo via GET para auto-preenchimento
                header('Location: ?route=chamados/consultar&protocolo=' . urlencode($chamado->protocolo));
            }
            exit;

        } else {
            setFlashMessage("Erro ao registrar chamado.", "danger");
            header('Location: ?route=chamados/criarUsuario');
            exit;
        }
    }
}


    // ========================
    // CONSULTA DE CHAMADO POR PROTOCOLO
    // ========================
   public function consultar() { 
    $chamado = null;
    $erro = null;

    // Se o visitante vier via QR Code com protocolo na URL
    $protocolo = trim($_POST['protocolo'] ?? $_GET['protocolo'] ?? '');

    if ($protocolo !== '') {
        $chamadoModel = new Chamado($this->db);
        $chamado = $chamadoModel->buscarPorProtocoloQr($protocolo); // Só QR Code

        if (!$chamado) {
            $erro = "Protocolo inválido ou chamado não encontrado.";
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $erro = "Informe o número do protocolo.";
    }

    include __DIR__ . '/../Views/chamados/consultar.php';
}



    // ========================
    // ADMIN
    // ========================
    public function complementar() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            setFlashMessage("Chamado não encontrado.", "warning");
            header("Location: ?route=chamados/listar");
            exit;
        }

        $chamadoModel = new Chamado($this->db);
        $dados = $chamadoModel->buscarPorId($id);

        if (!$dados) {
            setFlashMessage("Chamado não encontrado.", "warning");
            header("Location: ?route=chamados/listar");
            exit;
        }

        $tipoModel = new TipoChamado($this->db);
        $setorModel = new Setor($this->db);
        $prioridadeModel = new Prioridade($this->db);
        $usuarioModel = new Usuarios($this->db);
        $anexoModel = new AnexoChamado();

        $tecnicos = $usuarioModel->listarPorCargo('Técnico'); // novo método no model Usuario
        $tipos = $tipoModel->listarTodos();
        $setores = $setorModel->listarTodos();
        $prioridades = $prioridadeModel->listarTodos();
        $dados['anexos'] = $anexoModel->listarPorChamado($id);

        foreach ($dados['anexos'] as &$anexo) {
            $anexo['nome_arquivo'] = basename($anexo['caminho_arquivo']);
            $anexo['caminho'] = $anexo['caminho_arquivo']; // caminho relativo usado na view
        }

        require __DIR__ . '/../Views/chamados/complementar.php';
    }

    public function salvarComplemento() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chamado = new Chamado($this->db);
            $chamado->id            = $_POST['id'];
            $chamado->tipo_id       = $_POST['tipo_id'];
            $chamado->setor_id      = $_POST['setor_id'];
            $chamado->prioridade_id = $_POST['prioridade_id'];
            $chamado->tecnico_id    = $_POST['tecnico_id'];
            $chamado->status        = 'Em análise';

            if ($chamado->atualizarComplemento()) {
                setFlashMessage("Chamado complementado com sucesso.", "success");
                header('Location: ?route=chamados/listar');
                exit;
            } else {
                setFlashMessage("Erro ao complementar chamado.", "danger");
                header("Location: ?route=chamados/complementar&id={$chamado->id}");
                exit;
            }
        }
    }

    // ========================
    // TÉCNICO
    // ========================

    public function chamadosTecnico() {
        $chamadoModel = new Chamado($this->db);
        $chamados = $chamadoModel->listarPorTecnico($_SESSION['usuario_id']);
        require __DIR__ . '/../Views/chamados/listar.php';
    }

   
    // ========================
    // LISTAR, EXCLUIR, ATUALIZAR, GERENCIAR
    // ========================

    public function listar() {
        $chamadoModel = new Chamado($this->db);
        $perfil = $_SESSION['cargo_id'] ?? 3;
        $usuario_id = $_SESSION['usuario_id'] ?? null;

        if ($perfil == 1) {
            $chamados = $chamadoModel->listarTodos();
        } elseif ($perfil == 2) {
            $chamados = $chamadoModel->listarPorTecnico($usuario_id);
        } else {
            $chamados = $chamadoModel->listarPorUsuario($usuario_id);
        }

        require __DIR__ . '/../Views/chamados/listar.php';
    }

    public function excluir() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $chamado = new Chamado($this->db);
            if ($chamado->excluir($id)) {
                setFlashMessage("Chamado excluído com sucesso.", "success");
            } else {
                setFlashMessage("Erro ao excluir chamado.", "danger");
            }
        }
        header('Location: ?route=chamados/listar');
        exit;
    }

   public function editar() {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        setFlashMessage("Chamado não encontrado.", "warning");
        header("Location: ?route=chamados/listar");
        exit;
    }

    $chamadoModel = new Chamado($this->db);
    $dados = $chamadoModel->buscarPorId($id);

    if (!$dados) {
        setFlashMessage("Chamado não encontrado.", "warning");
        header("Location: ?route=chamados/listar");
        exit;
    }

    $tipoModel = new TipoChamado($this->db);
    $setorModel = new Setor($this->db);
    $prioridadeModel = new Prioridade($this->db);

    $tipos = $tipoModel->listarTodos();
    $setores = $setorModel->listarTodos();
    $prioridades = $prioridadeModel->listarTodos();

    require __DIR__ . '/../Views/chamados/atualizar.php';
}

public function atualizar() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;

        if (!$id) {
            setFlashMessage("Chamado não encontrado.", "warning");
            header("Location: ?route=chamados/listar");
            exit;
        }

        $chamadoModel = new Chamado($this->db);
        $dadosAntigos = $chamadoModel->buscarPorId($id);

        if (!$dadosAntigos) {
            setFlashMessage("Chamado não encontrado.", "warning");
            header("Location: ?route=chamados/listar");
            exit;
        }

        // Bloquear edição se já concluído
        if ($dadosAntigos['status'] === 'Concluído') {
            setFlashMessage("Chamados concluídos não podem ser editados.", "danger");
            header("Location: ?route=chamados/listar");
            exit;
        }

        $chamadoModel->id = $id;
        $chamadoModel->descricao = $_POST['descricao'] ?? $dadosAntigos['descricao'];
        $chamadoModel->tipo_id   = $_POST['tipo_chamado_id'] ?? $dadosAntigos['tipo_id'];
        $chamadoModel->setor_id  = $_POST['setor_id'] ?? $dadosAntigos['setor_id'];
        $chamadoModel->prioridade_id = $_POST['prioridade'] ?? $dadosAntigos['prioridade_id'];
        $chamadoModel->status    = $_POST['status'] ?? $dadosAntigos['status'];

        try {
            $this->db->beginTransaction();

            if (!$chamadoModel->atualizarComplemento()) {
                throw new Exception("Erro ao atualizar chamado.");
            }

            if ($dadosAntigos['status'] !== $chamadoModel->status) {
                $stmtHist = $this->db->prepare("
                    INSERT INTO historico_status (chamado_id, status_anterior, novo_status, alterado_por) 
                    VALUES (:chamado_id, :status_ant, :status_novo, :usuario_id)
                ");
                $stmtHist->bindParam(":chamado_id", $id);
                $stmtHist->bindParam(":status_ant", $dadosAntigos['status']);
                $stmtHist->bindParam(":status_novo", $chamadoModel->status);
                $stmtHist->bindParam(":usuario_id", $_SESSION['usuario_id']);
                $stmtHist->execute();
            }

            $this->db->commit();
            setFlashMessage("Chamado atualizado com sucesso.", "success");
            header("Location: ?route=chamados/listar");
            exit;

        } catch (Exception $e) {
            $this->db->rollBack();
            setFlashMessage("Erro ao atualizar chamado: " . $e->getMessage(), "danger");
            header("Location: ?route=chamados/editar&id={$id}");
            exit;
        }
    }
}

    public function gerenciar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['cargo_id'] ?? null, [1, 2])) {
        setFlashMessage("Acesso negado. Área restrita a administradores e técnicos.", "danger");
        header("Location: ?route=auth/dashboard");
        exit;
    }
        require __DIR__ . '/../Views/chamados/gerenciar.php';
    }
}
