<?php
/**
 * Section renderers.
 *
 * The same functions render a section whether it comes from an Elementor
 * widget or from the theme's own page layouts, so the markup is identical in
 * both cases. Elements with the class "edst-rv" rise into view on scroll.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

/* Accessors ---------------------------------------------------------------- */

/**
 * Trimmed string setting.
 *
 * @param array<string, mixed> $s Settings.
 * @param string               $k Key.
 */
function s_text( array $s, string $k ): string {
	$v = $s[ $k ] ?? '';

	return is_scalar( $v ) ? trim( (string) $v ) : '';
}

/**
 * Media setting as id and url.
 *
 * @param array<string, mixed> $s Settings.
 * @param string               $k Key.
 * @return array{id:int,url:string}
 */
function s_media( array $s, string $k ): array {
	$m = isset( $s[ $k ] ) && is_array( $s[ $k ] ) ? $s[ $k ] : array();

	return array(
		'id'  => (int) ( $m['id'] ?? 0 ),
		'url' => (string) ( $m['url'] ?? '' ),
	);
}

/**
 * Switch setting.
 *
 * @param array<string, mixed> $s Settings.
 * @param string               $k Key.
 */
function s_on( array $s, string $k ): bool {
	return 'yes' === ( $s[ $k ] ?? '' );
}

/**
 * Repeater setting.
 *
 * @param array<string, mixed> $s Settings.
 * @param string               $k Key.
 * @return array<int, array<string, mixed>>
 */
function s_items( array $s, string $k ): array {
	return array_values( array_filter( (array) ( $s[ $k ] ?? array() ), 'is_array' ) );
}

/* Building blocks ---------------------------------------------------------- */

/**
 * Render one section to a string.
 *
 * @param string               $key      Section key.
 * @param array<string, mixed> $settings Settings (partial is fine).
 */
function render_section( string $key, array $settings = array() ): string {
	$fn = __NAMESPACE__ . '\render_' . str_replace( '-', '_', $key );

	if ( ! function_exists( $fn ) ) {
		return '';
	}

	ob_start();
	$fn( section_settings( $key, $settings ) );

	return (string) ob_get_clean();
}

/**
 * Open a section element.
 *
 * @param string               $key   Section key.
 * @param array<string, mixed> $s     Settings.
 * @param string               $class Extra classes.
 */
function section_open( string $key, array $s, string $class = '' ): void {
	$tone   = in_array( $s['tone'] ?? '', array( 'ink', 'charcoal', 'paper', 'white', 'bone' ), true ) ? $s['tone'] : 'white';
	$space  = in_array( $s['spacing'] ?? '', array( 'default', 'compact', 'large', 'flush', 'none' ), true ) ? $s['spacing'] : 'default';
	$anchor = sanitize_title( s_text( $s, 'anchor' ) );

	printf(
		'<section class="%1$s"%2$s>',
		esc_attr( trim( 'edst-s edst-s--' . $key . ' edst-tone-' . $tone . ' edst-space-' . $space . ' ' . $class ) ),
		'' !== $anchor ? ' id="' . esc_attr( $anchor ) . '"' : ''
	);
}

/**
 * Close a section element.
 */
function section_close(): void {
	echo '</section>';
}

/**
 * Standard section heading: eyebrow, heading, introduction and a button.
 *
 * @param array<string, mixed> $s      Settings.
 * @param string               $layout split | center | stack.
 */
function section_head( array $s, string $layout = 'split' ): string {
	$label   = s_text( $s, 'label' );
	$heading = s_text( $s, 'heading' );
	$intro   = s_text( $s, 'intro' );
	$btn     = button( s_text( $s, 'btn_label' ), $s['btn_url'] ?? '', 'primary' );

	if ( '' === $label . $heading . $intro . $btn ) {
		return '';
	}

	$html  = '<header class="edst-head edst-head--' . esc_attr( $layout ) . ' edst-rv"><div class="edst-head__main">';
	$html .= '' !== $label ? '<p class="edst-eyebrow">' . esc_html( $label ) . '</p>' : '';
	$html .= '' !== $heading ? '<h2 class="edst-h2">' . inline( $heading ) . '</h2>' : '';
	$html .= '' !== $intro ? '<p class="edst-head__intro">' . inline( $intro ) . '</p>' : '';
	$html .= '</div>';
	$html .= '' !== $btn ? '<div class="edst-head__aside">' . $btn . '</div>' : '';
	$html .= '</header>';

	return $html;
}

/**
 * An uploaded image, or an illustration when no image is set.
 *
 * @param string                   $art   Illustration key.
 * @param array{id:int,url:string} $media Media setting.
 * @param string                   $size  Image size.
 * @param string                   $sizes Sizes attribute.
 */
function visual( string $art, array $media, string $size = 'large', string $sizes = '' ): string {
	if ( $media['id'] > 0 ) {
		$img = wp_get_attachment_image(
			$media['id'],
			$size,
			false,
			array_filter(
				array(
					'class'    => 'edst-img',
					'loading'  => 'lazy',
					'decoding' => 'async',
					'sizes'    => $sizes,
				)
			)
		);

		if ( $img ) {
			return $img;
		}
	}

	if ( '' !== $media['url'] ) {
		return sprintf( '<img class="edst-img" src="%s" alt="" loading="lazy" decoding="async">', esc_url( $media['url'] ) );
	}

	return art( $art );
}

/**
 * A moving strip of words.
 *
 * @param string[] $items Items.
 * @param string   $size  large | small.
 */
function marquee( array $items, string $size = 'small' ): string {
	if ( ! $items ) {
		return '';
	}

	$list = '';

	foreach ( $items as $item ) {
		$list .= '<li>' . esc_html( $item ) . '</li>';
	}

	return sprintf(
		'<div class="edst-marquee edst-marquee--%1$s"><div class="edst-marquee__track"><ul class="edst-marquee__list">%2$s</ul><ul class="edst-marquee__list" aria-hidden="true">%2$s</ul></div></div>',
		esc_attr( $size ),
		$list
	);
}

/**
 * Breadcrumb trail for the current view.
 */
function crumbs(): string {
	$items = array( array( __( 'Home', 'edshield' ), home_url( '/' ) ) );
	$post  = get_post();

	if ( is_home() && ! is_front_page() ) {
		$items[] = array( get_the_title( (int) get_option( 'page_for_posts' ) ), '' );
	} elseif ( is_search() ) {
		$items[] = array( __( 'Search', 'edshield' ), '' );
	} elseif ( is_404() ) {
		$items[] = array( __( 'Not found', 'edshield' ), '' );
	} elseif ( is_archive() ) {
		$items[] = array( wp_strip_all_tags( get_the_archive_title() ), '' );
	} elseif ( $post ) {
		if ( 'post' === $post->post_type && get_option( 'page_for_posts' ) ) {
			$items[] = array( get_the_title( (int) get_option( 'page_for_posts' ) ), get_permalink( (int) get_option( 'page_for_posts' ) ) );
		} elseif ( 'edst_project' === $post->post_type ) {
			$items[] = array( __( 'Work', 'edshield' ), resolve_url( 'page:work' ) );
		}

		foreach ( array_reverse( get_post_ancestors( $post ) ) as $ancestor ) {
			$items[] = array( get_the_title( $ancestor ), get_permalink( $ancestor ) );
		}

		$items[] = array( get_the_title( $post ), '' );
	}

	$html = '<nav class="edst-crumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'edshield' ) . '"><ol>';

	foreach ( $items as $i => $item ) {
		$last  = count( $items ) - 1 === $i;
		$html .= '<li>' . ( ! $last && $item[1] ? '<a href="' . esc_url( $item[1] ) . '">' . esc_html( $item[0] ) . '</a>' : '<span' . ( $last ? ' aria-current="page"' : '' ) . '>' . esc_html( $item[0] ) . '</span>' ) . '</li>';
	}

	return $html . '</ol></nav>';
}

/**
 * A text link with an arrow.
 *
 * @param string $label   Label.
 * @param mixed  $url     URL or token.
 * @param bool   $stretch Whether the link covers its card.
 */
function more_link( string $label, $url, bool $stretch = false ): string {
	$href = resolve_url( $url );

	if ( '' === trim( $label ) || '' === $href ) {
		return '';
	}

	return sprintf(
		'<a class="edst-more%1$s" href="%2$s"><span>%3$s</span>%4$s</a>',
		$stretch ? ' edst-stretch' : '',
		esc_url( $href ),
		esc_html( $label ),
		icon( 'arrow' )
	);
}

/* Sections ------------------------------------------------------------------ */

/**
 * Home hero.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_hero_home( array $s ): void {
	section_open( 'hero-home', $s );
	echo '<div class="edst-hero-bg" aria-hidden="true"></div><div class="edst-wrap edst-hh"><div class="edst-hh__copy">';

	$badges = s_items( $s, 'badges' );

	if ( $badges ) {
		echo '<ul class="edst-badges">';

		foreach ( $badges as $badge ) {
			if ( '' !== s_text( $badge, 'text' ) ) {
				echo '<li class="edst-badge"><span class="edst-badge__dot" aria-hidden="true"></span>' . esc_html( s_text( $badge, 'text' ) ) . '</li>';
			}
		}

		echo '</ul>';
	}

	if ( '' !== s_text( $s, 'heading' ) ) {
		echo '<h1 class="edst-display">' . inline( s_text( $s, 'heading' ) ) . '</h1>';
	}

	if ( '' !== s_text( $s, 'text' ) ) {
		echo '<p class="edst-lede">' . inline( s_text( $s, 'text' ) ) . '</p>';
	}

	$actions = button( s_text( $s, 'btn_label' ), $s['btn_url'], 'primary', 'edst-magnetic' ) . button( s_text( $s, 'btn2_label' ), $s['btn2_url'], 'ghost' );

	if ( '' !== $actions ) {
		echo '<div class="edst-actions">' . $actions . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts.
	}

	echo '</div>';

	$mode = s_text( $s, 'visual' );

	if ( 'image' === $mode ) {
		echo '<div class="edst-hh__visual edst-hh__visual--image">' . visual( '', s_media( $s, 'image' ), 'large', '(min-width: 64rem) 40vw, 100vw' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} elseif ( 'tiles' === $mode ) {
		$columns = array(
			array( 'web', 'ai', 'security', 'commerce', 'cloud' ),
			array( 'crm', 'software', 'industrial', 'consulting' ),
		);
		$index   = array_keys( services() );

		echo '<div class="edst-hh__visual"><div class="edst-tiles" aria-hidden="true">';

		foreach ( $columns as $c => $keys ) {
			$tiles = '';

			foreach ( $keys as $key ) {
				$service = service( $key );

				if ( ! $service ) {
					continue;
				}

				$num    = array_search( $key, $index, true );
				$tiles .= '<div class="edst-tile">' . art( service_art( $key ) ) . '<p class="edst-tile__cap"><span>' . esc_html( sprintf( '%02d', (int) $num + 1 ) ) . '</span>' . esc_html( $service['title'] ) . '</p></div>';
			}

			echo '<div class="edst-tiles__col' . ( 1 === $c ? ' edst-tiles__col--down' : '' ) . '"><div class="edst-tiles__track">' . $tiles . $tiles . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '</div></div>';
	}

	echo '</div>';

	$trust = lines( $s['trust_items'] ?? '' );

	if ( $trust ) {
		echo '<div class="edst-wrap edst-hh__trust">';

		if ( '' !== s_text( $s, 'trust_label' ) ) {
			echo '<p class="edst-eyebrow">' . esc_html( s_text( $s, 'trust_label' ) ) . '</p>';
		}

		echo marquee( $trust, 'small' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	section_close();
}

/**
 * Inner page hero.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_page_hero( array $s ): void {
	$position = s_text( $s, 'media_position' );
	$visual   = 'none' !== $position ? visual( s_text( $s, 'art' ), s_media( $s, 'image' ), 'large', '(min-width: 64rem) 50vw, 100vw' ) : '';
	$class    = '' !== $visual ? 'edst-ph--' . $position : '';

	section_open( 'page-hero', $s, $class );
	echo '<div class="edst-hero-bg" aria-hidden="true"></div><div class="edst-wrap edst-ph">';

	if ( s_on( $s, 'crumbs' ) ) {
		echo crumbs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '<div class="edst-ph__grid"><div class="edst-ph__copy">';

	if ( '' !== s_text( $s, 'badge' ) ) {
		echo '<p class="edst-badge"><span class="edst-badge__dot" aria-hidden="true"></span>' . esc_html( s_text( $s, 'badge' ) ) . '</p>';
	}

	echo '<h1 class="edst-h1">' . inline( s_text( $s, 'heading' ) ) . '</h1>';

	if ( '' !== s_text( $s, 'text' ) ) {
		echo '<p class="edst-lede">' . inline( s_text( $s, 'text' ) ) . '</p>';
	}

	echo '</div>';

	if ( 'aside' === $position && '' !== $visual ) {
		echo '<div class="edst-ph__aside"><div class="edst-frame">' . $visual . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '</div>';

	$btn = button( s_text( $s, 'btn_label' ), $s['btn_url'], 'light' );

	if ( '' !== $btn ) {
		echo '<div class="edst-ph__foot"><span class="edst-ph__rule" aria-hidden="true"></span>' . $btn . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	$trust = lines( $s['trust_items'] ?? '' );

	if ( $trust ) {
		echo '<div class="edst-ph__trust">' . ( '' !== s_text( $s, 'trust_label' ) ? '<p class="edst-eyebrow">' . esc_html( s_text( $s, 'trust_label' ) ) . '</p>' : '' ) . marquee( $trust, 'small' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '</div>';

	if ( 'below' === $position && '' !== $visual ) {
		echo '<div class="edst-wrap edst-ph__below"><div class="edst-frame edst-frame--wide">' . $visual . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	section_close();
}

/**
 * Card grid shared by the services grid, feature cards and industries.
 *
 * @param string                           $key   Section key.
 * @param array<string, mixed>             $s     Settings.
 * @param array<int, array<string, mixed>> $items Cards.
 * @param string                           $style media | feature | icon.
 * @param int                              $cols  Columns.
 */
function render_card_grid( string $key, array $s, array $items, string $style, int $cols ): void {
	section_open( $key, $s );
	echo '<div class="edst-wrap">' . section_head( $s ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '<div class="edst-cards edst-cards--' . esc_attr( $style ) . ' edst-cols-' . esc_attr( (string) $cols ) . '">';

	foreach ( $items as $i => $item ) {
		$title = s_text( $item, 'title' );
		$url   = resolve_url( $item['url'] ?? '' );
		$label = s_text( $item, 'link_label' );
		$art   = s_text( $item, 'art' );

		echo '<article class="edst-card edst-card--' . esc_attr( $style ) . ( '' !== $url ? ' edst-card--link' : '' ) . ' edst-rv">';

		if ( 'icon' === $style ) {
			$name = 0 === strpos( $art, 'icon:' ) ? substr( $art, 5 ) : 'spark';
			$img  = s_media( $item, 'image' );

			echo '<div class="edst-card__icon">' . ( $img['id'] || $img['url'] ? visual( '', $img, 'thumbnail' ) : icon( $name ) ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			echo '<div class="edst-card__media">' . visual( $art, s_media( $item, 'image' ), 'edst-card', '(min-width: 64rem) 30vw, 90vw' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '<div class="edst-card__body">';

		if ( 'media' === $style ) {
			echo '<span class="edst-card__num">' . esc_html( sprintf( '%02d', $i + 1 ) ) . '</span>';
		}

		echo '<h3 class="edst-card__title">';
		echo '' !== $url && '' === $label ? '<a class="edst-stretch" href="' . esc_url( $url ) . '">' . esc_html( $title ) . '</a>' : esc_html( $title );
		echo '</h3>';

		if ( '' !== s_text( $item, 'text' ) ) {
			echo '<p class="edst-card__text">' . inline( s_text( $item, 'text' ) ) . '</p>';
		}

		echo more_link( $label, $url, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div></article>';
	}

	echo '</div></div>';
	section_close();
}

/**
 * Services grid.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_services_grid( array $s ): void {
	$items = s_items( $s, 'items' );

	if ( 'services' === s_text( $s, 'source' ) ) {
		$items = array();

		foreach ( services() as $key => $service ) {
			$items[] = array(
				'title'      => $service['title'],
				'text'       => $service['summary'],
				'art'        => 'icon' === s_text( $s, 'style' ) ? 'icon:' . $service['icon'] : service_art( $key ),
				'image'      => array(
					'id'  => 0,
					'url' => '',
				),
				'link_label' => __( 'Explore service', 'edshield' ),
				'url'        => 'service:' . $key,
			);
		}
	}

	render_card_grid( 'services-grid', $s, $items, 'icon' === s_text( $s, 'style' ) ? 'icon' : 'media', max( 2, min( 4, (int) s_text( $s, 'columns' ) ) ) );
}

/**
 * Feature cards.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_features( array $s ): void {
	render_card_grid( 'features', $s, s_items( $s, 'items' ), 'feature', 3 );
}

/**
 * Industries grid.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_industries( array $s ): void {
	render_card_grid( 'industries', $s, s_items( $s, 'items' ), 'icon', 3 );
}

/**
 * Services list with alternating rows.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_services_list( array $s ): void {
	$rows = s_items( $s, 'items' );

	if ( 'services' === s_text( $s, 'source' ) || ! $rows ) {
		$rows = array();

		foreach ( services() as $key => $service ) {
			$rows[] = array(
				'title'      => $service['title'],
				'text'       => $service['summary'] . ' ' . $service['tagline_text'],
				'list'       => implode( "\n", $service['items'] ),
				'art'        => service_art( $key ),
				'image'      => array(
					'id'  => 0,
					'url' => '',
				),
				'link_label' => sprintf( /* translators: %s: service name. */ __( 'Explore %s', 'edshield' ), $service['title'] ),
				'url'        => 'service:' . $key,
			);
		}
	}

	section_open( 'services-list', $s );
	echo '<div class="edst-wrap">' . section_head( $s ) . '<div class="edst-svc-list">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	foreach ( $rows as $i => $row ) {
		echo '<article class="edst-svc-row' . ( $i % 2 ? ' edst-svc-row--flip' : '' ) . ' edst-rv"><div class="edst-svc-row__body">';
		echo '<span class="edst-num">' . esc_html( sprintf( '%02d', $i + 1 ) ) . '</span>';
		echo '<h3 class="edst-svc-row__title">' . esc_html( s_text( $row, 'title' ) ) . '</h3>';
		echo '<p class="edst-svc-row__text">' . inline( s_text( $row, 'text' ) ) . '</p>';

		$list = lines( $row['list'] ?? '' );

		if ( $list ) {
			echo '<ul class="edst-checks edst-checks--cols">';

			foreach ( $list as $li ) {
				echo '<li>' . icon( 'arrow' ) . '<span>' . esc_html( $li ) . '</span></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			echo '</ul>';
		}

		echo more_link( s_text( $row, 'link_label' ), $row['url'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div><div class="edst-svc-row__media">' . visual( s_text( $row, 'art' ), s_media( $row, 'image' ), 'large', '(min-width: 64rem) 40vw, 90vw' ) . '</div></article>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '</div></div>';
	section_close();
}

/**
 * Card data for one project, or placeholders when no projects exist yet.
 *
 * @param int $count Number of cards.
 * @return array<int, array<string, string>>
 */
function project_cards( int $count ): array {
	$cards = array();

	if ( post_type_exists( 'edst_project' ) ) {
		$posts = get_posts(
			array(
				'post_type'        => 'edst_project',
				'numberposts'      => max( 1, $count ),
				'post_status'      => 'publish',
				'suppress_filters' => false,
			)
		);

		foreach ( $posts as $i => $post ) {
			$meta    = project_meta( $post->ID );
			$cards[] = array(
				'title'    => get_the_title( $post ),
				'url'      => (string) get_permalink( $post ),
				'client'   => $meta['client'],
				'industry' => $meta['industry'],
				'tags'     => $meta['services'],
				'metric'   => $meta['metric_value'],
				'mlabel'   => $meta['metric_label'],
				'text'     => summary( $post->ID, 22 ),
				'image'    => (string) get_post_thumbnail_id( $post ),
				'art'      => '' !== $meta['art'] ? $meta['art'] : array( 'web', 'crm', 'security', 'software', 'ai', 'cloud' )[ $i % 6 ],
			);
		}
	}

	if ( ! $cards ) {
		foreach ( array( 'web', 'crm', 'security' ) as $i => $art ) {
			$cards[] = array(
				'title'    => __( 'Case study placeholder', 'edshield' ),
				'url'      => '',
				'client'   => __( 'Client name', 'edshield' ),
				'industry' => __( 'Industry', 'edshield' ),
				'tags'     => __( 'Service, Service', 'edshield' ),
				'metric'   => '—',
				'mlabel'   => __( 'Add a measured result', 'edshield' ),
				'text'     => __( 'Publish a project under Projects and it replaces this placeholder.', 'edshield' ),
				'image'    => '',
				'art'      => $art,
			);
		}
	}

	return $cards;
}

/**
 * One project card.
 *
 * @param array<string, string> $c Card data.
 */
function project_card( array $c ): string {
	$media = '' !== $c['image'] ? wp_get_attachment_image( (int) $c['image'], 'edst-card', false, array( 'class' => 'edst-img', 'loading' => 'lazy', 'decoding' => 'async', 'sizes' => '(min-width: 64rem) 30vw, 85vw' ) ) : '';
	$media = $media ? $media : art( $c['art'] );
	$tags  = implode( ' · ', array_filter( array_map( 'trim', explode( ',', $c['tags'] ) ) ) );

	return '<article class="edst-case' . ( '' !== $c['url'] ? ' edst-case--link' : '' ) . '" data-cat="' . esc_attr( sanitize_title( $c['industry'] ) ) . '">'
		. '<div class="edst-case__media">' . $media . '</div><div class="edst-case__shade" aria-hidden="true"></div>'
		. '<p class="edst-case__client">' . esc_html( $c['client'] ) . '</p>'
		. '<div class="edst-case__body">'
		. '<p class="edst-case__metric"><strong>' . esc_html( $c['metric'] ) . '</strong><span>' . esc_html( $c['mlabel'] ) . '</span></p>'
		. '<h3 class="edst-case__title">' . esc_html( $c['title'] ) . '</h3>'
		. '<p class="edst-case__text">' . esc_html( $c['text'] ) . '</p>'
		. '<div class="edst-case__foot"><span class="edst-case__tags">' . esc_html( $tags ) . '</span>' . ( '' !== $c['url'] ? more_link( __( 'View case study', 'edshield' ), $c['url'], true ) : '' ) . '</div>'
		. '</div></article>';
}

/**
 * Rail controls (progress and arrows).
 */
function rail_controls(): string {
	return '<div class="edst-rail__controls"><div class="edst-rail__bar" aria-hidden="true"><span class="edst-rail__fill"></span></div><div class="edst-rail__buttons"><button type="button" class="edst-rail__btn" data-edst-prev aria-label="' . esc_attr__( 'Previous', 'edshield' ) . '">' . icon( 'arrow', 'edst-icon--flip' ) . '</button><button type="button" class="edst-rail__btn" data-edst-next aria-label="' . esc_attr__( 'Next', 'edshield' ) . '">' . icon( 'arrow' ) . '</button></div></div>';
}

/**
 * Work: slider or filterable grid of projects.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_work( array $s ): void {
	$grid  = 'grid' === s_text( $s, 'layout' );
	$cards = project_cards( max( 1, (int) ( $s['count'] ?? 6 ) ) );

	section_open( 'work', $s );
	echo '<div class="edst-wrap">' . section_head( $s ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	if ( $grid ) {
		$cats = array();

		foreach ( $cards as $card ) {
			if ( '' !== trim( $card['industry'] ) ) {
				$cats[ sanitize_title( $card['industry'] ) ] = $card['industry'];
			}
		}

		echo '<div class="edst-work" data-edst-filter>';

		if ( count( $cats ) > 1 ) {
			echo '<div class="edst-filters" role="group" aria-label="' . esc_attr__( 'Filter projects', 'edshield' ) . '"><button type="button" class="edst-filter is-active" data-filter="*" aria-pressed="true">' . esc_html__( 'All', 'edshield' ) . '</button>';

			foreach ( $cats as $slug => $name ) {
				echo '<button type="button" class="edst-filter" data-filter="' . esc_attr( $slug ) . '" aria-pressed="false">' . esc_html( $name ) . '</button>';
			}

			echo '</div>';
		}

		echo '<div class="edst-work__grid">';

		foreach ( $cards as $i => $card ) {
			echo '<div class="edst-work__item edst-rv' . ( $i >= 6 ? ' edst-is-more' : '' ) . '" data-cats="' . esc_attr( sanitize_title( $card['industry'] ) ) . '">' . project_card( $card ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '</div>';

		if ( count( $cards ) > 6 ) {
			echo '<div class="edst-work__more"><button type="button" class="edst-btn edst-btn--primary" data-edst-more><span>' . esc_html__( 'Load more', 'edshield' ) . '</span></button></div>';
		}

		echo '</div></div>';
	} else {
		echo '</div><div class="edst-rail" data-edst-rail><ul class="edst-rail__track" tabindex="0" aria-label="' . esc_attr__( 'Case studies', 'edshield' ) . '">';

		foreach ( $cards as $card ) {
			echo '<li class="edst-rail__item edst-rail__item--case">' . project_card( $card ) . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '</ul><div class="edst-wrap">' . rail_controls() . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	section_close();
}

/**
 * Numbers.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_stats( array $s ): void {
	$items = s_items( $s, 'items' );

	section_open( 'stats', $s );
	echo '<div class="edst-wrap">' . section_head( $s ) . '<div class="edst-stats edst-cols-' . esc_attr( (string) max( 2, min( 4, count( $items ) ) ) ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	foreach ( $items as $item ) {
		echo '<div class="edst-stat edst-rv"><p class="edst-stat__value" data-edst-count>' . esc_html( s_text( $item, 'value' ) ) . '</p><p class="edst-stat__label">' . esc_html( s_text( $item, 'label' ) ) . '</p>';

		if ( '' !== s_text( $item, 'text' ) ) {
			echo '<p class="edst-stat__text">' . esc_html( s_text( $item, 'text' ) ) . '</p>';
		}

		echo '</div>';
	}

	echo '</div></div>';
	section_close();
}

/**
 * Testimonials rail.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_testimonials( array $s ): void {
	section_open( 'testimonials', $s );
	echo '<div class="edst-wrap">' . section_head( $s ) . '</div><div class="edst-rail" data-edst-rail><ul class="edst-rail__track" tabindex="0" aria-label="' . esc_attr__( 'Testimonials', 'edshield' ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	foreach ( s_items( $s, 'items' ) as $item ) {
		$name   = s_text( $item, 'name' );
		$photo  = s_media( $item, 'image' );
		$avatar = $photo['id'] ? wp_get_attachment_image( $photo['id'], 'thumbnail', false, array( 'class' => 'edst-quote__photo', 'loading' => 'lazy' ) ) : '';

		if ( ! $avatar ) {
			$initials = '';

			foreach ( array_slice( preg_split( '/\s+/', $name ), 0, 2 ) as $part ) {
				$initials .= mb_strtoupper( mb_substr( $part, 0, 1 ) );
			}

			$avatar = '<span class="edst-quote__initials" aria-hidden="true">' . esc_html( $initials ) . '</span>';
		}

		echo '<li class="edst-rail__item edst-rail__item--quote"><figure class="edst-quote">' . icon( 'quote', 'edst-quote__mark' ) . '<blockquote><p>' . esc_html( s_text( $item, 'quote' ) ) . '</p></blockquote><figcaption>' . $avatar . '<span><strong>' . esc_html( $name ) . '</strong><span>' . esc_html( s_text( $item, 'role' ) ) . '</span></span></figcaption></figure></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '</ul><div class="edst-wrap">' . rail_controls() . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	section_close();
}

/**
 * Process overview steps.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_steps( array $s ): void {
	$items = s_items( $s, 'items' );

	section_open( 'steps', $s );
	echo '<div class="edst-wrap">' . section_head( $s ) . '<ol class="edst-steps edst-cols-' . esc_attr( (string) max( 2, min( 4, count( $items ) ) ) ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	foreach ( $items as $i => $item ) {
		/* translators: %s: step number. */
		echo '<li class="edst-step edst-rv"><p class="edst-step__k">' . esc_html( sprintf( __( 'Step %s', 'edshield' ), sprintf( '%02d', $i + 1 ) ) ) . '</p><div class="edst-step__media">' . art( s_text( $item, 'art' ) ) . '</div><h3 class="edst-step__title">' . esc_html( s_text( $item, 'title' ) ) . '</h3><p class="edst-step__text">' . inline( s_text( $item, 'text' ) ) . '</p></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '</ol></div>';
	section_close();
}

/**
 * Numbered rows.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_rows( array $s ): void {
	$split = 'split' === s_text( $s, 'layout' );

	section_open( 'rows', $s, $split ? 'edst-rows-split' : '' );
	echo '<div class="edst-wrap">';

	if ( $split ) {
		echo '<div class="edst-split-list"><div class="edst-split-list__intro">' . section_head( $s, 'stack' ) . '</div><ol class="edst-split-list__items">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		echo section_head( $s ) . '<ol class="edst-rows">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	foreach ( s_items( $s, 'items' ) as $i => $item ) {
		$tags = array_filter( array_map( 'trim', explode( ',', s_text( $item, 'tags' ) ) ) );

		echo '<li class="edst-row edst-rv"><span class="edst-row__num">' . esc_html( sprintf( '%02d', $i + 1 ) ) . '</span><h3 class="edst-row__title">' . esc_html( s_text( $item, 'title' ) ) . '</h3><div class="edst-row__body"><p>' . inline( s_text( $item, 'text' ) ) . '</p>';

		if ( $tags ) {
			echo '<ul class="edst-tags">';

			foreach ( $tags as $tag ) {
				echo '<li>' . esc_html( $tag ) . '</li>';
			}

			echo '</ul>';
		}

		echo '</div></li>';
	}

	echo $split ? '</ol></div>' : '</ol>';
	echo '</div>';
	section_close();
}

/**
 * Post card (also used by the blog templates).
 *
 * @param int $post_id Post ID.
 */
function post_card( int $post_id ): string {
	$cats  = get_the_category( $post_id );
	$cat   = $cats ? $cats[0]->name : '';
	$thumb = get_the_post_thumbnail( $post_id, 'edst-card', array( 'class' => 'edst-img', 'loading' => 'lazy', 'decoding' => 'async', 'sizes' => '(min-width: 64rem) 30vw, 90vw' ) );

	return '<article class="edst-post-card edst-rv"><div class="edst-post-card__media">' . ( $thumb ? $thumb : art( array( 'web', 'ai', 'security', 'crm', 'cloud', 'software' )[ $post_id % 6 ] ) ) . '</div><div class="edst-post-card__body"><p class="edst-post-card__meta">' . ( '' !== $cat ? '<span>' . esc_html( $cat ) . '</span>' : '' ) . '<time datetime="' . esc_attr( get_the_date( DATE_W3C, $post_id ) ) . '">' . esc_html( get_the_date( 'M Y', $post_id ) ) . '</time></p><h3 class="edst-post-card__title"><a class="edst-stretch" href="' . esc_url( get_permalink( $post_id ) ) . '">' . esc_html( get_the_title( $post_id ) ) . '</a></h3><p class="edst-post-card__text">' . esc_html( summary( $post_id, 20 ) ) . '</p></div></article>';
}

/**
 * Latest articles.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_posts( array $s ): void {
	$args = array(
		'post_type'           => 'post',
		'posts_per_page'      => max( 1, min( 12, (int) ( $s['count'] ?? 3 ) ) ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'post_status'         => 'publish',
	);

	if ( '' !== s_text( $s, 'category' ) ) {
		$args['category_name'] = sanitize_title( s_text( $s, 'category' ) );
	}

	$ids = get_posts( array_merge( $args, array( 'fields' => 'ids' ) ) );

	if ( ! $ids ) {
		if ( elementor_editing() ) {
			echo '<p class="edst-admin-note">' . esc_html__( 'Latest articles: this section appears once posts are published.', 'edshield' ) . '</p>';
		}

		return;
	}

	section_open( 'posts', $s );
	echo '<div class="edst-wrap">' . section_head( $s ) . '<div class="edst-posts edst-cols-3">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	foreach ( $ids as $id ) {
		echo post_card( (int) $id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '</div></div>';
	section_close();
}

/**
 * Accordion items.
 *
 * @param array<int, array{0:string,1:string}> $pairs Question and answer pairs.
 */
function qa_list( array $pairs ): string {
	$name = wp_unique_id( 'edst-faq-' );
	$html = '';

	foreach ( $pairs as $pair ) {
		if ( '' === trim( $pair[0] ) ) {
			continue;
		}

		$html .= '<details class="edst-qa" name="' . esc_attr( $name ) . '"><summary><span>' . esc_html( $pair[0] ) . '</span><span class="edst-qa__icon" aria-hidden="true"></span></summary><div class="edst-qa__a">' . wpautop( esc_html( $pair[1] ) ) . '</div></details>';
	}

	return $html;
}

/**
 * FAQ.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_faq( array $s ): void {
	$pairs = array();

	foreach ( s_items( $s, 'items' ) as $item ) {
		$pairs[] = array( s_text( $item, 'q' ), s_text( $item, 'a' ) );
	}

	section_open( 'faq', $s );
	echo '<div class="edst-wrap edst-faq"><div class="edst-faq__intro">' . section_head( $s, 'stack' ) . '</div><div class="edst-faq__list edst-rv">' . qa_list( $pairs ) . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	section_close();
}

/**
 * FAQ with categories.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_faq_groups( array $s ): void {
	$groups = array();

	foreach ( s_items( $s, 'groups' ) as $group ) {
		$pairs = array();

		foreach ( preg_split( '/\R\s*\R/', trim( (string) ( $group['items'] ?? '' ) ) ) as $block ) {
			$block_lines = lines( $block );

			if ( $block_lines ) {
				$pairs[] = array( array_shift( $block_lines ), implode( "\n", $block_lines ) );
			}
		}

		if ( $pairs ) {
			$groups[] = array( s_text( $group, 'title' ), $pairs );
		}
	}

	$uid = wp_unique_id( 'edst-faqg-' );

	section_open( 'faq-groups', $s );
	echo '<div class="edst-wrap edst-faqg" data-edst-faqnav><nav class="edst-faqg__nav" aria-label="' . esc_attr__( 'Question categories', 'edshield' ) . '"><ol>';

	foreach ( $groups as $i => $group ) {
		echo '<li><a href="#' . esc_attr( $uid . '-' . ( $i + 1 ) ) . '"><span>' . esc_html( sprintf( '%02d', $i + 1 ) ) . '</span>' . esc_html( $group[0] ) . '</a></li>';
	}

	echo '</ol></nav><div class="edst-faqg__groups">';

	foreach ( $groups as $i => $group ) {
		echo '<section class="edst-faqg__group" id="' . esc_attr( $uid . '-' . ( $i + 1 ) ) . '"><h2 class="edst-faqg__title"><span>' . esc_html( sprintf( '%02d', $i + 1 ) ) . '</span>' . esc_html( $group[0] ) . '</h2>' . qa_list( $group[1] ) . '</section>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '</div></div>';
	section_close();
}

/**
 * Call to action band.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_cta( array $s ): void {
	section_open( 'cta', $s );
	echo '<div class="edst-wrap"><div class="edst-cta edst-tone-ink edst-rv"><div class="edst-cta__lines" aria-hidden="true"></div><div class="edst-cta__inner">';

	if ( '' !== s_text( $s, 'label' ) ) {
		echo '<p class="edst-eyebrow">' . esc_html( s_text( $s, 'label' ) ) . '</p>';
	}

	echo '<h2 class="edst-h2">' . inline( s_text( $s, 'heading' ) ) . '</h2>';

	if ( '' !== s_text( $s, 'text' ) ) {
		echo '<p class="edst-cta__text">' . inline( s_text( $s, 'text' ) ) . '</p>';
	}

	$actions = button( s_text( $s, 'btn_label' ), $s['btn_url'], 'primary', 'edst-magnetic' ) . button( s_text( $s, 'btn2_label' ), $s['btn2_url'], 'ghost' );

	if ( '' !== $actions ) {
		echo '<div class="edst-actions">' . $actions . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	if ( '' !== s_text( $s, 'status' ) ) {
		echo '<p class="edst-cta__status"><span class="edst-dot" aria-hidden="true"></span>' . esc_html( s_text( $s, 'status' ) ) . '</p>';
	}

	echo '</div></div></div>';
	section_close();
}

/**
 * Statement with a visual.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_split( array $s ): void {
	section_open( 'split', $s );
	echo '<div class="edst-wrap"><div class="edst-split' . ( s_on( $s, 'flip' ) ? ' edst-split--flip' : '' ) . '"><div class="edst-split__copy edst-rv">';

	if ( '' !== s_text( $s, 'label' ) ) {
		echo '<p class="edst-eyebrow">' . esc_html( s_text( $s, 'label' ) ) . '</p>';
	}

	if ( '' !== s_text( $s, 'heading' ) ) {
		echo '<h2 class="edst-h2">' . inline( s_text( $s, 'heading' ) ) . '</h2>';
	}

	if ( '' !== s_text( $s, 'text' ) ) {
		echo '<div class="edst-rich">' . wp_kses_post( wpautop( s_text( $s, 'text' ) ) ) . '</div>';
	}

	$chips = lines( $s['chips'] ?? '' );

	if ( $chips ) {
		echo '<ul class="edst-chips">';

		foreach ( $chips as $chip ) {
			echo '<li>' . esc_html( $chip ) . '</li>';
		}

		echo '</ul>';
	}

	echo more_link( s_text( $s, 'btn_label' ), $s['btn_url'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '</div><div class="edst-split__media edst-rv"><div class="edst-frame">' . visual( s_text( $s, 'art' ), s_media( $s, 'image' ), 'large', '(min-width: 64rem) 45vw, 90vw' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	if ( '' !== s_text( $s, 'stat_value' ) ) {
		echo '<div class="edst-split__stat"><strong>' . esc_html( s_text( $s, 'stat_value' ) ) . '</strong><span>' . esc_html( s_text( $s, 'stat_label' ) ) . '</span></div>';
	}

	echo '</div></div></div>';
	section_close();
}

/**
 * What is included.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_included( array $s ): void {
	section_open( 'included', $s );
	echo '<div class="edst-wrap">' . section_head( $s ) . '<div class="edst-incl">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	foreach ( s_items( $s, 'items' ) as $item ) {
		echo '<article class="edst-incl__item edst-rv"><span class="edst-incl__icon">' . icon( s_text( $item, 'icon' ) ? s_text( $item, 'icon' ) : 'check' ) . '</span><div><h3 class="edst-incl__title">' . esc_html( s_text( $item, 'title' ) ) . '</h3><p class="edst-incl__text">' . inline( s_text( $item, 'text' ) ) . '</p></div></article>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '</div></div>';
	section_close();
}

/**
 * Engagement models.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_plans( array $s ): void {
	section_open( 'plans', $s );
	echo '<div class="edst-wrap">' . section_head( $s ) . '<div class="edst-plans">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	foreach ( s_items( $s, 'items' ) as $item ) {
		$featured = s_on( $item, 'featured' );

		echo '<article class="edst-plan' . ( $featured ? ' edst-plan--featured edst-tone-ink' : '' ) . ' edst-rv"><h3 class="edst-plan__name">' . esc_html( s_text( $item, 'name' ) ) . '</h3><p class="edst-plan__price">' . esc_html( s_text( $item, 'price' ) ) . '</p><p class="edst-plan__summary">' . inline( s_text( $item, 'summary' ) ) . '</p>';
		echo button( s_text( $item, 'btn_label' ), $item['btn_url'] ?? '', $featured ? 'primary' : 'outline', 'edst-btn--block' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$features = lines( $item['features'] ?? '' );

		if ( $features ) {
			echo '<p class="edst-plan__inc">' . esc_html__( 'Includes', 'edshield' ) . '</p><ul class="edst-checks">';

			foreach ( $features as $feature ) {
				echo '<li>' . icon( 'check' ) . '<span>' . esc_html( $feature ) . '</span></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			echo '</ul>';
		}

		echo '</article>';
	}

	echo '</div>';

	if ( '' !== s_text( $s, 'brief_title' ) ) {
		echo '<div class="edst-brief edst-rv"><div><p class="edst-eyebrow">' . esc_html__( 'Custom brief', 'edshield' ) . '</p><h3 class="edst-brief__title">' . esc_html( s_text( $s, 'brief_title' ) ) . '</h3><p>' . inline( s_text( $s, 'brief_text' ) ) . '</p></div>' . button( s_text( $s, 'brief_btn_label' ), $s['brief_btn_url'] ?? '', 'primary' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '</div>';
	section_close();
}

/**
 * Contact form and details.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_contact( array $s ): void {
	$details = contact_details();
	$rows    = array(
		array( 'mail', __( 'Email', 'edshield' ), $details['email'], '' !== $details['email'] ? 'mailto:' . antispambot( $details['email'], 1 ) : '' ),
		array( 'phone', __( 'Phone', 'edshield' ), $details['phone'], '' !== $details['phone'] ? 'tel:' . preg_replace( '/[^0-9+]/', '', $details['phone'] ) : '' ),
		array( 'whatsapp', __( 'WhatsApp', 'edshield' ), $details['whatsapp'], '' !== $details['whatsapp'] ? 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $details['whatsapp'] ) : '' ),
		array( 'pin', __( 'Address', 'edshield' ), $details['address'], '' ),
		array( 'clock', __( 'Hours', 'edshield' ), $details['hours'], '' ),
	);

	section_open( 'contact', $s );
	echo '<div class="edst-wrap edst-contact"><div class="edst-contact__info edst-rv">';

	if ( '' !== s_text( $s, 'label' ) ) {
		echo '<p class="edst-eyebrow">' . esc_html( s_text( $s, 'label' ) ) . '</p>';
	}

	echo '<h2 class="edst-h2">' . inline( s_text( $s, 'heading' ) ) . '</h2><p class="edst-contact__text">' . inline( s_text( $s, 'text' ) ) . '</p><ul class="edst-contact__details">';

	$shown = 0;

	foreach ( $rows as $row ) {
		if ( '' === $row[2] ) {
			continue;
		}

		++$shown;
		$value = 'mail' === $row[0] ? antispambot( $row[2] ) : $row[2];

		echo '<li><span class="edst-contact__icon">' . icon( $row[0] ) . '</span><span><span class="edst-contact__k">' . esc_html( $row[1] ) . '</span>' . ( '' !== $row[3] ? '<a href="' . esc_attr( $row[3] ) . '"' . ( 'whatsapp' === $row[0] ? ' target="_blank" rel="noopener"' : '' ) . '>' . esc_html( $value ) . '</a>' : '<span>' . nl2br( esc_html( $value ) ) . '</span>' ) . '</span></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo '</ul>';

	if ( ! $shown && current_user_can( 'edit_theme_options' ) ) {
		echo '<p class="edst-admin-note">' . esc_html__( 'Only administrators see this note: add your email, phone and address in Appearance › Customize › ED Shield › Contact details.', 'edshield' ) . '</p>';
	}

	$steps = lines( $s['steps'] ?? '' );

	if ( $steps ) {
		echo '<div class="edst-next"><p class="edst-eyebrow">' . esc_html( s_text( $s, 'steps_title' ) ) . '</p><ol>';

		foreach ( $steps as $step ) {
			echo '<li>' . esc_html( $step ) . '</li>';
		}

		echo '</ol></div>';
	}

	echo '</div><div class="edst-contact__form edst-rv"><div class="edst-form-card">';

	if ( '' !== s_text( $s, 'form_title' ) ) {
		echo '<h3 class="edst-form-card__title">' . esc_html( s_text( $s, 'form_title' ) ) . '</h3>';
	}

	echo contact_form( s_text( $s, 'shortcode' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '</div></div></div>';
	section_close();
}

/**
 * Case study overview.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_case_overview( array $s ): void {
	$meta = array(
		__( 'Client', 'edshield' )   => s_text( $s, 'client' ),
		__( 'Industry', 'edshield' ) => s_text( $s, 'industry' ),
		__( 'Services', 'edshield' ) => s_text( $s, 'services' ),
		__( 'Year', 'edshield' )     => s_text( $s, 'year' ),
	);

	section_open( 'case-overview', $s );
	echo '<div class="edst-wrap edst-case-ov"><aside class="edst-case-ov__meta"><dl>';

	foreach ( $meta as $k => $v ) {
		if ( '' !== $v ) {
			echo '<div><dt>' . esc_html( $k ) . '</dt><dd>' . esc_html( $v ) . '</dd></div>';
		}
	}

	echo '</dl>' . button( __( 'Visit website', 'edshield' ), $s['website'] ?? '', 'outline' ) . '</aside><div class="edst-case-ov__body">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	foreach ( array( 'challenge' => __( 'The challenge', 'edshield' ), 'goal' => __( 'The goal', 'edshield' ) ) as $k => $title ) {
		if ( '' !== s_text( $s, $k ) ) {
			echo '<div class="edst-case-ov__block edst-rv"><h2 class="edst-h3">' . esc_html( $title ) . '</h2>' . wp_kses_post( wpautop( s_text( $s, $k ) ) ) . '</div>';
		}
	}

	$objectives = lines( $s['objectives'] ?? '' );

	if ( $objectives ) {
		echo '<div class="edst-case-ov__block edst-rv"><h2 class="edst-h3">' . esc_html__( 'Objectives', 'edshield' ) . '</h2><ol class="edst-objectives">';

		foreach ( $objectives as $i => $objective ) {
			echo '<li><span>' . esc_html( sprintf( '%02d', $i + 1 ) ) . '</span>' . esc_html( $objective ) . '</li>';
		}

		echo '</ol></div>';
	}

	echo '</div></div>';
	section_close();
}

/**
 * Gallery.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_gallery( array $s ): void {
	section_open( 'gallery', $s );
	echo '<div class="edst-wrap"><div class="edst-gallery edst-gallery--' . esc_attr( s_text( $s, 'layout' ) ) . '">';

	foreach ( s_items( $s, 'items' ) as $item ) {
		echo '<figure class="edst-gallery__item edst-rv"><div class="edst-frame">' . visual( s_text( $item, 'art' ), s_media( $item, 'image' ), 'edst-wide', '(min-width: 64rem) 60vw, 100vw' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( '' !== s_text( $item, 'caption' ) ) {
			echo '<figcaption>' . esc_html( s_text( $item, 'caption' ) ) . '</figcaption>';
		}

		echo '</figure>';
	}

	echo '</div></div>';
	section_close();
}

/**
 * Moving strip.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_marquee( array $s ): void {
	section_open( 'marquee', $s );
	echo marquee( lines( $s['items'] ?? '' ), 'small' === s_text( $s, 'size' ) ? 'small' : 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	section_close();
}

/**
 * Text block.
 *
 * @param array<string, mixed> $s Settings.
 */
function render_rich_text( array $s ): void {
	section_open( 'rich-text', $s );
	echo '<div class="edst-wrap"><div class="edst-textblock edst-textblock--' . esc_attr( 'wide' === s_text( $s, 'width' ) ? 'wide' : 'narrow' ) . '">';

	if ( '' !== s_text( $s, 'label' ) ) {
		echo '<p class="edst-eyebrow">' . esc_html( s_text( $s, 'label' ) ) . '</p>';
	}

	if ( '' !== s_text( $s, 'heading' ) ) {
		echo '<h2 class="edst-h2">' . inline( s_text( $s, 'heading' ) ) . '</h2>';
	}

	echo '<div class="edst-prose">' . wp_kses_post( wpautop( s_text( $s, 'content' ) ) ) . '</div></div></div>';
	section_close();
}
