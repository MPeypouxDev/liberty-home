console.log('Chargement de carousel');

class PropertyCarousel {
    constructor() {
        console.log("Le carousel se prepare...");
        this.container = document.querySelector('.carousel-container');
        if (!this.container) {
            console.error("Pas de carousel-container trouve !");
            return;
        }

        console.log("Conteneur trouve :", this.container);
        this.cards = document.querySelectorAll('.property-card');
        console.log(this.cards.length + " cartes trouvees");
        this.currentIndex = 0;
        this.init();
    }

    init() {
        console.log("Initialisation du carousel...");
        this.updateCounter();
        console.log("Carousel pret !");
    }
    updateCounter() {
        const counter = document.querySelector('carousel-counter');
        if (counter) {
            const current = this.currentIndex + 1;
            const total = this.cards.length;
            counter.textContent = current + " / " + total;
            console.log("Affichage : carte " + current + " sur " + total);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log("Page chargee, creation du carousel...");
    const carousel = new PropertyCarousel();
    window.carousel = carousel;
    console.log("Tout est pret !");
});

console.log('Fichier carousel charge completement');