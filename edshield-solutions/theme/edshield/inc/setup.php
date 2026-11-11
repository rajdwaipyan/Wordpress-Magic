<?php
/**
 * Theme setup: supports, menus, image sizes and body classes.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', __NAMESPACE__ . '\setup' );
/**
 * Register theme features.
 */
function setup(): void {
	load_theme_textdomain( 'edshield', DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 96,
			'width'       => 360,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_editor_style( 'assets/css/editor.css' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary navigation (header and mobile)', 'edshield' ),
			'company' => __( 'Footer: company links', 'edshield' ),
			'legal'   => __( 'Footer: legal links', 'edshield' ),
		)
	);

	add_image_size( 'edst-card', 960, 720, true );
	add_image_size( 'edst-wide', 1920, 1080, false );
}

add_action( 'after_setup_theme', __NAMESPACE__ . '\content_width', 0 );
/**
 * Content width used by embeds and page builders.
 */
function content_width(): void {
	$GLOBALS['content_width'] = (int) apply_filters( 'edst_content_width', 1280 );
}

add_filter( 'body_class', __NAMESPACE__ . '\body_classes' );
/**
 * Body classes.
 *
 * @param mixed $classes Existing classes.
 * @return array
 */
function body_classes( $classes ): array {
	$classes   = is_array( $classes ) ? $classes : array();
	$classes[] = 'edst';

	if ( is_singular() && built_with_elementor( (int) get_queried_object_id() ) ) {
		$classes[] = 'edst-elementor-page';
	}

	return $classes;
}

add_action( 'wp_head', __NAMESPACE__ . '\js_flag', 0 );
/**
 * Mark the document as script-capable before first paint.
 */
function js_flag(): void {
	// A closed announcement stays hidden before first paint, so nothing jumps.
	echo "<script>document.documentElement.classList.replace('no-js','js');try{var edstA=localStorage.getItem('edst-announce');if(edstA&&/^[a-f0-9]{8}$/.test(edstA)){var edstS=document.createElement('style');edstS.textContent='body:not(.elementor-editor-active) .edst-announce[data-edst-announce=\"'+edstA+'\"]{display:none}';document.head.appendChild(edstS)}}catch(e){}</script>\n";
}

add_action( 'after_switch_theme', __NAMESPACE__ . '\flag_rewrite_flush' );
/**
 * Flush rewrite rules once after activation, when the post types exist.
 */
function flag_rewrite_flush(): void {
	update_option( 'edst_flush_rewrites', 1, false );
}

add_action( 'init', __NAMESPACE__ . '\maybe_flush_rewrites', 99 );
/**
 * Run a flagged rewrite flush.
 */
function maybe_flush_rewrites(): void {
	if ( get_option( 'edst_flush_rewrites' ) ) {
		delete_option( 'edst_flush_rewrites' );
		flush_rewrite_rules( false );
	}
}

add_filter( 'excerpt_length', __NAMESPACE__ . '\excerpt_length', 20 );
/**
 * Shorter excerpts.
 */
function excerpt_length(): int {
	return 28;
}

add_filter( 'excerpt_more', __NAMESPACE__ . '\excerpt_more', 20 );
/**
 * Plain ellipsis after excerpts.
 */
function excerpt_more(): string {
	return '&hellip;';
}
