// Configurazione e gestione delle animazioni
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
    
    // Animazione per l'effetto sound wave
    setInterval(() => {
        document.querySelectorAll('.music-wave .bar').forEach(bar => {
            const height = Math.floor(Math.random() * 60) + 20;
            bar.style.height = height + 'px';
        });
    }, 500);
    
    // Animazione per gli effetti blob
    animateBlobs();
    
    // Validazione form di registrazione
    setupRegistrationFormValidation();
});

function animateBlobs() {
    const blobs = document.querySelectorAll('.color-blob');
    
    blobs.forEach(blob => {
        setInterval(() => {
            const xPos = Math.random() * 70;
            const yPos = Math.random() * 70;
            const scale = (Math.random() * 0.4) + 0.8;
            
            blob.style.transform = `translate(${xPos}%, ${yPos}%) scale(${scale})`;
        }, 3000);
    });
}

// Gestione validazione form registrazione
function setupRegistrationFormValidation() {
    const registrationForm = document.getElementById('registrationForm');
    
    if (registrationForm) {
        registrationForm.addEventListener('submit', function(event) {
            // Ottieni i valori dei campi
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const validationMessage = document.getElementById('validationMessage');
            
            // Verifica corrispondenza password
            if (password !== confirmPassword) {
                event.preventDefault(); // ferma l'invio del form
                validationMessage.textContent = 'Le password non coincidono';
                validationMessage.classList.remove('d-none');
                return false;
            }
            
            // Se la validazione passa, nascondi eventuali messaggi precedenti
            validationMessage.classList.add('d-none');
            return true;
        });
        
        // Validazione in tempo reale
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('confirm_password');
        
        // Validazione in tempo reale quando si esce dal campo conferma password
        confirmPasswordField.addEventListener('blur', function() {
            validatePasswordMatch();
        });
        
        // Validazione in tempo reale mentre si digita nel campo conferma password
        confirmPasswordField.addEventListener('input', function() {
            validatePasswordMatch();
        });
    }
}

async function registrazione() {
    let username = document.getElementById("username").value;
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    if(validatePasswordMatch()) {
        let url = "ajax/gestoreRegistrazione.php?user="+username+"&pass="+password+"&email="+email;
        let response = await fetch(url);
        if(!response.ok)
            throw new Error("fetch non riuscita");

        let txt = await response.text();
        console.log(txt);
        let datiRicevuti = JSON.parse(txt);
        console.log(datiRicevuti);

        if(datiRicevuti["status"] == "ERR")
            window.location.href = "registrazione.php?error="+datiRicevuti["msg"];
        else if(datiRicevuti["status"] == "OK")
            window.location.href = "index.php";  
    }
}

function validatePasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const confirmField = document.getElementById('confirm_password');
    
    // Verifica solo se entrambi i campi sono compilati
    if (password && confirmPassword) {
        if (password !== confirmPassword) {
            confirmField.classList.add('is-invalid');
            confirmField.classList.remove('is-valid');
            return false;
        } else {
            confirmField.classList.remove('is-invalid');
            confirmField.classList.add('is-valid');
            return true;
        }
    }
}