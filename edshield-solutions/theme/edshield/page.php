<?php
/**
 * Pages: Elementor content, a starter layout, or a standard text page.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="edst-main">
	<?php
	if ( ! elementor_location( 'single' ) ) {
		while ( have_posts() ) {
			the_post();
			render_current_page();
		}
	}
	?>
</main>
<?php
get_footer();
