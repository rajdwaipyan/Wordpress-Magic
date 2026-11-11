<?php
/**
 * Search form.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

$edst_search_id = wp_unique_id( 'edst-search-' );
?>
<form role="search" method="get" class="edst-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $edst_search_id ); ?>"><?php esc_html_e( 'Search the site', 'edshield' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $edst_search_id ); ?>" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search the site', 'edshield' ); ?>">
	<button type="submit" class="edst-btn edst-btn--primary"><span><?php esc_html_e( 'Search', 'edshield' ); ?></span></button>
</form>
