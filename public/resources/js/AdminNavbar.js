(() => {
    'use strict';
    const initialize = () => {
        const input = document.getElementById('adminModuleSearch');
        const form = document.getElementById('adminModuleSearchForm');
        const results = document.getElementById('adminModuleResults');
        const userMenu = document.getElementById('adminUserMenu');
        if (!input || !form || !results || !userMenu) return;
        const normalize = value => value.toLocaleLowerCase('id-ID').replace(/\s+/g, ' ').trim();
        const modules = [];
        const seen = new Set();
        document.querySelectorAll('.nav-sidebar a.nav-link').forEach(link => {
            const raw = link.getAttribute('href');
            if (!raw || raw === '#') return;
            const url = new URL(raw, window.location.origin);
            if (url.origin !== window.location.origin || seen.has(url.pathname + url.search)) return;
            const label = link.querySelector('p')?.textContent.trim() || link.textContent.trim();
            if (!label) return;
            seen.add(url.pathname + url.search);
            const sidebarIcon = link.querySelector('i.nav-icon, .nav-icon i, i');
            const iconClasses = sidebarIcon
                ? Array.from(sidebarIcon.classList).filter(name => /^(?:fa|fas|far|fab|fal|fad|fat|fa-[a-z0-9-]+)$/.test(name)).join(' ')
                : 'fas fa-circle';
            modules.push({ label, iconClasses, url: url.href, keywords: normalize(label + ' ' + url.pathname) });
        });
        let matches = [];
        let selected = -1;
        const close = () => {
            results.hidden = true;
            input.setAttribute('aria-expanded', 'false');
            input.removeAttribute('aria-activedescendant');
            selected = -1;
        };
        const select = index => {
            selected = index;
            results.querySelectorAll('a').forEach((link, i) => {
                link.classList.toggle('active', i === index);
                link.setAttribute('aria-selected', String(i === index));
                if (i === index) { input.setAttribute('aria-activedescendant', link.id); link.scrollIntoView({ block: 'nearest' }); }
            });
        };
        const search = () => {
            const query = normalize(input.value);
            results.replaceChildren();
            matches = [];
            selected = -1;
            input.removeAttribute('aria-activedescendant');
            if (!query) { close(); return; }
            matches = modules.filter(module => query.split(' ').every(word => module.keywords.includes(word))).slice(0, 10);
            matches.forEach((module, index) => {
                const link = document.createElement('a');
                link.id = 'admin-module-result-' + index;
                link.className = 'list-group-item list-group-item-action py-2 d-flex align-items-center';
                link.href = module.url;
                const icon = document.createElement('i');
                icon.className = (module.iconClasses || 'fas fa-circle') + ' fa-fw mr-2';
                icon.setAttribute('aria-hidden', 'true');
                const label = document.createElement('span');
                label.textContent = module.label;
                link.append(icon, label);
                link.setAttribute('role', 'option');
                link.setAttribute('aria-selected', 'false');
                results.append(link);
            });
            if (!matches.length) {
                const empty = document.createElement('div');
                empty.className = 'px-3 py-2 text-muted small';
                empty.textContent = 'Modul atau fitur tidak ditemukan.';
                results.append(empty);
            }
            results.hidden = false;
            input.setAttribute('aria-expanded', 'true');
        };
        input.addEventListener('input', search);
        input.addEventListener('focus', () => { if (input.value.trim()) search(); });
        input.addEventListener('keydown', event => {
            if (event.key === 'Escape') { close(); return; }
            if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                event.preventDefault();
                if (results.hidden) search();
                if (!matches.length) return;
                select(event.key === 'ArrowDown' ? (selected + 1) % matches.length : (selected <= 0 ? matches.length - 1 : selected - 1));
            }
        });
        form.addEventListener('submit', event => {
            event.preventDefault();
            if (results.hidden) search();
            const target = matches[selected < 0 ? 0 : selected];
            if (target) window.location.assign(target.url);
        });
        document.addEventListener('click', event => { if (!form.contains(event.target)) close(); });
        form.addEventListener('focusout', event => { if (!form.contains(event.relatedTarget)) close(); });

        const positionToasts = () => {
            const dropdown = userMenu.querySelector('.dropdown-menu.show');
            if (dropdown) {
                document.documentElement.style.setProperty('--admin-toast-top', Math.ceil(dropdown.getBoundingClientRect().bottom + 12) + 'px');
            } else {
                document.documentElement.style.removeProperty('--admin-toast-top');
            }
        };
        if (window.jQuery) {
            window.jQuery(userMenu).on('shown.bs.dropdown', () => { close(); positionToasts(); });
            window.jQuery(userMenu).on('hidden.bs.dropdown', positionToasts);
        }
        window.addEventListener('resize', positionToasts);
    };
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initialize);
    else initialize();
})();
