/**
 * Kids Gate — Cloudflare Worker.
 *
 * Fires only on the bare root (kidsgate.ai/). All other paths pass through.
 *
 * Priority order:
 *   1. User's saved choice (kg_choice cookie) — manual always wins.
 *   2. Cloudflare geo-IP for the 4 supported markets.
 *   3. Unknown country — no redirect, show the region selector page.
 *
 * HOW TO DEPLOY
 * -------------
 * 1. Cloudflare dashboard → Workers & Pages → Create Worker.
 * 2. Paste this script, save and deploy.
 * 3. Add a route for the bare root ONLY:
 *      Route: kidsgate.ai/       (NOT kidsgate.ai/*)
 *    Workers & Pages → your Worker → Settings → Triggers → Add Route.
 *
 * Cookie format: kg_choice=market:lang   e.g.  kg_choice=au:en
 * Set by the site's JS whenever the user manually picks a market or language.
 */

const MARKET_MAP = {
  AU: { market: 'au', lang: 'en' },
  US: { market: 'us', lang: 'en' },
  NZ: { market: 'nz', lang: 'en' },
  SG: { market: 'sg', lang: 'en' },
  ID: { market: 'id', lang: 'id' },
  TH: { market: 'th', lang: 'th' },
  IN: { market: 'in', lang: 'en' },
  PH: { market: 'ph', lang: 'en' },
  KH: { market: 'kh', lang: 'en' },
  VN: { market: 'vn', lang: 'en' },
  // Extend here as more markets are added.
  // Unknown ISO codes fall through to the selector (no redirect).
};

const VALID_MARKETS = new Set(['au', 'us', 'nz', 'sg', 'id', 'th', 'in', 'ph', 'kh', 'vn']);
const VALID_LANGS   = new Set(['en', 'id', 'th', 'zh']);

export default {
  async fetch(request) {
    const url = new URL(request.url);

    // Only intercept the bare root — all country-prefixed pages pass through.
    if (url.pathname !== '/') {
      return fetch(request);
    }

    // 1. Check for a saved user choice in the kg_choice cookie.
    const cookieHeader = request.headers.get('Cookie') || '';
    const cookieMatch  = cookieHeader.match(/\bkg_choice=([a-z]{2}):([a-z]{2})\b/);
    if (cookieMatch) {
      const market = cookieMatch[1];
      const lang   = cookieMatch[2];
      if (VALID_MARKETS.has(market) && VALID_LANGS.has(lang)) {
        return Response.redirect(url.origin + '/' + market + '/' + lang + '/', 302);
      }
    }

    // 2. Geo-detect via Cloudflare's CF-IPCountry header.
    const countryCode = request.cf?.country;
    const dest        = MARKET_MAP[countryCode];
    if (dest) {
      return Response.redirect(url.origin + '/' + dest.market + '/' + dest.lang + '/', 302);
    }

    // 3. Unknown country — serve the page as-is; the theme shows a region selector.
    return fetch(request);
  },
};
