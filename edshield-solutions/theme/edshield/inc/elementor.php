<?php
/**
 * Elementor and Elementor Pro integration.
 *
 * - Registers an "ED Shield" widget category with one widget per section type,
 *   plus Site header and Site footer widgets for Theme Builder templates.
 * - Registers every Theme Builder location (header, footer, single, archive):
 *   a Pro template assigned there replaces the theme's own markup.
 * - Re-initialises the theme's scripts when Elementor re-renders a widget.
 *
 * The theme never requires Elementor; without it, the same sections are
 * rendered by the theme templates.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

add_action( 'elementor/theme/register_locations', __NAMESPACE__ . '\register_elementor_locations' );
/**
 * Offer all Theme Builder locations.
 *
 * @param object $manager Elementor Pro locations manager.
 */
function register_elementor_locations( $manager ): void {
	if ( is_object( $manager ) && method_exists( $manager, 'register_all_core_location' ) ) {
		$manager->register_all_core_location();
	}
}

/**
 * Render a Theme Builder location when a template is assigned to it.
 *
 * @param string $location header, footer, single or archive.
 */
function elementor_location( string $location ): bool {
	return function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( $location );
}

add_action( 'elementor/elements/categories_registered', __NAMESPACE__ . '\elementor_category' );
/**
 * Widget category.
 *
 * @param object $elements_manager Elementor elements manager.
 */
function elementor_category( $elements_manager ): void {
	if ( is_object( $elements_manager ) && method_exists( $elements_manager, 'add_category' ) ) {
		$elements_manager->add_category(
			'edshield',
			array(
				'title' => __( 'ED Shield', 'edshield' ),
				'icon'  => 'eicon-site-identity',
			)
		);
	}
}

add_action( 'elementor/widgets/register', __NAMESPACE__ . '\elementor_widgets' );
/**
 * Register the widgets.
 *
 * @param object $widgets_manager Elementor widgets manager.
 */
function elementor_widgets( $widgets_manager ): void {
	if ( ! class_exists( '\Elementor\Widget_Base' ) || ! is_object( $widgets_manager ) ) {
		return;
	}

	try {
		require_once DIR . '/inc/elementor-widgets.php';

		foreach ( Elementor\widget_classes() as $class ) {
			if ( class_exists( $class ) ) {
				if ( method_exists( $widgets_manager, 'register' ) ) {
					$widgets_manager->register( new $class() );
				} elseif ( method_exists( $widgets_manager, 'register_widget_type' ) ) {
					$widgets_manager->register_widget_type( new $class() );
				}
			}
		}
	} catch ( \Throwable $error ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( 'ED Shield: Elementor widgets not registered: ' . $error->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions
		}
	}
}

add_action( 'elementor/page_templates/header-footer/before_content', __NAMESPACE__ . '\elementor_main_open' );
/**
 * Elementor's "Full width" page template prints no main landmark; add one so
 * the skip link and screen readers find the content.
 */
function elementor_main_open(): void {
	echo '<main id="main" class="edst-main">';
}

add_action( 'elementor/page_templates/header-footer/after_content', __NAMESPACE__ . '\elementor_main_close' );
/**
 * Close the main landmark.
 */
function elementor_main_close(): void {
	echo '</main>';
}

add_action( 'elementor/editor/after_enqueue_styles', __NAMESPACE__ . '\elementor_editor_styles' );
/**
 * Brand the widget category in the editor panel.
 */
function elementor_editor_styles(): void {
	wp_add_inline_style(
		'elementor-editor',
		'.elementor-panel .elementor-element .icon .eicon-site-identity,.elementor-panel [class*="elementor-element--edst"] .icon{color:#d8a848}'
	);
}

/**
 * Elementor container + widget data for one section (used by the importer).
 *
 * @param string               $section  Section key.
 * @param array<string, mixed> $settings Settings.
 * @return array<string, mixed>
 */
function elementor_section_data( string $section, array $settings ): array {
	return array(
		'id'       => element_id(),
		'elType'   => 'container',
		'isInner'  => false,
		'settings' => array(
			'content_width' => 'full',
			'padding'       => array(
				'unit'     => 'px',
				'top'      => '0',
				'right'    => '0',
				'bottom'   => '0',
				'left'     => '0',
				'isLinked' => true,
			),
			'flex_gap'      => array(
				'column'   => '0',
				'row'      => '0',
				'isLinked' => true,
				'unit'     => 'px',
				'size'     => 0,
			),
		),
		'elements' => array(
			array(
				'id'         => element_id(),
				'elType'     => 'widget',
				'widgetType' => 'edst-' . $section,
				'isInner'    => false,
				'settings'   => $settings,
				'elements'   => array(),
			),
		),
	);
}

/**
 * Replace link tokens with real URLs so editors see working links.
 *
 * @param mixed $value Settings or a part of them.
 * @return mixed
 */
function resolve_tokens( $value ) {
	if ( is_array( $value ) ) {
		if ( array_key_exists( 'url', $value ) && array_key_exists( 'is_external', $value ) ) {
			$value['url'] = resolve_url( (string) $value['url'] );

			return $value;
		}

		foreach ( $value as $k => $v ) {
			$value[ $k ] = resolve_tokens( $v );
		}
	}

	return $value;
}

/**
 * Elementor data for a starter layout.
 *
 * @param string $layout Layout key.
 * @return array<int, array<string, mixed>>
 */
function elementor_layout_data( string $layout ): array {
	$sections = array();

	foreach ( layout_sections( $layout ) as $section ) {
		$sections[] = array(
			'type'     => $section[0],
			'settings' => $section[1],
		);
	}

	return elementor_sections_data( $sections );
}

/**
 * Elementor data for a list of sections (type and settings), with link
 * tokens resolved to this site's addresses.
 *
 * @param array<int, array{type:string,settings:array<string, mixed>}> $sections Sections.
 * @return array<int, array<string, mixed>>
 */
function elementor_sections_data( array $sections ): array {
	$data = array();

	foreach ( $sections as $section ) {
		$type = (string) ( $section['type'] ?? '' );

		if ( '' === $type ) {
			continue;
		}

		$data[] = elementor_section_data( $type, resolve_tokens( section_settings( $type, (array) ( $section['settings'] ?? array() ) ) ) );
	}

	return $data;
}

/**
 * Store Elementor data on a page and switch it to Elementor's full-width template.
 *
 * @param int                              $post_id Post ID.
 * @param array<int, array<string, mixed>> $data    Elementor data.
 * @param string                           $type    Elementor document type.
 */
function set_elementor_data( int $post_id, array $data, string $type = 'wp-page' ): void {
	update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $post_id, '_elementor_template_type', $type );
	update_post_meta( $post_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );
	update_post_meta( $post_id, '_elementor_data', wp_slash( (string) wp_json_encode( $data ) ) );
	delete_post_meta( $post_id, '_elementor_css' );

	if ( 'wp-page' === $type ) {
		update_post_meta( $post_id, '_wp_page_template', 'elementor_header_footer' );
	}
}

/**
 * Clear Elementor's generated CSS so pages are rebuilt on next view.
 */
function elementor_clear_cache(): void {
	if ( elementor_active() && isset( \Elementor\Plugin::$instance->files_manager ) && method_exists( \Elementor\Plugin::$instance->files_manager, 'clear_cache' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
}

/**
 * Add the theme colours to Elementor's global colours (Site Settings).
 * Existing colours are kept; the theme reads these back as CSS variables.
 */
function elementor_add_colors(): bool {
	$kit_id = (int) get_option( 'elementor_active_kit' );

	if ( ! $kit_id || ! get_post( $kit_id ) ) {
		return false;
	}

	$settings = get_post_meta( $kit_id, '_elementor_page_settings', true );
	$settings = is_array( $settings ) ? $settings : array();
	$custom   = isset( $settings['custom_colors'] ) && is_array( $settings['custom_colors'] ) ? $settings['custom_colors'] : array();
	$have     = wp_list_pluck( $custom, '_id' );
	$ours     = array(
		'edstnavy'  => array( __( 'ED Shield Navy', 'edshield' ), '#071533' ),
		'edstnavy2' => array( __( 'ED Shield Navy (raised)', 'edshield' ), '#0C1D44' ),
		'edstgold'  => array( __( 'ED Shield Gold', 'edshield' ), '#D8A848' ),
		'edstgold2' => array( __( 'ED Shield Gold (light)', 'edshield' ), '#E9C46A' ),
		'edstpaper' => array( __( 'ED Shield Light grey', 'edshield' ), '#F6F7F9' ),
		'edstslate' => array( __( 'ED Shield Slate', 'edshield' ), '#5B6478' ),
	);

	foreach ( $ours as $id => $color ) {
		if ( ! in_array( $id, $have, true ) ) {
			$custom[] = array(
				'_id'   => $id,
				'title' => $color[0],
				'color' => $color[1],
			);
		}
	}

	$settings['custom_colors'] = $custom;
	update_post_meta( $kit_id, '_elementor_page_settings', $settings );
	elementor_clear_cache();

	return true;
}
