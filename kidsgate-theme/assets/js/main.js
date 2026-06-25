/**
 * Kids Gate v1.3 — shared interactions.
 * Vanilla JS, no dependencies. Everything degrades gracefully and
 * respects prefers-reduced-motion.
 */
(function () {
	'use strict';

	var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* ----------------------------------------------------------------
	 * Analytics: every element with [data-kg-event] pushes to dataLayer
	 * on click. GTM picks these up once a container ID is configured.
	 * -------------------------------------------------------------- */
	window.dataLayer = window.dataLayer || [];

	function track(eventName, params) {
		window.dataLayer.push(Object.assign({ event: eventName }, params || {}));
	}
	window.kgTrack = track;

	document.addEventListener('click', function (e) {
		var el = e.target.closest('[data-kg-event]');
		if (el) {
			track(el.getAttribute('data-kg-event-name') || el.getAttribute('data-kg-event'), {
				link_url: el.href || '',
				link_text: (el.textContent || '').trim().slice(0, 80)
			});
		}
	});

	// Scroll depth: 50% and 90% fire once per page view.
	var depths = [{ pct: 0.5, name: 'scroll_depth_50' }, { pct: 0.9, name: 'scroll_depth_90' }];
	var fired = {};
	function checkDepth() {
		var doc = document.documentElement;
		var scrollable = doc.scrollHeight - window.innerHeight;
		if (scrollable <= 0) { return; }
		var progress = (window.scrollY || doc.scrollTop) / scrollable;
		depths.forEach(function (d) {
			if (!fired[d.name] && progress >= d.pct) {
				fired[d.name] = true;
				track(d.name);
			}
		});
	}
	window.addEventListener('scroll', checkDepth, { passive: true });

	// Page-view style events derived from the body template class.
	var body = document.body;
	if (body.classList.contains('page-template-page-pricing')) { track('pricing_page_view'); }
	if (body.classList.contains('page-template-page-leaderboard')) { track('leaderboard_view'); }
	if (body.classList.contains('page-template-page-support')) { track('support_page_view'); }

	/* ----------------------------------------------------------------
	 * Scroll reveals with optional stagger (--kg-delay set in markup)
	 * -------------------------------------------------------------- */
	// Reduced-motion visitors still get the scroll-triggered reveals, but the
	// CSS swaps them to opacity-only fades (no translate/scale movement).
	//
	// While the page is still loading, attach-time geometry can be stale
	// (fonts/images settling, restored scroll position) and would fire
	// triggers for elements that are not really on screen yet — so before
	// acting we re-check the element's live position and otherwise keep
	// observing; the observer fires again when it genuinely scrolls into view.
	function prematureFire(entry) {
		if (document.readyState === 'complete') { return false; }
		var rect = entry.target.getBoundingClientRect();
		return rect.top > window.innerHeight || rect.bottom < 0;
	}

	var revealEls = document.querySelectorAll('[data-kg-reveal]');
	if ('IntersectionObserver' in window) {
		var revealObserver = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting && !prematureFire(entry)) {
					entry.target.classList.add('is-in');
					revealObserver.unobserve(entry.target);
				}
			});
		}, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
		revealEls.forEach(function (el) { revealObserver.observe(el); });
	} else {
		revealEls.forEach(function (el) { el.classList.add('is-in'); });
	}

	// How-it-works scroll engagement event (fires once when the loop section enters view).
	var hiw = document.querySelector('[data-kg-hiw-watch]');
	if (hiw && 'IntersectionObserver' in window) {
		var hiwSeen = false;
		new IntersectionObserver(function (entries, obs) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting && !hiwSeen) {
					hiwSeen = true;
					track('how_it_works_scroll');
					obs.disconnect();
				}
			});
		}, { threshold: 0.3 }).observe(hiw);
	}

	// Testimonial visibility event.
	var testimonialBlock = document.querySelector('[data-kg-testimonials]');
	if (testimonialBlock && 'IntersectionObserver' in window) {
		var tSeen = false;
		new IntersectionObserver(function (entries, obs) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting && !tSeen) {
					tSeen = true;
					track('testimonial_view');
					obs.disconnect();
				}
			});
		}, { threshold: 0.3 }).observe(testimonialBlock);
	}

	/* ----------------------------------------------------------------
	 * Animated counters: <span data-kg-count="1800" data-kg-suffix="+">
	 * -------------------------------------------------------------- */
	var counters = document.querySelectorAll('[data-kg-count]');
	function animateCounter(el) {
		var target = parseFloat(el.getAttribute('data-kg-count'));
		var suffix = el.getAttribute('data-kg-suffix') || '';
		var duration = 1400;
		var start = null;
		function tick(ts) {
			if (!start) { start = ts; }
			var p = Math.min((ts - start) / duration, 1);
			var eased = 1 - Math.pow(1 - p, 3);
			el.textContent = Math.round(target * eased).toLocaleString() + suffix;
			if (p < 1) { requestAnimationFrame(tick); }
		}
		requestAnimationFrame(tick);
	}
	if ('IntersectionObserver' in window) {
		var countObserver = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting && !prematureFire(entry)) {
					animateCounter(entry.target);
					countObserver.unobserve(entry.target);
				}
			});
		}, { threshold: 0.5 });
		counters.forEach(function (el) { countObserver.observe(el); });
	} else {
		counters.forEach(animateCounter);
	}

	/* ----------------------------------------------------------------
	 * Journey timeline (How It Works): a glowing orb travels down the
	 * track as you scroll, lighting up each gate and unfolding its card
	 * when it arrives. Scrolling back up rewinds the journey.
	 * -------------------------------------------------------------- */
	var journey = document.querySelector('[data-kg-journey]');
	if (journey) {
		var jTrack = journey.querySelector('.kg-jt__track');
		var jFill = journey.querySelector('[data-kg-journey-fill]');
		var jOrb = journey.querySelector('[data-kg-journey-orb]');
		var jSteps = Array.prototype.slice.call(journey.querySelectorAll('[data-kg-journey-step]'));
		var jTicking = false;

		function updateJourney() {
			jTicking = false;
			var rect = jTrack.getBoundingClientRect();
			// The orb sits where the reader's eye is: 45% down the viewport.
			var progress = (window.innerHeight * 0.45 - rect.top) / rect.height;
			progress = Math.max(0, Math.min(1, progress));
			var fillPx = progress * rect.height;
			if (!reducedMotion) {
				jFill.style.height = fillPx + 'px';
				jOrb.style.top = fillPx + 'px';
			}
			jSteps.forEach(function (step) {
				var node = step.querySelector('.kg-jt__node');
				var nRect = node.getBoundingClientRect();
				var nodeCentre = nRect.top + nRect.height / 2 - rect.top;
				step.classList.toggle('is-active', fillPx >= nodeCentre - 4);
			});
		}
		function queueJourney() {
			if (!jTicking) {
				jTicking = true;
				requestAnimationFrame(updateJourney);
			}
		}
		window.addEventListener('scroll', queueJourney, { passive: true });
		window.addEventListener('resize', queueJourney, { passive: true });
		if (reducedMotion) {
			// Static fully-drawn line; steps still activate (fade-only via CSS).
			jFill.style.height = '100%';
		}
		updateJourney();
	}

	/* ----------------------------------------------------------------
	 * Session timeline (How It Works): one-shot sequence when scrolled
	 * into view — a ball drops out from behind the navy section above
	 * into the first dot, then the line lights up dot by dot.
	 * -------------------------------------------------------------- */
	var session = document.querySelector('[data-kg-session]');
	if (session && 'IntersectionObserver' in window) {
		var FILL_MS = 1500;
		session.classList.add('is-armed');
		var sBall = session.querySelector('[data-kg-session-ball]');
		var sItems = Array.prototype.slice.call(session.querySelectorAll('.kg-timeline__item'));

		function runSession() {
			if (reducedMotion) {
				session.classList.add('is-run');
				sItems.forEach(function (item) { item.classList.add('is-lit'); });
				return;
			}
			var tlRect = session.getBoundingClientRect();
			var secRect = session.closest('.kg-section').getBoundingClientRect();
			var dotRect = sItems[0].querySelector('.kg-timeline__dot').getBoundingClientRect();
			// Start above the section's top edge (clipped → "behind" the navy
			// block), land on the centre of the first dot.
			var startY = secRect.top - tlRect.top - 30;
			var endY = dotRect.top + dotRect.height / 2 - tlRect.top;
			sBall.style.transform = 'translateY(' + startY + 'px)';
			sBall.style.opacity = '1';
			requestAnimationFrame(function () {
				requestAnimationFrame(function () {
					sBall.style.transition = 'transform 750ms cubic-bezier(.45, 0, .65, .9), opacity 450ms ease 150ms';
					sBall.style.transform = 'translateY(' + endY + 'px)';
				});
			});
			setTimeout(function () {
				sItems[0].classList.add('is-lit');
				sBall.style.opacity = '0'; // the ball melts into the dot…
				session.classList.add('is-run'); // …and the line takes over
				var trackTop = tlRect.top + 8;
				var trackH = tlRect.height - 16;
				sItems.slice(1).forEach(function (item) {
					var d = item.querySelector('.kg-timeline__dot').getBoundingClientRect();
					var frac = (d.top + d.height / 2 - trackTop) / trackH;
					setTimeout(function () { item.classList.add('is-lit'); }, Math.max(0, Math.min(1, frac)) * FILL_MS);
				});
			}, 780);
		}

		var sessionSeen = false;
		new IntersectionObserver(function (entries, obs) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting && !sessionSeen && !prematureFire(entry)) {
					sessionSeen = true;
					runSession();
					obs.disconnect();
				}
			});
		}, { threshold: 0.35 }).observe(session);
	}

	/* ----------------------------------------------------------------
	 * Header: shadow on scroll + mobile menu
	 * -------------------------------------------------------------- */
	var header = document.querySelector('[data-kg-header]');
	if (header) {
		window.addEventListener('scroll', function () {
			header.classList.toggle('is-scrolled', window.scrollY > 12);
		}, { passive: true });
	}

	var burger = document.querySelector('[data-kg-burger]');
	if (burger) {
		burger.addEventListener('click', function () {
			var open = body.classList.toggle('kg-nav-open');
			burger.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && body.classList.contains('kg-nav-open')) {
				body.classList.remove('kg-nav-open');
				burger.setAttribute('aria-expanded', 'false');
				burger.focus();
			}
		});
	}

	/* ----------------------------------------------------------------
	 * Language switcher dropdowns (click + keyboard, not hover-only)
	 * -------------------------------------------------------------- */
	document.querySelectorAll('[data-kg-lang]').forEach(function (lang) {
		var btn = lang.querySelector('.kg-lang__btn');
		btn.addEventListener('click', function (e) {
			e.stopPropagation();
			var open = lang.classList.toggle('is-open');
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	});
	document.addEventListener('click', function () {
		document.querySelectorAll('[data-kg-lang].is-open').forEach(function (lang) {
			lang.classList.remove('is-open');
			lang.querySelector('.kg-lang__btn').setAttribute('aria-expanded', 'false');
		});
	});
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') {
			document.querySelectorAll('[data-kg-lang].is-open').forEach(function (lang) {
				lang.classList.remove('is-open');
				lang.querySelector('.kg-lang__btn').setAttribute('aria-expanded', 'false');
			});
		}
	});

	/* ----------------------------------------------------------------
	 * FAQ accordions (shared by every FAQ on the site)
	 * -------------------------------------------------------------- */
	document.querySelectorAll('[data-kg-faq]').forEach(function (faq) {
		var context = faq.getAttribute('data-kg-faq-context') || 'faq';
		faq.querySelectorAll('.kg-faq__q button').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var item = btn.closest('.kg-faq__item');
				var panel = document.getElementById(btn.getAttribute('aria-controls'));
				var open = btn.getAttribute('aria-expanded') === 'true';

				// Close all other items in this accordion group first
				if (!open) {
					faq.querySelectorAll('.kg-faq__item.is-open').forEach(function (other) {
						if (other === item) { return; }
						var otherBtn = other.querySelector('.kg-faq__q button');
						var otherPanel = otherBtn && document.getElementById(otherBtn.getAttribute('aria-controls'));
						if (otherBtn) { otherBtn.setAttribute('aria-expanded', 'false'); }
						other.classList.remove('is-open');
						if (otherPanel) { otherPanel.hidden = true; }
					});
				}

				btn.setAttribute('aria-expanded', open ? 'false' : 'true');
				item.classList.toggle('is-open', !open);
				if (panel) { panel.hidden = open; }
				if (!open) {
					track(context === 'support' ? 'support_faq_expand' : 'faq_expand', {
						faq_question: btn.textContent.trim().slice(0, 100)
					});
				}
			});
		});
	});

	/* ----------------------------------------------------------------
	 * Tabs (learning experience preview, dashboards…)
	 * -------------------------------------------------------------- */
	document.querySelectorAll('[data-kg-tabs]').forEach(function (tabs) {
		var tabButtons = tabs.querySelectorAll('[role="tab"]');
		var panels = tabs.querySelectorAll('[role="tabpanel"]');

		function select(tab) {
			tabButtons.forEach(function (t) {
				var active = t === tab;
				t.setAttribute('aria-selected', active ? 'true' : 'false');
				t.tabIndex = active ? 0 : -1;
			});
			panels.forEach(function (p) {
				p.hidden = p.id !== tab.getAttribute('aria-controls');
			});
		}

		tabButtons.forEach(function (tab, i) {
			tab.addEventListener('click', function () { select(tab); });
			tab.addEventListener('keydown', function (e) {
				var dir = e.key === 'ArrowRight' ? 1 : e.key === 'ArrowLeft' ? -1 : 0;
				if (dir) {
					e.preventDefault();
					var next = tabButtons[(i + dir + tabButtons.length) % tabButtons.length];
					next.focus();
					select(next);
				}
			});
		});
	});

	/* ----------------------------------------------------------------
	 * Carousels (testimonials, screenshots) — native scroll + buttons
	 * -------------------------------------------------------------- */
	document.querySelectorAll('[data-kg-carousel]').forEach(function (carousel) {
		var trackEl = carousel.querySelector('.kg-carousel__track');
		var prev = carousel.querySelector('[data-kg-carousel-prev]');
		var next = carousel.querySelector('[data-kg-carousel-next]');
		function slideWidth() {
			var slide = trackEl.querySelector('.kg-carousel__slide');
			return slide ? slide.offsetWidth + 20 : 320;
		}
		if (prev) { prev.addEventListener('click', function () { trackEl.scrollBy({ left: -slideWidth(), behavior: reducedMotion ? 'auto' : 'smooth' }); }); }
		if (next) { next.addEventListener('click', function () { trackEl.scrollBy({ left: slideWidth(), behavior: reducedMotion ? 'auto' : 'smooth' }); }); }
	});

	/* ----------------------------------------------------------------
	 * Marquee carousels (testimonials) — continuous, gentle auto-scroll.
	 * Clone the set once so a single translate loops seamlessly; pace is
	 * a steady px/sec regardless of how many cards exist. Hover/focus
	 * pause is handled in CSS. Skipped entirely for reduced-motion.
	 * -------------------------------------------------------------- */
	document.querySelectorAll('[data-kg-marquee]').forEach(function (carousel) {
		if (reducedMotion) { return; }
		var trackEl = carousel.querySelector('.kg-carousel__track');
		if (!trackEl) { return; }
		var originals = Array.prototype.slice.call(trackEl.children);
		if (!originals.length) { return; }

		originals.forEach(function (slide) {
			var clone = slide.cloneNode(true);
			clone.setAttribute('aria-hidden', 'true');
			clone.querySelectorAll('a, button, input, textarea, select').forEach(function (el) {
				el.setAttribute('tabindex', '-1');
			});
			trackEl.appendChild(clone);
		});

		var SPEED = 38; // pixels per second — slow enough to read on hover-pause
		function syncMarquee() {
			var styles = getComputedStyle(trackEl);
			var gap = parseFloat(styles.columnGap || styles.gap) || 0;
			// Travel exactly one original set + one gap so the clone lands
			// precisely where the original began (no drift at the seam).
			var shift = (trackEl.scrollWidth + gap) / 2;
			trackEl.style.setProperty('--kg-marquee-shift', shift.toFixed(2) + 'px');
			trackEl.style.setProperty('--kg-marquee-dur', Math.max(18, shift / SPEED).toFixed(1) + 's');
		}
		syncMarquee();
		carousel.classList.add('kg-marquee-on');

		var resizeTimer;
		window.addEventListener('resize', function () {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(syncMarquee, 200);
		});
	});

	/* ----------------------------------------------------------------
	 * Demo video: poster overlay → play (no autoplay, no sound surprise)
	 * -------------------------------------------------------------- */
	document.querySelectorAll('[data-kg-video]').forEach(function (wrap) {
		var cover = wrap.querySelector('.kg-video__cover');
		var video = wrap.querySelector('video');
		if (!cover || !video) { return; }
		cover.addEventListener('click', function () {
			cover.remove();
			video.setAttribute('controls', '');
			video.play();
			track('video_play', { video_name: 'gate-demo' });
		});
	});

	/* ----------------------------------------------------------------
	 * AI demo: answer a sample question, watch the path adjust
	 * -------------------------------------------------------------- */
	document.querySelectorAll('[data-kg-ai-demo]').forEach(function (demo) {
		var result = demo.querySelector('.kg-ai__demo-result');
		demo.querySelectorAll('.kg-ai__demo-buttons button').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var correct = btn.getAttribute('data-kg-answer') === 'correct';
				result.className = 'kg-ai__demo-result ' + (correct ? 'is-correct' : 'is-wrong');
				result.textContent = correct ? result.getAttribute('data-kg-correct') : result.getAttribute('data-kg-wrong');
			});
		});
	});

	/* ----------------------------------------------------------------
	 * Home "daily loop": a row of four stage cards where the spotlight
	 * auto-advances 1→2→3→4 and repeats, conveying the everyday cycle
	 * without any scrolling. Pauses off-screen; hovering a card holds it.
	 * Reduced-motion: no auto-play, the first stage stays lit.
	 * -------------------------------------------------------------- */
	document.querySelectorAll('[data-kg-loop]').forEach(function (loop) {
		var steps = Array.prototype.slice.call(loop.querySelectorAll('[data-kg-loop-step]'));
		var bar = loop.querySelector('[data-kg-loop-bar]');
		if (steps.length < 2) { return; }
		var n = steps.length;
		var idx = 0;
		var timer = null;

		function show(i) {
			idx = (i + n) % n;
			steps.forEach(function (s, k) { s.classList.toggle('is-current', k === idx); });
			if (bar) { bar.style.width = ((idx + 1) / n * 100) + '%'; }
		}
		function start() { if (!timer && !reducedMotion) { timer = setInterval(function () { show(idx + 1); }, 2200); } }
		function stop() { if (timer) { clearInterval(timer); timer = null; } }

		show(0);

		if ('IntersectionObserver' in window) {
			new IntersectionObserver(function (entries) {
				entries.forEach(function (e) { if (e.isIntersecting) { start(); } else { stop(); } });
			}, { threshold: 0.3 }).observe(loop);
		} else {
			start();
		}

		// Mouse users can hold a stage by hovering it; leaving resumes the loop.
		steps.forEach(function (step, i) {
			step.addEventListener('mouseenter', function () { stop(); show(i); });
			step.addEventListener('mouseleave', start);
		});
	});

	/* ----------------------------------------------------------------
	 * Monthly / annual price toggles (homepage summary + pricing page)
	 * All [data-kg-billing-toggle] instances are kept in sync globally.
	 * -------------------------------------------------------------- */
	var allBillingToggles = document.querySelectorAll('[data-kg-billing-toggle]');

	function applyBillingMode(mode) {
		// Sync every toggle button on the page.
		allBillingToggles.forEach(function (toggle) {
			toggle.querySelectorAll('button').forEach(function (b) {
				b.setAttribute('aria-pressed', b.getAttribute('data-kg-billing') === mode ? 'true' : 'false');
			});
		});
		// Update plan prices.
		document.querySelectorAll('[data-kg-price-m]').forEach(function (el) {
			el.textContent = mode === 'y' ? el.getAttribute('data-kg-price-y') : el.getAttribute('data-kg-price-m');
		});
		// Show/hide billed-yearly notes.
		document.querySelectorAll('[data-kg-billing-note]').forEach(function (el) {
			el.hidden = el.getAttribute('data-kg-billing-note') !== mode;
		});
		// Notify the builder.
		document.dispatchEvent(new CustomEvent('kg:billing', { detail: { mode: mode } }));
	}

	allBillingToggles.forEach(function (toggle) {
		toggle.querySelectorAll('button').forEach(function (btn) {
			btn.addEventListener('click', function () { applyBillingMode(btn.getAttribute('data-kg-billing')); });
		});
	});

	// Default to annual billing on page load.
	applyBillingMode('y');

	/* ----------------------------------------------------------------
	 * Adaptive AI engine visualisation (Features spotlight).
	 *
	 * A self-running, looping story of the backend: an answer comes in,
	 * the model analyses it, finds a knowledge gap, queues reinforcement,
	 * then mastery climbs on the live knowledge map. The map levels persist
	 * across cycles so every revisited skill trends upward, conveying how
	 * the system adapts to the child's level over time.
	 * -------------------------------------------------------------- */
	document.querySelectorAll('[data-kg-aiviz]').forEach(function (viz) {
		var dataEl = viz.querySelector('[data-kg-aiviz-data]');
		if (!dataEl) { return; }
		var d = JSON.parse(dataEl.textContent);
		var skills = d.skills || [];
		if (!skills.length) { return; }

		var answerCard   = viz.querySelector('[data-kg-answer-card]');
		var answerSkill  = viz.querySelector('[data-kg-answer-skill]');
		var answerResult = viz.querySelector('[data-kg-answer-result]');
		var actionCard   = viz.querySelector('[data-kg-action-card]');
		var actionText   = viz.querySelector('[data-kg-action-text]');
		var core         = viz.querySelector('[data-kg-core]');
		var statusEl     = viz.querySelector('[data-kg-aiviz-status]');
		var rows         = Array.prototype.slice.call(viz.querySelectorAll('[data-kg-row]'));
		var phases       = d.phases || [];

		// Starting mastery per skill (persisted and nudged upward each cycle).
		var seed = [58, 46, 67, 41];
		var levels = skills.map(function (_, i) { return seed[i % seed.length]; });

		function renderBars() {
			rows.forEach(function (row, i) {
				var fill = row.querySelector('[data-kg-fill]');
				var pct  = row.querySelector('[data-kg-pct]');
				if (fill) { fill.style.width = levels[i] + '%'; }
				if (pct) { pct.textContent = Math.round(levels[i]) + '%'; }
			});
		}
		function setStatus(base, skill) {
			statusEl.innerHTML = base + (skill ? ' &middot; <strong>' + skill + '</strong>' : '');
		}
		function resetVisuals() {
			rows.forEach(function (r) { r.classList.remove('is-active', 'is-mastered'); });
			answerCard.classList.remove('is-active');
			actionCard.classList.remove('is-active');
			core.classList.remove('is-thinking');
			answerResult.className = 'kg-aiviz__result';
		}

		renderBars();

		// Static, motion-free snapshot for reduced-motion users.
		if (reducedMotion) {
			answerSkill.textContent = skills[0];
			answerResult.textContent = d.missed;
			answerResult.classList.add('is-missed');
			rows[0].classList.add('is-active');
			actionCard.classList.add('is-active');
			actionText.textContent = skills[0];
			setStatus(phases[2] || '', skills[0]);
			return;
		}

		var timers = [];
		function after(ms, fn) { timers.push(setTimeout(fn, ms)); }
		function clearTimers() { timers.forEach(clearTimeout); timers = []; }

		var idx = 0;
		function cycle() {
			clearTimers();
			resetVisuals();
			var i = idx % skills.length;
			var skill = skills[i];
			var start = levels[i];

			// 0 — answer arrives, marked wrong.
			answerSkill.textContent = skill;
			answerResult.textContent = d.missed;
			answerResult.classList.add('is-missed');
			answerCard.classList.add('is-active');
			setStatus(phases[0]);

			// 1 — model analyses the response.
			after(1500, function () {
				core.classList.add('is-thinking');
				setStatus(phases[1]);
			});

			// 2 — gap found: highlight the skill and dip its mastery.
			after(3100, function () {
				rows[i].classList.add('is-active');
				levels[i] = Math.max(20, start - 14);
				renderBars();
				setStatus(phases[2], skill);
			});

			// 3 — queue reinforcement from a new angle.
			after(4600, function () {
				actionCard.classList.add('is-active');
				actionText.textContent = skill;
				setStatus(phases[3], skill);
			});

			// 4 — child now answers correctly; mastery climbs past where it started.
			after(6200, function () {
				answerResult.className = 'kg-aiviz__result is-got';
				answerResult.textContent = d.got;
				rows[i].classList.remove('is-active');
				rows[i].classList.add('is-mastered');
				levels[i] = Math.min(96, start + 12);
				renderBars();
				core.classList.remove('is-thinking');
				setStatus(phases[4], skill);
			});

			after(8300, function () { idx++; cycle(); });
		}

		// Only animate while the widget is on screen.
		var running = false;
		if ('IntersectionObserver' in window) {
			new IntersectionObserver(function (entries) {
				entries.forEach(function (e) {
					if (e.isIntersecting && !running) { running = true; idx = 0; cycle(); }
					else if (!e.isIntersecting && running) { running = false; clearTimers(); }
				});
			}, { threshold: 0.25 }).observe(viz);
		} else {
			cycle();
		}
	});

	/* ----------------------------------------------------------------
	 * Parent dashboard tour: a real screenshot with annotation zones,
	 * kept in sync with the explanation cards. The dashboard has two
	 * views — Activity ('a') and Performance ('b') — that share an
	 * identical layout, so the five zones stay put while the screenshot
	 * and every card's title/text swap. The sixth card flips the view.
	 * One card open at a time; clicking the open one closes it.
	 * -------------------------------------------------------------- */
	document.querySelectorAll('[data-kg-tour]').forEach(function (tour) {
		var stage = tour.querySelector('[data-kg-tour-stage]');
		var items = Array.prototype.slice.call(tour.querySelectorAll('[data-kg-tour-item]'));
		var zones = Array.prototype.slice.call(tour.querySelectorAll('[data-kg-tour-zone]'));
		var img   = tour.querySelector('[data-kg-tour-img]');
		var barlabel = tour.querySelector('[data-kg-tour-barlabel]');
		if (!items.length) { return; }

		var data = (stage && stage.dataset) || {};
		var view = 'a';
		var active = -1;

		function fill(el, sel, value) {
			var t = el.querySelector(sel);
			if (t && typeof value === 'string') { t.textContent = value; }
		}

		// Swap screenshot + every card's copy to match the current view.
		function render() {
			if (img) {
				img.src = view === 'a' ? data.imgA : data.imgB;
				img.alt = view === 'a' ? data.altA : data.altB;
			}
			if (barlabel) { barlabel.textContent = view === 'a' ? data.labelA : data.labelB; }
			items.forEach(function (el) {
				var d = el.dataset;
				fill(el, '[data-kg-tour-title]', view === 'a' ? d.titleA : d.titleB);
				fill(el, '[data-kg-tour-text]',  view === 'a' ? d.textA  : d.textB);
			});
			zones.forEach(function (z) {
				// Reposition for this view (the two screenshots differ slightly).
				var pos = view === 'a' ? z.dataset.posA : z.dataset.posB;
				if (pos) { z.style.cssText = pos; }
				var card = items[parseInt(z.getAttribute('data-kg-tour-zone'), 10)];
				if (card) { z.setAttribute('aria-label', (view === 'a' ? card.dataset.titleA : card.dataset.titleB) || ''); }
			});
		}

		function apply(i, silent) {
			// The sixth card is the view toggle, not an accordion item.
			if (items[i] && items[i].hasAttribute('data-kg-tour-toggle')) {
				view = view === 'a' ? 'b' : 'a';
				render();
				if (!silent) { track('faq_expand', { faq_question: 'dashboard_tour_view_' + view }); }
				active = -1;        // force the first card open (don't toggle it shut)
				apply(0, true);     // reopen the first card in the new view
				return;
			}
			if (i === active) { i = -1; } // toggle the open card closed
			active = i;
			items.forEach(function (el, n) {
				var on = n === i;
				el.classList.toggle('is-active', on);
				var head = el.querySelector('[data-kg-tour-head]');
				if (head) { head.setAttribute('aria-expanded', on ? 'true' : 'false'); }
			});
			zones.forEach(function (z) { z.classList.toggle('is-active', parseInt(z.getAttribute('data-kg-tour-zone'), 10) === i); });
			if (stage) { stage.classList.toggle('has-active', i >= 0); }
			if (!silent && i >= 0) { track('faq_expand', { faq_question: 'dashboard_tour_' + view + '_' + (i + 1) }); }
		}

		items.forEach(function (el, n) {
			if (el.hasAttribute('data-kg-tour-toggle')) {
				el.addEventListener('click', function () { apply(n); });
				return;
			}
			var head = el.querySelector('[data-kg-tour-head]');
			if (head) { head.addEventListener('click', function () { apply(n); }); }
		});
		zones.forEach(function (z) {
			z.addEventListener('click', function () { apply(parseInt(z.getAttribute('data-kg-tour-zone'), 10)); });
		});

		// Open the first item by default so the section never looks inert.
		render();
		apply(0, true);
	});

	/* ----------------------------------------------------------------
	 * Lightbox: any [data-kg-lightbox] opens its image full-screen with a
	 * tap-to-zoom toggle, so detail-heavy screenshots stay readable.
	 * -------------------------------------------------------------- */
	var lightboxTriggers = document.querySelectorAll('[data-kg-lightbox]');
	if (lightboxTriggers.length) {
		var lb = document.createElement('div');
		lb.className = 'kg-lightbox';
		lb.setAttribute('hidden', '');
		lb.innerHTML =
			'<button type="button" class="kg-lightbox__close" aria-label="Close">' +
				'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>' +
			'</button>' +
			'<div class="kg-lightbox__stage" data-kg-lb-stage><img class="kg-lightbox__img" alt=""></div>' +
			'<span class="kg-lightbox__hint" data-kg-lb-hint></span>';
		document.body.appendChild(lb);

		var lbStage = lb.querySelector('[data-kg-lb-stage]');
		var lbImg   = lb.querySelector('.kg-lightbox__img');
		var lbClose = lb.querySelector('.kg-lightbox__close');
		var lbHint  = lb.querySelector('[data-kg-lb-hint]');
		var lbLastFocus = null;
		var zoomLabel = '', zoomOutLabel = '';

		function lbResetZoom() {
			lbImg.classList.remove('is-zoomed');
			lbStage.scrollTop = 0; lbStage.scrollLeft = 0;
			lbHint.textContent = zoomLabel;
			lbHint.hidden = !zoomLabel;
		}
		function lbOpen(trigger) {
			var im = trigger.matches('img') ? trigger : trigger.querySelector('img');
			lbImg.src = trigger.getAttribute('data-kg-lightbox-src') || (im && (im.currentSrc || im.src)) || '';
			lbImg.alt = im ? im.alt : '';
			zoomLabel = trigger.getAttribute('data-kg-lightbox-zoom') || '';
			zoomOutLabel = trigger.getAttribute('data-kg-lightbox-zoomout') || '';
			lbResetZoom();
			lb.removeAttribute('hidden');
			body.classList.add('kg-lightbox-open');
			lbLastFocus = document.activeElement;
			lbClose.focus();
		}
		function lbCloseFn() {
			lb.setAttribute('hidden', '');
			body.classList.remove('kg-lightbox-open');
			lbResetZoom();
			if (lbLastFocus && lbLastFocus.focus) { lbLastFocus.focus(); }
		}

		lightboxTriggers.forEach(function (t) {
			t.addEventListener('click', function () { lbOpen(t); });
		});
		lbImg.addEventListener('click', function (e) {
			e.stopPropagation();
			var zoomed = lbImg.classList.toggle('is-zoomed');
			lbHint.textContent = zoomed ? zoomOutLabel : zoomLabel;
			lbHint.hidden = !lbHint.textContent;
			if (!zoomed) { lbStage.scrollTop = 0; lbStage.scrollLeft = 0; }
		});
		lbClose.addEventListener('click', lbCloseFn);
		lb.addEventListener('click', function (e) {
			if (e.target === lb || e.target === lbStage) { lbCloseFn(); }
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && !lb.hasAttribute('hidden')) { lbCloseFn(); }
		});
	}

	/* ----------------------------------------------------------------
	 * Schools: Teacher / Principal dashboard view switch. A sliding
	 * toggle swaps the two views with a directional slide-in and eases
	 * the section background to a lighter navy on the Principal view.
	 * It auto-advances to Principal once — after a read-pause — unless
	 * the visitor has already switched it themselves.
	 * -------------------------------------------------------------- */
	document.querySelectorAll('[data-kg-dashtoggle]').forEach(function (section) {
		var toggle = section.querySelector('.kg-viewtoggle');
		var btns = Array.prototype.slice.call(section.querySelectorAll('[data-kg-view-btn]'));
		var views = Array.prototype.slice.call(section.querySelectorAll('.kg-dashview'));
		var stage = section.querySelector('.kg-dashstage');
		if (!toggle || !btns.length || !views.length) { return; }

		var AUTO_MS = 14000; // long enough to read the Teacher view first
		var current = 'teacher';
		var autoTimer = null;
		var touched = false;

		function setView(view, animate) {
			if (view === current) { return; }
			var dir = view === 'principal' ? 'right' : 'left';
			current = view;

			toggle.classList.toggle('is-principal', view === 'principal');
			section.classList.toggle('is-principal', view === 'principal');
			btns.forEach(function (b) {
				var on = b.getAttribute('data-kg-view-btn') === view;
				b.classList.toggle('is-active', on);
				b.setAttribute('aria-pressed', on ? 'true' : 'false');
			});
			views.forEach(function (v) {
				var on = v.getAttribute('data-kg-view') === view;
				v.classList.remove('anim-left', 'anim-right');
				if (on) {
					v.classList.add('is-shown');
					v.removeAttribute('aria-hidden');
					if (animate && !reducedMotion) {
						void v.offsetWidth; // restart the keyframe
						v.classList.add(dir === 'right' ? 'anim-right' : 'anim-left');
					}
				} else {
					v.classList.remove('is-shown');
					v.setAttribute('aria-hidden', 'true');
				}
			});
			track('school_view_switch', { view: view });
		}

		function stopAuto() { if (autoTimer) { clearTimeout(autoTimer); autoTimer = null; } }

		btns.forEach(function (b) {
			b.addEventListener('click', function () {
				touched = true;
				stopAuto();
				setView(b.getAttribute('data-kg-view-btn'), true);
			});
		});

		// Arm the one-shot auto-switch once the dashboards are actually on screen.
		if ('IntersectionObserver' in window && stage) {
			new IntersectionObserver(function (entries, obs) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting && !touched && !autoTimer) {
						autoTimer = setTimeout(function () {
							if (!touched) { setView('principal', true); }
						}, AUTO_MS);
						obs.disconnect();
					}
				});
			}, { threshold: 0.4 }).observe(stage);
		}
	});

	/* ----------------------------------------------------------------
	 * Market + language choice persistence.
	 *
	 * Saves the user's explicit market:lang selection to a cookie (readable
	 * by the Cloudflare Worker on the bare domain) and to localStorage (used
	 * client-side). A saved choice permanently overrides geo-detection.
	 *
	 * Cookie format:  kg_choice=au:en    (market:lang)
	 * ------------------------------------------------------------ */

	function kgSetChoice(market, lang) {
		if (!market || !lang) { return; }
		var val = market + ':' + lang;
		document.cookie = 'kg_choice=' + val + ';path=/;max-age=31536000;SameSite=Lax';
		try { localStorage.setItem('kg_choice', val); } catch (e) {}
	}

	// Intercept any link that carries data-kg-choice="market:lang" (market
	// switcher, language switcher, region banner). Save the choice before
	// the browser follows the link so the Cloudflare Worker respects it on
	// the next bare-domain visit.
	document.addEventListener('click', function (e) {
		var el = e.target.closest('[data-kg-choice]');
		if (!el) { return; }
		var parts = (el.getAttribute('data-kg-choice') || '').split(':');
		if (parts.length === 2 && parts[0] && parts[1]) {
			kgSetChoice(parts[0], parts[1]);
		}
	});

	/* ----------------------------------------------------------------
	 * Market switcher dropdown (mirrors the language switcher pattern)
	 * -------------------------------------------------------------- */
	document.querySelectorAll('[data-kg-market]').forEach(function (market) {
		var btn = market.querySelector('.kg-market__btn');
		if (!btn) { return; }
		btn.addEventListener('click', function (e) {
			e.stopPropagation();
			var open = market.classList.toggle('is-open');
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	});
	document.addEventListener('click', function () {
		document.querySelectorAll('[data-kg-market].is-open').forEach(function (market) {
			market.classList.remove('is-open');
			var btn = market.querySelector('.kg-market__btn');
			if (btn) { btn.setAttribute('aria-expanded', 'false'); }
		});
	});
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') {
			document.querySelectorAll('[data-kg-market].is-open').forEach(function (market) {
				market.classList.remove('is-open');
				var btn = market.querySelector('.kg-market__btn');
				if (btn) { btn.setAttribute('aria-expanded', 'false'); }
			});
		}
	});

	/* ----------------------------------------------------------------
	 * Region selector banner (bare domain only)
	 * Hides itself once a region link is clicked.
	 * -------------------------------------------------------------- */
	var regionBar = document.querySelector('[data-kg-region-bar]');
	if (regionBar) {
		regionBar.querySelectorAll('[data-kg-choice]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				regionBar.style.transition = 'opacity .25s ease, transform .25s ease';
				regionBar.style.opacity  = '0';
				regionBar.style.transform = 'translateY(-100%)';
			});
		});
	}

	/* ----------------------------------------------------------------
	 * "Choose Region" buttons — pricing page, bare domain only.
	 * Scrolls the region banner into view and pulses it.
	 * -------------------------------------------------------------- */
	document.addEventListener('click', function (e) {
		var el = e.target.closest('[data-kg-choose-region]');
		if (!el) { return; }
		e.preventDefault();
		var bar = document.querySelector('[data-kg-region-bar]');
		if (!bar) { return; }
		// Scroll so the bar clears the sticky header with a little breathing room
		var header = document.querySelector('[data-kg-header]');
		var headerH = header ? header.offsetHeight : 0;
		var barTop = bar.getBoundingClientRect().top + window.scrollY - headerH - 12;
		window.scrollTo({ top: Math.max(0, barTop), behavior: 'smooth' });
		bar.classList.remove('kg-region-bar--pulse');
		void bar.offsetWidth; // eslint-disable-line no-void
		bar.classList.add('kg-region-bar--pulse');
		bar.addEventListener('animationend', function () {
			bar.classList.remove('kg-region-bar--pulse');
		}, { once: true });
	});
})();
