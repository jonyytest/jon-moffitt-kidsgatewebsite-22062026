# Checkout, Accounts, Referrals & Geolocation — Implementation Plan

**For:** GATE Edutech Solutions Pty Ltd · **Companion to:** `PAYMENTS-STRIPE-VS-SHOPIFY.md`
**Status:** proposal for CEO + development team

> **TL;DR.** Use **Stripe Checkout** (Stripe-hosted card entry) but present it as part of
> The Kids Gate — ideally **embedded on our own page** so the parent never feels handed
> off to a different, sketchier site. Carry the plan selection across in the URL, recompute
> every price server-side, and let a **webhook** unlock the child's access in the app.
> Add **geolocation** for smart regional defaults (keeping the manual region selector for
> the small VPN minority), and run referrals through **Stripe promotion codes + an
> affiliate add-on** rather than building payout logic ourselves.

---

## 1. Why the checkout must look and feel like The Kids Gate

This is not a cosmetic concern. It is the single biggest controllable factor in
conversion, and it is where a split-off, unstyled checkout does real damage.

**Who is actually paying.** Our buyer is a parent, typically **25–35**, who shops online
constantly — Amazon, Woolworths, Afterpay, app stores. They have a very well-calibrated
instinct for what a legitimate checkout looks like. They are not confused by online
payment; they are *fluent* in it, which means they notice immediately when something is
off.

**Australian expectations are high.** Australian consumers are used to a polished
standard and are protected by strong consumer law. A checkout that looks improvised
reads as either amateur or fraudulent. There is no third interpretation.

**The stakes are higher for a children's product.** The parent is entering card details
on behalf of their child, on a site about their child's education and safety. Any
"is this legit?" hesitation is fatal — they close the tab and do not come back.

**What breaks trust, concretely:**
- Being bounced to a different domain that looks nothing like the site they were just on.
- Different fonts, colours, logo treatment, or no logo at all.
- A payment page with no HTTPS padlock, no company name, no support contact.
- Any visual discontinuity between "Start Free Trial" and the page asking for a card.

**Implication:** whatever we build, the parent's journey from the pricing page to the
payment confirmation must look like **one continuous product**. That single requirement
should drive the architecture decisions below.

---

## 2. Why Stripe Checkout beats building our own checkout

We should not build a custom card form. This is not a matter of skill or effort — it's
that a homemade checkout is strictly worse on every axis that matters.

| | Stripe Checkout | Custom-built checkout |
|---|---|---|
| **PCI compliance burden** | SAQ-A (minimal) — card data never touches our servers | SAQ-D (heavy) — annual audits, scans, liability |
| **3-D Secure / SCA** | Handled automatically | We build and maintain it |
| **Fraud screening** | Stripe Radar included | We build it, or we eat the chargebacks |
| **Apple Pay / Google Pay** | One toggle | Significant work each |
| **Local methods** (PromptPay TH, etc.) | Supported | Separate integration each |
| **Card-network rule changes** | Stripe keeps it current | Ongoing maintenance forever |
| **Failed payments / dunning** | Built into Stripe Billing | We build retry logic |
| **Trust signal** | Recognised, reassuring | Unknown page asking for a card |
| **Time to launch** | Days | Months |

**The Apple Pay / Google Pay point deserves emphasis.** A large share of our traffic is
mobile parents. One-tap wallet payment removes the single biggest drop-off point on
mobile — typing a 16-digit card number. Stripe Checkout gives us this essentially free;
a custom form realistically never gets it.

**The security argument is decisive.** With hosted Checkout, card numbers never pass
through our servers, so a breach of our site cannot leak card data. Building our own
means we own that risk permanently, for no upside.

---

## 3. Where the checkout should live — three options

The tension: hosted Checkout is safest, but we want it to feel like our site. Stripe
supports all three of these, so it's a genuine choice.

### Option A — Embedded Checkout on our own page ⭐ *recommended*
Stripe's checkout renders **inside our page** (an embedded component). The URL stays
`thekidsgate.com`, our header/footer/branding surround it, but the card fields are still
served and secured by Stripe.

- ✅ Feels completely like our site — solves the trust problem entirely
- ✅ Still PCI-light (card fields are Stripe's, inside a secure frame)
- ✅ Parent never sees a domain change
- ⚠️ Slightly more build than a redirect

### Option B — Redirect to Stripe-hosted Checkout
Click "Subscribe" → go to `checkout.stripe.com` → return to a success page.

- ✅ Fastest to build, most robust
- ✅ Stripe branding is itself a recognised trust signal
- ⚠️ A domain change mid-journey — acceptable, because most people recognise Stripe,
  but less seamless than A
- ✅ We can upload our logo and brand colours in Stripe's settings to soften the jump

### Option C — A separate site/system for checkout ❌ *not recommended*
This is roughly the current proposal (a second WordPress with Stripe on it).

- ⚠️ Two systems to maintain, update and secure, forever
- ❌ Highest risk of the visual discontinuity described in §1
- ❌ Data has to be passed between systems anyway (so it isn't simpler)
- ❌ Hardest to keep consistent as the brand evolves

**Recommendation: Option A**, falling back to **Option B** if we need to ship faster.
If Option C is kept for other reasons, it *must* at minimum share our branding and sit
on a `pay.thekidsgate.com` subdomain — never an unrelated-looking site.

---

## 4. Carrying the plan selection across (and saving preferences)

Our pricing page already has an interactive builder where the parent chooses children,
subjects and billing period. That selection has to reach the checkout.

### How to pass it
1. **URL parameters** — simplest and has a nice side effect: the URL becomes
   *shareable and bookmarkable*, so a parent can save or resume their configuration.
   ```
   /checkout?children=2&c1=en,ma&c2=en&billing=annual&region=au&lang=en
   ```
2. **Server-side cart token** — the site POSTs the selection, gets back a short token
   (`/checkout?cart=abc123`). Cleaner for complex carts, and nothing sensitive in the URL.

Either is fine. URL params are recommended for phase one because they're transparent,
debuggable and let people share a configured plan.

### 🔒 The rule that must never be broken
**Pass the *selection*, never the *price*.**

The URL says *"2 children, child A on both subjects, annual"* — it must **never** say
`price=180`. Our server recomputes the amount from the selection and hands Stripe
**Price IDs** (amounts live in Stripe). If a price ever rides in the URL, someone will
change it to `1` and pay a dollar.

The server must also **validate** the selection before building the session:
max children, 1–2 subjects each, valid subject names, exactly one child on the
first-child rate. This blocks the obvious exploit of claiming every child is an
"additional child" to dodge the full rate.

### Carrying region and language too
The site already uses `/au/en/…` style URLs. The checkout should inherit **region,
language and currency** the same way, so an Indonesian visitor doesn't land on an
English AUD checkout. This is also why geolocation (§8) matters.

---

## 5. Sign-up vs checkout — which comes first?

This is a real fork with conversion consequences. Three workable sequences:

### Option 1 — Checkout first, account created after ⭐ *best conversion*
Parent configures plan → pays → Stripe returns their email → webhook creates the family
account → they set a password and add their children's profiles.

- ✅ Fewest steps before payment = highest conversion
- ✅ Their plan choice flows straight into checkout with nothing lost
- ⚠️ Child profiles/subject assignment happens *after* payment (fine — see §6)

### Option 2 — Sign up first, then checkout
Create account → log in → choose plan → pay.

- ✅ Cleanest linking: we already know the app user ID before payment
- ✅ Lets them add children and pick subjects *before* paying
- ⚠️ More friction before the money moment; some drop off at signup
- ⚠️ Must persist their plan selection through signup (store it in the session, or
  carry it in the URL as in §4) — otherwise they lose their choices, which is the exact
  problem you were worried about

### Option 3 — Combined single page ⭐ *best experience*
Plan builder **and** embedded checkout on one page. Adjust children/subjects on the left,
the total and payment form update on the right, sign-up fields inline.

- ✅ Nothing to carry over — it never leaves the page
- ✅ Feels premium and modern; matches the standard our audience expects
- ⚠️ Most build effort; needs careful mobile layout

**Recommendation:** aim for **Option 3** (combined page with embedded checkout) as the
target, and ship **Option 1** first if we need to launch sooner. Option 1 and 3 both
avoid the "lost my selections during signup" problem entirely.

---

## 6. How internal account activation actually works

This is the part that is genuinely hard, and it lives in the **app**, not the website.

### The mechanism: webhooks
A webhook is Stripe **calling our server** when something happens.

```
Parent pays
   ↓
Stripe → POST to our server: "checkout.session.completed"
   ↓
Our server verifies the signature, reads the line items + metadata
   ↓
Our server calls the APP BACKEND: "activate this account"
   ↓
App unlocks: child Ava → English, child Ben → Maths, plan active until <date>
```

### Keeping it in sync afterwards
| Stripe event | What we do |
|---|---|
| `checkout.session.completed` | Create/activate the subscription and entitlements |
| `invoice.paid` | Renewal succeeded — extend access |
| `invoice.payment_failed` | Start dunning; warn the parent |
| `customer.subscription.deleted` | Cancelled — end access at period end |
| `customer.subscription.updated` | Plan/child/subject change — update entitlements |

### Linking Stripe to the app account
The crucial detail: how does *"Stripe customer X paid"* become *"unlock app user Y"*?
We pass our **app user ID** into the Checkout Session (Stripe has fields for exactly
this — `client_reference_id` / metadata) and store the Stripe customer + subscription IDs
on the account. That two-way link is what makes everything else work.

### Who owns what (important conceptual split)
- **Stripe owns:** how much was paid, how many subject slots, billing status.
- **The app owns:** *which* subject each child is studying, and their progress.

Payment buys "one subject slot"; the app decides that slot points at English or Maths.
This is why parents can **switch subjects without a billing change** — which we promise
in our FAQ. If subjects were welded to Stripe products, switching would be a billing
headache.

### ⚠️ The dependency that gates everything
The app backend must:
1. store subscription status and per-child subject entitlements,
2. expose an endpoint the webhook can call to set them,
3. hold the link between app account and Stripe customer.

**Nothing else in this document works until that exists.** It is the highest-risk item
and should be confirmed with the app team before any checkout work starts.

---

## 7. Mapping our pricing model into Stripe

### The model
The child studying the **most subjects** pays the **first-child rate**; every other child
pays the lower **additional-child rate** for their own subject count. Plus a **one-time
activation fee**.

### How it's expressed
Use **flat-rate** recurring prices — one per combination:

| | 1 subject | 2 subjects |
|---|---|---|
| **First child** | Price A | Price B |
| **Additional child** | Price C | Price D |

…×2 billing intervals (monthly/annual) ×each currency, **plus** a one-time activation
fee price.

A family becomes **multiple line items on one subscription**:
```
Child A (2 subjects, primary)  → Price B
Child B (1 subject)            → Price C
Activation fee                 → one-time line item
Trial: 14 days
```
Stripe sums it. Our server only decides *which* price each child gets.

### Why the additional-child discount is a separate price, not a coupon
The additional-child rates are **not a clean percentage** of the first-child rates, and
the ratio differs by subject count and currency. Expressing them as their own prices is
exact and auditable; a percentage coupon would drift from our published pricing.

### Why it is not "tiered" or "per-seat" pricing
Both of those assume **identical units × quantity**. Our children are not identical —
one may do two subjects, another one — and the "most subjects pays full" rule is a
business decision, not quantity maths. So: flat-rate prices, assembled by our code.

### The activation fee
- Charged **once per child** while continuously enrolled.
- **Carries over** monthly → annual (no re-charge).
- **Re-applies** only if an account lapses/cancels and later rejoins.
- Implemented as a one-time line item on the **first invoice**.
- Because the trial defers the first invoice, decide explicitly: is the activation fee
  charged **at signup** or **when the trial converts**? (See open questions.)

---

## 8. Geolocation — why we should add it

We sell in **ten regions**, four languages and multiple currencies. Without geolocation,
every visitor lands on one default (realistically US/English) and has to *find* the
region selector in the footer to see their own language and prices.

**That is a bad trade.** We'd be degrading the experience for the overwhelming majority
in order to guard against a rare edge case.

### The VPN objection, honestly assessed
The concern is that VPN users get geolocated wrongly. True — but:
- **VPN use among our audience (parents) is a small minority.** It is not a mainstream
  behaviour for someone browsing a children's education app.
- **We already cater to it.** The footer region selector lets anyone correct the guess
  in one click. VPNs are an argument *for having a manual override* — which we have —
  not for abandoning geolocation.
- **Being wrong is cheap.** A mis-detected user sees the wrong currency for a few seconds
  and switches. A non-detected user has to figure out that a selector exists at all.

### But don't *force* a redirect
There is a legitimate version of the concern, and it isn't about VPNs — it's SEO.
**Google advises against auto-redirecting users by IP.** Googlebot crawls from the US,
gets redirected, and regional pages index poorly. It's also irritating when it guesses wrong.

**So: geo-*suggest*, not geo-*force*.**
1. Detect location → set a **smart default** for language and currency.
2. Show a small **dismissable banner**: *"Looks like you're in Indonesia — view the
   Indonesian site?"*
3. Keep the **manual region selector** always available.
4. Use `hreflang` so search engines index each regional version properly.

### Could we detect VPN users and send them to a region chooser?
**Yes, technically.** IP-intelligence services (and Stripe Radar itself) flag traffic
from datacentre/VPN ranges. We could route flagged visitors to a neutral
"choose your region" page rather than guessing.

**Worth doing? Probably not in phase one.** It adds a dependency and a cost to solve a
problem the region selector already solves. Worth revisiting *only* if we see evidence
of pricing arbitrage. Better as a phase-two refinement than a launch requirement.

### The billing address is the real source of truth
This is the point that resolves the whole debate. **At checkout, the parent enters their
billing address, and their card has an issuing country.** That is far more reliable than
an IP address, and it's what actually determines:
- which currency and price applies,
- tax/GST treatment,
- fraud scoring.

So even if a VPN user browses the "wrong" regional pages, **the payment step corrects
the record.** IP geolocation is a *convenience for browsing*; the billing address is the
*authority for charging*. That distinction removes the arbitrage risk almost entirely.

---

## 9. Free trial — card required, or no card?

We currently advertise a **14-day free trial, no credit card required**. Worth
re-examining, because the two models behave very differently.

| | **No card required** | **Card on file, auto-charge** |
|---|---|---|
| Trial signups | **Much higher** | Lower (card is a real barrier) |
| Trial → paid conversion | **Much lower** (often single digits) | **Much higher** (often 40–60%) |
| Quality of signups | More tyre-kickers | More genuine intent |
| Effort to convert | Must re-engage via email/in-app | Converts by default |
| Feels | Generous, low pressure | Standard, slightly more committing |
| Risk | Wasted infrastructure on non-buyers | Complaints if the charge surprises them |

### The Australian legal angle
Auto-renewing subscriptions are normal and lawful, **but** Australian Consumer Law
expects genuine clarity. If we auto-charge we must:
- state plainly, before signup, that it converts to a paid plan and when,
- make cancellation genuinely easy (Stripe's Customer Portal handles this),
- ideally **send a reminder email a few days before the first charge**.

Done properly this is not a dark pattern; done sloppily it generates chargebacks and
complaints — which is a far worse outcome than a lower signup number.

### Which is better for us?
**Recommendation: card on file with auto-charge, with strong disclosure and a
pre-charge reminder email.**

Reasoning:
- Conversion difference is not marginal — it's usually severalfold. That difference
  determines whether paid acquisition is viable at all.
- Our audience is comfortable with it; it's how every subscription they already use works.
- The **US $1 parental-verification** step (COPPA) requires a card anyway, so a card-free
  trial is not universally possible across our markets.
- The reminder email converts a potential complaint into a trust-building moment.

**The middle path worth considering:** keep "no card to start" but require a card to
*continue* at day 14, with reminders. Best of both, at the cost of more build and a
re-engagement flow that has to actually work.

⚠️ **Note:** if we switch to card-required, the site copy must change — it currently says
"no credit card required" in all four languages, and that claim must stay accurate.

---

## 10. Referrals and promoter tracking

The goal: a promoter shares a code/link → the buyer gets a discount → the promoter gets
paid for the signup.

### What Stripe does natively
- **Coupons** (the discount) and **Promotion codes** (customer-facing codes like
  `SARAH20`) — fully built in, including a "add promotion code" field in Checkout. ✅
- **Attribution** — give each promoter a unique code, and we can see which code was used. ✅

### What Stripe does *not* do
- **Calculating and paying promoter commissions.** There is no built-in affiliate engine. ❌

### Two ways to close that gap

**A) An affiliate add-on** (Rewardful, FirstPromoter, Tolt) ⭐ *recommended*
Purpose-built to sit on top of Stripe subscriptions.
- Promoter dashboards and unique referral links
- **Link-based attribution via cookies** — catches people who click through without
  typing a code (a large share)
- Automatic recurring-commission calculation (e.g. % of every renewal, not just signup)
- Payout handling and basic fraud checks
- Days of setup, not months

**B) Build it ourselves** with Stripe Connect
- Full control, no monthly fee
- We own attribution, commission logic, payouts, tax forms, fraud, and a promoter
  dashboard — indefinitely
- Only sensible at large scale with unusual requirements

### Why the add-on is better for us
Referral programmes are deceptively complex. The hard parts aren't the discount — they're
**attribution** (who really drove this sale, across devices and weeks), **recurring
commission** (do they earn on renewals for 12 months?), **payouts**, and **fraud**
(promoters buying through their own link). These tools have solved all of that. Building
it means diverting engineering away from the actual product to rebuild a solved problem.

**Recommended combination:** unique **referral link** for tracking (cookie-based) **plus**
a memorable **promo code** for the buyer's discount and for offline/word-of-mouth sharing.
The link catches the clicks; the code catches the conversations.

---

## 11. Questions that must be answered before building

These are the "why wouldn't this work?" items. Each is a genuine risk.

### Blocking — must have answers
1. **Does the app backend have (or can it build) an entitlement API?** What endpoint
   unlocks a specific subject for a specific child? *Without this, payment succeeds and
   nothing happens.*
2. **How do we link an app account to a Stripe customer?** Can we pass our user ID into
   checkout and store Stripe IDs on the account?
3. **Where do subscriptions happen — web, in-app, or both?** Apple and Google **require**
   their own billing for in-app digital subscriptions (15–30%). Stripe is for the **web**
   channel only. This decision changes the entire commercial model.
4. **Who builds and maintains the integration**, and do they have Stripe experience?

### Important — needed before launch
5. **Activation fee timing** — charged at signup, or when the trial converts?
6. **Trial model** — card required or not (§9)? Site copy must match.
7. **Indonesia** — Stripe is limited there; which local gateway (Midtrans/Xendit), and
   how does that fork the checkout for `/id/`?
8. **Thailand** — does our Australian entity support local THB settlement and PromptPay,
   or do we need a Thai Stripe account?
9. **Tax/GST** — do we use Stripe Tax? Which markets are we registered in?
10. **Refunds and cancellation policy** — must satisfy Australian Consumer Law and be
    published before we take money.
11. **Failed payments** — how many retries, how long does access persist, what do we email?
12. **Does the separate-WordPress setup block Embedded Checkout?** If we want §3 Option A,
    the checkout needs to live where our branding lives.

### Worth deciding early
13. Do we offer annual upfront discounts beyond the current rates?
14. Do promoters earn on renewals or first payment only?
15. Data residency for customer/payment records.

---

## 12. Recommended sequence

1. **Confirm the app-side entitlement API exists** (item 1 above). Everything waits on this.
2. **Decide web vs in-app** subscriptions (item 3) — this is a commercial decision, not technical.
3. Build the **Stripe product catalogue** in a sandbox: the four flat-rate prices ×
   intervals × currencies, plus the activation fee. *(Testable today, no dev needed.)*
4. Prove the model in the sandbox: 14-day trial, mixed family cart, activation fee,
   promotion code, Customer Portal cancel, and a test clock run through a renewal.
5. Build the **create-checkout endpoint** (validates selection server-side → Price IDs →
   Checkout Session) and the **webhook** (activates the account).
6. Add **Embedded Checkout** on our own page, with the plan builder alongside it.
7. Layer in the **affiliate add-on** for promoter tracking.
8. Add **geo-suggest** (smart default + dismissable banner + existing region selector).
9. Add the **Indonesian gateway** as a separate track.

Steps 3 and 4 can start immediately in a Stripe sandbox and de-risk everything else —
they prove the commercial model is expressible before any code is written.

---

## 13. One-paragraph summary for the dev conversation

> We want Stripe Checkout, embedded in our own page so it looks like The Kids Gate — our
> buyers are Australian parents who expect a polished, familiar checkout and will bounce
> from anything that looks improvised. The pricing page passes the *selection* (not the
> price) to a server endpoint, which validates it, maps it to Stripe Price IDs, and
> creates a Checkout Session with one line item per child plus the one-time activation
> fee and a 14-day trial. A webhook then calls the app backend to unlock the right
> subjects for the right children — **we need to confirm that endpoint exists**. Referral
> discounts use Stripe promotion codes, with an affiliate add-on handling promoter
> attribution and commission payouts. Geolocation sets a smart regional default with a
> dismissable suggestion banner, never a forced redirect, and the manual region selector
> stays for the small VPN minority — with the billing address at checkout being the real
> authority on where the customer is.

---

*Prepared as a planning document. Payment platform capabilities, fees and country
availability change — confirm current details with Stripe before committing.*
