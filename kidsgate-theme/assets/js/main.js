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
	if (body.classList.contains('page-template-page-rewards')) { track('rewards_page_view'); }
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
		var duration = 2200;
		var start = null;
		// Number and suffix live in separate nodes so the suffix can be styled
		// (e.g. tinted with the card accent) and only the number updates per frame.
		el.textContent = '';
		var numNode = document.createTextNode('0');
		el.appendChild(numNode);
		if (suffix) {
			var sufEl = document.createElement('span');
			sufEl.className = 'kg-counter__suffix';
			sufEl.textContent = suffix;
			el.appendChild(sufEl);
		}
		function tick(ts) {
			if (!start) { start = ts; }
			var p = Math.min((ts - start) / duration, 1);
			var eased = 1 - Math.pow(1 - p, 3);
			numNode.nodeValue = Math.round(target * eased).toLocaleString();
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
			// Panels ship [hidden] for no-JS visitors. The open/close animation
			// (grid-rows 0fr→1fr) needs them rendered, so unhide once wired up —
			// the closed state is CSS-driven (collapsed + visibility:hidden).
			var initPanel = document.getElementById(btn.getAttribute('aria-controls'));
			if (initPanel) { initPanel.removeAttribute('hidden'); }
			btn.addEventListener('click', function () {
				var item = btn.closest('.kg-faq__item');
				var open = btn.getAttribute('aria-expanded') === 'true';

				// Close all other items in this accordion group first
				if (!open) {
					faq.querySelectorAll('.kg-faq__item.is-open').forEach(function (other) {
						if (other === item) { return; }
						var otherBtn = other.querySelector('.kg-faq__q button');
						if (otherBtn) { otherBtn.setAttribute('aria-expanded', 'false'); }
						other.classList.remove('is-open');
					});
				}

				btn.setAttribute('aria-expanded', open ? 'false' : 'true');
				item.classList.toggle('is-open', !open);
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

	/* --------------------------------------------------------------
	 * Dynamic "Save %" badges.
	 * Percentages are computed here from the raw displayed price
	 * numbers (data-kg-rates) — never hardcoded — and exclude the
	 * one-time activation fee. Mirrors the server-side kg_save_pct().
	 * -------------------------------------------------------------- */
	function computeSaveBadges(mode) {
		var box = document.querySelector('[data-kg-rates]');
		if (!box) { return; }
		var m1 = parseFloat(box.getAttribute('data-m1'));
		var y1 = parseFloat(box.getAttribute('data-y1'));
		var m2 = parseFloat(box.getAttribute('data-m2'));
		var y2 = parseFloat(box.getAttribute('data-y2'));
		var tpl = box.getAttribute('data-label') || 'Save {n}%';
		if (!(m1 > 0)) { return; }

		// Round DOWN so an advertised saving is never overstated.
		function pct(from, to) { return Math.floor((1 - to / from) * 100); }
		function label(n) { return tpl.replace('{n}', n); }

		var annual        = pct(m1, y1);      // annual vs monthly (single subject)
		var bundleMonthly = pct(2 * m1, m2);  // two subjects vs two single subjects (monthly)
		var bundleAnnual  = pct(2 * y1, y2);  // two subjects vs two single subjects (annual)

		// The Annual toggle pill is the single place the annual saving is shown.
		document.querySelectorAll('[data-kg-save-annual]').forEach(function (el) {
			el.textContent = label(annual);
		});
		document.querySelectorAll('[data-kg-save-card="two"]').forEach(function (el) {
			// Two-subject bundle vs two single subjects, compared like-for-like on
			// the same billing period (no mixing monthly and annual).
			el.textContent = label(mode === 'y' ? bundleAnnual : bundleMonthly);
			el.hidden = false;
		});
	}

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
		// Recompute the "Save %" badges for the active billing mode.
		computeSaveBadges(mode);
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
	 * Info tooltips ("?" bubbles). Hover and keyboard focus are handled
	 * in CSS; a tap toggles the bubble, and an outside tap or Escape
	 * closes it, so it works on touch devices too.
	 * -------------------------------------------------------------- */
	function closeAllTips(except) {
		document.querySelectorAll('[data-kg-tip].is-open').forEach(function (tip) {
			if (tip === except) { return; }
			tip.classList.remove('is-open');
			var b = tip.querySelector('.kg-tip__btn');
			if (b) { b.setAttribute('aria-expanded', 'false'); }
		});
	}
	document.querySelectorAll('[data-kg-tip]').forEach(function (tip) {
		var btn = tip.querySelector('.kg-tip__btn');
		if (!btn) { return; }
		btn.addEventListener('click', function (e) {
			e.stopPropagation();
			closeAllTips(tip);
			var open = tip.classList.toggle('is-open');
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	});
	document.addEventListener('click', function () { closeAllTips(null); });
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') {
			var openBtn = document.querySelector('[data-kg-tip].is-open .kg-tip__btn');
			closeAllTips(null);
			if (openBtn) { openBtn.focus(); }
		}
	});

	/* ----------------------------------------------------------------
	 * Adaptive AI engine visualisation (Features spotlight).
	 *
	 * A living learning path: the child's dot travels through lesson
	 * nodes, lighting the trail as it goes. When a topic is missed the
	 * AI visibly reacts — it scans the miss, grows a re-teach detour
	 * (story, game, quiz) beneath the path, walks the child through it,
	 * then returns to master the original topic before the journey
	 * continues. The path literally reshapes itself around the mistake,
	 * which is the whole pitch. Skills rotate each cycle.
	 * -------------------------------------------------------------- */
	document.querySelectorAll('[data-kg-aiviz]').forEach(function (viz) {
		var dataEl = viz.querySelector('[data-kg-aiviz-data]');
		if (!dataEl) { return; }
		var d = JSON.parse(dataEl.textContent);
		var skills = d.skills || [];
		var angles = d.angles || [];
		var phases = d.phases || [];

		var stage    = viz.querySelector('[data-kg-aipath]');
		var statusEl = viz.querySelector('[data-kg-aiviz-status]');
		if (!stage || !skills.length) { return; }

		var NS = 'http://www.w3.org/2000/svg';
		var mainPath = stage.querySelector('[data-kg-path-main]');
		var mainProg = stage.querySelector('[data-kg-prog-main]');
		var detPath  = stage.querySelector('[data-kg-path-detour]');
		var detProg  = stage.querySelector('[data-kg-prog-detour]');
		var beam     = stage.querySelector('[data-kg-beam]');
		var fxG      = stage.querySelector('[data-kg-fx]');
		var nodesG   = stage.querySelector('[data-kg-nodes]');
		var card     = stage.querySelector('[data-kg-card]');
		var cardRect = card.querySelector('rect');
		var cardGlyph = card.querySelector('[data-kg-card-glyph]');
		var cardWord  = card.querySelector('[data-kg-card-word]');
		var dot      = stage.querySelector('[data-kg-dot]');

		// Node waypoints along the main trail; index 2 is the miss node.
		var mainLen = mainPath.getTotalLength();
		var MAIN_FR = [0, 0.25, 0.5, 0.75, 1];
		var MISS = 2;
		var mainPts = MAIN_FR.map(function (f) { return mainPath.getPointAtLength(f * mainLen); });
		var n2 = mainPts[MISS];

		// The re-teach detour loops out of the miss node and back into it.
		detPath.setAttribute('d',
			'M' + n2.x + ' ' + n2.y +
			' C ' + (n2.x - 52) + ' ' + (n2.y + 46) + ', ' + (n2.x - 30) + ' ' + (n2.y + 92) + ', ' + (n2.x + 38) + ' ' + (n2.y + 90) +
			' C ' + (n2.x + 102) + ' ' + (n2.y + 88) + ', ' + (n2.x + 116) + ' ' + (n2.y + 38) + ', ' + n2.x + ' ' + n2.y);
		detProg.setAttribute('d', detPath.getAttribute('d'));
		var detLen = detPath.getTotalLength();
		var DET_FR = [0.3, 0.58, 0.84];
		var detPts = DET_FR.map(function (f) { return detPath.getPointAtLength(f * detLen); });

		beam.setAttribute('x2', n2.x);
		beam.setAttribute('y2', n2.y - 20);
		card.setAttribute('transform', 'translate(' + n2.x + ' ' + (n2.y - 56) + ')');

		// Progress strokes are driven per-frame; the detour rail draw-in is
		// a one-off CSS transition set inline when the AI grows the branch.
		mainProg.style.strokeDasharray = mainLen;
		mainProg.style.strokeDashoffset = mainLen;
		detProg.style.strokeDasharray = detLen;
		detProg.style.strokeDashoffset = detLen;
		detPath.style.strokeDasharray = detLen;
		detPath.style.strokeDashoffset = detLen;

		function makeNode(p, mini, labelDy) {
			var g = document.createElementNS(NS, 'g');
			g.setAttribute('class', 'kg-ainode' + (mini ? ' kg-ainode--mini' : ''));
			g.setAttribute('transform', 'translate(' + p.x + ' ' + p.y + ')');
			var inner = document.createElementNS(NS, 'g');
			inner.setAttribute('class', 'kg-ainode__in');
			var burst = document.createElementNS(NS, 'circle');
			burst.setAttribute('class', 'kg-ainode__burst');
			burst.setAttribute('r', mini ? 11 : 15);
			var c = document.createElementNS(NS, 'circle');
			c.setAttribute('class', 'kg-ainode__c');
			c.setAttribute('r', mini ? 11 : 15);
			var check = document.createElementNS(NS, 'path');
			check.setAttribute('class', 'kg-ainode__check');
			check.setAttribute('d', mini ? 'M-4 .5 L-1 3.5 L4.5 -3' : 'M-5.5 .5 L-1.5 4.5 L6 -4');
			var t = document.createElementNS(NS, 'text');
			t.setAttribute('class', 'kg-ainode__label');
			t.setAttribute('y', labelDy);
			inner.appendChild(burst); inner.appendChild(c); inner.appendChild(check);
			g.appendChild(inner); g.appendChild(t);
			nodesG.appendChild(g);
			return { g: g, label: t, inner: inner };
		}

		var mainNodes = mainPts.map(function (p, i) {
			var n = makeNode(p, false, p.y >= 110 ? 33 : -25);
			if (i === 0) { n.label.setAttribute('x', 18); } // keep the edge label inside the canvas
			if (i === mainPts.length - 1) {
				n.g.classList.add('kg-ainode--goal');
				var star = document.createElementNS(NS, 'text');
				star.setAttribute('class', 'kg-ainode__star');
				star.setAttribute('dy', '.38em');
				star.textContent = '★';
				n.inner.appendChild(star);
			}
			return n;
		});
		var detNodes = detPts.map(function (p, i) {
			var n = makeNode(p, true, 27);
			n.label.textContent = angles[i] || '';
			return n;
		});

		function setStatus(base, skill) {
			statusEl.innerHTML = base + (skill ? ' &middot; <strong>' + skill + '</strong>' : '');
		}
		// Rotate which subject sits on each node so the missed topic varies.
		var cycle = 0;
		function setLabels() {
			var off = cycle % skills.length;
			for (var i = 0; i < skills.length && i < mainNodes.length - 1; i++) {
				mainNodes[i].label.textContent = skills[(i + off) % skills.length];
			}
			return skills[(MISS + off) % skills.length];
		}
		function showCard(good, word) {
			cardGlyph.textContent = good ? '✓' : '✗';
			cardWord.textContent = ' ' + word;
			card.setAttribute('class', 'kg-aipath__card is-show ' + (good ? 'is-good' : 'is-bad'));
			var w = card.querySelector('text').getComputedTextLength() + 30;
			cardRect.setAttribute('width', w);
			cardRect.setAttribute('x', -w / 2);
		}
		function hideCard() { card.setAttribute('class', 'kg-aipath__card'); }
		function ripple() {
			for (var i = 0; i < 2; i++) {
				var r = document.createElementNS(NS, 'circle');
				r.setAttribute('class', 'kg-aipath__ripple');
				r.setAttribute('cx', n2.x); r.setAttribute('cy', n2.y);
				r.setAttribute('r', 16);
				r.style.animationDelay = (i * 0.4) + 's';
				fxG.appendChild(r);
			}
		}
		function clearRipples() { while (fxG.firstChild) { fxG.removeChild(fxG.firstChild); } }

		// Static, motion-free snapshot for reduced-motion users: the whole
		// journey shown complete, detour included, miss node mastered.
		if (reducedMotion) {
			var skill0 = setLabels();
			mainProg.style.strokeDashoffset = 0;
			detPath.style.strokeDashoffset = 0;
			detProg.style.strokeDashoffset = 0;
			mainNodes.forEach(function (n, i) { n.g.classList.add(i === MISS ? 'is-mastered' : 'is-done'); });
			detNodes.forEach(function (n) { n.g.classList.add('is-in', 'is-done'); });
			showCard(true, d.got);
			var pEnd = mainPath.getPointAtLength(mainLen);
			dot.setAttribute('cx', pEnd.x); dot.setAttribute('cy', pEnd.y);
			setStatus(phases[4] || '', skill0);
			return;
		}

		var timers = [];
		function after(ms, fn) { timers.push(setTimeout(fn, ms)); }
		function stopAll() { timers.forEach(clearTimeout); timers = []; }

		// Move the dot from fraction f0 to f1 of a path, lighting the
		// progress stroke behind it and ticking nodes as they are passed.
		// Timer-driven (like the rest of this file) rather than rAF so a
		// backgrounded tab degrades to coarse steps instead of freezing.
		function travel(path, prog, f0, f1, dur, onFrac, done) {
			var t0 = performance.now();
			var L = path.getTotalLength();
			function step() {
				var k = Math.min(1, (performance.now() - t0) / dur);
				var e = k < 0.5 ? 2 * k * k : 1 - Math.pow(-2 * k + 2, 2) / 2;
				var f = f0 + (f1 - f0) * e;
				var p = path.getPointAtLength(f * L);
				dot.setAttribute('cx', p.x); dot.setAttribute('cy', p.y);
				prog.style.strokeDashoffset = L * (1 - f);
				if (onFrac) { onFrac(f); }
				if (k < 1) { after(28, step); }
				else if (done) { done(); }
			}
			step();
		}
		function tickMain(f) {
			mainNodes.forEach(function (n, i) {
				if (i !== MISS && f >= MAIN_FR[i] - 0.01 && !n.g.classList.contains('is-done')) { n.g.classList.add('is-done'); }
			});
		}
		function tickDet(f) {
			detNodes.forEach(function (n, i) {
				if (f >= DET_FR[i] && !n.g.classList.contains('is-done')) { n.g.classList.add('is-done'); }
			});
		}

		function resetStage() {
			mainProg.style.strokeDashoffset = mainLen;
			detProg.style.strokeDashoffset = detLen;
			detPath.style.transition = 'none';
			detPath.style.strokeDashoffset = detLen;
			mainNodes.forEach(function (n) { n.g.classList.remove('is-done', 'is-miss', 'is-mastered'); });
			detNodes.forEach(function (n) { n.g.classList.remove('is-in', 'is-done'); });
			hideCard();
			clearRipples();
			beam.classList.remove('is-on');
			var p0 = mainPath.getPointAtLength(0);
			dot.setAttribute('cx', p0.x); dot.setAttribute('cy', p0.y);
		}

		function run() {
			stopAll();
			var skill = setLabels();
			resetStage();
			stage.classList.remove('is-fade');
			setStatus(phases[0]);
			after(500, function () {
				setStatus(phases[1]);
				// Leg 1: travel to the miss node, ticking earlier topics done.
				travel(mainPath, mainProg, 0, MAIN_FR[MISS], 2600, tickMain, function () {
					// The miss: red pulse, "✗ missed" chip, AI scan beam + ripples.
					mainNodes[MISS].g.classList.add('is-miss');
					showCard(false, d.missed);
					beam.classList.add('is-on');
					ripple();
					setStatus(phases[2], skill);
					after(1600, function () {
						// The AI grows the re-teach branch; its stops pop in.
						beam.classList.remove('is-on');
						clearRipples();
						detPath.style.transition = 'stroke-dashoffset .9s cubic-bezier(.22,.9,.36,1)';
						detPath.style.strokeDashoffset = 0;
						detNodes.forEach(function (n, i) { after(350 + i * 220, function () { n.g.classList.add('is-in'); }); });
						setStatus(phases[3], skill);
						after(1000, function () {
							// Leg 2: around the detour — story, game, quiz.
							travel(detPath, detProg, 0, 1, 3400, tickDet, function () {
								// Back at the topic: mastered, burst, chip flips to ✓.
								mainNodes[MISS].g.classList.remove('is-miss');
								mainNodes[MISS].g.classList.add('is-mastered');
								showCard(true, d.got);
								setStatus(phases[4], skill);
								after(1300, function () {
									// Leg 3: on to the goal star.
									travel(mainPath, mainProg, MAIN_FR[MISS], 1, 2300, tickMain, function () {
										after(1900, function () {
											stage.classList.add('is-fade');
											after(500, function () { cycle++; run(); });
										});
									});
								});
							});
						});
					});
				});
			});
		}

		// Only animate while the widget is on screen.
		var running = false;
		if ('IntersectionObserver' in window) {
			new IntersectionObserver(function (entries) {
				entries.forEach(function (e) {
					if (e.isIntersecting && !running) { running = true; run(); }
					else if (!e.isIntersecting && running) { running = false; stopAll(); }
				});
			}, { threshold: 0.25 }).observe(viz);
		} else {
			run();
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
		var secure = location.protocol === 'https:' ? ';Secure' : '';
		document.cookie = 'kg_choice=' + val + ';path=/;max-age=31536000;SameSite=Lax' + secure;
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

	/* ----------------------------------------------------------------
	 * Rewards page — the earning demo.
	 *
	 * The three factor cards take turns "earning": the live card lights
	 * up, a token flies from its icon into the jar (outer span moves on
	 * X, inner on Y with a gravity bezier = an arc), the jar fills and
	 * the count ticks up. At 60 tokens a reward chip pops, the jar banks
	 * and the loop starts over. Pure illustration, no real data. Runs
	 * only while on screen; reduced motion gets a static, part-filled jar.
	 * -------------------------------------------------------------- */
	var earn = document.querySelector('[data-kg-earn]');
	if (earn) {
		var earnCards = earn.querySelectorAll('.kg-earn__card');
		var earnJar = earn.querySelector('[data-kg-earn-jar]');
		var earnJarSvg = earnJar.querySelector('.kg-earnjar');
		var earnFill = earn.querySelector('[data-kg-earn-fill]');
		var earnCount = earn.querySelector('[data-kg-earn-count]');
		var earnUnlock = earn.querySelector('[data-kg-earn-unlock]');
		var EARN_TARGET = 60, EARN_STEP = 10, EARN_FLIGHT = 840;
		// SVG interior: the fill rect grows upward inside x18 y32 w84 h96.
		var EARN_JAR_H = 96, EARN_JAR_BOTTOM = 128;
		var earnTotal = 0, earnIdx = -1, earnTimer = null, earnBanking = false;

		var EARN_COIN_SVG = '<svg viewBox="0 0 38 38" focusable="false" aria-hidden="true"><circle cx="19" cy="19" r="16.5" fill="#F5A623" stroke="#D98C00" stroke-width="3"/><path d="M19 12.2l1.9 4 4.4.5-3.3 3 .9 4.3-3.9-2.2-3.9 2.2.9-4.3-3.3-3 4.4-.5z" fill="#D98C00" opacity=".8"/></svg>';

		function earnSetFill(n) {
			var h = Math.round(EARN_JAR_H * Math.min(n / EARN_TARGET, 1));
			earnFill.style.height = h + 'px';
			earnFill.style.y = (EARN_JAR_BOTTOM - h) + 'px';
		}

		if (reducedMotion) {
			// Static tableau: a part-filled jar and a believable count.
			earnTotal = 40;
			earnCount.textContent = '40';
			earnFill.style.transition = 'none';
			earnSetFill(40);
		} else {
			var earnFly = function (fromEl, done) {
				var s = fromEl.getBoundingClientRect();
				var t = earnJarSvg.getBoundingClientRect();
				var base = earn.getBoundingClientRect();
				var sx = s.left + s.width / 2 - base.left;
				var sy = s.top + s.height / 2 - base.top;
				var tx = t.left + t.width / 2 - base.left;
				var ty = t.top + 10 - base.top;
				var coin = document.createElement('span');
				coin.className = 'kg-earncoin';
				coin.innerHTML = '<i>' + EARN_COIN_SVG + '</i>';
				coin.style.left = sx + 'px';
				coin.style.top = sy + 'px';
				earn.appendChild(coin);
				var innerEl = coin.firstChild;
				requestAnimationFrame(function () {
					requestAnimationFrame(function () {
						coin.style.transform = 'translateX(' + (tx - sx) + 'px)';
						innerEl.style.transform = 'translateY(' + (ty - sy) + 'px)';
					});
				});
				setTimeout(function () {
					coin.remove();
					done();
				}, EARN_FLIGHT);
			};

			var earnBank = function () {
				earnBanking = true;
				earnUnlock.hidden = false;
				setTimeout(function () {
					earnUnlock.hidden = true;
					earnTotal = 0;
					earnCount.textContent = '0';
					earnSetFill(0);
					earnCards.forEach(function (c) { c.classList.remove('is-live'); });
					earnBanking = false;
				}, 2000);
			};

			var earnStep = function () {
				if (earnBanking) { return; }
				earnIdx = (earnIdx + 1) % earnCards.length;
				earnCards.forEach(function (c, i) { c.classList.toggle('is-live', i === earnIdx); });
				var bubble = earnCards[earnIdx].querySelector('.kg-bubble');
				earnFly(bubble, function () {
					earnTotal += EARN_STEP;
					earnCount.textContent = earnTotal;
					earnSetFill(earnTotal);
					earnJar.classList.remove('is-catch');
					void earnJar.offsetWidth;
					earnJar.classList.add('is-catch');
					var plus = document.createElement('span');
					plus.className = 'kg-earnplus';
					plus.textContent = '+' + EARN_STEP;
					earnJar.appendChild(plus);
					setTimeout(function () { plus.remove(); }, 900);
					if (earnTotal >= EARN_TARGET) { earnBank(); }
				});
			};

			var earnStart = function () {
				if (!earnTimer) { earnTimer = setInterval(earnStep, 1500); }
			};
			var earnStop = function () {
				if (earnTimer) { clearInterval(earnTimer); earnTimer = null; }
			};

			if ('IntersectionObserver' in window) {
				new IntersectionObserver(function (entries) {
					entries.forEach(function (entry) {
						if (entry.isIntersecting) { earnStart(); } else { earnStop(); }
					});
				}, { threshold: 0.3 }).observe(earn);
			} else {
				earnStart();
			}
		}
	}

	/* ----------------------------------------------------------------
	 * Rewards page — the storefront demo.
	 *
	 * Tapping a shelf item "buys" it: the balance ticks down, a "−price"
	 * floats off the balance pill and the item flips to its unlocked
	 * state. Once the shelf is empty the shop restocks and the balance
	 * refills for the next browser. Pure illustration, no real data;
	 * the starting balance is read from the markup so it never drifts. -- */
	var store = document.querySelector('[data-kg-store]');
	if (store) {
		var storeBalanceEl = store.querySelector('[data-kg-store-balance]');
		var storeItems = Array.prototype.slice.call(store.querySelectorAll('[data-kg-store-item]'));
		var storeStart = parseInt(storeBalanceEl.textContent, 10) || 0;
		var storeLeft = storeStart;
		var storeRestockTimer = null;

		var storeTick = function () {
			var pill = storeBalanceEl.parentNode;
			pill.classList.remove('is-tick');
			void pill.offsetWidth; // eslint-disable-line no-void
			pill.classList.add('is-tick');
		};

		var storeRestock = function () {
			storeLeft = storeStart;
			storeBalanceEl.textContent = storeLeft;
			storeItems.forEach(function (it) {
				it.classList.remove('is-owned');
				it.setAttribute('aria-pressed', 'false');
			});
			storeTick();
		};

		storeItems.forEach(function (item) {
			item.addEventListener('click', function () {
				if (item.classList.contains('is-owned')) { return; }
				var price = parseInt(item.getAttribute('data-kg-price'), 10) || 0;
				if (price > storeLeft) { return; }
				storeLeft -= price;
				storeBalanceEl.textContent = storeLeft;
				item.classList.add('is-owned');
				item.setAttribute('aria-pressed', 'true');
				storeTick();
				if (!reducedMotion) {
					var minus = document.createElement('span');
					minus.className = 'kg-storeminus';
					minus.textContent = '−' + price;
					storeBalanceEl.parentNode.appendChild(minus);
					setTimeout(function () { minus.remove(); }, 900);
				}
				var allOwned = storeItems.every(function (it) { return it.classList.contains('is-owned'); });
				if (allOwned) {
					clearTimeout(storeRestockTimer);
					storeRestockTimer = setTimeout(storeRestock, 2200);
				}
			});
		});
	}

	/* ----------------------------------------------------------------
	 * Sponsors page — pre-fill the enquiry form from the visitor's choices.
	 *
	 * The "Ways to partner" cards carry data-kg-partner (index-aligned with
	 * the "What are you interested in?" options) and the sponsorship-tier
	 * buttons carry data-kg-tier (index-aligned with the "Partnership level"
	 * options). Clicking a card sets the matching <select> before its anchor
	 * scrolls the visitor down, so both boxes arrive already filled. Index
	 * alignment keeps this working across every locale.
	 * -------------------------------------------------------------- */
	var sponsorInterest = document.getElementById('kg-sponsor-interest');
	var sponsorLevel = document.getElementById('kg-sponsor-level');
	if (sponsorInterest || sponsorLevel) {
		document.querySelectorAll('[data-kg-partner]').forEach(function (card) {
			card.addEventListener('click', function () {
				if (!sponsorInterest) { return; }
				var i = parseInt(card.getAttribute('data-kg-partner'), 10);
				if (i >= 0 && i < sponsorInterest.options.length) { sponsorInterest.selectedIndex = i; }
			});
		});
		document.querySelectorAll('[data-kg-tier]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				if (!sponsorLevel) { return; }
				var i = parseInt(btn.getAttribute('data-kg-tier'), 10);
				// +1 skips the "not sure yet" placeholder option at index 0.
				if (i >= 0 && i + 1 < sponsorLevel.options.length) { sponsorLevel.selectedIndex = i + 1; }
			});
		});
	}
})();
