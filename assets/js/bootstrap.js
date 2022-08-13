import bootstrap from "bootstrap/dist/js/bootstrap.bundle";
import AOS from 'aos';
import "bootstrap-icons/font/bootstrap-icons.css";
import 'aos/dist/aos.css';

AOS.init();

(() => {
    document.addEventListener('contextmenu', e => {
        e.preventDefault();
    })

    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
})();
