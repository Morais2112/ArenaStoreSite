document.addEventListener('DOMContentLoaded', () => {
    let slideIndex = 1;
    const slides = document.getElementsByClassName("slide");
    const dots = document.getElementsByClassName("dot");

    if (slides.length > 0) {
        mostrarSlides(slideIndex);

        window.mudarSlide = function (n) {
            mostrarSlides(slideIndex += n);
        };

        window.slideAtual = function (n) {
            mostrarSlides(slideIndex = n);
        };

        function mostrarSlides(n) {
            if (n > slides.length) slideIndex = 1;
            if (n < 1) slideIndex = slides.length;

            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
                slides[i].classList.remove("active");
            }
            for (let i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }

            slides[slideIndex - 1].style.display = "block";
            setTimeout(() => {
                slides[slideIndex - 1].classList.add("active");
            }, 10);
            
            if (dots[slideIndex - 1]) {
                dots[slideIndex - 1].className += " active";
            }
        }
    }

    const tracks = document.querySelectorAll('.product-grid');
    tracks.forEach(track => {
        track.addEventListener('scroll', () => {
            atualizarIndicadores(track);
        });
    });
});

window.proximaPagina = function(trackId) {
    const track = document.getElementById(trackId);
    if(track) {
        const width = track.clientWidth;
        track.scrollBy({ left: width, behavior: 'smooth' });
    }
};

window.paginaAnterior = function(trackId) {
    const track = document.getElementById(trackId);
    if(track) {
        const width = track.clientWidth;
        track.scrollBy({ left: -width, behavior: 'smooth' });
    }
};

window.irParaPagina = function(trackId, pageIndex) {
    const track = document.getElementById(trackId);
    if(track) {
        const width = track.clientWidth;
        track.scrollTo({ left: width * pageIndex, behavior: 'smooth' });
    }
};

function atualizarIndicadores(track) {
    const trackId = track.id;
    const indicatorsId = trackId.replace('track-', 'indicators-');
    const indicatorsContainer = document.getElementById(indicatorsId);

    if (!indicatorsContainer) return;

    const scrollLeft = track.scrollLeft;
    const width = track.clientWidth;
    const pageIndex = Math.round(scrollLeft / width);
    
    const dots = indicatorsContainer.querySelectorAll('.indicator');
    dots.forEach((dot, index) => {
        if (index === pageIndex) {
            dot.classList.add('active');
        } else {
            dot.classList.remove('active');
        }
    });
}