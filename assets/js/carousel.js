class PropertyCarousel {

    constructor() {
        console.log("Le carousel se prépare...");

        this.container = document.querySelector('.carousel-container');

        if (!this.container) {
            console.error("Pas de .carousel-container trouvé !");
            return;
        }

        console.log("Conteneur trouvé :", this.container);

        this.cards = document.querySelectorAll('.proprety-card');
        console.log(`${this.cards.length} cartes trouvées`);

        this.currentIndex = 0;

        this.init();
    }

    init() {
        console.log("nitialisation du carousel...");

        this.updateCounter();
        console.log("Carousel pret");
    }

    updateCounter() {
        const counter = document.querySelector('.carousel-counter');

        if (counter) {

            const current = this.currentIndex + 1;
            const total = this.cards.length;

            counter.textContent = `${current} / ${total}`;
            console.log(`Affichage : carte ${current} sur ${total}`);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log("Page chargée, création du carousel...");

    const carousel = new PropertyCarousel();

    window.carousel = carousel;

    console.log("Tout est pret, taper 'carousel' dans la console");
});