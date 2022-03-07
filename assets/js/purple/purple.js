import '../../styles/purple/purple.css';

const navbar = document.querySelector(".navbar");

window.addEventListener("scroll", () => {
    if (window.scrollY >= 30) navbar.classList.add("scroll");
    else navbar.classList.remove("scroll");
});

$(window).on("load", function () {
    const counters = $(".counter");
    const countersQuantity = counters.length;
    const counter = [];

    for (let i = 0; i < countersQuantity; i++) {
        counter[i] = parseInt(counters[i].innerHTML);
    }

    const count = function (start, value, id) {
        let localStart = start;
        setInterval(function () {
            if (localStart < value) {
                localStart++;
                counters[id].innerHTML = localStart;
            }
        }, 0);
    };

    for (let j = 0; j < countersQuantity; j++) {
        count(0, counter[j], j);
    }

    let mainMenuItem = $(`.circle-active`);
    $(`.navbar-link`)
        .on("mouseenter", function () {
            $(`.navbar-link`).removeClass("circle-active");
            $(this).addClass("circle-active");
            navbarUnderlineRelocation();
        })
        .on("mouseleave", function () {
            $(`.navbar-link`).removeClass("circle-active");
            mainMenuItem.addClass("circle-active");
            navbarUnderlineRelocation();
        });
    $(window).on("resize", function () {
        navbarUnderlineRelocation();
    });
    navbarUnderlineRelocation();
});

const navbarUnderlineRelocation = () => {
    let sel = $(`.circle-active`);
    if (sel.length === 0) {
        $(".navbar-circle").css("left", "100vw");
    } else {
        let left = $(`.circle-active`).innerWidth() / 2;
        left += sel.offset().left;
        left -= $(".navbar-circle").width() / 2;
        $(".navbar-circle").css("left", left + "px");
    }
};

$('a[href*="#"]')
    .not('[href="#"]')
    .not('[href="#0"]')
    .click(function (event) {
        if (
            location.pathname.replace(/^\//, "") ===
            this.pathname.replace(/^\//, "") &&
            location.hostname === this.hostname
        ) {
            let target = $(this.hash);
            target = target.length ? target : $("[name=" + this.hash.slice(1) + "]");
            if (target.length) {
                event.preventDefault();
                $("html, body").animate(
                    {
                        scrollTop: target.offset().top,
                    },
                    650,
                    function () {
                        let $target = $(target);
                        $target.focus();
                        if ($target.is(":focus")) {
                            return false;
                        } else {
                            $target.attr("tabindex", "-1");
                            $target.focus();
                        }
                    }
                );
            }
        }
    });

const paymentsButtons = document.querySelectorAll(
    ".payments-box-button button"
);
const text = document.querySelector(".payments-cost");

paymentsButtons.forEach((button) => {
    button.addEventListener("click", (e) => {
        paymentsButtons.forEach((btn) => btn.classList.remove("mx-active-button"));
        switch (e.target.id) {
            case "btn-1":
                button.classList.add("mx-active-button");
                text.textContent = "COST 10.00 PLN";
                break;
            case "btn-2":
                button.classList.add("mx-active-button");
                text.textContent = "COST 15.00 PLN";
                break;

            case "btn-3":
                button.classList.add("mx-active-button");
                text.textContent = "COST 25.00 PLN";
                break;
        }
    });
});

$(document).ready(function () {
    $('.add-another-collection-widget').click(function (e) {
        const list = $($(this).attr('data-list-selector'));
        let counter = list.data('widget-counter') || list.children().length;

        let newWidget = list.attr('data-prototype');
        newWidget = newWidget.replace(/__name__/g, counter);
        counter++;
        list.data('widget-counter', counter);
        const newElem = $(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);
    });
});