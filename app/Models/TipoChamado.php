<?php
class TipoChamado
{
    private $conn;
    private $table = "tipo_chamado";

    public $id;
    public $nome;
    public $descricao;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function listarTodos()
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY nome ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criar()
    {
        $query = "INSERT INTO {$this->table} (nome, descricao) VALUES (:nome, :descricao)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':nome', $this->nome);
        $stmt->bindValue(':descricao', $this->descricao);
        return $stmt->execute();
    }

    public function buscarPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar()
    {
        $query = "UPDATE {$this->table} SET nome = :nome, descricao = :descricao WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':nome', $this->nome);
        $stmt->bindValue(':descricao', $this->descricao);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function excluir($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
