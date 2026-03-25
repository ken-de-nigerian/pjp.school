/**
 * Progressive enhancement: replace native chrome for `select.md3-native-select` with a
 * searchable floating panel while keeping the real <select> for forms and change listeners.
 */
const ENHANCED_FLAG = 'data-md3-enhanced';

function isEnhanced(select) {
    return select.getAttribute(ENHANCED_FLAG) === '1';
}

function markEnhanced(select) {
    select.setAttribute(ENHANCED_FLAG, '1');
}

const PANEL_GAP_PX = 8;
const VIEW_MARGIN_PX = 8;
/** ~20rem max height cap; options list scrolls inside */
const PANEL_MAX_HEIGHT_PX = 320;
/** Below intl-tel-country-modal (10050); above app chrome */
const PANEL_Z_INDEX = 10040;

function resetPanelLayer(panel, wrapper) {
    panel.classList.remove('md3-select__panel--layer');
    if (panel.parentNode === document.body && wrapper) {
        wrapper.appendChild(panel);
    }
    panel.style.position = '';
    panel.style.top = '';
    panel.style.left = '';
    panel.style.right = '';
    panel.style.bottom = '';
    panel.style.width = '';
    panel.style.maxHeight = '';
    panel.style.zIndex = '';
}

function detachPanelListeners(wrapper) {
    if (wrapper._md3OnDocClick) {
        document.removeEventListener('click', wrapper._md3OnDocClick);
        wrapper._md3OnDocClick = null;
    }
    if (wrapper._md3OnKeydown) {
        document.removeEventListener('keydown', wrapper._md3OnKeydown);
        wrapper._md3OnKeydown = null;
    }
    if (wrapper._md3OnScroll) {
        window.removeEventListener('scroll', wrapper._md3OnScroll, true);
        window.removeEventListener('resize', wrapper._md3OnScroll);
        wrapper._md3OnScroll = null;
    }
}

function closePanel(wrapper, trigger, panel, filterInput) {
    if (panel.hidden) {
        return;
    }
    detachPanelListeners(wrapper);
    panel.hidden = true;
    trigger.setAttribute('aria-expanded', 'false');
    resetPanelLayer(panel, wrapper);
    if (filterInput) {
        filterInput.value = '';
    }
}

function enhanceSelect(select) {
    if (isEnhanced(select)) {
        return;
    }
    if (select.hasAttribute('multiple')) {
        return;
    }
    if (select.closest('.md3-select-enhanced')) {
        return;
    }

    markEnhanced(select);

    const parent = select.parentNode;
    const wrapper = document.createElement('div');
    wrapper.className = 'md3-select-enhanced';

    parent.insertBefore(wrapper, select);
    wrapper.appendChild(select);

    select.classList.add('md3-select-native-hidden');
    select.setAttribute('tabindex', '-1');

    const filterPlaceholder =
        select.getAttribute('data-md-filter-placeholder') || 'Filter...';

    const trigger = document.createElement('button');
    trigger.type = 'button';
    trigger.className = 'md3-select__trigger';
    trigger.setAttribute('aria-haspopup', 'listbox');
    trigger.setAttribute('aria-expanded', 'false');
    if (select.disabled) {
        trigger.disabled = true;
    }

    const selectId = select.id;
    if (selectId) {
        trigger.setAttribute('aria-controls', `${selectId}-listbox`);
    }

    const valueSpan = document.createElement('span');
    valueSpan.className = 'md3-select__trigger-value';
    trigger.appendChild(valueSpan);

    const chevron = document.createElement('span');
    chevron.className = 'md3-select__trigger-chevron';
    chevron.setAttribute('aria-hidden', 'true');
    trigger.appendChild(chevron);

    const panel = document.createElement('div');
    panel.className = 'md3-select__panel';
    panel.hidden = true;
    panel.setAttribute('role', 'listbox');
    if (selectId) {
        panel.id = `${selectId}-listbox`;
    }

    const searchWrap = document.createElement('div');
    searchWrap.className = 'md3-select__search-wrap';

    const searchInner = document.createElement('div');
    searchInner.className = 'md3-select__search';

    const filterInput = document.createElement('input');
    filterInput.type = 'text';
    filterInput.className = 'md3-select__filter form-input';
    filterInput.placeholder = filterPlaceholder;
    filterInput.autocomplete = 'off';
    filterInput.setAttribute('aria-label', filterPlaceholder);

    searchInner.appendChild(filterInput);
    searchWrap.appendChild(searchInner);

    const optionsWrap = document.createElement('div');
    optionsWrap.className = 'md3-select__options';

    panel.appendChild(searchWrap);
    panel.appendChild(optionsWrap);

    wrapper.appendChild(trigger);
    wrapper.appendChild(panel);

    function addOptionButton(opt, container) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'md3-select__option';
        btn.setAttribute('role', 'option');
        btn.dataset.value = opt.value;
        const span = document.createElement('span');
        span.className = 'md3-select__option-label';
        span.textContent = opt.textContent;
        btn.appendChild(span);
        if (opt.disabled) {
            btn.disabled = true;
        }
        const selected = opt.value === select.value;
        btn.setAttribute('aria-selected', selected ? 'true' : 'false');
        if (selected) {
            btn.classList.add('md3-select__option--selected');
        }
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (opt.disabled) {
                return;
            }
            select.value = opt.value;
            select.dispatchEvent(new Event('change', { bubbles: true }));
            updateTrigger();
            closePanel(wrapper, trigger, panel, filterInput);
            trigger.focus();
        });
        container.appendChild(btn);
    }

    function buildOptionList() {
        optionsWrap.replaceChildren();
        for (const child of select.children) {
            if (child.tagName === 'OPTGROUP') {
                const groupLabel = document.createElement('div');
                groupLabel.className = 'md3-select__optgroup-label';
                groupLabel.textContent = child.label || '';
                optionsWrap.appendChild(groupLabel);
                for (const opt of child.children) {
                    if (opt.tagName === 'OPTION') {
                        addOptionButton(opt, optionsWrap);
                    }
                }
            } else if (child.tagName === 'OPTION') {
                addOptionButton(child, optionsWrap);
            }
        }
    }

    function updateTrigger() {
        const idx = select.selectedIndex;
        const sel = idx >= 0 ? select.options[idx] : null;
        if (!sel) {
            valueSpan.textContent = '\u00a0';
            valueSpan.classList.add('md3-select__trigger-value--placeholder');
            return;
        }
        valueSpan.textContent = sel.textContent.trim() || '\u00a0';
        if (sel.value === '') {
            valueSpan.classList.add('md3-select__trigger-value--placeholder');
        } else {
            valueSpan.classList.remove('md3-select__trigger-value--placeholder');
        }
    }

    function filterOptions(query) {
        const lower = query.trim().toLowerCase();
        optionsWrap.querySelectorAll('.md3-select__option').forEach((btn) => {
            const label = btn.querySelector('.md3-select__option-label');
            const t = (label?.textContent || '').toLowerCase();
            btn.hidden = Boolean(lower && !t.includes(lower));
        });
    }

    function placePanel() {
        if (panel.hidden) {
            return;
        }
        /* `x-forms.md-select`: full control is `.m3-select` (icon + field). Measuring only the
         * trigger omits the icon column, so the panel looked too narrow on auth/check-result. */
        const anchor = trigger.closest('.m3-select') || trigger;
        const rect = anchor.getBoundingClientRect();
        const vh = window.innerHeight;
        const vw = window.innerWidth;

        if (rect.bottom < 0 || rect.top > vh) {
            closePanel(wrapper, trigger, panel, filterInput);
            return;
        }

        const width = Math.max(1, Math.round(rect.width));
        let left = Math.round(rect.left);
        if (left + width > vw - VIEW_MARGIN_PX) {
            left = Math.max(VIEW_MARGIN_PX, vw - VIEW_MARGIN_PX - width);
        }
        if (left < VIEW_MARGIN_PX) {
            left = VIEW_MARGIN_PX;
        }

        const openBelowTop = Math.round(rect.bottom + PANEL_GAP_PX);
        const spaceBelow = vh - openBelowTop - VIEW_MARGIN_PX;
        const spaceAbove = rect.top - VIEW_MARGIN_PX - PANEL_GAP_PX;
        const openAbove = spaceBelow < 160 && spaceAbove > spaceBelow;

        let maxH;
        if (openAbove) {
            maxH = Math.min(PANEL_MAX_HEIGHT_PX, Math.max(120, spaceAbove));
            panel.style.top = 'auto';
            panel.style.bottom = `${Math.round(vh - rect.top + PANEL_GAP_PX)}px`;
        } else {
            maxH = Math.min(PANEL_MAX_HEIGHT_PX, Math.max(120, spaceBelow));
            panel.style.top = `${openBelowTop}px`;
            panel.style.bottom = 'auto';
        }

        panel.style.position = 'fixed';
        panel.style.left = `${left}px`;
        panel.style.right = 'auto';
        panel.style.width = `${width}px`;
        panel.style.maxHeight = `${Math.round(maxH)}px`;
        panel.style.zIndex = String(PANEL_Z_INDEX);
    }

    function openPanel() {
        if (select.disabled) {
            return;
        }
        detachPanelListeners(wrapper);

        buildOptionList();
        filterInput.value = '';
        filterOptions('');

        document.body.appendChild(panel);
        panel.classList.add('md3-select__panel--layer');

        panel.hidden = false;
        trigger.setAttribute('aria-expanded', 'true');

        placePanel();

        wrapper._md3OnScroll = () => {
            if (!panel.hidden) {
                requestAnimationFrame(() => placePanel());
            }
        };
        window.addEventListener('scroll', wrapper._md3OnScroll, true);
        window.addEventListener('resize', wrapper._md3OnScroll);

        const fieldRoot = trigger.closest('.m3-select') || wrapper;
        wrapper._md3OnDocClick = (e) => {
            const t = e.target;
            if (fieldRoot.contains(t) || panel.contains(t)) {
                return;
            }
            closePanel(wrapper, trigger, panel, filterInput);
        };
        wrapper._md3OnKeydown = (e) => {
            if (e.key === 'Escape') {
                e.preventDefault();
                closePanel(wrapper, trigger, panel, filterInput);
                trigger.focus();
            }
        };

        setTimeout(() => {
            document.addEventListener('click', wrapper._md3OnDocClick);
            document.addEventListener('keydown', wrapper._md3OnKeydown);
        }, 0);

        requestAnimationFrame(() => {
            placePanel();
            filterInput.focus();
        });
    }

    trigger.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (select.disabled) {
            return;
        }
        if (panel.hidden) {
            openPanel();
        } else {
            closePanel(wrapper, trigger, panel, filterInput);
        }
    });

    filterInput.addEventListener('input', () => {
        filterOptions(filterInput.value);
    });
    filterInput.addEventListener('click', (e) => e.stopPropagation());

    select.addEventListener('change', updateTrigger);

    const form = select.form;
    if (form) {
        form.addEventListener('reset', () => {
            setTimeout(() => {
                updateTrigger();
                if (!panel.hidden) {
                    buildOptionList();
                    requestAnimationFrame(() => placePanel());
                }
            }, 0);
        });
    }

    const mo = new MutationObserver(() => {
        trigger.disabled = select.disabled;
        updateTrigger();
        if (!panel.hidden) {
            buildOptionList();
            filterOptions(filterInput.value);
            requestAnimationFrame(() => placePanel());
        }
    });
    mo.observe(select, { childList: true, subtree: true, attributes: true, attributeFilter: ['disabled'] });

    buildOptionList();
    updateTrigger();
}

/** Re-run after dynamic option/value changes if the native `change` event was not fired. */
export function refreshMdSelect(select) {
    if (!select || !select.classList.contains('md3-native-select')) {
        return;
    }
    const wrapper = select.closest('.md3-select-enhanced');
    if (!wrapper) {
        return;
    }
    const ev = new Event('change', { bubbles: true });
    select.dispatchEvent(ev);
}

export function initMdSelectEnhancements(root = document) {
    root.querySelectorAll('select.md3-native-select').forEach((select) => {
        enhanceSelect(select);
    });
}

function observeNewNodes(root = document.body) {
    const observer = new MutationObserver((mutations) => {
        for (const m of mutations) {
            for (const n of m.addedNodes) {
                if (n.nodeType !== Node.ELEMENT_NODE) {
                    continue;
                }
                if (n.matches?.('select.md3-native-select')) {
                    enhanceSelect(n);
                }
                n.querySelectorAll?.('select.md3-native-select').forEach((sel) => {
                    enhanceSelect(sel);
                });
            }
        }
    });
    observer.observe(root, { childList: true, subtree: true });
}

if (typeof window !== 'undefined') {
    window.refreshMdSelect = refreshMdSelect;
}

export function bootMdSelectEnhancements() {
    initMdSelectEnhancements();
    observeNewNodes();
}
