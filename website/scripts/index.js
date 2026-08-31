/**
 * Maruti Nandan Exports — Homepage Interactivity
 * Optimized for 60fps Micro-interactions & Accessibility
 */
document.addEventListener("DOMContentLoaded", () => {

	const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

	// =====================================================
	// 1. Scroll Reveal Intersection Observer
	// =====================================================
	const revealElements = document.querySelectorAll(".reveal-init");

	if (revealElements.length > 0) {
		if (prefersReducedMotion) {
			revealElements.forEach(el => el.classList.add("is-revealed"));
		} else {
			const revealObserver = new IntersectionObserver((entries, observer) => {
				entries.forEach((entry, idx) => {
					if (entry.isIntersecting) {
						// Staggered reveal for sibling bento & feature cards
						setTimeout(() => {
							entry.target.classList.add("is-revealed");
						}, 60);
						observer.unobserve(entry.target);
					}
				});
			}, {
				threshold: 0.12,
				rootMargin: "0px 0px -40px 0px"
			});

			revealElements.forEach(el => revealObserver.observe(el));
		}
	}

	// =====================================================
	// 2. Infinite Marquee Seamless Looping
	// =====================================================
	const marquees = [
		document.getElementById("licencesTrack"),
		document.getElementById("shippingTrack")
	].filter(Boolean);

	marquees.forEach(track => {
		// Clone items once for seamless CSS translateX infinite loop
		const clone = track.cloneNode(true);
		clone.setAttribute("aria-hidden", "true");
		while (clone.children.length > 0) {
			track.appendChild(clone.children[0]);
		}
	});

	// =====================================================
	// 3. Hero Floating Commodity Field Parallax
	// - Fluid mouse tracking with depth scaling
	// - Gentle magnetic repulsion on proximity
	// =====================================================
	const hero = document.getElementById("heroSection");
	const floats = document.querySelectorAll(".hero__float");

	if (hero && floats.length > 0 && !prefersReducedMotion) {
		floats.forEach((el, i) => {
			el.style.setProperty("--bob-duration", `${6.5 + (i % 4)}s`);
			el.style.setProperty("--bob-delay", `${-i * 0.8}s`);
		});

		const state = {
			mouseX: 0.5,
			mouseY: 0.5,
			pointerX: -9999,
			pointerY: -9999,
			inside: false
		};

		const items = [...floats].map(el => ({
			el,
			depth: parseFloat(el.dataset.depth || "1"),
			x: 0,
			y: 0
		}));

		const handlePointerMove = (e) => {
			const rect = hero.getBoundingClientRect();
			state.mouseX = (e.clientX - rect.left) / rect.width;
			state.mouseY = (e.clientY - rect.top) / rect.height;
			state.pointerX = e.clientX - rect.left;
			state.pointerY = e.clientY - rect.top;
			state.inside = true;
		};

		hero.addEventListener("pointermove", handlePointerMove, { passive: true });

		hero.addEventListener("pointerleave", () => {
			state.inside = false;
			state.mouseX = 0.5;
			state.mouseY = 0.5;
		});

		const PARALLAX_RANGE = 35;
		const REPEL_RADIUS = 130;
		const REPEL_STRENGTH = 45;
		const EASE = 0.07;

		let animFrameId = null;

		function renderParallax() {
			items.forEach(item => {
				const parallaxX = (state.mouseX - 0.5) * PARALLAX_RANGE * item.depth;
				const parallaxY = (state.mouseY - 0.5) * PARALLAX_RANGE * item.depth;

				let repelX = 0;
				let repelY = 0;

				if (state.inside) {
					const rect = item.el.getBoundingClientRect();
					const heroRect = hero.getBoundingClientRect();
					const cx = rect.left - heroRect.left + rect.width / 2;
					const cy = rect.top - heroRect.top + rect.height / 2;
					const dx = cx - state.pointerX;
					const dy = cy - state.pointerY;
					const dist = Math.hypot(dx, dy);

					if (dist < REPEL_RADIUS && dist > 0.001) {
						const force = (1 - dist / REPEL_RADIUS) * REPEL_STRENGTH;
						repelX = (dx / dist) * force;
						repelY = (dy / dist) * force;
					}
				}

				const targetX = parallaxX + repelX;
				const targetY = parallaxY + repelY;

				item.x += (targetX - item.x) * EASE;
				item.y += (targetY - item.y) * EASE;

				item.el.style.transform = `translate(${item.x.toFixed(2)}px, ${item.y.toFixed(2)}px)`;
			});

			animFrameId = requestAnimationFrame(renderParallax);
		}

		renderParallax();
	}
});

