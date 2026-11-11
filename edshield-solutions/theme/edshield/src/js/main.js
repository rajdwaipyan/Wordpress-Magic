/**
 * ED Shield theme: core behaviour. No dependencies.
 *
 * Header, mega menu, mobile menu, announcement bar, sliders, project
 * filter, FAQ navigation, counters, smooth in-page links and the lazy
 * motion layer. Each part starts only where its markup exists, and again
 * for widgets Elementor renders after the page has loaded.
 */

const doc = document;
const root = doc.documentElement;
const media = ( query ) => window.matchMedia( query );
const reduceMotion = media( '(prefers-reduced-motion: reduce)' );
const desktop = media( '(min-width: 64rem)' );
const finePointer = media( '(hover: hover) and (pointer: fine)' );
const behavior = () => ( reduceMotion.matches ? 'auto' : 'smooth' );
const inEditor = () => doc.body.classList.contains( 'elementor-editor-active' ) || /[?&]elementor-preview=/.test( window.location.search );
const announceKey = 'edst-announce';

/**
 * Run a setup once per element and feature.
 */
const done = {};
const fresh = ( feature, element ) => {
	const seen = done[ feature ] || ( done[ feature ] = new WeakSet() );

	if ( seen.has( element ) ) {
		return false;
	}

	seen.add( element );

	return true;
};

const focusables = ( container ) =>
	Array.from( container.querySelectorAll( 'a[href], button:not([disabled]), summary, input:not([type="hidden"]), select, textarea, [tabindex]:not([tabindex="-1"])' ) ).filter(
		( element ) => element.getClientRects().length > 0
	);

/* Header: hides while scrolling down, returns on the way up. */
const headerBar = () => {
	const header = doc.querySelector( '[data-edst-header]' );

	return header ? header.closest( '.elementor-location-header' ) || header : null;
};

const announceHeight = () => {
	const bar = doc.querySelector( '[data-edst-announce]' );
	const height = bar && ! bar.classList.contains( 'is-closed' ) && bar.getClientRects().length ? bar.offsetHeight : 0;

	root.style.setProperty( '--edst-announce-h', height + 'px' );
};

// Keyboard focus inside the header keeps it on screen.
const keyboardFocus = ( element ) => {
	try {
		return !! element.querySelector( ':focus-visible' );
	} catch ( error ) {
		return element.contains( doc.activeElement ) && doc.activeElement !== doc.body;
	}
};

let headerWatching = false;

function initHeader() {
	const header = doc.querySelector( '[data-edst-header]' );

	if ( ! header || ! fresh( 'header', header ) ) {
		return;
	}

	announceHeight();
	header.addEventListener( 'focusin', () => headerBar().classList.remove( 'is-hidden' ) );

	if ( headerWatching || inEditor() ) {
		return;
	}

	headerWatching = true;

	let lastY = window.scrollY;
	let queued = false;

	const update = () => {
		queued = false;

		const bar = headerBar();
		const y = Math.max( window.scrollY, 0 );
		const delta = y - lastY;

		if ( ! bar || ( Math.abs( delta ) < 6 && y > 120 ) ) {
			return;
		}

		const busy = root.classList.contains( 'edst-menu-open' ) || bar.querySelector( '.edst-panel.is-open' ) || keyboardFocus( bar );

		bar.classList.toggle( 'is-hidden', ! busy && y > 120 && delta > 0 );
		lastY = y;
	};

	window.addEventListener(
		'scroll',
		() => {
			if ( ! queued ) {
				queued = true;
				window.requestAnimationFrame( update );
			}
		},
		{ passive: true }
	);
	window.addEventListener( 'resize', announceHeight, { passive: true } );
}

/* Desktop mega menu and dropdowns. */
const openPanels = new Set();

const closePanels = ( except ) => {
	openPanels.forEach( ( panel ) => {
		if ( panel !== except ) {
			panel.close();
		}
	} );
};

let panelsWatching = false;

function initPanels( scope ) {
	scope.querySelectorAll( '.edst-nav__link[aria-controls]' ).forEach( ( button ) => {
		const panel = doc.getElementById( button.getAttribute( 'aria-controls' ) );
		const item = button.closest( '.edst-nav__item' ) || button.parentElement;

		if ( ! panel || ! item || ! fresh( 'panel', button ) ) {
			return;
		}

		let timer = 0;
		let openedBy = '';

		const control = {
			open( how ) {
				window.clearTimeout( timer );
				closePanels( control );
				openedBy = how;
				button.setAttribute( 'aria-expanded', 'true' );
				panel.classList.add( 'is-open' );
				openPanels.add( control );
			},
			close() {
				window.clearTimeout( timer );
				openedBy = '';
				button.setAttribute( 'aria-expanded', 'false' );
				panel.classList.remove( 'is-open' );
				openPanels.delete( control );
			},
		};

		button.addEventListener( 'click', () => {
			if ( panel.classList.contains( 'is-open' ) && 'hover' !== openedBy ) {
				control.close();
			} else {
				control.open( 'click' );
			}
		} );

		item.addEventListener( 'mouseenter', () => {
			if ( finePointer.matches ) {
				window.clearTimeout( timer );
				timer = window.setTimeout( () => panel.classList.contains( 'is-open' ) || control.open( 'hover' ), 90 );
			}
		} );

		item.addEventListener( 'mouseleave', () => {
			if ( finePointer.matches ) {
				window.clearTimeout( timer );
				timer = window.setTimeout( () => control.close(), 220 );
			}
		} );

		item.addEventListener( 'focusout', ( event ) => {
			if ( ! item.contains( event.relatedTarget ) ) {
				control.close();
			}
		} );

		item.addEventListener( 'keydown', ( event ) => {
			if ( 'Escape' === event.key && panel.classList.contains( 'is-open' ) ) {
				control.close();
				button.focus();
			}
		} );
	} );

	if ( ! panelsWatching ) {
		panelsWatching = true;
		doc.addEventListener( 'click', ( event ) => {
			if ( ! event.target.closest( '.edst-nav__item--has-panel' ) ) {
				closePanels();
			}
		} );
	}
}

/* Mobile menu. */
function initMobile() {
	const burger = doc.querySelector( '[data-edst-burger]' );
	const menu = doc.querySelector( '[data-edst-mobile]' );

	if ( ! burger || ! menu || ! fresh( 'mobile', burger ) ) {
		return;
	}

	const isOpen = () => 'true' === burger.getAttribute( 'aria-expanded' );

	const place = () => {
		const header = doc.querySelector( '[data-edst-header]' );
		const bottom = header ? header.getBoundingClientRect().bottom : 0;

		menu.style.setProperty( '--edst-mobile-top', Math.max( 0, bottom ) + 'px' );
	};

	const open = () => {
		const bar = headerBar();

		if ( bar ) {
			bar.classList.remove( 'is-hidden' );
		}

		place();
		menu.inert = false;
		menu.classList.add( 'is-open' );
		burger.setAttribute( 'aria-expanded', 'true' );
		root.classList.add( 'edst-menu-open' );
	};

	const close = ( returnFocus ) => {
		menu.classList.remove( 'is-open' );
		menu.inert = true;
		burger.setAttribute( 'aria-expanded', 'false' );
		root.classList.remove( 'edst-menu-open' );

		if ( returnFocus ) {
			burger.focus();
		}
	};

	menu.inert = true;

	burger.addEventListener( 'click', () => ( isOpen() ? close( false ) : open() ) );

	burger.addEventListener( 'keydown', ( event ) => {
		if ( 'Tab' === event.key && isOpen() ) {
			const items = focusables( menu );

			if ( items.length ) {
				event.preventDefault();
				items[ event.shiftKey ? items.length - 1 : 0 ].focus();
			}
		}
	} );

	menu.addEventListener( 'keydown', ( event ) => {
		if ( 'Escape' === event.key ) {
			close( true );

			return;
		}

		if ( 'Tab' !== event.key ) {
			return;
		}

		const items = focusables( menu );
		const edge = event.shiftKey ? items[ 0 ] : items[ items.length - 1 ];

		if ( doc.activeElement === edge ) {
			event.preventDefault();
			burger.focus();
		}
	} );

	doc.addEventListener( 'keydown', ( event ) => {
		if ( 'Escape' === event.key && isOpen() && doc.activeElement === burger ) {
			close( true );
		}
	} );

	menu.addEventListener( 'click', ( event ) => {
		if ( event.target.closest( 'a[href]' ) ) {
			close( false );
		}
	} );

	desktop.addEventListener( 'change', ( event ) => {
		if ( event.matches && isOpen() ) {
			close( false );
		}
	} );

	window.addEventListener( 'resize', () => isOpen() && place(), { passive: true } );
}

/* Announcement bar: closing it hides this message until the text changes. */
function initAnnounce() {
	const bar = doc.querySelector( '[data-edst-announce]' );

	if ( ! bar || ! fresh( 'announce', bar ) ) {
		return;
	}

	const id = bar.getAttribute( 'data-edst-announce' );
	const button = bar.querySelector( '[data-edst-announce-close]' );

	try {
		if ( window.localStorage.getItem( announceKey ) === id && ! inEditor() ) {
			bar.classList.add( 'is-closed' );
		}
	} catch ( error ) {
		// Storage unavailable: the bar simply stays.
	}

	if ( button ) {
		button.addEventListener( 'click', () => {
			bar.classList.add( 'is-closed' );
			announceHeight();

			try {
				window.localStorage.setItem( announceKey, id );
			} catch ( error ) {
				// Storage unavailable: closed for this page view only.
			}

			const next = doc.querySelector( '[data-edst-header] a[href]' );

			if ( next ) {
				next.focus();
			}
		} );
	}

	announceHeight();
}

/* Horizontal sliders: buttons, progress bar and mouse dragging. */
function initRails( scope ) {
	scope.querySelectorAll( '[data-edst-rail]' ).forEach( ( rail ) => {
		const track = rail.querySelector( '.edst-rail__track' );

		if ( ! track || ! fresh( 'rail', rail ) ) {
			return;
		}

		const prev = rail.querySelector( '[data-edst-prev]' );
		const next = rail.querySelector( '[data-edst-next]' );
		const fill = rail.querySelector( '.edst-rail__fill' );

		const step = () => {
			const item = track.querySelector( '.edst-rail__item' );
			const gap = parseFloat( window.getComputedStyle( track ).columnGap ) || 0;

			return item ? item.getBoundingClientRect().width + gap : track.clientWidth * 0.8;
		};

		const update = () => {
			const max = track.scrollWidth - track.clientWidth;
			const x = Math.min( Math.max( track.scrollLeft, 0 ), Math.max( max, 0 ) );

			rail.classList.toggle( 'is-static', max <= 2 );

			if ( prev ) {
				prev.disabled = x <= 2;
			}

			if ( next ) {
				next.disabled = x >= max - 2;
			}

			if ( fill && fill.parentElement ) {
				const barWidth = fill.parentElement.clientWidth;
				const ratio = track.scrollWidth ? Math.min( 1, Math.max( 0.12, track.clientWidth / track.scrollWidth ) ) : 1;
				const width = barWidth * ratio;

				fill.style.width = width + 'px';
				fill.style.transform = 'translateX(' + ( max > 0 ? ( barWidth - width ) * ( x / max ) : 0 ) + 'px)';
			}
		};

		let queued = false;

		track.addEventListener(
			'scroll',
			() => {
				if ( ! queued ) {
					queued = true;
					window.requestAnimationFrame( () => {
						queued = false;
						update();
					} );
				}
			},
			{ passive: true }
		);

		if ( prev ) {
			prev.addEventListener( 'click', () => track.scrollBy( { left: -step(), behavior: behavior() } ) );
		}

		if ( next ) {
			next.addEventListener( 'click', () => track.scrollBy( { left: step(), behavior: behavior() } ) );
		}

		// Drag with a mouse. Touch devices scroll natively.
		let down = false;
		let moved = false;
		let startX = 0;
		let startLeft = 0;

		track.addEventListener( 'pointerdown', ( event ) => {
			if ( 'mouse' !== event.pointerType || 0 !== event.button ) {
				return;
			}

			down = true;
			moved = false;
			startX = event.clientX;
			startLeft = track.scrollLeft;
		} );

		window.addEventListener( 'pointermove', ( event ) => {
			if ( ! down ) {
				return;
			}

			const dx = event.clientX - startX;

			if ( ! moved && Math.abs( dx ) > 6 ) {
				moved = true;
				track.classList.add( 'is-dragging' );
			}

			if ( moved ) {
				track.scrollLeft = startLeft - dx;
			}
		} );

		window.addEventListener( 'pointerup', () => {
			if ( down && moved ) {
				track.classList.remove( 'is-dragging' );
			}

			down = false;
		} );

		track.addEventListener(
			'click',
			( event ) => {
				if ( moved ) {
					event.preventDefault();
					event.stopPropagation();
					moved = false;
				}
			},
			true
		);

		track.addEventListener( 'dragstart', ( event ) => event.preventDefault() );
		window.addEventListener( 'resize', update, { passive: true } );
		update();
	} );
}

/* Project grid: industry filter and "Load more". */
const layoutChanged = () => window.dispatchEvent( new CustomEvent( 'edst:layout' ) );

function initFilters( scope ) {
	scope.querySelectorAll( '[data-edst-filter]' ).forEach( ( work ) => {
		if ( ! fresh( 'filter', work ) ) {
			return;
		}

		const buttons = work.querySelectorAll( '[data-filter]' );
		const items = work.querySelectorAll( '.edst-work__item' );
		const more = work.querySelector( '[data-edst-more]' );

		buttons.forEach( ( button ) =>
			button.addEventListener( 'click', () => {
				const filter = button.getAttribute( 'data-filter' );

				buttons.forEach( ( other ) => {
					const active = other === button;

					other.classList.toggle( 'is-active', active );
					other.setAttribute( 'aria-pressed', active ? 'true' : 'false' );
				} );

				if ( '*' !== filter ) {
					work.classList.add( 'is-expanded' );
				}

				items.forEach( ( item ) => {
					const cats = ( item.getAttribute( 'data-cats' ) || '' ).split( ' ' );

					item.classList.toggle( 'is-filtered', '*' !== filter && -1 === cats.indexOf( filter ) );
				} );

				layoutChanged();
			} )
		);

		if ( more ) {
			more.addEventListener( 'click', () => {
				const first = work.querySelector( '.edst-is-more a[href]' );

				work.classList.add( 'is-expanded' );
				layoutChanged();

				if ( first ) {
					first.focus( { preventScroll: true } );
				}
			} );
		}
	} );
}

/* FAQ categories: highlight the group being read. */
function initFaqNav( scope ) {
	scope.querySelectorAll( '[data-edst-faqnav]' ).forEach( ( wrap ) => {
		if ( ! fresh( 'faq', wrap ) ) {
			return;
		}

		const links = Array.from( wrap.querySelectorAll( '.edst-faqg__nav a[href^="#"]' ) );
		const groups = [];
		let current = null;

		links.forEach( ( link ) => {
			const target = doc.getElementById( link.getAttribute( 'href' ).slice( 1 ) );

			if ( target ) {
				groups.push( [ target, link ] );
			}
		} );

		if ( ! groups.length ) {
			return;
		}

		const activate = ( link ) => {
			if ( link === current ) {
				return;
			}

			current = link;
			links.forEach( ( other ) => {
				other.classList.toggle( 'is-active', other === link );

				if ( other === link ) {
					other.setAttribute( 'aria-current', 'true' );
				} else {
					other.removeAttribute( 'aria-current' );
				}
			} );

			const list = link.closest( 'ol' );

			if ( list && ! desktop.matches && list.scrollWidth > list.clientWidth ) {
				list.scrollTo( { left: link.parentElement.offsetLeft - 16, behavior: behavior() } );
			}
		};

		// The active group is the last one whose heading has passed 40% of the viewport.
		const update = () => {
			const line = window.innerHeight * 0.4;
			let active = groups[ 0 ][ 1 ];

			groups.forEach( ( [ target, link ] ) => {
				if ( target.getBoundingClientRect().top <= line ) {
					active = link;
				}
			} );

			activate( active );
		};

		let queued = false;

		window.addEventListener(
			'scroll',
			() => {
				if ( ! queued ) {
					queued = true;
					window.requestAnimationFrame( () => {
						queued = false;
						update();
					} );
				}
			},
			{ passive: true }
		);

		update();
	} );
}

/* Numbers count up once when they scroll into view. */
let counterObserver = null;

const countUp = ( element ) => {
	const parts = element.edstCount;
	const end = parseInt( parts[ 2 ], 10 );
	const start = window.performance.now();
	const duration = 1300;

	const tick = ( now ) => {
		const progress = Math.min( ( now - start ) / duration, 1 );

		element.textContent = parts[ 1 ] + Math.round( end * ( 1 - Math.pow( 1 - progress, 3 ) ) ) + ( parts[ 3 ] || '' );

		if ( progress < 1 ) {
			window.requestAnimationFrame( tick );
		}
	};

	window.requestAnimationFrame( tick );
};

function initCounters( scope ) {
	if ( reduceMotion.matches || inEditor() || ! ( 'IntersectionObserver' in window ) ) {
		return;
	}

	counterObserver =
		counterObserver ||
		new IntersectionObserver(
			( entries, observer ) => {
				entries.forEach( ( entry ) => {
					if ( entry.isIntersecting ) {
						observer.unobserve( entry.target );
						countUp( entry.target );
					}
				} );
			},
			{ threshold: 0.5 }
		);

	scope.querySelectorAll( '[data-edst-count]' ).forEach( ( element ) => {
		const parts = /^(\D*?)(\d{1,6})(\D.*)?$/.exec( element.textContent.trim() );

		if ( ! parts || ! fresh( 'count', element ) || element.getBoundingClientRect().top < window.innerHeight ) {
			return;
		}

		element.edstCount = parts;
		element.textContent = parts[ 1 ] + '0' + ( parts[ 3 ] || '' );
		counterObserver.observe( element );
	} );
}

/* Pause the moving strips while they are off screen. */
let pauseObserver = null;

function initPausers( scope ) {
	if ( ! ( 'IntersectionObserver' in window ) ) {
		return;
	}

	pauseObserver =
		pauseObserver ||
		new IntersectionObserver( ( entries ) => {
			entries.forEach( ( entry ) => entry.target.classList.toggle( 'is-paused', ! entry.isIntersecting ) );
		} );

	scope.querySelectorAll( '.edst-tiles, .edst-marquee' ).forEach( ( element ) => {
		if ( fresh( 'pause', element ) ) {
			pauseObserver.observe( element );
		}
	} );
}

/* Smooth scrolling for links to a place on the same page. */
const scrollOffset = () => parseFloat( window.getComputedStyle( root ).scrollPaddingTop ) || 96;

const goTo = ( target ) => {
	const top = target.getBoundingClientRect().top + window.scrollY - scrollOffset();

	window.scrollTo( { top: Math.max( top, 0 ), behavior: behavior() } );

	if ( ! target.matches( 'a[href], button, input, select, textarea, summary, [tabindex]' ) ) {
		target.setAttribute( 'tabindex', '-1' );
	}

	target.focus( { preventScroll: true } );
};

function initAnchors() {
	doc.addEventListener( 'click', ( event ) => {
		if ( event.defaultPrevented || 0 !== event.button || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey ) {
			return;
		}

		const link = event.target.closest( 'a[href*="#"]' );

		if ( ! link || ( link.target && '_self' !== link.target ) || inEditor() ) {
			return;
		}

		const url = new URL( link.href, window.location.href );

		if ( url.origin !== window.location.origin || url.pathname !== window.location.pathname || url.search !== window.location.search || url.hash.length < 2 ) {
			return;
		}

		let target = null;

		try {
			target = doc.getElementById( decodeURIComponent( url.hash.slice( 1 ) ) );
		} catch ( error ) {
			return;
		}

		if ( ! target ) {
			return;
		}

		event.preventDefault();
		goTo( target );

		if ( window.location.hash !== url.hash ) {
			window.history.pushState( null, '', url.hash );
		}
	} );
}

/* Motion layer: fetched once the browser is idle, never with reduced motion. */
let motionLoading = false;

function loadMotion() {
	const tag = doc.querySelector( 'script[data-edst-motion]' );
	const src = tag ? tag.getAttribute( 'data-edst-motion' ) : '';

	if ( ! src || motionLoading || reduceMotion.matches || inEditor() ) {
		return;
	}

	motionLoading = true;

	const add = () => {
		const script = doc.createElement( 'script' );

		script.src = src;
		script.async = true;
		doc.head.appendChild( script );
	};

	if ( 'requestIdleCallback' in window ) {
		window.requestIdleCallback( add, { timeout: 2500 } );
	} else {
		window.setTimeout( add, 1200 );
	}
}

/* Start everything inside one part of the page. */
function initScope( scope ) {
	initPanels( scope );
	initRails( scope );
	initFilters( scope );
	initFaqNav( scope );
	initCounters( scope );
	initPausers( scope );
}

function initSite() {
	initAnnounce();
	initHeader();
	initMobile();
}

initSite();
initScope( doc );
initAnchors();

if ( 'complete' === doc.readyState ) {
	loadMotion();
} else {
	window.addEventListener( 'load', loadMotion, { once: true } );
}

// Elementor renders widgets after load in the editor and in popups.
let elementorHooked = false;

const hookElementor = () => {
	const frontend = window.elementorFrontend;

	if ( elementorHooked || ! frontend || ! frontend.hooks ) {
		return;
	}

	elementorHooked = true;
	frontend.hooks.addAction( 'frontend/element_ready/global', ( $scope ) => {
		const element = $scope && $scope[ 0 ];

		if ( ! element ) {
			return;
		}

		if ( element.querySelector( '[data-edst-header], [data-edst-announce]' ) ) {
			initSite();
		}

		initScope( element );
	} );
};

window.addEventListener( 'elementor/frontend/init', hookElementor );
hookElementor();
