<?php
require_once __DIR__ . '/config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    echo "Conexão com o banco PostgreSQL realizada com sucesso!";
} catch (Exception $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
