// Inizializzazione quando il documento è caricato
document.addEventListener('DOMContentLoaded', function () {
    // Inizializzazione di particles.js se non già fatto in home.js
    initParticles();

    // Setup dell'effetto parallasse sui blob nelle sezioni del profilo
    setupProfileParallax();

    // Setup delle animazioni per il profilo
    setupProfileAnimations();

    // Setup dei gestori eventi per la modifica del profilo
    setupProfileEditing();
});

// Inizializzazione di particles.js
function initParticles() {
    // Controlliamo se particles.js è già stato inizializzato
    if (document.getElementById('particles-js') &&
        typeof particlesJS !== 'undefined' &&
        !document.querySelector('#particles-js canvas')) {

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
}

// Setup dell'effetto parallasse sui blob nelle sezioni del profilo
function setupProfileParallax() {
    const sections = document.querySelectorAll('.profile-section');

    sections.forEach(section => {
        const blobs = section.querySelectorAll('.card-blob');

        section.addEventListener('mousemove', function (e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const moveX = (x - centerX) / centerX * 15;
            const moveY = (y - centerY) / centerY * 15;

            blobs.forEach((blob, index) => {
                // Facciamo muovere i blob in direzioni opposte per un effetto migliore
                const factor = index % 2 === 0 ? 1 : -1;
                blob.style.transform = `translate(${moveX * factor}px, ${moveY * factor}px)`;
            });
        });

        // Resettiamo la posizione dei blob quando il mouse esce dalla sezione
        section.addEventListener('mouseleave', function () {
            blobs.forEach(blob => {
                blob.style.transform = 'translate(0, 0)';
            });
        });
    });

    // Effetto parallasse per l'header del profilo
    const profileHeader = document.querySelector('.profile-header');
    if (profileHeader) {
        profileHeader.addEventListener('mousemove', function (e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const moveX = (x - centerX) / centerX * 8;
            const moveY = (y - centerY) / centerY * 8;

            const avatar = this.querySelector('.profile-avatar');
            const blurEffect = this.querySelector('.profile-blur-effect');

            if (avatar && blurEffect) {
                avatar.style.transform = `translate(${moveX * 0.5}px, ${moveY * 0.5}px)`;
                blurEffect.style.transform = `translate(${moveX * -0.8}px, ${moveY * -0.8}px)`;
            }
        });

        profileHeader.addEventListener('mouseleave', function () {
            const avatar = this.querySelector('.profile-avatar');
            const blurEffect = this.querySelector('.profile-blur-effect');

            if (avatar && blurEffect) {
                avatar.style.transform = 'translate(0, 0)';
                blurEffect.style.transform = 'translate(0, 0)';
            }
        });
    }
}

// Setup delle animazioni per il profilo
function setupProfileAnimations() {
    // Animazione delle sound waves
    animateSoundWaves();

    // Animazione delle attività recenti con effetto di fade-in all'ingresso
    const activityItems = document.querySelectorAll('.activity-item');
    
    // Prepara gli elementi per l'animazione
    activityItems.forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    });

    // Usa solo l'Intersection Observer per triggerare l'animazione
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Applica l'animazione quando l'elemento diventa visibile
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                entry.target.classList.add('fade-in');
                
                // Non dobbiamo necessariamente smettere di osservare qui
                // ma possiamo farlo se vogliamo che l'animazione avvenga solo una volta
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Osserva tutti gli elementi
    activityItems.forEach(item => {
        observer.observe(item);
    });
    
    // Rimuoviamo l'event listener di scroll ridondante che causava conflitti
}

// Animazione delle sound waves
function animateSoundWaves() {
    const soundWave = document.querySelector('.sound-wave-mini');
    if (soundWave) {
        const bars = soundWave.querySelectorAll('.bar');

        // Animazione iniziale casuale
        bars.forEach(bar => {
            const height = Math.floor(Math.random() * 30) + 10;
            bar.style.height = height + 'px';
        });

        // Animazione continua
        setInterval(() => {
            bars.forEach(bar => {
                const height = Math.floor(Math.random() * 30) + 10;
                bar.style.height = height + 'px';
            });
        }, 1000);
    }
}

// Verifica se un elemento è nel viewport
function isElementInViewport(el) {
    const rect = el.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

// Setup dei gestori eventi per la modifica del profilo
async function setupProfileEditing() {
    const btnEditProfile = document.getElementById('btnEditProfile');
    const editProfileModal = document.getElementById('editProfileModal');

    // Configurazione del modal di modifica
    if (btnEditProfile) {
        btnEditProfile.addEventListener('click', function () {
            const modal = new bootstrap.Modal(editProfileModal);
            modal.show();
        });
    }
}

async function editProfilo() {
    let id = document.getElementById("id").value;
    let username = document.getElementById("editUsername").value;
    let email = document.getElementById("editEmail").value;
    let bio = document.getElementById("editBio").value;
    let genere = document.getElementById("editGenere").value;
    let stile = document.getElementById("editStile").value;

    let url = "ajax/editProfilo.php?id="+id+"&user="+username+"&email="+email+"&bio="+bio+"&genere="+genere+"&stile="+stile;
    let response = await fetch(url);
    if(!response.ok)
        throw new Error("fetch non riuscita");

    let txt = await response.text();
    console.log(txt);   
    let datiRicevuti = JSON.parse(txt);
    console.log(datiRicevuti);

    //sistemare con sweetalert uno errore e l'altro ok
    if(datiRicevuti["status"] == "ERR")
        alert(datiRicevuti["msg"]);
    else if(datiRicevuti["status"] == "OK") {
        alert(datiRicevuti["msg"]);
        window.location.reload();
    }
    else if(datiRicevuti["status"] == "UGUALE")
        console.log("nessuna modifica");
}