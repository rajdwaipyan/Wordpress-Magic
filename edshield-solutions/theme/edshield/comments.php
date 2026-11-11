<?php
/**
 * Comments.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="edst-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="edst-comments__title">
			<?php
			printf(
				/* translators: %s: number of comments. */
				esc_html( _n( '%s comment', '%s comments', get_comments_number(), 'edshield' ) ),
				esc_html( number_format_i18n( get_comments_number() ) )
			);
			?>
		</h2>
		<ol class="edst-comments__list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 40,
				)
			);
			?>
		</ol>
		<?php the_comments_pagination(); ?>
	<?php endif; ?>
	<?php
	comment_form(
		array(
			'class_submit'       => 'edst-btn edst-btn--primary',
			'title_reply_before' => '<h2 id="reply-title" class="edst-comments__title">',
			'title_reply_after'  => '</h2>',
		)
	);
	?>
</section>
