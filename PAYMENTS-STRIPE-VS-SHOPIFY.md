# Payments for The Kids Gate — Stripe vs Shopify

**Prepared:** [INSERT DATE] · **For:** GATE Edutech Solutions Pty Ltd (CEO + development team)

> **Bottom line.** For a subscription learning app selling in Australia, the US,
> Thailand and Indonesia — with a complex per-child/per-subject pricing model and a
> US$1 parental-verification step — **Stripe is the better core for the web channel.**
> It covers three of your four markets first-party (AU, US, TH), is purpose-built for
> recurring billing, and handles your pricing and the $1 check cleanly. Pair it with a
> **local Indonesian gateway** (GoPay / Midtrans / Xendit) and, for the mobile apps,
> **Apple / Google in-app purchases** (which are mandatory regardless of this choice).
> Shopify only wins if you also want a full physical-goods storefront — which the
> in-app "Kids Gate Store" (virtual tokens) does not require.

---

## 1. First, the important framing

**Stripe and Shopify are not the same kind of tool.**

- **Shopify** is a complete **e-commerce platform** — storefront, product catalogue, checkout, customer accounts — with **Shopify Payments** as its built-in card gateway. It is built to sell *products*. Recurring subscriptions are an add-on (a third-party subscription app).
- **Stripe** is **payments and billing infrastructure** — APIs plus prebuilt UI (Stripe Checkout, Payment Links, Customer Portal). **Stripe Billing** is built natively for *subscriptions*. There is no storefront; you (or Ziad) wire it into thekidsgate.com and the apps.

**The app-store reality applies to both.** If a subscription is bought **inside** the iOS or Android app, Apple and Google generally require their **own in-app purchase billing** (≈15–30% commission). Neither Stripe nor Shopify may be used for in-app digital subscriptions. So this comparison really governs your **web** channel; the apps will use Apple/Google billing either way. Decide where you want people to subscribe before optimising the web processor.

---

## 2. Summary comparison

| Dimension | Stripe | Shopify |
|---|---|---|
| Product type | Billing/payments infrastructure | Full e-commerce platform |
| Built for subscriptions | Yes — Stripe Billing is native | Add-on app required (Recharge, Appstle…) |
| Storefront included | No (you build the flow) | Yes (full store) |
| First-party payments: AU | ✓ | ✓ (Shopify Payments) |
| First-party payments: US | ✓ | ✓ (Shopify Payments) |
| First-party payments: Thailand | ✓ | ✗ (needs 3rd-party gateway) |
| First-party payments: Indonesia | Limited | ✗ (needs 3rd-party gateway) |
| Complex family pricing | Strong (API-driven) | Awkward (app + custom work) |
| US $1 verification (COPPA) | Trivial (auth / charge+refund) | Rigid trial mechanics |
| Multi-currency (AUD/USD/THB/IDR) | Yes | Yes |
| Tax handling | Stripe Tax | Shopify Tax |
| Typical monthly platform fee | None | US$39–399+/mo (plan) |
| Extra fee for 3rd-party gateway | N/A | Yes (≈0.5–2% surcharge) |
| Developer effort | Higher (build the flows) | Lower (more out-of-box) |
| In-app (iOS/Android) subscriptions | Not allowed (Apple/Google IAP) | Not allowed (Apple/Google IAP) |

*✓ = first-party card processing available; ✗ = platform works but its own gateway is not, so a third-party gateway is required.*

---

## 3. Country coverage (your four markets)

This is where the two genuinely diverge. "First-party" means the platform's own gateway can take and settle the payment without bolting on a separate provider.

| Market | Currency | Stripe | Shopify Payments | Practical note |
|---|---|---|---|---|
| Australia | AUD | ✓ | ✓ | Both fine. Home market. |
| United States | USD | ✓ | ✓ | Both fine. |
| Thailand | THB | ✓ | ✗ | Stripe operates in TH (cards + PromptPay). Shopify needs Opn/Omise, 2C2P or similar. |
| Indonesia | IDR | Limited | ✗ | Card use is low; locals pay by e-wallet (GoPay/OVO/DANA) & bank transfer. A local gateway is needed either way. |

**Read-out:** Stripe is first-party in **3 of 4** markets; Shopify Payments in only **2 of 4**. Indonesia needs a local gateway regardless of which you pick.

**Thailand caveat:** "Stripe is available in Thailand" usually means a *Thailand-registered* business. As an Australian company you can charge Thai customers' international cards from your AU Stripe account today; to fully support **local THB settlement and PromptPay** you may need a Thai Stripe account/entity — worth confirming with Stripe, but still far ahead of Shopify Payments, which offers nothing local in Thailand.

---

## 4. Fit for *this* product

### Recurring subscriptions
Kids Gate is a subscription, not a product store. **Stripe Billing** is built for exactly this — plans, trials, renewals, proration, failed-payment retries (dunning), cancellations. On **Shopify** you add a subscription app (Recharge, Appstle, Seal, Bold), which adds another vendor, another monthly fee and another set of limits.

### Your family pricing model
Your pricing is genuinely intricate: per child, per subject, "the child doing the most subjects pays the first-child rate, the others pay the additional-child rate," an activation fee, up to four children, monthly or annual. That is **custom logic** — cleaner to express with Stripe's products/prices API than to force through Shopify's cart + a subscription app. You are already building custom pricing UI on the site, so Stripe fits how the project is already going.

### The US$1 parental-verification trial
Verifying a parent via a **refundable $1 card charge** (your COPPA method) is a few API calls in Stripe — a SetupIntent or a $1 charge you refund immediately, with the cardholder notification COPPA needs. Shopify's trial flow is more rigid and card capture during a "free trial" is more awkward to model.

### The "Kids Gate Store"
The in-app store sells **virtual tokens/avatars**, not shippable goods — so Shopify's core strength (a real product storefront with inventory, shipping, fulfilment) is not something you need.

---

## 5. Cost (approximate — verify current rates)

Exact rates vary by country, card type and plan, so treat these as directional.

| Cost element | Stripe | Shopify |
|---|---|---|
| Monthly platform fee | None | US$39–399+/mo (Basic → Advanced); Plus is more |
| Card processing (domestic) | ≈1.7% + A$0.30 (AU) / 2.9% + 30¢ (US) | Similar on Shopify Payments; better on higher plans |
| Not using the built-in gateway | N/A | Extra ≈0.5–2% surcharge per transaction |
| Subscriptions | Stripe Billing ≈ +0.5–0.8% on recurring (waivable on custom plans) | Subscription app: US$0–99+/mo plus its own % |
| Currency conversion | ≈+2% on cross-currency | ≈+1.5–2% |

**Read-out:** for a subscription business, Stripe is usually **cheaper overall** — no platform fee, no "penalty" surcharge, no separate subscription-app subscription. Shopify's fee stack (plan + app + non-Shopify-Payments surcharge in TH/ID) adds up.

---

## 6. Developer effort & maintenance

- **Shopify** gets you live faster out of the box *if* a standard store fits — but your non-standard pricing and the app-store split erode that head start.
- **Stripe** needs more up-front build (checkout, Customer Portal, webhooks for provisioning access), but gives you full control over the pricing logic, the $1 flow, and how web purchases unlock app access. Given Ziad is already building custom pricing UI, this is largely work you're doing anyway.

Either way, someone must build the bridge between "payment succeeded" and "unlock the child's access" — that's a webhook/entitlement step in both.

---

## 7. App stores — unavoidable for both

Worth restating because it may matter more than Stripe-vs-Shopify:

- **iOS:** Apple requires **In-App Purchase** for digital subscriptions bought in the app (15% under the Small Business Program up to US$1M/yr, otherwise 30%). Anti-steering rules limit linking out to web payment (loosening after recent US court rulings, but tread carefully).
- **Android:** Google Play Billing similarly required, comparable fees.
- **Web (thekidsgate.com):** here you are free to use Stripe or Shopify with normal card fees.

**Implication:** many EdTech apps push sign-ups to the **web** (via Stripe) to avoid the app-store cut, then unlock the apps. If that is your plan, Stripe's web strengths matter even more. If most sign-ups happen in-app, the processor choice is secondary to getting IAP right.

---

## 8. Privacy / legal implications (ties to your policy drafts)

Whichever you choose becomes the **named payment processor** in the Privacy Policy and Terms — the party that holds card data and whose privacy notice applies. Current state of the drafts:

- If you consolidate on **Stripe (AU/US/TH) + a local Indonesian gateway**, the documents get *simpler and more accurate* — one main processor plus one regional one, rather than Shopify-plus-gateways-per-country.
- Either way, no full card data touches your servers (both are PCI-compliant gateways) — that statement in the Privacy Policy holds.
- The **data-residency** line ("maintained only in your domicile country") is a separate architecture question (AWS regions) and is independent of the processor choice.

---

## 9. Recommendation

**Use Stripe as the core web payment platform**, structured as:

1. **Web (thekidsgate.com):** Stripe Billing + Stripe Checkout + Customer Portal for AU, US and Thailand. Handles your family pricing, trials, the refundable US$1 verification, renewals and cancellations.
2. **Indonesia:** add a **local gateway** (GoPay / Midtrans / Xendit) for e-wallet + bank-transfer payments, since cards are not the norm there and neither Stripe nor Shopify covers ID cleanly.
3. **Mobile apps:** **Apple IAP + Google Play Billing** (mandatory for in-app subscriptions).

**Choose Shopify only if** you also intend to run a genuine physical-goods store (books, merch, boxed materials) where Shopify's storefront, inventory and fulfilment earn their keep. That is not what the current product needs.

This is ultimately a CEO + developer decision based on what is already built and where you want conversions to happen — but on the merits for this product and country mix, Stripe is the stronger core.

---

## 10. What to confirm before deciding

1. **Where do subscriptions happen** — web, in-app, or both? (Biggest factor; determines how much the web processor even matters.)
2. **Current Stripe Thailand status** for an Australian entity — international-card acceptance vs full THB/PromptPay settlement (may need a Thai account).
3. **Indonesian gateway choice** — GoPay vs Midtrans vs Xendit, and what customer/payment data each collects (for the Privacy Policy).
4. **How much is already built on Shopify**, if anything — sunk cost vs switching effort.
5. **Apple/Google IAP plan** for the apps, and whether you will steer sign-ups to web.
6. **Who builds and maintains** the checkout + entitlement bridge.

> **Caveat:** payment-platform country availability and fees change frequently. Confirm the current Stripe and Shopify Payments status for each market with the providers before committing.
