<?php
/**
 * Kids Gate theme v1.3 — core setup.
 *
 * Language handling: one page set serves EN / ID / TH. The active language
 * resolves from ?lang= → cookie → browser locale → English. For production
 * /en /id /th URL paths, pair with Polylang or WPML — every string already
 * lives in inc/lang/{code}.php so the migration is mechanical.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KG_VERSION', '1.3.0' );

require_once get_template_directory() . '/inc/config.php';

/* -------------------------------------------------------------------------
 * Language resolution
 * ---------------------------------------------------------------------- */

function kg_valid_langs() {
	return array( 'en', 'id', 'th' );
}

function kg_lang() {
	static $lang = null;
	if ( null !== $lang ) {
		return $lang;
	}
	$valid = kg_valid_langs();

	if ( isset( $_GET['lang'] ) && in_array( $_GET['lang'], $valid, true ) ) {
		$lang = $_GET['lang'];
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

// Persist an explicit ?lang= choice before output starts.
function kg_persist_lang_cookie() {
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

function kg_url( $slug = '' ) {
	return '' === $slug ? home_url( '/' ) : home_url( '/' . $slug . '/' );
}

function kg_asset( $path ) {
	return get_template_directory_uri() . '/assets/' . ltrim( $path, '/' );
}

function kg_nav_is_active( $slug ) {
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
 * Demo video URL: Customizer setting wins; otherwise the bundled file in
 * assets/video/gate-demo.mp4 is used when present. For lighter theme zips,
 * upload the video to the Media Library and set the URL in the Customizer.
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
		$p = kg_pricing_for_lang();
		wp_localize_script( 'kg-pricing', 'KG_PRICING', array(
			'rates'   => $p,
			'lang'    => kg_lang(),
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

	// support.js powers the guided helper (rendered globally in the footer) as
	// well as the support/schools form validation and FAQ filtering. It is
	// loaded on every page so the quick-help launcher works site-wide; the
	// form/FAQ logic no-ops gracefully where those elements are absent.
	wp_enqueue_script( 'kg-support', kg_asset( 'js/support.js' ), array(), KG_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'kg_enqueue_assets' );

// GA4 events are emitted by main.js through dataLayer; the GTM container
// activates once its ID is set in Customizer → Kids Gate Settings.
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

function kg_store_url( $store ) {
	// Per-language store URLs. EN and TH links not live yet — fall back to '#'.
	$urls = array(
		'id' => array(
			'app'  => 'https://apps.apple.com/bg/app/kids-gate-indonesia/id1591630141',
			'play' => 'https://play.google.com/store/apps/details?id=com.thekidsgate.gameapp&hl=en',
		),
		'en' => array( 'app' => '#', 'play' => '#' ),
		'th' => array( 'app' => '#', 'play' => '#' ),
	);
	$lang = kg_lang();
	$url  = isset( $urls[ $lang ][ $store ] ) ? $urls[ $lang ][ $store ] : '#';
	// Customizer overrides the default if set.
	$mod  = get_theme_mod( 'play' === $store ? 'kg_play_store_url' : 'kg_app_store_url', '' );
	return $mod ? esc_url( $mod ) : $url;
}

// Social media links, per language. Empty string = not live yet (button shown but inert).
function kg_social_links() {
	$all = array(
		'id' => array(
			'instagram' => 'https://www.instagram.com/kidsgate.id/',
			'tiktok'    => 'https://www.tiktok.com/@kidsgate.id',
			'facebook'  => 'https://www.facebook.com/profile.php?id=61578094916488',
		),
		'en' => array( 'instagram' => '', 'tiktok' => '', 'facebook' => '' ),
		'th' => array( 'instagram' => '', 'tiktok' => '', 'facebook' => '' ),
	);
	return isset( $all[ kg_lang() ] ) ? $all[ kg_lang() ] : $all['en'];
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

// Inline SVG flags. Emoji flags (🇬🇧 etc.) don't render on Windows/Chrome —
// they fall back to two-letter codes — so we draw simple, crisp SVGs that
// look identical on every platform. Clip-path ids are made unique per call.
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

// Language switcher (flag + language name).
function kg_language_switcher( $id_suffix = '' ) {
	$langs   = array(
		'en' => 'English',
		'id' => 'Bahasa Indonesia',
		'th' => 'ไทย',
	);
	$current = kg_lang();
	?>
	<div class="kg-lang" data-kg-lang>
		<button class="kg-lang__btn" type="button" aria-expanded="false" aria-haspopup="true" aria-controls="kg-lang-menu<?php echo esc_attr( $id_suffix ); ?>">
			<?php echo kg_flag( $current ); // phpcs:ignore ?>
			<span class="kg-lang__label"><?php echo esc_html( $langs[ $current ] ); ?></span>
			<svg width="12" height="12" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
		</button>
		<ul class="kg-lang__menu" id="kg-lang-menu<?php echo esc_attr( $id_suffix ); ?>" role="menu">
			<?php foreach ( $langs as $code => $label ) : ?>
				<li role="none">
					<a role="menuitem" data-kg-event="language_switch" class="<?php echo $code === $current ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'lang', $code ) ); ?>">
						<?php echo kg_flag( $code ); // phpcs:ignore ?> <?php echo esc_html( $label ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
}

// App store badge pair (final CTA, download page, footer).
function kg_store_badges() {
	?>
	<div class="kg-store-badges">
		<a class="kg-store-badge" href="<?php echo kg_store_url( 'app' ); ?>" data-kg-event="app_store_click">
			<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.05 12.54c-.03-2.89 2.36-4.27 2.47-4.34-1.35-1.97-3.44-2.24-4.18-2.27-1.78-.18-3.47 1.05-4.37 1.05-.9 0-2.29-1.02-3.77-1-1.94.03-3.72 1.13-4.72 2.86-2.01 3.49-.51 8.66 1.45 11.49.96 1.39 2.1 2.94 3.6 2.88 1.45-.06 2-.93 3.75-.93s2.24.93 3.77.9c1.56-.03 2.55-1.41 3.5-2.8 1.1-1.61 1.56-3.17 1.58-3.25-.03-.02-3.04-1.17-3.08-4.59zM14.16 4.06c.8-.97 1.34-2.31 1.19-3.66-1.15.05-2.55.77-3.38 1.74-.74.85-1.39 2.22-1.22 3.53 1.29.1 2.6-.65 3.41-1.61z"/></svg>
			<span><small><?php kg_e( 'common.store_on' ); ?></small><strong>App Store</strong></span>
		</a>
		<a class="kg-store-badge" href="<?php echo kg_store_url( 'play' ); ?>" data-kg-event="play_store_click">
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

// Recursively resolve {placeholder} tokens in every answer across the helper
// decision tree (branch nodes carry `children`, leaf nodes carry `answer`).
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

// Rule-based guided support helper. Rendered globally from footer.php so the
// quick-help launcher is available on every page (not just Support). It is a
// no-AI, choose-your-own-adventure widget: branching topic buttons drill down
// to an answer, then a "Was this helpful?" prompt restarts or hands off to the
// support page. support.js wires up the interactions and bails out gracefully
// if these elements are absent.
function kg_render_helper() {
	$helper = kg_t( 'support.helper' );
	if ( empty( $helper ) || ! is_array( $helper ) ) {
		return;
	}

	// Resolve {placeholders} inside answers (shared with the FAQ data).
	$replacements = array(
		'{schools_url}'   => esc_url( kg_url( 'schools' ) ),
		'{pricing_url}'   => esc_url( kg_url( 'pricing' ) ),
		'{parents_url}'   => esc_url( kg_url( 'parents' ) ),
		'{support_url}'   => esc_url( kg_url( 'support' ) ),
		'{support_email}' => esc_html( kg_support_email() ),
	);

	$helper_data = array(
		'greeting'    => $helper['greeting'],
		'restart'     => $helper['restart'],
		'helpful_q'   => $helper['helpful_q'],
		'helpful_yes' => $helper['helpful_yes'],
		'helpful_no'  => $helper['helpful_no'],
		'back'        => $helper['back'],
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
// Avoids hard mbstring dependency: WP only polyfills mb_substr, not mb_strtoupper.
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
// Each chip may be a plain string (tick) or array ['text'=>…,'cross'=>true] (red ✕).
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
