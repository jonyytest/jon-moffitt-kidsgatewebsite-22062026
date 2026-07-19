<?php
/**
 * Kids Gate — site configuration.
 *
 * Central place for values that change once real services are connected.
 * Everything here is a placeholder until the product owners confirm it.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Support team email.
 *
 * Confirmed support address (or set it in Appearance → Customize →
 * The Kids Gate Settings, which takes precedence).
 */
define( 'KG_SUPPORT_EMAIL_PLACEHOLDER', 'support@thekidsgate.com' );

function kg_support_email() {
	$custom = get_theme_mod( 'kg_support_email', '' );
	return $custom ? sanitize_email( $custom ) : KG_SUPPORT_EMAIL_PLACEHOLDER;
}

// True once a real (non-placeholder) support email has been configured.
function kg_support_email_is_live() {
	return true; // support@thekidsgate.com is the confirmed address
}

/**
 * Show / hide testimonial sections (home, Schools, Sponsors).
 *
 * Set to false while only placeholder quotes exist; flip to true once real,
 * verified testimonials are in the language files. This one switch controls
 * all three pages.
 */
define( 'KG_SHOW_TESTIMONIALS', false );

function kg_show_testimonials() {
	return KG_SHOW_TESTIMONIALS;
}

/**
 * Pricing engine — numeric rates per country, used by the per-child plan
 * builder on the pricing page (assets/js/pricing.js).
 *
 * Model: the child studying the MOST subjects pays the standard first-child
 * rate; every other child pays the additional-child rate for their own
 * subject count. Annual prices are per month, billed yearly.
 *
 * Keys: 'au' | 'us' | 'nz' | 'sg' | 'id' | 'th' | 'in' | 'ph' | 'kh' | 'vn'
 *
 * Prices are the confirmed final displayed values from the pricing model
 * (90-day FX basis, ~Jun 2026). They are fixed — do not re-convert.
 */
function kg_pricing_rates() {
	return array(
		'au' => array(
			'currency'  => 'AUD',
			'symbol'    => 'A$',
			'sym_after' => false,
			'thousands' => ',',
			'decimals'  => 2,
			// Zone 1.
			'first'     => array( 1 => array( 'm' => 15, 'y' => 10 ), 2 => array( 'm' => 22.5, 'y' => 15 ) ),
			'addl'      => array( 1 => array( 'm' => 11.25, 'y' => 7.5 ), 2 => array( 'm' => 16.88, 'y' => 11.25 ) ),
			'activation' => 20,
		),
		'us' => array(
			'currency'  => 'USD',
			'symbol'    => '$',
			'sym_after' => false,
			'thousands' => ',',
			'decimals'  => 2,
			// Zone 1.
			'first'     => array( 1 => array( 'm' => 11.5, 'y' => 7.5 ), 2 => array( 'm' => 17.5, 'y' => 11.5 ) ),
			'addl'      => array( 1 => array( 'm' => 8.5, 'y' => 5.5 ), 2 => array( 'm' => 13, 'y' => 8.5 ) ),
			'activation' => 15,
		),
		'nz' => array(
			'currency'  => 'NZD',
			'symbol'    => 'NZ$',
			'sym_after' => false,
			'thousands' => ',',
			'decimals'  => 2,
			// Zone 1.
			'first'     => array( 1 => array( 'm' => 19, 'y' => 12.5 ), 2 => array( 'm' => 28.5, 'y' => 19 ) ),
			'addl'      => array( 1 => array( 'm' => 14.5, 'y' => 9.5 ), 2 => array( 'm' => 22, 'y' => 14.5 ) ),
			'activation' => 25,
		),
		'sg' => array(
			'currency'  => 'SGD',
			'symbol'    => 'S$',
			'sym_after' => false,
			'thousands' => ',',
			'decimals'  => 2,
			// Zone 1.
			'first'     => array( 1 => array( 'm' => 14.5, 'y' => 9.5 ), 2 => array( 'm' => 22, 'y' => 14.5 ) ),
			'addl'      => array( 1 => array( 'm' => 10.5, 'y' => 7 ), 2 => array( 'm' => 16, 'y' => 10.5 ) ),
			'activation' => 19,
		),
		'id' => array(
			'currency'  => 'IDR',
			'symbol'    => 'Rp',
			'sym_after' => false,
			'thousands' => '.',
			'decimals'  => 0,
			// Zone 2.
			'first'     => array( 1 => array( 'm' => 150000, 'y' => 100000 ), 2 => array( 'm' => 225000, 'y' => 150000 ) ),
			'addl'      => array( 1 => array( 'm' => 115000, 'y' => 75000 ), 2 => array( 'm' => 175000, 'y' => 115000 ) ),
			'activation' => 250000,
		),
		'th' => array(
			'currency'  => 'THB',
			'symbol'    => '฿',
			'sym_after' => false,
			'thousands' => ',',
			'decimals'  => 0,
			// Zone 2.
			'first'     => array( 1 => array( 'm' => 290, 'y' => 190 ), 2 => array( 'm' => 440, 'y' => 290 ) ),
			'addl'      => array( 1 => array( 'm' => 210, 'y' => 140 ), 2 => array( 'm' => 320, 'y' => 210 ) ),
			'activation' => 470,
		),
		'in' => array(
			'currency'  => 'INR',
			'symbol'    => '₹',
			'sym_after' => false,
			'thousands' => ',',
			'decimals'  => 0,
			// Zone 2.
			'first'     => array( 1 => array( 'm' => 810, 'y' => 540 ), 2 => array( 'm' => 1220, 'y' => 810 ) ),
			'addl'      => array( 1 => array( 'm' => 620, 'y' => 410 ), 2 => array( 'm' => 930, 'y' => 620 ) ),
			'activation' => 1350,
		),
		'ph' => array(
			'currency'  => 'PHP',
			'symbol'    => '₱',
			'sym_after' => false,
			'thousands' => ',',
			'decimals'  => 0,
			// Zone 2.
			'first'     => array( 1 => array( 'm' => 530, 'y' => 350 ), 2 => array( 'm' => 800, 'y' => 530 ) ),
			'addl'      => array( 1 => array( 'm' => 390, 'y' => 260 ), 2 => array( 'm' => 590, 'y' => 390 ) ),
			'activation' => 870,
		),
		'kh' => array(
			'currency'  => 'USD',
			'symbol'    => '$',
			'sym_after' => false,
			'thousands' => ',',
			'decimals'  => 2,
			// Zone 2 — Cambodia is priced in USD.
			'first'     => array( 1 => array( 'm' => 9, 'y' => 6 ), 2 => array( 'm' => 13.5, 'y' => 9 ) ),
			'addl'      => array( 1 => array( 'm' => 7, 'y' => 4.5 ), 2 => array( 'm' => 10.5, 'y' => 7 ) ),
			'activation' => 15,
		),
		'vn' => array(
			'currency'  => 'VND',
			'symbol'    => '₫',
			'sym_after' => true,
			'thousands' => '.',
			'decimals'  => 0,
			// Zone 2.
			'first'     => array( 1 => array( 'm' => 225000, 'y' => 150000 ), 2 => array( 'm' => 340000, 'y' => 225000 ) ),
			'addl'      => array( 1 => array( 'm' => 175000, 'y' => 115000 ), 2 => array( 'm' => 265000, 'y' => 175000 ) ),
			'activation' => 375000,
		),
	);
}

/**
 * Returns pricing rates for the active country (from the URL prefix).
 * Falls back to 'us' rates on the bare domain.
 */
function kg_pricing_for_country( $country = null ) {
	$rates   = kg_pricing_rates();
	$country = $country ?: kg_country();
	return isset( $rates[ $country ] ) ? $rates[ $country ] : $rates['us'];
}

/**
 * Backwards-compatible wrapper used throughout the theme.
 * When a country prefix is in the URL it delegates to kg_pricing_for_country();
 * otherwise it maps the active language to a country for pricing.
 */
function kg_pricing_for_lang( $lang = null ) {
	if ( kg_country() ) {
		return kg_pricing_for_country();
	}
	$lang_map = array( 'en' => 'us', 'id' => 'id', 'th' => 'th', 'zh' => 'sg' );
	$lang     = $lang ?: kg_lang();
	$country  = isset( $lang_map[ $lang ] ) ? $lang_map[ $lang ] : 'us';
	return kg_pricing_for_country( $country );
}

/**
 * Savings percentage between two prices, e.g. kg_save_pct( 15, 10 ) → 33.
 * Used only for the no-JS initial render; main.js recomputes the same value
 * at runtime from the displayed price numbers. The percentage is always
 * derived from prices, never hardcoded, and excludes the activation fee.
 *
 * Rounds DOWN so an advertised saving is never overstated.
 */
function kg_save_pct( $from, $to ) {
	$from = (float) $from;
	if ( $from <= 0 ) {
		return 0;
	}
	return (int) floor( ( 1 - (float) $to / $from ) * 100 );
}

// "Save {n}%" label for a price pair, using the active-language template.
function kg_save_label( $from, $to ) {
	return str_replace( '{n}', kg_save_pct( $from, $to ), kg_t( 'pricing.save_label' ) );
}

// Format a number in the active currency, e.g. 22.5 → $22.50, 170000 → Rp170.000.
function kg_price( $value, $lang = null ) {
	$p      = kg_pricing_for_lang( $lang );
	$number = number_format( (float) $value, $p['decimals'], '.', $p['thousands'] );
	// Trim trailing .00 for whole USD amounts ($15 not $15.00).
	if ( $p['decimals'] > 0 && substr( $number, -3 ) === '.00' ) {
		$number = substr( $number, 0, -3 );
	}
	return $p['sym_after'] ? $number . $p['symbol'] : $p['symbol'] . $number;
}
