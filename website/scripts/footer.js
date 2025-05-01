
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

//     let firstClick = false;

// whatsapp.addEventListener('click', function (e) {
//     const isMobile = window.innerWidth <= 768;

//     if (isMobile && !firstClick) {
//         e.preventDefault(); // stop link
//         whatsapp.classList.add('expanded'); // optional for styling
//         const text = whatsapp.querySelector('.whatsapp-text');
//         text.style.opacity = '1';
//         text.style.maxWidth = '200px';
//         text.style.paddingRight = '1em';
//         firstClick = true;

//         // Optional: reset after some time if user doesn't click again
//         setTimeout(() => {
//             firstClick = false;
//             text.style.opacity = '0';
//             text.style.maxWidth = '0';
//             text.style.paddingRight = '0';
//             whatsapp.classList.remove('expanded');
//         }, 5000);
//     }
// });

});