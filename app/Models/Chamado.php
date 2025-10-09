<?php
require_once __DIR__ . '/../../config/database.php';

class Chamado {
    private $conn;
    private $table = "chamado";

    public $id;
    public $descricao;
    public $localizacao;
    public $usuario_id;
    public $usuario_temporario;
    public $protocolo;
    public $status;
    public $tipo_id;
    public $setor_id;
    public $tecnico_id;
    public $prioridade_id;
    public $resolucao;
    public $criado_em;
    public $origem; // NOVO CAMPO

    public function __construct($db = null) {
        $this->conn = $db ?? (new Database())->getConnection();
    }

    // =============================
    // Método para criar chamado básico
    // =============================
    public function criarBasico() {
        $this->protocolo = $this->protocolo ?? date("Y") . strtoupper(uniqid());

        $query = "INSERT INTO {$this->table} 
                  (descricao, localizacao, usuario_id, usuario_temporario, protocolo, status, origem) 
                  VALUES (:descricao, :localizacao, :usuario_id, :usuario_temporario, :protocolo, :status, :origem)
                  RETURNING id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":localizacao", $this->localizacao);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":usuario_temporario", $this->usuario_temporario);
        $stmt->bindParam(":protocolo", $this->protocolo);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":origem", $this->origem);

        if ($stmt->execute()) {
            return $stmt->fetchColumn(); 
        }
        return false;
    }

    public function atualizarComplemento() {
        $stmtOld = $this->conn->prepare("SELECT status FROM {$this->table} WHERE id = :id");
        $stmtOld->bindParam(":id", $this->id);
        $stmtOld->execute();
        $statusAntigo = $stmtOld->fetchColumn();

        $query = "UPDATE {$this->table} 
                SET tipo_id = :tipo_id, setor_id = :setor_id, prioridade_id = :prioridade_id, tecnico_id = :tecnico_id, status = :status 
                WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tipo_id", $this->tipo_id);
        $stmt->bindParam(":setor_id", $this->setor_id);
        $stmt->bindParam(":prioridade_id", $this->prioridade_id);
        $stmt->bindParam(":tecnico_id", $this->tecnico_id);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    public function listarTodos() {
        $query = "SELECT c.*, 
                         tc.nome AS tipo_nome, 
                         s.nome AS setor_nome, 
                         u.nome AS usuario_nome,
                         p.nome AS prioridade_nome,
                         tec.nome AS tecnico_nome
                  FROM {$this->table} c
                  LEFT JOIN tipo_chamado tc ON c.tipo_id = tc.id
                  LEFT JOIN setor s ON c.setor_id = s.id
                  LEFT JOIN prioridade_chamado p ON c.prioridade_id = p.id
                  LEFT JOIN usuario u ON c.usuario_id = u.id
                  LEFT JOIN usuario tec ON c.tecnico_id = tec.id
                  ORDER BY c.criado_em DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   public function listarPorTecnico($tecnico_id) {
    $query = "SELECT c.*,
                     tc.nome AS tipo_nome,
                     s.nome AS setor_nome,
                     u.nome AS usuario_nome,
                     p.nome AS prioridade_nome,
                     tec.nome AS tecnico_nome
              FROM {$this->table} c
              LEFT JOIN tipo_chamado tc ON c.tipo_id = tc.id
              LEFT JOIN setor s ON c.setor_id = s.id
              LEFT JOIN prioridade_chamado p ON c.prioridade_id = p.id
              LEFT JOIN usuario u ON c.usuario_id = u.id
              LEFT JOIN usuario tec ON c.tecnico_id = tec.id
              WHERE c.tecnico_id = :tecnico_id
              ORDER BY c.criado_em DESC";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":tecnico_id", $tecnico_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function listarPorUsuario($usuario_id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE usuario_id = :usuario_id ORDER BY criado_em DESC");
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $query = "
            SELECT c.*, u.nome AS tecnico_nome
            FROM chamado c
            LEFT JOIN usuario u ON c.tecnico_id = u.id
            WHERE c.id = :id
            LIMIT 1
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarStatus() {
        $query = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }


    public function buscarPorProtocoloQr($protocolo) {
        $query = "SELECT c.*, 
                         tc.nome AS tipo_nome, 
                         s.nome AS setor_nome, 
                         u.nome AS usuario_nome,
                         p.nome AS prioridade_nome,
                         tec.nome AS tecnico_nome
                  FROM {$this->table} c
                  LEFT JOIN tipo_chamado tc ON c.tipo_id = tc.id
                  LEFT JOIN setor s ON c.setor_id = s.id
                  LEFT JOIN prioridade_chamado p ON c.prioridade_id = p.id
                  LEFT JOIN usuario u ON c.usuario_id = u.id
                  LEFT JOIN usuario tec ON c.tecnico_id = tec.id
                  WHERE c.protocolo = :protocolo
                  AND c.origem = 'qrcode'
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":protocolo", $protocolo);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   public function listarPorPeriodo($inicio, $fim) {
        $query = "
            SELECT c.id, c.descricao, c.status, c.criado_em, 
                s.nome AS setor_nome, 
                t.nome AS tipo_nome,
                u.nome AS tecnico_nome
            FROM chamado c
            LEFT JOIN setor s ON c.setor_id = s.id
            LEFT JOIN tipo_chamado t ON c.tipo_id = t.id
            LEFT JOIN usuario u ON c.tecnico_id = u.id
            WHERE c.criado_em BETWEEN :inicio AND :fim
            ORDER BY c.criado_em DESC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':inicio', $inicio);
        $stmt->bindParam(':fim', $fim);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function excluir($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function setConnection($conn) {
        $this->conn = $conn;
    }
}
    
