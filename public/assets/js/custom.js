function flashSuccess(message) {
    iziToast.success({ ...iziToastSettings, message: message });
}

function flashError(message) {
    iziToast.error({ ...iziToastSettings, message: message });
}

function flashInfo(message) {
    iziToast.info({ ...iziToastSettings, message: message });
}

function flashWarning(message) {
    iziToast.warning({ ...iziToastSettings, message: message });
}

// ────────────────────────────────────────────────
// Laravel-style validation (inline under inputs)
// ────────────────────────────────────────────────
function clearFieldErrors(fieldIds) {
    (fieldIds || []).forEach(function (field) {
        const el = document.getElementById(field + '-error');
        if (el) {
            el.textContent = '';
            el.classList.add('hidden');
        }
    });
}

function showLaravelErrors(errors, fieldMap) {
    fieldMap = fieldMap || {};
    Object.keys(errors || {}).forEach(function (field) {
        const messages = errors[field];
        const msg = Array.isArray(messages) ? messages[0] : messages;
        const targetField = fieldMap[field] || field;
        const el = document.getElementById(targetField + '-error');
        if (el) {
            el.textContent = msg;
            el.classList.remove('hidden');
        }
    });
}

// ────────────────────────────────────────────────
// Button loading spinner (for AJAX forms)
// ────────────────────────────────────────────────
const SPINNER_HTML = '<span class="flex items-center justify-center gap-2 z-10 relative button-loading-spinner">' +
    '<svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">' +
    '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
    '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>' +
    '</svg>' +
    '<span>Processing...</span>' +
    '</span>';

function setButtonLoading(button, loading) {
    if (!button) return;
    if (loading) {
        button.setAttribute('data-original-html', button.innerHTML);
        button.innerHTML = SPINNER_HTML;
        button.disabled = true;
    } else {
        const original = button.getAttribute('data-original-html');
        if (original) {
            button.innerHTML = original;
            button.removeAttribute('data-original-html');
        }
        button.disabled = false;
    }
}

(function injectResultsScoreInputStyles() {
    if (document.getElementById('results-score-input-styles')) return;
    var s = document.createElement('style');
    s.id = 'results-score-input-styles';
    s.textContent =
        '.results-score-input.results-score-input-empty:not(:disabled){border-color:#ef4444!important;box-shadow:0 0 0 1px #ef4444;background-color:rgba(254,242,242,.35);}' +
        '[data-theme="dark"] .results-score-input.results-score-input-empty:not(:disabled){background-color:rgba(127,29,29,.2);}';
    document.head.appendChild(s);
})();

/** Call when result upload Save is clicked and validation failed: red border only on empty score inputs. */
function markEmptyResultsScoreInputsOnSubmit() {
    document.querySelectorAll('.results-score-input').forEach(function (el) {
        if (el.disabled) {
            el.classList.remove('results-score-input-empty');
            return;
        }
        var t = String(el.value).trim();
        var empty = t === '' || t === '-';
        if (empty) el.classList.add('results-score-input-empty');
        else el.classList.remove('results-score-input-empty');
    });
}

/** Validate CA (0–15), Assign (0–25), Exam (0–60). Returns { valid: true } or { valid: false, message: string }. */
function validateResultScores(caVal, assignmentVal, examVal) {
    var caV = parseFloat(caVal);
    var asgV = parseFloat(assignmentVal);
    var examV = parseFloat(examVal);
    if (caVal === '' || assignmentVal === '' || examVal === '' || isNaN(caV) || isNaN(asgV) || isNaN(examV)) {
        return { valid: false, message: 'Enter all scores.' };
    }
    if (caV < 0 || caV > 15 || asgV < 0 || asgV > 25 || examV < 0 || examV > 60) {
        return { valid: false, message: 'CA must be ≤15, Assign ≤25, Exam ≤60.' };
    }
    return { valid: true };
}

/** Mark invalid edit-result modal inputs with results-score-input-empty (same style as upload sheet). Clears first, then adds to empty or out-of-range. */
function markEditResultScoreInputErrors(caEl, asgEl, examEl, caV, asgV, examV) {
    [caEl, asgEl, examEl].forEach(function (el) { if (el) el.classList.remove('results-score-input-empty'); });
    var empty = caEl.value === '' || asgEl.value === '' || examEl.value === '' || isNaN(caV) || isNaN(asgV) || isNaN(examV);
    if (empty) {
        [caEl, asgEl, examEl].forEach(function (el) {
            if (el && (!el.value || el.value.trim() === '' || isNaN(parseFloat(el.value)))) el.classList.add('results-score-input-empty');
        });
    } else {
        if (caEl && (caV < 0 || caV > 15)) caEl.classList.add('results-score-input-empty');
        if (asgEl && (asgV < 0 || asgV > 25)) asgEl.classList.add('results-score-input-empty');
        if (examEl && (examV < 0 || examV > 60)) examEl.classList.add('results-score-input-empty');
    }
}

/** Clamp result-sheet number inputs to min/max (CA, Assign, Exam). On input: only cap over-max. On blur: min/max + step. */
function clampScoreInput(el, isBlur) {
    if (!el || el.disabled || el.type !== 'number') return;
    var maxS = el.getAttribute('max');
    var minS = el.getAttribute('min');
    if (maxS === null || maxS === '') return;
    var max = parseFloat(maxS);
    var min = minS !== null && minS !== '' ? parseFloat(minS) : 0;
    if (isNaN(max)) return;
    var raw = String(el.value).trim();
    if (raw === '' || raw === '-') return;
    if (!isBlur && /[eE]/.test(raw)) return;
    if (!isBlur && raw.slice(-1) === '.') return;
    var v = parseFloat(raw);
    if (isNaN(v)) return;
    var step = parseFloat(el.getAttribute('step')) || 1;
    if (!isBlur) {
        if (v > max) el.value = max;
        else if (v < min) el.value = min;
        return;
    }
    if (v > max) v = max;
    if (v < min) v = min;
    if (step && step < 1) {
        v = Math.round(v / step) * step;
        if (v > max) v = max;
        if (v < min) v = min;
    }
    el.value = v;
}

document.addEventListener(
    'input',
    function (e) {
        var t = e.target;
        if (t.tagName !== 'INPUT' || t.type !== 'number') return;
        if (!t.closest('.results-sheet-row') && !t.closest('#edit-result-modal')) return;
        clampScoreInput(t, false);
        if (t.classList.contains('results-score-input')) {
            var tt = String(t.value).trim();
            if (tt !== '' && tt !== '-') t.classList.remove('results-score-input-empty');
        }
    },
    true
);
document.addEventListener(
    'blur',
    function (e) {
        var t = e.target;
        if (t.tagName !== 'INPUT' || t.type !== 'number') return;
        if (!t.closest('.results-sheet-row') && !t.closest('#edit-result-modal')) return;
        clampScoreInput(t, true);
    },
    true
);

// ────────────────────────────────────────────────
// Theme Toggle Functions
// ────────────────────────────────────────────────
function getTheme() {
    return localStorage.getItem('theme') || 'light';
}

function setTheme(theme) {
    localStorage.setItem('theme', theme);
    const apply = function () {
        document.documentElement.setAttribute('data-theme', theme);
        updateThemeIcon(theme);
        updateThemeToggle(theme);
    };
    if (document.startViewTransition) {
        document.startViewTransition(apply);
    } else {
        apply();
    }
}

function toggleTheme() {
    const currentTheme = getTheme();
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    setTheme(newTheme);
}

function updateThemeIcon(theme) {
    const themeIcon = document.getElementById('theme-icon');
    if (themeIcon) {
        themeIcon.className = theme === 'dark'
            ? 'fas fa-sun text-base lg:text-sm'
            : 'fas fa-moon text-base lg:text-sm';
    }

    // Update mobile menu theme icon
    const mobileThemeIcon = document.getElementById('mobile-theme-icon');
    if (mobileThemeIcon) {
        mobileThemeIcon.className = theme === 'dark'
            ? 'fas fa-sun'
            : 'fas fa-moon';
    }

    // Update theme status text
    const themeStatus = document.getElementById('theme-status');
    if (themeStatus) {
        themeStatus.textContent = theme === 'dark'
            ? 'Switch to light mode'
            : 'Switch to dark mode';
    }
}

function updateThemeToggle(theme) {
    const toggle = document.getElementById('dark-mode-toggle');
    if (toggle) {
        toggle.checked = theme === 'dark';
    }

    // Update all toggle switches if there is multiple
    document.querySelectorAll('#dark-mode-toggle').forEach(t => {
        t.checked = theme === 'dark';
    });
}

// Initialize theme on a page load
function initTheme() {
    const savedTheme = getTheme();
    setTheme(savedTheme);
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    initTheme();
});

// Password Toggle
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Preloader
function startPreloader(ev, button) {
    if (ev && typeof ev.preventDefault === 'function') ev.preventDefault();
    const form = button.closest('form');
    if (!form) return;

    button.innerHTML = `
        <span class="flex items-center justify-center gap-2 z-10 relative button-loading-spinner">
            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <span>Processing...</span>
        </span>
    `;
    button.disabled = true;

    setTimeout(function () {
        form.submit();
    }, 500);
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('button[data-preloader], input[type="submit"][data-preloader]').forEach(function (btn) {
        btn.addEventListener('click', function (ev) {
            startPreloader(ev, this);
        });
    });
});

// Helper function to close Tailwind modals
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        // Prevent body scroll when modal is closed
        document.body.style.overflow = '';
    }
}

// Helper function to open Tailwind modals
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        // Prevent body scroll when modal is open
        document.body.style.overflow = 'hidden';
    }
}

// Toggle modal open/close state
function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        if (modal.classList.contains('hidden')) {
            openModal(modalId);
        } else {
            closeModal(modalId);
        }
    }
}

// Close modal when clicking backdrop
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        const modal = e.target.closest('.fixed.inset-0');
        if (modal && modal.id) {
            closeModal(modal.id);
        }
    }
});

// Close modal on an Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        // Close all visible modals
        document.querySelectorAll('.fixed.inset-0:not(.hidden)').forEach(modal => {
            if (modal.id) {
                closeModal(modal.id);
            }
        });
    }
});

// Profile Dropdown
function toggleProfileDropdown() {
    const dropdown = document.getElementById('profile-dropdown');
    dropdown.classList.toggle('hidden');
}

// Generic Dropdown Toggle Function
function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    if (dropdown) {
        // Close all other dropdowns first (include a mega menu)
        const allDropdowns = ['profile-dropdown', 'mega-menu-dropdown', 'invest-dropdown', 'transactions-dropdown', 'more-dropdown'];
        allDropdowns.forEach(id => {
            if (id !== dropdownId) {
                const otherDropdown = document.getElementById(id);
                if (otherDropdown) {
                    if (id === 'mega-menu-dropdown') {
                        otherDropdown.classList.remove('mega-menu-open');
                    } else {
                        otherDropdown.classList.add('hidden');
                    }
                }
            }
        });
        // Toggle the requested dropdown
        if (dropdownId === 'mega-menu-dropdown') {
            dropdown.classList.toggle('mega-menu-open');
        } else {
            dropdown.classList.toggle('hidden');
        }
        // Mega menu arrow rotation (admin + teacher use different ids)
        const megaArrow =
            document.getElementById('mega-menu-arrow') ||
            document.getElementById('teacher-mega-menu-arrow');
        if (megaArrow && dropdownId === 'mega-menu-dropdown') {
            megaArrow.style.transform = dropdown.classList.contains('mega-menu-open') ? 'rotate(180deg)' : 'rotate(0deg)';
        }
    }
}

// Mobile Menu Functions
function toggleMobileMenu() {
    const overlay = document.getElementById('mobile-menu-overlay');
    const panel = document.getElementById('mobile-menu-panel');

    if (overlay.classList.contains('active')) {
        closeMobileMenu();
    } else {
        overlay.classList.add('active');
        panel.classList.add('active');
        // Prevent body scroll when a menu is open
        document.body.style.overflow = 'hidden';
    }
}

function closeMobileMenu() {
    const overlay = document.getElementById('mobile-menu-overlay');
    const panel = document.getElementById('mobile-menu-panel');
    overlay.classList.remove('active');
    panel.classList.remove('active');
    // Restore body scroll
    document.body.style.overflow = '';
    // Close all mobile dropdowns when a menu closes
    document.querySelectorAll('.mobile-menu-dropdown-content').forEach(dropdown => {
        dropdown.classList.add('hidden');
    });
    document.querySelectorAll('.mobile-menu-dropdown').forEach(dropdown => {
        dropdown.classList.remove('active');
    });
}

// Toggle mobile menu dropdown
function toggleMobileDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    const dropdownContainer = dropdown.closest('.mobile-menu-dropdown');
    const arrowId = dropdownId.replace('-dropdown', '-arrow');
    const arrow = document.getElementById(arrowId);

    if (dropdown && dropdownContainer) {
        // Close all other dropdowns
        document.querySelectorAll('.mobile-menu-dropdown').forEach(container => {
            if (container !== dropdownContainer) {
                container.classList.remove('active');
                const otherDropdown = container.querySelector('.mobile-menu-dropdown-content');
                if (otherDropdown) {
                    otherDropdown.classList.add('hidden');
                }
            }
        });

        // Toggle the current dropdown
        dropdown.classList.toggle('hidden');
        dropdownContainer.classList.toggle('active');

        // Rotate arrow
        if (arrow) {
            if (dropdown.classList.contains('hidden')) {
                arrow.style.transform = 'rotate(0deg)';
            } else {
                arrow.style.transform = 'rotate(180deg)';
            }
        }
    }
}
