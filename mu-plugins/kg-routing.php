<?php
/**
 * Kids Gate — country + language URL routing.
 *
 * URL structure (both segments always required):
 *   /au/en/slug   → market=AU  lang=EN   AUD
 *   /au/id/slug   → market=AU  lang=ID   AUD
 *   /au/th/slug   → market=AU  lang=TH   AUD
 *   /us/en/slug   → market=US  lang=EN   USD
 *   /us/id/slug   → market=US  lang=ID   USD
 *   /us/th/slug   → market=US  lang=TH   USD
 *   /nz/en/slug   → market=NZ  lang=EN   NZD  (also /nz/id, /nz/th)
 *   /sg/en/slug   → market=SG  lang=EN   SGD  (also /sg/id, /sg/th)
 *   /in/en/slug   → market=IN  lang=EN   INR  (Zone 2, default English)
 *   /ph/en/slug   → market=PH  lang=EN   PHP  (Zone 2, default English)
 *   /kh/en/slug   → market=KH  lang=EN   KHR  (Zone 2, default English)
 *   /vn/en/slug   → market=VN  lang=EN   VND  (Zone 2, default English)
 *   /id/en/slug   → market=ID  lang=EN   IDR
 *   /id/id/slug   → market=ID  lang=ID   IDR
 *   /id/th/slug   → market=ID  lang=TH   IDR
 *   /th/en/slug   → market=TH  lang=EN   THB
 *   /th/id/slug   → market=TH  lang=ID   THB
 *   /th/th/slug   → market=TH  lang=TH   THB
 *
 * Shorthand redirects (301):
 *   /au   → /au/en
 *   /us   → /us/en
 *   /nz   → /nz/en
 *   /sg   → /sg/en
 *   /in   → /in/en
 *   /ph   → /ph/en
 *   /kh   → /kh/en
 *   /vn   → /vn/en
 *   /id   → /id/id
 *   /th   → /th/th
 *
 * Bare domain (kidsgate.ai/):
 *   Sets KG_CURRENT_COUNTRY = '' and KG_CURRENT_LANG = ''.
 *   The Cloudflare Worker handles the geo-redirect for known countries.
 *   Unknown countries see the site with a region-selector banner.
 *
 * DEPLOY: place this file at  wp-content/mu-plugins/kg-routing.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KG_VALID_COUNTRIES', array( 'au', 'us', 'nz', 'sg', 'id', 'th', 'in', 'ph', 'kh', 'vn' ) );
define( 'KG_VALID_LANGS',     array( 'en', 'id', 'th', 'zh' ) );
define( 'KG_COUNTRY_DEFAULT_LANG', array(
	'au' => 'en',
	'us' => 'en',
	'nz' => 'en',
	'sg' => 'en',
	'id' => 'id',
	'th' => 'th',
	'in' => 'en',
	'ph' => 'en',
	'kh' => 'en',
	'vn' => 'en',
) );

// Runs immediately — before WordPress parses the request URI.
( function () {
	$uri   = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '/';
	$path  = parse_url( $uri, PHP_URL_PATH );
	$parts = array_values( array_filter( explode( '/', trim( $path, '/' ) ), 'strlen' ) );

	// No country prefix? Leave WordPress to handle it (bare domain, static files, etc.).
	if ( empty( $parts[0] ) || ! in_array( $parts[0], KG_VALID_COUNTRIES, true ) ) {
		define( 'KG_CURRENT_COUNTRY', '' );
		define( 'KG_CURRENT_LANG',    '' );
		return;
	}

	$country = $parts[0];

	// Country present but language missing or invalid → 301 to default language.
	if ( empty( $parts[1] ) || ! in_array( $parts[1], KG_VALID_LANGS, true ) ) {
		if ( ! headers_sent() ) {
			$default_lang = KG_COUNTRY_DEFAULT_LANG[ $country ];
			$remaining    = implode( '/', array_slice( $parts, 1 ) );
			$query        = parse_url( $uri, PHP_URL_QUERY );
			$dest = '/' . $country . '/' . $default_lang . '/' . ( $remaining ? $remaining . '/' : '' ) . ( $query ? '?' . $query : '' );
			header( 'Location: ' . $dest, true, 301 );
			exit;
		}
	}

	$lang = $parts[1];
	$skip = 2;

	define( 'KG_CURRENT_COUNTRY', $country );
	define( 'KG_CURRENT_LANG',    $lang );

	// Rewrite REQUEST_URI so WordPress sees only the page slug.
	$remaining = implode( '/', array_slice( $parts, $skip ) );
	$query     = parse_url( $uri, PHP_URL_QUERY );
	$_SERVER['REQUEST_URI'] = '/' . ( $remaining ? $remaining . '/' : '' ) . ( $query ? '?' . $query : '' );
} )();
