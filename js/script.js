// Configurazione per particles.js
document.addEventListener('DOMContentLoaded', function() {
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
    
    // Animazione per l'effetto sound wave
    setInterval(() => {
        document.querySelectorAll('.music-wave .bar').forEach(bar => {
            const height = Math.floor(Math.random() * 60) + 20;
            bar.style.height = height + 'px';
        });
    }, 500);
    
    // Animazione per gli effetti blob
    animateBlobs();
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

async function login() {
    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;
    let url = "ajax/gestoreLogin.php?user="+username+"&pass="+password;
    let response = await fetch(url);
    if(!response.ok)
        throw new Error("fetch non riuscita");

    let txt = await response.text();
    console.log(txt);
    let datiRicevuti = JSON.parse(txt);
    console.log(datiRicevuti);

    if(datiRicevuti["status"]=="ERR")
        window.location.href = "index.php?error="+datiRicevuti["msg"];
    else if(datiRicevuti["status"]=="OK")
        window.location.href = "home.php";
}