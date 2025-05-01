
document.addEventListener('DOMContentLoaded', () => {
    const whatsapp = document.querySelector('.whatsapp-float');
    const footer = document.querySelector('.footer');

    const updatePosition = () => {
        const rect = footer.getBoundingClientRect();
        const viewportH = window.innerHeight;

        if (rect.top < viewportH) {
            // footer is visible → push the button up by the amount it would overlap + 1em buffer
            const overlap = viewportH - rect.top;
            whatsapp.style.bottom = `${overlap + 16}px`;
        } else {
            // footer not yet visible → stick at bottom, takes value from css
            whatsapp.style.bottom = '';
        }
    };

    window.addEventListener('scroll', updatePosition);
    window.addEventListener('resize', updatePosition);
    updatePosition();

});