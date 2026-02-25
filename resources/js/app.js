/**
 * Balanacan Port e-Transact — Main JavaScript
 * Handles: mobile nav, announcement bar, departure time, search widget
 */

document.addEventListener('DOMContentLoaded', () => {

    // ============================================================
    // MOBILE MENU
    // ============================================================
    const hamburger  = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');

    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = mobileMenu.classList.toggle('is-open');
            hamburger.classList.toggle('is-open', isOpen);
            hamburger.setAttribute('aria-expanded', String(isOpen));
            mobileMenu.setAttribute('aria-hidden', String(!isOpen));
            hamburger.setAttribute('aria-label', isOpen ? 'Close menu' : 'Open menu');
        });

        // Close on link click inside mobile menu
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
                closeMobileMenu();
            }
        });

        // Close on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeMobileMenu();
        });
    }

    function closeMobileMenu() {
        if (!hamburger || !mobileMenu) return;
        mobileMenu.classList.remove('is-open');
        hamburger.classList.remove('is-open');
        hamburger.setAttribute('aria-expanded', 'false');
        mobileMenu.setAttribute('aria-hidden', 'true');
        hamburger.setAttribute('aria-label', 'Open menu');
    }

    // ============================================================
    // ANNOUNCEMENT BAR DISMISS
    // ============================================================
    const closeBtn        = document.getElementById('closeAnnouncement');
    const announcementBar = document.getElementById('announcementBar');

    if (closeBtn && announcementBar) {
        if (sessionStorage.getItem('announcementDismissed') === 'true') {
            announcementBar.classList.add('hidden');
        }

        closeBtn.addEventListener('click', () => {
            announcementBar.classList.add('hidden');
            sessionStorage.setItem('announcementDismissed', 'true');
        });
    }

    // ============================================================
    // HIDE LOADING OVERLAY (if shown)
    // ============================================================
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.classList.remove('active');
    }

    // ============================================================
    // LIVE DEPARTURE TIME
    // ============================================================
    const departureEl = document.getElementById('nextDeparture');

    if (departureEl) {
        const departureTimes = ['05:00', '07:00', '09:00', '11:00', '13:00', '15:00', '17:00', '19:00'];

        function getNextDeparture() {
            const now     = new Date();
            const nowMins = now.getHours() * 60 + now.getMinutes();

            for (const timeStr of departureTimes) {
                const [h, m]   = timeStr.split(':').map(Number);
                const timeMins = h * 60 + m;
                if (timeMins > nowMins) return timeStr;
            }
            return departureTimes[0] + ' (tmrw)';
        }

        departureEl.textContent = getNextDeparture();

        setInterval(() => {
            departureEl.textContent = getNextDeparture();
        }, 60_000);
    }

    // ============================================================
    // SEARCH WIDGET — Set today's date as default
    // ============================================================
    const dateInput = document.getElementById('qDate');
    if (dateInput && !dateInput.value) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.value = today;
        dateInput.min   = today;
    }

    // ============================================================
    // SEARCH WIDGET — Pass values to booking page via URL params
    // ============================================================
    const searchBtn = document.getElementById('searchBtn');
    const routeSel  = document.getElementById('qRoute');
    const paxInput  = document.getElementById('qPax');

    if (searchBtn && routeSel && dateInput && paxInput) {
        searchBtn.addEventListener('click', (e) => {
            const route = routeSel.value;
            const date  = dateInput.value;
            const pax   = paxInput.value;

            if (!route) {
                e.preventDefault();
                routeSel.focus();
                routeSel.style.borderColor = '#dc2626';
                setTimeout(() => { routeSel.style.borderColor = ''; }, 2000);
                return;
            }

            if (!date) {
                e.preventDefault();
                dateInput.focus();
                dateInput.style.borderColor = '#dc2626';
                setTimeout(() => { dateInput.style.borderColor = ''; }, 2000);
                return;
            }

            const url = new URL(searchBtn.href);
            url.searchParams.set('route', route);
            url.searchParams.set('date',  date);
            url.searchParams.set('pax',   pax || 1);
            searchBtn.href = url.toString();
        });
    }

    // ============================================================
    // STICKY HEADER shadow on scroll
    // ============================================================
    const header = document.getElementById('siteHeader');
    if (header) {
        const onScroll = () => {
            header.style.boxShadow = window.scrollY > 8
                ? '0 4px 16px rgba(0,0,0,0.10)'
                : '';
        };
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    // ============================================================
    // FARE MATRIX TABS
    // ============================================================
    document.querySelectorAll('.fare-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.fare-tab').forEach(t => {
                t.classList.remove('is-active');
                t.setAttribute('aria-selected', 'false');
            });
            document.querySelectorAll('.fare-panel').forEach(p => {
                p.classList.remove('is-active');
            });

            tab.classList.add('is-active');
            tab.setAttribute('aria-selected', 'true');
            const panel = document.getElementById(tab.dataset.tab);
            if (panel) panel.classList.add('is-active');
        });
    });

});