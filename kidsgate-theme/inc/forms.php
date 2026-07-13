<?php
/**
 * Kids Gate — enquiry form backend.
 *
 * Receives the support / school / sponsor forms (assets/js/support.js posts
 * them as FormData to admin-post.php?action=kg_form) and relays them to the
 * support inbox via wp_mail(). The front end falls back to a mailto: draft
 * whenever this endpoint is unreachable or errors, so a broken mail server
 * degrades to the old open-your-email-app behaviour — never a lost message.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Subject line per form kind. Server-side map — the client sends only the
 * kind token, never a subject string (header-injection safety).
 */
function kg_form_subjects() {
	return array(
		'support' => 'The Kids Gate: Support Request',
		'schools' => 'The Kids Gate: School Enquiry',
		'sponsor' => 'The Kids Gate: Sponsor Enquiry',
	);
}

/**
 * Whitelisted short fields (POST key → email line label) across all three
 * forms. Anything else in the payload is ignored; the message textarea is
 * handled separately so it keeps its line breaks.
 */
function kg_form_fields() {
	return array(
		'kg_name'     => 'Name',
		'kg_email'    => 'Email',
		'kg_topic'    => 'Topic',
		'kg_account'  => 'Account / child profile',
		'kg_school'   => 'School',
		'kg_students' => 'Students',
		'kg_level'    => 'Sponsorship level',
		'kg_org'      => 'Organisation',
		'kg_budget'   => 'Budget',
	);
}

function kg_handle_form() {
	// Honeypot: the kg_website field is visually hidden — bots that fill it
	// get a fake success so they don't retry with variations.
	if ( ! empty( $_POST['kg_website'] ) ) {
		wp_send_json_success();
	}

	if ( ! isset( $_POST['kg_form_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['kg_form_nonce'] ), 'kg_form' ) ) {
		wp_send_json_error( array( 'code' => 'nonce' ), 403 );
	}

	// Rate limit: 5 submissions per hour per IP.
	$ip   = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';
	$key  = 'kg_form_rl_' . md5( $ip );
	$hits = (int) get_transient( $key );
	if ( $hits >= 5 ) {
		wp_send_json_error( array( 'code' => 'rate' ), 429 );
	}
	set_transient( $key, $hits + 1, HOUR_IN_SECONDS );

	$subjects = kg_form_subjects();
	$kind     = isset( $_POST['kg_kind'] ) ? sanitize_key( $_POST['kg_kind'] ) : '';
	if ( ! isset( $subjects[ $kind ] ) ) {
		wp_send_json_error( array( 'code' => 'kind' ), 400 );
	}

	$name    = isset( $_POST['kg_name'] ) ? sanitize_text_field( wp_unslash( $_POST['kg_name'] ) ) : '';
	$email   = isset( $_POST['kg_email'] ) ? sanitize_email( wp_unslash( $_POST['kg_email'] ) ) : '';
	$message = isset( $_POST['kg_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['kg_message'] ) ) : '';
	$message = mb_substr( $message, 0, 4000 );

	// The support form requires a message; school/sponsor enquiries may rely
	// on the structured fields alone.
	if ( '' === $name || ! is_email( $email ) || ( 'support' === $kind && '' === $message ) ) {
		wp_send_json_error( array( 'code' => 'invalid' ), 400 );
	}

	$lines = array();
	foreach ( kg_form_fields() as $field => $label ) {
		if ( ! isset( $_POST[ $field ] ) ) {
			continue;
		}
		$value = sanitize_text_field( wp_unslash( $_POST[ $field ] ) );
		if ( '' !== $value ) {
			$lines[] = $label . ': ' . mb_substr( $value, 0, 300 );
		}
	}
	if ( '' !== $message ) {
		$lines[] = '';
		$lines[] = 'Message:';
		$lines[] = $message;
	}
	$referer = wp_get_referer();
	$lines[] = '';
	$lines[] = '— Sent from ' . ( $referer ? esc_url_raw( $referer ) : home_url( '/' ) );

	// sanitize_text_field strips newlines already; drop the few characters
	// that could still confuse an address header.
	$reply_name = preg_replace( '/[<>,"]/', '', $name );
	$headers    = array( 'Reply-To: ' . $reply_name . ' <' . $email . '>' );

	$sent = wp_mail( kg_support_email(), $subjects[ $kind ], implode( "\n", $lines ), $headers );

	if ( $sent ) {
		wp_send_json_success();
	}
	wp_send_json_error( array( 'code' => 'mail' ), 500 );
}
add_action( 'admin_post_kg_form', 'kg_handle_form' );
add_action( 'admin_post_nopriv_kg_form', 'kg_handle_form' );
