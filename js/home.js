// Inizializzazione quando il documento è caricato
document.addEventListener('DOMContentLoaded', function() {
    // Inizializzazione di particles.js
    if (document.getElementById('particles-js')) {
        particlesJS('particles-js', {
            particles: {
                number: { value: 80, density: { enable: true, value_area: 800 } },
                color: { value: "#ffffff" },
                shape: { type: "circle" },
                opacity: { value: 0.5, random: true },
                size: { value: 3, random: true },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: "#ffffff",
                    opacity: 0.4,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 2,
                    direction: "none",
                    random: true,
                    straight: false,
                    out_mode: "out",
                    bounce: false
                }
            },
            interactivity: {
                detect_on: "canvas",
                events: {
                    onhover: { enable: true, mode: "grab" },
                    onclick: { enable: true, mode: "push" },
                    resize: true
                }
            },
            retina_detect: true
        });
    }
    
    // Animazione dell'effetto sound wave
    animateSoundWave();
    
    // Event listener per le card cliccabili
    setupCardNavigation();
    
    // Effetto di parallasse sui blob nelle feature card
    setupParallaxEffect();
});

// Animazione delle barre del sound wave
function animateSoundWave() {
    setInterval(() => {
        document.querySelectorAll('.music-wave .bar').forEach(bar => {
            const height = Math.floor(Math.random() * 60) + 20;
            bar.style.height = height + 'px';
        });
    }, 500);
}

// Setup della navigazione cliccando sulle card
function setupCardNavigation() {
    const featureCards = document.querySelectorAll('.feature-card');
    
    featureCards.forEach(card => {
        card.addEventListener('click', function() {
            const destination = this.getAttribute('data-destination');
            if (destination) {
                // Aggiungi un effetto di transizione prima della navigazione
                this.classList.add('clicked');
                
                // Naviga verso la destinazione dopo un breve ritardo
                setTimeout(() => {
                    window.location.href = destination;
                }, 300);
            }
        });
    });
}

// Effetto parallasse sui blob delle card
function setupParallaxEffect() {
    const cards = document.querySelectorAll('.feature-card');
    
    cards.forEach(card => {
        const blobs = card.querySelectorAll('.card-blob');
        
        card.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left; // x position within the element
            const y = e.clientY - rect.top;  // y position within the element
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const moveX = (x - centerX) / centerX * 15;
            const moveY = (y - centerY) / centerY * 15;
            
            blobs.forEach((blob, index) => {
                // Fai muovere i blob in direzioni opposte per un effetto migliore
                const factor = index % 2 === 0 ? 1 : -1;
                blob.style.transform = `translate(${moveX * factor}px, ${moveY * factor}px)`;
            });
        });
        
        // Resetta la posizione dei blob quando il mouse esce dalla card
        card.addEventListener('mouseleave', function() {
            blobs.forEach(blob => {
                blob.style.transform = 'translate(0, 0)';
            });
        });
    });
    
    // Animazione della sinestesia del giorno
    animateDailySynesthesia();
}

// Animazione della sinestesia del giorno
function animateDailySynesthesia() {
    const mainBlob = document.querySelector('.main-blob');
    
    if (mainBlob) {
        // Generiamo colori casuali che cambiano nel tempo
        setInterval(() => {
            const hue1 = Math.floor(Math.random() * 360);
            const hue2 = (hue1 + 40) % 360;
            const hue3 = (hue1 + 80) % 360;
            
            mainBlob.style.background = `
                linear-gradient(
                    135deg,
                    hsla(${hue1}, 70%, 50%, 0.7),
                    hsla(${hue2}, 70%, 50%, 0.7),
                    hsla(${hue3}, 70%, 50%, 0.7)
                )
            `;
        }, 5000);
    }
}