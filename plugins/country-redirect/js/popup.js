/**
 * Earth Drilling Country Redirect – Popup JavaScript
 *
 * Handles:
 *  - Storing the user's country choice in a cookie (set via PHP AJAX for httpOnly safety)
 *  - Animating card selection
 *  - Close button (dismisses popup without redirecting)
 *  - Auto-redirect if cookie already exists
 */
(() => {
	const overlay = document.getElementById("edcr-overlay");
	const closeBtn = document.getElementById("edcr-close");
	const cards = document.querySelectorAll(".edcr-card");

	if (!overlay) return;

	/* ---------------------------------------------------------------
	 *  Helpers
	 * --------------------------------------------------------------- */

	function setCookie(name, value, days) {
		const d = new Date();
		d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
		// biome-ignore lint/suspicious/noDocumentCookie: PHP backend reads this cookie server-side; Cookie Store API is not viable
		document.cookie = `${name}=${value};expires=${d.toUTCString()};path=/;SameSite=Lax`;
	}

	/* If visitor already has a cookie set, redirect immediately */
	const existing = document.cookie
		.split("; ")
		.find((row) => row.startsWith(`${edcr.cookieName}=`));

	if (existing) {
		const val = existing.split("=").slice(1).join("=");
		if (val === "ca" && window.location.pathname !== edcr.caUrl) {
			window.location.href = edcr.caUrl;
			return;
		}
		if (val === "us" && window.location.pathname !== edcr.usUrl) {
			window.location.href = edcr.usUrl;
			return;
		}
	}

	/* ---------------------------------------------------------------
	 *  Card click – save choice & redirect
	 * --------------------------------------------------------------- */

	cards.forEach((card) => {
		card.addEventListener("click", (e) => {
			e.preventDefault();

			const country = card.dataset.country;
			setCookie(edcr.cookieName, country, 30); // 30 days

			// Visual feedback
			card.classList.add("edcr-card--selected");
			overlay.classList.add("edcr-overlay--redirecting");

			// Brief delay so the user sees the selection animation
			setTimeout(() => {
				if (country === "ca") {
					window.location.href = edcr.caUrl;
				} else {
					window.location.href = edcr.usUrl;
				}
			}, 350);
		});
	});

	/* ---------------------------------------------------------------
	 *  Close button – dismiss popup without choosing
	 * --------------------------------------------------------------- */

	if (closeBtn) {
		closeBtn.addEventListener("click", () => {
			overlay.classList.add("edcr-overlay--hidden");
			setTimeout(() => {
				overlay.style.display = "none";
			}, 400);
		});
	}

	/* ---------------------------------------------------------------
	 *  Animation classes  (driven by JS, styled in CSS)
	 * --------------------------------------------------------------- */

	var style = document.createElement("style");
	style.textContent = `
		.edcr-overlay--redirecting {
			animation: edcrFadeOut 0.35s ease forwards;
		}
		.edcr-overlay--hidden {
			animation: edcrFadeOut 0.35s ease forwards;
		}
		@keyframes edcrFadeOut {
			to { opacity: 0; pointer-events: none; }
		}
		.edcr-card--selected {
			transform: scale(1.03);
		}
	`;
	document.head.appendChild(style);
})();
