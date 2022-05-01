import '/assets/styles/client/guild/dark/main.css';
import '../../../bootstrap';
import '../../../payment'

const navbar = document.querySelector(".navbar");
window.addEventListener("scroll", moveOnScroll);
moveOnScroll();

function moveOnScroll() {
    if (window.scrollY >= 30) navbar.classList.add("scroll");
    else navbar.classList.remove("scroll");
}