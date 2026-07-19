<?php
/**
 * Dev preview harness — stubs just enough of WordPress to render the
 * theme templates in a browser without a WP install.
 *
 *   php -S localhost:8123 tests/preview.php   (run from the theme root)
 *
 * Routes: /            → front-page.php
 *         /pricing/    → page-pricing.php   (any page slug works)
 *         /404         → 404.php
 *         /?lang=id    → switches language (cookie persists)
 *
 * NOT part of the WordPress theme — never upload tests/ to production.
 */

// Dev harness only — must never run under a real web server (Apache/PHP-FPM).
// It stubs esc_url()/esc_js() as no-ops, so serving it in production would
// expose an unescaped, WordPress-bypassing renderer. Allow only the PHP
// built-in server used by `php -S`.
if ( PHP_SAPI !== 'cli-server' ) {
	http_response_code( 404 );
	exit;
}

error_reporting( E_ALL & ~E_DEPRECATED );

$theme_root = dirname( __DIR__ );

/* ---- static files (assets) served directly ---- */
$uri  = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
$file = $theme_root . $uri;
if ( '/' !== $uri && is_file( $file ) ) {
	return false; // let the built-in server stream it
}

/* ---- WordPress stubs ---- */
define( 'ABSPATH', $theme_root . '/' );
define( 'YEAR_IN_SECONDS', 31536000 );

$GLOBALS['kg_styles']  = array();
$GLOBALS['kg_scripts'] = array();
$GLOBALS['kg_inline']  = array();

function get_template_directory() { return $GLOBALS['kg_theme_root']; }
function get_template_directory_uri() { return ''; }
function home_url( $path = '/' ) { return $path; }
function admin_url( $path = '' ) { return '/wp-admin/' . $path; }
function wp_nonce_field( $action = -1, $name = '_wpnonce', $referer = true ) {
	echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="preview-nonce">';
}
function add_query_arg( $key, $value ) {
	$params = $_GET;
	$params[ $key ] = $value;
	return strtok( $_SERVER['REQUEST_URI'], '?' ) . '?' . http_build_query( $params );
}
function add_action( $hook, $cb, $prio = 10, $args = 1 ) { $GLOBALS['kg_actions'][ $hook ][] = $cb; }
function add_filter( $hook, $cb, $prio = 10, $args = 1 ) {} // no-op in preview
function do_action_stub( $hook ) {
	foreach ( $GLOBALS['kg_actions'][ $hook ] ?? array() as $cb ) { call_user_func( $cb ); }
}
function add_theme_support() {}
function add_rewrite_rule() {}
function flush_rewrite_rules() {}
function get_option( $key, $default = false ) { return $default; }
function update_option( $key, $value ) {}
function get_query_var( $var, $default = '' ) { return $default; }
function sanitize_key( $s ) { return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $s ) ); }
function wp_redirect( $url, $status = 302 ) { header( 'Location: ' . $url, true, $status ); exit; }
function get_theme_mod( $name, $default = '' ) {
	// Dev-only override so Customizer-driven features can be exercised in the
	// preview, e.g. ?mod_kg_demo_video_url=https://youtu.be/XXXXXXXXXXX tests
	// the YouTube demo player. Values must parse as URLs: the harness's
	// esc_url() is a pass-through, so this filter is what keeps quote/angle
	// characters out of attribute output.
	if ( isset( $_GET[ 'mod_' . $name ] ) ) {
		$v = (string) $_GET[ 'mod_' . $name ];
		if ( false !== filter_var( $v, FILTER_VALIDATE_URL ) ) { return $v; }
	}
	return $default;
}
function esc_attr( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES ); }
function esc_html( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES ); }
function esc_url( $s ) { return $s; }
function esc_js( $s ) { return addslashes( $s ); }
function sanitize_email( $s ) { return filter_var( $s, FILTER_SANITIZE_EMAIL ); }
function sanitize_text_field( $s ) { return trim( strip_tags( $s ) ); }
function wp_strip_all_tags( $s ) { return strip_tags( $s ); }
function wpautop( $s ) { return '<p>' . str_replace( "\n\n", '</p><p>', $s ) . '</p>'; }
function wp_json_encode( $d, $flags = 0 ) { return json_encode( $d, $flags | JSON_UNESCAPED_UNICODE ); }
function is_ssl() { return ! empty( $_SERVER['HTTPS'] ) && 'off' !== $_SERVER['HTTPS']; }
function wp_enqueue_style( $handle, $src = '', $deps = array(), $ver = null ) {
	if ( $src ) { $GLOBALS['kg_styles'][ $handle ] = $src; }
}
function wp_enqueue_script( $handle, $src = '', $deps = array(), $ver = null, $footer = false ) {
	if ( $src ) { $GLOBALS['kg_scripts'][ $handle ] = $src; }
}
function wp_localize_script( $handle, $name, $data ) {
	$GLOBALS['kg_inline'][] = 'var ' . $name . ' = ' . wp_json_encode( $data ) . ';';
}
function language_attributes() { echo 'lang="' . esc_attr( kg_html_lang() ) . '"'; }
function bloginfo( $what ) { echo 'charset' === $what ? 'UTF-8' : 'The Kids Gate'; }
function body_class( $extra = '' ) {
	$page = $GLOBALS['kg_current_page'];
	$cls  = $extra . ' ';
	$cls .= '404' === $page ? 'error404' : ( 'home' === $page ? 'home page' : 'page-template-page-' . $page );
	echo 'class="' . esc_attr( trim( $cls ) ) . '"';
}
function wp_head() {
	foreach ( $GLOBALS['kg_styles'] as $src ) {
		echo '<link rel="stylesheet" href="' . esc_attr( $src ) . "\">\n";
	}
	echo '<title>The Kids Gate preview</title>';
	do_action_stub( 'wp_head' );
}
function wp_body_open() {}
function wp_footer() {
	foreach ( $GLOBALS['kg_inline'] as $js ) { echo '<script>' . $js . "</script>\n"; }
	foreach ( $GLOBALS['kg_scripts'] as $src ) {
		echo '<script src="' . esc_attr( $src ) . "\"></script>\n";
	}
}
function get_header() { include $GLOBALS['kg_theme_root'] . '/header.php'; }
function get_footer() { include $GLOBALS['kg_theme_root'] . '/footer.php'; }
function is_page( $slug = '' ) { return $GLOBALS['kg_current_page'] === $slug; }
function is_404() { return '404' === $GLOBALS['kg_current_page']; }
function have_posts() { return false; }
function get_page_by_path() { return null; }

$GLOBALS['kg_theme_root']   = $theme_root;
$GLOBALS['kg_actions']      = array();

/* ---- routing: strip optional /market/lang/ prefix (mirrors mu-plugin) ---- */
$valid_markets = array( 'au', 'us', 'nz', 'sg', 'id', 'th', 'in', 'ph', 'kh', 'vn' );
$valid_langs   = array( 'en', 'id', 'th', 'zh' );
$parts = array_values( array_filter( explode( '/', trim( $uri, '/' ) ), 'strlen' ) );

if ( ! empty( $parts[0] ) && in_array( $parts[0], $valid_markets, true ) ) {
	define( 'KG_CURRENT_COUNTRY', $parts[0] );
	$_has_lang = ! empty( $parts[1] ) && in_array( $parts[1], $valid_langs, true );
	define( 'KG_CURRENT_LANG', $_has_lang ? $parts[1] : 'en' );
	$_skip = $_has_lang ? 2 : 1;
	$slug  = implode( '/', array_slice( $parts, $_skip ) );
	// Rewrite REQUEST_URI exactly as the mu-plugin does so kg_url_for_lang()
	// and kg_url_for_market() see only the page slug, not the market/lang prefix.
	$_SERVER['REQUEST_URI'] = '/' . ( $slug ? $slug . '/' : '' );
} else {
	define( 'KG_CURRENT_COUNTRY', '' );
	define( 'KG_CURRENT_LANG', '' );
	$slug = implode( '/', $parts );
}

$page = '' === $slug ? 'home' : $slug;
$GLOBALS['kg_current_page'] = $page;

require $theme_root . '/functions.php';
do_action_stub( 'init' );

// Mirror kg_enqueue_assets (the wp_enqueue_scripts hook).
do_action_stub( 'wp_enqueue_scripts' );
foreach ( $GLOBALS['kg_actions']['wp_enqueue_scripts'] ?? array() as $cb ) {} // already run above

$template = 'home' === $page ? '/front-page.php' : ( '404' === $page ? '/404.php' : '/page-' . $page . '.php' );
if ( ! is_file( $theme_root . $template ) ) {
	$GLOBALS['kg_current_page'] = '404';
	http_response_code( 404 );
	$template = '/404.php';
}
include $theme_root . $template;
