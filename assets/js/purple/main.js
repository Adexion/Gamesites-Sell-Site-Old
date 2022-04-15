import '../../styles/purple/purple.css';
import '../payment'

const navbar = document.querySelector(".navbar");

window.addEventListener("scroll", () => {
    if (window.scrollY >= 30) navbar.classList.add("scroll");
    else navbar.classList.remove("scroll");
});
