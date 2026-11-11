<?php
/**
 * Single articles.
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
			$edst_id   = (int) get_the_ID();
			$edst_cats = get_the_category();
			?>
			<article <?php post_class( 'edst-article' ); ?>>
				<?php
				echo render_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'page-hero',
					array(
						'badge'     => $edst_cats ? $edst_cats[0]->name : '',
						'heading'   => esc_html( get_the_title() ),
						'text'      => has_excerpt() ? esc_html( get_the_excerpt() ) : '',
						'btn_label' => '',
					)
				);
				?>
				<div class="edst-page-body">
					<div class="edst-wrap">
						<p class="edst-article__meta">
							<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
							<span><?php echo esc_html( sprintf( /* translators: %d: minutes. */ _n( '%d minute read', '%d minutes read', reading_time( $edst_id ), 'edshield' ), reading_time( $edst_id ) ) ); ?></span>
							<span><?php the_author(); ?></span>
						</p>
						<?php if ( has_post_thumbnail() ) : ?>
							<figure class="edst-article__media edst-frame"><?php the_post_thumbnail( 'edst-wide', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'class' => 'edst-img' ) ); ?></figure>
						<?php endif; ?>
						<div class="edst-prose">
							<?php
							the_content();
							wp_link_pages();
							?>
						</div>
						<?php the_tags( '<ul class="edst-tags edst-article__tags"><li>', '</li><li>', '</li></ul>' ); ?>
						<nav class="edst-postnav" aria-label="<?php esc_attr_e( 'More articles', 'edshield' ); ?>">
							<?php
							previous_post_link( '<div class="edst-postnav__item"><span class="edst-eyebrow">' . esc_html__( 'Previous', 'edshield' ) . '</span>%link</div>' );
							next_post_link( '<div class="edst-postnav__item edst-postnav__item--next"><span class="edst-eyebrow">' . esc_html__( 'Next', 'edshield' ) . '</span>%link</div>' );
							?>
						</nav>
						<?php
						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}
						?>
					</div>
				</div>
			</article>
			<?php
			echo render_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'posts',
				array(
					'heading'   => __( 'Keep reading.', 'edshield' ),
					'btn_label' => __( 'All articles', 'edshield' ),
				)
			);
			echo render_section( 'cta' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
	?>
</main>
<?php
get_footer();
