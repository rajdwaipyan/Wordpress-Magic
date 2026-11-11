<?php
/**
 * Demo content package and one-click import.
 *
 * The package lives in demo/ as JSON files (pages with their sections, posts,
 * case studies, menus, widgets, theme settings, site settings and Elementor
 * design settings). tools/export-demo.php writes those files from the theme's
 * own layouts, so the package and the theme never disagree. When a file is
 * missing, the same data is built in memory.
 *
 * demo_import() creates everything on the current site. It is safe to run
 * more than once: existing demo pages, posts, menus and settings are kept.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Package file names.
 *
 * @return array<string, string>
 */
function demo_files(): array {
	return array(
		'content'    => 'content.json',
		'menus'      => 'menus.json',
		'widgets'    => 'widgets.json',
		'customizer' => 'customizer.json',
		'settings'   => 'settings.json',
		'elementor'  => 'elementor-kit.json',
	);
}

/**
 * Menu structure with link tokens (page:<layout>, service:<key>, posts or a
 * URL). Used by the default navigation and by the demo import.
 *
 * @return array<string, array{name:string,location:string,items:array<int, array<string, mixed>>}>
 */
function menu_blueprint(): array {
	$item = static function ( string $title, string $link, string $description = '', string $classes = '', array $children = array() ): array {
		return array(
			'title'       => $title,
			'link'        => $link,
			'description' => $description,
			'classes'     => $classes,
			'children'    => $children,
		);
	};

	$services = array();

	foreach ( services() as $key => $service ) {
		$services[] = $item( $service['title'], 'service:' . $key, $service['nav'], 'edst-icon-' . $service['icon'] );
	}

	foreach ( audience_pages() as $key => $page ) {
		$services[] = $item( $page['title'], 'page:audience-' . $key, $page['nav'], 'edst-icon-' . $page['icon'] );
	}

	$services[] = $item( __( 'View all services', 'edshield' ), 'page:services', '', 'edst-view-all' );

	$resources = array(
		$item( __( 'Website audit', 'edshield' ), 'page:audit', __( 'Your website’s weak spots, prioritised', 'edshield' ), 'edst-icon-chart' ),
		$item( __( 'Technology', 'edshield' ), 'page:technology', __( 'The platforms we build on', 'edshield' ), 'edst-icon-layers' ),
		$item( __( 'Insights', 'edshield' ), 'posts', __( 'Notes on technology and growth', 'edshield' ), 'edst-icon-spark' ),
	);

	$company = array(
		$item( __( 'About us', 'edshield' ), 'page:about', __( 'The team behind the work', 'edshield' ), 'edst-icon-users' ),
		$item( __( 'Our process', 'edshield' ), 'page:process', __( 'How we take you to launch', 'edshield' ), 'edst-icon-flag' ),
		$item( __( 'Pricing', 'edshield' ), 'page:engagement', __( 'Ways to work with us', 'edshield' ), 'edst-icon-compass' ),
		$item( __( 'FAQ', 'edshield' ), 'page:faq', __( 'Common questions, answered', 'edshield' ), 'edst-icon-chat' ),
		$item( __( 'Careers', 'edshield' ), 'page:careers', __( 'Join the team', 'edshield' ), 'edst-icon-rocket' ),
		$item( __( 'Contact', 'edshield' ), 'page:contact', __( 'Start a conversation', 'edshield' ), 'edst-icon-mail' ),
		$item( __( 'Industries we work with', 'edshield' ), 'page:industries', '', 'edst-view-all' ),
	);

	return array(
		'primary'   => array(
			'name'     => __( 'ED Shield main menu', 'edshield' ),
			'location' => 'primary',
			'items'    => array(
				$item( __( 'Work', 'edshield' ), 'page:work' ),
				$item( __( 'Services', 'edshield' ), 'page:services', '', 'edst-mega', $services ),
				$item( __( 'Resources', 'edshield' ), 'posts', '', '', $resources ),
				$item( __( 'Company', 'edshield' ), 'page:about', '', '', $company ),
			),
		),
		'company'   => array(
			'name'     => __( 'ED Shield footer: company', 'edshield' ),
			'location' => 'company',
			'items'    => array(
				$item( __( 'About', 'edshield' ), 'page:about' ),
				$item( __( 'Our process', 'edshield' ), 'page:process' ),
				$item( __( 'Work', 'edshield' ), 'page:work' ),
				$item( __( 'Industries', 'edshield' ), 'page:industries' ),
				$item( __( 'Pricing', 'edshield' ), 'page:engagement' ),
				$item( __( 'Careers', 'edshield' ), 'page:careers' ),
				$item( __( 'Contact', 'edshield' ), 'page:contact' ),
			),
		),
		'resources' => array(
			'name'     => __( 'ED Shield footer: resources', 'edshield' ),
			'location' => 'resources',
			'items'    => array(
				$item( __( 'Insights', 'edshield' ), 'posts' ),
				$item( __( 'Website audit', 'edshield' ), 'page:audit' ),
				$item( __( 'Technology', 'edshield' ), 'page:technology' ),
				$item( __( 'For startups', 'edshield' ), 'page:audience-startups' ),
				$item( __( 'For growing businesses', 'edshield' ), 'page:audience-growth' ),
				$item( __( 'FAQ', 'edshield' ), 'page:faq' ),
			),
		),
		'legal'     => array(
			'name'     => __( 'ED Shield footer: legal', 'edshield' ),
			'location' => 'legal',
			'items'    => array(
				$item( __( 'Privacy policy', 'edshield' ), 'page:privacy' ),
				$item( __( 'Terms of service', 'edshield' ), 'page:terms' ),
			),
		),
	);
}

/**
 * Widgets for the widget areas, as block widgets. Links use {{token}}.
 *
 * @return array<string, string[]>
 */
function demo_widgets(): array {
	$button = static function ( string $label, string $token ): string {
		return '<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{{' . $token . '}}">' . esc_html( $label ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons -->';
	};

	return array(
		'footer-contact'  => array(
			'<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . esc_html__( 'Get in touch', 'edshield' ) . '</h3><!-- /wp:heading --><!-- wp:paragraph --><p>' . esc_html__( 'Tell us what you are building and we will reply with a clear, practical next step.', 'edshield' ) . '</p><!-- /wp:paragraph -->' . $button( __( 'Start a project', 'edshield' ), 'page:contact' ),
		),
		'article-sidebar' => array(
			'<!-- wp:search {"label":"' . esc_attr__( 'Search articles', 'edshield' ) . '","buttonText":"' . esc_attr__( 'Search', 'edshield' ) . '"} /-->',
			'<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . esc_html__( 'Latest articles', 'edshield' ) . '</h3><!-- /wp:heading --><!-- wp:latest-posts {"postsToShow":4} /-->',
			'<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . esc_html__( 'Topics', 'edshield' ) . '</h3><!-- /wp:heading --><!-- wp:categories /-->',
			'<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . esc_html__( 'Need a hand?', 'edshield' ) . '</h3><!-- /wp:heading --><!-- wp:paragraph --><p>' . esc_html__( 'Talk to the team about your website, systems or security.', 'edshield' ) . '</p><!-- /wp:paragraph -->' . $button( __( 'Book a discovery call', 'edshield' ), 'page:contact' ),
		),
	);
}

/**
 * Theme settings (Customizer). Links use {{token}}.
 *
 * @return array<string, mixed>
 */
function demo_customizer(): array {
	return array(
		'edst_announce_on'    => true,
		'edst_announce_text'  => __( 'From websites to AI automation: one accountable technology partner.', 'edshield' ),
		'edst_announce_label' => __( 'Book a discovery call', 'edshield' ),
		'edst_announce_url'   => '{{page:contact}}',
		'edst_cta_label'      => __( 'Start a project', 'edshield' ),
		'edst_cta_url'        => '{{page:contact}}',
		'edst_mega_eyebrow'   => __( 'Not sure where to start?', 'edshield' ),
		'edst_mega_title'     => __( 'Book a discovery call', 'edshield' ),
		'edst_mega_text'      => __( 'A focused conversation about your goals, systems and constraints, and the most sensible first step.', 'edshield' ),
		'edst_mega_label'     => __( 'Book a call', 'edshield' ),
		'edst_mega_url'       => '{{page:contact}}',
		'edst_footer_tagline' => __( 'A technology and digital transformation partner for organisations of every size, in Bangladesh and internationally.', 'edshield' ),
		'edst_footer_status'  => __( 'Engineering digital growth. Empowering businesses.', 'edshield' ),
		'edst_footer_wordmark' => true,
	);
}

/**
 * Site settings. Page values are layout keys.
 *
 * @return array<string, mixed>
 */
function demo_settings(): array {
	return array(
		'show_on_front'              => 'page',
		'page_on_front'              => 'home',
		'page_for_posts'             => 'insights',
		'wp_page_for_privacy_policy' => 'privacy',
		'posts_per_page'             => 9,
		'permalink_structure'        => '/%postname%/',
		'blogdescription'            => __( 'Engineering digital growth. Empowering businesses.', 'edshield' ),
	);
}

/**
 * Elementor Site Settings (global colours, global fonts, layout).
 *
 * @return array<string, mixed>
 */
function demo_elementor_kit(): array {
	$font = static function ( string $id, string $title, string $weight ): array {
		return array(
			'_id'                    => $id,
			'title'                  => $title,
			'typography_typography'  => 'custom',
			'typography_font_family' => 'Geist',
			'typography_font_weight' => $weight,
		);
	};

	return array(
		'system_colors'     => array(
			array( '_id' => 'primary', 'title' => __( 'Primary', 'edshield' ), 'color' => '#071533' ),
			array( '_id' => 'secondary', 'title' => __( 'Secondary', 'edshield' ), 'color' => '#D8A848' ),
			array( '_id' => 'text', 'title' => __( 'Text', 'edshield' ), 'color' => '#5B6478' ),
			array( '_id' => 'accent', 'title' => __( 'Accent', 'edshield' ), 'color' => '#E9C46A' ),
		),
		'custom_colors'     => array(
			array( '_id' => 'edstnavy', 'title' => __( 'ED Shield Navy', 'edshield' ), 'color' => '#071533' ),
			array( '_id' => 'edstnavy2', 'title' => __( 'ED Shield Navy (raised)', 'edshield' ), 'color' => '#0C1D44' ),
			array( '_id' => 'edstgold', 'title' => __( 'ED Shield Gold', 'edshield' ), 'color' => '#D8A848' ),
			array( '_id' => 'edstgold2', 'title' => __( 'ED Shield Gold (light)', 'edshield' ), 'color' => '#E9C46A' ),
			array( '_id' => 'edstpaper', 'title' => __( 'ED Shield Light grey', 'edshield' ), 'color' => '#F6F7F9' ),
			array( '_id' => 'edstslate', 'title' => __( 'ED Shield Slate', 'edshield' ), 'color' => '#5B6478' ),
		),
		'system_typography' => array(
			$font( 'primary', __( 'Primary', 'edshield' ), '600' ),
			$font( 'secondary', __( 'Secondary', 'edshield' ), '500' ),
			$font( 'text', __( 'Text', 'edshield' ), '400' ),
			$font( 'accent', __( 'Accent', 'edshield' ), '600' ),
		),
		'container_width'   => array(
			'unit'  => 'px',
			'size'  => 1312,
			'sizes' => array(),
		),
		'options'           => array(
			'elementor_disable_color_schemes'      => 'yes',
			'elementor_disable_typography_schemes' => 'yes',
			'elementor_google_font'                => '0',
		),
	);
}

/**
 * Build the whole package in memory from the theme's layouts and content.
 *
 * @return array<string, mixed>
 */
function demo_package_build(): array {
	$pages = array();

	foreach ( layouts_index() as $key => $meta ) {
		$sections = array();

		foreach ( layout_sections( $key ) as $section ) {
			$sections[] = array(
				'type'     => $section[0],
				'settings' => section_settings( $section[0], $section[1] ),
			);
		}

		$pages[] = array(
			'layout'   => $key,
			'title'    => $meta['title'],
			'slug'     => basename( $meta['path'] ),
			'parent'   => $meta['parent'],
			'sections' => $sections,
		);
	}

	$posts = array();

	foreach ( demo_posts() as $post ) {
		$posts[] = $post;
	}

	$projects = array();

	foreach ( demo_projects() as $project ) {
		$projects[] = array(
			'title'   => $project['title'],
			'slug'    => sanitize_title( $project['title'] ),
			'excerpt' => __( 'Placeholder: replace with a one-sentence summary of a real project.', 'edshield' ),
			'content' => '<!-- wp:paragraph --><p>' . __( 'Placeholder: describe what was built, how it was delivered and what changed for the client. Add images in a gallery below.', 'edshield' ) . '</p><!-- /wp:paragraph -->',
			'meta'    => array(
				'client'       => __( 'Client name', 'edshield' ),
				'industry'     => $project['industry'],
				'services'     => $project['services'],
				'year'         => '',
				'website'      => '',
				'metric_value' => '—',
				'metric_label' => __( 'Add a measured result', 'edshield' ),
				'challenge'    => __( 'Placeholder: the problem the client faced and why it mattered.', 'edshield' ),
				'goal'         => __( 'Placeholder: what success looked like.', 'edshield' ),
				'objectives'   => implode( "\n", array( __( 'Placeholder objective one', 'edshield' ), __( 'Placeholder objective two', 'edshield' ), __( 'Placeholder objective three', 'edshield' ) ) ),
				'results'      => implode( "\n", array( '— | ' . __( 'Result placeholder', 'edshield' ), '— | ' . __( 'Result placeholder', 'edshield' ), '— | ' . __( 'Result placeholder', 'edshield' ) ) ),
				'art'          => $project['art'],
			),
		);
	}

	return array(
		'content'    => array(
			'version'  => VERSION,
			'pages'    => $pages,
			'posts'    => $posts,
			'projects' => $projects,
		),
		'menus'      => menu_blueprint(),
		'widgets'    => demo_widgets(),
		'customizer' => demo_customizer(),
		'settings'   => demo_settings(),
		'elementor'  => demo_elementor_kit(),
	);
}

/**
 * The demo package: the JSON files in demo/, or the in-memory build.
 *
 * @return array<string, mixed>
 */
function demo_package(): array {
	$built   = null;
	$package = array();

	foreach ( demo_files() as $part => $file ) {
		$path = DIR . '/demo/' . $file;
		$data = is_readable( $path ) ? json_decode( (string) file_get_contents( $path ), true ) : null; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file.

		if ( ! is_array( $data ) ) {
			$built = $built ?? demo_package_build();
			$data  = $built[ $part ];
		}

		$package[ $part ] = $data;
	}

	return $package;
}

/**
 * Replace {{token}} placeholders with real URLs.
 *
 * @param string $text Text.
 */
function demo_tokens( string $text ): string {
	return (string) preg_replace_callback(
		'/\{\{([a-z]+:[a-z0-9_#-]+|posts|home)\}\}/',
		static function ( array $m ): string {
			return esc_url( resolve_url( $m[1] ) );
		},
		$text
	);
}

/**
 * Import options with their defaults.
 *
 * @return array<string, bool>
 */
function demo_import_defaults(): array {
	return array(
		'pages'    => true,
		'posts'    => true,
		'menus'    => true,
		'widgets'  => true,
		'settings' => true,
		'front'    => true,
		'samples'  => true,
		'replace'  => false,
	);
}

/**
 * Run the import.
 *
 * @param array<string, bool> $opts Options, see demo_import_defaults().
 * @return array{log:string[],pages:int[],created:array<string,int>}
 */
function demo_import( array $opts = array() ): array {
	$opts    = array_merge( demo_import_defaults(), array_map( 'boolval', $opts ) );
	$package = demo_package();
	$log     = array();
	$created = array(
		'pages'    => 0,
		'posts'    => 0,
		'projects' => 0,
		'menus'    => 0,
		'widgets'  => 0,
	);

	if ( function_exists( 'set_time_limit' ) ) {
		set_time_limit( 300 ); // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged -- long import.
	}

	wp_defer_term_counting( true );

	$elementor = elementor_active();
	$page_ids  = array();

	/* WordPress's own sample post and page, if never edited -------------- */
	if ( $opts['samples'] ) {
		foreach ( array( array( 'hello-world', 'post' ), array( 'sample-page', 'page' ) ) as $sample ) {
			$found = get_page_by_path( $sample[0], OBJECT, $sample[1] );

			if ( $found && 'publish' === $found->post_status && $found->post_modified_gmt === $found->post_date_gmt && ! get_post_meta( $found->ID, '_edst_layout', true ) ) {
				wp_trash_post( $found->ID );
				/* translators: %s: title. */
				$log[] = sprintf( __( 'Moved the WordPress sample content "%s" to the Trash.', 'edshield' ), get_the_title( $found ) );
			}
		}
	}

	/* Pages ------------------------------------------------------------- */
	if ( $opts['pages'] ) {
		$order = 0;

		foreach ( (array) ( $package['content']['pages'] ?? array() ) as $page ) {
			$layout = (string) ( $page['layout'] ?? '' );

			if ( '' === $layout ) {
				continue;
			}

			$existing = layout_page_id( $layout, true );

			if ( $existing ) {
				$page_ids[ $layout ] = $existing;
				continue;
			}

			$parent_id = '' !== (string) ( $page['parent'] ?? '' ) ? (int) ( $page_ids[ $page['parent'] ] ?? 0 ) : 0;
			$slug      = sanitize_title( (string) ( $page['slug'] ?? $layout ) );
			$path      = $parent_id ? get_page_uri( $parent_id ) . '/' . $slug : $slug;
			$conflict  = get_page_by_path( $path, OBJECT, 'page' );

			// WordPress's own "Privacy Policy" draft, never edited, gives way to the
			// demo page so the policy keeps its usual address. It goes to the Trash.
			$wp_draft = $conflict
				&& 'privacy' === $layout
				&& 'draft' === $conflict->post_status
				&& (int) get_option( 'wp_page_for_privacy_policy' ) === (int) $conflict->ID
				&& $conflict->post_modified_gmt === $conflict->post_date_gmt;

			if ( $conflict && ( $opts['replace'] || $wp_draft ) ) {
				wp_trash_post( $conflict->ID );
				/* translators: %s: page title. */
				$log[] = sprintf( __( 'Moved the existing page "%s" to the Trash so the demo page can use its address.', 'edshield' ), get_the_title( $conflict ) );
			}

			$id = wp_insert_post(
				array(
					'post_type'      => 'page',
					'post_status'    => 'publish',
					'post_title'     => (string) ( $page['title'] ?? $layout ),
					'post_name'      => $slug,
					'post_parent'    => $parent_id,
					'post_content'   => '',
					'comment_status' => 'closed',
					'menu_order'     => $order++,
				),
				true
			);

			if ( is_wp_error( $id ) ) {
				/* translators: 1: page title, 2: error. */
				$log[] = sprintf( __( 'Could not create "%1$s": %2$s', 'edshield' ), (string) ( $page['title'] ?? $layout ), $id->get_error_message() );
				continue;
			}

			update_post_meta( $id, '_edst_layout', $layout );
			update_post_meta( $id, '_edst_demo', VERSION );
			$page_ids[ $layout ] = (int) $id;
			++$created['pages'];
		}

		// Lookups below must see the new pages.
		layout_page_id( '', true );

		// Designs: Elementor pages when Elementor is active (links resolved now
		// that every page exists); otherwise the theme renders each layout.
		if ( $elementor ) {
			foreach ( (array) ( $package['content']['pages'] ?? array() ) as $page ) {
				$id = (int) ( $page_ids[ $page['layout'] ] ?? 0 );

				if ( $id && ! empty( $page['sections'] ) && ! built_with_elementor( $id ) && '' === trim( (string) get_post_field( 'post_content', $id ) ) ) {
					set_elementor_data( $id, elementor_sections_data( (array) $page['sections'] ) );
				}
			}
		}

		$log[] = $created['pages']
			/* translators: %d: number of pages. */
			? sprintf( _n( '%d page created.', '%d pages created.', $created['pages'], 'edshield' ), $created['pages'] )
			: __( 'All demo pages already existed and were kept as they are.', 'edshield' );
		$log[] = $elementor
			? __( 'Pages are built in Elementor: every section is an editable ED Shield widget.', 'edshield' )
			: __( 'Elementor is not active, so the theme renders the page designs. Activate Elementor and use "Build pages in Elementor" below to edit them visually.', 'edshield' );
	}

	/* Articles and case studies ---------------------------------------- */
	if ( $opts['posts'] ) {
		foreach ( (array) ( $package['content']['posts'] ?? array() ) as $post ) {
			$slug = sanitize_title( (string) ( $post['slug'] ?? '' ) );

			if ( '' === $slug || get_page_by_path( $slug, OBJECT, 'post' ) ) {
				continue;
			}

			$category = (string) ( $post['category'] ?? '' );
			$term     = '' !== $category ? term_exists( $category, 'category' ) : null;
			$term     = $term ? $term : ( '' !== $category ? wp_insert_term( $category, 'category' ) : null );
			$term_id  = is_array( $term ) ? (int) $term['term_id'] : 0;

			$id = wp_insert_post(
				array(
					'post_type'     => 'post',
					'post_status'   => 'publish',
					'post_title'    => (string) $post['title'],
					'post_name'     => $slug,
					'post_excerpt'  => (string) ( $post['excerpt'] ?? '' ),
					'post_content'  => wp_slash( (string) ( $post['content'] ?? '' ) ),
					'post_category' => $term_id ? array( $term_id ) : array(),
				),
				true
			);

			if ( ! is_wp_error( $id ) ) {
				update_post_meta( $id, '_edst_demo', VERSION );
				update_post_meta( $id, '_edst_art', sanitize_key( str_replace( ':', '-', (string) ( $post['art'] ?? '' ) ) ) );
				++$created['posts'];
			}
		}

		if ( post_type_exists( 'edst_project' ) ) {
			foreach ( (array) ( $package['content']['projects'] ?? array() ) as $project ) {
				$slug = sanitize_title( (string) ( $project['slug'] ?? '' ) );

				if ( '' === $slug || get_page_by_path( $slug, OBJECT, 'edst_project' ) ) {
					continue;
				}

				$id = wp_insert_post(
					array(
						'post_type'    => 'edst_project',
						'post_status'  => 'publish',
						'post_title'   => (string) $project['title'],
						'post_name'    => $slug,
						'post_excerpt' => (string) ( $project['excerpt'] ?? '' ),
						'post_content' => wp_slash( (string) ( $project['content'] ?? '' ) ),
					),
					true
				);

				if ( ! is_wp_error( $id ) ) {
					update_post_meta( $id, '_edst_project', (array) ( $project['meta'] ?? array() ) );
					update_post_meta( $id, '_edst_demo', VERSION );
					++$created['projects'];
				}
			}
		}

		/* translators: 1: articles, 2: case studies. */
		$log[] = sprintf( __( '%1$d articles and %2$d placeholder case studies added.', 'edshield' ), $created['posts'], $created['projects'] );
	}

	/* Site settings (front page first: menus link to the posts page) ---- */
	if ( $opts['settings'] ) {
		$settings = (array) ( $package['settings'] ?? array() );

		if ( $opts['front'] ) {
			$front = layout_page_id( (string) ( $settings['page_on_front'] ?? 'home' ) );
			$blog  = layout_page_id( (string) ( $settings['page_for_posts'] ?? 'insights' ) );

			if ( $front && $blog ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $front );
				update_option( 'page_for_posts', $blog );
				$log[] = __( 'Home is now the front page and Insights is the blog.', 'edshield' );
			}
		}

		$privacy = layout_page_id( (string) ( $settings['wp_page_for_privacy_policy'] ?? 'privacy' ) );

		if ( $privacy ) {
			update_option( 'wp_page_for_privacy_policy', $privacy );
		}

		if ( ! empty( $settings['posts_per_page'] ) ) {
			update_option( 'posts_per_page', (int) $settings['posts_per_page'] );
		}

		if ( '' === (string) get_option( 'blogdescription' ) && ! empty( $settings['blogdescription'] ) ) {
			update_option( 'blogdescription', sanitize_text_field( (string) $settings['blogdescription'] ) );
		}

		if ( '' === (string) get_option( 'permalink_structure' ) ) {
			global $wp_rewrite;

			$wp_rewrite->set_permalink_structure( (string) ( $settings['permalink_structure'] ?? '/%postname%/' ) );
			$log[] = __( 'Permalinks set to "Post name" so page addresses are readable.', 'edshield' );
		}

		foreach ( (array) ( $package['customizer'] ?? array() ) as $key => $value ) {
			if ( 0 !== strpos( (string) $key, 'edst_' ) ) {
				continue;
			}

			// Never overwrite a setting the site owner has already chosen.
			$current = get_theme_mod( $key, null );

			if ( null !== $current && '' !== $current ) {
				continue;
			}

			set_theme_mod( $key, is_string( $value ) ? demo_tokens( $value ) : $value );
		}

		demo_site_icon();

		if ( $elementor ) {
			$log[] = demo_apply_elementor_kit( (array) ( $package['elementor'] ?? array() ) )
				? __( 'Elementor Site Settings updated: ED Shield colours, fonts and layout width.', 'edshield' )
				: __( 'No active Elementor kit was found, so Elementor Site Settings were not changed.', 'edshield' );
		}

		$log[] = __( 'Theme settings imported: header button, announcement bar, mega menu card and footer texts.', 'edshield' );
	}

	/* Menus -------------------------------------------------------------- */
	if ( $opts['menus'] ) {
		$created['menus'] = demo_import_menus( (array) ( $package['menus'] ?? array() ) );
		$log[]            = $created['menus']
			/* translators: %d: number of menus. */
			? sprintf( _n( '%d menu created and assigned.', '%d menus created and assigned.', $created['menus'], 'edshield' ), $created['menus'] )
			: __( 'Menus already existed and were kept.', 'edshield' );
	}

	/* Widgets ------------------------------------------------------------ */
	if ( $opts['widgets'] ) {
		$created['widgets'] = demo_import_widgets( (array) ( $package['widgets'] ?? array() ) );
		$log[]              = $created['widgets']
			/* translators: %d: number of widgets. */
			? sprintf( _n( '%d widget added.', '%d widgets added.', $created['widgets'], 'edshield' ), $created['widgets'] )
			: __( 'The demo widgets were already in place and were kept.', 'edshield' );
	}

	wp_defer_term_counting( false );

	if ( $elementor ) {
		elementor_clear_cache();
	}

	flush_rewrite_rules( false );
	update_option(
		'edst_demo_imported',
		array(
			'time'    => time(),
			'version' => VERSION,
			'created' => $created,
		),
		false
	);

	return array(
		'log'     => $log,
		'pages'   => array_values( $page_ids ),
		'created' => $created,
	);
}

/**
 * Create the demo menus and assign their locations.
 *
 * @param array<string, array<string, mixed>> $menus Menu blueprint.
 * @return int Number of menus created.
 */
function demo_import_menus( array $menus ): int {
	$locations = (array) get_theme_mod( 'nav_menu_locations', array() );
	$count     = 0;

	foreach ( $menus as $menu ) {
		$name     = (string) ( $menu['name'] ?? '' );
		$location = (string) ( $menu['location'] ?? '' );

		if ( '' === $name ) {
			continue;
		}

		$existing = wp_get_nav_menu_object( $name );

		if ( $existing ) {
			$menu_id = (int) $existing->term_id;
		} else {
			$menu_id = wp_create_nav_menu( $name );

			if ( is_wp_error( $menu_id ) ) {
				continue;
			}

			demo_add_menu_items( (int) $menu_id, (array) ( $menu['items'] ?? array() ), 0 );
			++$count;
		}

		if ( '' !== $location && empty( $locations[ $location ] ) ) {
			$locations[ $location ] = (int) $menu_id;
		}
	}

	set_theme_mod( 'nav_menu_locations', $locations );

	return $count;
}

/**
 * Add menu items recursively.
 *
 * @param int                              $menu_id Menu ID.
 * @param array<int, array<string, mixed>> $items   Items.
 * @param int                              $parent  Parent item ID.
 */
function demo_add_menu_items( int $menu_id, array $items, int $parent ): void {
	foreach ( $items as $item ) {
		$link = (string) ( $item['link'] ?? '' );
		$args = array(
			'menu-item-title'       => (string) ( $item['title'] ?? '' ),
			'menu-item-description' => (string) ( $item['description'] ?? '' ),
			'menu-item-classes'     => (string) ( $item['classes'] ?? '' ),
			'menu-item-parent-id'   => $parent,
			'menu-item-status'      => 'publish',
		);

		$layout = '';

		if ( 'posts' === $link ) {
			$posts_page = (int) get_option( 'page_for_posts' );
			$layout     = $posts_page ? '' : 'insights';

			if ( $posts_page ) {
				$args['menu-item-type']      = 'post_type';
				$args['menu-item-object']    = 'page';
				$args['menu-item-object-id'] = $posts_page;
			}
		} elseif ( 0 === strpos( $link, 'service:' ) ) {
			$layout = 'service-' . substr( $link, 8 );
		} elseif ( 0 === strpos( $link, 'page:' ) ) {
			$layout = substr( $link, 5 );
		}

		if ( '' !== $layout && ! isset( $args['menu-item-type'] ) ) {
			$page_id = layout_page_id( $layout );

			if ( $page_id ) {
				$args['menu-item-type']      = 'post_type';
				$args['menu-item-object']    = 'page';
				$args['menu-item-object-id'] = $page_id;
			}
		}

		if ( ! isset( $args['menu-item-type'] ) ) {
			$args['menu-item-type'] = 'custom';
			$args['menu-item-url']  = '' !== $link ? resolve_url( $link ) : '#';
		}

		$item_id = wp_update_nav_menu_item( $menu_id, 0, $args );

		if ( ! is_wp_error( $item_id ) && ! empty( $item['children'] ) ) {
			demo_add_menu_items( $menu_id, (array) $item['children'], (int) $item_id );
		}
	}
}

/**
 * Add block widgets to empty widget areas.
 *
 * @param array<string, string[]> $widgets Sidebar ID => block markup list.
 * @return int Number of widgets added.
 */
function demo_import_widgets( array $widgets ): int {
	$sidebars = wp_get_sidebars_widgets();
	$blocks   = get_option( 'widget_block', array() );
	$blocks   = is_array( $blocks ) ? $blocks : array();
	$next     = max( array_merge( array( 1 ), array_filter( array_keys( $blocks ), 'is_int' ) ) ) + 1;
	$count    = 0;
	$ours     = (array) get_option( 'edst_demo_widgets', array() );

	foreach ( $widgets as $sidebar => $contents ) {
		$current = isset( $sidebars[ $sidebar ] ) && is_array( $sidebars[ $sidebar ] ) ? $sidebars[ $sidebar ] : array();

		// Already imported and still there: keep whatever the owner did since.
		if ( array_intersect( $current, $ours ) ) {
			continue;
		}

		// Widgets WordPress or a previous theme put here move to Inactive
		// widgets, where they can be dragged back at any time.
		if ( $current ) {
			$sidebars['wp_inactive_widgets'] = array_merge( (array) ( $sidebars['wp_inactive_widgets'] ?? array() ), $current );
		}

		$sidebars[ $sidebar ] = array();

		foreach ( (array) $contents as $content ) {
			$blocks[ $next ]        = array( 'content' => demo_tokens( (string) $content ) );
			$sidebars[ $sidebar ][] = 'block-' . $next;
			$ours[]                 = 'block-' . $next;
			++$next;
			++$count;
		}
	}

	if ( $count ) {
		$blocks['_multiwidget'] = 1;
		update_option( 'widget_block', $blocks );
		update_option( 'edst_demo_widgets', array_values( array_unique( $ours ) ), false );
		wp_set_sidebars_widgets( $sidebars );
	}

	return $count;
}

/**
 * Apply Elementor Site Settings: global colours, global fonts, content width
 * and Elementor options that make its own widgets follow the theme.
 *
 * @param array<string, mixed> $kit Kit settings from the package.
 */
function demo_apply_elementor_kit( array $kit ): bool {
	foreach ( (array) ( $kit['options'] ?? array() ) as $option => $value ) {
		if ( 0 === strpos( (string) $option, 'elementor_' ) ) {
			update_option( (string) $option, $value );
		}
	}

	$kit_id = (int) get_option( 'elementor_active_kit' );

	if ( ! $kit_id || ! get_post( $kit_id ) ) {
		return false;
	}

	$settings = get_post_meta( $kit_id, '_elementor_page_settings', true );
	$settings = is_array( $settings ) ? $settings : array();

	// Global colours and fonts: theme entries are added or refreshed, the
	// site owner's own entries are kept.
	foreach ( array( 'custom_colors', 'system_colors', 'system_typography' ) as $group ) {
		if ( empty( $kit[ $group ] ) ) {
			continue;
		}

		$current = isset( $settings[ $group ] ) && is_array( $settings[ $group ] ) ? $settings[ $group ] : array();
		$by_id   = array();

		foreach ( $current as $entry ) {
			if ( is_array( $entry ) && isset( $entry['_id'] ) ) {
				$by_id[ (string) $entry['_id'] ] = $entry;
			}
		}

		foreach ( (array) $kit[ $group ] as $entry ) {
			$by_id[ (string) $entry['_id'] ] = $entry;
		}

		$settings[ $group ] = array_values( $by_id );
	}

	if ( ! empty( $kit['container_width'] ) && empty( $settings['container_width'] ) ) {
		$settings['container_width'] = $kit['container_width'];
	}

	update_post_meta( $kit_id, '_elementor_page_settings', $settings );

	return true;
}

/**
 * Use the ED Shield shield as the site icon when none is set.
 */
function demo_site_icon(): void {
	$source = DIR . '/demo/assets/site-icon.png';

	if ( get_option( 'site_icon' ) || ! is_readable( $source ) ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam( 'edshield-site-icon.png' );

	if ( ! $tmp || ! copy( $source, $tmp ) ) {
		return;
	}

	$id = media_handle_sideload(
		array(
			'name'     => 'edshield-site-icon.png',
			'tmp_name' => $tmp,
		),
		0,
		__( 'ED Shield site icon', 'edshield' )
	);

	if ( is_wp_error( $id ) ) {
		wp_delete_file( $tmp );

		return;
	}

	update_post_meta( (int) $id, '_edst_demo', VERSION );
	update_option( 'site_icon', (int) $id );
}

/**
 * Build Elementor data for demo pages that are not built in Elementor yet
 * (for sites that activate Elementor after the import).
 *
 * @return int Number of pages built.
 */
function demo_build_elementor_pages(): int {
	if ( ! elementor_active() ) {
		return 0;
	}

	$count = 0;
	$pages = get_posts(
		array(
			'post_type'   => 'page',
			'post_status' => 'any',
			'numberposts' => -1,
			'meta_key'    => '_edst_layout', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- admin tool.
			'fields'      => 'ids',
		)
	);

	foreach ( $pages as $id ) {
		$layout = (string) get_post_meta( $id, '_edst_layout', true );

		if ( '' === $layout || 'insights' === $layout || built_with_elementor( (int) $id ) || '' !== trim( (string) get_post_field( 'post_content', $id ) ) ) {
			continue;
		}

		$data = elementor_layout_data( $layout );

		if ( $data ) {
			set_elementor_data( (int) $id, $data );
			++$count;
		}
	}

	elementor_clear_cache();

	return $count;
}
