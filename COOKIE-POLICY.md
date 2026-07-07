# The Kids Gate — Cookie Policy

**Last updated:** [INSERT EFFECTIVE DATE]

> **Note for review — delete before publishing.** This draft is for GATE Edutech
> Solutions Pty Ltd. Unlike most cookie policies, this one is written from an
> audit of the actual website code, so the cookie inventory below is real, not
> boilerplate. Items in `[SQUARE BRACKETS]` need confirmation; open decisions are
> listed at the end under **"Items still to confirm."** This policy pairs with the
> Privacy Policy (PRIVACY-POLICY.md) and Terms of Service (TERMS-OF-SERVICE.md).

---

## The short version

- Our website uses a **small number of cookies**: two that remember your language and region choice, and — only if analytics is enabled — Google Analytics cookies to help us understand how the site is used.
- We do **not** use advertising cookies, tracking pixels for marketing, or any cookies that build profiles about you or your child.
- The Kids Gate apps do not use browser cookies; they use equivalent on-device storage to keep you signed in and remember settings.
- You can control or delete cookies at any time through your browser settings.

---

## 1. What this policy covers

This Cookie Policy explains how **GATE Edutech Solutions Pty Ltd** ("The Kids Gate", "we", "us") uses cookies and similar technologies on **thekidsgate.com** (the "Site") and, where relevant, in the Kids Gate mobile apps. It should be read together with our **[Privacy Policy]** [LINK].

**What cookies are.** Cookies are small text files a website stores on your device to remember information between pages or visits. Similar technologies include browser storage (localStorage and sessionStorage), which store information on your device without sending it to a server automatically.

---

## 2. Cookies we set (first-party)

### kg_choice

- **Purpose:** remembers the region and language you selected (for example, "Australia : English") so the site shows the right pricing, currency and language on your next visit. Your saved choice overrides automatic region detection.
- **Type:** functional / preference.
- **Duration:** 12 months.
- **Set when:** you choose a region or language using the site's region or language selector.

### kg_lang

- **Purpose:** remembers an explicit language choice on the main domain so the site loads in your language.
- **Type:** functional / preference.
- **Duration:** 12 months.
- **Set when:** you switch language on the main (region-free) version of the site.

Neither of these cookies identifies you personally — they contain only a region/language code such as `au:en`.

---

## 3. Browser storage we use

- **localStorage — `kg_choice`:** a copy of your region/language choice, kept in your browser so pages can apply it instantly. Persists until you clear browser data.
- **sessionStorage — quick-help widget:** if you use the on-site quick-help widget, your conversation with it (your selected topics and its answers) and whether the widget is open are kept in sessionStorage so the conversation survives moving between pages. This is stored **only in your browser**, is **not sent to our servers**, and is deleted automatically when you close the tab.

---

## 4. Analytics cookies (Google Analytics)

If analytics is enabled on the Site, we use **Google Tag Manager** to load **Google Analytics 4 (GA4)**, which sets cookies such as:

- **_ga** — distinguishes visitors using a random identifier. Duration: up to 2 years.
- **_ga_<container-id>** — keeps track of the browsing session. Duration: up to 2 years.

We use analytics to understand how visitors use the marketing website (which pages are viewed, which buttons are clicked) so we can improve it. Analytics runs on the **marketing website only** — we do not run advertising or remarketing features, and we do not use analytics data to build advertising profiles.

Google's own privacy information is available at https://policies.google.com/technologies/cookies. You can also opt out of Google Analytics across all websites with Google's browser add-on at https://tools.google.com/dlpage/gaoptout.

[CONFIRM: GA4/GTM is only active once a GTM container ID is configured. If a cookie-consent banner is added (see checklist), analytics cookies should only load after consent in regions that require it.]

---

## 5. Infrastructure cookies

Parts of the Site are delivered through **Cloudflare**, which may set technical cookies (such as `__cf_bm`, typically lasting under an hour) that protect the Site against bots and abuse. These are strictly necessary for security and cannot be switched off. [CONFIRM Cloudflare configuration and which cookies it currently sets.]

---

## 6. What we do NOT use

The Site does **not** use:

- advertising or remarketing cookies;
- social-media tracking pixels;
- cross-site tracking or fingerprinting;
- cookies that profile children or any other users for marketing.

This matches our wider commitments in the Privacy Policy: The Kids Gate is ad-free and does not sell or share personal information.

---

## 7. Cookies in the apps

The Kids Gate iOS and Android apps do not use browser cookies. They use equivalent on-device storage to keep you signed in, remember settings, and cache learning content, and may include the SDKs described in the Privacy Policy [CONFIRM app SDK list — align with Privacy Policy Section 3.3].

---

## 8. How to control cookies

- **Browser settings:** every major browser lets you view, delete and block cookies (including just third-party cookies). Blocking our functional cookies won't break the Site, but it will forget your language and region between visits.
- **Analytics:** use the Google opt-out add-on linked above, or decline analytics via the cookie banner where one is shown.
- **Consent preferences:** where a cookie-consent banner is available in your region, you can change your preferences at any time via the banner's settings link. [CONFIRM once the consent banner is implemented.]
- **Global Privacy Control (GPC):** where required by law, we treat a GPC signal from your browser as an opt-out of any "sale" or "sharing" of personal information — noting that we do not sell or share personal information in any case. [CONFIRM technical handling of GPC signals.]

---

## 9. Changes to this policy

We may update this Cookie Policy as the Site changes — for example, if we add new features that need new cookies. We will post the updated version here with a new "Last updated" date. Significant changes will be flagged as described in the Privacy Policy.

---

## 10. Contact

Questions about cookies or this policy: **support@thekidsgate.com** (privacy matters: **privacy@thekidsgate.com**)

GATE Edutech Solutions Pty Ltd (ACN 652 998 635), 1 Oxley Road, Hawthorn VIC 3122, Australia

---

## Items still to confirm before publishing

*(Working checklist — remove this section before publishing.)*

1. **Effective date.**
2. **Cookie-consent banner** — the site currently has no consent banner. One is legally required before analytics cookies load for EU/UK visitors, and recommended for Thailand (PDPA). Termly provides one; it should block GA4 until consent where required. Decide and implement.
3. **GTM/GA4 status** — analytics only activates once a GTM ID is configured in WordPress (Appearance → Customize → The Kids Gate Settings). Confirm whether it will be enabled at launch, and verify the exact GA4 cookie names/durations once live.
4. **Cloudflare cookies** (Section 5) — confirm the bare-domain Cloudflare Worker setup and which cookies Cloudflare actually sets in production.
5. **App storage/SDKs** (Section 7) — align with the Privacy Policy Section 3.3 SDK list once the developer confirms it.
6. **GPC handling** (Section 8) — confirm whether the site technically detects GPC signals, or soften the wording to "we do not sell or share personal information, so no opt-out is needed."
7. **Legal review** alongside the Privacy Policy and Terms of Service.
