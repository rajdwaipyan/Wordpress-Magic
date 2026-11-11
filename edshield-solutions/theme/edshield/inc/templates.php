<?php
/**
 * Template helpers: page rendering decisions and list markup.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Render the current page: Elementor content, a starter layout, or the
 * standard page header with a text column.
 */
function render_current_page(): void {
	$post = get_post();

	if ( ! $post ) {
		return;
	}

	$layout = (string) get_post_meta( $post->ID, '_edst_layout', true );

	if ( built_with_elementor( $post->ID ) ) {
		the_content();

		return;
	}

	if ( '' !== $layout && '' === trim( (string) $post->post_content ) && layout_sections( $layout ) ) {
		render_layout( $layout );

		return;
	}

	echo render_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		'page-hero',
		array(
			'heading'   => esc_html( get_the_title() ),
			'text'      => has_excerpt() ? esc_html( get_the_excerpt() ) : '',
			'btn_label' => '',
			'spacing'   => 'none',
		)
	);

	echo '<div class="edst-page-body"><div class="edst-wrap"><div class="edst-prose' . ( is_app_page() ? ' edst-prose--wide' : '' ) . '">';
	the_content();
	wp_link_pages();
	echo '</div>';

	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}

	echo '</div></div>';
}

/**
 * Whether a page is application-style: classic content made of a plugin
 * shortcode (a dashboard, report or form). Those get the full grid width.
 */
function is_app_page(): bool {
	$post   = get_post();
	$is_app = $post && ! has_blocks( $post->post_content ) && (bool) preg_match( '/\[[a-z][a-z0-9_-]*(\s[^\]]*)?\]/i', $post->post_content );

	return (bool) apply_filters( 'edst_is_app_page', $is_app, $post );
}

/**
 * Pagination for archives.
 */
function pagination(): void {
	the_posts_pagination(
		array(
			'mid_size'  => 1,
			'prev_text' => esc_html__( 'Newer', 'edshield' ),
			'next_text' => esc_html__( 'Older', 'edshield' ),
			'class'     => 'edst-pagination',
		)
	);
}

/**
 * Category filter links for the blog.
 */
function category_pills(): string {
	$cats = get_categories(
		array(
			'hide_empty' => true,
			'number'     => 12,
		)
	);

	if ( count( $cats ) < 2 ) {
		return '';
	}

	$posts_page = (int) get_option( 'page_for_posts' );
	$all_url    = $posts_page ? get_permalink( $posts_page ) : home_url( '/' );
	$html       = '<nav class="edst-filters edst-filters--links" aria-label="' . esc_attr__( 'Categories', 'edshield' ) . '"><a class="edst-filter' . ( is_home() ? ' is-active' : '' ) . '" href="' . esc_url( (string) $all_url ) . '">' . esc_html__( 'All', 'edshield' ) . '</a>';

	foreach ( $cats as $cat ) {
		$html .= '<a class="edst-filter' . ( is_category( $cat->term_id ) ? ' is-active' : '' ) . '" href="' . esc_url( get_category_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a>';
	}

	return $html . '</nav>';
}
