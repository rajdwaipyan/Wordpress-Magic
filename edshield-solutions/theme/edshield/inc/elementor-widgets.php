<?php
/**
 * Elementor widgets. Loaded only from the elementor/widgets/register hook,
 * when Elementor's base classes exist.
 *
 * Every section widget builds its controls from the section schema in
 * inc/sections/definitions.php and renders through the shared renderer, so
 * a widget and the matching theme section always produce the same markup.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * Base class for all ED Shield section widgets.
 */
abstract class Section_Widget extends Widget_Base {

	/**
	 * Section key; set by each subclass.
	 */
	const KEY = '';

	/**
	 * Section definition.
	 *
	 * @return array<string, mixed>
	 */
	protected function edst_def(): array {
		$defs = \EDShield\Theme\section_definitions();

		return $defs[ static::KEY ] ?? array(
			'title'  => static::KEY,
			'fields' => array(),
		);
	}

	public function get_name(): string {
		return 'edst-' . static::KEY;
	}

	public function get_title(): string {
		/* translators: %s: section name. */
		return sprintf( __( 'ED Shield: %s', 'edshield' ), (string) ( $this->edst_def()['title'] ?? static::KEY ) );
	}

	public function get_icon(): string {
		return (string) ( $this->edst_def()['icon'] ?? 'eicon-site-identity' );
	}

	public function get_categories(): array {
		return array( 'edshield' );
	}

	public function get_keywords(): array {
		return array( 'edshield', 'ed shield', 'section', str_replace( '-', ' ', static::KEY ) );
	}

	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	protected function is_dynamic_content(): bool {
		return in_array( static::KEY, array( 'work', 'posts', 'contact', 'page-hero' ), true );
	}

	/**
	 * Elementor control arguments for one schema field.
	 *
	 * @param array<string, mixed> $field Field.
	 * @return array<string, mixed>
	 */
	protected function edst_control( array $field ): array {
		$args = array(
			'label'   => $field['label'],
			'default' => $field['default'],
		);

		switch ( $field['type'] ) {
			case 'text':
				$args['type']        = Controls_Manager::TEXT;
				$args['label_block'] = true;
				$args['dynamic']     = array( 'active' => true );
				break;

			case 'textarea':
				$args['type']    = Controls_Manager::TEXTAREA;
				$args['rows']    = $field['rows'] ?? 3;
				$args['dynamic'] = array( 'active' => true );
				break;

			case 'wysiwyg':
				$args['type'] = Controls_Manager::WYSIWYG;
				break;

			case 'url':
				$args['type']    = Controls_Manager::URL;
				$args['dynamic'] = array( 'active' => true );
				break;

			case 'media':
				$args['type']    = Controls_Manager::MEDIA;
				$args['dynamic'] = array( 'active' => true );
				break;

			case 'select':
				$args['type']    = Controls_Manager::SELECT;
				$args['options'] = $field['options'];
				break;

			case 'switch':
				$args['type']         = Controls_Manager::SWITCHER;
				$args['return_value'] = 'yes';
				$args['label_on']     = __( 'Yes', 'edshield' );
				$args['label_off']    = __( 'No', 'edshield' );
				break;

			case 'number':
				$args['type'] = Controls_Manager::NUMBER;
				$args['min']  = 1;
				$args['max']  = 48;
				break;

			default:
				$args['type'] = Controls_Manager::TEXT;
		}

		if ( ! empty( $field['description'] ) ) {
			$args['description'] = $field['description'];
		}

		if ( ! empty( $field['condition'] ) ) {
			$args['condition'] = $field['condition'];
		}

		return $args;
	}

	/**
	 * Add one schema field as a control.
	 *
	 * @param array<string, mixed> $field Field.
	 */
	protected function edst_add_field( array $field ): void {
		if ( 'repeater' !== $field['type'] ) {
			$this->add_control( $field['id'], $this->edst_control( $field ) );

			return;
		}

		$repeater = new Repeater();

		foreach ( $field['fields'] as $sub ) {
			$repeater->add_control( $sub['id'], $this->edst_control( $sub ) );
		}

		$this->add_control(
			$field['id'],
			array(
				'label'       => $field['label'],
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => $field['default'],
				'title_field' => '{{{ ' . ( $field['title_field'] ?? 'title' ) . ' }}}',
			)
		);
	}

	protected function register_controls(): void {
		$def = $this->edst_def();

		$this->start_controls_section(
			'edst_content',
			array(
				'label' => __( 'Content', 'edshield' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		foreach ( $def['fields'] as $field ) {
			$this->edst_add_field( $field );
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'edst_section',
			array(
				'label' => __( 'Section', 'edshield' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		foreach ( \EDShield\Theme\common_fields( $def ) as $field ) {
			$this->edst_add_field( $field );
		}

		$this->end_controls_section();

		$this->edst_style_controls();
	}

	/**
	 * Style tab controls shared by every section: colours, typography,
	 * background and spacing. Colours are written to the section's design
	 * tokens, so every card, button and line inside follows them.
	 */
	protected function edst_style_controls(): void {
		$section = '{{WRAPPER}} > .edst-s, {{WRAPPER}} > .edst-s .edst-cta';
		$colors  = array(
			'edst_c_bg'      => array( __( 'Background', 'edshield' ), '--edst-bg' ),
			'edst_c_fg'      => array( __( 'Headings and text', 'edshield' ), '--edst-fg' ),
			'edst_c_muted'   => array( __( 'Secondary text', 'edshield' ), '--edst-muted' ),
			'edst_c_accent'  => array( __( 'Accent (labels, numbers, links)', 'edshield' ), '--edst-accent' ),
			'edst_c_em'      => array( __( 'Highlighted words in headings', 'edshield' ), '--edst-em' ),
			'edst_c_surface' => array( __( 'Cards and panels', 'edshield' ), '--edst-surface' ),
			'edst_c_line'    => array( __( 'Lines and borders', 'edshield' ), '--edst-line' ),
			'edst_c_button'  => array( __( 'Brand accent (buttons and icons)', 'edshield' ), '--edst-gold' ),
		);

		$this->start_controls_section(
			'edst_style_colors',
			array(
				'label' => __( 'Colours', 'edshield' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'edst_colors_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Leave empty to use the section tone. Colours apply to everything inside the section.', 'edshield' ),
				'content_classes' => 'elementor-descriptor',
			)
		);

		foreach ( $colors as $id => $color ) {
			$this->add_control(
				$id,
				array(
					'label'     => $color[0],
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						$section => $color[1] . ': {{VALUE}};',
					),
				)
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'edst_style_type',
			array(
				'label' => __( 'Typography', 'edshield' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'edst_t_heading',
				'label'    => __( 'Main heading', 'edshield' ),
				'selector' => '{{WRAPPER}} :is(.edst-display, .edst-h1, .edst-h2)',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'edst_t_title',
				'label'    => __( 'Card and item titles', 'edshield' ),
				'selector' => '{{WRAPPER}} :is(.edst-h3, .edst-card__title, .edst-svc-row__title, .edst-step__title, .edst-row__title, .edst-incl__title, .edst-plan__name, .edst-case__title, .edst-post-card__title, .edst-qa summary)',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'edst_t_text',
				'label'    => __( 'Body text', 'edshield' ),
				'selector' => '{{WRAPPER}} :is(.edst-lede, .edst-head__intro, .edst-rich, .edst-card__text, .edst-svc-row__text, .edst-row__body p, .edst-step__text, .edst-incl__text, .edst-qa__a, .edst-cta__text, .edst-contact__text, .edst-plan__summary)',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'edst_t_label',
				'label'    => __( 'Small labels', 'edshield' ),
				'selector' => '{{WRAPPER}} .edst-eyebrow',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'edst_style_box',
			array(
				'label' => __( 'Background and spacing', 'edshield' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'edst_bg',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} > .edst-s',
			)
		);

		$this->add_responsive_control(
			'edst_padding',
			array(
				'label'              => __( 'Section padding', 'edshield' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', 'rem', 'vh' ),
				'allowed_dimensions' => 'vertical',
				'selectors'          => array(
					'{{WRAPPER}} > .edst-s' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'edst_width',
			array(
				'label'      => __( 'Content width', 'edshield' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem', '%' ),
				'range'      => array(
					'px'  => array(
						'min' => 600,
						'max' => 1800,
					),
					'rem' => array(
						'min' => 40,
						'max' => 110,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} > .edst-s' => '--edst-max: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'edst_radius',
			array(
				'label'      => __( 'Card corner radius', 'edshield' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} > .edst-s' => '--edst-r-md: {{SIZE}}{{UNIT}}; --edst-r-lg: calc({{SIZE}}{{UNIT}} * 1.5);',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		echo \EDShield\Theme\render_section( static::KEY, (array) $this->get_settings_for_display() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sections escape their own output.
	}
}

// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound, Squiz.Commenting.ClassComment.Missing

final class Hero_Home extends Section_Widget {
	const KEY = 'hero-home';
}

final class Page_Hero extends Section_Widget {
	const KEY = 'page-hero';
}

final class Services_Grid extends Section_Widget {
	const KEY = 'services-grid';
}

final class Features extends Section_Widget {
	const KEY = 'features';
}

final class Industries extends Section_Widget {
	const KEY = 'industries';
}

final class Services_List extends Section_Widget {
	const KEY = 'services-list';
}

final class Work extends Section_Widget {
	const KEY = 'work';
}

final class Stats extends Section_Widget {
	const KEY = 'stats';
}

final class Testimonials extends Section_Widget {
	const KEY = 'testimonials';
}

final class Steps extends Section_Widget {
	const KEY = 'steps';
}

final class Rows extends Section_Widget {
	const KEY = 'rows';
}

final class Posts extends Section_Widget {
	const KEY = 'posts';
}

final class Faq extends Section_Widget {
	const KEY = 'faq';
}

final class Faq_Groups extends Section_Widget {
	const KEY = 'faq-groups';
}

final class Cta extends Section_Widget {
	const KEY = 'cta';
}

final class Split extends Section_Widget {
	const KEY = 'split';
}

final class Included extends Section_Widget {
	const KEY = 'included';
}

final class Plans extends Section_Widget {
	const KEY = 'plans';
}

final class Contact extends Section_Widget {
	const KEY = 'contact';
}

final class Case_Overview extends Section_Widget {
	const KEY = 'case-overview';
}

final class Gallery extends Section_Widget {
	const KEY = 'gallery';
}

final class Marquee extends Section_Widget {
	const KEY = 'marquee';
}

final class Rich_Text extends Section_Widget {
	const KEY = 'rich-text';
}

/**
 * Base for the site header and footer widgets (for Theme Builder templates).
 */
abstract class Site_Part_Widget extends Widget_Base {

	const PART  = '';
	const TITLE = '';

	public function get_name(): string {
		return 'edst-site-' . static::PART;
	}

	public function get_title(): string {
		return static::TITLE;
	}

	public function get_icon(): string {
		return 'header' === static::PART ? 'eicon-header' : 'eicon-footer';
	}

	public function get_categories(): array {
		return array( 'edshield' );
	}

	public function get_keywords(): array {
		return array( 'edshield', 'header', 'footer', 'menu', 'theme builder' );
	}

	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'edst_info',
			array(
				'label' => __( 'ED Shield', 'edshield' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'edst_note',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => esc_html__( 'Shows the theme’s own header or footer, including the mega menu. Edit links in Appearance › Menus and texts in Appearance › Customize › ED Shield.', 'edshield' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		get_template_part( 'template-parts/' . static::PART . '/site-' . static::PART );
	}
}

final class Site_Header extends Site_Part_Widget {
	const PART  = 'header';
	const TITLE = 'ED Shield: Site header';
}

final class Site_Footer extends Site_Part_Widget {
	const PART  = 'footer';
	const TITLE = 'ED Shield: Site footer';
}

// phpcs:enable

/**
 * All widget classes.
 *
 * @return string[]
 */
function widget_classes(): array {
	return array(
		Hero_Home::class,
		Page_Hero::class,
		Services_Grid::class,
		Features::class,
		Industries::class,
		Services_List::class,
		Work::class,
		Stats::class,
		Testimonials::class,
		Steps::class,
		Rows::class,
		Posts::class,
		Faq::class,
		Faq_Groups::class,
		Cta::class,
		Split::class,
		Included::class,
		Plans::class,
		Contact::class,
		Case_Overview::class,
		Gallery::class,
		Marquee::class,
		Rich_Text::class,
		Site_Header::class,
		Site_Footer::class,
	);
}
