
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

    <?php include __DIR__ . '/../partials/menu.php'; ?>
    <h2>Login</h2>
    <form method="POST" action="?route=login">
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Senha:</label><br>
        <input type="password" name="senha" required><br><br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
