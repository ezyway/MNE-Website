document.addEventListener("DOMContentLoaded", () => {

	// =====================================================
	// Marquee Section
	// =====================================================
	const marquees = document.querySelectorAll(".icon-scroll__container");

	marquees.forEach(container => {
		let isHovered = false;
		let position = 0;
		const speed = 0.5;

		// Clone all children
		const clone = container.cloneNode(true);
		while (clone.children.length > 0) {
			container.appendChild(clone.children[0]);
		}

		// Get original content width
		const originalWidth = [...container.children]
			.slice(0, container.children.length / 2)
			.reduce((acc, el) => acc + el.offsetWidth, 0);

		// Set container width to prevent wrapping
		container.style.whiteSpace = 'nowrap';

		// Hover events
		container.parentElement.addEventListener("mouseenter", () => {
			isHovered = true;
		});
		container.parentElement.addEventListener("mouseleave", () => {
			isHovered = false;
		});

		function animate() {
			if (!isHovered) {
				position += speed;
				if (position >= originalWidth) {
					position = 0;
				}
				container.style.transform = `translateX(-${position}px)`;
			}
			requestAnimationFrame(animate);
		}
		animate();
	});


	// =====================================================
	// Bruce Banner Section
	// =====================================================
	const track = document.querySelector(".banner__track");
	const slides = document.querySelectorAll(".banner__slide");
	const prevBtn = document.querySelector(".banner__nav--prev");
	const nextBtn = document.querySelector(".banner__nav--next");

	let index = 0;
	let autoScroll;
	const slideCount = slides.length;

	function setPosition() {
		track.style.transform = `translateX(-${index * 100}%)`;
	}

	function goToSlide(i) {
		index = (i + slideCount) % slideCount;
		setPosition();
	}

	function nextSlide() {
		goToSlide(index + 1);
	}

	function prevSlide() {
		goToSlide(index - 1);
	}

	function startAutoScroll() {
		autoScroll = setInterval(nextSlide, 3000);
	}

	function stopAutoScroll() {
		clearInterval(autoScroll);
	}

	prevBtn.addEventListener("click", () => {
		stopAutoScroll();
		prevSlide();
		startAutoScroll();
	});

	nextBtn.addEventListener("click", () => {
		stopAutoScroll();
		nextSlide();
		startAutoScroll();
	});

	// Init
	setPosition();
	startAutoScroll();


	// =====================================================
	// About Section View Fade-In
	// =====================================================
	const aboutSection = document.querySelector(".about");

	if (!aboutSection) return;

	const observer = new IntersectionObserver(
		([entry]) => {
			if (entry.isIntersecting) {
				aboutSection.classList.add("about--visible");
				observer.disconnect();
			}
		},
		{ threshold: 0.2 }
	);

	observer.observe(aboutSection);
});