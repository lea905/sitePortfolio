import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

/* Loader */
document.addEventListener("turbo:load", function () {
    const loader = document.querySelector(".loader");
    if (loader) {
        // Reset style just in case, though new DOM should be fresh
        // loader.style.opacity = "1"; 
        // loader.style.display = "block";

        // Trigger fade out
        setTimeout(() => {
            loader.style.opacity = "0";
            setTimeout(() => {
                loader.style.display = "none";
            }, 500);
        }, 100); // Small delay to ensure it's seen if needed, or immediate
    }
});

function toggleMenu() {
    const menu = document.getElementById("menu");
    if (menu) {
        menu.classList.toggle("active");
    }
}
// Expose toggleMenu to global scope if needed for onclick events in HTML
window.toggleMenu = toggleMenu;

/*Accordéon*/
document.addEventListener("turbo:load", function () {
    // Récupérer tous les boutons d'accordéon
    const accordions = document.querySelectorAll(".accordion");

    // Ajouter un événement de clic à chaque bouton
    accordions.forEach(acc => {
        acc.addEventListener("click", function () {
            // Toggle de la classe "active" pour changer l'apparence du bouton
            this.classList.toggle("active");

            // Récupérer le panneau suivant et afficher/masquer
            const panel = this.nextElementSibling;
            panel.classList.toggle("show");
        });
    });
});

/*Cercle compétence*/
document.addEventListener("turbo:load", function () {
    const circles = document.querySelectorAll("svg circle:nth-child(2)");

    circles.forEach(circle => {
        const parent = circle.closest(".competence");
        if (!parent) return;

        const pourcentage = parseInt(parent.dataset.pourcentage);
        const rayon = 50;
        const totalLength = 2 * Math.PI * rayon;
        const offset = totalLength * (1 - (pourcentage / 100));

        circle.style.strokeDasharray = totalLength;
        circle.style.strokeDashoffset = totalLength;
        circle.style.animation = "none";
        setTimeout(() => {
            circle.style.transition = "stroke-dashoffset 2s";
            circle.style.strokeDashoffset = offset;
        }, 100);
    });
});
