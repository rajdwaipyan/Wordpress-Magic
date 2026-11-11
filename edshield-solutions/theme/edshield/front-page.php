<?php
/**
 * Front page: the page set as front page, or the starter home layout when
 * the site shows latest posts on the front.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="edst-main">
	<?php
	if ( 'page' === get_option( 'show_on_front' ) && have_posts() ) {
		while ( have_posts() ) {
			the_post();
			render_current_page();
		}
	} else {
		render_layout( 'home' );
	}
	?>
</main>
<?php
get_footer();
