document.addEventListener("DOMContentLoaded", () => {
	if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;

	const targets = document.querySelectorAll(".js-reveal");
	if (!targets.length) return;

	const observer = new IntersectionObserver(
		(entries) => {
			entries.forEach((entry) => {
				if (entry.isIntersecting) {
					entry.target.classList.add("is-revealed");
					observer.unobserve(entry.target);
				}
			});
		},
		{ threshold: 0.15 }
	);

	targets.forEach((el) => observer.observe(el));
});
