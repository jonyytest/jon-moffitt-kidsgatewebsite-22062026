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
function add_query_arg( $key, $value ) {
	$params = $_GET;
	$params[ $key ] = $value;
	return strtok( $_SERVER['REQUEST_URI'], '?' ) . '?' . http_build_query( $params );
}
function add_action( $hook, $cb, $prio = 10 ) { $GLOBALS['kg_actions'][ $hook ][] = $cb; }
function do_action_stub( $hook ) {
	foreach ( $GLOBALS['kg_actions'][ $hook ] ?? array() as $cb ) { call_user_func( $cb ); }
}
function add_theme_support() {}
function get_theme_mod( $name, $default = '' ) { return $default; }
function esc_attr( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES ); }
function esc_html( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES ); }
function esc_url( $s ) { return $s; }
function esc_js( $s ) { return addslashes( $s ); }
function sanitize_email( $s ) { return filter_var( $s, FILTER_SANITIZE_EMAIL ); }
function sanitize_text_field( $s ) { return trim( strip_tags( $s ) ); }
function wp_strip_all_tags( $s ) { return strip_tags( $s ); }
function wpautop( $s ) { return '<p>' . str_replace( "\n\n", '</p><p>', $s ) . '</p>'; }
function wp_json_encode( $d ) { return json_encode( $d, JSON_UNESCAPED_UNICODE ); }
function wp_enqueue_style( $handle, $src = '', $deps = array(), $ver = null ) {
	if ( $src ) { $GLOBALS['kg_styles'][ $handle ] = $src; }
}
function wp_enqueue_script( $handle, $src = '', $deps = array(), $ver = null, $footer = false ) {
	if ( $src ) { $GLOBALS['kg_scripts'][ $handle ] = $src; }
}
function wp_localize_script( $handle, $name, $data ) {
	$GLOBALS['kg_inline'][] = 'var ' . $name . ' = ' . wp_json_encode( $data ) . ';';
}
function language_attributes() { echo 'lang="en"'; }
function bloginfo( $what ) { echo 'charset' === $what ? 'UTF-8' : 'Kids Gate'; }
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
	echo '<title>Kids Gate — preview</title>';
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

/* ---- routing ---- */
$slug = trim( $uri, '/' );
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
