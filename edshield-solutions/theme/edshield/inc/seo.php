<?php
/**
 * Minimal search and sharing metadata.
 *
 * Prints a meta description and basic Open Graph tags. Steps aside completely
 * when a dedicated SEO plugin is active, so the two never duplicate tags.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Whether a plugin that manages SEO metadata is active.
 */
function seo_plugin_active(): bool {
	return defined( 'WPSEO_VERSION' )
		|| defined( 'RANK_MATH_VERSION' )
		|| defined( 'SEOPRESS_VERSION' )
		|| defined( 'AIOSEO_VERSION' )
		|| defined( 'THE_SEO_FRAMEWORK_VERSION' )
		|| defined( 'SLIM_SEO_VER' );
}

/**
 * Plain-text description of the current view, at most about 160 characters.
 */
function page_description(): string {
	$text = '';

	if ( is_singular() ) {
		$post = get_post();

		if ( $post && ! post_password_required( $post ) ) {
			$layout = (string) get_post_meta( $post->ID, '_edst_layout', true );

			if ( '' !== trim( (string) $post->post_excerpt ) ) {
				$text = $post->post_excerpt;
			} elseif ( '' !== $layout && layout_sections( $layout ) ) {
				$first = layout_sections( $layout )[0][1];
				$text  = (string) ( $first['text'] ?? '' );
			} else {
				$text = excerpt_remove_blocks( strip_shortcodes( (string) $post->post_content ) );
			}
		}
	} elseif ( is_front_page() ) {
		$text = (string) ( section_settings( 'hero-home', array() )['text'] ?? '' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$text = (string) term_description();
	}

	if ( '' === trim( wp_strip_all_tags( $text ) ) && ( is_front_page() || is_home() ) ) {
		$text = (string) get_bloginfo( 'description', 'display' );
	}

	$text = trim( (string) preg_replace( '/\s+/', ' ', wp_strip_all_tags( $text ) ) );

	if ( mb_strlen( $text ) > 160 ) {
		$text = rtrim( mb_substr( $text, 0, 157 ), " ,.;:\u{2013}\u{2014}-" ) . '...';
	}

	/**
	 * Filter the meta description the theme prints.
	 *
	 * @param string $text Description.
	 */
	return (string) apply_filters( 'edst_meta_description', $text );
}

add_action( 'wp_head', __NAMESPACE__ . '\print_meta', 3 );
/**
 * Print the description and Open Graph tags.
 */
function print_meta(): void {
	if ( seo_plugin_active() ) {
		return;
	}

	$description = page_description();
	$title       = wp_get_document_title();
	$url         = is_singular() ? (string) get_permalink() : home_url( add_query_arg( array() ) );

	if ( '' !== $description ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
	}

	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:type" content="%s">' . "\n", is_singular( 'post' ) ? 'article' : 'website' );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name', 'display' ) ) );

	if ( is_singular() || is_front_page() ) {
		printf( '<meta property="og:url" content="%s">' . "\n", esc_url( is_front_page() ? home_url( '/' ) : $url ) );
	}

	if ( is_singular() && has_post_thumbnail() ) {
		$image = wp_get_attachment_image_url( (int) get_post_thumbnail_id(), 'edst-wide' );

		if ( $image ) {
			printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
		}
	}
}
