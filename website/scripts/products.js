/**
 * Maruti Nandan Exports — Products Catalog Interactivity & Ambient Physics
 * Optimized for 60fps Micro-interactions & Accessibility
 */
document.addEventListener("DOMContentLoaded", () => {

	const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

	// =====================================================
	// 1. Infinite Marquee Seamless Looping
	// =====================================================
	const marqueeTrack = document.getElementById("licencesTrack");

	if (marqueeTrack) {
		const clone = marqueeTrack.cloneNode(true);
		clone.setAttribute("aria-hidden", "true");
		while (clone.children.length > 0) {
			marqueeTrack.appendChild(clone.children[0]);
		}
	}

	// =====================================================
	// 2. Ambient Free-Floating Commodity Physics Engine
	// =====================================================
	const floatElements = document.querySelectorAll(".ambient__float");
	if (floatElements.length === 0 || prefersReducedMotion) return;

	const particles = [];
	const vw = () => window.innerWidth;
	const vh = () => window.innerHeight;

	floatElements.forEach((el, index) => {
		const depth = parseFloat(el.getAttribute("data-depth")) || 1.0;
		const initLeft = parseFloat(el.style.left) || (10 + (index * 12) % 80);
		const initTop = parseFloat(el.style.top) || (10 + (index * 14) % 80);

		const posX = (initLeft / 100) * vw();
		const posY = (initTop / 100) * vh();

		const angle = Math.random() * Math.PI * 2;
		const speed = 0.25 + Math.random() * 0.35;

		const radius = Math.max(30, (el.offsetWidth || 70) * 0.52);

		particles.push({
			el,
			x: posX,
			y: posY,
			vx: Math.cos(angle) * speed,
			vy: Math.sin(angle) * speed,
			targetVx: Math.cos(angle) * speed,
			targetVy: Math.sin(angle) * speed,
			rotation: (Math.random() - 0.5) * 12,
			vRot: (Math.random() - 0.5) * 0.15,
			depth,
			radius,
			wanderAngle: angle,
			dragged: false
		});
	});

	let mouseX = -9999;
	let mouseY = -9999;
	let mouseVelocityX = 0;
	let mouseVelocityY = 0;
	let lastMouseX = -9999;
	let lastMouseY = -9999;
	let mouseActive = false;
	let mouseTimeout = null;

	window.addEventListener("pointermove", (e) => {
		if (lastMouseX !== -9999) {
			mouseVelocityX = e.clientX - lastMouseX;
			mouseVelocityY = e.clientY - lastMouseY;
		}
		mouseX = e.clientX;
		mouseY = e.clientY;
		lastMouseX = mouseX;
		lastMouseY = mouseY;
		mouseActive = true;

		clearTimeout(mouseTimeout);
		mouseTimeout = setTimeout(() => {
			mouseActive = false;
			mouseX = -9999;
			mouseY = -9999;
			mouseVelocityX = 0;
			mouseVelocityY = 0;
		}, 1800);
	}, { passive: true });

	// Interactive Drag & Toss
	particles.forEach(p => {
		let isDragging = false;
		let dragStartX = 0;
		let dragStartY = 0;
		let prevDragX = 0;
		let prevDragY = 0;

		p.el.addEventListener("pointerdown", (e) => {
			isDragging = true;
			p.dragged = true;
			dragStartX = e.clientX - p.x;
			dragStartY = e.clientY - p.y;
			prevDragX = e.clientX;
			prevDragY = e.clientY;
			p.el.setPointerCapture(e.pointerId);
		});

		p.el.addEventListener("pointermove", (e) => {
			if (!isDragging) return;
			p.x = e.clientX - dragStartX;
			p.y = e.clientY - dragStartY;
			p.vx = (e.clientX - prevDragX) * 0.35;
			p.vy = (e.clientY - prevDragY) * 0.35;
			prevDragX = e.clientX;
			prevDragY = e.clientY;
		});

		const endDrag = () => {
			if (isDragging) {
				isDragging = false;
				setTimeout(() => { p.dragged = false; }, 300);
			}
		};

		p.el.addEventListener("pointerup", endDrag);
		p.el.addEventListener("pointercancel", endDrag);
	});

	// Physics Loop Constants
	const REPEL_RADIUS = 150;
	const REPEL_FORCE = 24;
	const DAMPING = 0.965;
	const WANDER_STRENGTH = 0.028;

	let lastTimestamp = performance.now();

	function physicsLoop(now) {
		const dt = Math.min((now - lastTimestamp) / 1000, 0.1);
		lastTimestamp = now;

		const currentW = vw();
		const currentH = vh();

		// Particle collision avoidance
		for (let i = 0; i < particles.length; i++) {
			for (let j = i + 1; j < particles.length; j++) {
				const p1 = particles[i];
				const p2 = particles[j];
				const dx = p2.x - p1.x;
				const dy = p2.y - p1.y;
				const dist = Math.hypot(dx, dy);
				const minDist = p1.radius + p2.radius + 18;

				if (dist < minDist && dist > 0.001) {
					const overlap = (minDist - dist) * 0.5;
					const nx = dx / dist;
					const ny = dy / dist;

					if (!p1.dragged) {
						p1.x -= nx * overlap * 0.4;
						p1.y -= ny * overlap * 0.4;
						p1.vx -= nx * 0.6;
						p1.vy -= ny * 0.6;
					}
					if (!p2.dragged) {
						p2.x += nx * overlap * 0.4;
						p2.y += ny * overlap * 0.4;
						p2.vx += nx * 0.6;
						p2.vy += ny * 0.6;
					}
				}
			}
		}

		particles.forEach(p => {
			if (p.dragged) {
				p.el.style.transform = `translate3d(${p.x}px, ${p.y}px, 0) rotate(${p.rotation}deg)`;
				return;
			}

			// Autonomous subtle wandering
			p.wanderAngle += (Math.random() - 0.5) * 0.2;
			p.vx += Math.cos(p.wanderAngle) * WANDER_STRENGTH;
			p.vy += Math.sin(p.wanderAngle) * WANDER_STRENGTH;

			// Mouse Repulsion & Momentum
			if (mouseActive) {
				const dx = p.x - mouseX;
				const dy = p.y - mouseY;
				const dist = Math.hypot(dx, dy);

				if (dist < REPEL_RADIUS && dist > 1) {
					const force = Math.pow((REPEL_RADIUS - dist) / REPEL_RADIUS, 2) * REPEL_FORCE;
					p.vx += (dx / dist) * force * p.depth;
					p.vy += (dy / dist) * force * p.depth;
					p.vx += mouseVelocityX * 0.04 * p.depth;
					p.vy += mouseVelocityY * 0.04 * p.depth;
					p.vRot += (dx > 0 ? 0.2 : -0.2);
				}
			}

			p.vx *= DAMPING;
			p.vy *= DAMPING;

			p.x += p.vx;
			p.y += p.vy;

			p.rotation += p.vRot;
			p.vRot *= 0.94;

			// Boundary bouncing
			const padding = 20;
			const pSize = p.radius * 2;

			if (p.x < padding) {
				p.x = padding;
				p.vx = Math.abs(p.vx) * 0.6 + 0.1;
			} else if (p.x > currentW - pSize - padding) {
				p.x = currentW - pSize - padding;
				p.vx = -Math.abs(p.vx) * 0.6 - 0.1;
			}

			if (p.y < padding) {
				p.y = padding;
				p.vy = Math.abs(p.vy) * 0.6 + 0.1;
			} else if (p.y > currentH - pSize - padding) {
				p.y = currentH - pSize - padding;
				p.vy = -Math.abs(p.vy) * 0.6 - 0.1;
			}

			p.el.style.transform = `translate3d(${p.x}px, ${p.y}px, 0) rotate(${p.rotation}deg)`;
		});

		requestAnimationFrame(physicsLoop);
	}

	requestAnimationFrame(physicsLoop);
});