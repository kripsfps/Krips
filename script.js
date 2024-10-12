document.querySelector('.discord-btn').addEventListener('click', function() {
    window.open('https://discord.gg/kripsoptimization', '_blank');
});

window.onscroll = function() {shrinkHeader()};

function shrinkHeader() {
    const header = document.querySelector("header");
    if (document.documentElement.scrollTop > 50) {
        header.classList.add("shrink");
    } else {
        header.classList.remove("shrink");
    }
}


document.querySelectorAll('.scroll-link').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault(); // Impede o comportamento padrão de pular diretamente

        // Anima o rolar suave até a seção correspondente
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth' // Ativa a animação de rolar suave
        });
    });
});