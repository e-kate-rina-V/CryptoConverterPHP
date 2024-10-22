function showForm(formId) {
    const form = document.getElementById(formId);
    form.style.display = 'block';
    document.addEventListener('click', function(event) {
        closeFormOnClickOutside(event, form);
    });
}

function hideForm(formId) {
    document.getElementById(formId).style.display = 'none';
}

function closeFormOnClickOutside(event, form) {
    if (!form.contains(event.target) && event.target.tagName !== 'A') {
        form.style.display = 'none';
        document.removeEventListener('click', function(e) {
            closeFormOnClickOutside(e, form);
        });
    }
}