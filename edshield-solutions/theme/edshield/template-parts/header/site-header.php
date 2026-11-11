<?php
/**
 * Site header: announcement bar, brand, mega menu, call to action, mobile menu.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

$edst_cta       = header_cta();
$edst_announce  = (bool) option( 'announce_on', true );
$edst_a_text    = trim( (string) option( 'announce_text', '' ) );
$edst_a_text    = '' !== $edst_a_text ? $edst_a_text : __( 'From websites to AI automation: one accountable technology partner.', 'edshield' );
$edst_a_label   = trim( (string) option( 'announce_label', '' ) );
$edst_a_label   = '' !== $edst_a_label ? $edst_a_label : __( 'Book a discovery call', 'edshield' );
$edst_a_url     = trim( (string) option( 'announce_url', '' ) );
$edst_a_url     = '' !== $edst_a_url ? $edst_a_url : resolve_url( 'page:contact' );
?>
<?php if ( $edst_announce ) : ?>
	<div class="edst-announce" data-edst-announce="<?php echo esc_attr( substr( md5( $edst_a_text ), 0, 8 ) ); ?>">
		<div class="edst-wrap edst-announce__inner">
			<p><?php echo esc_html( $edst_a_text ); ?> <a href="<?php echo esc_url( $edst_a_url ); ?>"><?php echo esc_html( $edst_a_label ); ?><?php echo icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></p>
			<button type="button" class="edst-announce__close" data-edst-announce-close aria-label="<?php esc_attr_e( 'Close announcement', 'edshield' ); ?>"><?php echo icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
		</div>
	</div>
<?php endif; ?>
<header class="edst-header" data-edst-header>
	<div class="edst-wrap edst-header__bar">
		<?php echo brand(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php primary_nav(); ?>
		<div class="edst-header__actions">
			<?php echo button( $edst_cta['label'], $edst_cta['url'], 'primary', 'edst-btn--sm edst-header__cta edst-magnetic' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<button type="button" class="edst-burger" data-edst-burger aria-expanded="false" aria-controls="edst-mobile">
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'edshield' ); ?></span>
				<span class="edst-burger__lines" aria-hidden="true"></span>
			</button>
		</div>
	</div>
</header>
<?php
mobile_nav();
