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
	// 3. Interactive Free-Floating Commodity Physics Engine
	// - Full-canvas autonomous wandering drift
	// - Real-time magnetic mouse repulsion & momentum physics
	// - Soft boundary reflection and 60fps GPU acceleration
	// =====================================================
	const floatElements = document.querySelectorAll(".ambient__float");

	if (floatElements.length > 0 && !prefersReducedMotion) {
		const state = {
			pointerX: -9999,
			pointerY: -9999,
			lastPointerX: -9999,
			lastPointerY: -9999,
			pointerVx: 0,
			pointerVy: 0,
			active: false
		};

		let vw = window.innerWidth;
		let vh = window.innerHeight;

		window.addEventListener("resize", () => {
			vw = window.innerWidth;
			vh = window.innerHeight;
		});

		// Initialize particle state for each floating element
		const particles = [...floatElements].map((el, i) => {
			const rect = el.getBoundingClientRect();
			const depth = parseFloat(el.dataset.depth || "1");
			
			// Initial pixel positions from style or layout
			let initialX = (parseFloat(el.style.left) / 100) * vw;
			let initialY = (parseFloat(el.style.top) / 100) * vh;

			if (isNaN(initialX)) initialX = (i / floatElements.length) * vw;
			if (isNaN(initialY)) initialY = ((i * 1.618) % 1) * vh;

			// Reset style positioning to top-left for GPU transform translate3d
			el.style.left = "0px";
			el.style.top = "0px";

			return {
				el,
				depth,
				x: initialX,
				y: initialY,
				vx: (Math.random() - 0.5) * 0.6,
				vy: (Math.random() - 0.5) * 0.6,
				rot: (Math.random() - 0.5) * 15,
				vRot: (Math.random() - 0.5) * 0.1,
				width: rect.width || 80,
				height: rect.height || 80,
				wanderPhase: Math.random() * Math.PI * 2,
				wanderSpeed: 0.006 + Math.random() * 0.007
			};
		});

		// Track pointer movement and velocity
		window.addEventListener("pointermove", (e) => {
			if (state.lastPointerX !== -9999) {
				state.pointerVx = e.clientX - state.lastPointerX;
				state.pointerVy = e.clientY - state.lastPointerY;
			}
			state.pointerX = e.clientX;
			state.pointerY = e.clientY;
			state.lastPointerX = e.clientX;
			state.lastPointerY = e.clientY;
			state.active = true;
		}, { passive: true });

		document.addEventListener("pointerleave", () => {
			state.active = false;
			state.pointerX = -9999;
			state.pointerY = -9999;
		});

		// Interactive click burst on floating item
		particles.forEach(p => {
			p.el.addEventListener("pointerdown", (e) => {
				const burstAngle = Math.random() * Math.PI * 2;
				const burstSpeed = 8 + Math.random() * 6;
				p.vx += Math.cos(burstAngle) * burstSpeed;
				p.vy += Math.sin(burstAngle) * burstSpeed;
				p.vRot += (Math.random() - 0.5) * 8;
			});
		});

		const REPEL_RADIUS = 210;
		const REPEL_FORCE = 80;
		const DAMPING = 0.95;
		const BASE_WANDER_FORCE = 0.12;

		function physicsLoop() {
			particles.forEach(p => {
				// 1. Autonomous ambient wandering
				p.wanderPhase += p.wanderSpeed;
				const wanderFx = Math.cos(p.wanderPhase) * BASE_WANDER_FORCE;
				const wanderFy = Math.sin(p.wanderPhase * 1.25) * BASE_WANDER_FORCE;
				p.vx += wanderFx;
				p.vy += wanderFy;

				// 2. Interactive Mouse Repulsion & Kinetic Impulse
				if (state.active) {
					const cx = p.x + p.width / 2;
					const cy = p.y + p.height / 2;
					const dx = cx - state.pointerX;
					const dy = cy - state.pointerY;
					const dist = Math.hypot(dx, dy);

					if (dist < REPEL_RADIUS && dist > 1) {
						// Proximity curve
						const normalizedDist = dist / REPEL_RADIUS;
						const force = Math.pow(1 - normalizedDist, 1.8) * (REPEL_FORCE / dist) * p.depth;
						
						p.vx += dx * force * 0.1;
						p.vy += dy * force * 0.1;

						// Add mouse movement momentum
						p.vx += state.pointerVx * 0.08 * (1 - normalizedDist);
						p.vy += state.pointerVy * 0.08 * (1 - normalizedDist);

						// Impart rotational torque
						p.vRot += (dx * p.vy - dy * p.vx) * 0.0004;
					}
				}

				// 3. Apply Damping / Drag
				p.vx *= DAMPING;
				p.vy *= DAMPING;
				p.vRot *= 0.93;

				// 4. Update Position & Rotation
				p.x += p.vx;
				p.y += p.vy;
				p.rot += p.vRot;

				// 5. Soft Boundary Reflection (Bounces smoothly off viewport edges)
				const margin = 20;
				if (p.x < margin) {
					p.x = margin;
					p.vx = Math.abs(p.vx) * 0.7 + 0.3;
				} else if (p.x > vw - p.width - margin) {
					p.x = vw - p.width - margin;
					p.vx = -Math.abs(p.vx) * 0.7 - 0.3;
				}

				if (p.y < margin) {
					p.y = margin;
					p.vy = Math.abs(p.vy) * 0.7 + 0.3;
				} else if (p.y > vh - p.height - margin) {
					p.y = vh - p.height - margin;
					p.vy = -Math.abs(p.vy) * 0.7 - 0.3;
				}

				// 6. Hardware-Accelerated 3D Transform
				p.el.style.transform = `translate3d(${p.x.toFixed(1)}px, ${p.y.toFixed(1)}px, 0) rotate(${p.rot.toFixed(1)}deg)`;
			});

			// Reset pointer velocity after consumption
			state.pointerVx *= 0.8;
			state.pointerVy *= 0.8;

			requestAnimationFrame(physicsLoop);
		}

		// Start 60fps physics simulation
		requestAnimationFrame(physicsLoop);
	}
});



