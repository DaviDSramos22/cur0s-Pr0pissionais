<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

$file = 'posts.json';
$posts = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$msg = '';

// Remover post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remover'])) {
    $id = (int)$_POST['remover'];
    if (isset($posts[$id])) {
        /*
        if (file_exists($posts[$id]['imagem'])) {
            unlink($posts[$id]['imagem']);
        }
        */
        array_splice($posts, $id, 1);
        file_put_contents($file, json_encode($posts, JSON_PRETTY_PRINT));
        $msg = "Post removido com sucesso!";
    }
}

// Adicionar post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'], $_POST['link'], $_POST['descricao']) && !isset($_POST['remover'])) {
    $titulo = trim($_POST['titulo']);
    $link = trim($_POST['link']);
    $descricao = trim($_POST['descricao']);
    $imagem = '';

    if (!empty($_FILES['imagem']['name'])) {
        if (!is_dir('imgs')) {
            mkdir('imgs', 0755, true);
        }
        $nomeArquivo = basename($_FILES['imagem']['name']);
        $destino = 'imgs/' . $nomeArquivo;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
            $imagem = $destino;
        } else {
            $msg = "Erro ao enviar imagem.";
        }
    } elseif (!empty($_POST['imagem_existente'])) {
        $imagem = $_POST['imagem_existente'];
    }

    if ($titulo && $link && $imagem && $descricao) {
        $posts[] = [
            'titulo' => $titulo,
            'link' => $link,
            'descricao' => $descricao,
            'imagem' => $imagem
        ];
        file_put_contents($file, json_encode($posts, JSON_PRETTY_PRINT));
        $msg = "Post adicionado com sucesso!";
    } else {
        $msg = "Preencha todos os campos.";
    }
}

$imagensExistentes = glob("imgs/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Gerenciamento de Posts</title>
  <style>
    /* Reset básico */
    * {
      box-sizing: border-box;
    }
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f8;
      color: #333;
      padding: 20px;
      max-width: 900px;
      margin: 0 auto;
    }
    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #2c3e50;
    }
    a.logout {
      display: inline-block;
      margin-bottom: 20px;
      background: #e74c3c;
      color: white;
      padding: 8px 16px;
      border-radius: 4px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }
    a.logout:hover {
      background: #c0392b;
    }
    .msg {
      padding: 10px;
      background-color: #27ae60;
      color: white;
      border-radius: 4px;
      margin-bottom: 20px;
      text-align: center;
      font-weight: bold;
    }

    form {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      margin-bottom: 40px;
    }
    form label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
      color: #34495e;
    }
    form input[type="text"],
    form input[type="url"],
    form input[type="file"],
    form select {
      width: 100%;
      padding: 8px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 1rem;
      transition: border-color 0.3s ease;
    }
    form input[type="text"]:focus,
    form input[type="url"]:focus,
    form input[type="file"]:focus,
    form select:focus {
      border-color: #3498db;
      outline: none;
    }
    form button {
      background: #3498db;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }
    form button:hover {
      background: #2980b9;
    }

    ul.posts-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    ul.posts-list li {
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      display: flex;
      align-items: center;
      padding: 15px;
      margin-bottom: 15px;
      gap: 15px;
      flex-wrap: wrap;
    }
    ul.posts-list li img {
      max-width: 120px;
      width: 100%;
      border-radius: 6px;
      object-fit: cover;
      box-shadow: 0 1px 5px rgba(0,0,0,0.1);
    }
    .post-info {
      flex: 1 1 auto;
      min-width: 200px;
    }
    .post-info h3 {
      margin: 0 0 8px 0;
      color: #2c3e50;
    }
    .post-info p {
      margin: 0 0 8px 0;
      color: #555;
      line-height: 1.4;
    }
    .post-info a {
      color: #2980b9;
      text-decoration: none;
      word-break: break-all;
    }
    .post-info a:hover {
      text-decoration: underline;
    }
    form.remover-form {
      margin-left: auto;
      min-width: 90px;
      text-align: right;
    }
    button.remover-btn {
      background: #e74c3c;
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s ease;
      width: 100%;
    }
    button.remover-btn:hover {
      background: #c0392b;
    }

    /* Responsividade */
    @media (max-width: 600px) {
      ul.posts-list li {
        flex-direction: column;
        align-items: flex-start;
      }
      form.remover-form {
        margin-left: 0;
        width: 100%;
        text-align: left;
        margin-top: 10px;
      }
      button.remover-btn {
        width: 100%;
      }
    }
  </style>
</head>
<body>

  <h1>Gerenciamento de Posts</h1>
  <a href="logout.php" class="logout">Sair</a>

  <?php if ($msg): ?>
    <p class="msg"><?= htmlspecialchars($msg) ?></p>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <label for="titulo">Título:</label>
    <input type="text" name="titulo" id="titulo" required>

    <label for="descricao">Descrição:</label>
    <input type="text" name="descricao" id="descricao" required>

    <label for="link">Link:</label>
    <input type="url" name="link" id="link" required>

    <label for="imagem">Fazer upload de nova imagem:</label>
    <input type="file" name="imagem" id="imagem" accept="image/*">

    <label for="imagem_existente">Ou selecionar imagem existente:</label>
    <select name="imagem_existente" id="imagem_existente">
      <option value="">-- Nenhuma --</option>
      <?php foreach ($imagensExistentes as $img): ?>
        <option value="<?= htmlspecialchars($img) ?>"><?= htmlspecialchars(basename($img)) ?></option>
      <?php endforeach; ?>
    </select>

    <button type="submit">Adicionar Post</button>
  </form>

  <ul class="posts-list">
    <?php foreach ($posts as $id => $p): ?>
      <li>
        <img src="<?= htmlspecialchars($p['imagem']) ?>" alt="<?= htmlspecialchars($p['titulo']) ?>">
        <div class="post-info">
          <h3><?= htmlspecialchars($p['titulo']) ?></h3>
          <p><?= htmlspecialchars($p['descricao'] ?? '') ?></p>
          <a href="<?= htmlspecialchars($p['link']) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($p['link']) ?></a>
        </div>

        <form method="post" class="remover-form" onsubmit="return confirm('Tem certeza que quer remover este post?');">
          <input type="hidden" name="remover" value="<?= $id ?>">
          <button type="submit" class="remover-btn">Remover</button>
        </form>
      </li>
    <?php endforeach; ?>
  </ul>

</body>
</html>
