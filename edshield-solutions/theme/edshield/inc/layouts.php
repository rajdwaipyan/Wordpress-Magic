<?php
/**
 * Starter page layouts.
 *
 * Each layout is a list of sections with their settings. The importer turns a
 * layout into an Elementor page (one container per section) and the theme
 * renders the same layout itself when Elementor is not used, so every page
 * looks the same either way.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Pages created by the setup screen: layout key => title, path, parent layout.
 *
 * @return array<string, array{title:string,path:string,parent:string}>
 */
function layouts_index(): array {
	static $index = null;

	if ( null !== $index ) {
		return $index;
	}

	$index = array(
		'home'       => array( 'title' => __( 'Home', 'edshield' ), 'path' => 'home', 'parent' => '' ),
		'services'   => array( 'title' => __( 'Services', 'edshield' ), 'path' => 'services', 'parent' => '' ),
	);

	foreach ( services() as $key => $service ) {
		$index[ 'service-' . $key ] = array(
			'title'  => $service['title'],
			'path'   => 'services/' . $service['slug'],
			'parent' => 'services',
		);
	}

	$index += array(
		'work'       => array( 'title' => __( 'Work', 'edshield' ), 'path' => 'work', 'parent' => '' ),
		'industries' => array( 'title' => __( 'Industries', 'edshield' ), 'path' => 'industries', 'parent' => '' ),
		'about'      => array( 'title' => __( 'About', 'edshield' ), 'path' => 'about', 'parent' => '' ),
		'process'    => array( 'title' => __( 'Process', 'edshield' ), 'path' => 'process', 'parent' => '' ),
		'engagement' => array( 'title' => __( 'Engagement models', 'edshield' ), 'path' => 'engagement-models', 'parent' => '' ),
		'faq'        => array( 'title' => __( 'FAQ', 'edshield' ), 'path' => 'faq', 'parent' => '' ),
		'careers'    => array( 'title' => __( 'Careers', 'edshield' ), 'path' => 'careers', 'parent' => '' ),
		'contact'    => array( 'title' => __( 'Contact', 'edshield' ), 'path' => 'contact', 'parent' => '' ),
		'insights'   => array( 'title' => __( 'Insights', 'edshield' ), 'path' => 'insights', 'parent' => '' ),
	);

	return $index;
}

/**
 * FAQ items of one group in repeater format.
 *
 * @param int $group Group index in faq_groups().
 * @return array<int, array{q:string,a:string}>
 */
function faq_items( int $group ): array {
	$groups = faq_groups();
	$items  = array();

	foreach ( $groups[ $group ]['items'] ?? array() as $pair ) {
		$items[] = array(
			'q' => $pair[0],
			'a' => $pair[1],
		);
	}

	return $items;
}

/**
 * Sections of one layout.
 *
 * @param string $key Layout key.
 * @return array<int, array{0:string,1:array<string, mixed>}>
 */
function layout_sections( string $key ): array {
	$cta = array( 'cta', array() );

	if ( 0 === strpos( $key, 'service-' ) ) {
		return service_layout( substr( $key, 8 ) );
	}

	switch ( $key ) {
		case 'home':
			return array(
				array( 'hero-home', array() ),
				array(
					'services-grid',
					array(
						'intro' => __( 'Nine divisions, one accountable team. Start with the priority that matters most today and grow from there.', 'edshield' ),
					),
				),
				array(
					'split',
					array(
						'label'      => __( 'Our position', 'edshield' ),
						'heading'    => __( 'We are not simply a website development <em>agency.</em>', 'edshield' ),
						'text'       => '<p>' . __( 'ED Shield is a technology partner for the whole journey: from a first website to custom software, automation, CRM, cybersecurity and digital transformation.', 'edshield' ) . '</p><p>' . __( 'We work with organisations of every size, and the same team stays responsible from the first workshop to the systems running in production.', 'edshield' ) . '</p>',
						'chips'      => implode( "\n", audiences() ),
						'art'        => 'consulting',
						'stat_value' => '9',
						'stat_label' => __( 'service divisions under one roof', 'edshield' ),
						'btn_label'  => __( 'About ED Shield', 'edshield' ),
						'btn_url'    => 'page:about',
					),
				),
				array( 'features', array() ),
				array(
					'work',
					array(
						'heading'   => __( 'Work that moves organisations forward.', 'edshield' ),
						'btn_label' => __( 'All work', 'edshield' ),
						'btn_url'   => 'page:work',
					),
				),
				array( 'stats', array() ),
				array( 'testimonials', array() ),
				array( 'steps', array() ),
				array( 'posts', array() ),
				array( 'faq', array() ),
				$cta,
			);

		case 'services':
			return array(
				array(
					'page-hero',
					array(
						'badge'       => __( 'Nine service divisions', 'edshield' ),
						'heading'     => __( 'Technology services for every stage of <em>growth.</em>', 'edshield' ),
						'text'        => __( 'Websites, software, automation, CRM, security and cloud from one accountable team, so every part of your digital operation works together.', 'edshield' ),
						'trust_label' => __( 'Technologies we work with', 'edshield' ),
						'trust_items' => implode( "\n", technologies() ),
					),
				),
				array(
					'split',
					array(
						'label'   => __( 'One accountable team', 'edshield' ),
						'heading' => __( 'From a first website to a complete transformation.', 'edshield' ),
						'text'    => '<p>' . __( 'Most organisations work with a different vendor for every system, and the gaps between them are where projects stall. We cover the whole path, so the website, the CRM, the automations and the security behind them are designed to work together.', 'edshield' ) . '</p>',
						'art'     => 'crm',
						'tone'    => 'paper',
					),
				),
				array(
					'services-list',
					array(
						'label'   => __( 'What we do', 'edshield' ),
						'heading' => __( 'Our services.', 'edshield' ),
					),
				),
				array( 'stats', array() ),
				array( 'faq', array( 'items' => faq_items( 1 ) ) ),
				$cta,
			);

		case 'work':
			return array(
				array(
					'page-hero',
					array(
						'heading' => __( 'Selected <em>work.</em>', 'edshield' ),
						'text'    => __( 'Case studies from our projects across websites, software, automation, CRM and security.', 'edshield' ),
					),
				),
				array(
					'work',
					array(
						'label'   => '',
						'heading' => '',
						'layout'  => 'grid',
						'count'   => 24,
						'tone'    => 'white',
					),
				),
				array( 'stats', array( 'tone' => 'bone' ) ),
				$cta,
			);

		case 'industries':
			$industrial = service( 'industrial' );

			return array(
				array(
					'page-hero',
					array(
						'heading' => __( 'Built for the way your sector <em>works.</em>', 'edshield' ),
						'text'    => __( 'Every sector has its own buyers, rules and systems. We bring the same engineering discipline to each, adapted to how you operate.', 'edshield' ),
					),
				),
				array(
					'industries',
					array(
						'heading' => __( 'Sectors we serve.', 'edshield' ),
						'intro'   => __( 'From startups to government institutions, in Bangladesh and internationally.', 'edshield' ),
					),
				),
				array(
					'split',
					array(
						'label'     => __( 'Manufacturing & industry', 'edshield' ),
						'heading'   => $industrial['hero_title'] ?? '',
						'text'      => '<p>' . ( $industrial['tagline_text'] ?? '' ) . '</p>',
						'art'       => 'industrial',
						'btn_label' => __( 'Explore Industrial Digitalization', 'edshield' ),
						'btn_url'   => 'service:industrial',
						'tone'      => 'bone',
						'flip'      => true,
					),
				),
				array( 'steps', array() ),
				array( 'features', array( 'tone' => 'paper' ) ),
				$cta,
			);

		case 'about':
			return array(
				array(
					'page-hero',
					array(
						'badge'          => __( 'About ED Shield', 'edshield' ),
						'heading'        => __( 'A technology partner, not just a web <em>agency.</em>', 'edshield' ),
						'text'           => __( 'ED Shield Solutions designs, builds and secures the systems organisations run on, for startups, enterprises and institutions in Bangladesh and internationally.', 'edshield' ),
						'media_position' => 'below',
						'art'            => 'system',
						'btn_label'      => __( 'Work with us', 'edshield' ),
					),
				),
				array(
					'split',
					array(
						'label'      => __( 'Our purpose', 'edshield' ),
						'heading'    => __( 'Engineering digital growth. Empowering businesses.', 'edshield' ),
						'text'       => '<p>' . __( 'Most organisations do not need another vendor. They need one partner who understands the whole system: the website customers see, the software teams use, the automations that connect them and the security that protects them.', 'edshield' ) . '</p><p>' . __( 'That is the role ED Shield is built for. Our nine divisions cover the full path from a first website to a complete digital transformation, so you can start with one priority and grow without changing partners.', 'edshield' ) . '</p>',
						'art'        => 'software',
						'stat_value' => '9',
						'stat_label' => __( 'divisions, one accountable team', 'edshield' ),
						'btn_label'  => __( 'Talk to us', 'edshield' ),
						'btn_url'    => 'page:contact',
					),
				),
				array(
					'rows',
					array(
						'label'   => __( 'Principles', 'edshield' ),
						'heading' => __( 'The principles behind the work.', 'edshield' ),
					),
				),
				array(
					'rows',
					array(
						'layout'  => 'split',
						'label'   => __( 'Working together', 'edshield' ),
						'heading' => __( 'What it is like to work with us.', 'edshield' ),
						'intro'   => __( 'We work more like an embedded team than a traditional agency: close to the problem, open about progress and clear about who owns what.', 'edshield' ),
						'tone'    => 'paper',
						'items'   => array(
							array( 'title' => __( 'Direct collaboration', 'edshield' ), 'text' => __( 'You work with the people doing the work, in the tools your team already uses.', 'edshield' ) ),
							array( 'title' => __( 'Short feedback loops', 'edshield' ), 'text' => __( 'Regular check-ins and reviews keep decisions moving and surprises rare.', 'edshield' ) ),
							array( 'title' => __( 'Security by default', 'edshield' ), 'text' => __( 'Access, credentials and data are handled carefully from the first day.', 'edshield' ) ),
							array( 'title' => __( 'Documentation and handover', 'edshield' ), 'text' => __( 'Everything we build is documented so your team is never dependent on memory.', 'edshield' ) ),
						),
					),
				),
				array(
					'services-grid',
					array(
						'source'  => 'services',
						'style'   => 'icon',
						'label'   => __( 'What we do', 'edshield' ),
						'heading' => __( 'Nine divisions. One accountable partner.', 'edshield' ),
						'tone'    => 'white',
					),
				),
				array( 'steps', array( 'tone' => 'bone' ) ),
				$cta,
			);

		case 'process':
			$stages = array();

			foreach ( process_stages() as $stage ) {
				$stages[] = array(
					'title' => $stage[0],
					'text'  => $stage[1],
					'tags'  => implode( ', ', $stage[2] ),
				);
			}

			return array(
				array(
					'page-hero',
					array(
						'heading' => __( 'A clear process, from first call to <em>launch.</em>', 'edshield' ),
						'text'    => __( 'Every engagement follows the same four stages. You always know what is happening, what comes next and what it costs.', 'edshield' ),
					),
				),
				array(
					'rows',
					array(
						'label'   => __( 'Stages', 'edshield' ),
						'heading' => __( 'What happens at each stage.', 'edshield' ),
						'items'   => $stages,
					),
				),
				array(
					'rows',
					array(
						'layout'  => 'split',
						'label'   => __( 'How AI shows up', 'edshield' ),
						'heading' => __( 'Automation speeds us up. People make the decisions.', 'edshield' ),
						'intro'   => __( 'We use AI and automation where they make delivery faster and more thorough. Every output is reviewed by a person before it reaches you.', 'edshield' ),
						'tone'    => 'paper',
						'items'   => array(
							array( 'title' => __( 'Research and synthesis', 'edshield' ), 'text' => __( 'Faster review of existing material, competitors and requirements before workshops.', 'edshield' ) ),
							array( 'title' => __( 'Drafting and documentation', 'edshield' ), 'text' => __( 'First drafts of specifications and guides, refined by the team.', 'edshield' ) ),
							array( 'title' => __( 'Build acceleration', 'edshield' ), 'text' => __( 'Quicker prototypes and routine code, always reviewed and tested.', 'edshield' ) ),
							array( 'title' => __( 'Quality checks', 'edshield' ), 'text' => __( 'Additional automated checks for accessibility, performance and security.', 'edshield' ) ),
						),
					),
				),
				array( 'steps', array() ),
				array( 'faq', array( 'items' => faq_items( 1 ), 'tone' => 'bone' ) ),
				$cta,
			);

		case 'engagement':
			return array(
				array(
					'page-hero',
					array(
						'heading' => __( 'Three ways to work with <em>us.</em>', 'edshield' ),
						'text'    => __( 'Every engagement starts with a conversation. Choose the model that fits your goals and we will tailor the scope to it.', 'edshield' ),
					),
				),
				array(
					'plans',
					array(
						'items' => array(
							array(
								'name'     => __( 'Project', 'edshield' ),
								'price'    => __( 'Scoped after discovery', 'edshield' ),
								'summary'  => __( 'A defined outcome, delivered end to end. Best for new websites, applications and integrations.', 'edshield' ),
								'features' => implode( "\n", array( __( 'Discovery and planning', 'edshield' ), __( 'Design and build', 'edshield' ), __( 'Testing and launch', 'edshield' ), __( 'Documentation and handover', 'edshield' ) ) ),
								'btn_label' => __( 'Discuss a project', 'edshield' ),
							),
							array(
								'name'      => __( 'Growth retainer', 'edshield' ),
								'price'     => __( 'Monthly, scoped to your goals', 'edshield' ),
								'summary'   => __( 'Ongoing improvement with a dedicated monthly allocation. Best for teams that keep shipping.', 'edshield' ),
								'features'  => implode( "\n", array( __( 'Prioritised monthly roadmap', 'edshield' ), __( 'Updates and enhancements', 'edshield' ), __( 'Monitoring and security', 'edshield' ), __( 'Monthly reporting', 'edshield' ) ) ),
								'btn_label' => __( 'Discuss a retainer', 'edshield' ),
								'featured'  => true,
							),
							array(
								'name'      => __( 'Dedicated team', 'edshield' ),
								'price'     => __( 'Sized to the programme', 'edshield' ),
								'summary'   => __( 'An extension of your team for larger programmes and transformation work.', 'edshield' ),
								'features'  => implode( "\n", array( __( 'Embedded specialists', 'edshield' ), __( 'Agile delivery', 'edshield' ), __( 'Weekly planning', 'edshield' ), __( 'Scales with the programme', 'edshield' ) ) ),
								'btn_label' => __( 'Discuss a team', 'edshield' ),
							),
						),
					),
				),
				array(
					'faq',
					array(
						'items'   => faq_items( 2 ),
						'heading' => __( 'Common questions.', 'edshield' ),
						'tone'    => 'bone',
					),
				),
				$cta,
			);

		case 'faq':
			return array(
				array(
					'page-hero',
					array(
						'heading' => __( 'Everything you need to know before we <em>start.</em>', 'edshield' ),
						'text'    => __( 'Answers to the questions we hear most. If yours is not here, ask us directly.', 'edshield' ),
					),
				),
				array( 'faq-groups', array() ),
				$cta,
			);

		case 'careers':
			return array(
				array(
					'page-hero',
					array(
						'badge'     => __( 'Careers', 'edshield' ),
						'heading'   => __( 'Build what organisations <em>run on.</em>', 'edshield' ),
						'text'      => __( 'We are always glad to hear from engineers, designers and specialists who care about doing things properly.', 'edshield' ),
						'btn_label' => __( 'Get in touch', 'edshield' ),
					),
				),
				array(
					'rows',
					array(
						'layout'  => 'split',
						'label'   => __( 'Why join', 'edshield' ),
						'heading' => __( 'What it is like to work here.', 'edshield' ),
						'items'   => array(
							array( 'title' => __( 'Meaningful problems', 'edshield' ), 'text' => __( 'Work on systems that real organisations depend on every day.', 'edshield' ) ),
							array( 'title' => __( 'Craft over shortcuts', 'edshield' ), 'text' => __( 'Clean code, careful design and honest estimates are the standard.', 'edshield' ) ),
							array( 'title' => __( 'Learning by default', 'edshield' ), 'text' => __( 'Nine divisions mean exposure to web, software, automation, security and more.', 'edshield' ) ),
							array( 'title' => __( 'Clear ownership', 'edshield' ), 'text' => __( 'Small teams, clear responsibilities and direct contact with clients.', 'edshield' ) ),
						),
					),
				),
				array(
					'split',
					array(
						'label'     => __( 'Open roles', 'edshield' ),
						'heading'   => __( 'No open roles are listed right now.', 'edshield' ),
						'text'      => '<p>' . __( 'We still want to hear from you. Send your CV or portfolio and tell us what you would like to work on.', 'edshield' ) . '</p>',
						'art'       => 'icon:users',
						'btn_label' => __( 'Send us a message', 'edshield' ),
						'btn_url'   => 'page:contact',
						'tone'      => 'bone',
					),
				),
				$cta,
			);

		case 'contact':
			return array(
				array(
					'page-hero',
					array(
						'heading'   => __( 'Start a <em>project.</em>', 'edshield' ),
						'text'      => __( 'Tell us what you are building. We will reply with a clear, practical next step.', 'edshield' ),
						'btn_label' => '',
					),
				),
				array( 'contact', array( 'anchor' => 'contact-form' ) ),
				array(
					'faq',
					array(
						'heading' => __( 'Before you get in touch.', 'edshield' ),
						'tone'    => 'bone',
						'btn_label' => '',
					),
				),
			);
	}

	return array();
}

/**
 * Sections of one service page.
 *
 * @param string $key Service key.
 * @return array<int, array{0:string,1:array<string, mixed>}>
 */
function service_layout( string $key ): array {
	$service = service( $key );

	if ( ! $service ) {
		return array();
	}

	$deliverables = array();

	foreach ( $service['deliverables'] as $d ) {
		$deliverables[] = array(
			'title'      => $d[0],
			'text'       => $d[1],
			'art'        => 'icon:' . $d[2],
			'link_label' => '',
			'url'        => '',
		);
	}

	$included = array();
	$icons    = array( 'layers', 'check', 'chart', 'users' );

	foreach ( $service['included'] as $i => $inc ) {
		$included[] = array(
			'icon'  => $icons[ $i % 4 ],
			'title' => $inc[0],
			'text'  => $inc[1],
		);
	}

	$faqs = array();

	foreach ( $service['faqs'] as $qa ) {
		$faqs[] = array(
			'q' => $qa[0],
			'a' => $qa[1],
		);
	}

	return array(
		array(
			'page-hero',
			array(
				'badge'          => $service['division'],
				'heading'        => $service['hero_title'],
				'text'           => $service['hero_text'],
				'media_position' => 'aside',
				'art'            => service_art( $key ),
			),
		),
		array(
			'split',
			array(
				'label'   => __( 'Why it matters', 'edshield' ),
				'heading' => $service['tagline'],
				'text'    => '<p>' . $service['tagline_text'] . '</p>',
				'chips'   => implode( "\n", array_slice( $service['items'], 0, 6 ) ),
				'art'     => 'icon:' . $service['icon'],
				'flip'    => true,
			),
		),
		array(
			'services-grid',
			array(
				'label'     => __( 'What is included', 'edshield' ),
				'heading'   => __( 'What this service covers.', 'edshield' ),
				'intro'     => $service['summary'],
				'btn_label' => __( 'Book a discovery call', 'edshield' ),
				'btn_url'   => 'page:contact',
				'style'     => 'icon',
				'items'     => $deliverables,
				'tone'      => 'white',
			),
		),
		array(
			'included',
			array(
				'items' => $included,
				'tone'  => 'bone',
			),
		),
		array( 'steps', array() ),
		array( 'features', array( 'tone' => 'paper' ) ),
		array(
			'faq',
			array(
				/* translators: %s: service name. */
				'heading' => sprintf( __( 'Questions about %s.', 'edshield' ), $service['title'] ),
				'items'   => $faqs,
			),
		),
		array( 'cta', array() ),
	);
}

/**
 * Render a layout with the theme (used when the page is not built in Elementor).
 *
 * @param string $key Layout key.
 */
function render_layout( string $key ): void {
	foreach ( layout_sections( $key ) as $section ) {
		echo render_section( $section[0], $section[1] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sections escape their own output.
	}
}
