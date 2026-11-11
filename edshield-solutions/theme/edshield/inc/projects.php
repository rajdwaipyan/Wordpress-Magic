<?php
/**
 * Projects (case studies): post type, details box and helpers.
 *
 * Projects live at /work/<project>/. They can be written in the block editor
 * or built in Elementor; the details box feeds the cards, the case study
 * header and the overview.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

add_action( 'init', __NAMESPACE__ . '\register_projects' );
/**
 * Register the project post type.
 */
function register_projects(): void {
	if ( ! apply_filters( 'edst_enable_projects', true ) ) {
		return;
	}

	register_post_type(
		'edst_project',
		array(
			'labels'        => array(
				'name'          => __( 'Projects', 'edshield' ),
				'singular_name' => __( 'Project', 'edshield' ),
				'add_new_item'  => __( 'Add new project', 'edshield' ),
				'edit_item'     => __( 'Edit project', 'edshield' ),
				'all_items'     => __( 'All projects', 'edshield' ),
				'menu_name'     => __( 'Projects', 'edshield' ),
			),
			'public'        => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-portfolio',
			'menu_position' => 21,
			'has_archive'   => false,
			'rewrite'       => array(
				'slug'       => 'work',
				'with_front' => false,
			),
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'elementor' ),
		)
	);
}

/**
 * Project detail fields: key => label, input type.
 *
 * @return array<string, array{0:string,1:string}>
 */
function project_fields(): array {
	return array(
		'client'       => array( __( 'Client', 'edshield' ), 'text' ),
		'industry'     => array( __( 'Industry (used for filters)', 'edshield' ), 'text' ),
		'services'     => array( __( 'Services (comma separated)', 'edshield' ), 'text' ),
		'year'         => array( __( 'Year', 'edshield' ), 'text' ),
		'website'      => array( __( 'Website URL', 'edshield' ), 'url' ),
		'metric_value' => array( __( 'Headline result, value (for example +40%)', 'edshield' ), 'text' ),
		'metric_label' => array( __( 'Headline result, label', 'edshield' ), 'text' ),
		'challenge'    => array( __( 'The challenge', 'edshield' ), 'textarea' ),
		'goal'         => array( __( 'The goal', 'edshield' ), 'textarea' ),
		'objectives'   => array( __( 'Objectives (one per line)', 'edshield' ), 'textarea' ),
		'results'      => array( __( 'Results (one per line: value | label)', 'edshield' ), 'textarea' ),
		'art'          => array( __( 'Illustration when no featured image is set', 'edshield' ), 'art' ),
	);
}

/**
 * Details of a project with every key present.
 *
 * @param int $post_id Post ID.
 * @return array<string, string>
 */
function project_meta( int $post_id ): array {
	$stored = get_post_meta( $post_id, '_edst_project', true );
	$stored = is_array( $stored ) ? $stored : array();
	$out    = array();

	foreach ( array_keys( project_fields() ) as $key ) {
		$out[ $key ] = isset( $stored[ $key ] ) ? (string) $stored[ $key ] : '';
	}

	return $out;
}

add_action( 'add_meta_boxes_edst_project', __NAMESPACE__ . '\project_meta_box' );
/**
 * Add the details box.
 */
function project_meta_box(): void {
	add_meta_box( 'edst-project', __( 'Case study details', 'edshield' ), __NAMESPACE__ . '\project_meta_box_html', 'edst_project', 'normal', 'high' );
}

/**
 * Details box markup.
 *
 * @param \WP_Post $post Post.
 */
function project_meta_box_html( \WP_Post $post ): void {
	$meta = project_meta( $post->ID );

	wp_nonce_field( 'edst_project_meta', 'edst_project_nonce' );
	echo '<table class="form-table" role="presentation"><tbody>';

	foreach ( project_fields() as $key => $field ) {
		$id = 'edst-project-' . $key;

		echo '<tr><th scope="row"><label for="' . esc_attr( $id ) . '">' . esc_html( $field[0] ) . '</label></th><td>';

		if ( 'textarea' === $field[1] ) {
			echo '<textarea class="large-text" rows="4" id="' . esc_attr( $id ) . '" name="edst_project[' . esc_attr( $key ) . ']">' . esc_textarea( $meta[ $key ] ) . '</textarea>';
		} elseif ( 'art' === $field[1] ) {
			echo '<select id="' . esc_attr( $id ) . '" name="edst_project[' . esc_attr( $key ) . ']">';

			foreach ( art_choices() as $value => $label ) {
				if ( 0 === strpos( (string) $value, 'icon:' ) ) {
					continue;
				}

				echo '<option value="' . esc_attr( (string) $value ) . '"' . selected( $meta[ $key ], $value, false ) . '>' . esc_html( $label ) . '</option>';
			}

			echo '</select>';
		} else {
			echo '<input class="regular-text" type="' . esc_attr( $field[1] ) . '" id="' . esc_attr( $id ) . '" name="edst_project[' . esc_attr( $key ) . ']" value="' . esc_attr( $meta[ $key ] ) . '">';
		}

		echo '</td></tr>';
	}

	echo '</tbody></table>';
}

add_action( 'save_post_edst_project', __NAMESPACE__ . '\save_project_meta' );
/**
 * Save the details box.
 *
 * @param int $post_id Post ID.
 */
function save_project_meta( int $post_id ): void {
	if ( ! isset( $_POST['edst_project_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['edst_project_nonce'] ) ), 'edst_project_meta' ) ) {
		return;
	}

	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$input = isset( $_POST['edst_project'] ) && is_array( $_POST['edst_project'] ) ? wp_unslash( $_POST['edst_project'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised per field below.
	$clean = array();

	foreach ( project_fields() as $key => $field ) {
		$value = isset( $input[ $key ] ) ? (string) $input[ $key ] : '';

		if ( 'textarea' === $field[1] ) {
			$clean[ $key ] = sanitize_textarea_field( $value );
		} elseif ( 'url' === $field[1] ) {
			$clean[ $key ] = esc_url_raw( $value );
		} elseif ( 'art' === $field[1] ) {
			$clean[ $key ] = array_key_exists( $value, art_choices() ) ? $value : '';
		} else {
			$clean[ $key ] = sanitize_text_field( $value );
		}
	}

	update_post_meta( $post_id, '_edst_project', $clean );
}

/**
 * Results of a project as value/label pairs.
 *
 * @param int $post_id Post ID.
 * @return array<int, array{value:string,label:string}>
 */
function project_results( int $post_id ): array {
	$out = array();

	foreach ( lines( project_meta( $post_id )['results'] ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		$out[] = array(
			'value' => $parts[0],
			'label' => $parts[1] ?? '',
			'text'  => '',
		);
	}

	return $out;
}
