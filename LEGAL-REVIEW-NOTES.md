# The Kids Gate — Legal & Compliance Review Notes

**Prepared:** [INSERT DATE] · **Covers:** Privacy Policy, Terms of Service, Cookie Policy, Children's Privacy Notice

> **Important — read first.** This is a structured, lawyer-style review to hand to
> qualified counsel; it is **not legal advice** and no solicitor–client relationship
> is created. It flags issues, inconsistencies, missing facts and compliance
> questions so counsel and the developers can resolve them efficiently. Where it
> states a legal requirement, treat it as "verify with counsel", especially for the
> fast-moving areas (COPPA Rule amendments, Australia's child-privacy reforms,
> Indonesia's UU PDP, Thailand's PDPA).

---

## A. Highest-priority items (fix or confirm before launch)

1. **COPPA — third-party disclosure of children's data.** The US$1 trial charge covers *parental consent to collect*. It does **not** by itself cover *disclosing* a child's personal information (including persistent identifiers) to third-party SDKs. If the app sends any child data to analytics, crash-reporting, push or ad SDKs, the amended COPPA Rule may require **separate** consent or that the disclosure be "integral" to the service. → depends entirely on the app's SDK list (Developer Questions 3–4).
2. **Persistent identifiers are "personal information" under COPPA.** Device IDs, advertising IDs and analytics cookies used to recognise a child over time count as personal information even with no name attached. Confirm no analytics/ad SDK runs on child-facing screens, or that it is configured COPPA-safe (Developer Questions 2–4, 23).
3. **DPO may be mandatory in Indonesia and Thailand.** Both UU PDP (Indonesia) and the PDPA (Thailand) can require appointing a Data Protection Officer where core activities involve large-scale processing of children's / sensitive data. The Privacy Policy currently states no DPO. Counsel to assess whether ID/TH operations trigger this.
4. **Australia's Children's Online Privacy Code.** The 2024 Privacy Act reforms require the OAIC to develop a Children's Online Privacy Code covering services likely to be accessed by children. A children's EdTech platform is squarely in scope. Monitor and plan to comply once registered.
5. **Payment provider for Indonesia & Thailand.** **GoPay** has now been named alongside Shopify. GoPay is Indonesia-focused, so confirm the **Thailand** provider (GoPay may not cover TH), and add GoPay's privacy-policy link — currently a placeholder in Privacy Policy Section 3.4.
6. **Data residency vs international transfers (NEW).** Privacy Policy Section 10 now states account data is "maintained only in your domicile country," while Section 8 describes transfers to Australia/US. These must be reconciled and matched to the actual AWS region setup (Developer Question 25). A false residency claim is a compliance risk.
7. **US auto-renewal law.** If the US trial stores a card and can auto-convert to paid, state Automatic Renewal Laws (California and others) require clear upfront disclosure, affirmative consent and an easy online cancel path. Confirm the checkout flow (Developer Question 9; TOS §3, §4.2).

---

## B. Document-by-document findings

### B1. Privacy Policy

**Reads correctly:** controller identity (ACN 652 998 635), data-minimisation framing, the automated-personalisation / no-AI-training description, ad-free and no-sale commitments, the refundable US$1 consent mechanism, and the region-specific rights section.

**Fixed inline in this review:**
- Rights requests now routed to **privacy@thekidsgate.com** (Section 12) — previously pointed only to support@, inconsistent with the designated privacy contact.

**Open notes / to double-check:**
- **APP 8 (Australia) — name the recipient countries.** Section 8 says data may go to "Australia and the United States." Australian Privacy Principle 8 expects you to identify the countries where overseas recipients are located *where practicable*. List them once hosting, Shopify's alternative, Google (analytics) and any SDK vendors are confirmed.
- **Breach notification — broaden beyond Australia.** Section 11 cites the Australian Notifiable Data Breaches scheme only. Thailand's PDPA (notify regulator within 72 hours) and Indonesia's UU PDP (notify within 3×24 hours) impose their own timelines. Recommend a general line: "and other breach-notification laws that apply to you."
- **International transfer mechanism (Section 8).** For ID/TH, confirm the actual lawful transfer basis (adequacy, contractual safeguards, or consent) rather than the generic "standard contractual clauses" phrasing, which is an EU concept.
- **"No strict minimum age" (Section 5).** Legally workable with parental consent, but it means a 3–4-year-old's data could be collected. Confirm this is intended, and that it sits comfortably with the "designed for 5–12" positioning.
- **"Legitimate interests" basis (Section 4.3).** Both Thai PDPA and Indonesia UU PDP recognise a legitimate-interest basis, so this is defensible — but the balancing must be documented. Confirm with counsel.
- **Retention specificity (Section 10).** The amended COPPA Rule expects a clear, public statement that children's data is kept only as long as necessary and then deleted. The section is close; confirm the final periods and that deletion is genuine (Developer Question 15).
- **Data controller vs APP entity terminology.** "Data controller" is used throughout; acceptable as a general term, but Australian law uses "APP entity." Cosmetic — counsel's call.

### B2. Terms of Service

**Reads correctly:** entity/ACN, per-region trial model, family pricing and activation-fee description (matches the live pricing page), tokens-have-no-cash-value clause, no-guaranteed-outcomes clause, ACL non-excludable-guarantees acknowledgement, and the governing-law clause with its consumer carve-out.

**Fixed inline in this review:**
- Section 9 now notes Shopify "**or an alternative provider** where Shopify is unavailable," aligning it with Section 4.2.

**Open notes / to double-check:**
- **Renewal notice logic (Section 4.2).** "30 days before automatic renewal" cannot literally apply to monthly plans (they renew every ~30 days). Confirm: 30-day notice for **annual** renewals; monthly renewals covered by the billing receipt. Reword accordingly.
- **Liability cap AUD $100 (Section 11).** Aggressive for a consumer contract. The ACL carve-out and resupply fallback help, but confirm enforceability against consumers in the US/ID/TH too; some US states restrict liability limitations.
- **"Non-refundable" wording (Section 4.3).** Keep it always adjacent to the ACL carve-out (it currently is). Blanket "no refunds" statements attract regulator (ACCC) attention if they read as excluding consumer-guarantee remedies.
- **Prize draws (Section 6).** Still needs the trade-promotion/permit check per market (AU state permits, ID, TH) and standard draw terms — already flagged in-document.
- **Cambridge alignment (Section 8).** Confirm the permitted way to describe alignment to the Cambridge syllabus and any required attribution/licence; "Cambridge" is a protected brand.
- **App-store rider.** Section 9 is good; when the iOS build ships, confirm whether Apple's required EULA terms (e.g. Apple as third-party beneficiary) need adding.

### B3. Cookie Policy

**Reads correctly and is unusually accurate** — the cookie inventory (`kg_choice`, `kg_lang`, GA4 `_ga`/`_ga_*`, Cloudflare `__cf_bm`, sessionStorage for the help widget) was built from the actual code.

**Open notes / to double-check:**
- **Consent-banner dependency.** The policy assumes a consent banner "where shown," but none is implemented yet. Thailand (PDPA) expects consent before non-essential cookies; even with EU/UK blocked, a banner that gates GA4 is recommended. This is an implementation task, not just wording.
- **GPC wording alignment.** Section 8's GPC paragraph should mirror the softened Privacy Policy wording (we don't sell/share, so the signal changes nothing; we honour it where a law requires). Minor consistency fix.
- **Checklist item 2** still frames the banner as an EU/UK requirement; update to reflect that EU/UK are now blocked and Thailand is the live driver.

### B4. Children's Privacy Notice

**Reads correctly** and does its job as a plain-language companion; the "For kids" section is a genuine plus for the emerging children's-code expectations.

**Open notes / to double-check:**
- **"Exact location (no GPS)" vs general location.** The notice lists location under "what we DON'T collect," but the Privacy Policy *does* collect general location (country, and state/city where provided). Not contradictory (GPS ≠ general location), but a careful parent could read tension. Consider a one-line clarification: "we know your country/region for pricing and rankings, but never your child's exact location."
- **Contact routing.** Uses support@ only; consider adding privacy@ for consistency with the Privacy Policy (kept friendly, support@ is fine as the visible channel).
- **Emoji rendering** — confirm ❌/🚫/👋/✨ render across the site and apps.

---

## C. Jurisdiction compliance flags

**United States (COPPA + state laws)**
- Verifiable parental consent: the refundable US$1 card charge is an FTC-recognised "monetary transaction" method — confirm it generates a cardholder notification (Developer Question 8).
- Amended COPPA Rule (2025): separate consent for third-party disclosures; written/public data-retention policy; updated direct-notice content. Verify against the app's actual data flows.
- Persistent identifiers count as children's personal information — govern analytics/SDKs accordingly.
- CCPA/CPRA (California) and other state laws: you don't sell/share, which simplifies compliance; keep the "do not sell/share" statements accurate.
- Auto-renewal laws: see Priority Item 6.

**Australia (Privacy Act 1988 + APPs, and 2024 reforms)**
- APP 1.4 privacy-policy content: mostly covered; ensure it states kinds of data, collection/holding methods, purposes, access/correction process, complaint process, and overseas disclosure + countries (APP 8).
- Children's Online Privacy Code (in development) — plan for it.
- Statutory tort for serious invasions of privacy (2024 reforms) raises the cost of a breach — reinforces the need for real security controls (Developer Questions 13–17).
- Confirm the registered office address and whether an ABN should appear (GST/tax-invoice context).

**Indonesia (UU PDP No. 27/2022, fully enforceable since Oct 2024)**
- Parental consent for children's data — align with the consent mechanism.
- Possible DPO requirement (Priority Item 3).
- Cross-border transfer rules — confirm lawful basis.
- Marketing: lean to explicit opt-in for Indonesian users.

**Thailand (PDPA B.E. 2562)**
- Children's consent: for a minor, consent from the person with parental responsibility; for a child under 10, the parent consents outright — confirm the flow captures this for 5–12-year-olds.
- Possible DPO requirement (Priority Item 3).
- Breach notification within 72 hours.
- Marketing consent and cookie consent expectations.

---

## D. Missing information to fill before publishing (consolidated)

1. **Effective date** (all four documents).
2. **ABN** + confirm the registered office address — *Privacy Policy Section 1, Terms Section 1.*
3. **Hosting** — provider confirmed as **AWS**; still confirm data-region per market, backup location, and the "domicile country" residency claim — *Privacy Policy Sections 6, 8 and 10.*
4. **Full app SDK list** (analytics, crash, push, ads) and what each receives — *Privacy Policy Section 3.3, Cookie Policy Section 7.*
5. **Payment providers** — **Shopify** and **GoPay** named; confirm the Thailand provider and add GoPay's privacy-policy URL — *Privacy Policy Section 3.4, Terms Sections 4.2 and 9.*
6. **Security specifics actually applied** (encryption, access controls) — *Privacy Policy Section 11.*
7. **Leaderboard**: exact fields shown, opt-out, public vs login-only — *Privacy Policy Section 4.2, Children's Notice.*
8. **Push-notification targets** (parent / child / both) — *Privacy Policy Section 3.3.*
9. **Final retention periods**; deletion and backup-purge reality — *Privacy Policy Section 10.*
10. **Cambridge alignment / attribution wording** — *Terms Section 8.*
11. **School agreement** + controller/processor stance; any Cambridge data sharing — *Privacy Policy Section 14, Terms Section 12.*
12. **Change-notification method** to users — *Privacy Policy Section 16, Terms Section 14.*
13. **Recipient countries** for overseas disclosure (APP 8) — *Privacy Policy Section 8.*
14. **Cookie-consent banner** + GPC technical handling — *Cookie Policy Sections 8–9.*

---

## E. Questions for the Application developers

*Please answer each; several determine whether the policies are accurate as written.*

**Data collection & identifiers**
1. Does the app request microphone or camera OS permissions at any point? (The notices state we do not record a child's voice — this must stay true at the permission level.)
2. Does the app collect device identifiers, advertising IDs (IDFA / Android Advertising ID), or use any device fingerprinting? For which users — parent, child, or both?
3. **List every third-party SDK** in the iOS and Android apps (analytics, crash reporting, push, A/B testing, ads, attribution), each with its purpose and what data it receives.
4. Do any of those SDKs transmit **children's** data — including persistent identifiers — to a third party? If so, which, and can it be disabled for child profiles?
5. Is push notification implemented? Via which service (Firebase Cloud Messaging, APNs directly, OneSignal, etc.)? Are notifications sent to the parent's device, the child's device, or both?
6. How is a family's **state and city** determined — parent-entered, derived from billing address, or IP geolocation?
7. Does the app or website collect any **health, disability, or special-education-needs** information (which would be sensitive data)?

**Accounts, consent & payments**
8. How is the US **$1 charge** implemented — capture-then-refund? And does it generate a **transaction notification to the cardholder** (required for the COPPA monetary-transaction consent method)?
9. For US trials, is the card stored/tokenised, and does the trial **auto-convert** to paid, or must the user re-enter payment to subscribe?
10. Which **payment provider** is used in Indonesia and Thailand (Shopify Payments is unavailable there), and what billing/card data does it collect?
11. How is **EU/UK blocking** implemented — IP geo-block, billing-country restriction, or both — and at what stage (browse, sign-up, checkout)?
12. Is there an **email verification** step at sign-up, and does account activation depend on it?

**Security, storage & retention**
13. Where is user data **hosted** (provider + country/region)? Where are backups stored?
25. **Data residency:** the Privacy Policy now states account data is "maintained only in your domicile country." Is that actually true — i.e. is each market's data kept in its own **AWS region** (e.g. Sydney for AU, Jakarta for ID, Bangkok for TH, a US region for US)? Or is data centralised in one region? This determines whether the residency claim and the international-transfers section are accurate.
14. Is data **encrypted** in transit (TLS) and at rest? How are passwords stored (hashing algorithm + salting)?
15. What are the **actual retention periods**? Is account deletion a hard delete, and what is the backup-purge cycle?
16. Who internally can access **children's data**? Are there role-based access controls and audit logging?
17. Is there a documented **data-breach response** process, and can you meet 72-hour / 3×24-hour regulator-notification timelines?

**Leaderboard, schools & sharing**
18. Exactly which fields are shown **publicly** on the leaderboard for a child (nickname? real name? country? state/city? avatar? score?)?
19. Is the leaderboard visible to **non-logged-in** visitors, or only to logged-in users inside the app?
20. Can a parent turn a child's **leaderboard visibility off**, and is it **on or off by default**?
21. Is any child/student data ever sent to **Cambridge Assessment** or other curriculum/third-party educational bodies?
22. For **school accounts**: do schools bulk-upload student data? Where is it stored, and who controls it (school vs The Kids Gate)?

**Cookies & consent**
23. Does **GA4/GTM load before or after** cookie consent, and is a consent banner implemented anywhere?
24. Does the site detect and honour **Global Privacy Control (GPC)** signals?

---

## F. Recommended pre-launch priority order

1. Get the developer answers in Section E — several policy statements are only "true if" the app behaves as assumed (voice, SDKs, identifiers, analytics on child screens).
2. Resolve the COPPA third-party-disclosure and persistent-identifier questions (A1–A2) — highest legal-risk area for a US kids' product.
3. Name the ID/TH payment provider and confirm the EU/UK block actually works.
4. Fill the missing facts in Section D (hosting, SDKs, retention, security, leaderboard).
5. Decide the US trial auto-convert behaviour and align the checkout with US auto-renewal law.
6. Have counsel review all four documents together — with these notes attached — and advise on the DPO question (ID/TH) and the Australian Children's Online Privacy Code.
7. Implement the cookie-consent banner (Thailand driver) before enabling GA4.
