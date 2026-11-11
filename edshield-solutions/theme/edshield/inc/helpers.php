<?php
/**
 * Small shared helpers: asset URLs, options, links, branding.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * URL of a file inside the theme.
 *
 * @param string $path Path relative to the theme root.
 */
function uri( string $path = '' ): string {
	return get_template_directory_uri() . ( '' !== $path ? '/' . ltrim( $path, '/' ) : '' );
}

/**
 * Cache-busting version for a theme file.
 *
 * @param string $path Path relative to the theme root.
 */
function asset_version( string $path ): string {
	$file = DIR . '/' . ltrim( $path, '/' );

	return file_exists( $file ) ? VERSION . '.' . filemtime( $file ) : VERSION;
}

/**
 * Read a theme option (stored as a theme mod with an `edst_` prefix).
 *
 * @param string $key     Option key without prefix.
 * @param mixed  $default Fallback value.
 * @return mixed
 */
function option( string $key, $default = '' ) {
	return get_theme_mod( 'edst_' . $key, $default );
}

/**
 * Whether Elementor is loaded.
 */
function elementor_active(): bool {
	return did_action( 'elementor/loaded' ) && class_exists( '\Elementor\Plugin' );
}

/**
 * Whether a post is rendered by Elementor.
 *
 * @param int $post_id Post ID.
 */
function built_with_elementor( int $post_id ): bool {
	return $post_id > 0 && elementor_active() && 'builder' === get_post_meta( $post_id, '_elementor_edit_mode', true );
}

/**
 * Whether the request renders inside the Elementor editor or its preview.
 */
function elementor_editing(): bool {
	if ( ! elementor_active() ) {
		return false;
	}

	$plugin = \Elementor\Plugin::$instance;

	return ( isset( $plugin->editor ) && $plugin->editor->is_edit_mode() ) || ( isset( $plugin->preview ) && $plugin->preview->is_preview_mode() );
}

/**
 * ID of the page created for a starter layout (0 when it does not exist).
 *
 * @param string $layout  Layout key, for example "contact" or "service-web".
 * @param bool   $refresh Empty the lookup cache first (after pages are created).
 */
function layout_page_id( string $layout, bool $refresh = false ): int {
	static $cache = array();

	if ( $refresh ) {
		$cache = array();
	}

	if ( '' === $layout ) {
		return 0;
	}

	if ( isset( $cache[ $layout ] ) ) {
		return $cache[ $layout ];
	}

	$ids = get_posts(
		array(
			'post_type'              => 'page',
			'post_status'            => array( 'publish', 'draft', 'private' ),
			'meta_key'               => '_edst_layout', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'             => $layout, // phpcs:ignore WordPress.DB.SlowDBQuery
			'numberposts'            => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	$cache[ $layout ] = $ids ? (int) $ids[0] : 0;

	return $cache[ $layout ];
}

/**
 * Resolve a link token or URL.
 *
 * Tokens: "page:<layout>", "service:<key>", "posts", "home", "#anchor".
 * Unknown pages fall back to their intended slug, so links work as soon as the
 * starter pages exist.
 *
 * @param mixed $value String, or an Elementor URL array.
 */
function resolve_url( $value ): string {
	if ( is_array( $value ) ) {
		$value = $value['url'] ?? '';
	}

	$value = trim( (string) $value );

	if ( '' === $value ) {
		return '';
	}

	if ( 'home' === $value ) {
		return home_url( '/' );
	}

	if ( 'posts' === $value ) {
		$posts_page = (int) get_option( 'page_for_posts' );

		return $posts_page ? (string) get_permalink( $posts_page ) : home_url( '/?post_type=post' );
	}

	// A token may carry an anchor: page:contact#contact-form.
	$hash = '';

	if ( preg_match( '/^(page|service):[^#]+(#.+)$/', $value, $match ) ) {
		$hash  = $match[2];
		$value = substr( $value, 0, -strlen( $hash ) );
	}

	if ( 0 === strpos( $value, 'service:' ) ) {
		$value = 'page:service-' . substr( $value, 8 );
	}

	if ( 0 === strpos( $value, 'page:' ) ) {
		$layout = substr( $value, 5 );
		$id     = layout_page_id( $layout );

		if ( $id ) {
			return (string) get_permalink( $id ) . $hash;
		}

		$fallback = layouts_index()[ $layout ]['path'] ?? $layout;

		return home_url( '/' . trim( $fallback, '/' ) . '/' ) . $hash;
	}

	return $value;
}

/**
 * Header call to action.
 *
 * @return array{label:string,url:string}
 */
function header_cta(): array {
	$label = trim( (string) option( 'cta_label', '' ) );
	$url   = trim( (string) option( 'cta_url', '' ) );

	return array(
		'label' => '' !== $label ? $label : __( 'Start a project', 'edshield' ),
		'url'   => '' !== $url ? $url : resolve_url( 'page:contact' ),
	);
}

/**
 * Contact details entered in the Customizer. Nothing is shown until entered.
 *
 * @return array{email:string,phone:string,whatsapp:string,address:string,hours:string}
 */
function contact_details(): array {
	return array(
		'email'    => sanitize_email( (string) option( 'contact_email', '' ) ),
		'phone'    => trim( (string) option( 'contact_phone', '' ) ),
		'whatsapp' => trim( (string) option( 'contact_whatsapp', '' ) ),
		'address'  => trim( (string) option( 'contact_address', '' ) ),
		'hours'    => trim( (string) option( 'contact_hours', '' ) ),
	);
}

/**
 * Social profile links entered in the Customizer.
 *
 * @return array<string, string> network => URL
 */
function social_links(): array {
	$links = array();

	foreach ( array( 'linkedin', 'facebook', 'instagram', 'x', 'youtube' ) as $network ) {
		$url = trim( (string) option( 'social_' . $network, '' ) );

		if ( '' !== $url ) {
			$links[ $network ] = $url;
		}
	}

	return $links;
}

/**
 * Site brand: the uploaded logo, or the shield mark with the wordmark.
 *
 * @param string $class Extra class.
 */
function brand( string $class = '' ): string {
	$home  = esc_url( home_url( '/' ) );
	$class = trim( 'edst-brand ' . $class );

	if ( has_custom_logo() ) {
		$image = wp_get_attachment_image(
			(int) get_theme_mod( 'custom_logo' ),
			'medium',
			false,
			array(
				'class'    => 'edst-brand__logo',
				'loading'  => 'eager',
				'decoding' => 'async',
				'alt'      => get_bloginfo( 'name', 'display' ),
			)
		);

		if ( $image ) {
			return sprintf( '<a class="%1$s" href="%2$s" rel="home">%3$s</a>', esc_attr( $class ), $home, $image );
		}
	}

	return sprintf(
		'<a class="%1$s" href="%2$s" rel="home"><svg class="edst-brand__mark" viewBox="0 0 28 32" width="28" height="32" aria-hidden="true" focusable="false"><path d="M14 1.2 26.6 5.4v10.4c0 7.5-5.2 12.9-12.6 16-7.4-3.1-12.6-8.5-12.6-16V5.4Z" fill="#0C1D44" stroke="currentColor" stroke-width="1.6"/><path d="M14 4.4 23.6 7.6v8.2c0 5.8-3.9 10-9.6 12.6-5.7-2.6-9.6-6.8-9.6-12.6V7.6Z" fill="none" stroke="currentColor" stroke-width="0.8" opacity="0.55"/><path d="M8.2 10.4h5.2M8.2 15.6h4.2M8.2 20.8h5.2M8.2 10.4v10.4M15.6 10.4h1.6c2.6 0 4.2 2.1 4.2 5.2s-1.6 5.2-4.2 5.2h-1.6Z" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg><span class="edst-brand__name">ED Shield<span class="edst-brand__sub">Solutions</span></span></a>',
		esc_attr( $class ),
		$home
	);
}

/**
 * A primary or secondary button.
 *
 * @param string $label   Label.
 * @param mixed  $url     URL or token.
 * @param string $variant primary | ghost | light | link.
 * @param string $extra   Extra classes.
 */
function button( string $label, $url, string $variant = 'primary', string $extra = '' ): string {
	$label = trim( $label );
	$href  = resolve_url( $url );

	if ( '' === $label || '' === $href ) {
		return '';
	}

	$external = is_array( $url ) && ! empty( $url['is_external'] );

	return sprintf(
		'<a class="%1$s" href="%2$s"%3$s><span>%4$s</span>%5$s</a>',
		esc_attr( trim( 'edst-btn edst-btn--' . $variant . ' ' . $extra ) ),
		esc_url( $href ),
		$external ? ' target="_blank" rel="noopener"' : '',
		esc_html( $label ),
		icon( 'link' === $variant ? 'arrow' : 'arrow-up-right', 'edst-btn__icon' )
	);
}

/**
 * Inline HTML allowed in headings and short texts.
 *
 * @param mixed $value Text.
 */
function inline( $value ): string {
	return wp_kses(
		(string) $value,
		array(
			'em'     => array(),
			'strong' => array(),
			'br'     => array(),
			'span'   => array( 'class' => true ),
			'a'      => array(
				'href'   => true,
				'target' => true,
				'rel'    => true,
			),
		)
	);
}

/**
 * Lines of a textarea as a clean list.
 *
 * @param mixed $value Text with one item per line, or an array.
 * @return string[]
 */
function lines( $value ): array {
	if ( is_array( $value ) ) {
		return array_values( array_filter( array_map( 'trim', array_map( 'strval', $value ) ), 'strlen' ) );
	}

	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $value ) ), 'strlen' ) );
}

/**
 * Estimated reading time in minutes.
 *
 * @param int $post_id Post ID.
 */
function reading_time( int $post_id ): int {
	$words = str_word_count( wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) ) );

	return max( 1, (int) ceil( $words / 220 ) );
}

/**
 * A short plain-text summary that never runs the_content filters
 * (safe and cheap for lists: no shortcodes or builder rendering).
 *
 * @param int $post_id Post ID.
 * @param int $words   Word limit.
 */
function summary( int $post_id, int $words = 26 ): string {
	$excerpt = (string) get_post_field( 'post_excerpt', $post_id );

	if ( '' === trim( $excerpt ) ) {
		$excerpt = excerpt_remove_blocks( strip_shortcodes( (string) get_post_field( 'post_content', $post_id ) ) );
	}

	return wp_trim_words( wp_strip_all_tags( $excerpt ), $words, '&hellip;' );
}
