<?php
/**
 * Icon set and illustrations.
 *
 * Every visual is drawn here as inline SVG, so the theme looks finished
 * without stock photography. Illustrations use CSS classes for colour, which
 * keeps them on-palette in every tone. Any section that shows an illustration
 * also accepts an uploaded image instead.
 *
 * @package EDShield\Theme
 */

namespace EDShield\Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Inner markup of each 24×24 line icon.
 *
 * @return array<string, string>
 */
function icon_paths(): array {
	return array(
		'web'           => '<rect x="3" y="4" width="18" height="16" rx="2.5"/><path d="M3 9h18M7 13h6M7 16h4"/>',
		'cart'          => '<path d="M3 4h2l2.2 10.3a1.5 1.5 0 0 0 1.5 1.2h8.4a1.5 1.5 0 0 0 1.5-1.1L20.5 8H6.1"/><circle cx="9.5" cy="19.5" r="1.3"/><circle cx="17" cy="19.5" r="1.3"/>',
		'ai'            => '<rect x="6" y="6" width="12" height="12" rx="2.5"/><path d="M9 3v3M15 3v3M9 18v3M15 18v3M3 9h3M3 15h3M18 9h3M18 15h3"/><path d="m12 9.4.8 1.8 1.8.8-1.8.8-.8 1.8-.8-1.8-1.8-.8 1.8-.8z"/>',
		'code'          => '<path d="m8 8-4 4 4 4M16 8l4 4-4 4M13.5 5.5l-3 13"/>',
		'crm'           => '<circle cx="6" cy="7" r="2.5"/><circle cx="18" cy="7" r="2.5"/><circle cx="12" cy="17.5" r="2.5"/><path d="M8.5 7h7M7.3 9.2l3.4 6M16.7 9.2l-3.4 6"/>',
		'shield'        => '<path d="M12 3 19 5.6V11c0 4.5-3 7.8-7 10-4-2.2-7-5.5-7-10V5.6z"/><path d="m9 12 2 2 4-4"/>',
		'cloud'         => '<path d="M7 18.5h10.2a4 4 0 0 0 .5-7.97A5.5 5.5 0 0 0 7.2 9.6 4.5 4.5 0 0 0 7 18.5z"/>',
		'factory'       => '<path d="M3 20h18M4 20v-9l5 3v-3l5 3v-3l5 3V5h2v15M8 17h2M13 17h2"/>',
		'compass'       => '<circle cx="12" cy="12" r="9"/><path d="m15.5 8.5-2 5-5 2 2-5z"/>',
		'layers'        => '<path d="m12 3 9 5-9 5-9-5z"/><path d="m3 13 9 5 9-5"/>',
		'chart'         => '<path d="M3 20h18M6 20v-7M11 20V5M16 20v-9M21 20v-4"/>',
		'spark'         => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18"/>',
		'lock'          => '<rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3M12 15v2"/>',
		'users'         => '<circle cx="9" cy="8" r="3"/><path d="M3.5 19a5.5 5.5 0 0 1 11 0"/><circle cx="17" cy="9" r="2.5"/><path d="M15.6 14.1A4.5 4.5 0 0 1 21 18.5"/>',
		'flag'          => '<path d="M5 21V4M5 4h11l-2 4 2 4H5"/>',
		'rocket'        => '<path d="M12 3c3.5 2 5 5.5 4.5 10l-2.5 2h-4L7.5 13C7 8.5 8.5 5 12 3z"/><circle cx="12" cy="9.5" r="1.5"/><path d="m10 15-2 4M14 15l2 4"/>',
		'store'         => '<path d="m4 9 1.5-5h13L20 9M4 9h16M5 9v11h14V9M9.5 20v-5h5v5"/>',
		'building'      => '<path d="M4 21V5l8-2v18M12 8h8v13M7 8h2M7 12h2M7 16h2M15 12h2M15 16h2M3 21h18"/>',
		'health'        => '<path d="M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10z"/><path d="M8.5 11.5h2l1-2 1.5 4 1-2h1.5"/>',
		'education'     => '<path d="m3 9 9-5 9 5-9 5z"/><path d="M7 11.5V16c1.5 1.4 3 2 5 2s3.5-.6 5-2v-4.5M21 9v5"/>',
		'ngo'           => '<path d="M12 20.5s-7-4-7-9.5a3.5 3.5 0 0 1 7-1.4 3.5 3.5 0 0 1 7 1.4c0 5.5-7 9.5-7 9.5z"/><path d="M12 9.6V6M10 4.5h4"/>',
		'government'    => '<path d="M3 21h18M4 10h16M12 3l9 5H3zM6 10v8M10 10v8M14 10v8M18 10v8"/>',
		'call'          => '<rect x="3" y="6" width="13" height="12" rx="2"/><path d="m16 10 5-3v10l-5-3"/>',
		'calendar'      => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4M8 14h2M12 14h2M16 14h2M8 17h2"/>',
		'check'         => '<circle cx="12" cy="12" r="9"/><path d="m8 12.5 2.5 2.5 5.5-5.5"/>',
		'chat'          => '<path d="M4 5h16v11H9l-5 4z"/><path d="M8 9h8M8 12h5"/>',
		'mail'          => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
		'phone'         => '<path d="M5 4h3.5l2 5-2.4 1.5a11 11 0 0 0 5.4 5.4L15 13.5l5 2V19a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/>',
		'pin'           => '<path d="M12 21s-6-5.5-6-11a6 6 0 0 1 12 0c0 5.5-6 11-6 11z"/><circle cx="12" cy="10" r="2"/>',
		'clock'         => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'whatsapp'      => '<path d="M4.5 20 5.8 16A8 8 0 1 1 8.4 18.7z"/><path d="M9.2 8.8c0 3.3 2.7 6 6 6l1-1.4-2-1-.9 1a4.2 4.2 0 0 1-2.1-2.1l1-.9-1-2z"/>',
		'arrow'         => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'arrow-up-right' => '<path d="M7 17 17 7M9 7h8v8"/>',
		'plus'          => '<path d="M12 5v14M5 12h14"/>',
		'chevron'       => '<path d="m6 9 6 6 6-6"/>',
		'close'         => '<path d="m6 6 12 12M18 6 6 18"/>',
		'quote'         => '<path d="M9.5 7H5v5h4v5l3-5V7zM19.5 7H15v5h4v5l3-5V7z"/>',
		'linkedin'      => '<rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 10.5V16M8 7.8v.2M12 16v-5.5M12 13a2.5 2.5 0 0 1 5 0v3"/>',
		'facebook'      => '<path d="M14 21v-8h3l.4-3H14V8.4c0-.9.4-1.4 1.5-1.4h1.8V4.2A19 19 0 0 0 15 4c-2.5 0-4 1.5-4 4.1V10H8v3h3v8"/>',
		'instagram'     => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5v.01"/>',
		'x'             => '<path d="M4 4l16 16M20 4 4 20"/>',
		'youtube'       => '<rect x="2.5" y="6" width="19" height="12" rx="4"/><path d="M10 9.5v5l4.5-2.5z"/>',
	);
}

/**
 * An inline SVG icon.
 *
 * @param string $name  Icon name.
 * @param string $class Extra class.
 */
function icon( string $name, string $class = '' ): string {
	$paths = icon_paths();

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg class="%1$s" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%2$s</svg>',
		esc_attr( trim( 'edst-icon ' . $class ) ),
		$paths[ $name ]
	);
}

/**
 * Names offered in icon pickers.
 *
 * @return array<string, string>
 */
function icon_choices(): array {
	$choices = array();

	foreach ( array( 'web', 'cart', 'ai', 'code', 'crm', 'shield', 'cloud', 'factory', 'compass', 'layers', 'chart', 'spark', 'lock', 'users', 'flag', 'rocket', 'store', 'building', 'health', 'education', 'ngo', 'government', 'call', 'calendar', 'check', 'chat', 'mail', 'phone', 'pin', 'clock' ) as $name ) {
		$choices[ $name ] = ucfirst( $name );
	}

	return $choices;
}

/**
 * Illustrations offered in pickers.
 *
 * @return array<string, string>
 */
function art_choices(): array {
	$choices = array(
		''           => __( 'None', 'edshield' ),
		'web'        => __( 'Website', 'edshield' ),
		'commerce'   => __( 'E-commerce', 'edshield' ),
		'ai'         => __( 'Automation flow', 'edshield' ),
		'software'   => __( 'Dashboard', 'edshield' ),
		'crm'        => __( 'CRM pipeline', 'edshield' ),
		'security'   => __( 'Security', 'edshield' ),
		'cloud'      => __( 'Cloud', 'edshield' ),
		'industrial' => __( 'Industrial data', 'edshield' ),
		'consulting' => __( 'Roadmap', 'edshield' ),
		'call'       => __( 'Discovery call', 'edshield' ),
		'calendar'   => __( 'Plan', 'edshield' ),
		'launch'     => __( 'Launch checklist', 'edshield' ),
		'system'     => __( 'System shield', 'edshield' ),
	);

	foreach ( icon_choices() as $name => $label ) {
		$choices[ 'icon:' . $name ] = sprintf( /* translators: %s: icon name. */ __( 'Icon: %s', 'edshield' ), $label );
	}

	return $choices;
}

/**
 * Map a service key to its illustration.
 *
 * @param string $service Service key.
 */
function service_art( string $service ): string {
	$map = array(
		'web'        => 'web',
		'commerce'   => 'commerce',
		'ai'         => 'ai',
		'software'   => 'software',
		'crm'        => 'crm',
		'security'   => 'security',
		'cloud'      => 'cloud',
		'industrial' => 'industrial',
		'consulting' => 'consulting',
	);

	return $map[ $service ] ?? 'web';
}

/**
 * An illustration as inline SVG (400×300, scales to fill its frame).
 *
 * @param string $key   Illustration key, or "icon:<name>".
 * @param string $class Extra class on the wrapper.
 */
function art( string $key, string $class = '' ): string {
	if ( '' === $key ) {
		return '';
	}

	$body = art_body( $key );

	// An icon name works too: it is drawn as an icon illustration.
	if ( '' === $body && 0 !== strpos( $key, 'icon:' ) ) {
		$body = art_body( 'icon:' . $key );
	}

	if ( '' === $body ) {
		return '';
	}

	// The drafting grid is a CSS background, so it runs edge to edge at any size.
	return sprintf(
		'<div class="%1$s" aria-hidden="true"><svg viewBox="0 0 400 300" preserveAspectRatio="xMidYMid meet" focusable="false">%2$s</svg></div>',
		esc_attr( trim( 'edst-art edst-art--' . sanitize_html_class( str_replace( ':', '-', $key ) ) . ' ' . $class ) ),
		$body
	);
}

/**
 * Drawing for one illustration.
 *
 * @param string $key Illustration key.
 */
function art_body( string $key ): string {
	if ( 0 === strpos( $key, 'icon:' ) ) {
		$paths = icon_paths();
		$name  = substr( $key, 5 );

		if ( ! isset( $paths[ $name ] ) ) {
			return '';
		}

		return '<circle class="a-m a-dash" cx="200" cy="150" r="96"/><circle class="a-m" cx="200" cy="150" r="64"/><rect class="a-f" x="156" y="106" width="88" height="88" rx="20"/><g class="a-i" transform="translate(170 120) scale(2.5)" fill="none" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">' . $paths[ $name ] . '</g><circle class="a-fb" cx="296" cy="88" r="5"/><circle class="a-fl" cx="104" cy="212" r="3.5"/>';
	}

	switch ( $key ) {
		case 'system':
			$out = '<circle class="a-m a-dash" cx="200" cy="150" r="122"/><circle class="a-m" cx="200" cy="150" r="100"/>';
			$dots = '';

			for ( $k = 0; $k < 9; $k++ ) {
				$a     = deg2rad( -90 + 40 * $k );
				$x     = round( 200 + 100 * cos( $a ), 1 );
				$y     = round( 150 + 100 * sin( $a ), 1 );
				$out  .= '<path class="a-b a-soft" d="M200 150L' . $x . ' ' . $y . '"/>';
				$dots .= '<circle class="' . ( 0 === $k % 3 ? 'a-fb' : 'a-f a-l' ) . '" cx="' . $x . '" cy="' . $y . '" r="7"/>';
			}

			return $out . $dots . '<path class="a-f a-l" d="M200 112 232 123v27c0 21-13.5 35-32 46-18.5-11-32-25-32-46v-27z"/><circle class="a-fb" cx="200" cy="152" r="9"/>';

		case 'web':
			return '<rect class="a-f" x="64" y="48" width="272" height="204" rx="12"/><path class="a-m" d="M64 78h272"/><circle class="a-fl" cx="82" cy="63" r="4"/><circle class="a-fm" cx="96" cy="63" r="4"/><circle class="a-fm" cx="110" cy="63" r="4"/><rect class="a-fm" x="196" y="57" width="120" height="12" rx="6"/><rect class="a-fl" x="86" y="98" width="128" height="13" rx="3"/><rect class="a-fm" x="86" y="120" width="98" height="7" rx="3"/><rect class="a-fm" x="86" y="133" width="112" height="7" rx="3"/><rect class="a-fb" x="86" y="152" width="62" height="20" rx="5"/><rect class="a-img" x="228" y="96" width="88" height="76" rx="7"/><path class="a-b" d="m236 162 20-22 14 13 11-10 27 19"/><circle class="a-fl" cx="296" cy="114" r="6"/><rect class="a-fm" x="86" y="190" width="70" height="44" rx="6"/><rect class="a-fm" x="165" y="190" width="70" height="44" rx="6"/><rect class="a-fm" x="244" y="190" width="72" height="44" rx="6"/>';

		case 'commerce':
			return '<rect class="a-f" x="70" y="50" width="160" height="200" rx="12"/><rect class="a-img" x="84" y="64" width="132" height="100" rx="8"/><path class="a-b" d="M116 140c10-26 58-26 68 0"/><circle class="a-fl" cx="150" cy="110" r="14"/><rect class="a-fl" x="84" y="178" width="96" height="10" rx="3"/><rect class="a-fm" x="84" y="196" width="60" height="8" rx="3"/><rect class="a-fb" x="84" y="216" width="132" height="22" rx="6"/><rect class="a-f" x="252" y="70" width="88" height="88" rx="16"/><g class="a-i" transform="translate(268 86) scale(2.33)" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">' . icon_paths()['cart'] . '</g><circle class="a-fb" cx="334" cy="74" r="11"/><rect class="a-fm" x="252" y="176" width="88" height="14" rx="5"/><rect class="a-fm" x="252" y="198" width="70" height="14" rx="5"/><rect class="a-fm" x="252" y="220" width="80" height="14" rx="5"/>';

		case 'ai':
			return '<rect class="a-f" x="34" y="122" width="78" height="56" rx="12"/><rect class="a-fl" x="48" y="138" width="40" height="7" rx="3"/><rect class="a-fm" x="48" y="152" width="50" height="7" rx="3"/><path class="a-b" d="M112 150h46"/><circle class="a-fb" cx="158" cy="150" r="4"/><rect class="a-chip" x="162" y="106" width="84" height="88" rx="18"/><path class="a-l" d="m204 132 5 11 11 5-11 5-5 11-5-11-11-5 11-5z"/><path class="a-m a-dash" d="M246 150c26 0 22-64 54-64M246 150h54M246 150c26 0 22 64 54 64"/><rect class="a-f" x="300" y="66" width="70" height="40" rx="10"/><rect class="a-f" x="300" y="130" width="70" height="40" rx="10"/><rect class="a-f" x="300" y="194" width="70" height="40" rx="10"/><rect class="a-fl" x="312" y="80" width="34" height="6" rx="3"/><rect class="a-fl" x="312" y="144" width="40" height="6" rx="3"/><rect class="a-fl" x="312" y="208" width="28" height="6" rx="3"/><circle class="a-fb" cx="358" cy="86" r="4"/><circle class="a-fb" cx="358" cy="150" r="4"/><circle class="a-fb" cx="358" cy="214" r="4"/>';

		case 'software':
			return '<rect class="a-f" x="52" y="44" width="296" height="212" rx="12"/><rect class="a-f2" x="52" y="44" width="62" height="212" rx="12"/><rect class="a-fl" x="66" y="62" width="34" height="8" rx="3"/><rect class="a-fm" x="66" y="86" width="30" height="6" rx="3"/><rect class="a-fb" x="66" y="102" width="34" height="6" rx="3"/><rect class="a-fm" x="66" y="118" width="26" height="6" rx="3"/><rect class="a-fm" x="66" y="134" width="32" height="6" rx="3"/><rect class="a-f2" x="130" y="60" width="62" height="40" rx="7"/><rect class="a-f2" x="202" y="60" width="62" height="40" rx="7"/><rect class="a-f2" x="274" y="60" width="58" height="40" rx="7"/><rect class="a-fl" x="140" y="70" width="28" height="9" rx="3"/><rect class="a-fl" x="212" y="70" width="34" height="9" rx="3"/><rect class="a-fb" x="284" y="70" width="26" height="9" rx="3"/><rect class="a-fm" x="146" y="196" width="16" height="42" rx="3"/><rect class="a-fm" x="172" y="176" width="16" height="62" rx="3"/><rect class="a-fm" x="198" y="186" width="16" height="52" rx="3"/><rect class="a-fb" x="224" y="152" width="16" height="86" rx="3"/><rect class="a-fm" x="250" y="170" width="16" height="68" rx="3"/><rect class="a-fm" x="276" y="160" width="16" height="78" rx="3"/><rect class="a-fm" x="302" y="140" width="16" height="98" rx="3"/><path class="a-b" d="m138 150 30-18 26 8 30-26 26 10 26-20 34-6"/>';

		case 'crm':
			$out = '';
			$cols = array( 56, 134, 212, 290 );
			$cards = array( 3, 2, 3, 1 );

			foreach ( $cols as $i => $x ) {
				$out .= '<rect class="a-fm" x="' . $x . '" y="58" width="56" height="8" rx="3"/>';

				for ( $c = 0; $c < $cards[ $i ]; $c++ ) {
					$y      = 78 + $c * 50;
					$cls    = ( 2 === $i && 0 === $c ) ? 'a-fb' : 'a-f';
					$out   .= '<rect class="' . $cls . '" x="' . $x . '" y="' . $y . '" width="56" height="40" rx="7"/>';
					$out   .= '<rect class="' . ( 'a-fb' === $cls ? 'a-fp' : 'a-fl' ) . '" x="' . ( $x + 9 ) . '" y="' . ( $y + 11 ) . '" width="30" height="5" rx="2"/>';
					$out   .= '<rect class="a-fm" x="' . ( $x + 9 ) . '" y="' . ( $y + 22 ) . '" width="22" height="5" rx="2"/>';
				}
			}

			return $out . '<path class="a-b a-dash" d="M112 98c40 0 60-30 100-20"/><path class="a-m" d="M56 248h290"/><circle class="a-fb" cx="212" cy="248" r="5"/>';

		case 'security':
			return '<circle class="a-m a-dash" cx="200" cy="150" r="118"/><circle class="a-m" cx="200" cy="150" r="86"/><path class="a-f a-l" d="M200 66 262 88v54c0 40-27 68-62 88-35-20-62-48-62-88V88z"/><rect class="a-fb" x="180" y="142" width="40" height="32" rx="6"/><path class="a-l" d="M188 142v-10a12 12 0 0 1 24 0v10"/><circle class="a-fp" cx="200" cy="157" r="3.5"/><rect class="a-f" x="290" y="62" width="92" height="26" rx="13"/><circle class="a-fb" cx="306" cy="75" r="5"/><rect class="a-fl" x="318" y="72" width="50" height="6" rx="3"/><rect class="a-f" x="18" y="202" width="96" height="26" rx="13"/><circle class="a-fb" cx="34" cy="215" r="5"/><rect class="a-fl" x="46" y="212" width="54" height="6" rx="3"/>';

		case 'cloud':
			return '<path class="a-f a-l" d="M152 120h104a32 32 0 0 0 3-63.8 44 44 0 0 0-84.4 10.2A27 27 0 0 0 152 120z"/><path class="a-m a-dash" d="M170 120v32M210 120v32M250 120v32"/><rect class="a-f" x="132" y="152" width="136" height="28" rx="6"/><rect class="a-f" x="132" y="186" width="136" height="28" rx="6"/><rect class="a-f" x="132" y="220" width="136" height="28" rx="6"/><circle class="a-fb" cx="148" cy="166" r="4"/><circle class="a-fb" cx="148" cy="200" r="4"/><circle class="a-fl" cx="148" cy="234" r="4"/><rect class="a-fm" x="162" y="163" width="60" height="6" rx="3"/><rect class="a-fm" x="162" y="197" width="72" height="6" rx="3"/><rect class="a-fm" x="162" y="231" width="50" height="6" rx="3"/><rect class="a-fl" x="242" y="163" width="14" height="6" rx="3"/><rect class="a-fl" x="242" y="197" width="14" height="6" rx="3"/><rect class="a-fl" x="242" y="231" width="14" height="6" rx="3"/>';

		case 'industrial':
			return '<path class="a-m a-thick" d="M58 214a82 82 0 0 1 164 0"/><path class="a-b a-thick" d="M58 214a82 82 0 0 1 118-73.6"/><path class="a-l" d="m140 214 40-44"/><circle class="a-fb" cx="140" cy="214" r="7"/><rect class="a-fl" x="104" y="232" width="72" height="8" rx="3"/><rect class="a-f" x="236" y="62" width="128" height="176" rx="12"/><path class="a-g2" d="M250 112h100M250 152h100M250 192h100"/><path class="a-b" d="m250 200 20-26 18 12 20-40 18 16 24-42"/><circle class="a-fb" cx="350" cy="120" r="5"/><rect class="a-fl" x="250" y="76" width="54" height="8" rx="3"/><circle class="a-m" cx="96" cy="86" r="26"/><circle class="a-fm" cx="96" cy="86" r="9"/><path class="a-m" d="M96 52v-8M96 128v-8M62 86h-8M138 86h-8"/>';

		case 'consulting':
			return '<path class="a-m" d="M44 214c70 0 66-90 140-90s70-62 172-62"/><circle class="a-fl" cx="44" cy="214" r="8"/><circle class="a-fl" cx="128" cy="168" r="8"/><circle class="a-fl" cx="214" cy="116" r="8"/><circle class="a-fb" cx="300" cy="72" r="9"/><path class="a-l" d="M356 62V26l26 9-26 9"/><rect class="a-f" x="66" y="228" width="96" height="30" rx="7"/><rect class="a-fl" x="78" y="240" width="54" height="6" rx="3"/><rect class="a-f" x="150" y="64" width="96" height="30" rx="7"/><rect class="a-fl" x="162" y="76" width="62" height="6" rx="3"/><rect class="a-f" x="246" y="120" width="96" height="30" rx="7"/><rect class="a-fb" x="258" y="132" width="48" height="6" rx="3"/>';

		case 'call':
			return '<rect class="a-f" x="64" y="52" width="272" height="188" rx="14"/><rect class="a-f2" x="80" y="68" width="116" height="122" rx="9"/><rect class="a-f2" x="204" y="68" width="116" height="122" rx="9"/><circle class="a-fm" cx="138" cy="118" r="22"/><path class="a-fm" d="M106 172a32 22 0 0 1 64 0z"/><circle class="a-fb" cx="262" cy="118" r="22"/><path class="a-fb" d="M230 172a32 22 0 0 1 64 0z"/><circle class="a-fm" cx="170" cy="214" r="11"/><circle class="a-fb" cx="200" cy="214" r="11"/><circle class="a-fm" cx="230" cy="214" r="11"/><rect class="a-fl" x="88" y="76" width="34" height="12" rx="6"/>';

		case 'calendar':
			$out = '<rect class="a-f" x="88" y="44" width="224" height="212" rx="14"/><rect class="a-f2" x="88" y="44" width="224" height="40" rx="14"/><rect class="a-fl" x="104" y="60" width="70" height="8" rx="3"/>';

			for ( $r = 0; $r < 5; $r++ ) {
				for ( $c = 0; $c < 7; $c++ ) {
					$cx   = 112 + $c * 29;
					$cy   = 106 + $r * 30;
					$cls  = ( 2 === $r && 4 === $c ) ? 'a-fb' : ( ( 2 === $r && $c > 0 && $c < 4 ) ? 'a-fl' : 'a-fm' );
					$out .= '<circle class="' . $cls . '" cx="' . $cx . '" cy="' . $cy . '" r="' . ( 'a-fb' === $cls ? 9 : 4 ) . '"/>';
				}
			}

			return $out;

		case 'launch':
			return '<rect class="a-f" x="92" y="48" width="216" height="204" rx="14"/><circle class="a-fb" cx="122" cy="88" r="10"/><path class="a-p" d="m117 88 3.5 3.5 6.5-7"/><rect class="a-fl" x="142" y="82" width="120" height="8" rx="3"/><circle class="a-fb" cx="122" cy="130" r="10"/><path class="a-p" d="m117 130 3.5 3.5 6.5-7"/><rect class="a-fl" x="142" y="124" width="98" height="8" rx="3"/><circle class="a-fb" cx="122" cy="172" r="10"/><path class="a-p" d="m117 172 3.5 3.5 6.5-7"/><rect class="a-fl" x="142" y="166" width="110" height="8" rx="3"/><rect class="a-fb" x="200" y="208" width="88" height="26" rx="13"/><rect class="a-fp" x="222" y="218" width="44" height="6" rx="3"/>';
	}

	return '';
}
