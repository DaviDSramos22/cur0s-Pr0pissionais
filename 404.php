<?php
// Ler os posts do arquivo JSON
$posts = [];
if (file_exists('posts.json')) {
  $posts = json_decode(file_get_contents('posts.json'), true);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Menu Responsivo com Posts</title>
  <style>
    /* Variáveis para facilitar ajustes */
    :root {
      --primary-color: #1e1e2f;
      --accent-color: #ffcc00;
      --text-color: #333;
      --background-color: #f9f9f9;
      --card-bg: #fff;
      --card-shadow: rgba(0, 0, 0, 0.12);
      --font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Reset e base */
    *, *::before, *::after {
      margin: 0; padding: 0; box-sizing: border-box;
    }

    body {
      font-family: var(--font-family);
      background-color: var(--background-color);
      color: var(--text-color);
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    /* Cabeçalho */
    header {
      background-color: var(--primary-color);
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 1.5rem;
      box-shadow: 0 2px 6px var(--card-shadow);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .logo {
      font-weight: 700;
      font-size: 1.5rem;
      letter-spacing: 1px;
      user-select: none;
    }

    /* Menu hambúrguer */
    .hamburguer {
      width: 30px;
      height: 22px;
      position: relative;
      cursor: pointer;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: transform 0.3s ease;
    }

    .hamburguer span {
      height: 3px;
      width: 100%;
      background: white;
      border-radius: 2px;
      transition: all 0.3s ease;
    }

    .hamburguer.ativo span:nth-child(1) {
      transform: rotate(45deg);
      position: relative;
      top: 7px;
    }

    .hamburguer.ativo span:nth-child(2) {
      opacity: 0;
    }

    .hamburguer.ativo span:nth-child(3) {
      transform: rotate(-45deg);
      position: relative;
      top: -7px;
    }

    /* Navegação */
    nav {
      background-color: var(--primary-color);
      display: none;
      flex-direction: column;
      padding: 1rem 1.5rem;
      position: absolute;
      top: 60px;
      right: 0;
      width: 200px;
      border-radius: 0 0 0 8px;
      box-shadow: 0 4px 8px var(--card-shadow);
    }

    nav a {
      color: white;
      text-decoration: none;
      padding: 0.7rem 0;
      font-weight: 600;
      transition: color 0.25s ease;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    nav a:last-child {
      border-bottom: none;
    }
    nav a:hover,
    nav a:focus {
      color: var(--accent-color);
      outline: none;
    }

    nav.ativo {
      display: flex;
    }

    /* Menu desktop */
    @media(min-width: 768px) {
      .hamburguer {
        display: none;
      }
      nav {
        position: static;
        display: flex !important;
        flex-direction: row;
        width: auto;
        padding: 0;
        box-shadow: none;
        border-radius: 0;
        background-color: transparent;
      }
      nav a {
        padding: 0;
        margin-left: 2rem;
        border: none;
        color: white;
        font-weight: 600;
      }
      nav a:hover,
      nav a:focus {
        color: var(--accent-color);
      }
    }

    /* Seção de posts */
    #posts {
      max-width: 900px;
      margin: 3rem auto;
      padding: 0 1rem;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
    }

    article.post-card {
      background: var(--card-bg);
      border-radius: 12px;
      box-shadow: 0 4px 12px var(--card-shadow);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    article.post-card:hover,
    article.post-card:focus-within {
      box-shadow: 0 8px 20px var(--card-shadow);
      transform: translateY(-5px);
    }

    article.post-card img {
      width: 100%;
      object-fit: cover;
      aspect-ratio: 16 / 9;
      transition: transform 0.3s ease;
      cursor: pointer;
      display: block;
    }
    article.post-card:hover img,
    article.post-card a:focus img {
      transform: scale(1.05);
    }

    article.post-card h2 {
      font-size: 1.25rem;
      margin: 1rem;
      color: var(--primary-color);
    }

    article.post-card p {
      flex-grow: 1;
      margin: 0 1rem 1rem;
      color: #555;
      font-size: 1rem;
      white-space: pre-line;
    }

    /* Mensagem quando não tem posts */
    .no-posts {
      text-align: center;
      font-style: italic;
      color: #888;
      margin-top: 3rem;
      font-size: 1.2rem;
    }
  </style>
</head>
<body>

  <header>
    <div class="logo" tabindex="0">MinhaMarca</div>
    <button class="hamburguer" onclick="toggleMenu()" id="menuBtn" aria-label="Toggle menu" aria-expanded="false" aria-controls="navMenu">
      <span></span>
      <span></span>
      <span></span>
    </button>
    <nav id="navMenu" role="navigation" aria-label="Menu principal">
      <a href="#">Início</a>
      <a href="#">Contato</a>
      <a href="login.php" style="color: var(--accent-color);" aria-current="page">Gerenciar Posts</a>
    </nav>
  </header>

  <main id="posts" aria-live="polite" aria-atomic="true" tabindex="-1">
    <?php if (count($posts) > 0): ?>
      <?php foreach ($posts as $post): ?>
        <article class="post-card" tabindex="0">
          <h2><?= htmlspecialchars($post['titulo']) ?></h2>
          <p><?= nl2br(htmlspecialchars($post['descricao'] ?? '')) ?></p>
          <a href="<?= htmlspecialchars($post['link']) ?>" target="_blank" rel="noopener noreferrer" tabindex="0" aria-label="Abrir link para <?= htmlspecialchars($post['titulo']) ?>">
            <img src="<?= htmlspecialchars($post['imagem']) ?>" alt="<?= htmlspecialchars($post['titulo']) ?>" loading="lazy" />
          </a>
        </article>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="no-posts">Nenhum post cadastrado.</p>
    <?php endif; ?>
  </main>

  <script>
    function toggleMenu() {
      const menu = document.getElementById("navMenu");
      const btn = document.getElementById("menuBtn");
      menu.classList.toggle("ativo");
      btn.classList.toggle("ativo");

      // Atualizar atributo aria-expanded para acessibilidade
      const expanded = btn.getAttribute("aria-expanded") === "true";
      btn.setAttribute("aria-expanded", !expanded);
    }

    // Fecha menu ao clicar fora
    document.addEventListener('click', function(e) {
      const menu = document.getElementById("navMenu");
      const btn = document.getElementById("menuBtn");
      if (!menu.contains(e.target) && !btn.contains(e.target)) {
        menu.classList.remove("ativo");
        btn.classList.remove("ativo");
        btn.setAttribute("aria-expanded", "false");
      }
    });
  </script>

</body>
</html>
