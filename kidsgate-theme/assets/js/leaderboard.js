/**
 * Leaderboard filtering — demonstration only. Filters, re-ranks and re-sorts
 * the sample rows client-side so the page feels live. No network calls; the
 * real rankings come from the app API once connected.
 *
 * Markup contract (page-leaderboard.php):
 *   [data-kg-lb-filters][data-my-country]      filter bar
 *     select[data-kg-filter="scope|grade|subject|period"]  with option values
 *   [data-kg-lb][data-thousands][data-count-tpl][data-count-one]  rows wrapper
 *     [data-kg-lb-row][data-country][data-grade][data-subject][data-tokens][data-seed]
 *       [data-kg-lb-rank]      rank badge text
 *       [data-kg-lb-tokenval]  token number text
 *   [data-kg-lb-count]       live result count
 *   [data-kg-lb-reset]       clear-filters button
 *   [data-kg-lb-empty]       no-results message
 *   [data-kg-lb-pagination]  pagination controls (created dynamically)
 */
(function () {
	var PAGE_SIZE = 10;
	var currentPage = 0;

	var lb        = document.querySelector('[data-kg-lb]');
	var filtersEl = document.querySelector('[data-kg-lb-filters]');
	if (!lb || !filtersEl) { return; }

	var rows     = Array.prototype.slice.call(lb.querySelectorAll('[data-kg-lb-row]'));
	var selects  = Array.prototype.slice.call(filtersEl.querySelectorAll('[data-kg-filter]'));
	var countEl  = document.querySelector('[data-kg-lb-count]');
	var resetEl  = document.querySelector('[data-kg-lb-reset]');
	var emptyEl  = document.querySelector('[data-kg-lb-empty]');
	var pagEl    = document.querySelector('[data-kg-lb-pagination]');

	var myCountry = filtersEl.getAttribute('data-my-country') || '';
	var sep       = lb.getAttribute('data-thousands') || ',';
	var countTpl  = lb.getAttribute('data-count-tpl') || '{n}';
	var countOne  = lb.getAttribute('data-count-one') || '{n}';
	var rankClasses = ['kg-lb__row--gold', 'kg-lb__row--silver', 'kg-lb__row--bronze'];
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	var data = rows.map(function (r) {
		return {
			el:      r,
			country: r.getAttribute('data-country'),
			grade:   parseInt(r.getAttribute('data-grade'), 10),
			subject: r.getAttribute('data-subject'),
			base:    parseInt(r.getAttribute('data-tokens'), 10) || 0,
			seed:    parseInt(r.getAttribute('data-seed'), 10) || 0,
			tokenEl: r.querySelector('[data-kg-lb-tokenval]'),
			rankEl:  r.querySelector('[data-kg-lb-rank]')
		};
	});

	function fmt(n) {
		return String(Math.round(n)).replace(/\B(?=(\d{3})+(?!\d))/g, sep);
	}

	function periodValue(d, period) {
		if (period === 'week') { return d.base; }
		var jitter = 0.8 + ((d.seed * 41) % 100) / 100 * 0.6;
		return period === 'month' ? d.base * 4 * jitter : d.base * 11 * jitter;
	}

	function current() {
		var v = {};
		selects.forEach(function (s) { v[s.getAttribute('data-kg-filter')] = s.value; });
		return v;
	}

	function isDefault(v) {
		return v.scope === 'global' && v.grade === 'all' && v.subject === 'all' && v.period === 'week';
	}

	function matches(d, v) {
		if (v.scope === 'country' && d.country !== myCountry) { return false; }
		if (v.grade !== 'all' && d.grade !== parseInt(v.grade, 10)) { return false; }
		if (v.subject !== 'all' && d.subject !== v.subject && d.subject !== 'both') { return false; }
		return true;
	}

	function updatePagination(total, totalPages) {
		if (!pagEl) { return; }
		pagEl.innerHTML = '';
		if (totalPages <= 1) { return; }

		var prev = document.createElement('button');
		prev.type = 'button';
		prev.className = 'kg-lb__page-btn';
		prev.setAttribute('aria-label', 'Previous page');
		prev.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg> Prev';
		prev.disabled = currentPage === 0;
		prev.addEventListener('click', function () {
			currentPage--;
			render();
			lb.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
		});

		var info = document.createElement('span');
		info.className = 'kg-lb__page-info';
		info.textContent = (currentPage + 1) + ' of ' + totalPages;

		var next = document.createElement('button');
		next.type = 'button';
		next.className = 'kg-lb__page-btn';
		next.setAttribute('aria-label', 'Next page');
		next.innerHTML = 'Next <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
		next.disabled = currentPage >= totalPages - 1;
		next.addEventListener('click', function () {
			currentPage++;
			render();
			lb.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
		});

		pagEl.appendChild(prev);
		pagEl.appendChild(info);
		pagEl.appendChild(next);
	}

	function render() {
		var v = current();
		var period = v.period || 'week';

		var visible = data.filter(function (d) { return matches(d, v); });
		visible.forEach(function (d) { d.score = periodValue(d, period); });
		visible.sort(function (a, b) { return b.score - a.score; });

		var totalPages = Math.max(1, Math.ceil(visible.length / PAGE_SIZE));
		currentPage = Math.min(currentPage, totalPages - 1);

		var start = currentPage * PAGE_SIZE;
		var pageRows = visible.slice(start, start + PAGE_SIZE);

		pageRows.forEach(function (d, i) {
			var el = d.el;
			el.style.display = '';
			rankClasses.forEach(function (c) { el.classList.remove(c); });
			var globalRank = start + i;
			if (globalRank < 3) { el.classList.add(rankClasses[globalRank]); }
			if (d.rankEl)  { d.rankEl.textContent = globalRank === 0 ? '🏆' : '#' + (globalRank + 1); }
			if (d.tokenEl) { d.tokenEl.textContent = fmt(d.score); }
			lb.appendChild(el);
		});

		data.forEach(function (d) {
			if (pageRows.indexOf(d) === -1) { d.el.style.display = 'none'; }
		});

		if (!reduce) {
			pageRows.forEach(function (d, i) {
				var el = d.el;
				el.classList.remove('is-shuffle');
				void el.offsetWidth;
				el.style.transitionDelay = Math.min(i, 8) * 35 + 'ms';
				el.classList.add('is-shuffle');
			});
			window.setTimeout(function () {
				pageRows.forEach(function (d) {
					d.el.style.transitionDelay = '';
					d.el.classList.remove('is-shuffle');
				});
			}, 520);
		}

		selects.forEach(function (s) { s.classList.toggle('is-active', s.selectedIndex !== 0); });

		var n = visible.length;
		if (countEl)  { countEl.textContent = n === 1 ? countOne : countTpl.replace('{n}', n); }
		if (emptyEl)  { emptyEl.hidden = n > 0; }
		if (resetEl)  { resetEl.hidden = isDefault(v); }

		updatePagination(n, totalPages);
	}

	selects.forEach(function (s) {
		s.addEventListener('change', function () {
			currentPage = 0;
			render();
			if (window.kgTrack) {
				window.kgTrack('leaderboard_view', {
					filter: s.getAttribute('data-kg-filter'),
					value:  s.value
				});
			}
		});
	});

	if (resetEl) {
		resetEl.addEventListener('click', function () {
			selects.forEach(function (s) { s.selectedIndex = 0; });
			currentPage = 0;
			render();
		});
	}

	render();
})();
