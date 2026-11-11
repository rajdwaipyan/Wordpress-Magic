<?php
/**
 * Blog, archives and search results.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

get_header();

$edst_posts_page = (int) get_option( 'page_for_posts' );

if ( is_search() ) {
	/* translators: %s: search terms. */
	$edst_title = sprintf( __( 'Results for “%s”', 'edshield' ), get_search_query() );
	$edst_text  = '';
} elseif ( is_archive() ) {
	$edst_title = wp_strip_all_tags( get_the_archive_title() );
	$edst_text  = wp_strip_all_tags( (string) get_the_archive_description() );
} else {
	$edst_title = $edst_posts_page ? get_the_title( $edst_posts_page ) : __( 'Insights', 'edshield' );
	$edst_text  = $edst_posts_page && has_excerpt( $edst_posts_page ) ? get_the_excerpt( $edst_posts_page ) : __( 'Notes on technology, automation, security and digital growth.', 'edshield' );
}
?>
<main id="main" class="edst-main">
	<?php
	echo render_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		'page-hero',
		array(
			'heading'   => esc_html( $edst_title ),
			'text'      => esc_html( $edst_text ),
			'btn_label' => '',
		)
	);
	?>
	<section class="edst-s edst-tone-white edst-space-default">
		<div class="edst-wrap">
			<?php echo category_pills(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php if ( have_posts() ) : ?>
				<div class="edst-posts edst-cols-3">
					<?php
					while ( have_posts() ) {
						the_post();
						echo post_card( (int) get_the_ID() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					?>
				</div>
				<?php pagination(); ?>
			<?php else : ?>
				<div class="edst-empty">
					<p><?php esc_html_e( 'Nothing has been published here yet.', 'edshield' ); ?></p>
					<?php get_search_form(); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php echo render_section( 'cta' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</main>
<?php
get_footer();
