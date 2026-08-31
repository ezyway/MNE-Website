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
			const width = rect.width || 75;
			const height = rect.height || 75;
			const radius = Math.max(width, height) / 2;
			
			// Initial pixel positions distributed across the canvas
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
				vx: (Math.random() - 0.5) * 0.3,
				vy: (Math.random() - 0.5) * 0.3,
				rot: (Math.random() - 0.5) * 12,
				vRot: (Math.random() - 0.5) * 0.04,
				width,
				height,
				radius,
				wanderPhase: Math.random() * Math.PI * 2,
				wanderSpeed: 0.003 + Math.random() * 0.004
			};
		});

		// Track pointer movement and velocity smoothly
		window.addEventListener("pointermove", (e) => {
			if (state.lastPointerX !== -9999) {
				state.pointerVx = (e.clientX - state.lastPointerX) * 0.5;
				state.pointerVy = (e.clientY - state.lastPointerY) * 0.5;
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

		// Gentle click interaction
		particles.forEach(p => {
			p.el.addEventListener("pointerdown", () => {
				const burstAngle = Math.random() * Math.PI * 2;
				const burstSpeed = 3.5 + Math.random() * 2.5;
				p.vx += Math.cos(burstAngle) * burstSpeed;
				p.vy += Math.sin(burstAngle) * burstSpeed;
				p.vRot += (Math.random() - 0.5) * 2;
			});
		});

		// Gentle physics parameters for calm, organic motion
		const REPEL_RADIUS = 160;
		const REPEL_FORCE = 24;
		const DAMPING = 0.965;
		const BASE_WANDER_FORCE = 0.028;

		function physicsLoop() {
			// 1. Particle-to-Particle Collision Avoidance (Zero Overlap)
			for (let i = 0; i < particles.length; i++) {
				for (let j = i + 1; j < particles.length; j++) {
					const p1 = particles[i];
					const p2 = particles[j];

					const c1x = p1.x + p1.radius;
					const c1y = p1.y + p1.radius;
					const c2x = p2.x + p2.radius;
					const c2y = p2.y + p2.radius;

					const dx = c1x - c2x;
					const dy = c1y - c2y;
					const dist = Math.hypot(dx, dy);
					const minDist = p1.radius + p2.radius + 18; // 18px comfortable clearance

					if (dist < minDist && dist > 0.001) {
						const overlap = minDist - dist;
						const nx = dx / dist;
						const ny = dy / dist;
						const pushForce = overlap * 0.035;

						p1.vx += nx * pushForce;
						p1.vy += ny * pushForce;
						p2.vx -= nx * pushForce;
						p2.vy -= ny * pushForce;
					}
				}
			}

			// 2. Individual Particle Physics & Mouse Interaction
			particles.forEach(p => {
				// Autonomous gentle wandering
				p.wanderPhase += p.wanderSpeed;
				const wanderFx = Math.cos(p.wanderPhase) * BASE_WANDER_FORCE;
				const wanderFy = Math.sin(p.wanderPhase * 1.25) * BASE_WANDER_FORCE;
				p.vx += wanderFx;
				p.vy += wanderFy;

				// Gentle Mouse Proximity Repulsion
				if (state.active) {
					const cx = p.x + p.radius;
					const cy = p.y + p.radius;
					const dx = cx - state.pointerX;
					const dy = cy - state.pointerY;
					const dist = Math.hypot(dx, dy);

					if (dist < REPEL_RADIUS && dist > 1) {
						const normalizedDist = dist / REPEL_RADIUS;
						const force = Math.pow(1 - normalizedDist, 1.6) * (REPEL_FORCE / dist) * p.depth;
						
						p.vx += dx * force * 0.06;
						p.vy += dy * force * 0.06;

						// Subtle mouse speed nudge
						p.vx += state.pointerVx * 0.03 * (1 - normalizedDist);
						p.vy += state.pointerVy * 0.03 * (1 - normalizedDist);

						// Gentle torque
						p.vRot += (dx * p.vy - dy * p.vx) * 0.00015;
					}
				}

				// Apply Damping / Air Resistance
				p.vx *= DAMPING;
				p.vy *= DAMPING;
				p.vRot *= 0.95;

				// Update Position & Rotation
				p.x += p.vx;
				p.y += p.vy;
				p.rot += p.vRot;

				// Soft Boundary Reflection
				const margin = 15;
				if (p.x < margin) {
					p.x = margin;
					p.vx = Math.abs(p.vx) * 0.6 + 0.15;
				} else if (p.x > vw - p.width - margin) {
					p.x = vw - p.width - margin;
					p.vx = -Math.abs(p.vx) * 0.6 - 0.15;
				}

				if (p.y < margin) {
					p.y = margin;
					p.vy = Math.abs(p.vy) * 0.6 + 0.15;
				} else if (p.y > vh - p.height - margin) {
					p.y = vh - p.height - margin;
					p.vy = -Math.abs(p.vy) * 0.6 - 0.15;
				}

				// Hardware-Accelerated 3D Transform
				p.el.style.transform = `translate3d(${p.x.toFixed(1)}px, ${p.y.toFixed(1)}px, 0) rotate(${p.rot.toFixed(1)}deg)`;
			});

			// Gradual pointer velocity decay
			state.pointerVx *= 0.75;
			state.pointerVy *= 0.75;

			requestAnimationFrame(physicsLoop);
		}

		// Start 60fps physics simulation
		requestAnimationFrame(physicsLoop);
	}
});




