/**
 * Kids Gate — per-child plan builder.
 *
 * Rules (from the pricing brief):
 *  - Up to 4 children per family account.
 *  - Each child independently studies 1 subject (English OR Maths) or both.
 *  - The child with the MOST subjects is billed at the standard first-child
 *    rate; every other child is billed at the additional-child rate for
 *    their own subject count.
 *  - Monthly and annual billing follow the same rule; annual prices are
 *    per month, billed yearly.
 *
 * Rates and labels arrive via wp_localize_script as KG_PRICING.
 */
(function () {
	'use strict';

	var root = document.querySelector('[data-kg-builder]');
	if (!root || typeof KG_PRICING === 'undefined') { return; }

	var rates = KG_PRICING.rates;
	var strings = KG_PRICING.strings;
	var listEl = root.querySelector('[data-kg-builder-children]');
	var addBtn = root.querySelector('[data-kg-builder-add]');
	var rowsEl = root.querySelector('[data-kg-builder-rows]');
	var totalEl = root.querySelector('[data-kg-builder-total]');
	var periodEl = root.querySelector('[data-kg-builder-period]');
	var template = document.getElementById('kg-child-template');

	var MAX_CHILDREN = 4;
	// Initialise from the toggle's current state so we don't depend on the
	// order in which main.js broadcasts the initial billing mode.
	var pressedYearly = document.querySelector('[data-kg-billing-toggle] button[data-kg-billing="y"][aria-pressed="true"]');
	var billing = pressedYearly ? 'y' : 'm'; // 'm' | 'y'
	var children = []; // each: { subjects: { english: bool, maths: bool } }

	function formatPrice(value) {
		var num = value.toFixed(rates.decimals);
		if (rates.decimals > 0 && /\.0+$/.test(num)) { num = String(Math.round(value)); }
		var parts = num.split('.');
		parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, rates.thousands);
		num = parts.join('.');
		return rates.sym_after ? num + rates.symbol : rates.symbol + num;
	}

	function subjectCount(child) {
		return (child.subjects.english ? 1 : 0) + (child.subjects.maths ? 1 : 0);
	}

	function addChild() {
		if (children.length >= MAX_CHILDREN) { return; }
		children.push({ subjects: { english: true, maths: false } });
		render();
	}

	function removeChild(index) {
		children.splice(index, 1);
		render();
	}

	function toggleSubject(index, subject) {
		var child = children[index];
		var next = !child.subjects[subject];
		// A child always studies at least one subject.
		if (!next && subjectCount(child) === 1) { return; }
		child.subjects[subject] = next;
		render();
	}

	/**
	 * Billing maths: sort children by subject count (desc); the first pays
	 * the full first-child rate, the rest pay additional-child rates.
	 */
	function computeRows() {
		var order = children
			.map(function (child, i) { return { i: i, count: subjectCount(child) }; })
			.sort(function (a, b) { return b.count - a.count; });

		return order.map(function (entry, pos) {
			var rate = pos === 0 ? rates.first[entry.count] : rates.addl[entry.count];
			return {
				childIndex: entry.i,
				count: entry.count,
				isFull: pos === 0,
				price: rate[billing]
			};
		});
	}

	function render() {
		// --- child cards ---
		listEl.innerHTML = '';
		children.forEach(function (child, i) {
			var node = template.content.cloneNode(true);
			var card = node.querySelector('.kg-builder__child');
			card.querySelector('[data-kg-child-label]').textContent = strings.child + ' ' + (i + 1);

			var removeBtn = card.querySelector('[data-kg-child-remove]');
			if (children.length === 1) {
				removeBtn.remove();
			} else {
				removeBtn.addEventListener('click', function () { removeChild(i); });
			}

			['english', 'maths'].forEach(function (subject) {
				var btn = card.querySelector('[data-kg-subject="' + subject + '"]');
				btn.setAttribute('aria-pressed', child.subjects[subject] ? 'true' : 'false');
				btn.addEventListener('click', function () { toggleSubject(i, subject); });
			});

			listEl.appendChild(node);
		});

		addBtn.disabled = children.length >= MAX_CHILDREN;

		// --- summary ---
		var rows = computeRows();
		var total = 0;
		rowsEl.innerHTML = '';
		rows.forEach(function (row) {
			total += row.price;
			var rateLabel = row.isFull ? strings.fullRate : strings.addlRate;
			var subjectsLabel = row.count === 2 ? strings.twoSubjects : strings.oneSubject;

			var div = document.createElement('div');
			div.className = 'kg-builder__row';
			var label = document.createElement('span');
			var nameEl = document.createElement('strong');
			nameEl.textContent = strings.child + ' ' + (row.childIndex + 1);
			var subEl = document.createElement('small');
			subEl.textContent = subjectsLabel + ' · ' + rateLabel;
			label.appendChild(nameEl);
			label.appendChild(subEl);
			var price = document.createElement('span');
			price.className = 'kg-row-price';
			price.textContent = formatPrice(row.price);
			div.appendChild(label);
			div.appendChild(price);
			rowsEl.appendChild(div);

			// matching rate annotation inside the child's card
			var card = listEl.children[row.childIndex];
			var note = card && card.querySelector('[data-kg-child-rate]');
			if (note) {
				note.innerHTML = '<strong>' + formatPrice(row.price) + '</strong> · ' + rateLabel;
			}
		});

		totalEl.textContent = formatPrice(total);
		periodEl.textContent = billing === 'y' ? strings.billedYear : strings.perMonth;
	}

	addBtn.addEventListener('click', addChild);

	document.addEventListener('kg:billing', function (e) {
		billing = e.detail.mode === 'y' ? 'y' : 'm';
		render();
	});

	// Selecting the plan routes to the payment page, which currently explains
	// that checkout is not connected yet and points to Support.
	var selectBtn = root.querySelector('[data-kg-builder-select]');
	if (selectBtn) {
		selectBtn.addEventListener('click', function () {
			if (window.kgTrack) {
				window.kgTrack('free_trial_start', {
					children: children.length,
					billing: billing
				});
			}
		});
	}

	addChild(); // start with one child
})();
