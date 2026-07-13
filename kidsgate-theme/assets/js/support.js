/**
 * The Kids Gate — support page interactions.
 *
 *  1. FAQ live search + category filter.
 *  2. Contact form: client-side validation and a documented placeholder
 *     submission state (no backend/email service is connected yet).
 *  3. Guided support helper: a rule-based, chat-style widget. It is NOT
 *     presented as an AI assistant — it suggests topics, links FAQ answers
 *     and escalates to the form/email. Content comes from the
 *     #kg-helper-data JSON blob printed by kg_render_helper() in footer.php.
 *     Free-text questions are matched against the same tree with plain
 *     substring search (works for Thai/Chinese too — no tokenising).
 *     The transcript persists across pages via sessionStorage, and
 *     escalation links carry a kg_topic param that pre-selects the topic
 *     in the support form below.
 */
(function () {
	'use strict';

	function track(name, params) {
		if (window.kgTrack) { window.kgTrack(name, params); }
	}

	/* Localised UI strings arrive via wp_localize_script as KG_DATA.strings;
	 * every use carries an English fallback for the preview harness. */
	var S = (typeof KG_DATA !== 'undefined' && KG_DATA.strings) || {};

	/* ----------------------------------------------------------------
	 * FAQ search + category pills + sort
	 * -------------------------------------------------------------- */
	var search = document.querySelector('[data-kg-support-search]');
	var faqWrap = document.querySelector('[data-kg-faq-context="support"]');
	var emptyMsg = document.querySelector('.kg-faq__empty');
	var catButtons = document.querySelectorAll('[data-kg-support-cat]');
	var countEl = document.querySelector('[data-kg-faq-count]');
	var sortSelect = document.querySelector('[data-kg-faq-sort]');
	var activeCat = 'all';

	/* Progressive disclosure: show PAGE_SIZE questions at a time within the
	 * current filter, with a "See more" button that reveals another batch.
	 * shownCount is capped per filter change (a new search/category starts
	 * again at the first PAGE_SIZE matches). */
	var PAGE_SIZE = 10;
	var shownCount = PAGE_SIZE;
	var moreWrap = document.querySelector('[data-kg-faq-more-wrap]');
	var moreBtn = document.querySelector('[data-kg-faq-more]');
	var moreLabel = document.querySelector('[data-kg-faq-more-label]');
	var moreLabelBase = moreLabel ? moreLabel.textContent : '';

	/* Item index. Array order always mirrors DOM order (sortItems keeps both
	 * in sync), so pagination follows whatever order is on screen. Each item
	 * remembers its question span's original text so search highlighting can
	 * be applied and undone without touching markup. */
	var faqItems = [];
	if (faqWrap) {
		faqWrap.querySelectorAll('.kg-faq__item').forEach(function (item, i) {
			var qSpan = item.querySelector('.kg-faq__q button span');
			faqItems.push({
				el: item,
				order: i,
				cat: item.getAttribute('data-kg-faq-cat') || '',
				text: item.getAttribute('data-kg-faq-text') || '',
				qSpan: qSpan,
				qText: qSpan ? qSpan.textContent : ''
			});
		});
	}

	/* Rebuild a question label with <mark> around each case-insensitive term
	 * match (the filter is multi-word AND, so terms may sit apart in the
	 * text). Overlapping term ranges are merged so <mark>s never nest, and
	 * the label is assembled from text nodes, never concatenated HTML. */
	function highlightQuestion(entry, terms) {
		if (!entry.qSpan) { return; }
		var lower = entry.qText.toLowerCase();
		var ranges = [];
		terms.forEach(function (t) {
			var at = lower.indexOf(t);
			while (at !== -1) {
				ranges.push([at, at + t.length]);
				at = lower.indexOf(t, at + t.length);
			}
		});
		if (!ranges.length) {
			if (entry.qSpan.textContent !== entry.qText) { entry.qSpan.textContent = entry.qText; }
			return;
		}
		ranges.sort(function (a, b) { return a[0] - b[0]; });
		var merged = [ranges[0]];
		for (var i = 1; i < ranges.length; i++) {
			var last = merged[merged.length - 1];
			if (ranges[i][0] <= last[1]) { last[1] = Math.max(last[1], ranges[i][1]); }
			else { merged.push(ranges[i]); }
		}
		var frag = document.createDocumentFragment();
		var pos = 0;
		merged.forEach(function (r) {
			if (r[0] > pos) { frag.appendChild(document.createTextNode(entry.qText.slice(pos, r[0]))); }
			var mark = document.createElement('mark');
			mark.className = 'kg-mark';
			mark.textContent = entry.qText.slice(r[0], r[1]);
			frag.appendChild(mark);
			pos = r[1];
		});
		if (pos < entry.qText.length) { frag.appendChild(document.createTextNode(entry.qText.slice(pos))); }
		entry.qSpan.textContent = '';
		entry.qSpan.appendChild(frag);
	}

	/* resetPage: restart at the first PAGE_SIZE (used when the filter changes).
	 * forceReveal: mark newly shown items visible immediately — the reveal
	 * observer never fired for items that were display:none, so a filter or
	 * "See more" action must un-hide them itself. The initial page load leaves
	 * this false so the first batch keeps its scroll-triggered entrance. */
	function applyFilter(resetPage, forceReveal) {
		if (!faqWrap) { return; }
		if (resetPage) { shownCount = PAGE_SIZE; }
		var q = search ? search.value.trim().toLowerCase() : '';
		// Split into words and require each one somewhere in the item's text,
		// so "forgot password" matches even when the words aren't adjacent.
		// Thai/Chinese queries rarely contain spaces and pass through as one term.
		var terms = q ? q.split(/\s+/) : [];
		var matchCount = 0;
		var shown = 0;
		var catCounts = { all: 0 };
		faqItems.forEach(function (entry) {
			// Per-category counts follow the search text only, so every pill
			// shows how many answers it would reveal for the current query.
			var textMatch = terms.every(function (t) { return entry.text.indexOf(t) !== -1; });
			if (textMatch) {
				catCounts.all++;
				catCounts[entry.cat] = (catCounts[entry.cat] || 0) + 1;
			}
			if (!textMatch || (activeCat !== 'all' && entry.cat !== activeCat)) {
				entry.el.style.display = 'none';
				return;
			}
			matchCount++;
			highlightQuestion(entry, terms);
			if (shown < shownCount) {
				entry.el.style.display = '';
				if (forceReveal) { entry.el.classList.add('is-in'); }
				shown++;
			} else {
				entry.el.style.display = 'none';
			}
		});
		if (emptyMsg) { emptyMsg.style.display = matchCount === 0 ? 'block' : 'none'; }
		if (countEl) {
			countEl.textContent = (S.results_count || '{n} answers').replace('{n}', matchCount);
		}
		catButtons.forEach(function (btn) {
			var badge = btn.querySelector('[data-kg-cat-count]');
			if (badge) { badge.textContent = catCounts[btn.getAttribute('data-kg-support-cat')] || 0; }
		});
		if (moreWrap) {
			var remaining = matchCount - shown;
			moreWrap.hidden = remaining <= 0;
			if (remaining > 0 && moreLabel) {
				moreLabel.textContent = moreLabelBase + ' (' + remaining + ')';
			}
		}
	}

	if (search) { search.addEventListener('input', function () { applyFilter(true, true); }); }
	catButtons.forEach(function (btn) {
		btn.addEventListener('click', function () {
			activeCat = btn.getAttribute('data-kg-support-cat');
			catButtons.forEach(function (b) {
				b.setAttribute('aria-pressed', b.getAttribute('data-kg-support-cat') === activeCat ? 'true' : 'false');
			});
			applyFilter(true, true);
		});
	});

	/* Sort: "suggested" is the authored order, "az" sorts by question text in
	 * the page language. Reordering moves the real DOM nodes (kept before the
	 * empty-state sentinel) and re-syncs the faqItems array to match. */
	function sortItems(mode) {
		faqItems.sort(function (a, b) {
			return mode === 'az'
				? a.qText.localeCompare(b.qText, document.documentElement.lang || undefined)
				: a.order - b.order;
		});
		faqItems.forEach(function (entry) {
			faqWrap.insertBefore(entry.el, emptyMsg);
		});
	}
	if (sortSelect) {
		sortSelect.addEventListener('change', function () {
			sortItems(sortSelect.value);
			applyFilter(true, true);
			track('support_faq_sort', { order: sortSelect.value });
		});
	}

	if (moreBtn) {
		moreBtn.addEventListener('click', function () {
			shownCount += PAGE_SIZE;
			applyFilter(false, true);
			track('support_faq_more', { shown: shownCount });
		});
	}

	/* "Most asked" chips jump straight to one FAQ item: clear the filters so
	 * it's guaranteed to match, page far enough that progressive disclosure
	 * can't hide it, then open and scroll to it. */
	document.querySelectorAll('[data-kg-faq-jump]').forEach(function (chip) {
		chip.addEventListener('click', function () {
			var idx = chip.getAttribute('data-kg-faq-jump');
			var target = null;
			faqItems.forEach(function (entry, i) {
				if (entry.el.getAttribute('data-kg-faq-index') === idx) { target = { entry: entry, pos: i }; }
			});
			if (!target) { return; }
			if (search) { search.value = ''; }
			activeCat = 'all';
			catButtons.forEach(function (b) {
				b.setAttribute('aria-pressed', b.getAttribute('data-kg-support-cat') === 'all' ? 'true' : 'false');
			});
			shownCount = Math.max(PAGE_SIZE, target.pos + 1);
			applyFilter(false, true);
			var btn = target.entry.el.querySelector('.kg-faq__q button');
			if (btn && btn.getAttribute('aria-expanded') !== 'true') { btn.click(); }
			target.entry.el.scrollIntoView({ behavior: 'smooth', block: 'center' });
			if (btn) { btn.focus({ preventScroll: true }); }
			track('support_faq_popular', { faq_index: idx });
		});
	});

	// Establish the initial 10-question window (no forced reveal, so the first
	// batch still animates in on scroll).
	applyFilter(false, false);

	/* ----------------------------------------------------------------
	 * Support / Schools / Sponsors forms.
	 *
	 * On submit: validates required fields (with inline error messages
	 * where the template provides .kg-field__error slots), then POSTs the
	 * form as FormData to admin-post.php — inc/forms.php relays it to the
	 * support inbox via wp_mail(). If the endpoint is missing or errors,
	 * falls back to the previous behaviour: a mailto: draft in the
	 * visitor's email app, so no message is ever lost.
	 * -------------------------------------------------------------- */
	var form = document.querySelector('[data-kg-support-form]');
	if (form) {
		var statusEl = form.querySelector('[data-kg-form-status]');
		var submitBtn = form.querySelector('button[type="submit"]');
		var submitSpan = submitBtn ? submitBtn.querySelector('span') : null;
		var submitLabel = submitSpan ? submitSpan.textContent : '';

		var setFieldError = function (field, ok) {
			var wrap = field.closest('.kg-field');
			if (!wrap) { return; }
			wrap.classList.toggle('kg-field--error', !ok);
			var errEl = wrap.querySelector('.kg-field__error');
			if (!errEl) { return; }
			if (ok) {
				errEl.hidden = true;
				errEl.textContent = '';
				return;
			}
			errEl.hidden = false;
			errEl.textContent = (field.type === 'email' && !field.validity.valueMissing)
				? (S.err_email || 'Please enter a valid email address.')
				: (S.err_required || 'Please fill in this field.');
		};

		// Clear a field's error as soon as the visitor fixes it.
		form.addEventListener('input', function (e) {
			if (e.target.closest && e.target.closest('.kg-field--error')) {
				setFieldError(e.target, e.target.checkValidity());
			}
		});

		var showSuccess = function () {
			form.hidden = true;
			var success = document.querySelector('[data-kg-support-form-success]');
			if (success) {
				success.hidden = false;
				success.focus();
			}
		};

		var restoreSubmit = function () {
			if (submitBtn) { submitBtn.disabled = false; }
			if (submitSpan) { submitSpan.textContent = submitLabel; }
		};

		// Previous dispatch, kept as the no-backend fallback: a mailto: draft
		// with the form's subject attribute and every visible field value.
		var mailtoFallback = function () {
			var subject = form.getAttribute('data-kg-form-subject') || 'The Kids Gate Enquiry';
			var lines = [];
			form.querySelectorAll('[name]').forEach(function (field) {
				if (!field.name || field.type === 'submit' || field.type === 'hidden' || field.name === 'kg_website') { return; }
				var label = field.closest('.kg-field') && field.closest('.kg-field').querySelector('label');
				var key = label ? label.textContent.trim().replace(/:$/, '') : field.name;
				if (field.value.trim()) { lines.push(key + ': ' + field.value.trim()); }
			});
			var to = (typeof KG_DATA !== 'undefined' && KG_DATA.support_email) ? KG_DATA.support_email : 'support@thekidsgate.com';
			window.location.href = 'mailto:' + to + '?subject=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(lines.join('\n'));
		};

		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var valid = true;
			form.querySelectorAll('[required]').forEach(function (field) {
				var ok = field.checkValidity();
				setFieldError(field, ok);
				if (!ok) { valid = false; }
			});
			if (!valid) {
				var firstError = form.querySelector('.kg-field--error input, .kg-field--error textarea, .kg-field--error select');
				if (firstError) { firstError.focus(); }
				return;
			}
			track('support_form_submit', {
				topic: (form.querySelector('[name="kg_topic"]') || {}).value || ''
			});

			var ajaxUrl = (typeof KG_DATA !== 'undefined' && KG_DATA.ajax_url) || '';
			if (!ajaxUrl || !window.fetch || !form.querySelector('[name="action"]')) {
				mailtoFallback();
				showSuccess();
				return;
			}

			if (submitBtn) { submitBtn.disabled = true; }
			if (submitSpan) { submitSpan.textContent = S.sending || 'Sending…'; }
			if (statusEl) { statusEl.hidden = true; }

			fetch(ajaxUrl, { method: 'POST', body: new FormData(form), credentials: 'same-origin' })
				.then(function (res) { return res.json(); })
				.then(function (json) {
					if (!json || !json.success) { throw new Error('server'); }
					track('support_form_sent', { method: 'server' });
					showSuccess();
				})
				.catch(function () {
					// Endpoint unreachable or refused: degrade to the mailto:
					// draft. Where the template has a status slot (support
					// page) keep the form on screen with an honest note;
					// elsewhere keep the legacy draft-then-success flow.
					restoreSubmit();
					track('support_form_sent', { method: 'mailto' });
					mailtoFallback();
					if (statusEl) {
						statusEl.hidden = false;
						statusEl.textContent = S.error_generic || 'We couldn\'t send this directly, so your email app has opened with the message pre-filled — just press send there.';
					} else {
						showSuccess();
					}
				});
		});
	}

	/* ----------------------------------------------------------------
	 * Support form topic pre-selection.
	 *
	 * Helper escalation links append ?kg_topic=<top-level-topic-id>. The
	 * support form's topic <option>s are index-aligned across locales, so
	 * a static id → index map works for every language.
	 * -------------------------------------------------------------- */
	var topicSelect = document.getElementById('kg-sup-topic');
	if (topicSelect) {
		var TOPIC_INDEX = { pricing: 0, trial: 1, dashboard: 2, using: 3, schools: 4, contact: 5 };
		var params = new URLSearchParams(window.location.search);
		var wanted = params.get('kg_topic');
		if (wanted && TOPIC_INDEX.hasOwnProperty(wanted) && topicSelect.options.length > TOPIC_INDEX[wanted]) {
			topicSelect.selectedIndex = TOPIC_INDEX[wanted];
		}
		// A question typed into the helper rides along as ?kg_q= — start the
		// message box with it so the user never types it twice.
		var carried = (params.get('kg_q') || '').slice(0, 200);
		var messageBox = document.getElementById('kg-sup-message');
		if (carried && messageBox && !messageBox.value) {
			messageBox.value = carried;
		}
	}

	/* ----------------------------------------------------------------
	 * Message character counter (support form only). Wired after the kg_q
	 * prefill above so the initial count reflects any carried-over text.
	 * -------------------------------------------------------------- */
	var msgCount = document.querySelector('[data-kg-msg-count]');
	var msgField = document.getElementById('kg-sup-message');
	if (msgCount && msgField) {
		var updateCount = function () {
			var max = parseInt(msgField.getAttribute('maxlength'), 10) || 2000;
			msgCount.textContent = (S.chars_left || '{n} characters left').replace('{n}', max - msgField.value.length);
		};
		msgField.addEventListener('input', updateCount);
		updateCount();
	}

	/* ----------------------------------------------------------------
	 * Guided support helper — rule-based decision tree.
	 *
	 * The data is a recursive tree of nodes. A node is either a BRANCH
	 * (has `children`: more options) or a LEAF (has `answer`). Picking a
	 * branch drills down a level (with a Back button); picking a leaf
	 * prints its answer, offers related sibling questions, then asks
	 * "Was this helpful?" — Yes restarts at the top, No hands off to the
	 * support page. A free-text box at the bottom substring-matches the
	 * whole tree and jumps straight to the best nodes.
	 * -------------------------------------------------------------- */
	var fab = document.querySelector('[data-kg-helper-fab]');
	var helper = document.querySelector('[data-kg-helper]');
	var dataEl = document.getElementById('kg-helper-data');
	if (!fab || !helper || !dataEl) { return; }

	var data = JSON.parse(dataEl.textContent);
	var bodyEl = helper.querySelector('.kg-helper__body');
	var closeBtn = helper.querySelector('.kg-helper__close');
	var inputForm = helper.querySelector('[data-kg-helper-form]');
	var inputEl = document.getElementById('kg-helper-input');

	/* Transcript persistence: message bubbles only (options are re-offered
	 * fresh), so navigating between pages keeps the conversation. Keyed by
	 * language so switching locales starts clean. */
	var LOG_KEY = 'kgHelper.log.' + (data.lang || 'en');
	var OPEN_KEY = 'kgHelper.open.' + (data.lang || 'en');
	function readLog() {
		try { return JSON.parse(sessionStorage.getItem(LOG_KEY)) || []; } catch (e) { return []; }
	}
	function saveLog(entry) {
		try {
			var log = readLog();
			log.push(entry);
			sessionStorage.setItem(LOG_KEY, JSON.stringify(log.slice(-40)));
		} catch (e) { /* storage unavailable — transcript just won't persist */ }
	}

	function escapeHtml(s) {
		var d = document.createElement('div');
		d.textContent = s;
		return d.innerHTML;
	}

	function addMessage(html, isUser, skipSave) {
		var msg = document.createElement('div');
		msg.className = 'kg-helper__msg' + (isUser ? ' kg-helper__msg--user' : '');
		msg.innerHTML = html;
		bodyEl.appendChild(msg);
		bodyEl.scrollTop = bodyEl.scrollHeight;
		if (!skipSave) { saveLog({ h: html, u: !!isUser }); }
		return msg;
	}

	/* Typing dots, then the reply — the pause makes replies read as
	 * "answers" rather than instant UI swaps. */
	function reply(fn, ms) {
		var dots = document.createElement('div');
		dots.className = 'kg-helper__msg kg-helper__typing';
		dots.setAttribute('aria-hidden', 'true');
		dots.innerHTML = '<span></span><span></span><span></span>';
		bodyEl.appendChild(dots);
		bodyEl.scrollTop = bodyEl.scrollHeight;
		setTimeout(function () {
			dots.remove();
			fn();
		}, ms || 520);
	}

	/* Insert a query param before any #hash. */
	function withParam(url, key, value) {
		if (!value) { return url; }
		var hashAt = url.indexOf('#');
		var base = hashAt > -1 ? url.slice(0, hashAt) : url;
		var hash = hashAt > -1 ? url.slice(hashAt) : '';
		return base + (base.indexOf('?') > -1 ? '&' : '?') + key + '=' + encodeURIComponent(value) + hash;
	}

	/* Append ?kg_topic=<root topic id> so the support form pre-selects it. */
	function withTopic(url, rootId) {
		return withParam(url, 'kg_topic', rootId);
	}

	/* The most recent free-text question, carried into escalation links as
	 * ?kg_q= so the support form's message box starts pre-filled with it. */
	var lastQuery = '';

	/* Flat search index over the whole tree: plain lowercase substring
	 * matching, which also works for Thai/Chinese where spaces are rare.
	 * Nodes and FAQ items may carry an optional `kw` string of hidden,
	 * locale-specific synonyms ("cost price fee") that are indexed but
	 * never displayed. */
	var searchIndex = [];
	(function buildIndex(nodes, parents, rootId) {
		nodes.forEach(function (node) {
			var root = rootId || node.id;
			var childLabels = (node.children || []).map(function (c) { return c.label; }).join(' ');
			var answerText = (node.answer || '').replace(/<[^>]*>/g, ' ');
			searchIndex.push({
				node: node,
				siblings: nodes,
				parents: parents,
				rootId: root,
				labelLc: node.label.toLowerCase(),
				textLc: (node.label + ' ' + answerText + ' ' + childLabels + ' ' + (node.kw || '')).toLowerCase()
			});
			if (node.children) { buildIndex(node.children, parents.concat([nodes]), root); }
		});
	})(data.nodes, [], null);

	/* The support-page FAQ rides along in data.faq so free-text search finds
	 * every published answer — including ones without a button in the topic
	 * tree. Each acts as a leaf node; its category maps to the closest form
	 * topic so escalation links still pre-select something sensible. */
	var FAQ_TOPIC = { plans: 'pricing', product: 'using', account: 'using' };
	(data.faq || []).forEach(function (item, i) {
		var answerText = (item.a || '').replace(/<[^>]*>/g, ' ');
		searchIndex.push({
			node: { id: 'faq-' + i, label: item.q, answer: item.a, escalate: !!item.escalate },
			siblings: [],
			parents: [],
			rootId: FAQ_TOPIC[item.cat] || 'contact',
			labelLc: item.q.toLowerCase(),
			textLc: (item.q + ' ' + answerText + ' ' + (item.kw || '')).toLowerCase()
		});
	});

	function searchTree(query) {
		var terms = query.toLowerCase().split(/\s+/).filter(function (t) { return t.length >= 2; });
		if (!terms.length) { return []; }
		var seen = [];
		return searchIndex
			.map(function (entry) {
				var score = 0;
				terms.forEach(function (t) {
					// Weight by term length so "cancel" outranks "how"/"do"
					if (entry.labelLc.indexOf(t) !== -1) { score += 3 * t.length; }
					else if (entry.textLc.indexOf(t) !== -1) { score += t.length; }
				});
				return { entry: entry, score: score };
			})
			.filter(function (r) { return r.score > 0; })
			.sort(function (a, b) { return b.score - a.score; })
			// Tree nodes and FAQ items can hold the same answer ("How does the
			// 30-day trial work?" exists as both) — keep only the higher-ranked
			// of any near-identical pair.
			.filter(function (r) {
				var key = r.entry.labelLc.replace(/[^0-9a-z-￿]+/g, ' ').trim().slice(0, 18);
				if (seen.indexOf(key) !== -1) { return false; }
				seen.push(key);
				return true;
			})
			.slice(0, 3)
			.map(function (r) { return r.entry; });
	}

	/* Shared "the user picked this node" path, used by level options,
	 * related-question buttons and free-text search results. */
	function selectNode(node, siblings, parents, rootId, wrap) {
		if (wrap) { wrap.remove(); }
		addMessage(node.label, true);
		track('support_helper_topic_select', { topic: node.id });
		var root = rootId || node.id;
		if (node.children && node.children.length) {
			reply(function () { showLevel(node.children, parents.concat([siblings]), root); });
		} else {
			reply(function () { showAnswer(node, root, siblings); });
		}
	}

	/* Render one level of options. `parents` is the stack of ancestor
	 * node-lists (powers Back); `rootId` is the top-level topic this level
	 * belongs to, carried into escalation links. */
	function showLevel(nodes, parents, rootId) {
		var wrap = document.createElement('div');
		wrap.className = 'kg-helper__options';

		nodes.forEach(function (node) {
			var btn = document.createElement('button');
			btn.type = 'button';
			btn.className = 'kg-helper__opt';
			btn.textContent = node.label;
			btn.addEventListener('click', function () {
				selectNode(node, nodes, parents, rootId, wrap);
			});
			wrap.appendChild(btn);
		});

		if (parents.length) {
			var back = document.createElement('button');
			back.type = 'button';
			back.className = 'kg-helper__back';
			back.textContent = data.back;
			back.addEventListener('click', function () {
				wrap.remove();
				var upParents = parents.slice(0, -1);
				showLevel(parents[parents.length - 1], upParents, upParents.length ? rootId : null);
			});
			wrap.appendChild(back);
		}

		bodyEl.appendChild(wrap);
		bodyEl.scrollTop = bodyEl.scrollHeight;
	}

	function restart() {
		addMessage(data.restart);
		showLevel(data.nodes, [], null);
	}

	/* Return to the very first set of questions. */
	function goToStart() {
		addMessage(data.greeting);
		showLevel(data.nodes, [], null);
	}

	/* A "go to support" button paired with a Back button on its left that
	 * returns to the start questions. Shown after a "Not really" answer and on
	 * escalation leaves like "Contact the support team". */
	function addSupportRow(url, label, topic) {
		var row = document.createElement('div');
		row.className = 'kg-helper__actions';

		var back = document.createElement('button');
		back.type = 'button';
		back.className = 'kg-helper__back';
		back.textContent = data.back;
		back.addEventListener('click', function () {
			row.remove();
			goToStart();
		});

		var cta = document.createElement('a');
		cta.className = 'kg-helper__cta';
		cta.href = withParam(url, 'kg_q', lastQuery);
		cta.textContent = label;
		cta.addEventListener('click', function () { track('support_helper_escalate', { topic: topic }); });

		row.appendChild(back);
		row.appendChild(cta);
		bodyEl.appendChild(row);
		bodyEl.scrollTop = bodyEl.scrollHeight;
	}

	/* Leaf reached. Escalation leaves (e.g. "Contact the support team") show a
	 * support button with a Back-to-start button on its left; every other leaf
	 * prints the answer, offers up to two related sibling questions, then asks
	 * "Was this helpful?". */
	function showAnswer(node, rootId, siblings) {
		addMessage(node.answer);

		if (node.escalate) {
			addSupportRow(withTopic(data.support_form_url, rootId || node.id), data.form_cta, node.id);
			return;
		}

		var wrap = document.createElement('div');
		wrap.className = 'kg-helper__options kg-helper__helpful';

		var related = (siblings || []).filter(function (s) { return s !== node; }).slice(0, 2);
		if (related.length) {
			var relLabel = document.createElement('p');
			relLabel.className = 'kg-helper__helpful-q';
			relLabel.textContent = data.related_q;
			wrap.appendChild(relLabel);
			related.forEach(function (sib) {
				var btn = document.createElement('button');
				btn.type = 'button';
				btn.className = 'kg-helper__opt';
				btn.textContent = sib.label;
				btn.addEventListener('click', function () {
					selectNode(sib, siblings, [], rootId, wrap);
				});
				wrap.appendChild(btn);
			});
		}

		var prompt = document.createElement('p');
		prompt.className = 'kg-helper__helpful-q';
		prompt.textContent = data.helpful_q;
		wrap.appendChild(prompt);

		var row = document.createElement('div');
		row.className = 'kg-helper__helpful-row';

		var yes = document.createElement('button');
		yes.type = 'button';
		yes.className = 'kg-helper__opt kg-helper__opt--yes';
		yes.textContent = data.helpful_yes;
		yes.addEventListener('click', function () {
			wrap.remove();
			addMessage(data.helpful_yes, true);
			track('support_helper_helpful', { helpful: true, topic: node.id });
			reply(restart);
		});

		var no = document.createElement('button');
		no.type = 'button';
		no.className = 'kg-helper__opt kg-helper__opt--no';
		no.textContent = data.helpful_no;
		no.addEventListener('click', function () {
			wrap.remove();
			addMessage(data.helpful_no, true);
			track('support_helper_helpful', { helpful: false, topic: node.id });
			reply(function () {
				addMessage(data.no_help);
				addSupportRow(withTopic(data.support_url, rootId || node.id), data.no_help_cta, node.id);
			});
		});

		row.appendChild(yes);
		row.appendChild(no);
		wrap.appendChild(row);
		bodyEl.appendChild(wrap);
		bodyEl.scrollTop = bodyEl.scrollHeight;
	}

	/* Free-text questions: match against the tree, offer the best nodes. */
	if (inputForm && inputEl) {
		inputForm.addEventListener('submit', function (e) {
			e.preventDefault();
			var q = inputEl.value.trim();
			if (!q) { return; }
			inputEl.value = '';
			lastQuery = q;
			addMessage(escapeHtml(q), true);
			var results = searchTree(q);
			track('support_helper_search', { query_length: q.length, matches: results.length });
			reply(function () {
				if (!results.length) {
					addMessage(data.search_none);
					showLevel(data.nodes, [], null);
					return;
				}
				addMessage(data.search_intro);
				var wrap = document.createElement('div');
				wrap.className = 'kg-helper__options';
				results.forEach(function (entry) {
					var btn = document.createElement('button');
					btn.type = 'button';
					btn.className = 'kg-helper__opt';
					btn.textContent = entry.node.label;
					btn.addEventListener('click', function () {
						selectNode(entry.node, entry.siblings, entry.parents, entry.rootId, wrap);
					});
					wrap.appendChild(btn);
				});
				// Escape hatch when none of the results fit: hand off to the
				// form with the question carried along (kg_q pre-fills it).
				var esc = document.createElement('a');
				esc.className = 'kg-helper__opt kg-helper__opt--escalate';
				esc.href = withParam(withTopic(data.support_form_url, 'contact'), 'kg_q', q);
				esc.textContent = data.search_escalate;
				esc.addEventListener('click', function () {
					track('support_helper_escalate', { topic: 'search' });
				});
				wrap.appendChild(esc);
				bodyEl.appendChild(wrap);
				bodyEl.scrollTop = bodyEl.scrollHeight;
			});
		});
	}

	var started = false;
	var savedLog = readLog();

	function openHelper(silent) {
		helper.classList.add('is-open');
		fab.setAttribute('aria-expanded', 'true');
		try { sessionStorage.setItem(OPEN_KEY, '1'); } catch (e) { /* noop */ }
		if (!silent) { track('support_helper_open'); }
		if (!started) {
			started = true;
			if (savedLog.length) {
				// Returning mid-conversation: replay the transcript, then
				// offer the top-level topics again.
				savedLog.forEach(function (m) { addMessage(m.h, m.u, true); });
			} else {
				addMessage(data.greeting);
			}
			showLevel(data.nodes, [], null);
		}
		if (!silent) { (inputEl || closeBtn).focus(); }
	}
	function closeHelper() {
		helper.classList.remove('is-open');
		fab.setAttribute('aria-expanded', 'false');
		try { sessionStorage.setItem(OPEN_KEY, '0'); } catch (e) { /* noop */ }
		fab.focus();
	}

	fab.addEventListener('click', function () {
		helper.classList.contains('is-open') ? closeHelper() : openHelper();
	});
	closeBtn.addEventListener('click', closeHelper);
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && helper.classList.contains('is-open')) { closeHelper(); }
	});

	// Navigating with the panel open: reopen quietly with the transcript.
	if (savedLog.length) {
		try {
			if (sessionStorage.getItem(OPEN_KEY) === '1') { openHelper(true); }
		} catch (e) { /* noop */ }
	}
})();
