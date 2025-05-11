// Gestione delle playlist quando il documento è caricato
document.addEventListener('DOMContentLoaded', function() {
    // Inizializza il filtro delle playlist
    initPlaylistFilter();
    
    // Inizializza la ricerca delle playlist
    initPlaylistSearch();
    
    // Aggiungi effetti hover alle playlist
    initPlaylistHoverEffects();
});

// Inizializza il filtro delle playlist
function initPlaylistFilter() {
    const filterButtons = document.querySelectorAll('.btn-filter');
    const playlistCards = document.querySelectorAll('.playlist-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Rimuovi la classe active da tutti i pulsanti
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Aggiungi la classe active al pulsante cliccato
            this.classList.add('active');
            
            // Ottieni il valore del filtro
            const filter = this.getAttribute('data-filter');
            
            // Filtra le playlist
            playlistCards.forEach(card => {
                if (filter === 'all') {
                    card.style.display = 'block';
                } else if (card.classList.contains(filter)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Controlla se ci sono risultati
            checkResults();
        });
    });
}

// Controlla se ci sono risultati e mostra/nasconde il messaggio
function checkResults() {
    const playlistCards = document.querySelectorAll('.playlist-card');
    const noResults = document.getElementById('noResults');
    
    let visibleCards = 0;
    
    playlistCards.forEach(card => {
        if (card.style.display !== 'none') {
            visibleCards++;
        }
    });
    
    if (visibleCards === 0) {
        noResults.style.display = 'block';
    } else {
        noResults.style.display = 'none';
    }
}

// Inizializza gli effetti hover sulle playlist
function initPlaylistHoverEffects() {
    const playlistCards = document.querySelectorAll('.playlist-card');
    
    playlistCards.forEach(card => {
        // Aggiungi l'effetto di movimento 3D
        card.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const xc = rect.width / 2;
            const yc = rect.height / 2;
            
            const dx = x - xc;
            const dy = y - yc;
            
            // Limita la rotazione a un massimo di 5 gradi
            const tiltX = -(dy / yc) * 5;
            const tiltY = (dx / xc) * 5;
            
            this.style.transform = `perspective(1000px) rotateX(${tiltX}deg) rotateY(${tiltY}deg) translateY(-10px)`;
        });
        
        // Reset al mouseout
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
}

// Funzione per riprodurre una playlist (simulata, in realtà aprirebbe Spotify)
function playPlaylist(playlistId) {
    // In un'applicazione reale, questa funzione potrebbe aprire Spotify o avviare la riproduzione
    console.log(`Riproduzione playlist: ${playlistId}`);
    
    // Qui potresti usare l'API Web Playback SDK di Spotify per la riproduzione reale
    // Ma per ora apriamo semplicemente una nuova finestra con la playlist su Spotify
    window.open(`https://open.spotify.com/playlist/${playlistId}`, '_blank');
}

// Funzione per mostrare una notifica
function showPlaylistNotification(message) {
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