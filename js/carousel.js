console.log('Chargement de carousel.js');

class PropertyCarousel {
    constructor() {
        console.log("🚀 Le carousel se prepare...");
        
        this.container = document.querySelector('.carousel-container');
        if (!this.container) {
            console.error("❌ Pas de .carousel-container trouve !");
            return;
        }

        console.log("Conteneur trouve");
        
        this.cards = document.querySelectorAll('.property-card');
        console.log("✅ " + this.cards.length + " cartes trouvees");
       
        this.currentIndex = 0;
        this.isScrolling = false;
       
        this.touchStartX = 0;
        this.touchEndX = 0;
       
        this.init();
    }

    init() {
        console.log("⚙️ Initialisation du carousel...");
        
        this.updateCounter();
       
        this.setupScrollSnap();
        
        this.setupTouchEvents();
        
        this.setupKeyboardNavigation();
        
        this.setupIntersectionObserver();
        
        console.log("Carousel pret !");
    }
    
    setupScrollSnap() {
        this.container.style.scrollSnapType = 'x mandatory';
        this.container.style.overflowX = 'scroll';
        
        this.cards.forEach(card => {
            card.style.scrollSnapAlign = 'start';
        });
    }
    
    setupTouchEvents() {
        this.container.addEventListener('touchstart', (e) => {
            this.touchStartX = e.touches[0].clientX;
        }, { passive: true });

        this.container.addEventListener('touchend', (e) => {
            this.touchEndX = e.changedTouches[0].clientX;
            this.handleSwipe();
        });
    }
    
    handleSwipe() {
        const swipeDistance = this.touchStartX - this.touchEndX;
        const minSwipeDistance = 50;

        if (Math.abs(swipeDistance) < minSwipeDistance) return;

        if (swipeDistance > 0) {
            this.next();
        } else {
            this.previous();
        }
    }
   
    setupKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight') {
                e.preventDefault();
                this.next();
            } else if (e.key === 'ArrowLeft') {
                e.preventDefault();
                this.previous();
            }
        });
    }
  
    setupIntersectionObserver() {
        const options = {
            root: this.container,
            threshold: 0.5
        };

        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const index = Array.from(this.cards).indexOf(entry.target);
                    this.currentIndex = index;
                    this.updateCounter();
                    this.updateProgressBar();
                }
            });
        }, options);

        this.cards.forEach(card => this.observer.observe(card));
    }
    
    next() {
        if (this.isScrolling || this.currentIndex >= this.cards.length - 1) {
            console.log("Déjà à la dernière carte");
            return;
        }
        
        this.isScrolling = true;
        this.currentIndex++;
        this.scrollToCard(this.currentIndex);
    }

    previous() {
        if (this.isScrolling || this.currentIndex <= 0) {
            console.log("Déjà à la première carte");
            return;
        }
        
        this.isScrolling = true;
        this.currentIndex--;
        this.scrollToCard(this.currentIndex);
    }

    scrollToCard(index) {
        if (index < 0 || index >= this.cards.length) return;

        const targetCard = this.cards[index];
        targetCard.scrollIntoView({ 
            behavior: 'smooth',
            block: 'nearest',
            inline: 'start'
        });

        setTimeout(() => {
            this.isScrolling = false;
        }, 600);
    }

    goTo(index) {
        if (index < 0 || index >= this.cards.length) {
            console.warn("Index " + index + " hors limites");
            return;
        }

        this.currentIndex = index;
        this.scrollToCard(index);
    }
    
    updateCounter() {
        const counter = document.querySelector('.carousel-counter');
        
        if (counter) {
            const current = this.currentIndex + 1;
            const total = this.cards.length;
            counter.textContent = current + " / " + total;
            console.log("📍 Affichage : carte " + current + " sur " + total);
        }
    }
   
    updateProgressBar() {
        const progressBar = document.querySelector('.carousel-progress');
        
        if (progressBar) {
            const progress = ((this.currentIndex + 1) / this.cards.length) * 100;
            progressBar.style.width = progress + '%';
        }
    }
    
    getCurrentIndex() {
        return this.currentIndex;
    }

    getCurrentCard() {
        return this.cards[this.currentIndex];
    }

    getTotalCards() {
        return this.cards.length;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log("📄 Page chargee, creation du carousel...");
    const carousel = new PropertyCarousel();
    window.carousel = carousel;
    console.log("🎉 Tout est pret !");
    console.log("💡 Utilise les flèches ← → ou swipe gauche/droite");
});

console.log('✅ Fichier carousel.js charge completement');