<?php
session_start();

// Defina sua senha aqui
$senhaCorreta = 'minhasenha123';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha = $_POST['senha'] ?? '';
    if ($senha === $senhaCorreta) {
        $_SESSION['logado'] = true;
        header('Location: gerenciamento.php');
        exit;
    } else {
        $erro = 'Senha incorreta.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
</head>
<body>
  <h2>Área Restrita</h2>
  <?php if (isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
  <form method="post">
    <label>Senha: <input type="password" name="senha" required></label>
    <button type="submit">Entrar</button>
  </form>
</body>
</html>
