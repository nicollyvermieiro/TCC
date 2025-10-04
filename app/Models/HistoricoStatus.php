<?php

require_once __DIR__ . '/../../config/database.php';

class HistoricoStatus {
    private $conn;
    private $table = "historico_status";

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    // Registrar novo histórico
    public function registrar($chamado_id, $status, $observacao) {
        $query = "INSERT INTO historico_status (chamado_id, novo_status, observacao, criado_em)
                  VALUES (:chamado_id, :novo_status, :observacao, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":chamado_id", $chamado_id);
        $stmt->bindParam(":novo_status", $status);
        $stmt->bindParam(":observacao", $observacao);
        return $stmt->execute();
    }

    // Listar históricos concluídos com todas informações necessárias
    public function listarConcluidos($usuario_id = null, $perfil = null, $filtros = []) {
        $query = "SELECT 
                    h.*, 
                    c.descricao, 
                    c.localizacao,
                    c.criado_em,
                    t.nome AS tipo_nome,
                    s.nome AS setor_nome,
                    p.nome AS prioridade_nome,
                    u.nome AS usuario_nome,
                    tec.nome AS tecnico_nome
                  FROM {$this->table} h
                  INNER JOIN chamado c ON h.chamado_id = c.id
                  LEFT JOIN tipo_chamado t ON c.tipo_id = t.id
                  LEFT JOIN setor s ON c.setor_id = s.id
                  LEFT JOIN prioridade_chamado p ON c.prioridade_id = p.id
                  LEFT JOIN usuario u ON c.usuario_id = u.id
                  LEFT JOIN usuario tec ON c.tecnico_id = tec.id
                  WHERE h.novo_status = 'Concluído'";

        $params = [];

        // Se for técnico, mostra apenas seus chamados
        if ($perfil == 2 && $usuario_id) {
            $query .= " AND tec.id = :usuario_id";
            $params[':usuario_id'] = $usuario_id;
        }

        // Filtros adicionais
        if (!empty($filtros['descricao'])) {
            $query .= " AND c.descricao ILIKE :descricao";
            $params[':descricao'] = '%' . $filtros['descricao'] . '%';
        }
        if (!empty($filtros['setor'])) {
            $query .= " AND s.id = :setor";
            $params[':setor'] = $filtros['setor'];
        }
        if (!empty($filtros['categoria'])) {
            $query .= " AND t.id = :categoria";
            $params[':categoria'] = $filtros['categoria'];
        }
        if (!empty($filtros['prioridade'])) {
            $query .= " AND p.id = :prioridade";
            $params[':prioridade'] = $filtros['prioridade'];
        }
        if (!empty($filtros['localizacao']) && $perfil == 2) {
            $query .= " AND c.localizacao = :localizacao";
            $params[':localizacao'] = $filtros['localizacao'];
        }

        $query .= " ORDER BY h.data_alteracao DESC";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarSetores() {
        $stmt = $this->conn->prepare("SELECT * FROM setor ORDER BY nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarTecnicos() {
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE cargo_id = 2 ORDER BY nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarCategorias() {
        $stmt = $this->conn->prepare("SELECT * FROM tipo_chamado ORDER BY nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPrioridades() {
        $stmt = $this->conn->prepare("SELECT * FROM prioridade_chamado ORDER BY nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarLocalizacoes($usuario_id) {
    $stmt = $this->conn->prepare("
        SELECT DISTINCT c.localizacao
        FROM chamado c
        INNER JOIN usuario tec ON c.tecnico_id = tec.id
        WHERE tec.id = :usuario_id
        ORDER BY c.localizacao
    ");
    $stmt->bindValue(':usuario_id', $usuario_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Buscar históricos por chamado específico
    public function buscarPorChamado($chamado_id) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE chamado_id = :chamado_id 
                  ORDER BY criado_em DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":chamado_id", $chamado_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
