# Kids Gate — WordPress Theme (v1.3.0)

A complete, install-ready WordPress theme for the Kids Gate marketing site. Full visual
redesign built around **"the Gate"** design language: arch-topped shapes mark the child's
world, straight rounded cards mark the parent's world. Trilingual (EN / ID / TH),
scroll-driven, conversion-first, accessible.

---

## 1. Install (turnkey)

1. Zip the `kidsgate-theme` folder (see §7 for what to exclude) or use the provided zip.
2. WP admin: **Appearance → Themes → Add New Theme → Upload Theme** → choose the zip →
   **Install** → **Activate**.
3. Done. On activation the theme automatically:
   - creates all pages (Home, How It Works, Features, For Parents, Pricing,
     For Schools & Teachers, Leaderboard, About Us, Download the App, Support),
   - sets **Home** as the static front page,
   - enables pretty permalinks so `/pricing/`, `/support/` etc. resolve.

No plugins are required.

## 2. Page → template map

| Page (slug) | Template | Notes |
|---|---|---|
| Home (front page) | `front-page.php` | Full scroll story: hero gate → stats → problem/solution → loop → experience tabs → AI demo → dashboard → rewards → testimonials → pricing → final CTA |
| `/how-it-works/` | `page-how-it-works.php` | 7 expandable journey steps, **demo video**, 20-min session timeline |
| `/features/` | `page-features.php` | AI + dashboard spotlight rows, 8-card grid |
| `/parents/` | `page-parents.php` | Tappable dashboard tour, family profiles, safety, parent FAQ |
| `/pricing/` | `page-pricing.php` | Billing toggle, plan cards, **per-child family plan builder** (up to 4 children, mixed subjects), disclaimer, FAQ |
| `/schools/` | `page-schools.php` | Curriculum alignment, use cases, teacher dashboard, enquiry form |
| `/leaderboard/` | `page-leaderboard.php` | Sample rankings + filters, safety rules, prize draws |
| `/about/` | `page-about.php` | Mission, story, values, global scope, contact |
| `/download/` | `page-download.php` | Store badges, QR placeholder, screenshot carousel, 3 steps |
| `/support/` | `page-support.php` | Searchable/categorised FAQ, contact form, **rule-based guided helper** |
| 404 | `404.php` | Gate-arch 404, Return Home + **Visit Support** CTAs |

## 3. Languages (EN / ID / TH)

All copy lives in `inc/lang/en.php`, `inc/lang/id.php`, `inc/lang/th.php` — cultural
adaptations, not literal translations. Templates read strings via `kg_t( 'dot.path' )`.

- Resolution: `?lang=` parameter → cookie → browser locale → English.
- Pricing shows **USD globally, IDR on Indonesian, THB on Thai** (rates in `inc/config.php`).
- Thai pages automatically switch to Thai-native typefaces (Mitr display / Anuphan body).
- For production `/en` `/id` `/th` URL paths, pair with **Polylang** or **WPML** — all
  strings are already externalised, so migration is mechanical.
- `tests/check-lang-keys.php` verifies the three files stay structurally identical.

## 4. Pricing engine

Numeric rates live in `inc/config.php` (`kg_pricing_rates()`). The plan builder
(`assets/js/pricing.js`) implements the family billing rule: **the child with the most
subjects pays the standard first-child rate; every other child pays the additional-child
rate for their own subject count** — for monthly and annual billing alike. Plan selection
currently routes to `/support/` (no checkout yet).

## 5. Customizer settings

**Appearance → Customize → Kids Gate Settings**

| Setting | Purpose |
|---|---|
| Google Tag Manager ID | Activates GTM + the GA4 event plan |
| Demo video URL | Media Library URL for the lesson demo video |
| App Store / Play Store URLs | Wired to every store badge |
| Support team email | Replaces the placeholder address everywhere |
| Stat: learners / questions | Homepage counter placeholders |

## 6. Analytics events

`main.js` pushes to `window.dataLayer` (GTM-ready): `hero_cta_click`,
`free_trial_start`, `pricing_page_view`, `app_store_click`, `play_store_click`,
`language_switch`, `how_it_works_scroll`, `video_play`, `leaderboard_view`,
`testimonial_view`, `faq_expand`, `scroll_depth_50`, `scroll_depth_90`,
`school_enquiry_submit`, `support_page_view`, `support_form_submit`,
`support_email_click`, `support_helper_open`, `support_helper_topic_select`,
`support_faq_expand`, `404_support_click`.

## 7. Packaging notes

- **`assets/video/gate-demo.mp4` is ~210 MB** — exclude it from the theme zip (most WP
  hosts cap uploads well below that). Upload the video to the Media Library instead and
  set its URL in the Customizer; the How It Works page picks it up automatically.
- **`tests/`** is a dev-only preview harness (`php -S localhost:8123 tests/preview.php`)
  plus the language-key checker. Exclude it from production zips.

## 8. Integration placeholders (documented, not faked)

- **Support / school / sponsor enquiry forms**: delivered by email via the
  `kg/v1/enquiry` REST endpoint (`functions.php`), which sends to the configured
  support address through `wp_mail()` with honeypot + rate-limit protection. If the
  endpoint is unreachable (or in the `tests/preview.php` harness, which has no REST
  API), `assets/js/support.js` falls back to opening a pre-filled `mailto:` draft.
  On production, install an SMTP plugin (e.g. WP Mail SMTP) — bare `wp_mail()`
  relies on PHP `mail()`, which many hosts don't deliver reliably.
- **Support email**: `support@kidsgate.example` placeholder in `inc/config.php`; set the
  real address in the Customizer (templates label it as a placeholder until then).
- **Store links / QR code**: `#` placeholders until the listings exist.
- **Testimonials** (parents + teacher): clearly flagged placeholder cards.
- **Curriculum overview PDF**: button is a labelled placeholder.
- **Leaderboard data**: sample rows, labelled as such on the page.
- **Privacy / Terms**: footer placeholders awaiting real pages.

## 9. Accessibility & motion

Skip link, semantic headings, labelled forms, ARIA-correct tabs/accordions/menus,
44px+ touch targets, visible focus rings, keyboard-operable everything, and a global
`prefers-reduced-motion` kill-switch for reveals, counters, bobbing and smooth scroll.
