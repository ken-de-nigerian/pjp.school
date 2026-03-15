/**
 * intl-tel-input: country picker uses a shared modal (same pattern as site modals).
 * Inits #phone, #father_phone, #mother_phone, #sponsor_phone when present.
 */
(function ($) {
    'use strict';

    var itiInstances = [];
    var activeIti = null;
    var activePhoneInput = null;
    var modalEl = null;
    var listEl = null;
    var searchEl = null;
    var countryRows = [];
    var listBuilt = false;

    var defaultOptions = {
        utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/utils.js',
        dropdownContainer: document.body,
        // No geoIpLookup: browser calls to ipapi.co cause CORS/429 in dev and burn free quotas.
        // Default country; users still pick any country in the modal.
        initialCountry: 'ng',
    };

    function ensureModal() {
        if (modalEl) return;
        modalEl = document.getElementById('intl-tel-country-modal');
        if (!modalEl) {
            modalEl = document.createElement('div');
            modalEl.id = 'intl-tel-country-modal';
            modalEl.className = 'intl-tel-country-modal hidden';
            modalEl.setAttribute('aria-modal', 'true');
            modalEl.setAttribute('role', 'dialog');
            modalEl.setAttribute('aria-labelledby', 'intl-tel-country-modal-title');
            modalEl.innerHTML =
                '<div class="intl-tel-country-modal__backdrop" data-intl-tel-modal-close aria-hidden="true"></div>' +
                '<div class="intl-tel-country-modal__panel">' +
                '<div class="intl-tel-country-modal__head">' +
                '<h2 id="intl-tel-country-modal-title" class="intl-tel-country-modal__title">Select country</h2>' +
                '<button type="button" class="intl-tel-country-modal__close" data-intl-tel-modal-close aria-label="Close">&times;</button>' +
                '</div>' +
                '<div class="intl-tel-country-modal__search-wrap">' +
                '<i class="fas fa-search intl-tel-country-modal__search-icon" aria-hidden="true"></i>' +
                '<input type="search" id="intl-tel-country-modal-search" class="intl-tel-country-modal__search" placeholder="Search country or code…" autocomplete="off" />' +
                '</div>' +
                '<div class="intl-tel-country-modal__list-wrap">' +
                '<div id="intl-tel-country-modal-list" class="intl-tel-country-modal__list"></div>' +
                '</div>' +
                '</div>';
            document.body.appendChild(modalEl);
        }
        listEl = document.getElementById('intl-tel-country-modal-list');
        searchEl = document.getElementById('intl-tel-country-modal-search');
        modalEl.querySelectorAll('[data-intl-tel-modal-close]').forEach(function (el) {
            el.addEventListener('click', closeModal);
        });
        searchEl.addEventListener('input', filterList);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modalEl && !modalEl.classList.contains('hidden')) closeModal();
        });
    }

    function escapeHtml(s) {
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    /** [flagcdn.com](https://flagcdn.com) h20 PNG — e.g. https://flagcdn.com/h20/ua.png */
    var FLAG_CDN_BASE = 'https://flagcdn.com/h20/';
    /** Neutral default when country asset missing */
    var FLAG_DEFAULT_URL = FLAG_CDN_BASE + 'un.png';
    var flagLazyObserver = null;

    /** ISO2 → regional-indicator emoji (last resort) */
    function flagEmoji(iso2) {
        try {
            var u = String(iso2 || '').toUpperCase();
            if (u.length !== 2) return '\u2753';
            return String.fromCodePoint(0x1f1e6 - 65 + u.charCodeAt(0), 0x1f1e6 - 65 + u.charCodeAt(1));
        } catch (e) {
            return '\u2753';
        }
    }

    function getCountryData() {
        // v17: getCountryData lives on intlTelInputGlobals (not on the intlTelInput factory fn)
        var g = window.intlTelInputGlobals;
        if (g && typeof g.getCountryData === 'function') return g.getCountryData();
        if (typeof window.intlTelInput !== 'undefined' && typeof window.intlTelInput.getCountryData === 'function') {
            return window.intlTelInput.getCountryData();
        }
        return [];
    }

    function buildList() {
        if (listBuilt || !listEl) return;
        var data = getCountryData();
        data.sort(function (a, b) { return (a.name || '').localeCompare(b.name || ''); });
        data.forEach(function (c) {
            if (!c.iso2) return;
            var row = document.createElement('button');
            row.type = 'button';
            row.className = 'intl-tel-country-modal__row';
            row.setAttribute('data-name', (c.name || '').toLowerCase());
            row.setAttribute('data-dial', String(c.dialCode || ''));
            row.setAttribute('data-iso', c.iso2.toLowerCase());
            var iso = c.iso2;
            var isoLower = String(iso).toLowerCase();
            var flagSrc = FLAG_CDN_BASE + isoLower + '.png';
            row.innerHTML =
                '<span class="intl-tel-country-modal__flag-wrap" aria-hidden="true">' +
                '<img class="intl-tel-country-modal__flag-img" alt="" decoding="async" loading="lazy" ' +
                'data-src="' + flagSrc.replace(/"/g, '&quot;') + '" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" ' +
                'onerror="if(window.__intlTelFlagErr)window.__intlTelFlagErr(this);" />' +
                '<span class="intl-tel-country-modal__flag-fallback" hidden>' + flagEmoji(iso) + '</span>' +
                '</span>' +
                '<span class="intl-tel-country-modal__name">' + escapeHtml(c.name || '') + '</span>' +
                '<span class="intl-tel-country-modal__dial">+' + escapeHtml(String(c.dialCode || '')) + '</span>';
            row.addEventListener('click', function () {
                if (!activeIti || !activePhoneInput) return;
                activeIti.setCountry(iso);
                activePhoneInput.dispatchEvent(new Event('countrychange', { bubbles: true }));
                activePhoneInput.dispatchEvent(new Event('input', { bubbles: true }));
                closeModal();
            });
            listEl.appendChild(row);
            countryRows.push(row);
        });
        listBuilt = true;
        startFlagLazyObserver();
    }

    function onFlagImgError(img) {
        if (!img || img.getAttribute('data-flag-dead') === '1') return;
        if (img.getAttribute('data-tried-default') !== '1') {
            img.setAttribute('data-tried-default', '1');
            img.src = FLAG_DEFAULT_URL;
            return;
        }
        img.setAttribute('data-flag-dead', '1');
        img.style.display = 'none';
        var n = img.nextElementSibling;
        if (n && n.classList.contains('intl-tel-country-modal__flag-fallback')) {
            n.removeAttribute('hidden');
            n.classList.add('is-visible');
        }
    }

    function startFlagLazyObserver() {
        if (!listEl || typeof IntersectionObserver === 'undefined') {
            listEl.querySelectorAll('img.intl-tel-country-modal__flag-img[data-src]').forEach(function (img) {
                if (!img.getAttribute('data-loaded')) {
                    img.setAttribute('data-loaded', '1');
                    img.src = img.getAttribute('data-src') || FLAG_DEFAULT_URL;
                }
            });
            return;
        }
        if (flagLazyObserver) flagLazyObserver.disconnect();
        flagLazyObserver = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;
                    var img = entry.target;
                    var src = img.getAttribute('data-src');
                    if (src && !img.getAttribute('data-loaded')) {
                        img.setAttribute('data-loaded', '1');
                        img.src = src;
                    }
                    flagLazyObserver.unobserve(img);
                });
            },
            { root: listEl.closest('.intl-tel-country-modal__list-wrap') || null, rootMargin: '80px', threshold: 0.01 }
        );
        listEl.querySelectorAll('img.intl-tel-country-modal__flag-img[data-src]').forEach(function (img) {
            flagLazyObserver.observe(img);
        });
    }

    function filterList() {
        var q = (searchEl.value || '').trim().toLowerCase();
        countryRows.forEach(function (row) {
            var name = row.getAttribute('data-name') || '';
            var dial = row.getAttribute('data-dial') || '';
            var iso = row.getAttribute('data-iso') || '';
            var ok = !q || name.indexOf(q) !== -1 || dial.indexOf(q) !== -1 || iso.indexOf(q) !== -1;
            row.style.display = ok ? '' : 'none';
        });
    }

    function openModal(iti, inputEl) {
        ensureModal();
        buildList();
        activeIti = iti;
        activePhoneInput = inputEl;
        searchEl.value = '';
        filterList();
        modalEl.classList.remove('hidden');
        document.body.classList.add('intl-tel-modal-open');
        setTimeout(function () { searchEl.focus(); }, 80);
    }

    function closeModal() {
        if (modalEl) modalEl.classList.add('hidden');
        document.body.classList.remove('intl-tel-modal-open');
        activeIti = null;
        activePhoneInput = null;
    }

    function bindModalPicker(iti, inputEl) {
        var itiRoot = inputEl.closest('.iti');
        if (!itiRoot) return;
        function blockAndOpen(e) {
            if (!e.target.closest('.iti__selected-flag')) return;
            e.preventDefault();
            e.stopImmediatePropagation();
            if (typeof iti._hideDropdown === 'function') iti._hideDropdown();
            openModal(iti, inputEl);
        }
        itiRoot.addEventListener('mousedown', blockAndOpen, true);
        itiRoot.addEventListener('click', blockAndOpen, true);
        itiRoot.addEventListener('touchstart', blockAndOpen, { capture: true, passive: false });
    }

    function hideLists() {
        document.querySelectorAll('.iti__country-list').forEach(function (ul) {
            ul.style.display = 'none';
        });
    }

    function closeAllPluginDropdowns() {
        itiInstances.forEach(function (iti) {
            if (iti && typeof iti._hideDropdown === 'function') iti._hideDropdown();
        });
        hideLists();
    }

    window.__intlTelFlagErr = onFlagImgError;

    $(document).ready(function () {
        window.addEventListener('scroll', closeAllPluginDropdowns, true);
        var mainScroll = document.querySelector('main');
        if (mainScroll) mainScroll.addEventListener('scroll', closeAllPluginDropdowns, true);

        var mo = new MutationObserver(function () { hideLists(); });
        mo.observe(document.body, { childList: true, subtree: true });

        var phoneInput = document.querySelector('#phone');
        if (phoneInput) {
            var iti = window.intlTelInput(phoneInput, defaultOptions);
            itiInstances.push(iti);
            bindModalPicker(iti, phoneInput);

            phoneInput.addEventListener('input', function () {
                var country = iti.getSelectedCountryData().name;
                var countryEl = document.getElementById('country');
                var phoneForm = phoneInput.closest('form');
                if (countryEl && phoneForm && phoneForm.contains(countryEl)) $('#country').val(country);
                validatePhoneNumber(iti, phoneInput);
            });
            phoneInput.addEventListener('countrychange', function () {
                var countryName = iti.getSelectedCountryData().name;
                var countryEl = document.getElementById('country');
                var phoneForm = phoneInput.closest('form');
                if (countryEl && phoneForm && phoneForm.contains(countryEl)) $('#country').val(countryName);
                validatePhoneNumber(iti, phoneInput);
            });

            function validatePhoneNumber() {
                var formattedEl = document.getElementById('formattedPhone');
                if (formattedEl && window.intlTelInputUtils) {
                    formattedEl.value = iti.getNumber(intlTelInputUtils.numberFormat.E164) || '';
                }
                var isValid = !window.intlTelInputUtils || !phoneInput.value.trim() || iti.isValidNumber();
                var phoneErrorEl = document.getElementById('phone-error') || document.getElementById('contact_phone-error');
                if (phoneErrorEl) {
                    if (!isValid && phoneInput.value.trim()) {
                        $(phoneErrorEl).text('Please enter a valid phone number for ' + iti.getSelectedCountryData().name + '.')
                            .addClass('error').removeClass('hidden');
                        $(phoneInput.closest('.iti')).addClass('iti-margin');
                    } else {
                        $(phoneErrorEl).text('').removeClass('error').addClass('hidden');
                        $(phoneInput.closest('.iti')).removeClass('iti-margin');
                    }
                }
                var profileBtn = document.getElementById('profileBtn');
                if (profileBtn) $('#profileBtn').prop('disabled', !isValid && phoneInput.value.trim() !== '');
                var editUserBtn = $('#editUserBtn');
                if (editUserBtn.length) editUserBtn.prop('disabled', !isValid && phoneInput.value.trim() !== '');
            }
        }

        function initAuxPhone(sel, hiddenSel) {
            var el = document.querySelector(sel);
            var hiddenEl = document.querySelector(hiddenSel);
            if (!el || !hiddenEl) return;
            var iti = window.intlTelInput(el, defaultOptions);
            itiInstances.push(iti);
            bindModalPicker(iti, el);
            function sync() {
                if (window.intlTelInputUtils) hiddenEl.value = iti.getNumber(intlTelInputUtils.numberFormat.E164) || '';
            }
            el.addEventListener('input', sync);
            el.addEventListener('countrychange', sync);
        }

        initAuxPhone('#father_phone', '#formattedPhoneFather');
        initAuxPhone('#mother_phone', '#formattedPhoneMother');
        initAuxPhone('#sponsor_phone', '#formattedPhoneSponsor');

        hideLists();
    });
})(jQuery);
