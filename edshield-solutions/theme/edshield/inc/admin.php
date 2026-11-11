<?php
/**
 * Appearance › Import Demo Data: required plugins, one-click demo import and
 * the theme's Elementor tools. Also registers the demo with the One Click
 * Demo Import plugin when that plugin is active.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

const DEMO_PAGE = 'edshield-demo-import';

/**
 * Plugins the demo works with. Only Elementor is required, and only for
 * visual editing: without it the theme renders every page itself.
 *
 * @return array<string, array{name:string,file:string,required:bool,text:string,repo:bool}>
 */
function demo_plugins(): array {
	return array(
		'elementor'     => array(
			'name'     => 'Elementor',
			'file'     => 'elementor/elementor.php',
			'required' => true,
			'repo'     => true,
			'text'     => __( 'Required for visual editing. Every imported page is built from ED Shield widgets you can edit, move and restyle.', 'edshield' ),
		),
		'elementor-pro' => array(
			'name'     => 'Elementor Pro',
			'file'     => 'elementor-pro/elementor-pro.php',
			'required' => false,
			'repo'     => false,
			'text'     => __( 'Optional. Adds Theme Builder header and footer templates; the theme header and footer work without it.', 'edshield' ),
		),
	);
}

/**
 * Plugins that belong to the previous Biznax theme and break other themes.
 *
 * @return array<string, string> Plugin file => name.
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

/**
 * Whether One Click Demo Import is active (it then owns the menu label).
 */
function ocdi_active(): bool {
	return class_exists( '\OCDI\OneClickDemoImport' );
}

add_action( 'admin_menu', __NAMESPACE__ . '\demo_menu' );
/**
 * Appearance › Import Demo Data.
 */
function demo_menu(): void {
	$title = ocdi_active() ? __( 'ED Shield Demo', 'edshield' ) : __( 'Import Demo Data', 'edshield' );

	add_theme_page( __( 'Import Demo Data', 'edshield' ), $title, 'edit_theme_options', DEMO_PAGE, __NAMESPACE__ . '\demo_screen' );
}

add_action( 'admin_notices', __NAMESPACE__ . '\demo_notices' );
/**
 * Point new installs to the importer, and warn about Biznax companions.
 */
function demo_notices(): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( $screen && in_array( $screen->id, array( 'appearance_page_' . DEMO_PAGE, 'appearance_page_one-click-demo-import' ), true ) ) {
		return;
	}

	$link = esc_url( admin_url( 'themes.php?page=' . DEMO_PAGE ) );

	if ( companion_plugins() ) {
		echo '<div class="notice notice-error"><p><strong>' . esc_html__( 'ED Shield:', 'edshield' ) . '</strong> ' . esc_html( implode( ', ', companion_plugins() ) ) . ' ' . esc_html__( 'only work with the Biznax theme and can break pages under any other theme.', 'edshield' ) . ' <a href="' . $link . '">' . esc_html__( 'Review', 'edshield' ) . '</a></p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	if ( ! get_option( 'edst_demo_imported' ) ) {
		echo '<div class="notice notice-info"><p><strong>' . esc_html__( 'ED Shield is active.', 'edshield' ) . '</strong> ' . esc_html__( 'Import the complete website (all pages, menus, widgets and settings) in one click.', 'edshield' ) . ' <a class="button button-primary" style="margin-left:8px" href="' . $link . '">' . esc_html__( 'Import Demo Data', 'edshield' ) . '</a></p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

add_action( 'admin_post_edst_demo', __NAMESPACE__ . '\demo_handle' );
/**
 * Run an action from the screen.
 */
function demo_handle(): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to do this.', 'edshield' ) );
	}

	check_admin_referer( 'edst_demo' );

	$task   = isset( $_POST['edst_task'] ) ? sanitize_key( wp_unslash( $_POST['edst_task'] ) ) : '';
	$report = array();
	$pages  = array();

	switch ( $task ) {
		case 'import':
			$opts = array();

			foreach ( array_keys( demo_import_defaults() ) as $key ) {
				$opts[ $key ] = ! empty( $_POST[ 'edst_' . $key ] );
			}

			$result = demo_import( $opts );
			$report = $result['log'];
			$pages  = $result['pages'];
			break;

		case 'build':
			$count = demo_build_elementor_pages();
			/* translators: %d: number of pages. */
			$report[] = $count ? sprintf( _n( '%d page is now built in Elementor.', '%d pages are now built in Elementor.', $count, 'edshield' ), $count ) : __( 'Every imported page is already built in Elementor (or Elementor is not active).', 'edshield' );
			break;

		case 'rebuild':
			$layout = isset( $_POST['edst_layout'] ) ? sanitize_key( wp_unslash( $_POST['edst_layout'] ) ) : '';
			$id     = layout_page_id( $layout );

			if ( $id && ! empty( $_POST['edst_confirm'] ) && elementor_active() && 'insights' !== $layout ) {
				set_elementor_data( $id, elementor_layout_data( $layout ) );
				elementor_clear_cache();
				/* translators: %s: page title. */
				$report[] = sprintf( __( 'Reset "%s" to its demo layout.', 'edshield' ), get_the_title( $id ) );
			} else {
				$report[] = __( 'Nothing was changed. Choose a page, tick the confirmation box, and make sure Elementor is active.', 'edshield' );
			}
			break;

		case 'kit':
			$report[] = demo_apply_elementor_kit( demo_package()['elementor'] )
				? __( 'Elementor Site Settings updated: ED Shield colours, fonts and layout width.', 'edshield' )
				: __( 'No active Elementor kit was found.', 'edshield' );
			elementor_clear_cache();
			break;

		case 'pro_templates':
			$report = create_pro_templates();
			break;

		case 'builder_on':
		case 'builder_off':
			set_theme_mod( 'edst_theme_builder', 'builder_on' === $task );
			$report[] = 'builder_on' === $task
				? __( 'Elementor Pro Theme Builder templates are now used for the header, footer, single and archive locations where their display conditions apply.', 'edshield' )
				: __( 'The ED Shield header and footer are used again. Your Theme Builder templates are kept unchanged.', 'edshield' );
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

	set_transient(
		'edst_demo_report_' . get_current_user_id(),
		array(
			'task'  => $task,
			'log'   => $report,
			'pages' => $pages,
		),
		10 * MINUTE_IN_SECONDS
	);
	wp_safe_redirect( admin_url( 'themes.php?page=' . DEMO_PAGE . '&done=' . $task ) );
	exit;
}

add_action( 'admin_post_edst_plugin', __NAMESPACE__ . '\demo_plugin_handle' );
/**
 * Install and/or activate a plugin from the screen, then come back.
 */
function demo_plugin_handle(): void {
	$slug    = isset( $_GET['plugin'] ) ? sanitize_key( wp_unslash( $_GET['plugin'] ) ) : '';
	$plugins = demo_plugins();

	if ( ! isset( $plugins[ $slug ] ) || ! current_user_can( 'install_plugins' ) || ! current_user_can( 'activate_plugins' ) ) {
		wp_die( esc_html__( 'You are not allowed to do this.', 'edshield' ) );
	}

	check_admin_referer( 'edst_plugin_' . $slug );

	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	$file    = $plugins[ $slug ]['file'];
	$message = '';

	if ( ! file_exists( WP_PLUGIN_DIR . '/' . $file ) && $plugins[ $slug ]['repo'] ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		$api = plugins_api(
			'plugin_information',
			array(
				'slug'   => $slug,
				'fields' => array( 'sections' => false ),
			)
		);

		if ( is_wp_error( $api ) ) {
			$message = $api->get_error_message();
		} else {
			$upgrader = new \Plugin_Upgrader( new \WP_Ajax_Upgrader_Skin() );
			$result   = $upgrader->install( $api->download_link );

			if ( is_wp_error( $result ) ) {
				$message = $result->get_error_message();
			} elseif ( ! $result ) {
				$message = __( 'WordPress could not write the plugin files. Install the plugin from Plugins › Add New instead.', 'edshield' );
			}
		}
	}

	if ( '' === $message && file_exists( WP_PLUGIN_DIR . '/' . $file ) && ! is_plugin_active( $file ) ) {
		$result = activate_plugin( $file );

		if ( is_wp_error( $result ) ) {
			$message = $result->get_error_message();
		}

		// Stay on this screen instead of the plugin's welcome page.
		delete_transient( 'elementor_activation_redirect' );
	}

	set_transient(
		'edst_demo_report_' . get_current_user_id(),
		array(
			'task'  => 'plugin',
			/* translators: 1: plugin name, 2: error. */
			'log'   => array( '' === $message ? sprintf( __( '%s is installed and active.', 'edshield' ), $plugins[ $slug ]['name'] ) : sprintf( __( '%1$s could not be installed automatically: %2$s', 'edshield' ), $plugins[ $slug ]['name'], $message ) ),
			'pages' => array(),
		),
		10 * MINUTE_IN_SECONDS
	);
	wp_safe_redirect( admin_url( 'themes.php?page=' . DEMO_PAGE ) );
	exit;
}

/**
 * Status and action link for one plugin.
 *
 * @param string $slug Plugin slug.
 * @return array{status:string,label:string,url:string}
 */
function demo_plugin_state( string $slug ): array {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	$plugin = demo_plugins()[ $slug ];
	$file   = $plugin['file'];
	$action = wp_nonce_url( admin_url( 'admin-post.php?action=edst_plugin&plugin=' . $slug ), 'edst_plugin_' . $slug );

	if ( is_plugin_active( $file ) ) {
		return array(
			'status' => __( 'Active', 'edshield' ),
			'label'  => '',
			'url'    => '',
		);
	}

	if ( file_exists( WP_PLUGIN_DIR . '/' . $file ) ) {
		return array(
			'status' => __( 'Installed, not active', 'edshield' ),
			'label'  => current_user_can( 'activate_plugins' ) ? __( 'Activate', 'edshield' ) : '',
			'url'    => $action,
		);
	}

	if ( ! $plugin['repo'] ) {
		return array(
			'status' => __( 'Not installed', 'edshield' ),
			'label'  => __( 'Get it from elementor.com', 'edshield' ),
			'url'    => 'https://elementor.com/pro/',
		);
	}

	return array(
		'status' => __( 'Not installed', 'edshield' ),
		'label'  => current_user_can( 'install_plugins' ) ? __( 'Install and activate', 'edshield' ) : '',
		'url'    => $action,
	);
}

/**
 * Pro header and footer templates holding the theme header and footer.
 *
 * @return string[]
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
		set_theme_mod( 'edst_theme_builder', true );
		/* translators: %s: template title. */
		$report[] = sprintf( __( 'Created the template "%s". Open Templates › Theme Builder to set its display conditions.', 'edshield' ), get_the_title( $id ) );
	}

	return $report;
}

/**
 * The screen.
 */
function demo_screen(): void {
	$report   = get_transient( 'edst_demo_report_' . get_current_user_id() );
	$imported = get_option( 'edst_demo_imported' );
	$package  = demo_package();
	$counts   = array(
		'pages'    => count( (array) ( $package['content']['pages'] ?? array() ) ),
		'posts'    => count( (array) ( $package['content']['posts'] ?? array() ) ),
		'projects' => count( (array) ( $package['content']['projects'] ?? array() ) ),
		'menus'    => count( (array) ( $package['menus'] ?? array() ) ),
	);
	$existing = array();

	if ( $report ) {
		delete_transient( 'edst_demo_report_' . get_current_user_id() );
	}

	foreach ( layouts_index() as $key => $meta ) {
		$id = layout_page_id( $key );

		if ( $id ) {
			$existing[ $key ] = $id;
		}
	}

	$action = esc_url( admin_url( 'admin-post.php' ) );
	$form   = static function ( string $task, string $inner, string $label, string $class = 'button' ) use ( $action ): void {
		echo '<form method="post" action="' . $action . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		wp_nonce_field( 'edst_demo' );
		echo '<input type="hidden" name="action" value="edst_demo"><input type="hidden" name="edst_task" value="' . esc_attr( $task ) . '">' . $inner . '<p><button type="submit" class="' . esc_attr( $class ) . '">' . esc_html( $label ) . '</button></p></form>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	};
	$check  = static function ( string $key, string $label, bool $checked ): string {
		return '<label class="edst-demo__check"><input type="checkbox" name="edst_' . esc_attr( $key ) . '" value="1"' . ( $checked ? ' checked' : '' ) . '> ' . esc_html( $label ) . '</label>';
	};
	?>
	<div class="wrap edst-demo">
		<style>
			.edst-demo{max-width:1080px}
			.edst-demo__hero{display:grid;grid-template-columns:minmax(0,1fr) 380px;gap:32px;align-items:center;margin:20px 0 24px;padding:28px;border-radius:12px;background:#071533;color:#f5f7fb}
			.edst-demo__hero h2{margin:0 0 8px;color:#fff;font-size:24px}
			.edst-demo__hero p{margin:0 0 12px;color:#c9d1e3;font-size:14px;line-height:1.6}
			.edst-demo__hero img{display:block;width:100%;border-radius:8px;box-shadow:0 20px 40px rgba(0,0,0,.35)}
			.edst-demo__chips{display:flex;flex-wrap:wrap;gap:8px;margin:14px 0 0;padding:0;list-style:none}
			.edst-demo__chips li{margin:0;padding:4px 10px;border:1px solid rgba(233,196,106,.45);border-radius:99px;color:#e9c46a;font-size:12px}
			.edst-demo__card{margin:0 0 20px;padding:20px 24px;border:1px solid #dcdcde;border-radius:10px;background:#fff}
			.edst-demo__card h2{margin-top:0}
			.edst-demo__check{display:block;margin:8px 0}
			.edst-demo__plugins td,.edst-demo__plugins th{vertical-align:middle}
			.edst-demo__go{padding:6px 22px!important;font-size:15px!important;height:auto!important}
			.edst-demo .button-primary{background:#071533;border-color:#071533}
			.edst-demo .button-primary:hover{background:#13285a;border-color:#13285a}
			@media (max-width:900px){.edst-demo__hero{grid-template-columns:1fr}}
		</style>

		<h1><?php esc_html_e( 'Import Demo Data', 'edshield' ); ?></h1>

		<?php if ( $report ) : ?>
			<div class="notice notice-success is-dismissible"><ul style="list-style:disc;padding-left:18px">
				<?php foreach ( (array) $report['log'] as $line ) : ?>
					<li><?php echo esc_html( $line ); ?></li>
				<?php endforeach; ?>
			</ul>
			<?php if ( 'import' === ( $report['task'] ?? '' ) ) : ?>
				<p><a class="button button-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'View the website', 'edshield' ); ?></a> <a class="button" href="#edst-pages"><?php esc_html_e( 'See every imported page', 'edshield' ); ?></a></p>
			<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="edst-demo__hero">
			<div>
				<h2><?php esc_html_e( 'ED Shield: complete website', 'edshield' ); ?></h2>
				<p><?php esc_html_e( 'Imports the whole site in one click: every page with its design, the mega menu and footer menus, widgets, articles, placeholder case studies, theme settings and Elementor colours and fonts.', 'edshield' ); ?></p>
				<p><?php esc_html_e( 'Existing content is never edited or deleted. Running the import again only adds what is missing.', 'edshield' ); ?></p>
				<ul class="edst-demo__chips">
					<?php /* translators: %d: count. */ ?>
					<li><?php echo esc_html( sprintf( __( '%d pages', 'edshield' ), $counts['pages'] ) ); ?></li>
					<?php /* translators: %d: count. */ ?>
					<li><?php echo esc_html( sprintf( __( '%d articles', 'edshield' ), $counts['posts'] ) ); ?></li>
					<?php /* translators: %d: count. */ ?>
					<li><?php echo esc_html( sprintf( __( '%d case studies (placeholders)', 'edshield' ), $counts['projects'] ) ); ?></li>
					<?php /* translators: %d: count. */ ?>
					<li><?php echo esc_html( sprintf( __( '%d menus', 'edshield' ), $counts['menus'] ) ); ?></li>
					<li><?php esc_html_e( 'Widgets', 'edshield' ); ?></li>
					<li><?php esc_html_e( 'Theme settings', 'edshield' ); ?></li>
					<li><?php esc_html_e( 'Elementor design settings', 'edshield' ); ?></li>
				</ul>
			</div>
			<img src="<?php echo esc_url( uri( 'demo/preview.jpg' ) ); ?>" alt="<?php esc_attr_e( 'Preview of the ED Shield demo homepage', 'edshield' ); ?>">
		</div>

		<?php if ( companion_plugins() ) : ?>
			<div class="edst-demo__card" style="border-left:4px solid #d63638">
				<h2><?php esc_html_e( 'Biznax companion plugins are active', 'edshield' ); ?></h2>
				<p><?php echo esc_html( implode( ', ', companion_plugins() ) ); ?> <?php esc_html_e( 'belong to the Biznax theme. Bravis Addons stops front-end pages from loading under any other theme. Deactivate them; they can be reactivated at any time.', 'edshield' ); ?></p>
				<?php $form( 'companions', '', __( 'Deactivate Biznax companion plugins', 'edshield' ) ); ?>
			</div>
		<?php endif; ?>

		<div class="edst-demo__card">
			<h2><?php esc_html_e( '1. Plugins', 'edshield' ); ?></h2>
			<table class="widefat striped edst-demo__plugins"><tbody>
				<?php foreach ( demo_plugins() as $slug => $plugin ) : ?>
					<?php $state = demo_plugin_state( $slug ); ?>
					<tr>
						<th style="width:170px"><strong><?php echo esc_html( $plugin['name'] ); ?></strong><br><span style="color:<?php echo $plugin['required'] ? '#b32d2e' : '#646970'; ?>"><?php echo $plugin['required'] ? esc_html__( 'Required', 'edshield' ) : esc_html__( 'Optional', 'edshield' ); ?></span></th>
						<td><?php echo esc_html( $plugin['text'] ); ?></td>
						<td style="width:150px"><?php echo esc_html( $state['status'] ); ?></td>
						<td style="width:190px;text-align:right">
							<?php if ( '' !== $state['label'] ) : ?>
								<a class="button<?php echo $plugin['required'] ? ' button-primary' : ''; ?>" href="<?php echo esc_url( $state['url'] ); ?>"<?php echo $plugin['repo'] ? '' : ' target="_blank" rel="noopener"'; ?>><?php echo esc_html( $state['label'] ); ?></a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody></table>
			<?php if ( ! elementor_active() ) : ?>
				<p><?php esc_html_e( 'Activate Elementor before importing so every page is created as an Elementor page. The import also works without it: the theme then renders the same designs, and the pages can be converted to Elementor later.', 'edshield' ); ?></p>
			<?php endif; ?>
		</div>

		<div class="edst-demo__card">
			<h2><?php esc_html_e( '2. Import', 'edshield' ); ?></h2>
			<?php if ( $imported ) : ?>
				<?php /* translators: %s: date. */ ?>
				<p><?php echo esc_html( sprintf( __( 'The demo was imported on %s. Importing again only adds what is missing.', 'edshield' ), wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), (int) $imported['time'] ) ) ); ?></p>
			<?php endif; ?>
			<?php
			$fields  = $check( 'pages', __( 'Pages and their designs', 'edshield' ), true );
			$fields .= $check( 'posts', __( 'Articles and placeholder case studies', 'edshield' ), true );
			$fields .= $check( 'menus', __( 'Menus (main menu with Services mega menu, footer menus)', 'edshield' ), true );
			$fields .= $check( 'widgets', __( 'Widgets (footer contact column, article sidebar)', 'edshield' ), true );
			$fields .= $check( 'settings', __( 'Theme settings, site icon and Elementor colours and fonts', 'edshield' ), true );
			$fields .= $check( 'front', __( 'Make Home the front page and Insights the blog', 'edshield' ), true );
			$fields .= $check( 'samples', __( 'Move WordPress’s untouched sample content ("Hello world!" and "Sample Page") to the Trash', 'edshield' ), true );
			$fields .= $check( 'replace', __( 'Move existing pages that use the same addresses (for example /about/) to the Trash, so the demo pages get clean addresses. Trashed pages can be restored.', 'edshield' ), false );
			$fields .= '<p style="margin-top:14px"><strong>' . esc_html__( 'Elementor:', 'edshield' ) . '</strong> ' . ( elementor_active() ? esc_html__( 'active, pages will be built in Elementor.', 'edshield' ) : esc_html__( 'not active, pages will be rendered by the theme.', 'edshield' ) ) . '</p>';
			$form( 'import', $fields, __( 'Import demo', 'edshield' ), 'button button-primary edst-demo__go' );
			?>
		</div>

		<?php if ( $existing ) : ?>
			<div class="edst-demo__card" id="edst-pages">
				<h2><?php esc_html_e( '3. Your pages', 'edshield' ); ?></h2>
				<table class="widefat striped"><thead><tr>
					<th><?php esc_html_e( 'Page', 'edshield' ); ?></th>
					<th><?php esc_html_e( 'Address', 'edshield' ); ?></th>
					<th><?php esc_html_e( 'Edited with', 'edshield' ); ?></th>
					<th></th>
				</tr></thead><tbody>
					<?php foreach ( $existing as $key => $id ) : ?>
						<?php $elementor_built = built_with_elementor( $id ); ?>
						<tr>
							<td><strong><?php echo esc_html( get_the_title( $id ) ); ?></strong></td>
							<td><code><?php echo esc_html( wp_make_link_relative( (string) get_permalink( $id ) ) ); ?></code></td>
							<td><?php echo $elementor_built ? 'Elementor' : ( 'insights' === $key ? esc_html__( 'Blog (posts page)', 'edshield' ) : esc_html__( 'Theme layout', 'edshield' ) ); ?></td>
							<td style="text-align:right">
								<a class="button button-small" href="<?php echo esc_url( (string) get_permalink( $id ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'View', 'edshield' ); ?></a>
								<?php if ( $elementor_built && elementor_active() ) : ?>
									<a class="button button-small button-primary" href="<?php echo esc_url( admin_url( 'post.php?post=' . $id . '&action=elementor' ) ); ?>"><?php esc_html_e( 'Edit with Elementor', 'edshield' ); ?></a>
								<?php else : ?>
									<a class="button button-small" href="<?php echo esc_url( (string) get_edit_post_link( $id ) ); ?>"><?php esc_html_e( 'Edit', 'edshield' ); ?></a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody></table>
			</div>
		<?php endif; ?>

		<?php if ( elementor_active() ) : ?>
			<div class="edst-demo__card">
				<h2><?php esc_html_e( 'Elementor tools', 'edshield' ); ?></h2>
				<?php $form( 'build', '<p>' . esc_html__( 'Converts imported pages that are still rendered by the theme (for example because Elementor was activated after the import) into Elementor pages.', 'edshield' ) . '</p>', __( 'Build pages in Elementor', 'edshield' ) ); ?>
				<?php $form( 'kit', '<p>' . esc_html__( 'Adds the ED Shield colours and fonts to Site Settings › Global Colors and Global Fonts, and turns off Elementor’s default colours and fonts so Elementor widgets match the theme.', 'edshield' ) . '</p>', __( 'Match Elementor to the theme', 'edshield' ) ); ?>
				<?php if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) : ?>
					<?php
					$builder_on = theme_builder_enabled();
					$builder_p  = '<p><strong>' . esc_html__( 'Header and footer:', 'edshield' ) . '</strong> ' . ( $builder_on ? esc_html__( 'Elementor Pro Theme Builder templates are used where their display conditions apply.', 'edshield' ) : esc_html__( 'the ED Shield header and footer are used. Elementor Pro Theme Builder templates on this site (for example ones left from a previous theme) do not replace them.', 'edshield' ) ) . '</p>';
					$form( $builder_on ? 'builder_off' : 'builder_on', $builder_p, $builder_on ? __( 'Use the ED Shield header and footer', 'edshield' ) : __( 'Use Theme Builder templates instead', 'edshield' ) );
					$form( 'pro_templates', '<p>' . esc_html__( 'Creates Theme Builder header and footer templates that contain the theme header (with mega menu) and footer, ready for display conditions and further editing. This also switches the header and footer to Theme Builder templates.', 'edshield' ) . '</p>', __( 'Create header and footer templates', 'edshield' ) );
					?>
				<?php endif; ?>
				<?php
				if ( $existing ) {
					$options = '';

					foreach ( $existing as $key => $id ) {
						if ( 'insights' !== $key ) {
							$options .= '<option value="' . esc_attr( $key ) . '">' . esc_html( get_the_title( $id ) ) . '</option>';
						}
					}

					$form( 'rebuild', '<p>' . esc_html__( 'Reset one page to its demo layout. This replaces the page’s current Elementor content.', 'edshield' ) . '</p><p><select name="edst_layout">' . $options . '</select> <label><input type="checkbox" name="edst_confirm" value="1"> ' . esc_html__( 'I understand the current content of this page will be replaced', 'edshield' ) . '</label></p>', __( 'Reset page layout', 'edshield' ) );
				}
				?>
			</div>
		<?php endif; ?>

		<div class="edst-demo__card">
			<h2><?php esc_html_e( 'After the import', 'edshield' ); ?></h2>
			<ul style="list-style:disc;padding-left:18px">
				<li><a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=edst' ) ); ?>"><?php esc_html_e( 'Add your email, phone, address and social links (Customize › ED Shield)', 'edshield' ); ?></a></li>
				<li><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=edst_project' ) ); ?>"><?php esc_html_e( 'Replace the placeholder case studies with real projects', 'edshield' ); ?></a></li>
				<li><?php esc_html_e( 'Replace the placeholder testimonials in the Testimonials sections, or remove those sections', 'edshield' ); ?></li>
				<li><?php esc_html_e( 'Complete the Privacy Policy and Terms of Service pages', 'edshield' ); ?></li>
				<li><a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><?php esc_html_e( 'Review the menus', 'edshield' ); ?></a> · <a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php esc_html_e( 'Review the widgets', 'edshield' ); ?></a></li>
			</ul>
		</div>
	</div>
	<?php
}

/* One Click Demo Import ---------------------------------------------------- */

add_filter( 'ocdi/import_files', __NAMESPACE__ . '\ocdi_import_files' );
/**
 * Offer the ED Shield demo on One Click Demo Import's screen. The import is
 * done by the theme's own importer (see ocdi_after_import), so the demo is
 * identical whichever screen is used.
 *
 * @param mixed $files Registered imports.
 * @return mixed
 */
function ocdi_import_files( $files ) {
	$files   = is_array( $files ) ? $files : array();
	$files[] = array(
		'import_file_name'         => 'ED Shield — Complete website',
		'categories'               => array( 'Business' ),
		'import_preview_image_url' => uri( 'demo/preview.jpg' ),
		'preview_url'              => home_url( '/' ),
		'import_notice'            => __( 'Imports every page with its design, menus, widgets, articles, placeholder case studies, theme settings and Elementor colours and fonts. Activate Elementor first so the pages are built in Elementor.', 'edshield' ),
	);

	return $files;
}

add_action( 'ocdi/after_import', __NAMESPACE__ . '\ocdi_after_import' );
/**
 * Run the theme importer when the ED Shield demo is chosen in OCDI.
 *
 * @param mixed $selected Selected import.
 */
function ocdi_after_import( $selected ): void {
	if ( is_array( $selected ) && 0 === strpos( (string) ( $selected['import_file_name'] ?? '' ), 'ED Shield' ) ) {
		demo_import();
	}
}

add_filter( 'ocdi/register_plugins', __NAMESPACE__ . '\ocdi_plugins' );
/**
 * Ask One Click Demo Import to install Elementor before importing.
 *
 * @param mixed $plugins Registered plugins.
 * @return mixed
 */
function ocdi_plugins( $plugins ) {
	$plugins   = is_array( $plugins ) ? $plugins : array();
	$plugins[] = array(
		'name'        => 'Elementor',
		'description' => __( 'Required for visual editing of the imported pages.', 'edshield' ),
		'slug'        => 'elementor',
		'required'    => true,
		'preselected' => true,
	);

	return $plugins;
}

add_filter( 'ocdi/regenerate_thumbnails_in_content_import', '__return_false' );
