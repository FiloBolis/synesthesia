// Inizializzazione quando il documento è caricato
document.addEventListener('DOMContentLoaded', function () {
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

    // Inizializzazione dei filtri del guardaroba
    initializeFilters();

    // Event listener per le card cliccabili
    setupItemCards();

    // Animazione della palette di colori
    animateColorPalette();

    // Simulazione di caricamento iniziale dei dati del guardaroba
    loadInitialWardrobeItems();
});

// Inizializzazione dei filtri
function initializeFilters() {
    const filterButtons = document.querySelectorAll('.btn-filter');
    const searchInput = document.getElementById('searchItems');

    // Event listener per i pulsanti di filtro
    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Rimuove la classe active da tutti i pulsanti
            filterButtons.forEach(btn => btn.classList.remove('active'));

            // Aggiunge la classe active al pulsante cliccato
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');
            filterItems(filterValue, searchInput.value);
        });
    });

    // Event listener per la ricerca
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const activeFilter = document.querySelector('.btn-filter.active');
            const filterValue = activeFilter ? activeFilter.getAttribute('data-filter') : 'all';
            filterItems(filterValue, this.value);
        });
    }
}

// Filtraggio degli elementi del guardaroba
function filterItems(category, searchText) {
    const items = document.querySelectorAll('.item-card');

    items.forEach(item => {
        const itemCategory = item.getAttribute('data-category');
        const itemTitle = item.querySelector('.item-title').textContent.toLowerCase();
        const matchesCategory = category === 'all' || itemCategory === category;
        const matchesSearch = !searchText || itemTitle.includes(searchText.toLowerCase());

        if (matchesCategory && matchesSearch) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Configurazione delle card dei capi
function setupItemCards() {
    // Delegazione degli eventi per rendere cliccabili le card dei capi
    document.addEventListener('click', function (e) {
        const itemCard = e.target.closest('.item-card');
        if (itemCard) {
            const itemId = itemCard.getAttribute('data-id');
            openItemDetails(itemId);
        }
    });

    // Event listener per il pulsante di eliminazione
    const btnDeleteItem = document.querySelector('.btn-delete-item');
    if (btnDeleteItem) {
        btnDeleteItem.addEventListener('click', function () {
            // Qui andrà il codice per la conferma dell'eliminazione
            if (confirm('Sei sicuro di voler eliminare questo capo?')) {
                // Logica per eliminare il capo
                const itemId = document.getElementById('viewItemModal').getAttribute('data-item-id');
                deleteItem(itemId);
            }
        });
    }
}

// Aprire i dettagli di un capo
function openItemDetails(itemId) {
    // Recupera i dettagli del capo
    getItemDetails(itemId).then(itemData => {
        if (itemData) {
            const viewModal = document.getElementById('viewItemModal');
            const detailsContainer = document.getElementById('viewItemDetails');

            // Memorizza l'ID dell'item corrente nel modal
            viewModal.setAttribute('data-item-id', itemId);

            // Gestisci il caso in cui img_path sia vuoto
            const imgPath = itemData.img_path;

            // Popola i dettagli
            detailsContainer.innerHTML = `
                <div class="item-detail-image" style="background-image: url(${imgPath})"></div>
                <h4 class="item-detail-title">${itemData.nome}</h4>
                <div class="item-detail-info">
                    <div class="detail-row">
                        <span class="detail-label">Categoria:</span>
                        <span class="detail-value">${getCategoryName(itemData.categoria)}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Colore:</span>
                        <span class="detail-value color-tag">
                            <span class="color-dot" style="background-color: ${itemData.colore};"></span>
                            ${itemData.colore}
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Stile:</span>
                        <span class="detail-value">${itemData.stile}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Descrizione:</span>
                    </div>
                    <p class="item-description">${itemData.descrizione}</p>
                </div>
            `;

            // Mostra il modal
            const modal = new bootstrap.Modal(viewModal);
            modal.show();
        }
    }).catch(error => {
        console.error('Errore nel recupero dei dettagli:', error);
    });
}

// Ottenere i dettagli di un capo
async function getItemDetails(itemId) {
    try {
        let url = "ajax/getWardrobeItems.php";
        let response = await fetch(url);

        if (!response.ok)
            throw new Error("fetch non riuscita");

        let txt = await response.text();
        let datiRicevuti = JSON.parse(txt);

        if (datiRicevuti["status"] == "ERR") {
            console.error("Errore ricevuto dal server:", datiRicevuti.msg);
            return null;
        }
        else if (datiRicevuti["status"] == "OK") {
            const vestiti = datiRicevuti["vestiti"];
            // Cerca e restituisci il vestito con l'ID corrispondente
            const vestitoTrovato = vestiti.find(vestito => vestito.id == itemId);
            return vestitoTrovato || null;
        }
    } catch (error) {
        console.error("Errore nel getItemDetails:", error);
        return null;
    }
}

// Simulazione: eliminare un capo
async function deleteItem(itemId) {
    let url = "ajax/deleteWardrobeItem.php?id=" + itemId;
    let response = await fetch(url);
    if (!response.ok)
        throw new Error("fetch non riuscita");

    let txt = await response.text();
    console.log(txt);
    let datiRicevuti = JSON.parse(txt);
    console.log(datiRicevuti);

    if (datiRicevuti["status"] == "ERR")
        showToast('Errore', datiRicevuti["msg"], 'danger');
    else if (datiRicevuti["status"] == "OK") {
        // Chiudi il modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('viewItemModal'));
        modal.hide();

        // Mostra un messaggio di successo
        showToast('Successo', datiRicevuti["msg"], 'success');

        // Ricarica gli elementi del guardaroba
        loadInitialWardrobeItems();

        location.reload();
    }
}

// Animazione della palette di colori
function animateColorPalette() {
    const colorBubbles = document.querySelectorAll('.color-bubble');

    colorBubbles.forEach((bubble, index) => {
        // Aggiungi un effetto di animazione casuale a ciascun bubble
        const delay = index * 0.2;
        bubble.style.animation = `pulse 3s infinite ${delay}s`;
    });
}

// Caricamento iniziale degli elementi del guardaroba
async function loadInitialWardrobeItems() {
    const container = document.getElementById('wardrobeItems');

    if (!container) return;

    try {
        let url = "ajax/getWardrobeItems.php";
        let response = await fetch(url);

        if (!response.ok)
            throw new Error("fetch non riuscita");

        let txt = await response.text();
        console.log(txt);
        let datiRicevuti = JSON.parse(txt);
        console.log(datiRicevuti);

        if (datiRicevuti["status"] == "ERR") {
            console.error("Errore nel caricamento dei vestiti:", datiRicevuti.msg);
            return;
        }
        else if (datiRicevuti["status"] == "OK") {
            const vestiti = datiRicevuti["vestiti"];

            if (vestiti.length === 0) {
                container.innerHTML = '<div class="alert alert-info">Nessun capo nel guardaroba</div>';
                return;
            }

            // Svuota il container prima di aggiungere i nuovi elementi
            container.innerHTML = '';

            // Aggiungi ogni vestito al container
            vestiti.forEach(vestito => {
                // Gestisci il caso in cui img_path sia vuoto
                const imgPath = vestito.img_path;

                const itemElement = document.createElement('div');
                itemElement.className = 'item-card';
                itemElement.setAttribute('data-id', vestito.id);
                itemElement.setAttribute('data-category', vestito.categoria);

                itemElement.innerHTML = `
                    <div class="item-image" style="background-image: url(${imgPath})">
                        <span class="item-category">${getCategoryName(vestito.categoria)}</span>
                    </div>
                    <div class="item-info">
                        <h3 class="item-title">${vestito.nome}</h3>
                        <div class="item-attributes">
                            <span class="item-attribute">
                                <span class="color-dot" style="background-color: ${vestito.colore};"></span>
                                ${vestito.colore}
                            </span>
                            <span class="item-attribute">
                                <i class="fas fa-tshirt"></i> ${vestito.stile}
                            </span>
                        </div>
                    </div>
                `;

                // Aggiungi al container
                container.appendChild(itemElement);
            });

            // Aggiorna le statistiche
            updateWardrobeStats(vestiti);
        }
    } catch (error) {
        console.error("Errore nel caricamento dei vestiti:", error);
        container.innerHTML = '<div class="alert alert-danger">Errore nel caricamento dei vestiti</div>';
    }
}

// Ottenere il nome della categoria
function getCategoryName(category) {
    const categories = {
        'top': 'Top',
        'bottom': 'Bottom',
        'footwear': 'Calzature',
        'calzature': 'Calzature',
        'accessories': 'Accessori'
    };

    return categories[category] || category;
}

// Aggiornamento delle statistiche del guardaroba
function updateWardrobeStats(items) {
    const totalItemsElement = document.getElementById('totalItems');
    const dominantColorElement = document.getElementById('dominantColor');
    const dominantStyleElement = document.getElementById('dominantStyle');

    // Se non sono stati passati gli items, conta quelli presenti nel DOM
    if (!items) {
        const itemElements = document.querySelectorAll('.item-card');
        if (totalItemsElement) {
            totalItemsElement.textContent = itemElements.length;
        }
        return;
    }

    // Aggiorna il conteggio totale
    if (totalItemsElement) {
        totalItemsElement.textContent = items.length;
    }

    // Calcola il colore dominante e lo stile dominante (se ci sono elementi)
    if (items.length > 0 && dominantColorElement && dominantStyleElement) {
        // Conteggio dei colori
        const colorCount = {};
        const styleCount = {};

        items.forEach(item => {
            // Conta i colori
            if (item.colore) {
                colorCount[item.colore] = (colorCount[item.colore] || 0) + 1;
            }

            // Conta gli stili
            if (item.stile) {
                styleCount[item.stile] = (styleCount[item.stile] || 0) + 1;
            }
        });

        // Trova il colore più frequente
        let maxColor = '';
        let maxColorCount = 0;
        for (const color in colorCount) {
            if (colorCount[color] > maxColorCount) {
                maxColor = color;
                maxColorCount = colorCount[color];
            }
        }

        // Trova lo stile più frequente
        let maxStyle = '';
        let maxStyleCount = 0;
        for (const style in styleCount) {
            if (styleCount[style] > maxStyleCount) {
                maxStyle = style;
                maxStyleCount = styleCount[style];
            }
        }

        // Aggiorna gli elementi DOM
        if (maxColor) {
            dominantColorElement.innerHTML = `<span class="color-dot" style="background-color: ${maxColor};"></span> ${maxColor}`;
        }

        if (maxStyle) {
            dominantStyleElement.textContent = maxStyle;
        }
    }
}

// Aggiungere effetti di animazione ai CSS
document.addEventListener('DOMContentLoaded', function () {
    // Aggiungi classe per animazione all'entrata degli elementi
    const styleSheet = document.createElement('style');
    styleSheet.textContent = `
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }
            50% {
                transform: scale(1.1);
                opacity: 1;
            }
            100% {
                transform: scale(1);
                opacity: 0.8;
            }
        }
        
        .item-card {
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
        }
    `;
    document.head.appendChild(styleSheet);

    // Applica ritardi progressivi per un effetto a cascata
    setTimeout(() => {
        const items = document.querySelectorAll('.item-card');
        items.forEach((item, index) => {
            item.style.animationDelay = `${index * 0.1}s`;
        });
    }, 100);
});

// Aggiungi questo script per la funzionalità di anteprima
document.getElementById('itemImage').addEventListener('change', function (e) {
    const file = e.target.files[0];
    const previewContainer = document.querySelector('.image-preview-container');
    const imagePreview = document.getElementById('imagePreview');

    if (file) {
        // Controllo del tipo di file e della dimensione
        const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
        const maxSizeInBytes = 5 * 1024 * 1024; // 5MB

        if (!validImageTypes.includes(file.type)) {
            alert("Per favore seleziona un'immagine valida (JPEG, PNG, GIF).");
            this.value = '';
            previewContainer.classList.add('d-none');
            return;
        }

        if (file.size > maxSizeInBytes) {
            alert('L\'immagine è troppo grande. La dimensione massima è 5MB.');
            this.value = '';
            previewContainer.classList.add('d-none');
            return;
        }

        // Mostra l'anteprima
        const reader = new FileReader();
        reader.onload = function (event) {
            imagePreview.src = event.target.result;
            previewContainer.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.classList.add('d-none');
    }
});

// Funzionalità per rimuovere l'immagine
document.getElementById('removeImage').addEventListener('click', function () {
    document.getElementById('itemImage').value = '';
    document.querySelector('.image-preview-container').classList.add('d-none');
});

async function addCapo() {
    // Recupera i valori dai campi del form
    const nome = document.getElementById('itemName').value;
    const categoria = document.getElementById('itemCategory').value;
    const colore = document.getElementById('itemColor').value;
    const stile = document.getElementById('itemStyle').value;
    const materiale = document.getElementById('itemMaterial').value;
    const vestibilita = document.getElementById('itemFit').value;
    const descrizione = document.getElementById('itemDescription').value;
    const fileInput = document.getElementById('itemImage');

    // Controlla se tutti i campi richiesti sono compilati
    if (!nome || !categoria || !colore || !stile || !materiale || !vestibilita) {
        showToast('Errore', 'Compila tutti i campi obbligatori', 'danger');
        return;
    }

    // Prepara i dati per l'invio
    const formData = new FormData();
    formData.append('nome', nome);
    formData.append('categoria', categoria);
    formData.append('colore', colore);
    formData.append('stile', stile);
    formData.append('materiale', materiale);
    formData.append('vestibilita', vestibilita);
    formData.append('descrizione', descrizione);

    // Imposta sempre la rimozione dello sfondo
    formData.append('removeBackground', '1');

    // Aggiungi l'immagine se presente
    if (fileInput.files.length > 0) {
        formData.append('immagine', fileInput.files[0]);
    }

    // Mostra indicatore di caricamento
    const addButton = document.querySelector('#addItemForm .btn-primary');
    const originalContent = addButton.innerHTML;
    addButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Caricamento...';
    addButton.disabled = true;

    // Invia la richiesta al server
    fetch('ajax/addWardrobeItem.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            // Ripristina il pulsante
            addButton.innerHTML = originalContent;
            addButton.disabled = false;

            if (data.status === 'OK') {
                // Chiudi il modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addItemModal'));
                modal.hide();

                // Resetta il form
                document.getElementById('addItemForm').reset();
                document.querySelector('.image-preview-container').classList.add('d-none');

                // Mostra un messaggio di successo
                showToast('Successo', 'Capo aggiunto con successo!', 'success');

                // Ricarica gli elementi del guardaroba
                loadInitialWardrobeItems();
            } else {
                // Mostra un messaggio di errore
                showToast('Errore', data.msg || 'Si è verificato un errore', 'danger');
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            addButton.innerHTML = originalContent;
            addButton.disabled = false;
            showToast('Errore', 'Si è verificato un errore di connessione', 'danger');
        });
}

// Funzione per mostrare i toast di notifica
function showToast(title, message, type = 'info') {
    // Crea un nuovo elemento toast se non esiste già
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }

    const toastId = 'toast-' + Date.now();
    const toastHTML = `
        <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}</strong>: ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 5000 });
    toast.show();

    // Rimuovi il toast dal DOM dopo che è stato nascosto
    toastElement.addEventListener('hidden.bs.toast', function () {
        toastElement.remove();
    });
}

/**/

function geInfo() {
    getLocation();
    let informazioni = "";

    // Funzione principale per ottenere la posizione
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(fetchWeatherData, handleGeolocationError);
        } else {
            alert("La geolocalizzazione non è supportata dal tuo browser.");
        }
    }

    // Funzione per gestire gli errori di geolocalizzazione
    function handleGeolocationError(error) {
        let errorMsg = "";
        switch (error.code) {
            case error.PERMISSION_DENIED:
                errorMsg = "L'utente ha negato la richiesta di geolocalizzazione.";
                break;
            case error.POSITION_UNAVAILABLE:
                errorMsg = "Posizione non disponibile.";
                break;
            case error.TIMEOUT:
                errorMsg = "La richiesta di geolocalizzazione è scaduta.";
                break;
            case error.UNKNOWN_ERROR:
                errorMsg = "Errore sconosciuto.";
                break;
        }

        alert(errorMsg);
    }

    // Funzione per recuperare i dati meteo
    async function fetchWeatherData(position) {
        try {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            // Costruisci l'URL per la richiesta
            const url = `ajax/weather_service.php?lat=${lat}&lon=${lon}`;

            // Effettua la richiesta al servizio
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error("Errore nella risposta del server");
            }

            // Ottieni il testo della risposta
            const txt = await response.text();
            console.log("Risposta ricevuta:", txt);

            // Converti in JSON
            const datiRicevuti = JSON.parse(txt);
            console.log("Dati parsati:", datiRicevuti);

            // Controlla lo stato della risposta
            if (datiRicevuti.status === "ERR") {
                alert("Errore: " + datiRicevuti.msg);
            } else {
                // Aggiorna l'interfaccia con i dati ricevuti
                informazioni = datiRicevuti;
            }
        } catch (error) {
            console.error("Errore:", error);
            alert("Si è verificato un errore: " + error.message);
        }
    }

    return informazioni;
}

// Funzione per aggiornare l'interfaccia utente
async function mostraConsigli() {
    let informazioni = geInfo();

    // Aggiorna la stagione
    let stagione = "";
    let temperatura = "";
    let umidita = "";
    let vento = "";
    let codice = "";
    let stile = document.getElementById('styleSelect').value;

    if (informazioni.season) {
        stagione = informazioni.season;
    }

    // Aggiorna i dati meteo
    if (informazioni.weatherInfo) {
        temperatura = informazioni.weatherInfo.temp;
        umidita = informazioni.weatherInfo.humidity;
        vento = informazioni.weatherInfo.wind_speed;
        codice = informazioni.weatherInfo.weather_code;
    }

    let url = "ajax/getOutfit.php?stagione=" + stagione + "&temperatura=" + temperatura + "&umidita=" + umidita + "&vento=" + vento + "&codice=" + codice + "&stile=" + stile;
    let response = await fetch(url);
    if (!response.ok)
        throw new Error("Errore nella risposta del server");

    let txt = await response.text();
    console.log(txt);
    let datiRicevuti = JSON.parse(txt);
    console.log(datiRicevuti);

    if (datiRicevuti["status"] == "ERR")
        console.error("Errore ricevuto dal server:", datiRicevuti.msg);
    else if (datiRicevuti["status"] == "OK") {
        console.log("Outfit inserito in sessione");
        window.location.href = "style_advice.php";
    }
}