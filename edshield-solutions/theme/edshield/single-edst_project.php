<?php
/**
 * Case study.
 *
 * Header and details come from the "Case study details" box. The body is the
 * project's Elementor content when it is built in Elementor; otherwise the
 * overview from the details box followed by the editor content.
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
			$edst_meta = project_meta( $edst_id );
			$edst_img  = get_post_thumbnail_id( $edst_id );

			echo render_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'page-hero',
				array(
					'badge'          => $edst_meta['industry'],
					'heading'        => esc_html( get_the_title() ),
					'text'           => has_excerpt() ? esc_html( get_the_excerpt() ) : '',
					'btn_label'      => __( 'Start a project', 'edshield' ),
					'media_position' => 'below',
					'art'            => '' !== $edst_meta['art'] ? $edst_meta['art'] : 'web',
					'image'          => array(
						'id'  => $edst_img ? (int) $edst_img : 0,
						'url' => '',
					),
				)
			);

			if ( built_with_elementor( $edst_id ) ) {
				the_content();
			} else {
				echo render_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'case-overview',
					array(
						'client'     => $edst_meta['client'],
						'industry'   => $edst_meta['industry'],
						'services'   => $edst_meta['services'],
						'year'       => $edst_meta['year'],
						'website'    => $edst_meta['website'],
						'challenge'  => $edst_meta['challenge'],
						'goal'       => $edst_meta['goal'],
						'objectives' => $edst_meta['objectives'],
					)
				);

				if ( '' !== trim( (string) get_post_field( 'post_content', $edst_id ) ) ) {
					echo '<section class="edst-s edst-tone-white edst-space-flush"><div class="edst-wrap"><div class="edst-prose edst-case-body">';
					the_content();
					echo '</div></div></section>';
				}

				$edst_results = project_results( $edst_id );

				if ( $edst_results ) {
					echo render_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'stats',
						array(
							'label'     => __( 'Results', 'edshield' ),
							'heading'   => __( 'What changed.', 'edshield' ),
							'btn_label' => '',
							'items'     => $edst_results,
							'tone'      => 'bone',
						)
					);
				}
			}

			echo render_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'work',
				array(
					'label'     => __( 'More work', 'edshield' ),
					'heading'   => __( 'Other projects to explore.', 'edshield' ),
					'btn_label' => __( 'All work', 'edshield' ),
					'btn_url'   => 'page:work',
				)
			);
			echo render_section( 'cta' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
	?>
</main>
<?php
get_footer();
