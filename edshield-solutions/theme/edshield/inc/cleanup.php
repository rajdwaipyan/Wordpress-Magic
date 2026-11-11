<?php
/**
 * Remove front-end output this theme does not need.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

add_action( 'init', __NAMESPACE__ . '\trim_head' );
/**
 * Drop legacy head output and the emoji runtime.
 */
function trim_head(): void {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
}

add_filter( 'emoji_svg_url', '__return_false' );

add_filter( 'wp_resource_hints', __NAMESPACE__ . '\drop_emoji_dns_prefetch', 10, 2 );
/**
 * Remove the DNS prefetch for the emoji CDN.
 *
 * @param array  $urls          Resource hint URLs.
 * @param string $relation_type Relation type.
 * @return array
 */
function drop_emoji_dns_prefetch( array $urls, string $relation_type ): array {
	if ( 'dns-prefetch' !== $relation_type ) {
		return $urls;
	}

	return array_values(
		array_filter(
			$urls,
			static function ( $url ) {
				$href = is_array( $url ) ? ( $url['href'] ?? '' ) : (string) $url;

				return false === strpos( $href, 's.w.org' );
			}
		)
	);
}

// Load each core block's stylesheet only on pages that use the block.
add_filter( 'should_load_separate_core_block_assets', '__return_true' );
