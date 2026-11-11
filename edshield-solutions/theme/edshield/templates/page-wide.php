<?php
/**
 * Template Name: ED Shield: full width, no title
 * Template Post Type: page
 *
 * Edge-to-edge page for builder layouts, dashboards and other application
 * pages. Content that is not built with sections sits in the full grid width.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="edst-main">
	<?php
	while ( have_posts() ) {
		the_post();

		if ( built_with_elementor( (int) get_the_ID() ) ) {
			the_content();
		} else {
			echo '<div class="edst-page-body"><div class="edst-wrap"><div class="edst-prose edst-prose--wide">';
			the_content();
			echo '</div></div></div>';
		}
	}
	?>
</main>
<?php
get_footer();
