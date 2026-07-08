# PLAN: Cookie-consent banner that gates GTM/GA4

## Goal

Implement the consent banner the legal review calls a blocking implementation task
(LEGAL-REVIEW-NOTES.md §B3 and §F item 7 — "Implement the cookie-consent banner
(Thailand driver) before enabling GA4"). Today `kg_gtm_head()` in
`kidsgate-theme/functions.php:564–574` injects GTM unconditionally whenever the
Customizer GTM ID is set. After this task:

- GTM/GA4 loads **only after** the visitor accepts analytics cookies.
- Declining (or ignoring) the banner means GTM never loads; the site works normally.
- The choice persists in a first-party cookie and the banner never reappears once
  answered.
- Accepting loads GTM immediately in-page (no reload needed).

## Exact files to touch

| File | Change |
|---|---|
| `kidsgate-theme/functions.php` | Gate `kg_gtm_head()` on the consent cookie; emit the GTM loader as a JS function `window.kgLoadGtm()` instead of running inline; add a `kg_consent_banner()` renderer |
| `kidsgate-theme/footer.php` | Call `kg_consent_banner()` just before `kg_render_helper()` |
| `kidsgate-theme/assets/js/main.js` | New block: show/hide banner, set cookie, call `kgLoadGtm()` on accept |
| `kidsgate-theme/assets/css/main.css` | Banner styles |
| `kidsgate-theme/inc/lang/en.php`, `id.php`, `th.php`, `zh.php` | New `consent` string group (identical key tree in all four) |

## Design decisions (already made — do not re-litigate)

- **Cookie:** `kg_consent`, values `granted` | `denied`, `Max-Age` 180 days,
  `path=/; SameSite=Lax`, plus `Secure` when `location.protocol === 'https:'`.
  Match the exact flag pattern main.js already uses for `kg_choice`
  (search main.js for `kg_choice` — a July 2026 security pass standardized these
  flags; copy that pattern, don't invent a new one).
- **Banner scope:** shown in every market (simplest compliant approach; Thailand
  PDPA is the driver but one behaviour everywhere avoids geo-logic).
- **Essential cookies** (`kg_choice`, `kg_lang`, Cloudflare `__cf_bm`, helper
  sessionStorage) are exempt and set regardless — the banner copy must say the
  choice covers **analytics** cookies only. This matches COOKIE-POLICY.md's
  inventory, which was built from the actual code.
- **Two buttons only**: Accept and Decline, equal visual weight (no dark patterns —
  the site brief demands non-manipulative design). No "cookie settings" modal.
- **No consent-mode stub**: if GTM didn't load, don't fake `gtag('consent', ...)`.

## Step-by-step

1. **functions.php** — rewrite `kg_gtm_head()`:
   - Keep the early return when no GTM ID.
   - Always emit `window.dataLayer = window.dataLayer || [];`.
   - Wrap the existing GTM snippet body in `window.kgLoadGtm = function(){ ... }`
     (guard against double-invocation with a flag).
   - After defining it, emit:
     `if (document.cookie.indexOf('kg_consent=granted') !== -1) { window.kgLoadGtm(); }`
   - Server-side PHP cookie check is NOT enough by itself (page caches), so do the
     check in JS as above; do not read `$_COOKIE` for the gating decision.
2. **functions.php** — add `kg_consent_banner()`: a small fixed-position
   `<div class="kg-consent" role="region" aria-label="...banner heading..." hidden data-kg-consent>`
   containing heading, one short sentence, a link to the Cookie Policy PDF (reuse the
   footer's `kg_asset( 'pdf/cookie-policy.pdf' )` URL — see PLAN-fix-legal-doc-links.md;
   if that plan hasn't run, still use `kg_asset()`, never `/assets/...`), and two
   `<button type="button">` elements with `data-kg-consent-accept` /
   `data-kg-consent-decline`. All copy via `kg_t( 'consent.*' )`.
3. **footer.php** — render it before `<?php kg_render_helper(); ?>` so it's inside
   `<body>` on every page.
4. **Lang files** — add to all four, e.g. en:
   ```php
   'consent' => array(
       'heading' => 'Cookies at The Kids Gate',
       'body'    => 'We use a small number of analytics cookies to understand how the site is used. Essential cookies that remember your language and region are always on.',
       'accept'  => 'Accept analytics',
       'decline' => 'No thanks',
       'policy'  => 'Cookie Policy',
   ),
   ```
   Translate naturally for id/th/zh (the site prefers cultural adaptation). Place the
   group at the same position in every file (files are index-aligned by convention).
5. **main.js** — new IIFE-section near the `kg_choice` cookie code:
   - If no `kg_consent` cookie → remove `hidden` from `[data-kg-consent]`.
   - Accept → set cookie `granted`, hide banner, call `window.kgLoadGtm && window.kgLoadGtm()`.
   - Decline → set cookie `denied`, hide banner.
   - Push `track('consent_choice', { choice: 'granted'|'denied' })` — safe because
     `dataLayer` exists as an inert array even without GTM (main.js line 15), and if
     the user accepts, GTM reads the pre-existing dataLayer backlog on load.
6. **CSS** — `.kg-consent`: fixed, `left: 16px; bottom: 16px`, max-width ~380px,
   navy card on cream shadow, `z-index` BELOW the quick-help widget's launcher
   (inspect `.kg-helper` z-index in main.css first and go one lower) so the helper
   stays clickable; on ≤480px span full width minus margins. Include a
   `@media (prefers-reduced-motion: reduce)` override if you add an entrance
   animation.

## Edge cases found while exploring (a weaker model would miss these)

- **`main.js` already guarantees `window.dataLayer` exists** (line 15) and `track()`
  pushes unconditionally. Do NOT try to gate every `track()` call — pushing into a
  plain array that GTM never reads is inert and legally fine (no cookie is set, no
  request leaves the page). Only the GTM **loader** needs gating.
- **Double-load guard:** if the user accepts, `kgLoadGtm()` runs; on the next page
  load the cookie check runs it again — fine — but make the function idempotent
  (set `window.__kgGtmLoaded` flag) so accept-clicks can't inject the script twice.
- **The dev preview has no GTM ID configured**, so `kg_gtm_head()` outputs nothing
  there and `window.kgLoadGtm` is undefined — the accept handler must use the
  `window.kgLoadGtm && ...` guard or it throws on every accept in the preview.
- **The quick-help widget occupies the bottom-right corner** on every page
  (`kg_render_helper()`); the banner must sit bottom-LEFT so the two never overlap
  on mobile.
- **`kidsgate-theme/tests/check-lang-keys.php` currently only verifies en/id/th** —
  it will NOT catch a forgotten zh key (see PLAN-zh-parity-and-docs.md). Add the
  `consent` group to zh.php anyway and `php -l` all four files.
- **Skip-link and focus order:** the banner is the last element in `<body>`; do not
  autofocus it (it's a region, not a modal — no focus trap). Buttons must be
  reachable by Tab and have visible focus rings (site pattern: `:focus-visible`).
- **COOKIE-POLICY.md §8 says the site honours GPC "where a law requires."** Optional
  hardening (do only if trivial): if `navigator.globalPrivacyControl` is truthy,
  treat absence of a cookie as declined and don't show the banner. If you skip this,
  say so in the report.
- **Do not set the consent cookie from PHP** — the page may be served from cache to
  multiple users; the choice must be made client-side.

## Acceptance criteria

1. With no `kg_consent` cookie: banner visible on every page; **no request to
   `googletagmanager.com`** appears in the network log even with a GTM ID set.
2. Click Accept → cookie `kg_consent=granted` set with `SameSite=Lax` and 180-day
   expiry; GTM request fires within 1s (when a GTM ID is configured); banner hidden;
   banner absent after reload.
3. Click Decline → cookie `kg_consent=denied`; no GTM request now or after reload;
   banner absent after reload.
4. In the preview (no GTM ID): Accept/Decline produce no console errors.
5. `php tests/check-lang-keys.php` passes (en/id/th; zh checked manually or via
   PLAN-zh-parity-and-docs.md), and `php -l` passes on all five edited PHP files.
6. Keyboard: Tab reaches both buttons with visible focus; banner text readable at
   375px viewport width; helper launcher still clickable while banner is open.
