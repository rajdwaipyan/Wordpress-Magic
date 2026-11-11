<?php
/**
 * Built-in contact form and lead store.
 *
 * Works without any form plugin. Submissions are validated, protected against
 * bots (nonce, honeypot, time check, rate limit), saved as private "Leads",
 * emailed to the contact address and, when a webhook URL is set in the
 * Customizer, posted as signed JSON (for example to n8n).
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

add_action( 'init', __NAMESPACE__ . '\register_leads' );
/**
 * Private post type that stores form submissions.
 */
function register_leads(): void {
	register_post_type(
		'edst_lead',
		array(
			'labels'          => array(
				'name'          => __( 'Leads', 'edshield' ),
				'singular_name' => __( 'Lead', 'edshield' ),
				'all_items'     => __( 'All leads', 'edshield' ),
				'edit_item'     => __( 'Lead', 'edshield' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => false,
			'menu_icon'       => 'dashicons-email-alt',
			'menu_position'   => 22,
			// Leads hold personal data: only administrators can see or manage them.
			'capability_type' => 'post',
			'capabilities'    => array(
				'create_posts'           => 'do_not_allow',
				'edit_posts'             => 'manage_options',
				'edit_others_posts'      => 'manage_options',
				'edit_published_posts'   => 'manage_options',
				'edit_private_posts'     => 'manage_options',
				'publish_posts'          => 'manage_options',
				'read_private_posts'     => 'manage_options',
				'delete_posts'           => 'manage_options',
				'delete_others_posts'    => 'manage_options',
				'delete_published_posts' => 'manage_options',
				'delete_private_posts'   => 'manage_options',
			),
			'map_meta_cap'    => true,
			'supports'        => array( 'title' ),
		)
	);
}

/**
 * Service options offered in the form.
 *
 * @return string[]
 */
function form_services(): array {
	return array_merge( wp_list_pluck( services(), 'title' ), array( __( 'Not sure yet', 'edshield' ) ) );
}

add_shortcode( 'edst_contact_form', __NAMESPACE__ . '\\contact_form_shortcode' );
/**
 * [edst_contact_form]: the enquiry form anywhere (Elementor Shortcode widget,
 * block editor, widgets).
 */
function contact_form_shortcode(): string {
	static $depth = 0;

	if ( $depth > 0 ) {
		return '';
	}

	++$depth;
	$html = contact_form();
	--$depth;

	return '<div class="edst-form-card">' . $html . '</div>';
}

/**
 * The enquiry form, or the output of a form plugin's shortcode when one is
 * set in the Customizer.
 *
 * @param string $shortcode Optional shortcode that replaces the built-in form.
 */
function contact_form( string $shortcode = '' ): string {
	$shortcode = '' !== $shortcode ? $shortcode : trim( (string) option( 'contact_shortcode', '' ) );

	if ( '' !== $shortcode ) {
		$rendered = do_shortcode( $shortcode );

		if ( '' !== trim( $rendered ) && trim( $rendered ) !== trim( $shortcode ) ) {
			return $rendered;
		}
	}

	$status = isset( $_GET['edst_sent'] ) ? sanitize_key( wp_unslash( $_GET['edst_sent'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
	$notice = '';

	if ( '1' === $status ) {
		$notice = '<p class="edst-form__notice edst-form__notice--ok" role="status">' . esc_html__( 'Thank you. Your message has been received and we will be in touch.', 'edshield' ) . '</p>';
	} elseif ( '' !== $status ) {
		$messages = array(
			'invalid' => __( 'Please fill in your name, a valid email address and a message.', 'edshield' ),
			'consent' => __( 'Please confirm that we may contact you about your enquiry.', 'edshield' ),
			'limit'   => __( 'Too many messages were sent from your connection. Please try again later or email us directly.', 'edshield' ),
			'spam'    => __( 'Your message could not be sent. Please try again.', 'edshield' ),
		);
		$notice   = '<p class="edst-form__notice edst-form__notice--error" role="alert">' . esc_html( $messages[ $status ] ?? $messages['spam'] ) . '</p>';
	}

	$id      = wp_unique_id( 'edst-form-' );
	$options = '<option value="">' . esc_html__( 'Choose a service', 'edshield' ) . '</option>';

	foreach ( form_services() as $service ) {
		$options .= '<option value="' . esc_attr( $service ) . '">' . esc_html( $service ) . '</option>';
	}

	$privacy = get_privacy_policy_url();

	ob_start();
	?>
	<form class="edst-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="<?php echo esc_attr( $id ); ?>">
		<?php echo $notice; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>
		<input type="hidden" name="action" value="edst_contact">
		<input type="hidden" name="edst_return" value="<?php echo esc_url( remove_query_arg( 'edst_sent', home_url( add_query_arg( array() ) ) ) ); ?>">
		<input type="hidden" name="edst_ts" value="<?php echo esc_attr( (string) time() ); ?>">
		<?php wp_nonce_field( 'edst_contact', 'edst_nonce' ); ?>
		<div class="edst-form__hp" aria-hidden="true">
			<label for="<?php echo esc_attr( $id ); ?>-website"><?php esc_html_e( 'Leave this field empty', 'edshield' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $id ); ?>-website" name="edst_website" tabindex="-1" autocomplete="off">
		</div>
		<div class="edst-form__grid">
			<p class="edst-field">
				<label for="<?php echo esc_attr( $id ); ?>-name"><?php esc_html_e( 'Name', 'edshield' ); ?> <span aria-hidden="true">*</span></label>
				<input type="text" id="<?php echo esc_attr( $id ); ?>-name" name="edst_name" autocomplete="name" required maxlength="120">
			</p>
			<p class="edst-field">
				<label for="<?php echo esc_attr( $id ); ?>-email"><?php esc_html_e( 'Work email', 'edshield' ); ?> <span aria-hidden="true">*</span></label>
				<input type="email" id="<?php echo esc_attr( $id ); ?>-email" name="edst_email" autocomplete="email" required maxlength="160">
			</p>
			<p class="edst-field">
				<label for="<?php echo esc_attr( $id ); ?>-company"><?php esc_html_e( 'Organisation', 'edshield' ); ?></label>
				<input type="text" id="<?php echo esc_attr( $id ); ?>-company" name="edst_company" autocomplete="organization" maxlength="160">
			</p>
			<p class="edst-field">
				<label for="<?php echo esc_attr( $id ); ?>-phone"><?php esc_html_e( 'Phone', 'edshield' ); ?></label>
				<input type="tel" id="<?php echo esc_attr( $id ); ?>-phone" name="edst_phone" autocomplete="tel" maxlength="40">
			</p>
			<p class="edst-field edst-field--full">
				<label for="<?php echo esc_attr( $id ); ?>-service"><?php esc_html_e( 'What do you need help with?', 'edshield' ); ?></label>
				<select id="<?php echo esc_attr( $id ); ?>-service" name="edst_service"><?php echo $options; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></select>
			</p>
			<p class="edst-field edst-field--full">
				<label for="<?php echo esc_attr( $id ); ?>-message"><?php esc_html_e( 'Tell us about your project', 'edshield' ); ?> <span aria-hidden="true">*</span></label>
				<textarea id="<?php echo esc_attr( $id ); ?>-message" name="edst_message" rows="5" required maxlength="5000"></textarea>
			</p>
		</div>
		<p class="edst-check">
			<input type="checkbox" id="<?php echo esc_attr( $id ); ?>-consent" name="edst_consent" value="1" required>
			<label for="<?php echo esc_attr( $id ); ?>-consent">
				<?php
				echo $privacy
					/* translators: %s: privacy policy link. */
					? wp_kses_post( sprintf( __( 'I agree that ED Shield may contact me about this enquiry. See the %s.', 'edshield' ), '<a href="' . esc_url( $privacy ) . '">' . esc_html__( 'privacy policy', 'edshield' ) . '</a>' ) )
					: esc_html__( 'I agree that ED Shield may contact me about this enquiry.', 'edshield' );
				?>
			</label>
		</p>
		<button type="submit" class="edst-btn edst-btn--primary edst-btn--block"><span><?php esc_html_e( 'Send message', 'edshield' ); ?></span><?php echo icon( 'arrow-up-right', 'edst-btn__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
	</form>
	<?php

	return (string) ob_get_clean();
}

add_action( 'admin_post_nopriv_edst_contact', __NAMESPACE__ . '\handle_contact' );
add_action( 'admin_post_edst_contact', __NAMESPACE__ . '\handle_contact' );
/**
 * Handle a submission.
 */
function handle_contact(): void {
	$return = isset( $_POST['edst_return'] ) ? esc_url_raw( wp_unslash( $_POST['edst_return'] ) ) : home_url( '/' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified below.
	$return = wp_validate_redirect( $return, home_url( '/' ) );

	$back = static function ( string $status ) use ( $return ): void {
		wp_safe_redirect( add_query_arg( 'edst_sent', $status, $return ) . '#contact-form' );
		exit;
	};

	if ( ! isset( $_POST['edst_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['edst_nonce'] ) ), 'edst_contact' ) ) {
		$back( 'spam' );
	}

	$started = isset( $_POST['edst_ts'] ) ? absint( $_POST['edst_ts'] ) : 0;

	if ( ! empty( $_POST['edst_website'] ) || ! $started || time() - $started < 3 ) {
		$back( 'spam' );
	}

	$ip_key = 'edst_rl_' . md5( (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ) . wp_salt( 'nonce' ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- only hashed.
	$count  = (int) get_transient( $ip_key );

	if ( $count >= 5 ) {
		$back( 'limit' );
	}

	$data = array(
		'name'    => isset( $_POST['edst_name'] ) ? sanitize_text_field( wp_unslash( $_POST['edst_name'] ) ) : '',
		'email'   => isset( $_POST['edst_email'] ) ? sanitize_email( wp_unslash( $_POST['edst_email'] ) ) : '',
		'company' => isset( $_POST['edst_company'] ) ? sanitize_text_field( wp_unslash( $_POST['edst_company'] ) ) : '',
		'phone'   => isset( $_POST['edst_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['edst_phone'] ) ) : '',
		'service' => isset( $_POST['edst_service'] ) ? sanitize_text_field( wp_unslash( $_POST['edst_service'] ) ) : '',
		'message' => isset( $_POST['edst_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['edst_message'] ) ) : '',
		'source'  => $return,
	);

	if ( '' === $data['name'] || ! is_email( $data['email'] ) || '' === $data['message'] ) {
		$back( 'invalid' );
	}

	if ( empty( $_POST['edst_consent'] ) ) {
		$back( 'consent' );
	}

	if ( '' !== $data['service'] && ! in_array( $data['service'], form_services(), true ) ) {
		$data['service'] = '';
	}

	set_transient( $ip_key, $count + 1, HOUR_IN_SECONDS );

	$lead_id = wp_insert_post(
		array(
			'post_type'   => 'edst_lead',
			'post_status' => 'private',
			/* translators: 1: name, 2: organisation or email. */
			'post_title'  => sprintf( __( '%1$s (%2$s)', 'edshield' ), $data['name'], '' !== $data['company'] ? $data['company'] : $data['email'] ),
		),
		true
	);

	if ( ! is_wp_error( $lead_id ) ) {
		update_post_meta( $lead_id, '_edst_lead', $data );
	}

	$to      = sanitize_email( (string) option( 'contact_email', '' ) );
	$to      = '' !== $to ? $to : (string) get_option( 'admin_email' );
	$subject = sprintf( /* translators: %s: name. */ __( 'New enquiry from %s', 'edshield' ), $data['name'] );
	$body    = '';

	foreach ( $data as $k => $v ) {
		$body .= ucfirst( $k ) . ': ' . $v . "\n";
	}

	wp_mail( $to, $subject, $body, array( 'Reply-To: ' . $data['name'] . ' <' . $data['email'] . '>' ) );

	$webhook = esc_url_raw( (string) option( 'lead_webhook', '' ) );

	if ( '' !== $webhook && wp_http_validate_url( $webhook ) ) {
		$payload = wp_json_encode(
			array(
				'event'   => 'lead.created',
				'lead_id' => is_wp_error( $lead_id ) ? 0 : (int) $lead_id,
				'site'    => home_url( '/' ),
				'time'    => gmdate( 'c' ),
				'lead'    => $data,
			)
		);
		$secret  = (string) option( 'lead_webhook_secret', '' );
		$headers = array( 'Content-Type' => 'application/json' );

		if ( '' !== $secret ) {
			$headers['X-EDShield-Signature'] = 'sha256=' . hash_hmac( 'sha256', (string) $payload, $secret );
		}

		wp_remote_post(
			$webhook,
			array(
				'timeout'  => 5,
				'blocking' => false,
				'headers'  => $headers,
				'body'     => $payload,
			)
		);
	}

	/**
	 * Fires after a lead is stored and notified.
	 *
	 * @param int   $lead_id Lead post ID (0 if it could not be stored).
	 * @param array $data    Submitted fields.
	 */
	do_action( 'edst_lead_submitted', is_wp_error( $lead_id ) ? 0 : (int) $lead_id, $data );

	$back( '1' );
}

add_filter( 'manage_edst_lead_posts_columns', __NAMESPACE__ . '\lead_columns' );
/**
 * Lead list columns.
 *
 * @param array<string, string> $columns Columns.
 * @return array<string, string>
 */
function lead_columns( array $columns ): array {
	return array(
		'cb'           => $columns['cb'] ?? '',
		'title'        => __( 'Lead', 'edshield' ),
		'edst_email'   => __( 'Email', 'edshield' ),
		'edst_service' => __( 'Service', 'edshield' ),
		'date'         => __( 'Received', 'edshield' ),
	);
}

add_action( 'manage_edst_lead_posts_custom_column', __NAMESPACE__ . '\lead_column', 10, 2 );
/**
 * Lead list column values.
 *
 * @param string $column  Column.
 * @param int    $post_id Post ID.
 */
function lead_column( string $column, int $post_id ): void {
	$data = get_post_meta( $post_id, '_edst_lead', true );
	$data = is_array( $data ) ? $data : array();

	if ( 'edst_email' === $column && ! empty( $data['email'] ) ) {
		echo '<a href="' . esc_attr( 'mailto:' . $data['email'] ) . '">' . esc_html( $data['email'] ) . '</a>';
	} elseif ( 'edst_service' === $column ) {
		echo esc_html( $data['service'] ?? '' );
	}
}

add_action( 'add_meta_boxes_edst_lead', __NAMESPACE__ . '\lead_meta_box' );
/**
 * Read-only view of a lead.
 */
function lead_meta_box(): void {
	add_meta_box(
		'edst-lead',
		__( 'Enquiry', 'edshield' ),
		static function ( \WP_Post $post ): void {
			$data = get_post_meta( $post->ID, '_edst_lead', true );
			$data = is_array( $data ) ? $data : array();

			echo '<table class="widefat striped"><tbody>';

			foreach ( $data as $k => $v ) {
				echo '<tr><th style="width:140px">' . esc_html( ucfirst( (string) $k ) ) . '</th><td>' . nl2br( esc_html( (string) $v ) ) . '</td></tr>';
			}

			echo '</tbody></table>';
		},
		'edst_lead',
		'normal',
		'high'
	);
}
