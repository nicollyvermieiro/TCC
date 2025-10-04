<?php
require_once __DIR__ . '/../Models/AnexoChamado.php';

class AnexosChamadosController {

    // Listar anexos
    public function listar() {
        $chamado_id = $_GET['chamado_id'] ?? null;

        if ($chamado_id) {
            $anexoModel = new AnexoChamado();
            $anexos = $anexoModel->listarPorChamado($chamado_id);

            // Para testes, exibir JSON
            header('Content-Type: application/json');
            echo json_encode($anexos);
        } else {
            echo "Chamado não informado.";
        }
    }

    // Upload de anexo
    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chamado_id = $_POST['chamado_id'] ?? null;

            if ($chamado_id && isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $fileTmpPath = $_FILES['arquivo']['tmp_name'];
                $fileName = time() . "_" . basename($_FILES['arquivo']['name']); // evita duplicidade
                $targetFilePath = $uploadDir . $fileName;

                // Verifica tipo e tamanho do arquivo
                $tipo = mime_content_type($fileTmpPath);
                $tiposPermitidos = ['image/jpeg','image/png','image/gif','application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                $tamanhoMax = 5 * 1024 * 1024; // 5MB

                if (!in_array($tipo, $tiposPermitidos)) {
                    die("Tipo de arquivo não permitido.");
                }
                if ($_FILES['arquivo']['size'] > $tamanhoMax) {
                    die("Arquivo muito grande. Máx 5MB.");
                }

                if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                    $anexoModel = new AnexoChamado();
                    $caminhoRelativo = 'uploads/' . $fileName;

                    if ($anexoModel->salvar($chamado_id, $caminhoRelativo, $tipo)) {
                        header("Location: ?route=anexos/listar&chamado_id=$chamado_id");
                        exit;
                    } else {
                        echo "Erro ao salvar anexo no banco.";
                    }
                } else {
                    echo "Erro ao mover o arquivo.";
                }

            } else {
                echo "Parâmetros ou arquivo inválidos.";
            }
        }
    }

    // Excluir anexo
    public function excluir() {
        $id = $_GET['id'] ?? null;
        $chamado_id = $_GET['chamado_id'] ?? null;

        if ($id) {
            $anexoModel = new AnexoChamado();
            if ($anexoModel->excluir($id)) {
                header("Location: ?route=anexos/listar&chamado_id=$chamado_id");
                exit;
            } else {
                echo "Erro ao excluir anexo.";
            }
        } else {
            echo "ID do anexo não informado.";
        }
    }
}
