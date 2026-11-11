/**
 * ED Shield theme: motion layer (GSAP + ScrollTrigger).
 *
 * Loaded on idle by main.js, never with reduced motion and never inside the
 * Elementor editor. Three restrained effects:
 *
 *   1. Reveal    elements marked .edst-rv rise into place once, as they
 *                enter the viewport. Anything already on screen is left
 *                exactly as it is, so nothing flashes or shifts.
 *   2. Depth     the home hero visual drifts a little slower than the page.
 *   3. Magnetic  primary calls to action lean toward a mouse pointer.
 *
 * If anything here throws, every element returns to its normal state.
 */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

const doc = document;
const touched = [];

const restore = () => {
	touched.forEach( ( element ) => {
		element.style.opacity = '';
		element.style.transform = '';
	} );
};

try {
	gsap.registerPlugin( ScrollTrigger );
	gsap.defaults( { ease: 'power3.out', duration: 0.9 } );

	const finePointer = window.matchMedia( '(hover: hover) and (pointer: fine)' ).matches;

	/* 1. Reveal */
	const fold = window.innerHeight * 0.94;
	const targets = gsap.utils.toArray( '.edst-rv' ).filter( ( element ) => {
		if ( ! element.getClientRects().length || ( element.parentElement && element.parentElement.closest( '.edst-rv' ) ) ) {
			return false;
		}

		return element.getBoundingClientRect().top > fold;
	} );

	if ( targets.length ) {
		touched.push( ...targets );
		gsap.set( targets, { opacity: 0, y: 28 } );

		ScrollTrigger.batch( targets, {
			start: 'top 90%',
			once: true,
			onEnter: ( batch ) =>
				gsap.to( batch, {
					opacity: 1,
					y: 0,
					stagger: 0.08,
					overwrite: true,
					clearProps: 'opacity,transform',
				} ),
		} );

		// A focused element must be visible even before its turn comes.
		doc.addEventListener( 'focusin', ( event ) => {
			const element = event.target.closest && event.target.closest( '.edst-rv' );

			if ( element && '0' === element.style.opacity ) {
				gsap.to( element, { opacity: 1, y: 0, duration: 0.3, overwrite: true, clearProps: 'opacity,transform' } );
			}
		} );
	}

	/* 2. Depth */
	gsap.utils.toArray( '.edst-s--hero-home' ).forEach( ( hero ) => {
		const visual = hero.querySelector( '.edst-hh__visual' );

		if ( ! visual || ! window.matchMedia( '(min-width: 64rem)' ).matches ) {
			return;
		}

		touched.push( visual );
		gsap.to( visual, {
			yPercent: 8,
			ease: 'none',
			scrollTrigger: {
				trigger: hero,
				start: 'top top',
				end: 'bottom top',
				scrub: 0.4,
			},
		} );
	} );

	/* 3. Magnetic */
	if ( finePointer ) {
		gsap.utils.toArray( '.edst-magnetic' ).forEach( ( element ) => {
			const x = gsap.quickTo( element, 'x', { duration: 0.5 } );
			const y = gsap.quickTo( element, 'y', { duration: 0.5 } );

			touched.push( element );

			element.addEventListener( 'pointermove', ( event ) => {
				const box = element.getBoundingClientRect();

				x( ( event.clientX - ( box.left + box.width / 2 ) ) * 0.18 );
				y( ( event.clientY - ( box.top + box.height / 2 ) ) * 0.3 );
			} );

			element.addEventListener( 'pointerleave', () => {
				x( 0 );
				y( 0 );
			} );
		} );
	}

	// Measure again once web fonts have arrived and whenever the layout changes.
	const refresh = () => ScrollTrigger.refresh();

	if ( doc.fonts && doc.fonts.ready ) {
		doc.fonts.ready.then( refresh );
	}

	window.addEventListener( 'edst:layout', () => window.requestAnimationFrame( refresh ) );
	doc.documentElement.classList.add( 'edst-motion-ready' );
} catch ( error ) {
	restore();
}
