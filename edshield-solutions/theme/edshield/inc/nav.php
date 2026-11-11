<?php
/**
 * Navigation: mega menu, dropdowns, mobile menu and footer link lists.
 *
 * Menus are managed in Appearance › Menus. On the "Primary" menu:
 *  - a top-level item with children opens a dropdown panel;
 *  - add the CSS class "edst-mega" to make it a wide mega panel with icons;
 *  - give a child the class "edst-view-all" to show it as the panel's footer link;
 *  - a child's "Description" is shown under its title (enable it in Screen Options);
 *  - a child's class "edst-icon-<name>" sets its icon (for example edst-icon-shield).
 * Without an assigned menu, a complete default navigation is shown.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Items of a menu location as a two-level tree.
 *
 * @param string $location Menu location.
 * @return array<int, array<string, mixed>>
 */
function menu_tree( string $location ): array {
	$locations = get_nav_menu_locations();

	if ( empty( $locations[ $location ] ) ) {
		return array();
	}

	$items = wp_get_nav_menu_items( (int) $locations[ $location ] );

	if ( ! $items ) {
		return array();
	}

	_wp_menu_item_classes_by_context( $items );

	// Case studies belong to the Work page and articles to the posts page.
	$section_page = 0;

	if ( is_singular( 'edst_project' ) ) {
		$section_page = layout_page_id( 'work' );
	} elseif ( is_singular( 'post' ) || is_category() || is_tag() || is_author() || is_date() ) {
		$section_page = (int) get_option( 'page_for_posts' );
	}

	$top      = array();
	$children = array();

	foreach ( $items as $item ) {
		$classes = array_values( array_filter( (array) $item->classes ) );
		$icon    = '';

		foreach ( $classes as $class ) {
			if ( 0 === strpos( $class, 'edst-icon-' ) ) {
				$icon = substr( $class, 10 );
			}
		}

		if ( '' === $icon && 'page' === $item->object ) {
			$layout = (string) get_post_meta( (int) $item->object_id, '_edst_layout', true );

			if ( 0 === strpos( $layout, 'service-' ) ) {
				$icon = service( substr( $layout, 8 ) )['icon'] ?? '';
			}
		}

		$node   = array(
			'title'       => (string) $item->title,
			'url'         => (string) $item->url,
			'description' => trim( (string) $item->description ),
			'target'      => (string) $item->target,
			'current'     => (bool) array_intersect( array( 'current-menu-item', 'current-menu-ancestor', 'current-menu-parent', 'current-page-ancestor', 'current_page_parent' ), $classes ) || ( $section_page && 'page' === $item->object && (int) $item->object_id === $section_page ),
			'classes'     => $classes,
			'icon'        => $icon,
			'children'    => array(),
		);
		$parent = (int) $item->menu_item_parent;

		if ( 0 === $parent ) {
			$top[ (int) $item->ID ] = $node;
		} else {
			$children[ $parent ][] = $node;
		}
	}

	foreach ( $top as $id => $node ) {
		$top[ $id ]['children'] = $children[ $id ] ?? array();
	}

	return array_values( $top );
}

/**
 * A menu node.
 *
 * @param string   $title       Title.
 * @param string   $url         URL or token.
 * @param string   $description Description.
 * @param string[] $classes     Classes.
 * @param string   $icon        Icon.
 * @param array    $children    Children.
 * @return array<string, mixed>
 */
function nav_node( string $title, string $url, string $description = '', array $classes = array(), string $icon = '', array $children = array() ): array {
	$href = resolve_url( $url );

	return array(
		'title'       => $title,
		'url'         => $href,
		'description' => $description,
		'target'      => '',
		'current'     => is_page() && untrailingslashit( (string) get_permalink() ) === untrailingslashit( $href ),
		'classes'     => $classes,
		'icon'        => $icon,
		'children'    => $children,
	);
}

/**
 * Primary navigation: the assigned menu or the default one.
 *
 * @return array<int, array<string, mixed>>
 */
function primary_items(): array {
	$tree = menu_tree( 'primary' );

	if ( $tree ) {
		return $tree;
	}

	return default_menu_items();
}

/**
 * Link attributes for a node.
 *
 * @param array<string, mixed> $item Node.
 */
function link_attrs( array $item ): string {
	$attrs = ' href="' . esc_url( $item['url'] ) . '"';

	if ( ! empty( $item['target'] ) ) {
		$attrs .= ' target="' . esc_attr( $item['target'] ) . '" rel="noopener"';
	}

	if ( ! empty( $item['current'] ) ) {
		$attrs .= ' aria-current="page"';
	}

	return $attrs;
}

/**
 * Split children into regular items and the "view all" link.
 *
 * @param array<int, array<string, mixed>> $children Children.
 * @return array{0:array<int, array<string, mixed>>,1:?array<string, mixed>}
 */
function split_children( array $children ): array {
	$items = array();
	$all   = null;

	foreach ( $children as $child ) {
		if ( in_array( 'edst-view-all', $child['classes'], true ) ) {
			$all = $child;
		} else {
			$items[] = $child;
		}
	}

	return array( $items, $all );
}

/**
 * Desktop navigation with panels.
 */
function primary_nav(): void {
	$items = primary_items();

	if ( ! $items ) {
		return;
	}

	echo '<nav class="edst-nav" aria-label="' . esc_attr__( 'Primary', 'edshield' ) . '"><ul class="edst-nav__list">';

	foreach ( $items as $index => $item ) {
		if ( ! $item['children'] ) {
			echo '<li class="edst-nav__item"><a class="edst-nav__link"' . link_attrs( $item ) . '>' . esc_html( $item['title'] ) . '</a></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			continue;
		}

		$mega  = in_array( 'edst-mega', $item['classes'], true );
		$panel = 'edst-panel-' . ( $index + 1 );

		list( $children, $all ) = split_children( $item['children'] );

		echo '<li class="edst-nav__item edst-nav__item--has-panel' . ( $mega ? ' edst-nav__item--mega' : '' ) . '">';
		echo '<button type="button" class="edst-nav__link' . ( $item['current'] ? ' is-current' : '' ) . '" aria-expanded="false" aria-controls="' . esc_attr( $panel ) . '">' . esc_html( $item['title'] ) . icon( 'chevron', 'edst-nav__chev' ) . '</button>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<div class="edst-panel edst-panel--' . ( $mega ? 'mega' : 'drop' ) . '" id="' . esc_attr( $panel ) . '"><div class="edst-panel__inner"><ul class="edst-panel__grid">';

		foreach ( $children as $child ) {
			echo '<li><a class="edst-panel__link"' . link_attrs( $child ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			if ( $mega ) {
				echo '<span class="edst-panel__icon">' . icon( $child['icon'] ? $child['icon'] : 'spark' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			echo '<span class="edst-panel__text"><span class="edst-panel__title">' . esc_html( $child['title'] ) . '</span>';

			if ( '' !== $child['description'] ) {
				echo '<span class="edst-panel__desc">' . esc_html( $child['description'] ) . '</span>';
			}

			echo '</span></a></li>';
		}

		echo '</ul>';

		if ( $mega ) {
			$feature = mega_feature();

			echo '<aside class="edst-panel__feature"><p class="edst-eyebrow">' . esc_html( $feature['eyebrow'] ) . '</p><p class="edst-panel__ftitle">' . esc_html( $feature['title'] ) . '</p><p class="edst-panel__ftext">' . esc_html( $feature['text'] ) . '</p>' . button( $feature['label'], $feature['url'], 'primary', 'edst-btn--sm' ) . '</aside>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '</div>';

		if ( $all ) {
			echo '<a class="edst-panel__all"' . link_attrs( $all ) . '><span>' . esc_html( $all['title'] ) . '</span>' . icon( 'arrow' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '</div></li>';
	}

	echo '</ul></nav>';
}

/**
 * Featured card shown in the mega panel.
 *
 * @return array{eyebrow:string,title:string,text:string,label:string,url:string}
 */
function mega_feature(): array {
	$get = static function ( string $key, string $fallback ): string {
		$value = trim( (string) option( 'mega_' . $key, '' ) );

		return '' !== $value ? $value : $fallback;
	};

	return array(
		'eyebrow' => $get( 'eyebrow', __( 'Not sure where to start?', 'edshield' ) ),
		'title'   => $get( 'title', __( 'Book a discovery call', 'edshield' ) ),
		'text'    => $get( 'text', __( 'A focused conversation about your goals, systems and constraints, and the most sensible first step.', 'edshield' ) ),
		'label'   => $get( 'label', __( 'Book a call', 'edshield' ) ),
		'url'     => $get( 'url', 'page:contact' ),
	);
}

/**
 * Mobile navigation overlay.
 */
function mobile_nav(): void {
	$cta      = header_cta();
	$contacts = contact_details();
	?>
	<div class="edst-mobile" id="edst-mobile" data-edst-mobile>
		<nav class="edst-mobile__nav" aria-label="<?php esc_attr_e( 'Mobile', 'edshield' ); ?>">
			<ul class="edst-mobile__list">
				<?php foreach ( primary_items() as $item ) : ?>
					<li>
						<?php if ( $item['children'] ) : ?>
							<details class="edst-mobile__group">
								<summary class="edst-mobile__link"><?php echo esc_html( $item['title'] ); ?><span class="edst-mobile__toggle" aria-hidden="true"></span></summary>
								<ul class="edst-mobile__sub">
									<?php foreach ( $item['children'] as $child ) : ?>
										<li><a<?php echo link_attrs( $child ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $child['title'] ); ?></a></li>
									<?php endforeach; ?>
								</ul>
							</details>
						<?php else : ?>
							<a class="edst-mobile__link"<?php echo link_attrs( $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $item['title'] ); ?></a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>
		<div class="edst-mobile__foot">
			<?php echo button( $cta['label'], $cta['url'], 'primary', 'edst-btn--block' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php if ( '' !== $contacts['email'] || '' !== $contacts['phone'] ) : ?>
				<p class="edst-mobile__contact">
					<?php if ( '' !== $contacts['email'] ) : ?>
						<a href="<?php echo esc_attr( 'mailto:' . antispambot( $contacts['email'], 1 ) ); ?>"><?php echo esc_html( antispambot( $contacts['email'] ) ); ?></a>
					<?php endif; ?>
					<?php if ( '' !== $contacts['phone'] ) : ?>
						<a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $contacts['phone'] ) ); ?>"><?php echo esc_html( $contacts['phone'] ); ?></a>
					<?php endif; ?>
				</p>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Footer link list from a menu location, or a fallback list.
 *
 * @param string                           $location Menu location.
 * @param array<int, array<string, mixed>> $fallback Fallback nodes.
 */
function footer_links( string $location, array $fallback ): string {
	$items = menu_tree( $location );
	$items = $items ? $items : $fallback;

	if ( ! $items ) {
		return '';
	}

	$html = '<ul class="edst-footer__links">';

	foreach ( $items as $item ) {
		$html .= '<li><a' . link_attrs( $item ) . '>' . esc_html( $item['title'] ) . '</a></li>';
	}

	return $html . '</ul>';
}

/**
 * The default menu (when no primary menu is assigned), built from the same
 * blueprint the demo import uses.
 *
 * @return array<int, array<string, mixed>>
 */
function default_menu_items(): array {
	$convert = static function ( array $items ) use ( &$convert ): array {
		$nodes = array();

		foreach ( $items as $item ) {
			$classes = array_filter( explode( ' ', (string) ( $item['classes'] ?? '' ) ) );
			$icon    = '';

			foreach ( $classes as $class ) {
				if ( 0 === strpos( $class, 'edst-icon-' ) ) {
					$icon = substr( $class, 10 );
				}
			}

			$nodes[] = nav_node( (string) $item['title'], (string) $item['link'], (string) ( $item['description'] ?? '' ), array_values( $classes ), $icon, $convert( (array) ( $item['children'] ?? array() ) ) );
		}

		return $nodes;
	};

	return $convert( menu_blueprint()['primary']['items'] );
}
