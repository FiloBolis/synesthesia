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
});


// document.addEventListener('DOMContentLoaded', function() {
//     // Ottieni la posizione automaticamente all'avvio
//     getLocation();
    
//     // Funzione principale per ottenere la posizione
//     function getLocation() {
//         if (navigator.geolocation) {
//             navigator.geolocation.getCurrentPosition(fetchWeatherData, handleGeolocationError);
//         } else {
//             alert("La geolocalizzazione non è supportata dal tuo browser.");
//         }
//     }
    
//     // Funzione per gestire gli errori di geolocalizzazione
//     function handleGeolocationError(error) {      
//         let errorMsg = "";
//         switch(error.code) {
//             case error.PERMISSION_DENIED:
//                 errorMsg = "L'utente ha negato la richiesta di geolocalizzazione.";
//                 break;
//             case error.POSITION_UNAVAILABLE:
//                 errorMsg = "Posizione non disponibile.";
//                 break;
//             case error.TIMEOUT:
//                 errorMsg = "La richiesta di geolocalizzazione è scaduta.";
//                 break;
//             case error.UNKNOWN_ERROR:
//                 errorMsg = "Errore sconosciuto.";
//                 break;
//         }
        
//         alert(errorMsg);
//     }
    
//     // Funzione per recuperare i dati meteo
//     async function fetchWeatherData(position) {
//         try {
//             const lat = position.coords.latitude;
//             const lon = position.coords.longitude;
            
//             // Costruisci l'URL per la richiesta
//             const url = `weather_service.php?lat=${lat}&lon=${lon}`;
            
//             // Effettua la richiesta al servizio
//             const response = await fetch(url);
            
//             if (!response.ok) {
//                 throw new Error("Errore nella risposta del server");
//             }
            
//             // Ottieni il testo della risposta
//             const txt = await response.text();
//             console.log("Risposta ricevuta:", txt);
            
//             // Converti in JSON
//             const datiRicevuti = JSON.parse(txt);
//             console.log("Dati parsati:", datiRicevuti);
            
//             // Controlla lo stato della risposta
//             if (datiRicevuti.status === "ERR") {
//                 alert("Errore: " + datiRicevuti.msg);
//             } else {
//                 // Aggiorna l'interfaccia con i dati ricevuti
//                 getOutfit(datiRicevuti);
//             }
//         } catch (error) {
//             console.error("Errore:", error);
//             alert("Si è verificato un errore: " + error.message);
//         }
//     }
    
//     // Funzione per aggiornare l'interfaccia utente
//     async function getOutfit(data) {       
//         // Aggiorna la stagione
//         let stagione = "";
//         let temperatura = "";
//         let umidita = "";
//         let vento = "";
//         let codice = "";

//         if (data.season) {
//             stagione = data.season;
//         }
        
//         // Aggiorna i dati meteo
//         if (data.weatherInfo) {
//             temperatura = data.weatherInfo.temp;
//             umidita = data.weatherInfo.humidity;
//             vento = data.weatherInfo.wind_speed;
//             codice = data.weatherInfo.weather_code;
//         }

//         let url = "ajax/getOutfit.php?stagione=" + stagione + "&temperatura=" + temperatura + "&umidita=" + umidita + "&vento=" + vento + "&codice=" + codice;
//         let response = await fetch(url);
//         if(!response.ok)
//             throw new Error("Errore nella risposta del server");

//         let txt = await response.text();
//         console.log("Risposta ricevuta:", txt);
//         let datiRicevuti = JSON.parse(txt);
//         console.log("Dati parsati:", datiRicevuti);

//         if (datiRicevuti.status === "ERR") {
//             alert("Errore: " + datiRicevuti.msg);
//         } else {
//             // Aggiorna l'interfaccia con i dati ricevuti
            
//         }
//     }
// });