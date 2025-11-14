<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            max-width: 600px;
            margin: 50px auto;
            border-radius: 12px;
        }
        .card-header {
            background-color: #0d6efd; /* Azul padrão ManutSmart */
            color: #fff;
            font-weight: bold;
            font-size: 1.2rem;
            text-align: center;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .form-label { font-weight: 500; }
        .form-control, .form-select { height: 40px; font-size: 0.95rem; }
        textarea.form-control { height: auto; }
        .btn-primary { background-color: #0d6efd; border-color: #0d6efd; font-weight: 500; }
        .btn-primary:hover { background-color: #0b5ed7; border-color: #0b5ed7; }
        .btn-secondary { font-weight: 500; }
        .d-grid button { height: 42px; font-size: 0.95rem; }
        h4 { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>

    <?php $flash = getFlashMessage(); ?>
    <?php if ($flash): ?>
        <div class="container mt-3">
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header">Criar Usuário</div>
            <div class="card-body">
                <form method="post" action="?route=usuarios/salvar">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome:</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha:</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>

                    <div class="mb-3">
                        <label for="cargo_id" class="form-label">Cargo:</label>
                        <select class="form-select" id="cargo_id" name="cargo_id" required>
                            <option value="">-- Selecione --</option>
                            <?php foreach($cargos as $cargo): ?>
                                <option value="<?= htmlspecialchars($cargo['id']) ?>">
                                    <?= htmlspecialchars($cargo['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3" id="setor_div" style="display:none;">
                        <label for="setor_id" class="form-label">Setor:</label>
                        <select class="form-select" id="setor_id" name="setor_id">
                            <option value="">-- Selecione o setor --</option>
                            <?php foreach($setores as $setor): ?>
                                <option value="<?= htmlspecialchars($setor['id']) ?>"
                                    <?= (isset($usuario) && $usuario['setor_id'] == $setor['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($setor['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <a href="?route=usuarios/listar" class="btn btn-secondary">Voltar para Lista</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    const cargoSelect = document.getElementById('cargo_id');
    const setorDiv = document.getElementById('setor_div');
    const setorSelect = document.getElementById('setor_id');

    function atualizarSetor() {
        const tipo = cargoSelect.options[cargoSelect.selectedIndex].text.toLowerCase();
        if(tipo.includes('técnico')) {
            setorDiv.style.display = 'block';
            setorSelect.required = true;
        } else {
            setorDiv.style.display = 'none';
            setorSelect.required = false;
            setorSelect.value = '';
        }
    }

    cargoSelect.addEventListener('change', atualizarSetor);

    window.addEventListener('load', atualizarSetor);
    </script>

    <footer class="bg-primary text-white text-center py-1 mt-4 shadow-sm">
        <div style="font-size: 0.8rem; opacity: 0.8;">
            &copy; 2025 ManutSmart. Todos os direitos reservados.
        </div>
    </footer>
</body>
</html>
