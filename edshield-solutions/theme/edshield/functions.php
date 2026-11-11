<?php
/**
 * ED Shield theme bootstrap.
 *
 * Everything lives in the EDShield\Theme namespace so nothing can collide
 * with functions declared by plugins on the same site.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

const VERSION = '1.1.0';
const DIR     = __DIR__;

foreach ( array( 'helpers', 'art', 'content', 'content-pages', 'sections/definitions', 'sections/render', 'layouts', 'setup', 'cleanup', 'assets', 'customizer', 'nav', 'projects', 'contact', 'elementor', 'demo', 'seo', 'templates' ) as $edst_file ) {
	require DIR . '/inc/' . $edst_file . '.php';
}

if ( is_admin() ) {
	require DIR . '/inc/admin.php';
}

unset( $edst_file );
