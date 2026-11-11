<?php
/**
 * Theme options: Appearance › Customize › ED Shield.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', __NAMESPACE__ . '\customize_register' );
/**
 * Register the options.
 *
 * @param \WP_Customize_Manager $wp_customize Customizer.
 */
function customize_register( \WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_panel(
		'edst',
		array(
			'title'    => __( 'ED Shield', 'edshield' ),
			'priority' => 25,
		)
	);

	$sections = array(
		'edst_header'  => array(
			__( 'Header and menu', 'edshield' ),
			array(
				'announce_on'    => array( 'checkbox', __( 'Show the announcement bar', 'edshield' ), true ),
				'announce_text'  => array( 'text', __( 'Announcement text', 'edshield' ), '' ),
				'announce_label' => array( 'text', __( 'Announcement link label', 'edshield' ), '' ),
				'announce_url'   => array( 'url', __( 'Announcement link', 'edshield' ), '' ),
				'theme_builder'  => array( 'checkbox', __( 'Use Elementor Pro Theme Builder templates instead of the ED Shield header and footer', 'edshield' ), false ),
				'cta_label'      => array( 'text', __( 'Header button label', 'edshield' ), '' ),
				'cta_url'        => array( 'url', __( 'Header button link (empty: Contact page)', 'edshield' ), '' ),
				'mega_eyebrow'   => array( 'text', __( 'Mega menu card: eyebrow', 'edshield' ), '' ),
				'mega_title'     => array( 'text', __( 'Mega menu card: title', 'edshield' ), '' ),
				'mega_text'      => array( 'textarea', __( 'Mega menu card: text', 'edshield' ), '' ),
				'mega_label'     => array( 'text', __( 'Mega menu card: button', 'edshield' ), '' ),
				'mega_url'       => array( 'url', __( 'Mega menu card: link', 'edshield' ), '' ),
			),
		),
		'edst_contact' => array(
			__( 'Contact details', 'edshield' ),
			array(
				'contact_email'     => array( 'email', __( 'Email', 'edshield' ), '' ),
				'contact_phone'     => array( 'text', __( 'Phone', 'edshield' ), '' ),
				'contact_whatsapp'  => array( 'text', __( 'WhatsApp number (international format)', 'edshield' ), '' ),
				'contact_address'   => array( 'textarea', __( 'Address', 'edshield' ), '' ),
				'contact_hours'     => array( 'text', __( 'Office hours', 'edshield' ), '' ),
				'contact_shortcode' => array( 'text', __( 'Form shortcode (optional, replaces the built-in form)', 'edshield' ), '' ),
			),
		),
		'edst_social'  => array(
			__( 'Social profiles', 'edshield' ),
			array(
				'social_linkedin'  => array( 'url', 'LinkedIn', '' ),
				'social_facebook'  => array( 'url', 'Facebook', '' ),
				'social_instagram' => array( 'url', 'Instagram', '' ),
				'social_x'         => array( 'url', 'X', '' ),
				'social_youtube'   => array( 'url', 'YouTube', '' ),
			),
		),
		'edst_footer'  => array(
			__( 'Footer', 'edshield' ),
			array(
				'footer_tagline'  => array( 'textarea', __( 'Tagline', 'edshield' ), '' ),
				'footer_status'   => array( 'text', __( 'Status line', 'edshield' ), '' ),
				'footer_wordmark' => array( 'checkbox', __( 'Show the large wordmark', 'edshield' ), true ),
			),
		),
		'edst_leads'   => array(
			__( 'Leads and integrations', 'edshield' ),
			array(
				'lead_webhook'        => array( 'url', __( 'Webhook URL for new leads (for example n8n)', 'edshield' ), '' ),
				'lead_webhook_secret' => array( 'text', __( 'Webhook signing secret (sent as an HMAC SHA-256 signature)', 'edshield' ), '' ),
			),
		),
	);

	$sanitizers = array(
		'checkbox' => 'rest_sanitize_boolean',
		'text'     => 'sanitize_text_field',
		'textarea' => 'sanitize_textarea_field',
		'url'      => 'esc_url_raw',
		'email'    => 'sanitize_email',
	);

	foreach ( $sections as $section_id => $section ) {
		$wp_customize->add_section(
			$section_id,
			array(
				'title' => $section[0],
				'panel' => 'edst',
			)
		);

		foreach ( $section[1] as $key => $field ) {
			$wp_customize->add_setting(
				'edst_' . $key,
				array(
					'default'           => $field[2],
					'sanitize_callback' => $sanitizers[ $field[0] ],
				)
			);
			$wp_customize->add_control(
				'edst_' . $key,
				array(
					'section' => $section_id,
					'type'    => $field[0],
					'label'   => $field[1],
				)
			);
		}
	}
}
