const menuButton = document.querySelector('[data-menu-button]');
const navigation = document.querySelector('[data-site-nav]');

if (menuButton && navigation) {
    const pageRegions = [document.querySelector('main'), document.querySelector('footer')].filter(Boolean);
    const focusable = () => [...navigation.querySelectorAll('a[href], button:not([disabled])')];
    const closeMenu = () => {
        menuButton.setAttribute('aria-expanded', 'false');
        navigation.removeAttribute('data-open');
        document.body.style.removeProperty('overflow');
        pageRegions.forEach((region) => region.removeAttribute('inert'));
    };
    menuButton.addEventListener('click', () => {
        const isOpen = menuButton.getAttribute('aria-expanded') === 'true';
        menuButton.setAttribute('aria-expanded', String(!isOpen));
        navigation.toggleAttribute('data-open', !isOpen);
        document.body.style.overflow = isOpen ? '' : 'hidden';
        pageRegions.forEach((region) => region.toggleAttribute('inert', !isOpen));
        if (!isOpen) focusable()[0]?.focus();
    });
    navigation.addEventListener('click', (event) => {
        if (event.target.closest('a')) closeMenu();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && navigation.hasAttribute('data-open')) {
            closeMenu();
            menuButton.focus();
        }
        if (event.key === 'Tab' && navigation.hasAttribute('data-open')) {
            const items = focusable();
            const first = items[0];
            const last = items.at(-1);
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    });
}

const message = document.querySelector('#message');
const counter = document.querySelector('[data-character-count]');
if (message && counter) {
    const updateCount = () => counter.textContent = `${message.value.length.toLocaleString()} / 2,000`;
    message.addEventListener('input', updateCount);
    updateCount();
}

const form = document.querySelector('[data-inquiry-form]');
const submitButton = document.querySelector('[data-submit-button]');
form?.addEventListener('submit', () => {
    if (!submitButton || !form.checkValidity()) return;
    submitButton.disabled = true;
    submitButton.querySelector('span').textContent = 'Sending request…';
});

const focusNotice = document.querySelector('[data-focus-notice]');
if (focusNotice) {
    focusNotice.focus({ preventScroll: true });
    focusNotice.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

/**
 * Motion. Everything here is progressive enhancement: the stylesheet renders
 * the finished state by default, and `js-motion` is only added when motion is
 * welcome, so a visitor with reduced-motion set — or with this script blocked —
 * still gets the complete page.
 */
const motionAllowed = window.matchMedia('(prefers-reduced-motion: reduce)').matches === false;

if (motionAllowed && 'IntersectionObserver' in window) {
    document.body.classList.add('js-motion');

    // Stagger grouped items before anything is observed, so delays are in place
    // on the first paint rather than applied late.
    document.querySelectorAll('[data-stagger]').forEach((group) => {
        [...group.children].forEach((child, index) => {
            child.style.setProperty('--reveal-delay', String(index * 90));
        });
    });

    // Every contour in a schematic is drawn rather than switched on, so each
    // path needs its measured length before the stroke can travel it. Labels
    // follow the lines in.
    document.querySelectorAll('.schematic path').forEach((path) => {
        if (typeof path.getTotalLength !== 'function') return;
        path.classList.add('draw');
        try {
            path.style.setProperty('--len', String(Math.ceil(path.getTotalLength())));
        } catch (error) {
            path.classList.remove('draw');
        }
    });

    document.querySelectorAll('.schematic text').forEach((label) => label.classList.add('fade'));

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-in');
            observer.unobserve(entry.target);
        });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.12 });

    document.querySelectorAll('.reveal, .schematic').forEach((element) => observer.observe(element));
}
