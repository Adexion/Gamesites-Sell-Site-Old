import '../../../../styles/client/multi/totalcraft/main.css';

import '../../../animation/animation'
import '../../../bootstrap';
import '../../../payment'

const navbar = document.querySelector(".navbar");

window.addEventListener("scroll", () => {
    if (window.scrollY >= 30) navbar.classList.add("scroll");
    else navbar.classList.remove("scroll");
});

(()=>{
    document.querySelector('#copyIp')?.addEventListener('click', e => {
        navigator.clipboard.writeText(document.getElementById('ip').value);
        e.target.innerHTML = 'Skopiowano!'
    })
})()
