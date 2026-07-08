# PLAN: Wire the support / schools / sponsors forms to a real email backend

## Goal

Three lead-capture forms (Support contact, Schools enquiry, Sponsors enquiry)
currently validate, fire analytics, and show a success message that openly admits
"the email backend is not yet connected — this submission was recorded for
analytics only." Real leads — including school sales enquiries — are being dropped.

After this task, submissions are delivered by email via `wp_mail()` through a
WordPress REST endpoint, with spam protection, while the dev preview (which is NOT
WordPress) keeps working through the existing fallback path.

## Exact files to touch

| File | Change |
|---|---|
| `kidsgate-theme/functions.php` | Register REST route `kg/v1/enquiry`; handler with sanitisation, honeypot, rate-limit, `wp_mail()`; extend the existing `wp_localize_script( 'kg-support', 'KG_DATA', ... )` (line ~557) with `rest_url` and `nonce` |
| `kidsgate-theme/assets/js/support.js` | In the submit handler (~line 106–160): if `KG_DATA.rest_url` exists, `fetch()` the endpoint; on success show the success state; on network/4xx failure fall back to the current mailto path |
| `kidsgate-theme/inc/lang/en.php`, `id.php`, `th.php`, `zh.php` | Rewrite the three `success_text` strings that currently say the backend is not connected (en.php ~757 schools, ~1015 sponsors, ~1168 support; find the aligned strings in the other three files at the same key paths) |
| `kidsgate-theme/page-support.php`, `page-schools.php`, `page-sponsors.php` | Add a honeypot field to each form (see step 3) |
| `kidsgate-theme/README.md` | §8 "Integration placeholders": update the forms bullet |

## Step-by-step

1. **Find all three forms first.** `grep -rn "data-kg-support-form" kidsgate-theme/*.php`
   — all three forms share this attribute and `data-kg-form-subject` (which carries
   the email subject line). The single submit handler in `support.js` serves all of
   them. Do not write three handlers.
2. **REST endpoint** in functions.php:
   ```php
   add_action( 'rest_api_init', function () {
       register_rest_route( 'kg/v1', '/enquiry', array(
           'methods'             => 'POST',
           'callback'            => 'kg_handle_enquiry',
           'permission_callback' => '__return_true', // public form; abuse is handled below
       ) );
   } );
   ```
   `kg_handle_enquiry( WP_REST_Request $r )`:
   - Reject if honeypot field `kg_website` is non-empty → return 200 with
     `{ok:true}` (silent drop; don't teach bots).
   - Rate-limit: transient `kg_enq_` + md5 of client IP, max 5 per 10 minutes →
     429 on excess.
   - Sanitise: `sanitize_text_field` everything, `sanitize_email` the email,
     `sanitize_textarea_field` the message; require name, valid email, message,
     subject; cap message at 5000 chars.
   - `wp_mail( kg_support_email(), $subject, $body, array( 'Reply-To: ' . $name . ' <' . $email . '>' ) )`.
     Subject comes from the posted `subject` field but must be validated against a
     whitelist of the three known `data-kg-form-subject` values (grep them; hardcode
     the array) — never let the client pick an arbitrary subject.
   - Return `{ ok: (bool) $sent }`; if `wp_mail` returns false, return 500 so the
     client falls back to mailto.
3. **Honeypot**: in each of the three form templates add
   `<input type="text" name="kg_website" tabindex="-1" autocomplete="off" aria-hidden="true" class="kg-visually-hidden">`
   (the `kg-visually-hidden` utility class already exists — verify with grep, and
   note it hides visually while staying in the DOM, which is exactly right).
4. **support.js submit handler**: keep the existing validation and
   `track('support_form_submit', ...)` exactly as-is. Then:
   - If `window.KG_DATA && KG_DATA.rest_url`: POST JSON
     (`Content-Type: application/json`, header `X-WP-Nonce: KG_DATA.nonce`)
     with all named fields (the handler already iterates `form.elements` — reuse
     that loop's output). On `res.ok` → existing success state. On failure →
     existing mailto fallback, unchanged.
   - Else (preview / WP not present): current behaviour, untouched.
5. **KG_DATA**: extend the existing `wp_localize_script` call with
   `'rest_url' => esc_url_raw( rest_url( 'kg/v1/enquiry' ) )` and
   `'nonce' => wp_create_nonce( 'wp_rest' )`.
6. **Success copy**: rewrite the three `success_text` strings in all four locales to
   a plain "we've received your message and will reply to <email channel> within two
   working days" message with no backend disclaimer. Keep each string at the SAME
   array position in every locale file.
7. **README.md §8**: replace the forms bullet with a line documenting the REST
   endpoint and the preview fallback.

## Edge cases found while exploring (a weaker model would miss these)

- **`tests/preview.php` is not WordPress.** There is no REST API, no nonces, no
  `wp_mail` in the preview harness. The preview mirrors `wp_localize_script` output
  (see the comment at `tests/preview.php:143`) — check whether it emits `KG_DATA`;
  if it does, make sure it does NOT emit `rest_url` (so the JS feature-detect keeps
  the preview on the mailto path). The whole design hinges on `KG_DATA.rest_url`
  being the switch.
- **Nonce vs page caching:** `wp_create_nonce` output baked into a cached page goes
  stale after 12–24h and the REST call 403s. The JS failure path already falls back
  to mailto, so a stale nonce degrades gracefully instead of losing the lead. Do not
  "fix" this by removing the nonce; do make sure a 403 triggers the fallback, not an
  unhandled promise rejection.
- **Topic `<select>` values are index-aligned with `TOPIC_INDEX` in support.js**
  (documented in FAQ-INBOX.md step 4). The email body must contain the topic's
  human-readable label (`select.selectedOptions[0].textContent`), not its value/index.
- **`data-kg-form-subject` is already in the DOM** — e.g.
  `page-support.php:105`: `data-kg-form-subject="The Kids Gate: Support Request"`.
  The subject whitelist in PHP must match all three templates' values byte-for-byte;
  grep for `data-kg-form-subject` and copy them.
- **`wp_mail` deliverability**: on bare WordPress `wp_mail` uses PHP `mail()` which
  many hosts don't deliver. That's a hosting concern, not a code concern — but the
  completion report must say "an SMTP plugin (e.g. WP Mail SMTP) is recommended in
  production."
- **Don't break the existing success-state markup contract**: the success element is
  `[data-kg-support-form-success]` with `hidden` + `tabindex="-1"` (focus is moved
  there for screen readers). Reuse it; don't build a new success UI.
- **The three success strings differ per form** (schools mentions "schools team",
  sponsors mentions partnering). Rewrite each in place; don't collapse them into one
  shared string, because the lang-key checker compares key trees and templates
  reference the distinct paths.
- **Sponsors form exists too** — README §8 only mentions support + schools, but
  `page-sponsors.php` has a third form with the same attribute. Include it.

## Acceptance criteria

1. `php -l` passes on functions.php and all four lang files; `node --check` (or
   careful review) passes on support.js.
2. Preview: submitting the Support form with valid input still shows the success
   state and no console errors, and no fetch to `/wp-json/` is attempted (verify in
   the network log).
3. Code review confirms: honeypot short-circuits before `wp_mail`; rate limiter
   returns 429 on the 6th call in 10 min from one IP; subject not in the whitelist →
   400; invalid email → 400.
4. All four locales: the three success strings no longer contain any "not connected
   / recorded for analytics only" wording (grep for "analytics only", "belum
   terhubung", "not yet connected" returns nothing in lang files' success_text).
5. `php tests/check-lang-keys.php` still prints OK.
6. README §8 updated. Completion report notes the SMTP-plugin recommendation and
   that live email delivery was NOT tested end-to-end (no WP install in this repo).
