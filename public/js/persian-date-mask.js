function applyPersianDateMask(selector) {
    const inputs = document.querySelectorAll(selector);

    inputs.forEach(input => {
        input.addEventListener('input', function () {
            let value = input.value.replace(/\D/g, ''); // remove non-digits

            if (value.length > 8) value = value.slice(0, 8);

            let formatted = value;
            if (value.length > 4) {
                formatted = value.slice(0, 4) + '/' + value.slice(4);
            }
            if (value.length > 6) {
                formatted = formatted.slice(0, 7) + '/' + formatted.slice(7);
            }

            input.value = formatted;
        });
    });
}
