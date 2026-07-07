# Quick Help — Conversation Tree

This document is the full content of the "Quick help" bubble (bottom-right of every page).
Edit anything here — button labels, answers, add or remove topics — and send it back;
it maps 1-to-1 onto the site's helper config, so changes are easy to integrate.

---

## How to read and edit this document

- `MENU:` = a button that opens more buttons (a sub-menu).
- `ANSWER:` = a button that prints a reply in the chat.
- Every ANSWER declares how it ends, on the `ENDING:` line:
  - `ENDING: HELPFUL? buttons` → shows "Was this helpful?" with **Yes, thanks!** / **Not really**.
    - Yes → "Glad that helped! What else can we look into?" and the topics show again.
    - No → "No problem. Our support team can help you directly." + a **Go to Support** button.
  - `ENDING: SUPPORT FORM button` → skips the helpful question and shows a
    **Go to the support form** button straight away (for dead-ends that need a human).
- `(id: xxx)` is the internal name used for analytics tracking. Keep ids if you can;
  if you add new buttons, either invent a short id or leave it blank and I'll add one.
- Answers may contain links written as `[link text -> {placeholder}]`. Available placeholders:
  - `{pricing_url}` — Pricing page
  - `{parents_url}` — For Parents page
  - `{schools_url}` — For Schools & Teachers page
  - `{support_url}` — Support page
  - `{support_email}` — the configured support email (currently support@thekidsgate.com)
- You can nest menus as deep as you like (currently max 2 levels are used).
- This file is the ENGLISH version. Once you're happy with it, I'll mirror the
  changes into Indonesian, Thai and Chinese (or you can supply the translations).

---

## General texts (not part of the tree)

| Purpose | Current text |
|---|---|
| Floating button label | Quick help |
| Window title | The Kids Gate Help |
| Greeting | Hi! How can we help today? Pick a topic below 👇 |
| After a "Yes, thanks!" | Glad that helped! What else can we look into? |
| Helpful question | Was this helpful? |
| Helpful — yes button | Yes, thanks! |
| Helpful — no button | Not really |
| After "Not really" | No problem. Our support team can help you directly. |
| Button after "Not really" | Go to Support |
| Escalation button | Go to the support form |
| Back button | ← Back |

---

## The tree

```
START (greeting)
│
├── MENU: Pricing & family plans (id: pricing)
│   │
│   ├── ANSWER: How much does it cost? (id: pricing-cost)
│   │     Plans start with one subject for the first child, with a lower rate for a
│   │     second subject and for additional children. The [pricing page -> {pricing_url}]
│   │     has an interactive builder that shows your exact monthly or yearly total.
│   │     ENDING: HELPFUL? buttons
│   │
│   ├── MENU: Adding more children (id: pricing-add)
│   │   │
│   │   ├── ANSWER: How many children can I add? (id: pricing-add-count)
│   │   │     A family account covers up to six children, each with their own profile,
│   │   │     progress and rewards.
│   │   │     ENDING: HELPFUL? buttons
│   │   │
│   │   └── ANSWER: Do extra children pay full price? (id: pricing-add-cost)
│   │         No. You pay full price only for the child doing the most subjects. The
│   │         others are charged the lower additional-child rate based on what they study.
│   │         ENDING: HELPFUL? buttons
│   │
│   ├── ANSWER: Different subjects per child (id: pricing-subjects)
│   │     Yes. Children on the same account can study different subjects. You're only
│   │     charged the extra cost for the additional subjects each child takes.
│   │     ENDING: HELPFUL? buttons
│   │
│   └── ANSWER: Monthly vs yearly (id: pricing-billing)
│         You can pay monthly or yearly, and yearly billing works out cheaper over the
│         year. Toggle between them on the [pricing page -> {pricing_url}].
│         ENDING: HELPFUL? buttons
│
├── MENU: Starting the free trial (id: trial)
│   │
│   ├── ANSWER: How does the 30-day trial work? (id: trial-how)
│   │     Every plan begins with 30 days free so your child can explore the full
│   │     experience. Just create a family account in the app to start.
│   │     ENDING: HELPFUL? buttons
│   │
│   ├── ANSWER: Do I need a credit card? (id: trial-card)
│   │     No credit card is needed to start the free trial.
│   │     ENDING: HELPFUL? buttons
│   │
│   └── ANSWER: Cancelling (id: trial-cancel)
│         You can cancel at any time. Until self-service cancellation is connected,
│         our [support team -> {support_url}] will sort it for you.
│         ENDING: HELPFUL? buttons
│
├── MENU: Parent dashboard (id: dashboard)
│   │
│   ├── ANSWER: What can I see? (id: dash-what)
│   │     Progress, time spent, mastery scores, difficult topics and simple
│   │     recommendations for each child. The [For Parents page -> {parents_url}]
│   │     has a full walkthrough.
│   │     ENDING: HELPFUL? buttons
│   │
│   ├── ANSWER: Tracking several children (id: dash-multi)
│   │     Each child has their own profile, so you can switch between them and see
│   │     individual progress, streaks and rewards.
│   │     ENDING: HELPFUL? buttons
│   │
│   └── ANSWER: Spotting struggles (id: dash-help)
│         The dashboard flags difficult topics early and suggests how to help, so
│         small gaps don't become big ones.
│         ENDING: HELPFUL? buttons
│
├── MENU: Using the app (id: using)
│   │
│   ├── ANSWER: How long each day? (id: using-time)
│   │     The Kids Gate is built around short daily sessions of about 20 minutes,
│   │     enough to make progress without losing the fun.
│   │     ENDING: HELPFUL? buttons
│   │
│   ├── ANSWER: Subjects & grades (id: using-subjects)
│   │     Children can study Cambridge English, International Maths, or both, across
│   │     Grades 1–6 (ages 5–12).
│   │     ENDING: HELPFUL? buttons
│   │
│   ├── ANSWER: Is it safe and ad-free? (id: using-safe)
│   │     Yes. The Kids Gate is designed as a safe, ad-free space for children to
│   │     learn and play.
│   │     ENDING: HELPFUL? buttons
│   │
│   └── ANSWER: Something isn't working (id: using-tech)
│         Sorry about that! Updating to the latest app version fixes most issues. If
│         it persists, use the support form with the topic "Technical help" and
│         we'll dig in.
│         ENDING: SUPPORT FORM button
│
├── MENU: Schools & teachers (id: schools)
│   │
│   ├── ANSWER: Teacher & principal dashboards (id: schools-dash)
│   │     Teachers get class-level progress and mastery views; principals get
│   │     school-wide insight across classes. See the
│   │     [For Schools & Teachers page -> {schools_url}].
│   │     ENDING: HELPFUL? buttons
│   │
│   ├── ANSWER: School or bulk pricing (id: schools-pricing)
│   │     For classroom or whole-school plans, send us the details through the
│   │     enquiry form on the [For Schools & Teachers page -> {schools_url}] and our
│   │     schools team will be in touch.
│   │     ENDING: HELPFUL? buttons
│   │
│   └── ANSWER: Make an enquiry (id: schools-enquiry)
│         The enquiry form on the [For Schools & Teachers page -> {schools_url}]
│         goes straight to our schools team.
│         ENDING: HELPFUL? buttons
│
└── ANSWER: Contact the support team (id: contact)
      You can reach a human via the support form, or email us at
      [{support_email} -> mailto:{support_email}]. We aim to reply within two
      working days.
      ENDING: SUPPORT FORM button
```

---

## Notes

- Every menu screen automatically gets a `← Back` button; you don't need to add it.
- Ordering in this file = ordering of the buttons on screen.
- To DELETE a button, just remove its block. To ADD one, copy an existing block,
  change the label/answer, and place it where you want it in the order.
- To change an ending, just change the `ENDING:` line (`HELPFUL? buttons` ⇄
  `SUPPORT FORM button`).
