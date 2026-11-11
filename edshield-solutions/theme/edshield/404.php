<?php
/**
 * Not found.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="edst-main">
	<?php
	echo render_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		'page-hero',
		array(
			'badge'     => __( 'Error 404', 'edshield' ),
			'heading'   => __( 'This page is not on the <em>map.</em>', 'edshield' ),
			'text'      => __( 'The address may have changed or the page may have been removed. Try the services below or search the site.', 'edshield' ),
			'btn_label' => __( 'Back to the homepage', 'edshield' ),
			'btn_url'   => 'home',
		)
	);
	?>
	<section class="edst-s edst-tone-white edst-space-compact">
		<div class="edst-wrap edst-notfound"><?php get_search_form(); ?></div>
	</section>
	<?php
	echo render_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		'services-grid',
		array(
			'source'  => 'services',
			'style'   => 'icon',
			'label'   => __( 'Services', 'edshield' ),
			'heading' => __( 'Where would you like to go?', 'edshield' ),
		)
	);
	?>
</main>
<?php
get_footer();
