# PLAN: Apply the wording-only fixes from the legal review to the four legal documents

## Goal

LEGAL-REVIEW-NOTES.md contains a set of "open notes" that are pure wording fixes —
they need no developer answers, no counsel sign-off, and no business decisions.
Apply exactly those, and nothing else, to the Markdown sources. Anything that
depends on Section E developer answers (SDK lists, data residency, retention
periods, DPO, payment providers) is explicitly OUT of scope — do not guess at it.

## Exact files to touch

| File | Change |
|---|---|
| `PRIVACY-POLICY.md` | Broaden the breach-notification line (§11) |
| `COOKIE-POLICY.md` | GPC wording alignment (§8); reframe checklist item 2 |
| `CHILDRENS-PRIVACY-NOTICE.md` | One-line location clarification |
| `LEGAL-REVIEW-NOTES.md` | Move each applied item from "open notes" to the document's "Fixed inline in this review" list |

`.docx` and `.pdf` versions are NOT edited in this task (see edge cases).

## The changes (from LEGAL-REVIEW-NOTES.md §B)

1. **PRIVACY-POLICY.md §11 (breach notification)** — currently cites only the
   Australian Notifiable Data Breaches scheme. Append the recommended general line,
   integrated into the existing sentence style: "…and any other breach-notification
   laws that apply to you (for example Thailand's PDPA and Indonesia's UU PDP have
   their own notification timelines)." Use the review's own suggested phrasing
   (§B1: "and other breach-notification laws that apply to you") as the anchor; the
   parenthetical is optional — match the document's tone, which is plain-language.
2. **COOKIE-POLICY.md §8 (GPC)** — reword so it mirrors the Privacy Policy's
   softened GPC stance (read PRIVACY-POLICY.md's GPC paragraph FIRST and mirror its
   wording): we do not sell or share personal information, so the GPC signal does
   not change how we treat data; we honour it where a law requires us to act on it.
3. **COOKIE-POLICY.md checklist item 2** — currently frames the consent banner as
   an EU/UK requirement. Reword: EU/UK are blocked at market level; the live driver
   for the banner is Thailand's PDPA (consent before non-essential cookies), and a
   banner that gates GA4 is recommended for all markets. (If
   PLAN-cookie-consent-banner.md has already been implemented, instead mark the
   item as done and describe the implemented behaviour: GA4/GTM loads only after
   an explicit accept, choice stored in the first-party `kg_consent` cookie.)
4. **CHILDRENS-PRIVACY-NOTICE.md** — in the "what we DON'T collect" area where
   exact location / GPS is mentioned, add the clarifying line suggested by the
   review (§B4): "We know your country or region so we can show the right prices
   and leaderboard rankings — but never your child's exact location." Adapt to the
   notice's friendly voice (it has a "For kids" register in places; put this line
   in the parent-facing section, not the kids section).
5. **LEGAL-REVIEW-NOTES.md** — for each of 1–4, move the corresponding bullet from
   the "Open notes / to double-check" list into that document's "Fixed inline in
   this review" list (create the list for B3/B4 if absent), with today's date.

## Step-by-step

1. Read each target section in full before editing (grep for section headings —
   the `.md` docs use `## ` numbered headings; find "breach", "Global Privacy
   Control", "checklist", "location" respectively).
2. **Verify the fix isn't already applied.** The review says some items were
   "fixed inline" during the review pass — if the text already matches the intent,
   skip the edit and only update LEGAL-REVIEW-NOTES.md. (Known already-applied
   examples: privacy@ routing in Privacy Policy §12; "or an alternative provider"
   in TOS §9.)
3. Apply edits 1–4 as minimal diffs — do not reflow paragraphs, do not touch
   effective-date placeholders (`[INSERT DATE]` stays), do not renumber sections.
4. Update LEGAL-REVIEW-NOTES.md (edit 5).
5. Report the exact diff of every changed sentence.

## Edge cases found while exploring (a weaker model would miss these)

- **Each document exists as `.md`, `.docx`, and (privacy only) `.pdf`, and the
  footer serves PDFs from `kidsgate-theme/assets/pdf/`.** Editing only the `.md`
  creates drift on purpose: the `.md` is the working source for these fixes, but
  the published PDF and the `.docx` are now stale. The completion report MUST list
  which downstream copies need regeneration (all four `.docx`, plus the PDFs per
  PLAN-fix-legal-doc-links.md). If PLAN-fix-legal-doc-links.md runs after this
  plan, its PDFs pick these fixes up only if it converts from `.md` — coordinate:
  tell the owner the `.docx` files are now BEHIND the `.md` files, reversing the
  usual authority order. Best execution order: this plan → regenerate docx/PDFs →
  PLAN-fix-legal-doc-links.
- **Do NOT touch the §10-vs-§8 data-residency contradiction** in the Privacy
  Policy (review priority item 6). It looks like a wording fix but it isn't — the
  correct wording depends on the actual AWS region setup (Developer Question 25).
  Same for the TOS renewal-notice logic (§4.2) — the 30-day/annual vs monthly split
  needs an owner decision.
- **The Cookie Policy's inventory is code-accurate on purpose** (`kg_choice`,
  `kg_lang`, `_ga`, `__cf_bm`, helper sessionStorage — review §B3 calls it
  "unusually accurate"). If the consent-banner plan has added a `kg_consent`
  cookie, ADD it to the inventory table (first-party, essential/preference, stores
  the analytics-consent choice, 180 days). If the banner isn't built yet, don't
  pre-document it.
- **Tone check:** the Children's Notice deliberately uses emoji (❌ 🚫 👋 ✨) and
  second-person warmth. A legal-register sentence dropped into it will read wrong.
  Match voice per document: Privacy Policy = formal-plain, Children's Notice =
  warm-plain.
- **These files are at repo ROOT, not in the theme** — nothing on the website
  changes from this task alone; there is nothing to verify in the preview. Don't
  waste time reloading the site.

## Acceptance criteria

1. Grep confirms each of the four insertions exists in its target file, and
   `[INSERT DATE]` count per file is unchanged.
2. PRIVACY-POLICY.md §11 mentions breach-notification obligations beyond
   Australia; COOKIE-POLICY.md §8 no longer implies GPC changes our processing
   (mirrors Privacy Policy wording); checklist item 2 no longer frames the banner
   as an EU/UK requirement; CHILDRENS-PRIVACY-NOTICE.md contains the
   country-vs-exact-location clarification outside the "For kids" section.
3. LEGAL-REVIEW-NOTES.md shows all four items under "Fixed inline" with a date,
   and they no longer appear under "Open notes".
4. No other sentence in any legal document changed (diff review).
5. Completion report lists the stale downstream copies (.docx ×4, PDFs) that need
   regeneration.
