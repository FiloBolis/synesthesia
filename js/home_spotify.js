// Inizializzazione quando il documento è caricato
document.addEventListener('DOMContentLoaded', function() {
    // Inizializzazione di particles.js
    initParticles();

    // Effetti per le card
    setupCardEffects();
});

// Inizializzazione particles.js
function initParticles() {
    if (document.getElementById('particles-js')) {
        particlesJS('particles-js', {
            "particles": {
                "number": {
                    "value": 80,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": ["#2196f3", "#e91e63", "#00bcd4", "#673ab7"]
                },
                "shape": {
                    "type": "circle",
                    "stroke": {
                        "width": 0,
                        "color": "#000000"
                    },
                },
                "opacity": {
                    "value": 0.5,
                    "random": true,
                    "anim": {
                        "enable": true,
                        "speed": 1,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 3,
                    "random": true,
                    "anim": {
                        "enable": true,
                        "speed": 2,
                        "size_min": 0.1,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#2196f3",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 1,
                    "direction": "none",
                    "random": true,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false,
                    "attract": {
                        "enable": false,
                        "rotateX": 600,
                        "rotateY": 1200
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "grab"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "grab": {
                        "distance": 140,
                        "line_linked": {
                            "opacity": 1
                        }
                    },
                    "bubble": {
                        "distance": 400,
                        "size": 40,
                        "duration": 2,
                        "opacity": 8,
                        "speed": 3
                    },
                    "repulse": {
                        "distance": 200,
                        "duration": 0.4
                    },
                    "push": {
                        "particles_nb": 4
                    },
                    "remove": {
                        "particles_nb": 2
                    }
                }
            },
            "retina_detect": true
        });
    }
}

// Effetti per le card
function setupCardEffects() {
    const cards = document.querySelectorAll('.hub-card');
    
    cards.forEach(card => {
        // Effetto hover 3D
        card.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const xc = rect.width / 2;
            const yc = rect.height / 2;
            
            const dx = x - xc;
            const dy = y - yc;
            
            // Limita la rotazione a un massimo di 10 gradi
            const tiltX = -(dy / yc) * 10;
            const tiltY = (dx / xc) * 10;
            
            this.style.transform = `perspective(1000px) rotateX(${tiltX}deg) rotateY(${tiltY}deg) translateY(-10px)`;
            
            // Muovi le "blobs" in base alla posizione del mouse
            const blobs = this.querySelectorAll('.card-blob');
            blobs.forEach((blob, index) => {
                const factor = index % 2 === 0 ? 1 : -1;
                blob.style.transform = `translate(${dx * 0.05 * factor}px, ${dy * 0.05 * factor}px) scale(1.2)`;
            });
        });
        
        // Reset al mouseout
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            
            const blobs = this.querySelectorAll('.card-blob');
            blobs.forEach(blob => {
                blob.style.transform = 'scale(1)';
            });
        });
    });
}

// Funzione per convertire il mood in italiano
function moodToItalian(mood) {
    const translations = {
        'happy': 'Felice',
        'relaxed': 'Rilassato',
        'energetic': 'Energico',
        'melancholic': 'Malinconico',
        'focused': 'Concentrato',
        'romantic': 'Romantico',
        'party': 'Festa',
        'chill': 'Chill'
    };
    
    return translations[mood] || mood;
}

// Mostra una notifica
function showNotification(message) {
    // Crea l'elemento notifica
    const notification = document.createElement('div');
    notification.className = 'toast align-items-center text-white bg-success';
    notification.setAttribute('role', 'alert');
    notification.setAttribute('aria-live', 'assertive');
    notification.setAttribute('aria-atomic', 'true');
    
    notification.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i> ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    // Crea un contenitore se non esiste
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    // Aggiungi la notifica al contenitore
    toastContainer.appendChild(notification);
    
    // Inizializza e mostra la notifica con Bootstrap
    const toast = new bootstrap.Toast(notification, {
        autohide: true,
        delay: 3000
    });
    toast.show();
    
    // Rimuovi dopo che è stata nascosta
    notification.addEventListener('hidden.bs.toast', function () {
        notification.remove();
    });
}

async function getPlaylists(id_utente) {
    let url = "ajax/getPlaylists.php?id=" + id_utente;
    let response = await fetch(url);
    if(!response.ok)
        throw new Error("Errore durante la fetch");
    let txt = await response.text();
    console.log(txt);
    let datiRicevuti = JSON.parse(txt);
    console.log(datiRicevuti);
    if(datiRicevuti["status"] == "ERR")
        alert(datiRicevuti["msg"]);
    else if(datiRicevuti["status"] == "OK")
        window.location.href = "playlists.php";
}

async function getTracks(max_tracks) {
    let url = "ajax/getTracks.php?max_tracks=" + max_tracks;
    let response = await fetch(url);
    if(!response.ok)
        throw new Error("Errore durante la fetch");
    let txt = await response.text();
    console.log(txt);
    let datiRicevuti = JSON.parse(txt);
    console.log(datiRicevuti);
    if(datiRicevuti["status"] == "ERR")
        alert(datiRicevuti["msg"]);
    else if(datiRicevuti["status"] == "OK" && max_tracks < 500)
        window.location.href = "pickaTune.php";
}