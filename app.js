document.querySelectorAll('[data-counter-for]').forEach((counter) => {
    const field = document.getElementById(counter.dataset.counterFor);
    if (!field) return;

    const updateCounter = () => {
        counter.textContent = `${field.value.length} / ${field.maxLength}`;
    };

    field.addEventListener('input', updateCounter);
    updateCounter();
});
document.querySelectorAll('[data-quote-composer]').forEach((form) => {
    const quoteField = form.querySelector('textarea[name="text"]');
    const preview = form.querySelector('[data-quote-preview]');
    const previewText = form.querySelector('[data-preview-text]');
    const themeFields = form.querySelectorAll('input[name="theme"]');
    if (!quoteField || !preview || !previewText) return;

    const updatePreview = () => {
        previewText.textContent = quoteField.value.trim() || 'اكتب اقتباسك ليظهر هنا.';
    };

    const updateTheme = () => {
        const selectedTheme = form.querySelector('input[name="theme"]:checked')?.value || 'paper';
        preview.classList.remove('theme-paper', 'theme-lemon', 'theme-sea', 'theme-lilac', 'theme-coral');
        preview.classList.add(`theme-${selectedTheme}`);
    };

    quoteField.addEventListener('input', updatePreview);
    themeFields.forEach((field) => field.addEventListener('change', updateTheme));
    updatePreview();
    updateTheme();
});

const menuToggle = document.querySelector('[data-menu-toggle]');
const drawer = document.querySelector('[data-menu-drawer]');
const backdrop = document.querySelector('[data-menu-backdrop]');
const closeButton = document.querySelector('[data-menu-close]');
if (menuToggle && drawer && backdrop) {
    const setMenuOpen = (open) => {
        menuToggle.setAttribute('aria-expanded', String(open));
        menuToggle.setAttribute('aria-label', open ? 'أغلق القائمة' : 'افتح القائمة');
        drawer.setAttribute('aria-hidden', String(!open));
        drawer.dataset.open = String(open);
        backdrop.hidden = !open;
        document.body.classList.toggle('drawer-open', open);
        if (open) closeButton?.focus();
        else menuToggle.focus();
    };
    menuToggle.addEventListener('click', () => setMenuOpen(menuToggle.getAttribute('aria-expanded') !== 'true'));
    closeButton?.addEventListener('click', () => setMenuOpen(false));
    backdrop.addEventListener('click', () => setMenuOpen(false));
    drawer.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setMenuOpen(false)));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && menuToggle.getAttribute('aria-expanded') === 'true') setMenuOpen(false);
    });
}
