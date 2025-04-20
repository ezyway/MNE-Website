document.addEventListener("DOMContentLoaded", () => {
  const marquees = document.querySelectorAll(".icon-scroll__container");

  marquees.forEach(container => {
    let position = 0;
    let speed = 0.5;
    let isHovered = false;

    setTimeout(() => {
      const maxScroll = container.scrollWidth;

      if (maxScroll <= container.offsetWidth) return;

      const animate = () => {
        if (!isHovered) {
          position += speed;

          if (position >= maxScroll) {
            position = 0; // Reset to start when reaching end
          }

          container.style.transform = `translateX(${-position}px)`;
        }

        requestAnimationFrame(animate);
      };

      // Pause on hover
      container.parentElement.addEventListener("mouseenter", () => {
        isHovered = true;
      });
      container.parentElement.addEventListener("mouseleave", () => {
        isHovered = false;
      });

      animate();
    }, 100);
  });
});
