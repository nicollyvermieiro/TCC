<?php

require_once __DIR__ . '/../../config/database.php';

class AnexoChamado {
    private $conn;
    private $table = "anexo_chamado";

    public $id, $chamado_id, $caminho_arquivo, $tipo_arquivo;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    // Salvar anexo com tipo e timestamp
    public function salvar($chamado_id, $caminho_arquivo, $tipo_arquivo = null) {
        $query = "INSERT INTO {$this->table} (chamado_id, caminho_arquivo, tipo_arquivo, data_upload) 
                  VALUES (:chamado_id, :caminho, :tipo_arquivo, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':chamado_id', $chamado_id);
        $stmt->bindParam(':caminho', $caminho_arquivo);
        $stmt->bindParam(':tipo_arquivo', $tipo_arquivo);
        return $stmt->execute();
    }

    // Listar anexos de um chamado
    public function listarPorChamado($chamado_id) {
        $query = "SELECT * FROM {$this->table} WHERE chamado_id = :chamado_id ORDER BY data_upload DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':chamado_id', $chamado_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Excluir anexo
    public function excluir($id) {
        // Antes, remover arquivo físico
        $stmt = $this->conn->prepare("SELECT caminho_arquivo FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $arquivo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($arquivo && file_exists(__DIR__ . '/../../public/' . $arquivo['caminho_arquivo'])) {
            unlink(__DIR__ . '/../../public/' . $arquivo['caminho_arquivo']);
        }

        // Excluir registro do banco
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
