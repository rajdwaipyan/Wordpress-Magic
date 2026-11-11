<?php
/**
 * Write the demo content package (demo/*.json) from the theme's layouts and
 * content, so the package always matches the theme.
 *
 * Usage, from a WordPress install where this theme is active:
 *
 *   php tools/export-demo.php /path/to/wordpress
 *
 * Command line only.
 *
 * @package EDShield\Theme
 */

if ( 'cli' !== PHP_SAPI ) {
	exit;
}

$edst_wp = rtrim( (string) ( $argv[1] ?? '' ), '/' );

if ( '' === $edst_wp || ! is_file( $edst_wp . '/wp-load.php' ) ) {
	fwrite( STDERR, "Usage: php tools/export-demo.php /path/to/wordpress\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite
	exit( 1 );
}

$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost';

require $edst_wp . '/wp-load.php';

if ( ! function_exists( 'EDShield\Theme\demo_package_build' ) ) {
	fwrite( STDERR, "Activate the ED Shield theme on that site first.\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite
	exit( 1 );
}

$edst_package = EDShield\Theme\demo_package_build();
$edst_dir     = dirname( __DIR__ ) . '/demo';

if ( ! is_dir( $edst_dir ) ) {
	mkdir( $edst_dir, 0755, true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_mkdir
}

foreach ( EDShield\Theme\demo_files() as $edst_part => $edst_file ) {
	$edst_json = wp_json_encode( $edst_package[ $edst_part ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	file_put_contents( $edst_dir . '/' . $edst_file, $edst_json . "\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	echo esc_html( $edst_file ) . ': ' . esc_html( (string) strlen( (string) $edst_json ) ) . " bytes\n";
}
