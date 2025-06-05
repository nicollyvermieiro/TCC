<?php
require_once __DIR__ . '/../Models/AnexoChamado.php';

class AnexosChamadosController {

    // Listar anexos de um chamado específico
    public function listar() {
        $chamado_id = $_GET['chamado_id'] ?? null;

        if ($chamado_id) {
            $anexoModel = new AnexoChamado();
            $anexos = $anexoModel->listarPorChamado($chamado_id);
            // Aqui incluiria a view para listar, mas por enquanto só retornamos os dados
            // include __DIR__ . '/../Views/anexos/listar.php';

            // Para testes, só exibir array
            header('Content-Type: application/json');
            echo json_encode($anexos);
        } else {
            echo "Chamado não informado.";
        }
    }

    // Salvar anexo (upload simples, só caminho no model)
    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chamado_id = $_POST['chamado_id'] ?? null;

            if ($chamado_id && isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileTmpPath = $_FILES['arquivo']['tmp_name'];
                $fileName = basename($_FILES['arquivo']['name']);
                $targetFilePath = $uploadDir . $fileName;

                if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                    $anexoModel = new AnexoChamado();
                    // Salvar o caminho relativo para uso no app
                    $caminhoRelativo = 'uploads/' . $fileName;

                    if ($anexoModel->salvar($chamado_id, $caminhoRelativo)) {
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

    // Excluir anexo pelo ID
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
