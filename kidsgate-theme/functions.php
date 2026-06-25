<?php
/**
 * Kids Gate theme v1.3 — core setup.
 *
 * Language handling: resolved from the URL country/lang prefix set by the
 * mu-plugin (wp-content/mu-plugins/kg-routing.php), which defines
 * KG_CURRENT_COUNTRY and KG_CURRENT_LANG before WordPress routes the request.
 * Falls back to cookie / Accept-Language on the bare domain.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KG_VERSION', '1.3.3' );

require_once get_template_directory() . '/inc/config.php';

/* -------------------------------------------------------------------------
 * Language + country resolution
 * ---------------------------------------------------------------------- */

function kg_valid_langs() {
	return array( 'en', 'id', 'th' );
}

/**
 * Active country code — 'au' | 'us' | 'id' | 'th' | '' (bare domain).
 * Set by mu-plugin from the first URL segment.
 */
function kg_country() {
	return defined( 'KG_CURRENT_COUNTRY' ) ? KG_CURRENT_COUNTRY : '';
}

/**
 * Default language for a given country. Kept in sync with the mu-plugin.
 * If $country is null the current country is used.
 */
function kg_country_default_lang( $country = null ) {
	$map = array( 'au' => 'en', 'us' => 'en', 'id' => 'id', 'th' => 'th' );
	if ( null === $country ) {
		$country = kg_country();
	}
	return isset( $map[ $country ] ) ? $map[ $country ] : 'en';
}

/**
 * Active language code — 'en' | 'id' | 'th'.
 * Priority: URL prefix (KG_CURRENT_LANG) → ?lang= param → cookie → Accept-Language → 'en'.
 */
function kg_lang() {
	static $lang = null;
	if ( null !== $lang ) {
		return $lang;
	}

	// URL-prefix route: mu-plugin has already resolved the language.
	if ( kg_country() !== '' ) {
		$lang = defined( 'KG_CURRENT_LANG' ) && KG_CURRENT_LANG !== '' ? KG_CURRENT_LANG : 'en';
		return $lang;
	}

	// Bare domain fallback: query string / kg_choice cookie / legacy kg_lang cookie / browser locale.
	$valid = kg_valid_langs();

	if ( isset( $_GET['lang'] ) && in_array( $_GET['lang'], $valid, true ) ) {
		$lang = $_GET['lang'];
	} elseif ( isset( $_COOKIE['kg_choice'] ) && preg_match( '/^[a-z]{2}:([a-z]{2})$/', $_COOKIE['kg_choice'], $m ) && in_array( $m[1], $valid, true ) ) {
		// kg_choice=market:lang — set by JS whenever user manually picks market/language.
		$lang = $m[1];
	} elseif ( isset( $_COOKIE['kg_lang'] ) && in_array( $_COOKIE['kg_lang'], $valid, true ) ) {
		$lang = $_COOKIE['kg_lang'];
	} else {
		$accept = isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ? strtolower( substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5 ) ) : '';
		if ( 0 === strpos( $accept, 'id' ) || 0 === strpos( $accept, 'in' ) ) {
			$lang = 'id';
		} elseif ( 0 === strpos( $accept, 'th' ) ) {
			$lang = 'th';
		} else {
			$lang = 'en';
		}
	}
	return $lang;
}

// Persist an explicit ?lang= choice as a cookie (bare domain only).
function kg_persist_lang_cookie() {
	if ( kg_country() !== '' ) {
		return; // Language is in the URL — no cookie needed.
	}
	if ( isset( $_GET['lang'] ) && in_array( $_GET['lang'], kg_valid_langs(), true ) && ! headers_sent() ) {
		setcookie( 'kg_lang', $_GET['lang'], time() + YEAR_IN_SECONDS, '/' );
	}
}
add_action( 'init', 'kg_persist_lang_cookie' );

/**
 * Translated strings for the active language. Each file returns a nested
 * array; kg_t() walks it with dot notation, e.g. kg_t('home.hero.title').
 */
function kg_strings() {
	static $strings = null;
	if ( null === $strings ) {
		$file = get_template_directory() . '/inc/lang/' . kg_lang() . '.php';
		if ( ! file_exists( $file ) ) {
			$file = get_template_directory() . '/inc/lang/en.php';
		}
		$strings = require $file;
	}
	return $strings;
}

function kg_t( $key ) {
	$value = kg_strings();
	foreach ( explode( '.', $key ) as $part ) {
		if ( ! is_array( $value ) || ! isset( $value[ $part ] ) ) {
			return $key; // Surface missing keys instead of failing silently.
		}
		$value = $value[ $part ];
	}
	return $value;
}

// Strings are trusted theme copy and may carry inline markup (<strong>, <br>).
function kg_e( $key ) {
	echo kg_t( $key ); // phpcs:ignore WordPress.Security.EscapeOutput
}

// Array accessor for list-shaped content (cards, FAQ items, steps…).
function kg_list( $key ) {
	$value = kg_t( $key );
	return is_array( $value ) ? $value : array();
}

/* -------------------------------------------------------------------------
 * URLs and assets
 * ---------------------------------------------------------------------- */

/**
 * Generate an internal URL with the active market + language prefix.
 *
 * Both segments are always explicit:
 *   kg_url()           → /au/en/   (or /id/id/, /th/th/, etc.)
 *   kg_url('pricing')  → /au/en/pricing/
 *
 * On the bare domain (no market set) no prefix is added.
 */
function kg_url( $slug = '' ) {
	$country = kg_country();

	if ( ! $country ) {
		return '' === $slug ? home_url( '/' ) : home_url( '/' . $slug . '/' );
	}

	$prefix = '/' . $country . '/' . kg_lang();

	return '' === $slug ? home_url( $prefix . '/' ) : home_url( $prefix . '/' . $slug . '/' );
}

/**
 * URL for the current page in a different language, keeping the market unchanged.
 * Language change must NOT change currency or market.
 *
 * Falls back to ?lang= if the mu-plugin is not yet deployed.
 */
function kg_url_for_lang( $target_lang ) {
	if ( ! defined( 'KG_CURRENT_COUNTRY' ) ) {
		return add_query_arg( 'lang', $target_lang );
	}

	$country = kg_country();
	$uri     = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '/';
	$path    = trim( parse_url( $uri, PHP_URL_PATH ), '/' );

	if ( ! $country ) {
		// Bare domain: go to target lang's default market.
		$lang_market = array( 'en' => 'us', 'id' => 'id', 'th' => 'th' );
		$market      = isset( $lang_market[ $target_lang ] ) ? $lang_market[ $target_lang ] : 'us';
		return home_url( '/' . $market . '/' . $target_lang . '/' . ( $path ? $path . '/' : '' ) );
	}

	// Market stays the same; only language changes.
	return home_url( '/' . $country . '/' . $target_lang . '/' . ( $path ? $path . '/' : '' ) );
}

/**
 * URL for the current page in a different market, keeping the language unchanged.
 * Market change updates currency/pricing but keeps the same UI language.
 */
function kg_url_for_market( $target_market ) {
	$lang = kg_lang() ?: kg_country_default_lang( $target_market );
	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '/';
	$path = trim( parse_url( $uri, PHP_URL_PATH ), '/' );

	return home_url( '/' . $target_market . '/' . $lang . '/' . ( $path ? $path . '/' : '' ) );
}

function kg_asset( $path ) {
	return get_template_directory_uri() . '/assets/' . ltrim( $path, '/' );
}

/* -------------------------------------------------------------------------
 * WordPress rewrite rules for market/lang routing.
 *
 * When mu-plugin (wp-content/mu-plugins/kg-routing.php) IS deployed:
 *   It rewrites REQUEST_URI before WordPress parses, so these rules never
 *   match — they are completely harmless.
 *
 * When mu-plugin is NOT deployed (local preview, simple hosting):
 *   These rules teach WordPress how to handle /au/en/pricing/ URLs directly.
 *   KG_CURRENT_COUNTRY and KG_CURRENT_LANG are set via parse_request.
 * ---------------------------------------------------------------------- */

function kg_register_rewrite_rules() {
	if ( defined( 'KG_CURRENT_COUNTRY' ) ) {
		return; // mu-plugin is active — skip.
	}
	$c = 'au|us|id|th';
	$l = 'en|id|th';

	// /market/lang/page-slug/
	add_rewrite_rule(
		'^(' . $c . ')/(' . $l . ')/(.+?)/?$',
		'index.php?pagename=$matches[3]&kg_market=$matches[1]&kg_lang_code=$matches[2]',
		'top'
	);
	// /market/lang/ → front page
	add_rewrite_rule(
		'^(' . $c . ')/(' . $l . ')/?$',
		'index.php?kg_market=$matches[1]&kg_lang_code=$matches[2]',
		'top'
	);
	// /market/ → 301 to /market/default-lang/
	add_rewrite_rule(
		'^(' . $c . ')/?$',
		'index.php?kg_market=$matches[1]&kg_lang_code=',
		'top'
	);
}
add_action( 'init', 'kg_register_rewrite_rules' );

add_filter( 'query_vars', function( $vars ) {
	$vars[] = 'kg_market';
	$vars[] = 'kg_lang_code';
	return $vars;
} );

// Fires before any template is chosen — defines routing constants and
// handles country-only → lang redirect.
add_action( 'parse_request', function( $wp ) {
	if ( defined( 'KG_CURRENT_COUNTRY' ) ) {
		return; // mu-plugin already set these.
	}

	$market = isset( $wp->query_vars['kg_market'] )    ? sanitize_key( $wp->query_vars['kg_market'] )    : '';
	$lang   = isset( $wp->query_vars['kg_lang_code'] ) ? sanitize_key( $wp->query_vars['kg_lang_code'] ) : '';

	if ( ! $market ) {
		define( 'KG_CURRENT_COUNTRY', '' );
		define( 'KG_CURRENT_LANG',    '' );
		return;
	}

	// Country-only URL (/au/) → 301 to /au/en/.
	if ( $market && $lang === '' ) {
		$default = kg_country_default_lang( $market );
		wp_redirect( home_url( '/' . $market . '/' . $default . '/' ), 301 );
		exit;
	}

	define( 'KG_CURRENT_COUNTRY', $market );
	define( 'KG_CURRENT_LANG',    $lang );
}, 1 );

// One-time rewrite flush so the new rules take effect without a manual
// Admin → Permalinks → Save. Runs once then sets a flag.
add_action( 'init', function() {
	if ( ! get_option( 'kg_rewrite_v2_flushed' ) ) {
		flush_rewrite_rules( false );
		update_option( 'kg_rewrite_v2_flushed', 1 );
	}
}, 99 );

function kg_nav_is_active( $slug ) {
	// When the mu-plugin is active, REQUEST_URI is already stripped of the
	// market/lang prefix. When using rewrite rules, check the query var instead.
	if ( defined( 'KG_CURRENT_COUNTRY' ) && KG_CURRENT_COUNTRY !== '' ) {
		// Rewrite-rules path: use the pagename query var to find the active slug.
		$pagename = get_query_var( 'pagename', '' );
		if ( '' === $slug ) {
			return $pagename === '';
		}
		return rtrim( $pagename, '/' ) === rtrim( $slug, '/' );
	}
	$uri          = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '/';
	$current_path = rtrim( strtok( $uri, '?' ), '/' ) . '/';
	if ( '' === $slug ) {
		return $current_path === '/';
	}
	$target_path = '/' . trim( $slug, '/' ) . '/';
	return $current_path === $target_path;
}

function kg_nav_active_attr( $slug ) {
	if ( kg_nav_is_active( $slug ) ) {
		echo ' aria-current="page" class="kg-active"';
	}
}

/**
 * Output a canonical <link> tag for the current page.
 * On a market/lang page: /market/lang/slug/
 * On the bare domain:    kidsgate.ai/
 */
function kg_canonical_tag() {
	$country = kg_country();
	$uri     = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '/';
	$path    = trim( parse_url( $uri, PHP_URL_PATH ), '/' );
	if ( $country ) {
		$href = home_url( '/' . $country . '/' . kg_lang() . '/' . ( $path ? $path . '/' : '' ) );
	} else {
		$href = home_url( '/' );
	}
	printf( "\t" . '<link rel="canonical" href="%s">' . "\n", esc_url( $href ) );
}

/**
 * Output hreflang tags for all 12 market × language combinations.
 * URL format is always /market/lang/slug — both segments explicit.
 */
function kg_hreflang_tags() {
	$variants = array(
		array( 'market' => 'au', 'lang' => 'en', 'hreflang' => 'en-AU' ),
		array( 'market' => 'au', 'lang' => 'id', 'hreflang' => 'id-AU' ),
		array( 'market' => 'au', 'lang' => 'th', 'hreflang' => 'th-AU' ),
		array( 'market' => 'us', 'lang' => 'en', 'hreflang' => 'en-US' ),
		array( 'market' => 'us', 'lang' => 'id', 'hreflang' => 'id-US' ),
		array( 'market' => 'us', 'lang' => 'th', 'hreflang' => 'th-US' ),
		array( 'market' => 'id', 'lang' => 'en', 'hreflang' => 'en-ID' ),
		array( 'market' => 'id', 'lang' => 'id', 'hreflang' => 'id-ID' ),
		array( 'market' => 'id', 'lang' => 'th', 'hreflang' => 'th-ID' ),
		array( 'market' => 'th', 'lang' => 'en', 'hreflang' => 'en-TH' ),
		array( 'market' => 'th', 'lang' => 'id', 'hreflang' => 'id-TH' ),
		array( 'market' => 'th', 'lang' => 'th', 'hreflang' => 'th-TH' ),
	);

	// Current page slug (already stripped of market/lang by the mu-plugin).
	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '/';
	$path = trim( parse_url( $uri, PHP_URL_PATH ), '/' );
	$slug = $path ? $path . '/' : '';
	$base = home_url( '/' );

	foreach ( $variants as $v ) {
		printf(
			"\t" . '<link rel="alternate" hreflang="%s" href="%s">' . "\n",
			esc_attr( $v['hreflang'] ),
			esc_url( $base . $v['market'] . '/' . $v['lang'] . '/' . $slug )
		);
	}
	printf(
		"\t" . '<link rel="alternate" hreflang="x-default" href="%s">' . "\n",
		esc_url( home_url( '/' ) )
	);
}

/**
 * Demo video URL: Customizer setting wins; otherwise the bundled file in
 * assets/video/gate-demo.mp4 is used when present.
 */
function kg_video_url() {
	$custom = get_theme_mod( 'kg_demo_video_url', '' );
	if ( $custom ) {
		return esc_url( $custom );
	}
	if ( file_exists( get_template_directory() . '/assets/video/gate-demo.mp4' ) ) {
		return kg_asset( 'video/gate-demo.mp4' );
	}
	return '';
}

/* -------------------------------------------------------------------------
 * Theme setup and assets
 * ---------------------------------------------------------------------- */

function kg_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
}
add_action( 'after_setup_theme', 'kg_setup' );

function kg_enqueue_assets() {
	// Baloo 2 (display) + Nunito Sans (body) with Mitr + Anuphan covering Thai script.
	wp_enqueue_style(
		'kg-fonts',
		'https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Nunito+Sans:ital,opsz,wght@0,6..12,400;0,6..12,600;0,6..12,700;0,6..12,800;1,6..12,400&family=Mitr:wght@500;600;700&family=Anuphan:wght@400;600;700&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'kg-main', kg_asset( 'css/main.css' ), array( 'kg-fonts' ), KG_VERSION );
	wp_enqueue_script( 'kg-main', kg_asset( 'js/main.js' ), array(), KG_VERSION, true );

	if ( is_page( 'pricing' ) ) {
		wp_enqueue_script( 'kg-pricing', kg_asset( 'js/pricing.js' ), array(), KG_VERSION, true );
		$p = kg_pricing_for_country();
		wp_localize_script( 'kg-pricing', 'KG_PRICING', array(
			'rates'   => $p,
			'lang'    => kg_lang(),
			'country' => kg_country(),
			'strings' => array(
				'perMonth'    => kg_t( 'pricing.calc.per_month' ),
				'billedYear'  => kg_t( 'pricing.calc.billed_yearly' ),
				'child'       => kg_t( 'pricing.calc.child' ),
				'fullRate'    => kg_t( 'pricing.calc.full_rate' ),
				'addlRate'    => kg_t( 'pricing.calc.addl_rate' ),
				'oneSubject'  => kg_t( 'pricing.calc.one_subject' ),
				'twoSubjects' => kg_t( 'pricing.calc.two_subjects' ),
			),
		) );
	}

	if ( is_page( 'leaderboard' ) ) {
		wp_enqueue_script( 'kg-leaderboard', kg_asset( 'js/leaderboard.js' ), array(), KG_VERSION, true );
	}

	// support.js powers the guided helper and form validation globally.
	wp_enqueue_script( 'kg-support', kg_asset( 'js/support.js' ), array(), KG_VERSION, true );
	wp_localize_script( 'kg-support', 'KG_DATA', array(
		'support_email' => kg_support_email(),
	) );
}
add_action( 'wp_enqueue_scripts', 'kg_enqueue_assets' );

// GA4 events are emitted by main.js through dataLayer.
function kg_gtm_head() {
	$gtm = get_theme_mod( 'kg_gtm_id', '' );
	if ( ! $gtm ) {
		return;
	}
	?>
	<script>window.dataLayer = window.dataLayer || [];</script>
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?php echo esc_js( $gtm ); ?>');</script>
	<?php
}
add_action( 'wp_head', 'kg_gtm_head' );

function kg_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'kg_settings', array(
		'title'    => 'Kids Gate Settings',
		'priority' => 30,
	) );

	$fields = array(
		'kg_gtm_id'         => 'Google Tag Manager ID (GTM-XXXXXXX)',
		'kg_demo_video_url' => 'Demo video URL (Media Library)',
		'kg_app_store_url'  => 'App Store URL',
		'kg_play_store_url' => 'Play Store URL',
		'kg_support_email'  => 'Support team email',
		'kg_stat_learners'  => 'Stat: learners (e.g. 12,000+)',
		'kg_stat_questions' => 'Stat: questions answered this week',
	);
	foreach ( $fields as $id => $label ) {
		$wp_customize->add_setting( $id, array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( $id, array( 'label' => $label, 'section' => 'kg_settings', 'type' => 'text' ) );
	}
}
add_action( 'customize_register', 'kg_customize_register' );

/**
 * App store URLs — keyed by country. All pointing to the same app listing
 * until separate regional listings are created. Customizer overrides win.
 */
function kg_store_url( $store ) {
	$urls = array(
		'au' => array(
			'app'  => 'https://apps.apple.com/bg/app/kids-gate-indonesia/id1591630141',
			'play' => 'https://play.google.com/store/apps/details?id=com.thekidsgate.gameapp&hl=en',
		),
		'us' => array(
			'app'  => 'https://apps.apple.com/bg/app/kids-gate-indonesia/id1591630141',
			'play' => 'https://play.google.com/store/apps/details?id=com.thekidsgate.gameapp&hl=en',
		),
		'id' => array(
			'app'  => 'https://apps.apple.com/bg/app/kids-gate-indonesia/id1591630141',
			'play' => 'https://play.google.com/store/apps/details?id=com.thekidsgate.gameapp&hl=en',
		),
		'th' => array(
			'app'  => 'https://apps.apple.com/bg/app/kids-gate-indonesia/id1591630141',
			'play' => 'https://play.google.com/store/apps/details?id=com.thekidsgate.gameapp&hl=en',
		),
	);

	$country = kg_country() ?: 'au';
	$url     = isset( $urls[ $country ][ $store ] ) ? $urls[ $country ][ $store ] : '#';
	$mod     = get_theme_mod( 'play' === $store ? 'kg_play_store_url' : 'kg_app_store_url', '' );
	return $mod ? esc_url( $mod ) : $url;
}

/**
 * Social media links — keyed by country so Indonesian visitors always see
 * Indonesian social accounts regardless of their UI language choice.
 */
function kg_social_links() {
	$all = array(
		'id' => array(
			'instagram' => 'https://www.instagram.com/kidsgate.id/',
			'tiktok'    => 'https://www.tiktok.com/@kidsgate.id',
			'facebook'  => 'https://www.facebook.com/profile.php?id=61578094916488',
		),
		'au' => array( 'instagram' => '', 'tiktok' => '', 'facebook' => '' ),
		'us' => array( 'instagram' => '', 'tiktok' => '', 'facebook' => '' ),
		'th' => array( 'instagram' => '', 'tiktok' => '', 'facebook' => '' ),
	);

	$country = kg_country();
	if ( $country && isset( $all[ $country ] ) ) {
		return $all[ $country ];
	}
	// Bare domain: map by language as a best-effort.
	$lang_map = array( 'id' => 'id', 'th' => 'th', 'en' => 'au' );
	$key      = isset( $lang_map[ kg_lang() ] ) ? $lang_map[ kg_lang() ] : 'au';
	return isset( $all[ $key ] ) ? $all[ $key ] : $all['au'];
}

/* -------------------------------------------------------------------------
 * Turnkey install: create the page set on theme activation
 * ---------------------------------------------------------------------- */

function kg_create_pages() {
	$pages = array(
		'home'         => 'Home',
		'how-it-works' => 'How It Works',
		'features'     => 'Features',
		'parents'      => 'For Parents',
		'pricing'      => 'Pricing',
		'schools'      => 'For Schools & Teachers',
		'leaderboard'  => 'Leaderboard',
		'about'        => 'About Us',
		'sponsors'     => 'Sponsors',
		'support'      => 'Support',
		'payment'      => 'Payment',
	);

	foreach ( $pages as $slug => $title ) {
		if ( ! get_page_by_path( $slug ) ) {
			wp_insert_post( array(
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '<!-- Rendered by the Kids Gate theme template page-' . $slug . '.php -->',
			) );
		}
	}

	$front = get_page_by_path( 'home' );
	if ( $front ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front->ID );
	}

	if ( ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'kg_create_pages' );

/* -------------------------------------------------------------------------
 * Shared render helpers
 * ---------------------------------------------------------------------- */

// Primary CTA button. $event feeds the GA4 plan (hero_cta_click etc.).
function kg_cta( $label_key, $url, $event = '', $classes = 'kg-btn kg-btn--primary' ) {
	printf(
		'<a class="%s" href="%s"%s><span>%s</span><svg class="kg-btn__arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg></a>',
		esc_attr( $classes ),
		esc_url( $url ),
		$event ? ' data-kg-event="' . esc_attr( $event ) . '"' : '',
		kg_t( $label_key ) // phpcs:ignore WordPress.Security.EscapeOutput
	);
}

// Section heading block: kicker pill + title + optional lede.
function kg_section_head( $base_key, $center = true, $tone = '' ) {
	$kicker = kg_t( $base_key . '.kicker' );
	$title  = kg_t( $base_key . '.title' );
	$lede   = kg_t( $base_key . '.lede' );
	$cls    = 'kg-section-head' . ( $center ? ' kg-section-head--center' : '' ) . ( $tone ? ' kg-section-head--' . $tone : '' );
	echo '<div class="' . esc_attr( $cls ) . '" data-kg-reveal>';
	if ( $kicker !== $base_key . '.kicker' ) {
		echo '<span class="kg-kicker">' . $kicker . '</span>';
	}
	if ( $title !== $base_key . '.title' ) {
		echo '<h2 class="kg-h2">' . $title . '</h2>';
	}
	if ( $lede !== $base_key . '.lede' ) {
		echo '<p class="kg-lede">' . $lede . '</p>';
	}
	echo '</div>';
}

// Inline SVG flags. Emoji flags don't render on Windows/Chrome so we draw crisp SVGs.
function kg_flag( $code ) {
	static $n = 0;
	$uid = 'kgflag' . ( ++$n );
	switch ( $code ) {
		case 'id':
			return '<svg class="kg-flag" viewBox="0 0 60 40" aria-hidden="true" focusable="false">'
				. '<clipPath id="' . $uid . '"><rect width="60" height="40" rx="5"/></clipPath>'
				. '<g clip-path="url(#' . $uid . ')"><rect width="60" height="40" fill="#fff"/>'
				. '<rect width="60" height="20" fill="#E70011"/></g></svg>';
		case 'th':
			return '<svg class="kg-flag" viewBox="0 0 60 40" aria-hidden="true" focusable="false">'
				. '<clipPath id="' . $uid . '"><rect width="60" height="40" rx="5"/></clipPath>'
				. '<g clip-path="url(#' . $uid . ')"><rect width="60" height="40" fill="#A51931"/>'
				. '<rect y="6.67" width="60" height="26.66" fill="#F4F5F8"/>'
				. '<rect y="13.33" width="60" height="13.34" fill="#2D2A4A"/></g></svg>';
		case 'au':
			// Australia: blue field with Union Jack and stars (simplified).
			return '<svg class="kg-flag" viewBox="0 0 60 40" aria-hidden="true" focusable="false">'
				. '<clipPath id="' . $uid . '"><rect width="60" height="40" rx="5"/></clipPath>'
				. '<g clip-path="url(#' . $uid . ')"><rect width="60" height="40" fill="#00008B"/>'
				. '<path d="M0 0 30 20M30 0 0 20" stroke="#fff" stroke-width="6"/>'
				. '<path d="M0 0 30 20M30 0 0 20" stroke="#C8102E" stroke-width="4"/>'
				. '<path d="M15 0v20M0 10h30" stroke="#fff" stroke-width="9"/>'
				. '<path d="M15 0v20M0 10h30" stroke="#C8102E" stroke-width="6"/></g></svg>';
		case 'us':
			return '<svg class="kg-flag" viewBox="0 0 60 40" aria-hidden="true" focusable="false">'
				. '<clipPath id="' . $uid . '"><rect width="60" height="40" rx="5"/></clipPath>'
				. '<g clip-path="url(#' . $uid . ')">'
				. '<rect width="60" height="40" fill="#B22234"/>'
				. '<rect y="3.08" width="60" height="3.07" fill="#fff"/>'
				. '<rect y="9.23" width="60" height="3.07" fill="#fff"/>'
				. '<rect y="15.38" width="60" height="3.07" fill="#fff"/>'
				. '<rect y="21.54" width="60" height="3.07" fill="#fff"/>'
				. '<rect y="27.69" width="60" height="3.07" fill="#fff"/>'
				. '<rect y="33.85" width="60" height="3.07" fill="#fff"/>'
				. '<rect width="24" height="21.54" fill="#3C3B6E"/></g></svg>';
		case 'en':
		default:
			return '<svg class="kg-flag" viewBox="0 0 60 40" aria-hidden="true" focusable="false">'
				. '<clipPath id="' . $uid . '"><rect width="60" height="40" rx="5"/></clipPath>'
				. '<g clip-path="url(#' . $uid . ')"><rect width="60" height="40" fill="#012169"/>'
				. '<path d="M0 0 60 40M60 0 0 40" stroke="#fff" stroke-width="8"/>'
				. '<path d="M0 0 60 40M60 0 0 40" stroke="#C8102E" stroke-width="5"/>'
				. '<path d="M30 0v40M0 20h60" stroke="#fff" stroke-width="13"/>'
				. '<path d="M30 0v40M0 20h60" stroke="#C8102E" stroke-width="8"/></g></svg>';
	}
}

// Language switcher — changes language only, market and pricing stay the same.
function kg_language_switcher( $id_suffix = '' ) {
	$langs = array(
		'en' => 'English',
		'id' => 'Bahasa Indonesia',
		'th' => 'ไทย',
	);
	$current = kg_lang();
	$market  = kg_country();
	// Pre-compute the market that will be in each choice value.
	$lang_market = array( 'en' => 'us', 'id' => 'id', 'th' => 'th' );
	?>
	<div class="kg-lang" data-kg-lang>
		<button class="kg-lang__btn" type="button" aria-expanded="false" aria-haspopup="true" aria-controls="kg-lang-menu<?php echo esc_attr( $id_suffix ); ?>">
			<?php echo kg_flag( $current ); // phpcs:ignore ?>
			<span class="kg-lang__label"><?php echo esc_html( $langs[ $current ] ); ?></span>
			<svg width="12" height="12" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
		</button>
		<ul class="kg-lang__menu" id="kg-lang-menu<?php echo esc_attr( $id_suffix ); ?>" role="menu">
			<?php foreach ( $langs as $code => $label ) :
				$choice_market = $market ?: ( isset( $lang_market[ $code ] ) ? $lang_market[ $code ] : 'us' );
			?>
				<li role="none">
					<a role="menuitem"
					   data-kg-event="language_switch"
					   data-kg-choice="<?php echo esc_attr( $choice_market . ':' . $code ); ?>"
					   class="<?php echo $code === $current ? 'is-active' : ''; ?>"
					   href="<?php echo esc_url( kg_url_for_lang( $code ) ); ?>">
						<?php echo kg_flag( $code ); // phpcs:ignore ?> <?php echo esc_html( $label ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
}

// Market switcher — changes market/currency, language stays the same.
function kg_market_switcher( $id_suffix = '' ) {
	$markets = array(
		'au' => array( 'label' => 'Australia',     'currency' => 'AUD' ),
		'us' => array( 'label' => 'United States', 'currency' => 'USD' ),
		'id' => array( 'label' => 'Indonesia',     'currency' => 'IDR' ),
		'th' => array( 'label' => 'Thailand',      'currency' => 'THB' ),
	);
	$current = kg_country();
	$lang    = kg_lang() ?: 'en';
	?>
	<div class="kg-market" data-kg-market>
		<button class="kg-market__btn" type="button" aria-expanded="false" aria-haspopup="true" aria-controls="kg-market-menu<?php echo esc_attr( $id_suffix ); ?>">
			<?php if ( $current && isset( $markets[ $current ] ) ) : ?>
				<?php echo kg_flag( $current ); // phpcs:ignore ?>
				<span class="kg-market__label"><?php echo esc_html( $markets[ $current ]['currency'] ); ?></span>
			<?php else : ?>
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.8"/><path d="M2 12h20M12 2c-2.76 3.56-4 6.8-4 10s1.24 6.44 4 10M12 2c2.76 3.56 4 6.8 4 10s-1.24 6.44-4 10" stroke="currentColor" stroke-width="1.8"/></svg>
				<span class="kg-market__label">Region</span>
			<?php endif; ?>
			<svg width="12" height="12" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
		</button>
		<ul class="kg-market__menu" id="kg-market-menu<?php echo esc_attr( $id_suffix ); ?>" role="menu">
			<?php foreach ( $markets as $code => $info ) :
				$target_lang = $lang; // keep current language
			?>
				<li role="none">
					<a role="menuitem"
					   data-kg-event="market_switch"
					   data-kg-choice="<?php echo esc_attr( $code . ':' . $target_lang ); ?>"
					   class="<?php echo $code === $current ? 'is-active' : ''; ?>"
					   href="<?php echo esc_url( kg_url_for_market( $code ) ); ?>">
						<?php echo kg_flag( $code ); // phpcs:ignore ?>
						<span><?php echo esc_html( $info['label'] ); ?></span>
						<small><?php echo esc_html( $info['currency'] ); ?></small>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
}

// Region selector banner — shown on the bare domain for unknown countries.
function kg_region_banner() {
	if ( kg_country() ) {
		return;
	}
	// Keep current language when switching region so e.g. English → Indonesia
	// goes to /id/en/... rather than forcing the user into Bahasa Indonesia.
	$current_lang = kg_lang() ?: 'en';
	$options = array(
		array( 'market' => 'au', 'label' => 'Australia',     'currency' => 'AUD' ),
		array( 'market' => 'us', 'label' => 'United States', 'currency' => 'USD' ),
		array( 'market' => 'id', 'label' => 'Indonesia',     'currency' => 'IDR' ),
		array( 'market' => 'th', 'label' => 'Thailand',      'currency' => 'THB' ),
	);
	echo '<div class="kg-region-bar" data-kg-region-bar role="complementary" aria-label="Choose your region">';
	echo '<p class="kg-region-bar__msg">Choose your region to see local pricing:</p>';
	echo '<div class="kg-region-bar__options">';
	foreach ( $options as $opt ) {
		printf(
			'<a class="kg-region-bar__btn" href="%s" data-kg-choice="%s">%s <strong>%s</strong> <small>%s</small></a>',
			esc_url( kg_url_for_market( $opt['market'] ) ),
			esc_attr( $opt['market'] . ':' . $current_lang ),
			kg_flag( $opt['market'] ), // phpcs:ignore
			esc_html( $opt['label'] ),
			esc_html( $opt['currency'] )
		);
	}
	echo '</div></div>';
}

// App store badge pair (final CTA, download page, footer).
function kg_store_badges() {
	?>
	<div class="kg-store-badges">
		<a class="kg-store-badge" href="<?php echo esc_url( kg_store_url( 'app' ) ); ?>" data-kg-event="app_store_click">
			<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.05 12.54c-.03-2.89 2.36-4.27 2.47-4.34-1.35-1.97-3.44-2.24-4.18-2.27-1.78-.18-3.47 1.05-4.37 1.05-.9 0-2.29-1.02-3.77-1-1.94.03-3.72 1.13-4.72 2.86-2.01 3.49-.51 8.66 1.45 11.49.96 1.39 2.1 2.94 3.6 2.88 1.45-.06 2-.93 3.75-.93s2.24.93 3.77.9c1.56-.03 2.55-1.41 3.5-2.8 1.1-1.61 1.56-3.17 1.58-3.25-.03-.02-3.04-1.17-3.08-4.59zM14.16 4.06c.8-.97 1.34-2.31 1.19-3.66-1.15.05-2.55.77-3.38 1.74-.74.85-1.39 2.22-1.22 3.53 1.29.1 2.6-.65 3.41-1.61z"/></svg>
			<span><small><?php kg_e( 'common.store_on' ); ?></small><strong>App Store</strong></span>
		</a>
		<a class="kg-store-badge" href="<?php echo esc_url( kg_store_url( 'play' ) ); ?>" data-kg-event="play_store_click">
			<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3.6 1.8c-.34.36-.54.92-.54 1.64v17.1c0 .73.2 1.29.55 1.64l.09.08 9.58-9.58v-.22L3.7 1.72l-.1.08zm12.87 12.88-3.19-3.2v-.22l3.2-3.2.07.05 3.78 2.15c1.08.61 1.08 1.61 0 2.23l-3.78 2.15-.08.04zm-.08-6.7L13.2 11.2 3.6 1.6c.36-.38.94-.42 1.6-.05l11.19 6.43zM3.6 22.4l9.6-9.6 3.19 3.2L5.2 22.44c-.66.37-1.25.33-1.6-.05z"/></svg>
			<span><small><?php kg_e( 'common.store_get' ); ?></small><strong>Google Play</strong></span>
		</a>
	</div>
	<?php
}

// Reusable FAQ accordion. $items: array of ['q' => ..., 'a' => ...].
function kg_faq_accordion( $items, $context = 'faq' ) {
	if ( empty( $items ) ) {
		return;
	}
	echo '<div class="kg-faq" data-kg-faq data-kg-faq-context="' . esc_attr( $context ) . '">';
	foreach ( $items as $i => $item ) {
		$id = esc_attr( $context . '-' . $i );
		?>
		<div class="kg-faq__item" data-kg-reveal style="--kg-delay:<?php echo (int) ( $i * 60 ); ?>ms" data-kg-faq-text="<?php echo esc_attr( strtolower( wp_strip_all_tags( $item['q'] . ' ' . $item['a'] ) ) ); ?>">
			<h3 class="kg-faq__q">
				<button type="button" aria-expanded="false" aria-controls="kg-faq-panel-<?php echo $id; ?>" id="kg-faq-btn-<?php echo $id; ?>">
					<span><?php echo $item['q']; // phpcs:ignore ?></span>
					<svg class="kg-faq__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/></svg>
				</button>
			</h3>
			<div class="kg-faq__a" id="kg-faq-panel-<?php echo $id; ?>" role="region" aria-labelledby="kg-faq-btn-<?php echo $id; ?>" hidden>
				<div class="kg-faq__a-inner"><?php echo wpautop( $item['a'] ); // phpcs:ignore ?></div>
			</div>
		</div>
		<?php
	}
	echo '</div>';
}

// Recursively resolve {placeholder} tokens in every answer across the helper decision tree.
function kg_helper_prepare_nodes( $nodes, $replacements ) {
	$out = array();
	foreach ( $nodes as $node ) {
		if ( isset( $node['answer'] ) ) {
			$node['answer'] = strtr( $node['answer'], $replacements );
		}
		if ( isset( $node['children'] ) && is_array( $node['children'] ) ) {
			$node['children'] = kg_helper_prepare_nodes( $node['children'], $replacements );
		}
		$out[] = $node;
	}
	return $out;
}

// Rule-based guided support helper. Rendered globally from footer.php.
function kg_render_helper() {
	$helper = kg_t( 'support.helper' );
	if ( empty( $helper ) || ! is_array( $helper ) ) {
		return;
	}

	$replacements = array(
		'{schools_url}'   => esc_url( kg_url( 'schools' ) ),
		'{pricing_url}'   => esc_url( kg_url( 'pricing' ) ),
		'{parents_url}'   => esc_url( kg_url( 'parents' ) ),
		'{support_url}'   => esc_url( kg_url( 'support' ) ),
		'{support_email}' => esc_html( kg_support_email() ),
	);

	$helper_data = array(
		'greeting'         => $helper['greeting'],
		'restart'          => $helper['restart'],
		'helpful_q'        => $helper['helpful_q'],
		'helpful_yes'      => $helper['helpful_yes'],
		'helpful_no'       => $helper['helpful_no'],
		'back'             => $helper['back'],
		'no_help'          => $helper['no_help'],
		'no_help_cta'      => $helper['no_help_cta'],
		'form_cta'         => $helper['form_cta'],
		'support_url'      => esc_url( kg_url( 'support' ) ),
		'support_form_url' => esc_url( kg_url( 'support' ) . '#support-form' ),
		'nodes'            => kg_helper_prepare_nodes( $helper['nodes'], $replacements ),
	);
	?>
	<script type="application/json" id="kg-helper-data"><?php echo wp_json_encode( $helper_data ); ?></script>

	<button class="kg-helper-fab" type="button" data-kg-helper-fab aria-expanded="false" aria-controls="kg-helper">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 22c5.5 0 10-4.5 10-10S17.5 2 12 2 2 6.5 2 12c0 1.8.5 3.5 1.3 5L2 22l5-1.3c1.5.8 3.2 1.3 5 1.3z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
		<span class="kg-helper-fab__label"><?php echo $helper['fab_label']; // phpcs:ignore ?></span>
	</button>
	<div class="kg-helper" id="kg-helper" data-kg-helper role="dialog" aria-label="<?php echo esc_attr( $helper['title'] ); ?>">
		<div class="kg-helper__head">
			<img src="<?php echo esc_url( kg_asset( 'img/kg-logo.png' ) ); ?>" alt="" width="30" height="31">
			<strong><?php echo $helper['title']; // phpcs:ignore ?></strong>
			<button class="kg-helper__close" type="button" aria-label="Close">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>
			</button>
		</div>
		<div class="kg-helper__body"></div>
	</div>
	<?php
}

// First character of a (possibly multibyte) name, for avatar initials.
function kg_initial( $name ) {
	if ( function_exists( 'mb_substr' ) && function_exists( 'mb_strtoupper' ) ) {
		return mb_strtoupper( mb_substr( $name, 0, 1, 'UTF-8' ), 'UTF-8' );
	}
	if ( preg_match( '/./us', $name, $m ) ) {
		return strtoupper( $m[0] );
	}
	return strtoupper( substr( $name, 0, 1 ) );
}

// Trust strip: small reassurance chips under hero / CTA blocks.
function kg_trust_chips( $key = 'common.trust_chips' ) {
	$chips = kg_list( $key );
	if ( empty( $chips ) ) {
		return;
	}
	echo '<ul class="kg-chips" data-kg-reveal>';
	foreach ( $chips as $chip ) {
		$is_cross = is_array( $chip ) && ! empty( $chip['cross'] );
		$label    = is_array( $chip ) ? $chip['text'] : $chip;
		if ( $is_cross ) {
			$icon = '<svg class="kg-chip__cross" width="13" height="13" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="3.2" stroke-linecap="round"/></svg>';
			echo '<li class="kg-chip kg-chip--cross">' . $icon . $label . '</li>'; // phpcs:ignore
		} else {
			$icon = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>';
			echo '<li class="kg-chip">' . $icon . $label . '</li>'; // phpcs:ignore
		}
	}
	echo '</ul>';
}
