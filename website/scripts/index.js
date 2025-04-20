document.addEventListener("DOMContentLoaded", () => {
    const marquees = document.querySelectorAll(".icon-scroll__container");
  
    marquees.forEach(container => {
      let direction = 1;
      let position = 0;
      let speed = 0.5;
      let isHovered = false;
      let bouncing = false;
  
      // Wait until everything's laid out
      setTimeout(() => {
        const maxScroll = container.scrollWidth - container.offsetWidth;
  
        if (maxScroll <= 0) return;
  
        const bounceDistance = 20;
        const bounceSpeed = 1;
        const pauseTime = 300;
  
        const animate = () => {
          if (!isHovered && !bouncing) {
            position += direction * speed;
  
            if (position >= maxScroll) {
              position = maxScroll + bounceDistance;
              bouncing = true;
              bounceBack(maxScroll);
            } else if (position <= 0) {
              position = -bounceDistance;
              bouncing = true;
              bounceBack(0);
            }
  
            container.style.transform = `translateX(${-position}px)`;
          }
  
          requestAnimationFrame(animate);
        };
  
        const bounceBack = (target) => {
          const bounceStep = () => {
            if (Math.abs(position - target) < bounceSpeed) {
              position = target;
              container.style.transform = `translateX(${-position}px)`;
  
              setTimeout(() => {
                direction *= -1;
                bouncing = false;
              }, pauseTime);
            } else {
              position += (target - position) * 0.2;
              container.style.transform = `translateX(${-position}px)`;
              requestAnimationFrame(bounceStep);
            }
          };
          bounceStep();
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
  