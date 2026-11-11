<?php
/**
 * Section schemas.
 *
 * One schema per section type drives three things: the controls of the
 * matching Elementor widget, the defaults used when the section is rendered
 * by the theme, and the shape of the starter page data. Values follow
 * Elementor's formats (URL and media controls are arrays, switches are "yes").
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

/* Field constructors ------------------------------------------------------ */

/**
 * A field definition.
 *
 * @param string $type    Field type.
 * @param string $id      Field ID.
 * @param string $label   Label.
 * @param mixed  $default Default value.
 * @param array  $extra   Extra keys (options, fields, title_field, description, condition).
 * @return array<string, mixed>
 */
function fld( string $type, string $id, string $label, $default = '', array $extra = array() ): array {
	return array_merge(
		array(
			'type'    => $type,
			'id'      => $id,
			'label'   => $label,
			'default' => $default,
		),
		$extra
	);
}

/**
 * Link default in Elementor's URL format.
 *
 * @param string $url URL or token.
 * @return array{url:string,is_external:string,nofollow:string}
 */
function link_value( string $url ): array {
	return array(
		'url'         => $url,
		'is_external' => '',
		'nofollow'    => '',
	);
}

/**
 * Standard heading fields shared by most sections.
 *
 * @param array<string, string> $d Defaults: label, heading, intro, btn_label, btn_url.
 * @return array<int, array<string, mixed>>
 */
function head_fields( array $d = array() ): array {
	return array(
		fld( 'text', 'label', __( 'Eyebrow label', 'edshield' ), $d['label'] ?? '' ),
		fld( 'textarea', 'heading', __( 'Heading', 'edshield' ), $d['heading'] ?? '', array( 'description' => __( 'Wrap words in <em></em> to highlight them.', 'edshield' ) ) ),
		fld( 'textarea', 'intro', __( 'Introduction', 'edshield' ), $d['intro'] ?? '' ),
		fld( 'text', 'btn_label', __( 'Button label', 'edshield' ), $d['btn_label'] ?? '' ),
		fld( 'url', 'btn_url', __( 'Button link', 'edshield' ), link_value( $d['btn_url'] ?? '' ) ),
	);
}

/**
 * Picker of illustrations.
 *
 * @param string $id      Field ID.
 * @param string $default Default illustration.
 * @param string $label   Label.
 */
function art_field( string $id = 'art', string $default = '', string $label = '' ): array {
	return fld( 'select', $id, '' !== $label ? $label : __( 'Illustration', 'edshield' ), $default, array( 'options' => art_choices() ) );
}

/**
 * Cards repeater fields (title, text, link, illustration, image).
 *
 * @return array<int, array<string, mixed>>
 */
function card_fields(): array {
	return array(
		fld( 'text', 'title', __( 'Title', 'edshield' ), __( 'Card title', 'edshield' ) ),
		fld( 'textarea', 'text', __( 'Text', 'edshield' ), '' ),
		art_field( 'art', 'icon:spark' ),
		fld( 'media', 'image', __( 'Image (replaces the illustration)', 'edshield' ), array( 'url' => '', 'id' => '' ) ),
		fld( 'text', 'link_label', __( 'Link label', 'edshield' ), '' ),
		fld( 'url', 'url', __( 'Link', 'edshield' ), link_value( '' ) ),
	);
}

/**
 * Repeater item defaults from tuples.
 *
 * @param array<int, array<int|string, mixed>> $rows Rows as associative arrays.
 * @return array<int, array<string, mixed>>
 */
function rows_default( array $rows ): array {
	return array_values( $rows );
}

/* Schemas ------------------------------------------------------------------ */

/**
 * All section types.
 *
 * @return array<string, array<string, mixed>>
 */
function section_definitions(): array {
	static $defs = null;

	if ( null !== $defs ) {
		return $defs;
	}

	$service_cards = array();

	foreach ( array_slice( services(), 0, 6, true ) as $key => $service ) {
		$service_cards[] = array(
			'title'      => $service['title'],
			'text'       => $service['summary'],
			'art'        => service_art( $key ),
			'image'      => array(
				'url' => '',
				'id'  => '',
			),
			'link_label' => __( 'Explore service', 'edshield' ),
			'url'        => link_value( 'service:' . $key ),
		);
	}

	$defs = array(
		'hero-home'     => array(
			'title'  => __( 'Home hero', 'edshield' ),
			'icon'   => 'eicon-banner',
			'tone'   => 'ink',
			'space'  => 'none',
			'fields' => array(
				fld(
					'repeater',
					'badges',
					__( 'Badges', 'edshield' ),
					array(
						array( 'text' => __( 'Technology & transformation partner', 'edshield' ) ),
						array( 'text' => __( 'Bangladesh · International', 'edshield' ) ),
					),
					array(
						'fields'      => array( fld( 'text', 'text', __( 'Text', 'edshield' ), '' ) ),
						'title_field' => 'text',
					)
				),
				fld( 'textarea', 'heading', __( 'Heading', 'edshield' ), __( 'Engineering digital growth. <em>Empowering businesses.</em>', 'edshield' ), array( 'description' => __( 'Wrap words in <em></em> to highlight them.', 'edshield' ) ) ),
				fld( 'textarea', 'text', __( 'Introduction', 'edshield' ), __( 'ED Shield Solutions designs, builds and secures the systems organisations run on: websites, software, automation, CRM and cybersecurity, for teams in Bangladesh and around the world.', 'edshield' ) ),
				fld( 'text', 'btn_label', __( 'Primary button', 'edshield' ), __( 'Book a discovery call', 'edshield' ) ),
				fld( 'url', 'btn_url', __( 'Primary link', 'edshield' ), link_value( 'page:contact' ) ),
				fld( 'text', 'btn2_label', __( 'Secondary button', 'edshield' ), __( 'Explore services', 'edshield' ) ),
				fld( 'url', 'btn2_url', __( 'Secondary link', 'edshield' ), link_value( 'page:services' ) ),
				fld( 'select', 'visual', __( 'Visual', 'edshield' ), 'tiles', array( 'options' => array( 'tiles' => __( 'Animated capability tiles', 'edshield' ), 'image' => __( 'Image', 'edshield' ), 'none' => __( 'None', 'edshield' ) ) ) ),
				fld( 'media', 'image', __( 'Image', 'edshield' ), array( 'url' => '', 'id' => '' ), array( 'condition' => array( 'visual' => 'image' ) ) ),
				fld( 'text', 'trust_label', __( 'Strip label', 'edshield' ), __( 'Technologies we work with', 'edshield' ) ),
				fld( 'textarea', 'trust_items', __( 'Strip items (one per line)', 'edshield' ), implode( "\n", technologies() ) ),
			),
		),
		'page-hero'     => array(
			'title'  => __( 'Page hero', 'edshield' ),
			'icon'   => 'eicon-header',
			'tone'   => 'ink',
			'space'  => 'none',
			'fields' => array(
				fld( 'switch', 'crumbs', __( 'Show breadcrumb', 'edshield' ), 'yes' ),
				fld( 'text', 'badge', __( 'Badge', 'edshield' ), '' ),
				fld( 'textarea', 'heading', __( 'Heading', 'edshield' ), __( 'Page heading', 'edshield' ), array( 'description' => __( 'Wrap words in <em></em> to highlight them.', 'edshield' ) ) ),
				fld( 'textarea', 'text', __( 'Introduction', 'edshield' ), '' ),
				fld( 'text', 'btn_label', __( 'Button label', 'edshield' ), __( 'Book a discovery call', 'edshield' ) ),
				fld( 'url', 'btn_url', __( 'Button link', 'edshield' ), link_value( 'page:contact' ) ),
				fld( 'select', 'media_position', __( 'Visual position', 'edshield' ), 'none', array( 'options' => array( 'none' => __( 'None', 'edshield' ), 'aside' => __( 'Beside the heading', 'edshield' ), 'below' => __( 'Large, below the heading', 'edshield' ) ) ) ),
				art_field( 'art', 'web' ),
				fld( 'media', 'image', __( 'Image (replaces the illustration)', 'edshield' ), array( 'url' => '', 'id' => '' ) ),
				fld( 'text', 'trust_label', __( 'Strip label', 'edshield' ), '' ),
				fld( 'textarea', 'trust_items', __( 'Strip items (one per line)', 'edshield' ), '' ),
			),
		),
		'services-grid' => array(
			'title'  => __( 'Services grid', 'edshield' ),
			'icon'   => 'eicon-gallery-grid',
			'tone'   => 'bone',
			'fields' => array_merge(
				head_fields(
					array(
						'label'     => __( 'Services', 'edshield' ),
						'heading'   => __( 'Explore our services', 'edshield' ),
						'btn_label' => __( 'All services', 'edshield' ),
						'btn_url'   => 'page:services',
					)
				),
				array(
					fld( 'select', 'source', __( 'Cards', 'edshield' ), 'manual', array( 'options' => array( 'manual' => __( 'Edit cards below', 'edshield' ), 'services' => __( 'All nine services (automatic)', 'edshield' ) ) ) ),
					fld( 'select', 'columns', __( 'Columns', 'edshield' ), '3', array( 'options' => array( '2' => '2', '3' => '3', '4' => '4' ) ) ),
					fld( 'select', 'style', __( 'Card style', 'edshield' ), 'media', array( 'options' => array( 'media' => __( 'Illustration on top', 'edshield' ), 'icon' => __( 'Icon', 'edshield' ) ) ) ),
					fld( 'repeater', 'items', __( 'Cards', 'edshield' ), $service_cards, array( 'fields' => card_fields(), 'title_field' => 'title' ) ),
				)
			),
		),
		'features'      => array(
			'title'  => __( 'Feature cards (why choose us)', 'edshield' ),
			'icon'   => 'eicon-info-box',
			'tone'   => 'white',
			'fields' => array_merge(
				head_fields(
					array(
						'label'     => __( 'Why ED Shield', 'edshield' ),
						'heading'   => __( 'Why organisations choose us', 'edshield' ),
						'btn_label' => __( 'Start a project', 'edshield' ),
						'btn_url'   => 'page:contact',
					)
				),
				array(
					fld(
						'repeater',
						'items',
						__( 'Cards', 'edshield' ),
						array_map(
							static function ( array $r ): array {
								return array(
									'title'      => $r[0],
									'text'       => $r[1],
									'art'        => $r[2],
									'image'      => array( 'url' => '', 'id' => '' ),
									'link_label' => $r[3],
									'url'        => link_value( $r[4] ),
								);
							},
							reasons()
						),
						array(
							'fields'      => card_fields(),
							'title_field' => 'title',
						)
					),
				)
			),
		),
		'industries'    => array(
			'title'  => __( 'Industries grid', 'edshield' ),
			'icon'   => 'eicon-apps',
			'tone'   => 'white',
			'fields' => array_merge(
				head_fields(
					array(
						'label'   => __( 'Industries', 'edshield' ),
						'heading' => __( 'Built for the way your sector works.', 'edshield' ),
					)
				),
				array(
					fld(
						'repeater',
						'items',
						__( 'Cards', 'edshield' ),
						industry_cards(),
						array(
							'fields'      => card_fields(),
							'title_field' => 'title',
						)
					),
				)
			),
		),
		'services-list' => array(
			'title'  => __( 'Services list (detailed rows)', 'edshield' ),
			'icon'   => 'eicon-post-list',
			'tone'   => 'white',
			'fields' => array_merge(
				head_fields(),
				array(
					fld( 'select', 'source', __( 'Rows', 'edshield' ), 'services', array( 'options' => array( 'services' => __( 'All nine services (automatic)', 'edshield' ), 'manual' => __( 'Edit rows below', 'edshield' ) ) ) ),
					fld(
						'repeater',
						'items',
						__( 'Rows', 'edshield' ),
						array(),
						array(
							'fields'      => array(
								fld( 'text', 'title', __( 'Title', 'edshield' ), '' ),
								fld( 'textarea', 'text', __( 'Text', 'edshield' ), '' ),
								fld( 'textarea', 'list', __( 'Checklist (one per line)', 'edshield' ), '' ),
								art_field( 'art', 'web' ),
								fld( 'media', 'image', __( 'Image (replaces the illustration)', 'edshield' ), array( 'url' => '', 'id' => '' ) ),
								fld( 'text', 'link_label', __( 'Link label', 'edshield' ), __( 'Explore service', 'edshield' ) ),
								fld( 'url', 'url', __( 'Link', 'edshield' ), link_value( '' ) ),
							),
							'title_field' => 'title',
						)
					),
				)
			),
		),
		'work'          => array(
			'title'  => __( 'Work: case studies', 'edshield' ),
			'icon'   => 'eicon-slides',
			'tone'   => 'bone',
			'fields' => array_merge(
				head_fields(
					array(
						'label'   => __( 'Selected work', 'edshield' ),
						'heading' => __( 'Projects we are proud of.', 'edshield' ),
					)
				),
				array(
					fld( 'select', 'layout', __( 'Layout', 'edshield' ), 'slider', array( 'options' => array( 'slider' => __( 'Slider', 'edshield' ), 'grid' => __( 'Grid with filters', 'edshield' ) ) ) ),
					fld( 'number', 'count', __( 'Number of projects', 'edshield' ), 6 ),
				)
			),
		),
		'stats'         => array(
			'title'  => __( 'Numbers', 'edshield' ),
			'icon'   => 'eicon-counter',
			'tone'   => 'white',
			'fields' => array_merge(
				head_fields(
					array(
						'label'     => __( 'At a glance', 'edshield' ),
						'heading'   => __( 'ED Shield in numbers.', 'edshield' ),
						'btn_label' => __( 'Talk to us', 'edshield' ),
						'btn_url'   => 'page:contact',
					)
				),
				array(
					fld(
						'repeater',
						'items',
						__( 'Numbers', 'edshield' ),
						array(
							array(
								'value' => '9',
								'label' => __( 'Service divisions', 'edshield' ),
								'text'  => __( 'From websites to industrial systems.', 'edshield' ),
							),
							array(
								'value' => '2',
								'label' => __( 'Markets', 'edshield' ),
								'text'  => __( 'Bangladesh and international.', 'edshield' ),
							),
							array(
								'value' => '1',
								'label' => __( 'Accountable team', 'edshield' ),
								'text'  => __( 'From the first call to ongoing support.', 'edshield' ),
							),
						),
						array(
							'fields'      => array(
								fld( 'text', 'value', __( 'Value', 'edshield' ), '' ),
								fld( 'text', 'label', __( 'Label', 'edshield' ), '' ),
								fld( 'text', 'text', __( 'Note', 'edshield' ), '' ),
							),
							'title_field' => 'label',
						)
					),
				)
			),
		),
		'testimonials'  => array(
			'title'  => __( 'Testimonials', 'edshield' ),
			'icon'   => 'eicon-testimonial-carousel',
			'tone'   => 'bone',
			'fields' => array_merge(
				head_fields(
					array(
						'label'   => __( 'Client voices', 'edshield' ),
						'heading' => __( 'What our clients say.', 'edshield' ),
					)
				),
				array(
					fld(
						'repeater',
						'items',
						__( 'Testimonials', 'edshield' ),
						array_fill(
							0,
							3,
							array(
								'quote' => __( 'Placeholder: add a short, real quote from a client. Keep it specific: the problem, what changed and the result.', 'edshield' ),
								'name'  => __( 'Client name', 'edshield' ),
								'role'  => __( 'Role, Organisation', 'edshield' ),
								'image' => array( 'url' => '', 'id' => '' ),
							)
						),
						array(
							'fields'      => array(
								fld( 'textarea', 'quote', __( 'Quote', 'edshield' ), '' ),
								fld( 'text', 'name', __( 'Name', 'edshield' ), '' ),
								fld( 'text', 'role', __( 'Role and organisation', 'edshield' ), '' ),
								fld( 'media', 'image', __( 'Photo', 'edshield' ), array( 'url' => '', 'id' => '' ) ),
							),
							'title_field' => 'name',
						)
					),
				)
			),
		),
		'steps'         => array(
			'title'  => __( 'Steps (process overview)', 'edshield' ),
			'icon'   => 'eicon-number-field',
			'tone'   => 'white',
			'fields' => array_merge(
				head_fields(
					array(
						'label'     => __( 'How it works', 'edshield' ),
						'heading'   => __( 'Three steps. Clear from day one.', 'edshield' ),
						'btn_label' => __( 'Start your project', 'edshield' ),
						'btn_url'   => 'page:contact',
					)
				),
				array(
					fld(
						'repeater',
						'items',
						__( 'Steps', 'edshield' ),
						array_map(
							static function ( array $r ): array {
								return array(
									'title' => $r[0],
									'text'  => $r[1],
									'art'   => $r[2],
								);
							},
							three_steps()
						),
						array(
							'fields'      => array(
								fld( 'text', 'title', __( 'Title', 'edshield' ), '' ),
								fld( 'textarea', 'text', __( 'Text', 'edshield' ), '' ),
								art_field( 'art', 'call' ),
							),
							'title_field' => 'title',
						)
					),
				)
			),
		),
		'rows'          => array(
			'title'  => __( 'Numbered rows', 'edshield' ),
			'icon'   => 'eicon-bullet-list',
			'tone'   => 'white',
			'fields' => array_merge(
				head_fields(
					array(
						'label'   => __( 'Principles', 'edshield' ),
						'heading' => __( 'How we work', 'edshield' ),
					)
				),
				array(
					fld( 'select', 'layout', __( 'Layout', 'edshield' ), 'table', array( 'options' => array( 'table' => __( 'Full-width rows', 'edshield' ), 'split' => __( 'Heading left, list right', 'edshield' ) ) ) ),
					fld(
						'repeater',
						'items',
						__( 'Rows', 'edshield' ),
						array_map(
							static function ( array $r ): array {
								return array(
									'title' => $r[0],
									'text'  => $r[1],
									'tags'  => '',
								);
							},
							principles()
						),
						array(
							'fields'      => array(
								fld( 'text', 'title', __( 'Title', 'edshield' ), '' ),
								fld( 'textarea', 'text', __( 'Text', 'edshield' ), '' ),
								fld( 'text', 'tags', __( 'Tags (comma separated)', 'edshield' ), '' ),
							),
							'title_field' => 'title',
						)
					),
				)
			),
		),
		'posts'         => array(
			'title'  => __( 'Latest articles', 'edshield' ),
			'icon'   => 'eicon-posts-grid',
			'tone'   => 'bone',
			'fields' => array_merge(
				head_fields(
					array(
						'label'     => __( 'Insights', 'edshield' ),
						'heading'   => __( 'Notes on technology and growth.', 'edshield' ),
						'btn_label' => __( 'All articles', 'edshield' ),
						'btn_url'   => 'posts',
					)
				),
				array(
					fld( 'number', 'count', __( 'Number of articles', 'edshield' ), 3 ),
					fld( 'text', 'category', __( 'Category slug (optional)', 'edshield' ), '' ),
				)
			),
		),
		'faq'           => array(
			'title'  => __( 'FAQ', 'edshield' ),
			'icon'   => 'eicon-accordion',
			'tone'   => 'white',
			'fields' => array_merge(
				head_fields(
					array(
						'label'     => __( 'FAQ', 'edshield' ),
						'heading'   => __( 'Questions, answered.', 'edshield' ),
						'intro'     => __( 'Cannot find what you are looking for? Ask us directly.', 'edshield' ),
						'btn_label' => __( 'Contact us', 'edshield' ),
						'btn_url'   => 'page:contact',
					)
				),
				array(
					fld(
						'repeater',
						'items',
						__( 'Questions', 'edshield' ),
						array_map(
							static function ( array $r ): array {
								return array(
									'q' => $r[0],
									'a' => $r[1],
								);
							},
							faq_groups()[0]['items']
						),
						array(
							'fields'      => array(
								fld( 'text', 'q', __( 'Question', 'edshield' ), '' ),
								fld( 'textarea', 'a', __( 'Answer', 'edshield' ), '' ),
							),
							'title_field' => 'q',
						)
					),
				)
			),
		),
		'faq-groups'    => array(
			'title'  => __( 'FAQ with categories', 'edshield' ),
			'icon'   => 'eicon-toggle',
			'tone'   => 'white',
			'fields' => array(
				fld(
					'repeater',
					'groups',
					__( 'Categories', 'edshield' ),
					array_map(
						static function ( array $g ): array {
							$text = array();

							foreach ( $g['items'] as $qa ) {
								$text[] = $qa[0] . "\n" . $qa[1];
							}

							return array(
								'title' => $g['title'],
								'items' => implode( "\n\n", $text ),
							);
						},
						faq_groups()
					),
					array(
						'fields'      => array(
							fld( 'text', 'title', __( 'Category', 'edshield' ), '' ),
							fld( 'textarea', 'items', __( 'Questions and answers', 'edshield' ), '', array( 'description' => __( 'First line: the question. Next lines: the answer. Leave an empty line between questions.', 'edshield' ), 'rows' => 12 ) ),
						),
						'title_field' => 'title',
					)
				),
			),
		),
		'cta'           => array(
			'title'  => __( 'Call to action band', 'edshield' ),
			'icon'   => 'eicon-call-to-action',
			'tone'   => 'white',
			'fields' => array(
				fld( 'text', 'label', __( 'Eyebrow label', 'edshield' ), __( 'Start a project', 'edshield' ) ),
				fld( 'textarea', 'heading', __( 'Heading', 'edshield' ), __( 'Bring us the problem that <em>slows you down.</em>', 'edshield' ) ),
				fld( 'textarea', 'text', __( 'Text', 'edshield' ), __( 'Tell us where you are and where you need to be. We will reply with a clear, practical next step.', 'edshield' ) ),
				fld( 'text', 'btn_label', __( 'Primary button', 'edshield' ), __( 'Book a discovery call', 'edshield' ) ),
				fld( 'url', 'btn_url', __( 'Primary link', 'edshield' ), link_value( 'page:contact' ) ),
				fld( 'text', 'btn2_label', __( 'Secondary button', 'edshield' ), __( 'Explore services', 'edshield' ) ),
				fld( 'url', 'btn2_url', __( 'Secondary link', 'edshield' ), link_value( 'page:services' ) ),
				fld( 'text', 'status', __( 'Status line', 'edshield' ), __( 'Working with clients in Bangladesh and internationally', 'edshield' ) ),
			),
		),
		'split'         => array(
			'title'  => __( 'Statement with visual', 'edshield' ),
			'icon'   => 'eicon-image-box',
			'tone'   => 'paper',
			'fields' => array(
				fld( 'text', 'label', __( 'Eyebrow label', 'edshield' ), __( 'Our position', 'edshield' ) ),
				fld( 'textarea', 'heading', __( 'Heading', 'edshield' ), __( 'We are not simply a website development agency.', 'edshield' ) ),
				fld( 'wysiwyg', 'text', __( 'Text', 'edshield' ), '<p>' . __( 'ED Shield is a technology partner for the whole journey, from a first website to software, automation, cybersecurity and digital transformation, for organisations of every size.', 'edshield' ) . '</p>' ),
				fld( 'textarea', 'chips', __( 'Tags (one per line)', 'edshield' ), '' ),
				fld( 'text', 'btn_label', __( 'Button label', 'edshield' ), '' ),
				fld( 'url', 'btn_url', __( 'Button link', 'edshield' ), link_value( '' ) ),
				art_field( 'art', 'security' ),
				fld( 'media', 'image', __( 'Image (replaces the illustration)', 'edshield' ), array( 'url' => '', 'id' => '' ) ),
				fld( 'text', 'stat_value', __( 'Highlight value', 'edshield' ), '' ),
				fld( 'text', 'stat_label', __( 'Highlight label', 'edshield' ), '' ),
				fld( 'switch', 'flip', __( 'Visual on the left', 'edshield' ), '' ),
			),
		),
		'included'      => array(
			'title'  => __( 'What is included', 'edshield' ),
			'icon'   => 'eicon-checkbox',
			'tone'   => 'white',
			'fields' => array_merge(
				head_fields(
					array(
						'label'   => __( 'Outcomes', 'edshield' ),
						'heading' => __( 'What you walk away with.', 'edshield' ),
					)
				),
				array(
					fld(
						'repeater',
						'items',
						__( 'Items', 'edshield' ),
						array(
							array(
								'icon'  => 'layers',
								'title' => __( 'Outcome', 'edshield' ),
								'text'  => __( 'Describe a concrete result of this service.', 'edshield' ),
							),
						),
						array(
							'fields'      => array(
								fld( 'select', 'icon', __( 'Icon', 'edshield' ), 'check', array( 'options' => icon_choices() ) ),
								fld( 'text', 'title', __( 'Title', 'edshield' ), '' ),
								fld( 'textarea', 'text', __( 'Text', 'edshield' ), '' ),
							),
							'title_field' => 'title',
						)
					),
				)
			),
		),
		'plans'         => array(
			'title'  => __( 'Engagement models', 'edshield' ),
			'icon'   => 'eicon-price-table',
			'tone'   => 'white',
			'fields' => array_merge(
				head_fields(),
				array(
					fld(
						'repeater',
						'items',
						__( 'Models', 'edshield' ),
						array(),
						array(
							'fields'      => array(
								fld( 'text', 'name', __( 'Name', 'edshield' ), '' ),
								fld( 'text', 'price', __( 'Price line', 'edshield' ), __( 'Scoped after discovery', 'edshield' ) ),
								fld( 'textarea', 'summary', __( 'Summary', 'edshield' ), '' ),
								fld( 'textarea', 'features', __( 'Included (one per line)', 'edshield' ), '' ),
								fld( 'text', 'btn_label', __( 'Button label', 'edshield' ), __( 'Book a call', 'edshield' ) ),
								fld( 'url', 'btn_url', __( 'Button link', 'edshield' ), link_value( 'page:contact' ) ),
								fld( 'switch', 'featured', __( 'Highlight', 'edshield' ), '' ),
							),
							'title_field' => 'name',
						)
					),
					fld( 'text', 'brief_title', __( 'Custom brief: title', 'edshield' ), __( 'Working on something specific?', 'edshield' ) ),
					fld( 'textarea', 'brief_text', __( 'Custom brief: text', 'edshield' ), __( 'Not every project fits a model. Tell us what you need and we will prepare a tailored proposal.', 'edshield' ) ),
					fld( 'text', 'brief_btn_label', __( 'Custom brief: button', 'edshield' ), __( 'Book a call', 'edshield' ) ),
					fld( 'url', 'brief_btn_url', __( 'Custom brief: link', 'edshield' ), link_value( 'page:contact' ) ),
				)
			),
		),
		'contact'       => array(
			'title'  => __( 'Contact (form and details)', 'edshield' ),
			'icon'   => 'eicon-form-horizontal',
			'tone'   => 'white',
			'fields' => array(
				fld( 'text', 'label', __( 'Eyebrow label', 'edshield' ), __( 'Contact', 'edshield' ) ),
				fld( 'textarea', 'heading', __( 'Heading', 'edshield' ), __( 'Tell us about your project.', 'edshield' ) ),
				fld( 'textarea', 'text', __( 'Text', 'edshield' ), __( 'Share a few details about what you need and we will come back with a clear, practical next step.', 'edshield' ) ),
				fld( 'text', 'form_title', __( 'Form title', 'edshield' ), __( 'Start a project', 'edshield' ) ),
				fld( 'text', 'shortcode', __( 'Form shortcode (optional)', 'edshield' ), '', array( 'description' => __( 'Leave empty to use the built-in form. Enter, for example, a Contact Form 7 shortcode to use that instead.', 'edshield' ) ) ),
				fld( 'text', 'steps_title', __( 'Next steps title', 'edshield' ), __( 'What happens next', 'edshield' ) ),
				fld( 'textarea', 'steps', __( 'Next steps (one per line)', 'edshield' ), implode( "\n", array( __( 'We review your message and any context you share.', 'edshield' ), __( 'We arrange a discovery call at a time that suits you.', 'edshield' ), __( 'You receive a written proposal with scope, timeline and cost.', 'edshield' ) ) ) ),
			),
		),
		'case-overview' => array(
			'title'  => __( 'Case study overview', 'edshield' ),
			'icon'   => 'eicon-document-file',
			'tone'   => 'white',
			'fields' => array(
				fld( 'text', 'client', __( 'Client', 'edshield' ), __( 'Client name', 'edshield' ) ),
				fld( 'text', 'industry', __( 'Industry', 'edshield' ), __( 'Industry', 'edshield' ) ),
				fld( 'text', 'services', __( 'Services', 'edshield' ), __( 'Website Development', 'edshield' ) ),
				fld( 'text', 'year', __( 'Year', 'edshield' ), '' ),
				fld( 'url', 'website', __( 'Website', 'edshield' ), link_value( '' ) ),
				fld( 'textarea', 'challenge', __( 'The challenge', 'edshield' ), __( 'Placeholder: describe the problem the client faced and why it mattered.', 'edshield' ) ),
				fld( 'textarea', 'goal', __( 'The goal', 'edshield' ), __( 'Placeholder: describe what success looked like.', 'edshield' ) ),
				fld( 'textarea', 'objectives', __( 'Objectives (one per line)', 'edshield' ), implode( "\n", array( __( 'Placeholder objective one', 'edshield' ), __( 'Placeholder objective two', 'edshield' ), __( 'Placeholder objective three', 'edshield' ) ) ) ),
			),
		),
		'gallery'       => array(
			'title'  => __( 'Gallery', 'edshield' ),
			'icon'   => 'eicon-gallery-masonry',
			'tone'   => 'bone',
			'fields' => array(
				fld( 'select', 'layout', __( 'Layout', 'edshield' ), 'mosaic', array( 'options' => array( 'mosaic' => __( 'Mosaic', 'edshield' ), 'grid' => __( 'Two columns', 'edshield' ), 'stack' => __( 'Full width', 'edshield' ) ) ) ),
				fld(
					'repeater',
					'items',
					__( 'Images', 'edshield' ),
					array(
						array(
							'image'   => array( 'url' => '', 'id' => '' ),
							'art'     => 'web',
							'caption' => '',
						),
						array(
							'image'   => array( 'url' => '', 'id' => '' ),
							'art'     => 'software',
							'caption' => '',
						),
						array(
							'image'   => array( 'url' => '', 'id' => '' ),
							'art'     => 'crm',
							'caption' => '',
						),
					),
					array(
						'fields'      => array(
							fld( 'media', 'image', __( 'Image', 'edshield' ), array( 'url' => '', 'id' => '' ) ),
							art_field( 'art', 'web', __( 'Illustration (used when no image is set)', 'edshield' ) ),
							fld( 'text', 'caption', __( 'Caption', 'edshield' ), '' ),
						),
						'title_field' => 'caption',
					)
				),
			),
		),
		'marquee'       => array(
			'title'  => __( 'Moving strip', 'edshield' ),
			'icon'   => 'eicon-animation-text',
			'tone'   => 'ink',
			'space'  => 'none',
			'fields' => array(
				fld( 'textarea', 'items', __( 'Items (one per line)', 'edshield' ), implode( "\n", wp_list_pluck( services(), 'title' ) ) ),
				fld( 'select', 'size', __( 'Size', 'edshield' ), 'large', array( 'options' => array( 'large' => __( 'Large', 'edshield' ), 'small' => __( 'Small', 'edshield' ) ) ) ),
			),
		),
		'rich-text'     => array(
			'title'  => __( 'Text block', 'edshield' ),
			'icon'   => 'eicon-text',
			'tone'   => 'white',
			'fields' => array(
				fld( 'text', 'label', __( 'Eyebrow label', 'edshield' ), '' ),
				fld( 'textarea', 'heading', __( 'Heading', 'edshield' ), '' ),
				fld( 'wysiwyg', 'content', __( 'Content', 'edshield' ), '<p>' . __( 'Write your content here.', 'edshield' ) . '</p>' ),
				fld( 'select', 'width', __( 'Width', 'edshield' ), 'narrow', array( 'options' => array( 'narrow' => __( 'Reading width', 'edshield' ), 'wide' => __( 'Full grid', 'edshield' ) ) ) ),
			),
		),
	);

	/**
	 * Filter the section schemas.
	 *
	 * @param array $defs Section definitions.
	 */
	$defs = (array) apply_filters( 'edst_section_definitions', $defs );

	return $defs;
}

/**
 * Fields every section has, shown in their own panel.
 *
 * @param array<string, mixed> $def Section definition.
 * @return array<int, array<string, mixed>>
 */
function common_fields( array $def ): array {
	return array(
		fld(
			'select',
			'tone',
			__( 'Background tone', 'edshield' ),
			$def['tone'] ?? 'white',
			array(
				'options' => array(
					'ink'      => __( 'Ink (dark)', 'edshield' ),
					'charcoal' => __( 'Charcoal (dark)', 'edshield' ),
					'paper'    => __( 'Paper (warm off-white)', 'edshield' ),
					'white'    => __( 'White', 'edshield' ),
					'bone'     => __( 'Grey', 'edshield' ),
				),
			)
		),
		fld(
			'select',
			'spacing',
			__( 'Vertical spacing', 'edshield' ),
			$def['space'] ?? 'default',
			array(
				'options' => array(
					'default' => __( 'Default', 'edshield' ),
					'compact' => __( 'Compact', 'edshield' ),
					'large'   => __( 'Large', 'edshield' ),
					'flush'   => __( 'No top spacing', 'edshield' ),
					'none'    => __( 'None', 'edshield' ),
				),
			)
		),
		fld( 'text', 'anchor', __( 'Anchor ID', 'edshield' ), '', array( 'description' => __( 'Lets menus link to this section, for example "services" for #services.', 'edshield' ) ) ),
	);
}

/**
 * Settings of a section with defaults filled in and values normalised.
 *
 * @param string               $key      Section key.
 * @param array<string, mixed> $settings Given settings.
 * @return array<string, mixed>
 */
function section_settings( string $key, array $settings ): array {
	$defs = section_definitions();

	if ( ! isset( $defs[ $key ] ) ) {
		return $settings;
	}

	$out = array();

	foreach ( array_merge( $defs[ $key ]['fields'], common_fields( $defs[ $key ] ) ) as $field ) {
		$id         = $field['id'];
		$value      = array_key_exists( $id, $settings ) ? $settings[ $id ] : $field['default'];
		$out[ $id ] = normalise_value( $field, $value );
	}

	return $out;
}

/**
 * Normalise one value to the field's storage format.
 *
 * @param array<string, mixed> $field Field definition.
 * @param mixed                $value Value.
 * @return mixed
 */
function normalise_value( array $field, $value ) {
	switch ( $field['type'] ) {
		case 'url':
			return is_array( $value ) ? array_merge( link_value( '' ), $value ) : link_value( (string) $value );

		case 'media':
			if ( is_array( $value ) ) {
				return array(
					'url' => (string) ( $value['url'] ?? '' ),
					'id'  => $value['id'] ?? '',
				);
			}

			return array(
				'url' => (string) $value,
				'id'  => '',
			);

		case 'switch':
			return ( true === $value || 'yes' === $value || '1' === $value || 1 === $value ) ? 'yes' : '';

		case 'number':
			return is_numeric( $value ) ? 0 + $value : ( $field['default'] ?? 0 );

		case 'repeater':
			$items = array();

			foreach ( (array) $value as $item ) {
				$row = array();

				foreach ( $field['fields'] as $sub ) {
					$row[ $sub['id'] ] = normalise_value( $sub, is_array( $item ) && array_key_exists( $sub['id'], $item ) ? $item[ $sub['id'] ] : $sub['default'] );
				}

				$row['_id'] = is_array( $item ) && ! empty( $item['_id'] ) ? (string) $item['_id'] : element_id();
				$items[]    = $row;
			}

			return $items;
	}

	return is_scalar( $value ) ? (string) $value : '';
}

/**
 * A random seven-character ID in Elementor's format.
 */
function element_id(): string {
	return substr( md5( uniqid( (string) wp_rand(), true ) ), 0, 7 );
}
