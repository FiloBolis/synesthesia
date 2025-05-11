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
    
    // Animazione del cerchio centrale
    animateCentralCircle();
    
    // Effetto hover sulla card di Spotify
    setupCardHoverEffects();
    
    // Effetto pulsante connetti
    setupConnectButtonEffect();
});

// Animazione delle barre del sound wave
function animateSoundWave() {
    setInterval(() => {
        document.querySelectorAll('.wave-bar').forEach(bar => {
            const height = Math.floor(Math.random() * 60) + 10;
            bar.style.height = height + 'px';
        });
    }, 300);
}

// Animazione del cerchio centrale
function animateCentralCircle() {
    const centralCircle = document.querySelector('.central-circle');
    
    if (centralCircle) {
        // Generiamo colori casuali che cambiano nel tempo
        setInterval(() => {
            const hue1 = Math.floor(Math.random() * 360);
            const hue2 = (hue1 + 40) % 360;
            const hue3 = (hue1 + 80) % 360;
            
            centralCircle.style.background = `
                linear-gradient(
                    135deg,
                    hsla(${hue1}, 70%, 50%, 0.7),
                    hsla(${hue2}, 70%, 50%, 0.7),
                    hsla(${hue3}, 70%, 50%, 0.7)
                )
            `;
        }, 5000);
        
        // Aggiungi animazione di respiro al cerchio
        let scale = 1;
        let growing = true;
        
        setInterval(() => {
            if (growing) {
                scale += 0.01;
                if (scale >= 1.1) growing = false;
            } else {
                scale -= 0.01;
                if (scale <= 0.9) growing = true;
            }
            
            centralCircle.style.transform = `scale(${scale})`;
        }, 50);
    }
}

// Setup dell'effetto hover sulla card di Spotify
function setupCardHoverEffects() {
    const connectCard = document.querySelector('.connect-card');
    
    if (connectCard) {
        const blobs = connectCard.querySelectorAll('.card-blob');
        
        connectCard.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const moveX = (x - centerX) / centerX * 20;
            const moveY = (y - centerY) / centerY * 20;
            
            blobs.forEach((blob, index) => {
                // Fai muovere i blob in direzioni opposte per un effetto migliore
                const factor = index % 2 === 0 ? 1 : -1;
                blob.style.transform = `translate(${moveX * factor}px, ${moveY * factor}px)`;
            });
        });
        
        // Resetta la posizione dei blob quando il mouse esce dalla card
        connectCard.addEventListener('mouseleave', function() {
            blobs.forEach(blob => {
                blob.style.transform = 'translate(0, 0)';
            });
        });
    }
    
    // Effetto hover sui benefit item
    const benefitItems = document.querySelectorAll('.benefit-item');
    
    benefitItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.querySelector('.benefit-icon').style.transform = 'scale(1.1)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.querySelector('.benefit-icon').style.transform = 'scale(1)';
        });
    });
}

// Setup dell'effetto sul pulsante connetti
function setupConnectButtonEffect() {
    const connectButton = document.querySelector('.btn-spotify');
    
    if (connectButton) {
        connectButton.addEventListener('mousedown', function() {
            this.style.transform = 'scale(0.95)';
        });
        
        connectButton.addEventListener('mouseup', function() {
            this.style.transform = 'translateY(-3px)';
        });
        
        connectButton.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
        
        // Aggiungi un effetto speciale al click
        connectButton.addEventListener('click', function(e) {
            // Crea un effetto ripple
            const ripple = document.createElement('span');
            ripple.classList.add('ripple-effect');
            
            const button = this;
            const diameter = Math.max(button.clientWidth, button.clientHeight);
            const radius = diameter / 2;
            
            ripple.style.width = ripple.style.height = `${diameter}px`;
            ripple.style.left = `${e.clientX - button.getBoundingClientRect().left - radius}px`;
            ripple.style.top = `${e.clientY - button.getBoundingClientRect().top - radius}px`;
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.backgroundColor = 'rgba(255, 255, 255, 0.3)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            
            button.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
        
        // Aggiungi animazione CSS per l'effetto ripple
        const style = document.createElement('style');
        style.innerHTML = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            .btn-spotify {
                position: relative;
                overflow: hidden;
            }
        `;
        document.head.appendChild(style);
    }
}

async function connetti() {
    let url = "ajax/spotify_connect.php";
    let response = await fetch(url);
    if(!response.ok) 
        throw new Error("errore durante la fetch");
    let txt = await response.text();
    console.log(txt);
    let datiRicevuti = JSON.parse(txt);
    console.log(datiRicevuti);
    if(datiRicevuti["status"] == "ERR")
        alert("Errore durante la connessione a Spotify. Riprova più tardi.");
    else
        window.location.href = datiRicevuti["msg"];
}