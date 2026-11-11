<?php
/**
 * Front-end assets: one stylesheet, one small deferred script, and a GSAP
 * motion bundle fetched only when the browser is idle (never with reduced
 * motion). No jQuery and no third-party requests.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets' );
/**
 * Enqueue the stylesheet and script.
 */
function enqueue_assets(): void {
	wp_enqueue_style( 'edst-main', uri( 'assets/css/main.css' ), array(), asset_version( 'assets/css/main.css' ) );
	wp_enqueue_script(
		'edst-main',
		uri( 'assets/js/main.js' ),
		array(),
		asset_version( 'assets/js/main.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => false,
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_filter( 'script_loader_tag', __NAMESPACE__ . '\motion_hint', 10, 2 );
/**
 * Tell main.js where the motion bundle is.
 *
 * @param mixed $tag    Script tag.
 * @param mixed $handle Handle.
 * @return mixed
 */
function motion_hint( $tag, $handle ) {
	if ( 'edst-main' !== $handle || ! is_string( $tag ) ) {
		return $tag;
	}

	$motion = add_query_arg( 'ver', asset_version( 'assets/js/motion.js' ), uri( 'assets/js/motion.js' ) );

	return str_replace( '<script ', '<script data-edst-motion="' . esc_url( $motion ) . '" ', $tag );
}

add_action( 'wp_head', __NAMESPACE__ . '\preload_fonts', 1 );
/**
 * Preload the font used above the fold.
 */
function preload_fonts(): void {
	foreach ( array( 'geist-latin.woff2' ) as $font ) {
		printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( uri( 'assets/fonts/' . $font ) ) );
	}

	echo '<meta name="theme-color" content="#071533">' . "\n";
}
