<?php
require_once __DIR__ . '/Chamado.php';

class ManutencaoChamado {
    private $conn;
    private $table = "manutencao_chamado";

    public $id;
    public $chamado_id;
    public $tecnico_id;
    public $descricao_servico;
    public $pecas_trocadas;
    public $observacoes;
    public $data_registro;
    public $ultimoErro; // <- para guardar erro do banco

    public function __construct($db = null) {
        $this->conn = $db ?? (new Database())->getConnection();
    }

    public function criar() {
        try {
            $query = "INSERT INTO {$this->table} 
                      (chamado_id, tecnico_id, descricao_servico, pecas_trocadas, observacoes) 
                      VALUES (:chamado_id, :tecnico_id, :descricao_servico, :pecas_trocadas, :observacoes) 
                      RETURNING id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":chamado_id", $this->chamado_id);
            $stmt->bindParam(":tecnico_id", $this->tecnico_id);
            $stmt->bindParam(":descricao_servico", $this->descricao_servico);
            $stmt->bindParam(":pecas_trocadas", $this->pecas_trocadas);
            $stmt->bindParam(":observacoes", $this->observacoes);
            $stmt->execute();
            $this->id = $stmt->fetchColumn();

            // Atualizar status do chamado para "Concluído"
            $chamado = new Chamado($this->conn);
            $chamado->id = $this->chamado_id;
            $chamado->status = 'Concluído';
            $chamado->atualizarStatus();

            return true;
        } catch (Exception $e) {
            $this->ultimoErro = $e->getMessage();
            return false;
        }
    }

    public function listarTodos() {
        $query = "SELECT m.*, u.nome AS tecnico_nome, c.descricao AS chamado_descricao, c.status AS status_chamado, c.localizacao
                  FROM {$this->table} m
                  LEFT JOIN usuario u ON m.tecnico_id = u.id
                  LEFT JOIN chamado c ON m.chamado_id = c.id
                  ORDER BY m.data_registro DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function buscarPorId($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
