# PLAN: Bring Chinese (zh) into the language-parity test, and truth-up README.md

## Goal

Two safety nets have drifted behind reality:

1. **`kidsgate-theme/tests/check-lang-keys.php` only verifies en/id/th.** `zh.php`
   was added later (commit b3a7af6 "…and Chinese locale") and is completely
   unchecked — a missing zh key renders a raw key path like `footer.cookie_policy`
   on the live Chinese site and nothing catches it. Every other plan in this batch
   edits all four lang files, so this checker is the regression net for all of them.
2. **`kidsgate-theme/README.md` describes a site that no longer exists**: it says
   trilingual EN/ID/TH (there are four languages and ten market/currency variants),
   lists `/leaderboard/` and `page-leaderboard.php` (deleted), lists `/download/`
   and `page-download.php` (no such template in the tree), omits the Rewards,
   Sponsors and Payment pages that DO exist, and claims the support email is a
   placeholder (`kg_support_email_is_live()` in `inc/config.php:27` now returns
   `true` — support@thekidsgate.com is confirmed).

## Exact files to touch

| File | Change |
|---|---|
| `kidsgate-theme/tests/check-lang-keys.php` | Add `'zh'` to both loops; fix anything it then reports |
| `kidsgate-theme/inc/lang/zh.php` (possibly id/th too) | Whatever key mismatches the checker reveals |
| `kidsgate-theme/README.md` | Truth-up pass (details below) |

## Step-by-step

1. In `check-lang-keys.php`: line 27 `array( 'en', 'id', 'th' )` →
   `array( 'en', 'id', 'th', 'zh' )`; line 33 `array( 'id', 'th' )` →
   `array( 'id', 'th', 'zh' )`. Update the final OK message text ("across
   en/id/th/zh").
2. Run `php kidsgate-theme/tests/check-lang-keys.php`. **Expect failures** — zh has
   never been checked. For each reported mismatch:
   - `MISSING` in zh → translate the en string into natural Chinese (the site
     prefers cultural adaptation over literal translation; match the register of
     surrounding zh strings) and insert it at the **same array position** as in
     en.php.
   - `EXTRA` in zh → check git history / en.php first; an "extra" key usually means
     the key was renamed in en but not in zh — rename rather than delete, so the
     translation isn't lost.
   Re-run until it prints OK.
3. **README.md truth-up**, section by section:
   - Title/intro: "Trilingual (EN / ID / TH)" → four languages (EN / ID / TH / ZH);
     mention the ten-market pricing (`au us nz sg id th in ph kh vn` — the list in
     `functions.php:309` and `inc/config.php` `kg_pricing_rates()`).
   - §1 install bullets: the auto-created page list must match `kg_create_pages()`
     in `functions.php:659–672` exactly (home, how-it-works, features, parents,
     pricing, schools, rewards, about, sponsors, support, payment). Remove
     Leaderboard and Download the App.
   - §2 template map: regenerate from the actual files —
     `ls kidsgate-theme/page-*.php front-page.php 404.php`. Remove the
     `/leaderboard/` and `/download/` rows; add `/rewards/` (`page-rewards.php`),
     `/sponsors/` (`page-sponsors.php`), `/payment/` (`page-payment.php`,
     placeholder shown after plan selection).
   - §3 languages: add zh; note market/language URL routing (`/au/en/pricing/`
     style, mu-plugin `mu-plugins/kg-routing.php` optional, rewrite-rule fallback
     in functions.php) — the current text about "?lang= → cookie → browser" is the
     bare-domain fallback only, and the cookie is now `kg_choice` (market:lang),
     not just `kg_lang`.
   - §3 last bullet + §7: "the three files" → four; checker text update.
   - §5/§8: support email — real address confirmed; remove
     "support@kidsgate.example" wording (grep README for `kidsgate.example`).
   - §6 analytics list: reconcile against reality —
     `grep -o "track('[a-z_0-9]*'" kidsgate-theme/assets/js/*.js | sort -u` — remove
     `leaderboard_view` if gone, add anything new (e.g. market_switch,
     consent_choice if PLAN-cookie-consent-banner.md has run).
   - §8 placeholders: privacy/terms are no longer "placeholders awaiting real
     pages" — four real PDFs are linked in the footer (see
     PLAN-fix-legal-doc-links.md); forms bullet: match the current state (or the
     post-PLAN-form-email-backend state if it ran first).
4. Run the checker one final time + `php -l` on every lang file you touched.

## Edge cases found while exploring (a weaker model would miss these)

- **The checker compares numeric lists by LENGTH ONLY** (line 13–14: a sequential
  array flattens to `path[count]`). So zh can "pass" while a list item inside is
  structurally wrong (e.g. a FAQ item missing its `cat` key, or
  `support.form.topics` order scrambled — those options are index-aligned with
  `TOPIC_INDEX` in `assets/js/support.js` per FAQ-INBOX.md step 4). After the
  checker passes, spot-check zh's `support.form.topics` order against en's, and
  spot-check one `support.faq_items` entry for the same sub-keys.
- **Do not "fix" a mismatch by deleting the en key.** en.php is the source of
  truth; the fix always lands in the lagging file.
- **README §2 mentions the demo video on How It Works** — that's still true but the
  video is a Customizer-driven placeholder (`page-how-it-works.php:69–71`); keep
  the packaging note in §7 about the 210 MB file (it's also in `.gitignore`).
- **Don't invent zh translations for brand terms**: existing zh copy uses
  "The Kids Gate" untranslated (check zh.php's hero strings before writing new
  copy) — stay consistent with whatever it does.
- **The preview harness runs lang files through `require`**, so a PHP syntax error
  in zh.php takes down every zh page silently in preview — always `php -l` after
  editing.
- If the checker reports a large mismatch count in zh (dozens of keys), STOP and
  report rather than machine-translating en masse — the owner may prefer a human
  pass; small gaps (< ~10 keys) are fine to translate directly.

## Acceptance criteria

1. `php kidsgate-theme/tests/check-lang-keys.php` prints
   `OK — <n> keys match across en/id/th/zh` and exits 0.
2. `php -l` passes on all four lang files.
3. `grep -in "leaderboard\|page-download\|kidsgate.example\|Trilingual (EN / ID / TH)" kidsgate-theme/README.md`
   returns nothing (except any deliberate historical note).
4. README §2 table rows correspond 1:1 with `page-*.php` files present in the theme
   plus front-page.php and 404.php.
5. README §6 event list matches the `track('…')` calls actually present in
   `assets/js/main.js` + `support.js` + `pricing.js`.
6. Preview: open one zh page (`?lang=zh` or `/sg/zh/`) — no raw `x.y.z` key paths
   visible anywhere on the Support page (the page with the most strings).
