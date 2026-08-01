/**
 * Demo-only behaviour. Nothing here is a security control - the server refuses
 * every non-GET request before routing, and the database is opened read-only.
 * This exists so the write controls look disabled rather than broken, and so a
 * visitor is told why instead of clicking into a 405.
 */
(function () {
    'use strict';

    var NOTE = document.documentElement.lang === 'en'
        ? 'Read-only demo — this form does not save.'
        : 'Демонстрация само за четене — формата не записва.';

    /**
     * A form that is laid out inside a table cell or a card header is a
     * single-button affair (the row delete). Captioning each one would put a
     * paragraph in every row, so those get a tooltip and nothing more.
     */
    function wantsNote(form) {
        if (form.closest('td, th, .card-header, .float-right, .form-inline')) {
            return false;
        }

        return form.querySelector('input:not([type="hidden"]), select, textarea') !== null;
    }

    function disable(form) {
        var controls = form.querySelectorAll('input, select, textarea, button');

        Array.prototype.forEach.call(controls, function (control) {
            if (control.type === 'hidden') {
                return;
            }

            control.disabled = true;
            control.title = NOTE;
        });

        if (!wantsNote(form)) {
            return;
        }

        var note = document.createElement('p');
        note.className = 'demo-form-note';
        note.textContent = NOTE;
        form.appendChild(note);
    }

    document.addEventListener('DOMContentLoaded', function () {
        var forms = document.querySelectorAll('form[method="post"], form[method="POST"]');
        Array.prototype.forEach.call(forms, disable);
    });

    // The validation-error component calls this. It can never fire in the demo
    // - nothing can be submitted, so nothing can fail validation - but the
    // function has to exist for the same view to work on master.
    window.showModal = function (modalId) {
        var el = document.getElementById(modalId);

        if (el && window.bootstrap) {
            new window.bootstrap.Modal(el).show();
        }
    };
})();
