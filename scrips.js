// Número do WhatsApp (com código do país, sem +, só números)
const whatsappNumber = "558688172751";

// Seleciona todos os divs filhos diretos da .courses-grid
const courseDivs = document.querySelectorAll('.courses-grid > div');

courseDivs.forEach(div => {
  const cursoTexto = div.textContent.trim();

  // Monta a mensagem para o WhatsApp
  const mensagem = `olá denilson gostaria de saber mais do curso ${cursoTexto}`;

  // URL encode da mensagem
  const mensagemEncoded = encodeURIComponent(mensagem);

  // Monta o link do WhatsApp
  const whatsappLink = `https://wa.me/${whatsappNumber}?text=${mensagemEncoded}`;

  // Cria o elemento <a>
  const a = document.createElement('a');
  a.href = whatsappLink;
  a.target = "_blank";
  a.rel = "noopener noreferrer";
  a.textContent = cursoTexto;

  // Limpa o div e insere o link dentro
  div.textContent = "";
  div.appendChild(a);
});
