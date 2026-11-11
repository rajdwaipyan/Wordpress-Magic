<?php
/**
 * Page layouts.
 *
 * Each layout is a list of sections with their settings. The demo importer
 * turns a layout into an Elementor page (one container per section) and the
 * theme renders the same layout itself when Elementor is not used, so every
 * page looks the same either way.
 *
 * The page structure follows the reference site: home, services and service
 * pages, audience pages, work and case studies, industries and industry
 * pages, about, process, pricing, FAQ, insights, contact, resources, careers
 * and legal pages.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Every page of the site: layout key => title, path, parent layout.
 *
 * @return array<string, array{title:string,path:string,parent:string}>
 */
function layouts_index(): array {
	static $index = null;

	if ( null !== $index ) {
		return $index;
	}

	$index = array(
		'home'     => array( 'title' => __( 'Home', 'edshield' ), 'path' => 'home', 'parent' => '' ),
		'services' => array( 'title' => __( 'Services', 'edshield' ), 'path' => 'services', 'parent' => '' ),
	);

	foreach ( services() as $key => $service ) {
		$index[ 'service-' . $key ] = array(
			'title'  => $service['title'],
			'path'   => 'services/' . $service['slug'],
			'parent' => 'services',
		);
	}

	foreach ( audience_pages() as $key => $page ) {
		$index[ 'audience-' . $key ] = array(
			'title'  => $page['title'],
			'path'   => $page['slug'],
			'parent' => '',
		);
	}

	$index['work']       = array( 'title' => __( 'Work', 'edshield' ), 'path' => 'work', 'parent' => '' );
	$index['industries'] = array( 'title' => __( 'Industries', 'edshield' ), 'path' => 'industries', 'parent' => '' );

	foreach ( industry_pages() as $key => $page ) {
		$index[ 'industry-' . $key ] = array(
			'title'  => $page['title'],
			'path'   => 'industries/' . $page['slug'],
			'parent' => 'industries',
		);
	}

	$index += array(
		'about'      => array( 'title' => __( 'About', 'edshield' ), 'path' => 'about', 'parent' => '' ),
		'process'    => array( 'title' => __( 'Our Process', 'edshield' ), 'path' => 'process', 'parent' => '' ),
		'engagement' => array( 'title' => __( 'Pricing', 'edshield' ), 'path' => 'pricing', 'parent' => '' ),
		'faq'        => array( 'title' => __( 'FAQ', 'edshield' ), 'path' => 'faq', 'parent' => '' ),
		'careers'    => array( 'title' => __( 'Careers', 'edshield' ), 'path' => 'careers', 'parent' => '' ),
		'contact'    => array( 'title' => __( 'Contact', 'edshield' ), 'path' => 'contact', 'parent' => '' ),
		'insights'   => array( 'title' => __( 'Insights', 'edshield' ), 'path' => 'insights', 'parent' => '' ),
		'audit'      => array( 'title' => __( 'Website Audit', 'edshield' ), 'path' => 'website-audit', 'parent' => '' ),
		'technology' => array( 'title' => __( 'Technology', 'edshield' ), 'path' => 'technology', 'parent' => '' ),
		'privacy'    => array( 'title' => __( 'Privacy Policy', 'edshield' ), 'path' => 'privacy-policy', 'parent' => '' ),
		'terms'      => array( 'title' => __( 'Terms of Service', 'edshield' ), 'path' => 'terms-of-service', 'parent' => '' ),
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

	return qa_pairs( $groups[ $group ]['items'] ?? array() );
}

/**
 * Question and answer tuples in repeater format.
 *
 * @param array<int, array{0:string,1:string}> $pairs Tuples.
 * @return array<int, array{q:string,a:string}>
 */
function qa_pairs( array $pairs ): array {
	$items = array();

	foreach ( $pairs as $pair ) {
		$items[] = array(
			'q' => $pair[0],
			'a' => $pair[1],
		);
	}

	return $items;
}

/**
 * Industry cards for the industries grid, linked to their pages.
 *
 * @return array<int, array<string, mixed>>
 */
function industry_cards(): array {
	$links = array(
		'rocket'     => 'page:audience-startups',
		'store'      => 'page:audience-growth',
		'building'   => 'service:consulting',
		'factory'    => 'page:industry-manufacturing',
		'health'     => 'page:industry-healthcare',
		'education'  => 'page:industry-education',
		'ngo'        => 'page:industry-ngo',
		'government' => 'page:industry-government',
		'cart'       => 'page:industry-retail',
	);
	$cards = array();

	foreach ( industries() as $row ) {
		$url     = $links[ $row[2] ] ?? '';
		$cards[] = array(
			'title'      => $row[0],
			'text'       => $row[1],
			'art'        => 'icon:' . $row[2],
			'image'      => array( 'url' => '', 'id' => '' ),
			'link_label' => '' !== $url ? __( 'Explore industry', 'edshield' ) : '',
			'url'        => $url,
		);
	}

	return $cards;
}

/**
 * Card items from title, text, icon tuples.
 *
 * @param array<int, array{0:string,1:string,2:string}> $rows Tuples.
 * @return array<int, array<string, mixed>>
 */
function icon_cards( array $rows ): array {
	$cards = array();

	foreach ( $rows as $row ) {
		$cards[] = array(
			'title'      => $row[0],
			'text'       => $row[1],
			'art'        => 'icon:' . $row[2],
			'image'      => array( 'url' => '', 'id' => '' ),
			'link_label' => '',
			'url'        => '',
		);
	}

	return $cards;
}

/**
 * Cards for a list of services, linked to their pages.
 *
 * @param string[] $keys Service keys.
 * @return array<int, array<string, mixed>>
 */
function service_cards( array $keys ): array {
	$cards = array();

	foreach ( $keys as $key ) {
		$service = service( $key );

		if ( ! $service ) {
			continue;
		}

		$cards[] = array(
			'title'      => $service['title'],
			'text'       => $service['summary'],
			'art'        => service_art( $key ),
			'image'      => array( 'url' => '', 'id' => '' ),
			'link_label' => __( 'Explore service', 'edshield' ),
			'url'        => 'service:' . $key,
		);
	}

	return $cards;
}

/**
 * Step items from title, text, art tuples.
 *
 * @param array<int, array{0:string,1:string,2:string}> $rows Tuples.
 * @return array<int, array{title:string,text:string,art:string}>
 */
function step_items( array $rows ): array {
	$items = array();

	foreach ( $rows as $row ) {
		$items[] = array(
			'title' => $row[0],
			'text'  => $row[1],
			'art'   => $row[2],
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

	if ( 0 === strpos( $key, 'audience-' ) ) {
		return audience_layout( substr( $key, 9 ) );
	}

	if ( 0 === strpos( $key, 'industry-' ) ) {
		return industry_layout( substr( $key, 9 ) );
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
						'btn_label'   => __( 'Contact us today', 'edshield' ),
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
				array( 'stats', array( 'tone' => 'paper' ) ),
				array( 'faq', array( 'items' => faq_items( 1 ) ) ),
				$cta,
			);

		case 'work':
			return array(
				array(
					'page-hero',
					array(
						'heading'   => __( 'Built carefully. Built to <em>last.</em>', 'edshield' ),
						'text'      => __( 'Case studies from our projects across websites, software, automation, CRM and security.', 'edshield' ),
						'btn_label' => __( 'Start a project', 'edshield' ),
					),
				),
				array(
					'work',
					array(
						'label'   => '',
						'heading' => '',
						'layout'  => 'grid',
						'count'   => 24,
						'tone'    => 'paper',
					),
				),
				array( 'features', array() ),
				array( 'stats', array( 'tone' => 'paper' ) ),
				array( 'testimonials', array() ),
				$cta,
			);

		case 'industries':
			return array(
				array(
					'page-hero',
					array(
						'heading'   => __( 'We know your sector. We build for how it <em>works.</em>', 'edshield' ),
						'text'      => __( 'Every sector has its own buyers, rules and systems. We bring the same engineering discipline to each, adapted to how you operate.', 'edshield' ),
						'btn_label' => __( 'Book a discovery call', 'edshield' ),
					),
				),
				array(
					'industries',
					array(
						'heading'   => __( 'Every sector has different needs.', 'edshield' ),
						'intro'     => __( 'From startups to government institutions, in Bangladesh and internationally.', 'edshield' ),
						'btn_label' => __( 'Book a call', 'edshield' ),
						'btn_url'   => 'page:contact',
						'items'     => industry_cards(),
					),
				),
				array( 'steps', array( 'tone' => 'paper' ) ),
				array( 'stats', array() ),
				array( 'testimonials', array() ),
				array( 'features', array() ),
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
						'btn_label'      => __( 'See open roles', 'edshield' ),
						'btn_url'        => 'page:careers',
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
						'btn_label'  => __( 'Contact us today', 'edshield' ),
						'btn_url'    => 'page:contact',
					),
				),
				array(
					'rows',
					array(
						'label'   => __( 'How we work', 'edshield' ),
						'heading' => __( 'The principles behind the work.', 'edshield' ),
						'tone'    => 'paper',
					),
				),
				array( 'stats', array() ),
				array(
					'rows',
					array(
						'layout'  => 'split',
						'label'   => __( 'Working together', 'edshield' ),
						'heading' => __( 'Work in motion.', 'edshield' ),
						'intro'   => __( 'We work more like an embedded team than a traditional agency: close to the problem, open about progress and clear about who owns what.', 'edshield' ),
						'tone'    => 'paper',
						'items'   => array(
							array( 'title' => __( 'Direct collaboration', 'edshield' ), 'text' => __( 'You work with the people doing the work, in the tools your team already uses.', 'edshield' ) ),
							array( 'title' => __( 'Short feedback loops', 'edshield' ), 'text' => __( 'Regular check-ins and reviews keep decisions moving and surprises rare.', 'edshield' ) ),
							array( 'title' => __( 'Flexible team setup', 'edshield' ), 'text' => __( 'The team grows or shrinks with the work, from a single specialist to a full squad.', 'edshield' ) ),
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
				array( 'steps', array( 'tone' => 'paper' ) ),
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
						'heading'   => __( 'From first call to launch, without <em>surprises.</em>', 'edshield' ),
						'text'      => __( 'Every engagement follows the same four stages. You always know what is happening, what comes next and what it costs.', 'edshield' ),
						'btn_label' => __( 'Book a discovery call', 'edshield' ),
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
				array( 'stats', array( 'tone' => 'paper' ) ),
				array( 'testimonials', array() ),
				$cta,
			);

		case 'engagement':
			return array(
				array(
					'page-hero',
					array(
						'heading'   => __( 'Start. Build. <em>Grow.</em>', 'edshield' ),
						'text'      => __( 'Three ways to work with us. Every engagement starts with a conversation, and the scope and cost are agreed in writing before work begins.', 'edshield' ),
						'btn_label' => '',
					),
				),
				array(
					'plans',
					array(
						'items' => array(
							array(
								'name'      => __( 'Project', 'edshield' ),
								'price'     => __( 'Scoped after discovery', 'edshield' ),
								'summary'   => __( 'A defined outcome, delivered end to end. Best for new websites, applications and integrations.', 'edshield' ),
								'features'  => implode( "\n", array( __( 'Discovery and planning', 'edshield' ), __( 'Design and build', 'edshield' ), __( 'Testing and launch', 'edshield' ), __( 'Documentation and handover', 'edshield' ) ) ),
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
				array( 'testimonials', array( 'tone' => 'paper' ) ),
				array(
					'faq',
					array(
						'items'   => faq_items( 2 ),
						'heading' => __( 'Common questions.', 'edshield' ),
					),
				),
				$cta,
			);

		case 'faq':
			return array(
				array(
					'page-hero',
					array(
						'heading'   => __( 'Everything you need to know before we <em>start.</em>', 'edshield' ),
						'text'      => __( 'Answers to the questions we hear most. If yours is not here, ask us directly.', 'edshield' ),
						'btn_label' => __( 'Book a discovery call', 'edshield' ),
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
						'tone'      => 'paper',
					),
				),
				$cta,
			);

		case 'contact':
			return array(
				array(
					'page-hero',
					array(
						'heading'   => __( 'Get in <em>touch.</em>', 'edshield' ),
						'text'      => __( 'Tell us what you are building. We will reply with a clear, practical next step.', 'edshield' ),
						'btn_label' => '',
					),
				),
				array( 'contact', array( 'anchor' => 'contact-form' ) ),
				array( 'testimonials', array( 'tone' => 'paper' ) ),
				array(
					'faq',
					array(
						'heading'   => __( 'Frequently asked questions.', 'edshield' ),
						'tone'      => 'ink',
						'btn_label' => '',
					),
				),
			);

		case 'audit':
			return array(
				array(
					'page-hero',
					array(
						'badge'          => __( 'Resource', 'edshield' ),
						'heading'        => __( 'A website audit with a clear <em>action list.</em>', 'edshield' ),
						'text'           => __( 'We review your website for speed, security, search foundations, accessibility and conversion, then send a prioritised list of what to fix first.', 'edshield' ),
						'btn_label'      => __( 'Request an audit', 'edshield' ),
						'btn_url'        => 'page:audit#audit-form',
						'media_position' => 'aside',
						'art'            => 'launch',
					),
				),
				array(
					'services-grid',
					array(
						'label'     => __( 'What we check', 'edshield' ),
						'heading'   => __( 'Six areas, one practical report.', 'edshield' ),
						'btn_label' => '',
						'style'     => 'icon',
						'items'     => icon_cards( audit_checks() ),
						'tone'      => 'white',
					),
				),
				array(
					'steps',
					array(
						'heading'   => __( 'How the audit works.', 'edshield' ),
						'btn_label' => '',
						'tone'      => 'paper',
						'items'     => step_items(
							array(
								array( __( 'Send your website', 'edshield' ), __( 'Share the address and what you want to improve.', 'edshield' ), 'web' ),
								array( __( 'We review it', 'edshield' ), __( 'Our team checks the six areas with tools and a careful manual review.', 'edshield' ), 'software' ),
								array( __( 'Get your action list', 'edshield' ), __( 'A short report with priorities, explained in plain language.', 'edshield' ), 'launch' ),
							)
						),
					),
				),
				array(
					'contact',
					array(
						'anchor'     => 'audit-form',
						'label'      => __( 'Request', 'edshield' ),
						'heading'    => __( 'Request your website audit.', 'edshield' ),
						'text'       => __( 'Tell us your website address and what matters most to you. We will confirm the scope before we start.', 'edshield' ),
						'form_title' => __( 'Audit request', 'edshield' ),
					),
				),
				array(
					'faq',
					array(
						'heading' => __( 'Questions about the audit.', 'edshield' ),
						'tone'    => 'paper',
						'items'   => qa_pairs(
							array(
								array( __( 'What do we need to provide?', 'edshield' ), __( 'Only the website address and your main goals. Access to analytics or hosting is optional and helps us go deeper.', 'edshield' ) ),
								array( __( 'Will you change anything on our website?', 'edshield' ), __( 'No. The audit only reviews the website; nothing is changed without your approval.', 'edshield' ) ),
								array( __( 'What happens after the audit?', 'edshield' ), __( 'You can fix the issues yourself with the report, or ask us to propose a plan for them.', 'edshield' ) ),
							)
						),
					),
				),
				$cta,
			);

		case 'technology':
			return array(
				array(
					'page-hero',
					array(
						'heading'     => __( 'The platforms we build <em>on.</em>', 'edshield' ),
						'text'        => __( 'Proven, widely supported technologies chosen for each job, so what we build is fast, secure and maintainable by any capable team.', 'edshield' ),
						'btn_label'   => __( 'Talk to an engineer', 'edshield' ),
						'trust_label' => __( 'Technologies we work with', 'edshield' ),
						'trust_items' => implode( "\n", technologies() ),
					),
				),
				array(
					'rows',
					array(
						'label'   => __( 'Our stack', 'edshield' ),
						'heading' => __( 'The right tool for each part of the system.', 'edshield' ),
						'items'   => tech_groups(),
					),
				),
				array(
					'split',
					array(
						'label'   => __( 'Ownership', 'edshield' ),
						'heading' => __( 'You own everything we build.', 'edshield' ),
						'text'    => '<p>' . __( 'Code, accounts, domains and data are set up in your name. We document how everything fits together, so you are never locked in to us or to a single person.', 'edshield' ) . '</p>',
						'art'     => 'system',
						'tone'    => 'paper',
						'flip'    => true,
					),
				),
				array( 'features', array() ),
				$cta,
			);

		case 'privacy':
		case 'terms':
			return array(
				array(
					'page-hero',
					array(
						'heading'   => 'privacy' === $key ? __( 'Privacy policy', 'edshield' ) : __( 'Terms of service', 'edshield' ),
						'text'      => '',
						'btn_label' => '',
						'spacing'   => 'compact',
					),
				),
				array(
					'rich-text',
					array(
						'label'   => '',
						'heading' => '',
						'content' => 'privacy' === $key ? privacy_text() : terms_text(),
						'width'   => 'narrow',
						'tone'    => 'white',
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
				'tone'  => 'paper',
			),
		),
		array( 'steps', array() ),
		array( 'stats', array( 'tone' => 'paper' ) ),
		array( 'testimonials', array() ),
		array( 'features', array( 'tone' => 'paper' ) ),
		array(
			'faq',
			array(
				/* translators: %s: service name. */
				'heading' => sprintf( __( 'Questions about %s.', 'edshield' ), $service['title'] ),
				'items'   => qa_pairs( $service['faqs'] ),
			),
		),
		array( 'cta', array() ),
	);
}

/**
 * Sections of an audience page (for startups, for growing businesses).
 *
 * @param string $key Audience key.
 * @return array<int, array{0:string,1:array<string, mixed>}>
 */
function audience_layout( string $key ): array {
	$page = audience_pages()[ $key ] ?? null;

	if ( ! $page ) {
		return array();
	}

	$groups = array();

	foreach ( $page['groups'] as $group ) {
		$groups[] = array(
			'title'      => $group[0],
			'text'       => $group[1],
			'art'        => 'icon:' . $group[2],
			'image'      => array( 'url' => '', 'id' => '' ),
			'link_label' => __( 'Explore service', 'edshield' ),
			'url'        => 'service:' . $group[3],
		);
	}

	return array(
		array(
			'page-hero',
			array(
				'badge'          => $page['badge'],
				'heading'        => $page['hero_title'],
				'text'           => $page['hero_text'],
				'btn_label'      => __( 'Request a proposal', 'edshield' ),
				'media_position' => 'aside',
				'art'            => $page['art'],
			),
		),
		array(
			'split',
			array(
				'label'   => __( 'Why it matters', 'edshield' ),
				'heading' => $page['tagline'],
				'text'    => '<p>' . $page['tagline_text'] . '</p>',
				'art'     => 'icon:' . $page['icon'],
				'tone'    => 'paper',
			),
		),
		array(
			'services-grid',
			array(
				'label'     => __( 'What you get', 'edshield' ),
				'heading'   => __( 'Everything you need, from one team.', 'edshield' ),
				'btn_label' => __( 'Start a project', 'edshield' ),
				'btn_url'   => 'page:contact',
				'style'     => 'icon',
				'columns'   => '2',
				'items'     => $groups,
				'tone'      => 'white',
			),
		),
		array(
			'steps',
			array(
				'heading' => __( 'Three simple steps.', 'edshield' ),
				'items'   => step_items( $page['steps'] ),
				'tone'    => 'paper',
			),
		),
		array( 'features', array() ),
		array(
			'work',
			array(
				'heading'   => __( 'Work that moves organisations forward.', 'edshield' ),
				'btn_label' => __( 'All work', 'edshield' ),
				'btn_url'   => 'page:work',
				'tone'      => 'paper',
			),
		),
		array( 'stats', array() ),
		array(
			'faq',
			array(
				'heading' => __( 'Frequently asked questions.', 'edshield' ),
				'items'   => qa_pairs( $page['faqs'] ),
				'tone'    => 'paper',
			),
		),
		array( 'cta', array() ),
	);
}

/**
 * Sections of an industry page.
 *
 * @param string $key Industry key.
 * @return array<int, array{0:string,1:array<string, mixed>}>
 */
function industry_layout( string $key ): array {
	$page = industry_pages()[ $key ] ?? null;

	if ( ! $page ) {
		return array();
	}

	$challenges = array();

	foreach ( $page['challenges'] as $row ) {
		$challenges[] = array(
			'title' => $row[0],
			'text'  => $row[1],
			'tags'  => '',
		);
	}

	return array(
		array(
			'page-hero',
			array(
				'badge'          => __( 'Industries', 'edshield' ),
				'heading'        => $page['hero_title'],
				'text'           => $page['hero_text'],
				'btn_label'      => __( 'Book a discovery call', 'edshield' ),
				'media_position' => 'aside',
				'art'            => $page['art'],
			),
		),
		array(
			'split',
			array(
				'label'   => __( 'Why it matters', 'edshield' ),
				'heading' => $page['tagline'],
				'text'    => '<p>' . $page['tagline_text'] . '</p>',
				'art'     => 'icon:' . $page['icon'],
				'flip'    => true,
			),
		),
		array(
			'rows',
			array(
				'layout'  => 'split',
				'label'   => __( 'The challenge', 'edshield' ),
				'heading' => __( 'Where it usually gets hard.', 'edshield' ),
				'intro'   => __( 'The same problems come up in almost every organisation in this sector. Each one has a practical fix.', 'edshield' ),
				'items'   => $challenges,
				'tone'    => 'paper',
			),
		),
		array(
			'services-grid',
			array(
				'label'     => __( 'How we help', 'edshield' ),
				/* translators: %s: industry name. */
				'heading'   => sprintf( __( 'What we build for %s.', 'edshield' ), $page['title'] ),
				'btn_label' => '',
				'style'     => 'icon',
				'items'     => icon_cards( $page['solutions'] ),
				'tone'      => 'white',
			),
		),
		array(
			'services-grid',
			array(
				'label'     => __( 'Services', 'edshield' ),
				'heading'   => __( 'The services behind it.', 'edshield' ),
				'btn_label' => __( 'All services', 'edshield' ),
				'btn_url'   => 'page:services',
				'columns'   => '4',
				'items'     => service_cards( $page['services'] ),
				'tone'      => 'paper',
			),
		),
		array( 'steps', array() ),
		array( 'testimonials', array( 'tone' => 'paper' ) ),
		array(
			'faq',
			array(
				/* translators: %s: industry name. */
				'heading' => sprintf( __( 'Questions from %s teams.', 'edshield' ), $page['title'] ),
				'items'   => qa_pairs( $page['faqs'] ),
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
