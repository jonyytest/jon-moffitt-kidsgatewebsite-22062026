# FAQ Inbox

Drop new support questions and answers here, then tell Claude:

> **"Process the FAQ inbox"**

Claude will translate each entry into all four site languages and wire it into
every place it needs to live. You only write it once, in one language.

---

## How to add an entry

Copy the template below into the **New entries** section. Only `Q:` and `A:`
are required — write them in whichever language you drafted (English,
Indonesian, Thai or Chinese). Everything else is optional; leave it out and
Claude will pick sensibly and say what it picked.

```
### Q: <the question>
A: <the answer>
Category: <plans | product | account>          (optional)
Helper topic: <pricing | trial | dashboard | using | schools | contact | none> (optional)
Notes: <anything else — e.g. "answer differs for Indonesia", "replaces old answer about X">
```

### Category cheat-sheet (Support page FAQ)

| Category  | Use for |
|-----------|---------|
| `plans`   | Pricing, billing, trial, additional children, cancelling |
| `product` | What The Kids Gate is, lessons, AI, subjects, rewards, safety |
| `account` | App, devices, profiles, login, settings, technical issues |

### Helper topic cheat-sheet (Quick-help chat widget)

| Topic       | Covers |
|-------------|--------|
| `pricing`   | Cost, family plans, subjects per child, billing |
| `trial`     | Free trial, credit card, cancelling |
| `dashboard` | Parent dashboard questions |
| `using`     | Daily use, subjects & grades, safety, technical problems |
| `schools`   | Teachers, principals, school pricing |
| `contact`   | Escalation to the support team |
| `none`      | FAQ-only — no topic button in the chat widget (still findable by typing: the widget's free-text search indexes the whole support FAQ automatically) |

---

## New entries

(Nothing waiting. Add entries above this line — newest at the top is fine.)

---

## Processed

**2026-07-13 — persona brainstorm batch #2** (14 entries, generated on request
by role-playing prospective parents, current subscribers, homeschoolers,
teachers, sponsors and technical users; fanned out to all four locales at
support FAQ positions 22–35, before the bug-report + contact items). All are
FAQ-only (`Helper topic: none`) — every top-level widget topic is already at
its ~5-child cap, and the widget's free-text search indexes the whole support
FAQ automatically. This batch also introduced hidden `kw` search synonyms
(indexed, never displayed) on each new item so common phrasings match — e.g.
"cost / price / fee", "ipad / iphone", "harga", "ราคา", "多少钱" — supported by
`kw` handling added to `page-support.php`, `kg_render_helper()` and
`assets/js/support.js`. Questions the team still needs to answer were parked
in `UNANSWERED-FAQS.md` (20 items) instead of being published.

| Question (en) | Category | Escalates |
|---|---|---|
| How much does The Kids Gate cost? | plans | – |
| What ages is The Kids Gate designed for? | product | – |
| Which devices does The Kids Gate work on? | account | – |
| Is The Kids Gate a replacement for school? | product | – |
| What makes The Kids Gate different from other learning apps? | product | – |
| What are tokens and what can my child spend them on? | product | – |
| Can tokens be bought with real money? | product | – |
| Does each child need their own profile? | account | – |
| Can I change subjects or switch plans later? | plans | yes |
| I can't log in. What should I do? | account | yes |
| How is my child's personal information handled? | product | – |
| Can we use The Kids Gate for homeschooling? | product | – |
| How can my organisation sponsor or partner with The Kids Gate? | product | – |
| How do I delete our account or my child's data? | account | yes |

**2026-07-04 — via /faqadd**: "I've found a bug or error — how do I report it?"
(reworded from "Experienced a bug or error…") — `account`, helper node: none
(FAQ-only; found via widget search — the `using` topic is full and `using-tech`
already covers browse-path troubleshooting). Support FAQ position: second-to-last,
before the contact item. Marked `'escalate' => true`, so in the widget it offers
the go-to-support-form button instead of "Was this helpful?".

**2026-07-04 — persona brainstorm batch** (8 entries, generated on request and
fanned out to all four locales; support FAQ positions 15–22, before the final
"still need help" item):

| Question (en) | Category | Helper node |
|---|---|---|
| Does my child take a test before starting? | product | `trial-assessment` |
| Can I limit how much time my child spends in the app? | account | `dash-screentime` |
| Does The Kids Gate work without an internet connection? | account | `using-offline` |
| Will I get progress updates without logging in? | product | `dash-reports` |
| How are winners chosen in the monthly prize draws? | product | none (FAQ-only; found via widget search) |
| Can my child's teacher see their progress? | product | `schools-connect` |
| Which countries and currencies are supported? | plans | `pricing-regions` |
| How do I pay once the free trial ends? | plans | `trial-payment` (escalates) |

---

## Instructions for Claude (the fan-out procedure)

When asked to **process the FAQ inbox**, do the following for every entry in
**New entries**:

1. **Translate** the Q and A into the missing languages: en, id, th, zh.
   Adapt naturally per locale (the site prefers cultural adaptation over
   literal translation). Match the tone of existing answers: warm, short,
   no invented claims — if an entry asserts a product fact not already on the
   site, flag it to the owner instead of guessing.
2. **Support page FAQ** — add to `support.faq_items` in all four
   `kidsgate-theme/inc/lang/{en,id,th,zh}.php`, with the entry's `cat`
   (default: best fit from the category cheat-sheet). Keep the same array
   position in every locale (the files are index-aligned).
3. **Quick-help widget** — unless `Helper topic: none`, add a node under the
   matching top-level topic in `support.helper.nodes` in all four lang files:
   - `id`: unique kebab-case, prefixed by topic (e.g. `using-offline`).
   - `label`: a short button version of the question (a few words).
   - `answer`: may reuse the FAQ answer, trimmed for chat; links use the
     `{pricing_url}`-style placeholders resolved in `kg_helper_prepare_nodes()`.
   - Set `'escalate' => true` only if the answer's resolution is "contact us".
   - Keep a topic's children to ~5 max — nest a sub-branch if it grows past that.
4. **Never add a new top-level helper topic** without also updating
   `TOPIC_INDEX` in `assets/js/support.js` and the `support.form.topics`
   options in all four lang files (they are index-aligned with it), plus the
   `$lead_map` in `kg_render_helper()` if the topic should lead on a page.
5. **Verify**: `php -l` all four lang files, then in the preview open the
   Support page FAQ (check the new item renders and filters by category) and
   the Quick-help widget (check the new node appears and free-text search
   finds it — the search index builds itself from the tree, no extra step).
6. **Archive**: move the entry to **Processed** with today's date and the ids
   it was given. If an entry replaces an old answer, update the old item
   in place instead of adding a duplicate.
