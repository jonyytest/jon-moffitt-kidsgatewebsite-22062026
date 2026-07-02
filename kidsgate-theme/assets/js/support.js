/**
 * The Kids Gate — support page interactions.
 *
 *  1. FAQ live search + category filter.
 *  2. Contact form: client-side validation and a documented placeholder
 *     submission state (no backend/email service is connected yet).
 *  3. Guided support helper: a rule-based, chat-style widget. It is NOT
 *     presented as an AI assistant — it suggests topics, links FAQ answers
 *     and escalates to the form/email. Content comes from the
 *     KG_HELPER_DATA JSON blob printed by page-support.php / 404.php.
 */
(function () {
	'use strict';

	function track(name, params) {
		if (window.kgTrack) { window.kgTrack(name, params); }
	}

	/* ----------------------------------------------------------------
	 * FAQ search + category filter
	 * -------------------------------------------------------------- */
	var search = document.querySelector('[data-kg-support-search]');
	var faqWrap = document.querySelector('[data-kg-faq-context="support"]');
	var emptyMsg = document.querySelector('.kg-faq__empty');
	var catButtons = document.querySelectorAll('[data-kg-support-cat]');
	var activeCat = 'all';

	function applyFilter() {
		if (!faqWrap) { return; }
		var q = search ? search.value.trim().toLowerCase() : '';
		var visible = 0;
		faqWrap.querySelectorAll('.kg-faq__item').forEach(function (item) {
			var text = item.getAttribute('data-kg-faq-text') || '';
			var cat = item.getAttribute('data-kg-faq-cat') || '';
			var matches = (!q || text.indexOf(q) !== -1) && (activeCat === 'all' || cat === activeCat);
			item.style.display = matches ? '' : 'none';
			if (matches) { visible++; }
		});
		if (emptyMsg) { emptyMsg.style.display = visible === 0 ? 'block' : 'none'; }
	}

	if (search) { search.addEventListener('input', applyFilter); }
	catButtons.forEach(function (btn) {
		btn.addEventListener('click', function () {
			var cat = btn.getAttribute('data-kg-support-cat');
			activeCat = activeCat === cat ? 'all' : cat;
			catButtons.forEach(function (b) {
				b.setAttribute('aria-pressed', b.getAttribute('data-kg-support-cat') === activeCat ? 'true' : 'false');
			});
			applyFilter();
		});
	});

	/* ----------------------------------------------------------------
	 * Support / Schools / Sponsors forms.
	 *
	 * On submit: validates required fields, builds a mailto: URL using the
	 * form's data-kg-form-subject attribute as the email subject, collects
	 * all named inputs into the body, then opens the user's email client.
	 * Shows the success state after opening the mailto.
	 *
	 * Replace the mailto dispatch with a fetch() to a real backend endpoint
	 * when one is available — the subject attribute and field collection
	 * logic can stay as-is.
	 * -------------------------------------------------------------- */
	var form = document.querySelector('[data-kg-support-form]');
	if (form) {
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var valid = true;
			form.querySelectorAll('[required]').forEach(function (field) {
				var wrap = field.closest('.kg-field');
				var ok = field.checkValidity();
				if (wrap) { wrap.classList.toggle('kg-field--error', !ok); }
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

			// Build mailto with distinct subject per form and all field values in body
			var subject = form.getAttribute('data-kg-form-subject') || 'The Kids Gate Enquiry';
			var lines = [];
			form.querySelectorAll('[name]').forEach(function (field) {
				if (!field.name || field.type === 'submit') { return; }
				var label = field.closest('.kg-field') && field.closest('.kg-field').querySelector('label');
				var key = label ? label.textContent.trim().replace(/:$/, '') : field.name;
				if (field.value.trim()) { lines.push(key + ': ' + field.value.trim()); }
			});
			var to = (typeof KG_DATA !== 'undefined' && KG_DATA.support_email) ? KG_DATA.support_email : 'support@kidsgate.ai';
			var mailto = 'mailto:' + to + '?subject=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(lines.join('\n'));
			window.location.href = mailto;

			form.hidden = true;
			var success = document.querySelector('[data-kg-support-form-success]');
			if (success) {
				success.hidden = false;
				success.focus();
			}
		});
	}

	/* ----------------------------------------------------------------
	 * Guided support helper — rule-based decision tree.
	 *
	 * The data is a recursive tree of nodes. A node is either a BRANCH
	 * (has `children`: more options) or a LEAF (has `answer`). Picking a
	 * branch drills down a level (with a Back button); picking a leaf
	 * prints its answer then asks "Was this helpful?" — Yes restarts at
	 * the top, No hands off to the support page.
	 * -------------------------------------------------------------- */
	var fab = document.querySelector('[data-kg-helper-fab]');
	var helper = document.querySelector('[data-kg-helper]');
	var dataEl = document.getElementById('kg-helper-data');
	if (!fab || !helper || !dataEl) { return; }

	var data = JSON.parse(dataEl.textContent);
	var bodyEl = helper.querySelector('.kg-helper__body');
	var closeBtn = helper.querySelector('.kg-helper__close');

	function addMessage(html, isUser) {
		var msg = document.createElement('div');
		msg.className = 'kg-helper__msg' + (isUser ? ' kg-helper__msg--user' : '');
		msg.innerHTML = html;
		bodyEl.appendChild(msg);
		bodyEl.scrollTop = bodyEl.scrollHeight;
		return msg;
	}

	/* Render one level of options. `parents` is the stack of ancestor
	 * node-lists, used to power the Back button. */
	function showLevel(nodes, parents) {
		var wrap = document.createElement('div');
		wrap.className = 'kg-helper__options';

		nodes.forEach(function (node) {
			var btn = document.createElement('button');
			btn.type = 'button';
			btn.className = 'kg-helper__opt';
			btn.textContent = node.label;
			btn.addEventListener('click', function () {
				wrap.remove();
				addMessage(node.label, true);
				track('support_helper_topic_select', { topic: node.id });
				if (node.children && node.children.length) {
					setTimeout(function () {
						showLevel(node.children, parents.concat([nodes]));
					}, 200);
				} else {
					setTimeout(function () { showAnswer(node); }, 220);
				}
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
				showLevel(parents[parents.length - 1], parents.slice(0, -1));
			});
			wrap.appendChild(back);
		}

		bodyEl.appendChild(wrap);
		bodyEl.scrollTop = bodyEl.scrollHeight;
	}

	function restart() {
		addMessage(data.restart);
		showLevel(data.nodes, []);
	}

	/* Return to the very first set of questions. */
	function goToStart() {
		addMessage(data.greeting);
		showLevel(data.nodes, []);
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
		cta.href = url;
		cta.textContent = label;
		cta.addEventListener('click', function () { track('support_helper_escalate', { topic: topic }); });

		row.appendChild(back);
		row.appendChild(cta);
		bodyEl.appendChild(row);
		bodyEl.scrollTop = bodyEl.scrollHeight;
	}

	/* Leaf reached. Escalation leaves (e.g. "Contact the support team") show a
	 * support button with a Back-to-start button on its left; every other leaf
	 * prints the answer then asks "Was this helpful?". */
	function showAnswer(node) {
		addMessage(node.answer);

		if (node.escalate) {
			addSupportRow(data.support_form_url, data.form_cta, node.id);
			return;
		}

		var wrap = document.createElement('div');
		wrap.className = 'kg-helper__options kg-helper__helpful';

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
			setTimeout(restart, 220);
		});

		var no = document.createElement('button');
		no.type = 'button';
		no.className = 'kg-helper__opt kg-helper__opt--no';
		no.textContent = data.helpful_no;
		no.addEventListener('click', function () {
			wrap.remove();
			addMessage(data.helpful_no, true);
			track('support_helper_helpful', { helpful: false, topic: node.id });
			setTimeout(function () {
				addMessage(data.no_help);
				addSupportRow(data.support_url, data.no_help_cta, node.id);
			}, 220);
		});

		row.appendChild(yes);
		row.appendChild(no);
		wrap.appendChild(row);
		bodyEl.appendChild(wrap);
		bodyEl.scrollTop = bodyEl.scrollHeight;
	}

	var started = false;
	function openHelper() {
		helper.classList.add('is-open');
		fab.setAttribute('aria-expanded', 'true');
		track('support_helper_open');
		if (!started) {
			started = true;
			addMessage(data.greeting);
			showLevel(data.nodes, []);
		}
		closeBtn.focus();
	}
	function closeHelper() {
		helper.classList.remove('is-open');
		fab.setAttribute('aria-expanded', 'false');
		fab.focus();
	}

	fab.addEventListener('click', function () {
		helper.classList.contains('is-open') ? closeHelper() : openHelper();
	});
	closeBtn.addEventListener('click', closeHelper);
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && helper.classList.contains('is-open')) { closeHelper(); }
	});
})();
