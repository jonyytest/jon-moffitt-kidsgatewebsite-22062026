<?php
/**
 * Dev check: every language file must expose the same key tree, so no
 * template ever renders a raw key path. Run: php tests/check-lang-keys.php
 */

function kg_flatten_keys( array $arr, $prefix = '' ) {
	$keys = array();
	foreach ( $arr as $k => $v ) {
		$path = '' === $prefix ? (string) $k : $prefix . '.' . $k;
		if ( is_array( $v ) ) {
			// Numeric lists: compare length only, not item-internal keys.
			if ( array_keys( $v ) === range( 0, count( $v ) - 1 ) ) {
				$keys[] = $path . '[' . count( $v ) . ']';
			} else {
				$keys = array_merge( $keys, kg_flatten_keys( $v, $path ) );
			}
		} else {
			$keys[] = $path;
		}
	}
	return $keys;
}

$base = dirname( __DIR__ ) . '/inc/lang/';
$langs = array();
foreach ( array( 'en', 'id', 'th' ) as $code ) {
	$langs[ $code ] = kg_flatten_keys( require $base . $code . '.php' );
	sort( $langs[ $code ] );
}

$fail = false;
foreach ( array( 'id', 'th' ) as $code ) {
	$missing = array_diff( $langs['en'], $langs[ $code ] );
	$extra   = array_diff( $langs[ $code ], $langs['en'] );
	if ( $missing ) {
		$fail = true;
		echo strtoupper( $code ) . " missing:\n  " . implode( "\n  ", $missing ) . "\n";
	}
	if ( $extra ) {
		$fail = true;
		echo strtoupper( $code ) . " extra:\n  " . implode( "\n  ", $extra ) . "\n";
	}
}

echo $fail ? "MISMATCH\n" : 'OK — ' . count( $langs['en'] ) . " keys match across en/id/th\n";
exit( $fail ? 1 : 0 );
