document.getElementById('busca').addEventListener('keyup', function () {
    const termo = this.value.toLowerCase();
    const tutoriais = document.querySelectorAll('#lista-tutoriais li');

    tutoriais.forEach(tutorial => {
        const texto = tutorial.textContent.toLowerCase();
        tutorial.style.display = texto.includes(termo) ? '' : 'none';
    });
});
