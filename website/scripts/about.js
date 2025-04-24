document.addEventListener("DOMContentLoaded", () => {
    const counters = document.querySelectorAll('.stats__number');

    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'), 10);
        const suffix = counter.getAttribute('data-suffix') || '';
        let count = 0;
        const duration = 2000; // total time for animation in ms
        const increment = target / (duration / 20);

        const updateCount = () => {
            count += increment;
            if (count < target) {
                counter.innerText = Math.ceil(count) + suffix;
                setTimeout(updateCount, 20);
            } else {
                counter.innerText = target + suffix;
            }
        };

        updateCount();
    });
});