
document.addEventListener('DOMContentLoaded', () => {
    const whatsapp = document.querySelector('.whatsapp-float');
    const footer = document.querySelector('.site-footer') || document.querySelector('.footer');

    if (!whatsapp || !footer) return;

    let ticking = false;
    const updatePosition = () => {
        const rect = footer.getBoundingClientRect();
        const viewportH = window.innerHeight;

        if (rect.top < viewportH) {
            const overlap = viewportH - rect.top;
            whatsapp.style.bottom = `${overlap + 24}px`;
        } else {
            whatsapp.style.bottom = '';
        }
        ticking = false;
    };

    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(updatePosition);
            ticking = true;
        }
    }, { passive: true });

    window.addEventListener('resize', updatePosition, { passive: true });
    updatePosition();
});