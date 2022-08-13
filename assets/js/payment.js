$(document).ready(function () {
    $('.rem-another-collection-widget').click(function() {
        const list = $($(this).attr('data-list-selector'));
        list.children().last().remove();
    });

    $('.add-another-collection-widget').click(function (e) {
        const list = $($(this).attr('data-list-selector'));

        let newWidget = list.attr('data-prototype');
        newWidget = newWidget.replace(/__name__/g, list.children().length);
        const newElem = $(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);
    });

    if (document.getElementById('check')) {
        document.getElementById('check').checked = false;
    }

    if (document.getElementById('payment')?.value === 'paySafeCard') {
        document.getElementById('psc-code').removeAttribute('style');
    }

    if (document.getElementById('payment')?.value === 'simplePayPal') {
        document.getElementById('psc-code').removeAttribute('d-none');
    }

    document.querySelector('#payment')?.addEventListener('change', (e) => {
        e.target.value === 'paySafeCard'
            ? document.getElementById('psc-code').removeAttribute('style')
            : document.getElementById('psc-code').setAttribute('style', 'display: none;')

        e.target.value === 'simplePayPal'
            ? document.getElementById('paypal').classList.remove('d-none')
            : document.getElementById('paypal').classList.add('d-none');
    })

    document.getElementById('count')?.addEventListener('input', (e) => {
        const counter = document.getElementById('counter');
        const c = document.querySelectorAll('.counter');
        const p = document.querySelectorAll('.pricing');
        const val = e.target.value * counter.getAttribute('data-price');
        document.getElementById('price').innerHTML = val.toFixed(2);
        counter.innerHTML = e.target.value;
        c.forEach(f => f.innerHTML =  e.target.value)
        p.forEach(f => f.innerHTML = val.toFixed(2))
    })

    document.getElementById('check')?.addEventListener('click', () => {
        const checkbox = document.getElementById('check');
        const submitBtn = document.getElementById('submit');

        !checkbox.checked
            ? submitBtn.setAttribute('style', 'display:none')
            : submitBtn.removeAttribute('style');
    })
});