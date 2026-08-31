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
	// 3. Page-Wide Floating Commodity Field Parallax
	// - Fluid window-wide mouse tracking with depth scaling
	// - Interactive magnetic repulsion when cursor gets close
	// =====================================================
	const floats = document.querySelectorAll(".ambient__float");

	if (floats.length > 0 && !prefersReducedMotion) {
		floats.forEach((el, i) => {
			el.style.setProperty("--bob-duration", `${6.2 + (i % 5) * 0.7}s`);
			el.style.setProperty("--bob-delay", `${-i * 0.75}s`);
		});

		const state = {
			mouseX: 0.5,
			mouseY: 0.5,
			pointerClientX: -9999,
			pointerClientY: -9999,
			active: false
		};

		const items = [...floats].map(el => ({
			el,
			depth: parseFloat(el.dataset.depth || "1"),
			x: 0,
			y: 0
		}));

		window.addEventListener("pointermove", (e) => {
			state.mouseX = e.clientX / window.innerWidth;
			state.mouseY = e.clientY / window.innerHeight;
			state.pointerClientX = e.clientX;
			state.pointerClientY = e.clientY;
			state.active = true;
		}, { passive: true });

		document.addEventListener("pointerleave", () => {
			state.active = false;
			state.mouseX = 0.5;
			state.mouseY = 0.5;
		});

		const PARALLAX_RANGE = 42;
		const REPEL_RADIUS = 160;
		const REPEL_STRENGTH = 65;
		const EASE = 0.08;

		let animFrameId = null;

		function renderParallax() {
			const vh = window.innerHeight;
			const vw = window.innerWidth;

			items.forEach(item => {
				const rect = item.el.getBoundingClientRect();

				// Skip offscreen elements to save CPU
				if (rect.bottom < -100 || rect.top > vh + 100) {
					return;
				}

				const parallaxX = (state.mouseX - 0.5) * PARALLAX_RANGE * item.depth;
				const parallaxY = (state.mouseY - 0.5) * PARALLAX_RANGE * item.depth;

				let repelX = 0;
				let repelY = 0;

				if (state.active) {
					const cx = rect.left + rect.width / 2;
					const cy = rect.top + rect.height / 2;
					const dx = cx - state.pointerClientX;
					const dy = cy - state.pointerClientY;
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


