<?php
/**
 * Site footer and document end.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

if ( ! elementor_location( 'footer' ) ) {
	get_template_part( 'template-parts/footer/site-footer' );
}

wp_footer();
?>
</body>
</html>
