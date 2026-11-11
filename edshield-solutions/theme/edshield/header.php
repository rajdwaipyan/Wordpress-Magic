<?php
/**
 * Document head and site header.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="edst-skip" href="#main"><?php esc_html_e( 'Skip to content', 'edshield' ); ?></a>
<?php
if ( ! elementor_location( 'header' ) ) {
	get_template_part( 'template-parts/header/site-header' );
}
