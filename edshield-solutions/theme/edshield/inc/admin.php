<?php
/**
 * Setup screen (Appearance › ED Shield Setup), importer and admin notices.
 *
 * Nothing runs automatically. Every action is a button press by an
 * administrator, and existing pages are never overwritten: the importer only
 * creates pages that do not exist yet (pages are matched by their layout key).
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Plugins that belong to the previous Biznax theme and break other themes.
 *
 * @return array<string, string> plugin file => name
 */
function companion_plugins(): array {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$found = array();

	foreach ( array(
		'bravis-addons/bravis-addons.php' => 'Bravis Addons',
		'bravis-user/bravis-user.php'     => 'Bravis User',
	) as $file => $name ) {
		if ( is_plugin_active( $file ) ) {
			$found[ $file ] = $name;
		}
	}

	return $found;
}

add_action( 'admin_menu', __NAMESPACE__ . '\setup_menu' );
/**
 * Add the setup screen.
 */
function setup_menu(): void {
	add_theme_page( __( 'ED Shield Setup', 'edshield' ), __( 'ED Shield Setup', 'edshield' ), 'edit_theme_options', 'edshield-setup', __NAMESPACE__ . '\setup_screen' );
}

add_action( 'admin_notices', __NAMESPACE__ . '\setup_notices' );
/**
 * Notices: finish setup, and Biznax companion plugins still active.
 */
function setup_notices(): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( $screen && 'appearance_page_edshield-setup' === $screen->id ) {
		return;
	}

	$link = esc_url( admin_url( 'themes.php?page=edshield-setup' ) );

	if ( companion_plugins() ) {
		echo '<div class="notice notice-error"><p><strong>' . esc_html__( 'ED Shield:', 'edshield' ) . '</strong> ' . esc_html( implode( ', ', companion_plugins() ) ) . ' ' . esc_html__( 'only work with the Biznax theme and can break pages under any other theme.', 'edshield' ) . ' <a href="' . $link . '">' . esc_html__( 'Review in ED Shield Setup', 'edshield' ) . '</a></p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	if ( ! get_option( 'edst_setup_done' ) ) {
		echo '<div class="notice notice-info"><p><strong>' . esc_html__( 'ED Shield is active.', 'edshield' ) . '</strong> ' . esc_html__( 'Create the starter pages, menus and placeholder case studies in one step.', 'edshield' ) . ' <a class="button button-primary" style="margin-left:8px" href="' . $link . '">' . esc_html__( 'Open ED Shield Setup', 'edshield' ) . '</a></p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

add_action( 'admin_post_edst_setup', __NAMESPACE__ . '\handle_setup' );
/**
 * Run a setup action.
 */
function handle_setup(): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to do this.', 'edshield' ) );
	}

	check_admin_referer( 'edst_setup' );

	$task   = isset( $_POST['edst_task'] ) ? sanitize_key( wp_unslash( $_POST['edst_task'] ) ) : '';
	$report = array();

	switch ( $task ) {
		case 'import':
			$report = import_site(
				array(
					'front'     => ! empty( $_POST['edst_front'] ),
					'menus'     => ! empty( $_POST['edst_menus'] ),
					'projects'  => ! empty( $_POST['edst_projects'] ),
					'elementor' => ! empty( $_POST['edst_elementor'] ) && elementor_active(),
				)
			);
			break;

		case 'rebuild':
			$layout = isset( $_POST['edst_layout'] ) ? sanitize_key( wp_unslash( $_POST['edst_layout'] ) ) : '';
			$id     = layout_page_id( $layout );

			if ( $id && ! empty( $_POST['edst_confirm'] ) && elementor_active() && 'insights' !== $layout ) {
				set_elementor_data( $id, elementor_layout_data( $layout ) );
				elementor_clear_cache();
				/* translators: %s: page title. */
				$report[] = sprintf( __( 'Rebuilt "%s" from the starter layout.', 'edshield' ), get_the_title( $id ) );
			} else {
				$report[] = __( 'Nothing was changed. Choose a page, tick the confirmation box, and make sure Elementor is active.', 'edshield' );
			}
			break;

		case 'colors':
			$report[] = elementor_add_colors() ? __( 'ED Shield colours were added to Elementor › Site Settings › Global Colors.', 'edshield' ) : __( 'No active Elementor kit was found.', 'edshield' );
			update_option( 'elementor_disable_color_schemes', 'yes' );
			update_option( 'elementor_disable_typography_schemes', 'yes' );
			$report[] = __( 'Elementor’s default colours and fonts were switched off, so Elementor’s own widgets use the theme’s fonts and colours.', 'edshield' );
			break;

		case 'pro_templates':
			$report = create_pro_templates();
			break;

		case 'companions':
			if ( current_user_can( 'activate_plugins' ) && companion_plugins() ) {
				$names = companion_plugins();
				deactivate_plugins( array_keys( $names ) );
				/* translators: %s: plugin names. */
				$report[] = sprintf( __( 'Deactivated: %s.', 'edshield' ), implode( ', ', $names ) );
			}
			break;
	}

	set_transient( 'edst_setup_report_' . get_current_user_id(), $report, 5 * MINUTE_IN_SECONDS );
	wp_safe_redirect( admin_url( 'themes.php?page=edshield-setup&done=1' ) );
	exit;
}

/**
 * Create the starter pages, menus and placeholder projects.
 *
 * @param array<string, bool> $opts Options: front, menus, projects, elementor.
 * @return string[] Report lines.
 */
function import_site( array $opts ): array {
	$report  = array();
	$ids     = array();
	$created = array();

	foreach ( layouts_index() as $key => $meta ) {
		$id = layout_page_id( $key );

		if ( ! $id ) {
			$id = wp_insert_post(
				array(
					'post_type'      => 'page',
					'post_status'    => 'publish',
					'post_title'     => $meta['title'],
					'post_name'      => basename( $meta['path'] ),
					'post_parent'    => '' !== $meta['parent'] ? (int) ( $ids[ $meta['parent'] ] ?? 0 ) : 0,
					'post_content'   => '',
					'comment_status' => 'closed',
					'menu_order'     => count( $ids ),
				),
				true
			);

			if ( is_wp_error( $id ) ) {
				/* translators: 1: page title, 2: error. */
				$report[] = sprintf( __( 'Could not create "%1$s": %2$s', 'edshield' ), $meta['title'], $id->get_error_message() );
				continue;
			}

			update_post_meta( $id, '_edst_layout', $key );
			$created[] = $key;
		}

		$ids[ $key ] = (int) $id;
	}

	// Later lookups must see the pages just created.
	layout_page_id( '', true );

	if ( $opts['elementor'] ) {
		foreach ( $created as $key ) {
			if ( 'insights' !== $key ) {
				set_elementor_data( $ids[ $key ], elementor_layout_data( $key ) );
			}
		}

		elementor_clear_cache();
	}

	/* translators: %d: number of pages. */
	$report[] = sprintf( _n( '%d page created.', '%d pages created.', count( $created ), 'edshield' ), count( $created ) );

	if ( count( $created ) < count( $ids ) ) {
		$report[] = __( 'Pages that already existed were left unchanged.', 'edshield' );
	}

	if ( $opts['front'] && isset( $ids['home'], $ids['insights'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['home'] );
		update_option( 'page_for_posts', $ids['insights'] );
		$report[] = __( 'Home is now the front page and Insights is the posts page.', 'edshield' );
	}

	if ( $opts['menus'] ) {
		$report = array_merge( $report, create_menus( $ids ) );
	}

	if ( $opts['projects'] ) {
		$report[] = create_sample_projects();
	}

	if ( '' === (string) get_option( 'permalink_structure' ) ) {
		global $wp_rewrite;

		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		$report[] = __( 'Permalinks set to "Post name" so page addresses are readable.', 'edshield' );
	}

	flush_rewrite_rules( false );
	update_option( 'edst_setup_done', time(), false );

	return $report;
}

/**
 * Add one item to a menu.
 *
 * @param int    $menu        Menu ID.
 * @param int    $page        Page ID (0 for a custom link).
 * @param string $title       Title.
 * @param int    $parent      Parent item ID.
 * @param string $classes     CSS classes.
 * @param string $description Description.
 * @param string $url         Custom URL when no page is given.
 */
function add_menu_item( int $menu, int $page, string $title, int $parent = 0, string $classes = '', string $description = '', string $url = '' ): int {
	$args = array(
		'menu-item-title'       => $title,
		'menu-item-status'      => 'publish',
		'menu-item-parent-id'   => $parent,
		'menu-item-classes'     => $classes,
		'menu-item-description' => $description,
	);

	if ( $page ) {
		$args['menu-item-object']    = 'page';
		$args['menu-item-object-id'] = $page;
		$args['menu-item-type']      = 'post_type';
	} else {
		$args['menu-item-type'] = 'custom';
		$args['menu-item-url']  = $url;
	}

	$id = wp_update_nav_menu_item( $menu, 0, $args );

	return is_wp_error( $id ) ? 0 : (int) $id;
}

/**
 * Create the primary and footer menus (only for locations still empty).
 *
 * @param array<string, int> $ids Page IDs by layout key.
 * @return string[] Report lines.
 */
function create_menus( array $ids ): array {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations = is_array( $locations ) ? $locations : array();
	$report    = array();
	$page      = static function ( string $key ) use ( $ids ): int {
		return (int) ( $ids[ $key ] ?? 0 );
	};

	if ( empty( $locations['primary'] ) ) {
		$menu = wp_create_nav_menu( __( 'ED Shield main', 'edshield' ) . ' ' . gmdate( 'Y-m-d H:i' ) );

		if ( ! is_wp_error( $menu ) ) {
			$services = add_menu_item( $menu, $page( 'services' ), __( 'Services', 'edshield' ), 0, 'edst-mega' );

			foreach ( services() as $key => $service ) {
				add_menu_item( $menu, $page( 'service-' . $key ), $service['title'], $services, 'edst-icon-' . $service['icon'], $service['nav'] );
			}

			add_menu_item( $menu, $page( 'services' ), __( 'View all services', 'edshield' ), $services, 'edst-view-all' );
			add_menu_item( $menu, $page( 'work' ), __( 'Work', 'edshield' ) );
			add_menu_item( $menu, $page( 'industries' ), __( 'Industries', 'edshield' ) );

			$company = add_menu_item( $menu, $page( 'about' ), __( 'Company', 'edshield' ) );

			foreach ( array(
				'about'      => array( __( 'About', 'edshield' ), __( 'Who we are and how we think', 'edshield' ) ),
				'process'    => array( __( 'Process', 'edshield' ), __( 'How a project runs', 'edshield' ) ),
				'engagement' => array( __( 'Engagement models', 'edshield' ), __( 'Ways to work with us', 'edshield' ) ),
				'careers'    => array( __( 'Careers', 'edshield' ), __( 'Join the team', 'edshield' ) ),
				'faq'        => array( __( 'FAQ', 'edshield' ), __( 'Common questions, answered', 'edshield' ) ),
				'contact'    => array( __( 'Contact', 'edshield' ), __( 'Start a conversation', 'edshield' ) ),
			) as $key => $item ) {
				add_menu_item( $menu, $page( $key ), $item[0], $company, '', $item[1] );
			}

			add_menu_item( $menu, $page( 'insights' ), __( 'Insights', 'edshield' ) );
			$locations['primary'] = $menu;
			$report[]             = __( 'Primary menu created with the Services mega menu.', 'edshield' );
		}
	}

	if ( empty( $locations['company'] ) ) {
		$menu = wp_create_nav_menu( __( 'ED Shield company', 'edshield' ) . ' ' . gmdate( 'Y-m-d H:i' ) );

		if ( ! is_wp_error( $menu ) ) {
			foreach ( array( 'about', 'process', 'work', 'engagement', 'careers', 'insights', 'contact' ) as $key ) {
				if ( $page( $key ) ) {
					add_menu_item( $menu, $page( $key ), get_the_title( $page( $key ) ) );
				}
			}

			$locations['company'] = $menu;
			$report[]             = __( 'Footer company menu created.', 'edshield' );
		}
	}

	$privacy = (int) get_option( 'wp_page_for_privacy_policy' );

	if ( empty( $locations['legal'] ) && $privacy && 'publish' === get_post_status( $privacy ) ) {
		$menu = wp_create_nav_menu( __( 'ED Shield legal', 'edshield' ) . ' ' . gmdate( 'Y-m-d H:i' ) );

		if ( ! is_wp_error( $menu ) ) {
			add_menu_item( $menu, $privacy, get_the_title( $privacy ) );
			$locations['legal'] = $menu;
		}
	}

	set_theme_mod( 'nav_menu_locations', $locations );

	return $report;
}

/**
 * Create three clearly marked placeholder case studies when none exist.
 */
function create_sample_projects(): string {
	if ( get_posts( array( 'post_type' => 'edst_project', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) ) ) {
		return __( 'Projects already exist, so no placeholder case studies were added.', 'edshield' );
	}

	$samples = array(
		array( __( 'Placeholder case study: corporate website and CRM', 'edshield' ), __( 'Corporate', 'edshield' ), 'Website Development, CRM & ERP Integration', 'web' ),
		array( __( 'Placeholder case study: patient booking automation', 'edshield' ), __( 'Healthcare', 'edshield' ), 'AI & Business Automation, Cybersecurity', 'ai' ),
		array( __( 'Placeholder case study: production dashboard', 'edshield' ), __( 'Manufacturing', 'edshield' ), 'Industrial Digitalization, Custom Software', 'industrial' ),
	);

	foreach ( $samples as $sample ) {
		$id = wp_insert_post(
			array(
				'post_type'    => 'edst_project',
				'post_status'  => 'publish',
				'post_title'   => $sample[0],
				'post_excerpt' => __( 'Placeholder: replace with a one-sentence summary of a real project.', 'edshield' ),
				'post_content' => '<!-- wp:paragraph --><p>' . __( 'Placeholder: describe what was built, how it was delivered and what changed for the client. Add images in a gallery below.', 'edshield' ) . '</p><!-- /wp:paragraph -->',
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta(
				$id,
				'_edst_project',
				array(
					'client'       => __( 'Client name', 'edshield' ),
					'industry'     => $sample[1],
					'services'     => $sample[2],
					'year'         => '',
					'website'      => '',
					'metric_value' => '—',
					'metric_label' => __( 'Add a measured result', 'edshield' ),
					'challenge'    => __( 'Placeholder: the problem the client faced and why it mattered.', 'edshield' ),
					'goal'         => __( 'Placeholder: what success looked like.', 'edshield' ),
					'objectives'   => implode( "\n", array( __( 'Placeholder objective one', 'edshield' ), __( 'Placeholder objective two', 'edshield' ), __( 'Placeholder objective three', 'edshield' ) ) ),
					'results'      => implode( "\n", array( '— | ' . __( 'Result placeholder', 'edshield' ), '— | ' . __( 'Result placeholder', 'edshield' ), '— | ' . __( 'Result placeholder', 'edshield' ) ) ),
					'art'          => $sample[3],
				)
			);
		}
	}

	return __( 'Three placeholder case studies were added under Projects.', 'edshield' );
}

/**
 * Create Elementor Pro header and footer templates holding the theme's
 * header and footer widgets. Display conditions are left for the user.
 *
 * @return string[] Report lines.
 */
function create_pro_templates(): array {
	if ( ! defined( 'ELEMENTOR_PRO_VERSION' ) || ! post_type_exists( 'elementor_library' ) ) {
		return array( __( 'Elementor Pro is not active.', 'edshield' ) );
	}

	$report = array();

	foreach ( array( 'header', 'footer' ) as $type ) {
		$id = wp_insert_post(
			array(
				'post_type'   => 'elementor_library',
				'post_status' => 'publish',
				/* translators: %s: header or footer. */
				'post_title'  => sprintf( __( 'ED Shield %s', 'edshield' ), ucfirst( $type ) ),
			),
			true
		);

		if ( is_wp_error( $id ) ) {
			continue;
		}

		$data = array( elementor_section_data( 'x', array() ) );

		$data[0]['elements'][0]['widgetType'] = 'edst-site-' . $type;
		$data[0]['elements'][0]['settings']   = array();

		set_elementor_data( (int) $id, $data, $type );
		wp_set_object_terms( (int) $id, $type, 'elementor_library_type' );
		/* translators: %s: template title. */
		$report[] = sprintf( __( 'Created the template "%s". Open Templates › Theme Builder to set its display conditions.', 'edshield' ), get_the_title( $id ) );
	}

	return $report;
}

/**
 * The setup screen.
 */
function setup_screen(): void {
	$report     = get_transient( 'edst_setup_report_' . get_current_user_id() );
	$companions = companion_plugins();
	$existing   = array();

	if ( $report ) {
		delete_transient( 'edst_setup_report_' . get_current_user_id() );
	}

	foreach ( layouts_index() as $key => $meta ) {
		$id = layout_page_id( $key );

		if ( $id ) {
			$existing[ $key ] = $id;
		}
	}

	$action = esc_url( admin_url( 'admin-post.php' ) );
	$form   = static function ( string $task, string $inner, string $label, string $class = 'button button-primary' ) use ( $action ): void {
		echo '<form method="post" action="' . $action . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		wp_nonce_field( 'edst_setup' );
		echo '<input type="hidden" name="action" value="edst_setup"><input type="hidden" name="edst_task" value="' . esc_attr( $task ) . '">' . $inner . '<p><button type="submit" class="' . esc_attr( $class ) . '">' . esc_html( $label ) . '</button></p></form>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	};
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'ED Shield Setup', 'edshield' ); ?></h1>

		<?php if ( $report ) : ?>
			<div class="notice notice-success"><ul>
				<?php foreach ( (array) $report as $line ) : ?>
					<li><?php echo esc_html( $line ); ?></li>
				<?php endforeach; ?>
			</ul></div>
		<?php endif; ?>

		<table class="widefat striped" style="max-width:900px;margin:16px 0 24px"><tbody>
			<tr><th style="width:280px"><?php esc_html_e( 'Theme version', 'edshield' ); ?></th><td><?php echo esc_html( VERSION ); ?></td></tr>
			<tr><th><?php esc_html_e( 'Elementor', 'edshield' ); ?></th><td><?php echo elementor_active() ? esc_html( 'Active ' . ( defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '' ) ) : esc_html__( 'Not active (pages are rendered by the theme)', 'edshield' ); ?></td></tr>
			<tr><th><?php esc_html_e( 'Elementor Pro', 'edshield' ); ?></th><td><?php echo defined( 'ELEMENTOR_PRO_VERSION' ) ? esc_html( 'Active ' . ELEMENTOR_PRO_VERSION ) : esc_html__( 'Not active', 'edshield' ); ?></td></tr>
			<tr><th><?php esc_html_e( 'Starter pages', 'edshield' ); ?></th><td><?php echo esc_html( count( $existing ) . ' / ' . count( layouts_index() ) ); ?></td></tr>
			<tr><th><?php esc_html_e( 'Front page', 'edshield' ); ?></th><td><?php echo 'page' === get_option( 'show_on_front' ) ? esc_html( get_the_title( (int) get_option( 'page_on_front' ) ) ) : esc_html__( 'Latest posts', 'edshield' ); ?></td></tr>
		</tbody></table>

		<?php if ( $companions ) : ?>
			<div style="max-width:900px;padding:12px 16px;border-left:4px solid #d63638;background:#fff;margin-bottom:24px">
				<h2 style="margin-top:0"><?php esc_html_e( 'Biznax companion plugins are active', 'edshield' ); ?></h2>
				<p><?php echo esc_html( implode( ', ', $companions ) ); ?> <?php esc_html_e( 'belong to the Biznax theme. Bravis Addons stops every front-end page from loading under any other theme. Deactivate them; they can be reactivated at any time.', 'edshield' ); ?></p>
				<?php $form( 'companions', '', __( 'Deactivate Biznax companion plugins', 'edshield' ), 'button' ); ?>
			</div>
		<?php endif; ?>

		<h2><?php esc_html_e( 'Create pages, menus and placeholders', 'edshield' ); ?></h2>
		<p style="max-width:760px"><?php esc_html_e( 'Creates Home, Services and nine service pages, Work, Industries, About, Process, Engagement models, FAQ, Careers, Contact and Insights. Pages that already exist are never changed.', 'edshield' ); ?></p>
		<?php
		$fields  = '<p><label><input type="checkbox" name="edst_front" value="1" checked> ' . esc_html__( 'Set Home as the front page and Insights as the posts page', 'edshield' ) . '</label></p>';
		$fields .= '<p><label><input type="checkbox" name="edst_menus" value="1" checked> ' . esc_html__( 'Create the main menu (with the Services mega menu) and footer menus for empty locations', 'edshield' ) . '</label></p>';
		$fields .= '<p><label><input type="checkbox" name="edst_projects" value="1" checked> ' . esc_html__( 'Add three clearly marked placeholder case studies', 'edshield' ) . '</label></p>';
		$fields .= elementor_active()
			? '<p><label><input type="checkbox" name="edst_elementor" value="1" checked> ' . esc_html__( 'Build the new pages in Elementor (every section becomes an editable ED Shield widget)', 'edshield' ) . '</label></p>'
			: '<p><em>' . esc_html__( 'Elementor is not active: pages will be rendered by the theme. Activate Elementor first to get Elementor-editable pages.', 'edshield' ) . '</em></p>';
		$form( 'import', $fields, __( 'Create pages and menus', 'edshield' ) );
		?>

		<?php if ( elementor_active() ) : ?>
			<h2><?php esc_html_e( 'Elementor tools', 'edshield' ); ?></h2>
			<?php $form( 'colors', '<p>' . esc_html__( 'Adds the ED Shield palette to Site Settings › Global Colors (changing those colours in Elementor recolours the theme) and switches off Elementor’s default colours and fonts, so Elementor’s own widgets match the theme.', 'edshield' ) . '</p>', __( 'Match Elementor to the theme', 'edshield' ), 'button' ); ?>

			<?php if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) : ?>
				<?php $form( 'pro_templates', '<p>' . esc_html__( 'Creates Theme Builder header and footer templates that contain the theme header (with mega menu) and footer, ready for display conditions and further editing.', 'edshield' ) . '</p>', __( 'Create header and footer templates', 'edshield' ), 'button' ); ?>
			<?php endif; ?>

			<?php if ( $existing ) : ?>
				<?php
				$options = '';

				foreach ( $existing as $key => $id ) {
					if ( 'insights' !== $key ) {
						$options .= '<option value="' . esc_attr( $key ) . '">' . esc_html( get_the_title( $id ) ) . '</option>';
					}
				}

				$form( 'rebuild', '<p>' . esc_html__( 'Reset one page to its starter layout. This replaces the page’s current Elementor content.', 'edshield' ) . '</p><p><select name="edst_layout">' . $options . '</select> <label><input type="checkbox" name="edst_confirm" value="1"> ' . esc_html__( 'I understand the current content of this page will be replaced', 'edshield' ) . '</label></p>', __( 'Reset page layout', 'edshield' ), 'button' );
				?>
			<?php endif; ?>
		<?php endif; ?>

		<h2><?php esc_html_e( 'Finish setting up', 'edshield' ); ?></h2>
		<ul style="list-style:disc;padding-left:20px">
			<li><a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=edst' ) ); ?>"><?php esc_html_e( 'Add contact details, social links and the header button in the Customizer', 'edshield' ); ?></a></li>
			<li><a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><?php esc_html_e( 'Review the menus', 'edshield' ); ?></a></li>
			<li><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=edst_project' ) ); ?>"><?php esc_html_e( 'Replace the placeholder case studies', 'edshield' ); ?></a></li>
		</ul>
	</div>
	<?php
}
