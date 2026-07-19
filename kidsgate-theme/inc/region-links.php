<?php
/**
 * ═════════════════════════════════════════════════════════════════════════
 *  THE KIDS GATE — REGION LINKS
 *  The ONE place to update app-store links, social-media links and the
 *  How It Works demo video — per region.
 * ═════════════════════════════════════════════════════════════════════════
 *
 *  HOW TO EDIT (safe if you follow these rules):
 *    • Change only the text BETWEEN the straight quotes '  '.
 *    • Keep the quotes and the comma at the end of every line.
 *    • 'video' takes a YouTube link (e.g. https://youtu.be/XXXXXXXXXXX) or a
 *      Media Library file URL. Leave it as '' to use no video / the bundled file.
 *
 *  HOW REGIONS WORK:
 *    • The 'default' block is used everywhere UNLESS a region overrides it.
 *    • A region only needs the lines that DIFFER from default — anything it
 *      leaves out automatically uses the default value.
 *    • Region codes (the bit in the URL, e.g. /id/ ):
 *          au  us  nz  sg  id  th  in  ph  kh  vn
 *      Any region not listed below uses 'default'.
 *
 *  EXAMPLE — give Thailand its own app links + video, keep default socials:
 *      'th' => array(
 *          'app_store'  => 'https://apps.apple.com/th/app/....',
 *          'play_store' => 'https://play.google.com/store/apps/details?id=....',
 *          'video'      => 'https://youtu.be/XXXXXXXXXXX',
 *      ),
 * ═════════════════════════════════════════════════════════════════════════
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kg_region_links() {
	return array(

		/* ── DEFAULT — used by every region unless overridden below ────────── */
		'default' => array(
			'app_store'  => 'https://apps.apple.com/bg/app/kids-gate-indonesia/id1591630141',
			'play_store' => 'https://play.google.com/store/apps/details?id=com.thekidsgate.gameapp&hl=en',
			'video'      => '', // How It Works demo — paste a YouTube link, or leave ''
			'instagram'  => 'https://www.instagram.com/thekidsgate/',
			'tiktok'     => 'https://www.tiktok.com/@thekidsgate',
			'facebook'   => 'https://www.facebook.com/thekidsgate',
			'youtube'    => 'https://www.youtube.com/@thekidsgate3295',
		),

		/* ── INDONESIA — its own Instagram / TikTok / Facebook ─────────────── */
		'id' => array(
			'instagram'  => 'https://www.instagram.com/kidsgate.id/',
			'tiktok'     => 'https://www.tiktok.com/@kidsgate.id',
			'facebook'   => 'https://www.facebook.com/profile.php?id=61578094916488',
			// app_store, play_store, video, youtube fall back to 'default'.
		),

		/* ── Add more regions below. Copy a block, change the code and links. ─
		'th' => array(
			'app_store'  => '',
			'play_store' => '',
			'video'      => '',
		),
		'au' => array(
			'app_store'  => '',
			'play_store' => '',
		),
		*/

	);
}

/**
 * Resolve ONE link for the active region, e.g. kg_region_link( 'app_store' ).
 *
 * Region is taken from the URL prefix (kg_country). On the bare domain it maps
 * the UI language to a region as a best effort. A region value that is missing
 * or blank falls back to the 'default' block.
 */
function kg_region_link( $key ) {
	$all     = kg_region_links();
	$country = kg_country();

	// Bare domain (no /xx/ prefix): best-effort by language.
	if ( ! $country ) {
		$lang_map = array( 'id' => 'id', 'th' => 'th' ); // en / zh → default
		$country  = isset( $lang_map[ kg_lang() ] ) ? $lang_map[ kg_lang() ] : '';
	}

	$region = ( $country && isset( $all[ $country ] ) )
		? array_merge( $all['default'], $all[ $country ] )
		: $all['default'];

	// A blank override means "use the default".
	if ( empty( $region[ $key ] ) && ! empty( $all['default'][ $key ] ) ) {
		$region[ $key ] = $all['default'][ $key ];
	}

	return isset( $region[ $key ] ) ? $region[ $key ] : '';
}
