# PLAN: Fix the footer legal-document links (3 of 4 PDFs missing, all 4 URLs break in production)

## Goal

Every legal link in the footer (Privacy Policy, Terms of Service, Cookie Policy,
Children's Privacy) must download a real, current PDF **both** in the dev preview
(`php -S localhost:8090 tests/preview.php`) **and** in a real WordPress install.

Today neither is fully true:

1. Only `kidsgate-theme/assets/pdf/privacy-policy.pdf` exists. The footer also links to
   `terms-of-service.pdf`, `cookie-policy.pdf` and `childrens-privacy-notice.pdf`,
   which do not exist → those three links 404 everywhere.
2. All four hrefs are **site-root-absolute** (`/assets/pdf/...`). That resolves in the
   dev preview (its router serves `/assets/*` straight from the theme folder) but
   **404s in a real WordPress install**, where theme assets live at
   `/wp-content/themes/kidsgate-theme/assets/...`. The correct helper is
   `kg_asset( 'pdf/<name>.pdf' )` (defined in `kidsgate-theme/functions.php:289`).

## Exact files to touch

| File | Change |
|---|---|
| `kidsgate-theme/footer.php` (lines 64–67) | Replace hardcoded `/assets/pdf/...` hrefs with `kg_asset()` calls |
| `kidsgate-theme/assets/pdf/terms-of-service.pdf` | NEW — generated from `TERMS-OF-SERVICE.md`/`.docx` at repo root |
| `kidsgate-theme/assets/pdf/cookie-policy.pdf` | NEW — generated from `COOKIE-POLICY.md`/`.docx` |
| `kidsgate-theme/assets/pdf/childrens-privacy-notice.pdf` | NEW — generated from `CHILDRENS-PRIVACY-NOTICE.md`/`.docx` |
| `kidsgate-theme/assets/pdf/README.txt` | Update: it lists only two expected filenames; add the two new ones |

Do **not** touch the four source documents' content in this plan (content fixes are
PLAN-legal-copy-alignment.md; if both plans run, do that one FIRST, then regenerate
PDFs here).

## Step-by-step

1. **Fix the hrefs.** In `footer.php`, change each of the four `<li>` links from
   `href="/assets/pdf/<name>.pdf"` to
   `href="<?php echo esc_url( kg_asset( 'pdf/<name>.pdf' ) ); ?>"`.
   Keep the `download` attribute and the `kg-footer__pdf` class exactly as they are.
   Filenames must stay exactly: `privacy-policy.pdf`, `terms-of-service.pdf`,
   `cookie-policy.pdf`, `childrens-privacy-notice.pdf` (the footer, README.txt and
   this plan all agree on these).
2. **Generate the three missing PDFs.** Source `.docx` files exist at repo root, so
   the most reliable route on this Windows machine is Word COM automation from
   PowerShell (per-file):
   ```powershell
   $word = New-Object -ComObject Word.Application
   $word.Visible = $false
   $doc = $word.Documents.Open("C:\Users\User\!!!CLAUDE\Kids Gate v1.3\TERMS-OF-SERVICE.docx")
   $doc.SaveAs("C:\Users\User\!!!CLAUDE\Kids Gate v1.3\kidsgate-theme\assets\pdf\terms-of-service.pdf", 17)  # 17 = wdFormatPDF
   $doc.Close($false); $word.Quit()
   ```
   If Word is not installed (COM object creation fails), do NOT fake it with a
   text-to-PDF hack that mangles formatting — instead leave the three PDFs absent,
   keep the href fix, and report clearly that manual export is needed.
3. **Update `assets/pdf/README.txt`** to list all four expected filenames.
4. **Verify** (see acceptance criteria).

## Edge cases found while exploring (a weaker model would miss these)

- **The dev preview lies about this bug.** `tests/preview.php` serves `/assets/*`
  directly (line 29–30), so the old root-absolute links *appear to work* if you only
  test in the preview. The fix must be verified by inspecting the rendered href —
  after the change it should contain `/kidsgate-theme/assets/pdf/` (whatever
  `get_template_directory_uri()` returns), not a bare `/assets/`.
- **`PRIVACY-POLICY.pdf` exists in two places** — repo root and
  `kidsgate-theme/assets/pdf/privacy-policy.pdf`. The theme copy is the one that
  ships. If you regenerate the privacy PDF, update **both**, or at minimum the theme
  copy, and say which you updated.
- **The source documents contain `[INSERT DATE]` placeholders** (effective date —
  see LEGAL-REVIEW-NOTES.md §D item 1). Generating PDFs will bake those placeholders
  in. That is acceptable for now (the owner knows), but the completion report must
  flag that the published PDFs still show `[INSERT DATE]`.
- **`.md` and `.docx` versions of each document coexist** and may have drifted. The
  `.docx` files were reviewed by the legal pass (LEGAL-REVIEW-NOTES mentions inline
  fixes). Spot-check one "fixed inline" item — e.g. the Privacy Policy routing rights
  requests to privacy@thekidsgate.com — in whichever source you convert, to confirm
  you're converting the post-review version. If `.md` and `.docx` disagree, the
  `.docx` is authoritative; note the drift in the report.
- **The `download` attribute** only works same-origin — fine here since the PDFs
  are served from the same host, but don't move them to a CDN URL in this task.
- **git**: `*.zip` is ignored but PDFs are not; the three new PDFs are meant to be
  committed (the existing privacy-policy.pdf already is). Word can produce PDFs
  over a few MB if the docx embeds fonts — if any PDF exceeds ~10 MB something went
  wrong; investigate rather than commit.

## Acceptance criteria

1. `php -l kidsgate-theme/footer.php` passes.
2. In the running preview, `document.querySelectorAll('.kg-footer__pdf')` yields 4
   links, every `href` ends in the right filename, and fetching each href returns
   HTTP 200 with `%PDF` as the first bytes.
3. `grep -n "assets/pdf" kidsgate-theme/footer.php` shows zero root-absolute
   (`href="/assets/`) occurrences — all four go through `kg_asset(`.
4. The four files exist in `kidsgate-theme/assets/pdf/` and each opens as a valid
   PDF (non-zero pages).
5. `assets/pdf/README.txt` lists all four filenames.
6. Completion report states: whether PDFs still contain `[INSERT DATE]`, and which
   source (`.md` or `.docx`) each PDF was generated from.
