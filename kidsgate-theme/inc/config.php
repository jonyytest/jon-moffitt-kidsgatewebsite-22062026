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
 * PLACEHOLDER — replace with the confirmed support address (or set it in
 * Appearance → Customize → Kids Gate Settings, which takes precedence).
 */
define( 'KG_SUPPORT_EMAIL_PLACEHOLDER', 'support@kidsgate.example' );

function kg_support_email() {
	$custom = get_theme_mod( 'kg_support_email', '' );
	return $custom ? sanitize_email( $custom ) : KG_SUPPORT_EMAIL_PLACEHOLDER;
}

// True once a real (non-placeholder) support email has been configured.
function kg_support_email_is_live() {
	return kg_support_email() !== KG_SUPPORT_EMAIL_PLACEHOLDER;
}

/**
 * Pricing engine — numeric rates per currency, used by the per-child plan
 * builder on the pricing page (assets/js/pricing.js).
 *
 * Model: the child studying the MOST subjects pays the standard first-child
 * rate; every other child pays the additional-child rate for their own
 * subject count. Annual prices are per month, billed yearly.
 */
function kg_pricing_rates() {
	return array(
		'en' => array(
			'currency'  => 'USD',
			'symbol'    => '$',
			'sym_after' => false,
			'thousands' => ',',
			'decimals'  => 2,
			'first'     => array( 1 => array( 'm' => 15, 'y' => 12 ), 2 => array( 'm' => 22.5, 'y' => 18 ) ),
			'addl'      => array( 1 => array( 'm' => 12, 'y' => 9.5 ), 2 => array( 'm' => 18, 'y' => 14.5 ) ),
		),
		'id' => array(
			'currency'  => 'IDR',
			'symbol'    => 'Rp',
			'sym_after' => false,
			'thousands' => '.',
			'decimals'  => 0,
			'first'     => array( 1 => array( 'm' => 170000, 'y' => 135000 ), 2 => array( 'm' => 250000, 'y' => 200000 ) ),
			'addl'      => array( 1 => array( 'm' => 130000, 'y' => 100000 ), 2 => array( 'm' => 170000, 'y' => 135000 ) ),
		),
		'th' => array(
			'currency'  => 'THB',
			'symbol'    => '฿',
			'sym_after' => false,
			'thousands' => ',',
			'decimals'  => 0,
			'first'     => array( 1 => array( 'm' => 400, 'y' => 320 ), 2 => array( 'm' => 600, 'y' => 480 ) ),
			'addl'      => array( 1 => array( 'm' => 300, 'y' => 240 ), 2 => array( 'm' => 400, 'y' => 320 ) ),
		),
	);
}

function kg_pricing_for_lang( $lang = null ) {
	$rates = kg_pricing_rates();
	$lang  = $lang ? $lang : kg_lang();
	return isset( $rates[ $lang ] ) ? $rates[ $lang ] : $rates['en'];
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
