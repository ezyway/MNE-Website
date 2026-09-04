document.addEventListener('DOMContentLoaded', () => {
    const whatsapp = document.querySelector('.whatsapp-float');
    const footer = document.querySelector('.site-footer') || document.querySelector('.footer');
    const cookieBanner = document.getElementById('cookieConsent');

    if (!whatsapp) return;

    let ticking = false;
    let lastCookieOffset = '';

    const getCookieOffset = () => {
        // Clear the FAB whenever the cookie banner is on screen.
        if (cookieBanner && cookieBanner.classList.contains('is-visible')) {
            return `${cookieBanner.getBoundingClientRect().height + 16}px`;
        }
        return '';
    };

    const updatePosition = () => {
        // 1) Nudge the button above the cookie banner when it is visible.
        const cookieOffset = getCookieOffset();
        if (cookieOffset !== lastCookieOffset) {
            whatsapp.style.bottom = cookieOffset;
            lastCookieOffset = cookieOffset;
        }

        // 2) Retreat (fade + slide down) only once the footer starts to fill
        //    the lower viewport, so the button never rides up over content or
        //    jumps around while scrolling the footer.
        if (footer) {
            const footerTop = footer.getBoundingClientRect().top;
            const threshold = window.innerHeight - 100;
            whatsapp.classList.toggle('whatsapp-float--retreat', footerTop < threshold);
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

    // React when the cookie banner is accepted/dismissed.
    const observer = new MutationObserver(updatePosition);
    if (cookieBanner) {
        observer.observe(cookieBanner, { attributes: true, attributeFilter: ['class'] });
    }

    updatePosition();
});
