// pickaTune.js - Gestione dell'interfaccia swipe per canzoni

document.addEventListener('DOMContentLoaded', async function() {
    // Inizializza l'interfaccia di pickaTune
    initPickaTune();
    
    // Funzione per evitare che il drag di un iframe influisca sul swipe
    preventIframeDrag();

    // Attendi che la Promise venga risolta prima di procedere
    try {
        idPlaylist = await createPlaylist();
        console.log("Playlist creata con ID:", idPlaylist);
    } catch (error) {
        console.error("Errore nella creazione della playlist:", error);
        showNotification("Errore nella creazione della playlist", "error");
    }
});

// Variabili globali
let idPlaylist = "";
let currentIndex = 0;
let swipedTracks = {
    liked: [],
    disliked: []
};

// Inizializza l'interfaccia di pickaTune
function initPickaTune() {
    // Aggiorna il conteggio totale delle carte
    document.getElementById('totalCards').textContent = tracks.length;
    
    // Inizializza l'iframe Spotify una volta sola
    initSpotifyEmbed();
    
    // Aggiorna la traccia corrente
    updateTrack();
    
    // Inizializza i pulsanti di azione
    initActionButtons();
    
    // Aggiorna la barra di avanzamento
    updateProgress();
}

// Inizializza l'iframe Spotify
function initSpotifyEmbed() {
    const swipeArea = document.getElementById('swipeArea');
    
    // Pulisci l'area di swipe
    swipeArea.innerHTML = '';
    
    // Crea la card della traccia
    const card = document.createElement('div');
    card.className = 'track-card';
    
    // Aggiungi i bordi per il feedback visivo di swipe
    const leftBorder = document.createElement('div');
    leftBorder.className = 'card-border left';
    leftBorder.innerHTML = '<i class="fas fa-times"></i>';
    card.appendChild(leftBorder);
    
    const rightBorder = document.createElement('div');
    rightBorder.className = 'card-border right';
    rightBorder.innerHTML = '<i class="fas fa-check"></i>';
    card.appendChild(rightBorder);
    
    // Crea il contenitore per le informazioni della traccia
    const trackInfo = document.createElement('div');
    trackInfo.className = 'track-info';
    trackInfo.innerHTML = `
        <h3 class="track-name"></h3>
        <p class="track-artist"></p>
    `;
    card.appendChild(trackInfo);
    
    // Crea il contenitore per il player Spotify
    const playerContainer = document.createElement('div');
    playerContainer.className = 'track-player';
    
    // Crea l'iframe (una volta sola)
    const iframe = document.createElement('iframe');
    iframe.id = 'spotify-embed';
    iframe.width = '100%';
    iframe.height = '380';
    iframe.frameBorder = '0';
    iframe.allow = 'autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture';
    
    playerContainer.appendChild(iframe);
    card.appendChild(playerContainer);
    
    // Aggiungi la card all'area di swipe
    swipeArea.appendChild(card);
    
    // Inizializza la gesture di swipe
    initSwipeGesture(card);
}

// Aggiorna la traccia corrente nell'iframe
function updateTrack() {
    if (currentIndex >= tracks.length) {
        showResults();
        return;
    }
    
    const track = tracks[currentIndex];
    const card = document.querySelector('.track-card');
    
    // Imposta l'ID della traccia
    card.setAttribute('data-id', track.id);
    
    // Aggiorna le informazioni della traccia
    card.querySelector('.track-name').textContent = track.name;
    card.querySelector('.track-artist').textContent = track.artist;
    
    // Aggiorna l'iframe Spotify con la nuova traccia
    const iframe = document.getElementById('spotify-embed');
    iframe.src = `https://open.spotify.com/embed/track/${track.id}?utm_source=generator`;
    
    // Reset della posizione e delle classi
    card.style.transform = '';
    card.classList.remove('dragging-right', 'dragging-left', 'swiped-right', 'swiped-left');
    
    // Aggiorna la posizione corrente
    document.getElementById('currentPosition').textContent = currentIndex + 1;
    
    // Aggiorna la barra di avanzamento
    updateProgress();
}

// Inizializza la gesture di swipe usando Hammer.js
function initSwipeGesture(card) {
    const hammer = new Hammer(card);
    
    // Configurazione per rilevare il panning
    hammer.add(new Hammer.Pan({ 
        direction: Hammer.DIRECTION_HORIZONTAL,
        threshold: 10
    }));
    
    let posX = 0;
    let posY = 0;
    let rotation = 0;
    
    // Gestisci gli eventi di panning
    hammer.on('panstart panmove panend pancancel', function(event) {
        card = event.target.closest('.track-card');
        
        if (event.type === 'panstart') {
            // Inizializzazione del drag
            posX = 0;
            posY = 0;
            rotation = 0;
        } else if (event.type === 'panmove') {
            // Movimento durante il drag
            posX = event.deltaX;
            posY = event.deltaY;
            
            // Calcola la rotazione in base alla distanza x (max ±20 gradi)
            rotation = (posX / window.innerWidth) * 20;
            
            // Applica la trasformazione
            card.style.transform = `translate(${posX}px, ${posY}px) rotate(${rotation}deg)`;
            
            // Aggiungi classi di feedback visivo
            if (posX > 50) {
                card.classList.add('dragging-right');
                card.classList.remove('dragging-left');
            } else if (posX < -50) {
                card.classList.add('dragging-left');
                card.classList.remove('dragging-right');
            } else {
                card.classList.remove('dragging-right', 'dragging-left');
            }
        } else if (event.type === 'panend' || event.type === 'pancancel') {
            // Rilascio del drag
            const threshold = window.innerWidth / 3; // Soglia per considerare lo swipe
            
            if (posX > threshold) {
                // Swipe a destra (like)
                likeTrack(card);
            } else if (posX < -threshold) {
                // Swipe a sinistra (dislike)
                dislikeTrack(card);
            } else {
                // Ritorna alla posizione originale
                card.style.transform = '';
                card.classList.remove('dragging-right', 'dragging-left');
            }
        }
    });
}

// Funzione per il like di una traccia
async function likeTrack(card) {
    card.classList.add('swiped-right');
    const trackId = card.getAttribute('data-id');
    
    // Aggiungi alle tracce piaciute
    swipedTracks.liked.push(trackId);
    
    // Aggiungi effetto visivo di swipe
    card.style.transform = `translate(${window.innerWidth}px, 0) rotate(30deg)`;

    // Verifica che idPlaylist sia stato inizializzato correttamente
    if (idPlaylist) {
        try {
            await addTrack(trackId, idPlaylist);
        } catch (error) {
            console.error("Errore nell'aggiunta della traccia:", error);
            showNotification("Errore nell'aggiunta della traccia alla playlist", "error");
        }
    } else {
        console.error("ID Playlist non disponibile");
        showNotification("Impossibile aggiungere la traccia: playlist non disponibile", "error");
    }
    
    // Passa alla prossima traccia dopo l'animazione
    setTimeout(() => {
        currentIndex++;
        updateTrack();
    }, 300);
}

// Funzione per il dislike di una traccia
function dislikeTrack(card) {
    card.classList.add('swiped-left');
    const trackId = card.getAttribute('data-id');
    
    // Aggiungi alle tracce non piaciute
    swipedTracks.disliked.push(trackId);
    
    // Aggiungi effetto visivo di swipe
    card.style.transform = `translate(-${window.innerWidth}px, 0) rotate(-30deg)`;
    
    // Passa alla prossima traccia dopo l'animazione
    setTimeout(() => {
        currentIndex++;
        updateTrack();
    }, 300);
}

// Inizializza i pulsanti di azione
function initActionButtons() {
    // Pulsante dislike
    document.getElementById('dislikeBtn').addEventListener('click', function() {
        const currentCard = document.querySelector('.track-card');
        if (currentCard) {
            dislikeTrack(currentCard);
        }
    });
    
    // Pulsante like
    document.getElementById('likeBtn').addEventListener('click', function() {
        const currentCard = document.querySelector('.track-card');
        if (currentCard) {
            likeTrack(currentCard);
        }
    });
}

// Aggiorna la barra di avanzamento
function updateProgress() {
    const progressBar = document.getElementById('progressBar');
    const totalCards = tracks.length;
    
    // Aggiorna la barra di avanzamento
    const percentage = (currentIndex / totalCards) * 100;
    progressBar.style.width = `${percentage}%`;
}

// Mostra una notifica
function showNotification(message, type = 'success') {
    // Crea l'elemento notifica
    const notification = document.createElement('div');
    notification.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'warning'}`;
    notification.setAttribute('role', 'alert');
    notification.setAttribute('aria-live', 'assertive');
    notification.setAttribute('aria-atomic', 'true');
    
    notification.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i> ${message}
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

// Funzione per evitare che il drag di un iframe influisca sul swipe
function preventIframeDrag() {
    document.addEventListener('DOMNodeInserted', function(e) {
        if (e.target.tagName === 'IFRAME') {
            e.target.addEventListener('mousedown', function(e) {
                e.stopPropagation();
            });
            
            e.target.addEventListener('touchstart', function(e) {
                e.stopPropagation();
            });
        }
    });
}

async function createPlaylist() {
    try {
        let url = "ajax/createPlaylist.php";
        let response = await fetch(url);
        if (!response.ok)
            throw new Error("Errore durante la fetch");
        
        let txt = await response.text();
        console.log(txt);
        
        let datiRicevuti;
        try {
            datiRicevuti = JSON.parse(txt);
        } catch (e) {
            console.error("Errore nel parsing JSON:", e);
            console.error("Testo ricevuto:", txt);
            throw new Error("Risposta non valida dal server");
        }
        
        console.log(datiRicevuti);
        if (datiRicevuti["status"] == "ERR") {
            alert(datiRicevuti["msg"]);
            return null;
        }
        return datiRicevuti["playlist_id"];
    } catch (error) {
        console.error("Errore in createPlaylist:", error);
        throw error;
    }
}

async function addTrack(songId, playlistId) {
    try {
        // Verifica che playlistId sia una stringa valida
        if (!playlistId || typeof playlistId !== 'string') {
            console.error("ID playlist non valido:", playlistId);
            throw new Error("ID playlist non valido");
        }
        
        let url = "ajax/addTrack.php?songId=" + songId + "&playlistId=" + playlistId;
        let response = await fetch(url);
        if (!response.ok)
            throw new Error("Errore durante la fetch");
        
        let txt = await response.text();
        console.log(txt);
        
        let datiRicevuti;
        try {
            datiRicevuti = JSON.parse(txt);
        } catch (e) {
            console.error("Errore nel parsing JSON:", e);
            console.error("Testo ricevuto:", txt);
            throw new Error("Risposta non valida dal server");
        }
        
        console.log(datiRicevuti);
        if (datiRicevuti["status"] == "ERR") {
            alert(datiRicevuti["msg"]);
            throw new Error(datiRicevuti["msg"]);
        }
        
        return datiRicevuti;
    } catch (error) {
        console.error("Errore in addTrack:", error);
        throw error;
    }
}

function showResults() {
    // Implementa la visualizzazione dei risultati
    // Questa funzione è chiamata quando l'utente ha valutato tutte le tracce
    console.log("Tracce piaciute:", swipedTracks.liked);
    console.log("Tracce non piaciute:", swipedTracks.disliked);
    
    // Puoi mostrare un messaggio o reindirizzare l'utente
    alert("Hai valutato tutte le canzoni! Grazie per aver usato pickaTune!");
}