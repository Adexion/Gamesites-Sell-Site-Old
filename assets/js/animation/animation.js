function getOffset(e) {
    return e.getBoundingClientRect().top + window.scrollY
}

function showEl(e, cls) {
    window.scrollY + window.innerHeight - 300 >= getOffset(e) ? e.classList.remove(cls) : e.classList.add(cls)
}

function show() {
    document.querySelectorAll(".down").forEach(e => showEl(e, "downed"));
    document.querySelectorAll(".left").forEach(e => showEl(e, "lefted"));
}

document.addEventListener("scroll", () => {
    show()
});
show();