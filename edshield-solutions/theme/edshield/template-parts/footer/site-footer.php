<?php
/**
 * Site footer.
 *
 * Contact details and social links appear only once entered in
 * Appearance › Customize › ED Shield.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

$edst_contact  = contact_details();
$edst_social   = social_links();
$edst_tagline  = trim( (string) option( 'footer_tagline', '' ) );
$edst_tagline  = '' !== $edst_tagline ? $edst_tagline : __( 'A technology and digital transformation partner for organisations of every size, in Bangladesh and internationally.', 'edshield' );
$edst_status   = trim( (string) option( 'footer_status', '' ) );
$edst_status   = '' !== $edst_status ? $edst_status : __( 'Engineering digital growth. Empowering businesses.', 'edshield' );
$edst_services = array();

foreach ( services() as $edst_key => $edst_service ) {
	$edst_services[] = nav_node( $edst_service['title'], 'service:' . $edst_key );
}

$edst_company = array(
	nav_node( __( 'About', 'edshield' ), 'page:about' ),
	nav_node( __( 'Process', 'edshield' ), 'page:process' ),
	nav_node( __( 'Work', 'edshield' ), 'page:work' ),
	nav_node( __( 'Engagement models', 'edshield' ), 'page:engagement' ),
	nav_node( __( 'Careers', 'edshield' ), 'page:careers' ),
	nav_node( __( 'Insights', 'edshield' ), 'posts' ),
	nav_node( __( 'FAQ', 'edshield' ), 'page:faq' ),
);
?>
<footer class="edst-footer edst-tone-ink">
	<div class="edst-wrap">
		<div class="edst-footer__top">
			<div class="edst-footer__brand">
				<?php echo brand(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<p class="edst-footer__tagline"><?php echo esc_html( $edst_tagline ); ?></p>
				<?php if ( $edst_social ) : ?>
					<ul class="edst-social">
						<?php foreach ( $edst_social as $edst_net => $edst_url ) : ?>
							<li><a href="<?php echo esc_url( $edst_url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( ucfirst( $edst_net ) ); ?>"><?php echo icon( $edst_net ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
			<div class="edst-footer__cta">
				<p class="edst-footer__status"><span class="edst-dot" aria-hidden="true"></span><?php echo esc_html( $edst_status ); ?></p>
				<?php echo button( header_cta()['label'], header_cta()['url'], 'primary' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>

		<div class="edst-footer__cols">
			<nav class="edst-footer__col edst-footer__col--wide" aria-label="<?php esc_attr_e( 'Services', 'edshield' ); ?>">
				<p class="edst-eyebrow"><?php esc_html_e( 'Services', 'edshield' ); ?></p>
				<?php echo footer_links( '', $edst_services ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</nav>
			<nav class="edst-footer__col" aria-label="<?php esc_attr_e( 'Company', 'edshield' ); ?>">
				<p class="edst-eyebrow"><?php esc_html_e( 'Company', 'edshield' ); ?></p>
				<?php echo footer_links( 'company', $edst_company ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</nav>
			<div class="edst-footer__col">
				<p class="edst-eyebrow"><?php esc_html_e( 'Get in touch', 'edshield' ); ?></p>
				<ul class="edst-footer__links">
					<?php if ( '' !== $edst_contact['email'] ) : ?>
						<li><a href="<?php echo esc_attr( 'mailto:' . antispambot( $edst_contact['email'], 1 ) ); ?>"><?php echo esc_html( antispambot( $edst_contact['email'] ) ); ?></a></li>
					<?php endif; ?>
					<?php if ( '' !== $edst_contact['phone'] ) : ?>
						<li><a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $edst_contact['phone'] ) ); ?>"><?php echo esc_html( $edst_contact['phone'] ); ?></a></li>
					<?php endif; ?>
					<?php if ( '' !== $edst_contact['whatsapp'] ) : ?>
						<li><a href="<?php echo esc_url( 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $edst_contact['whatsapp'] ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'WhatsApp', 'edshield' ); ?></a></li>
					<?php endif; ?>
					<?php if ( '' !== $edst_contact['address'] ) : ?>
						<li class="edst-footer__address"><?php echo nl2br( esc_html( $edst_contact['address'] ) ); ?></li>
					<?php endif; ?>
					<li><a href="<?php echo esc_url( resolve_url( 'page:contact' ) ); ?>"><?php esc_html_e( 'Contact form', 'edshield' ); ?></a></li>
				</ul>
			</div>
		</div>

		<div class="edst-footer__bottom">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> ED Shield Solutions</p>
			<?php
			$edst_legal = footer_links( 'legal', get_privacy_policy_url() ? array( nav_node( __( 'Privacy policy', 'edshield' ), get_privacy_policy_url() ) ) : array() );
			echo $edst_legal; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
			<a class="edst-footer__top-link" href="#main"><?php esc_html_e( 'Back to top', 'edshield' ); ?><?php echo icon( 'arrow-up-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
		</div>
	</div>
	<?php if ( option( 'footer_wordmark', true ) ) : ?>
		<div class="edst-footer__mark" aria-hidden="true"><span>ED SHIELD</span></div>
	<?php endif; ?>
</footer>
