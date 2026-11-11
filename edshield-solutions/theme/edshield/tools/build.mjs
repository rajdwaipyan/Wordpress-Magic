/**
 * ED Shield theme build.
 *
 *   npm install
 *   npm run build     one-off production build
 *   npm run watch     rebuild on change
 *
 * Sources live in src/, compiled files in assets/. Four outputs:
 *
 *   assets/css/main.css    front-end stylesheet
 *   assets/css/editor.css  block editor stylesheet
 *   assets/js/main.js      core behaviour (no dependencies)
 *   assets/js/motion.js    GSAP + ScrollTrigger effects, loaded on idle
 */
import { build, context } from 'esbuild';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const root = path.resolve( path.dirname( fileURLToPath( import.meta.url ) ), '..' );
const watch = process.argv.includes( '--watch' );

const shared = {
	absWorkingDir: root,
	bundle: true,
	minify: true,
	legalComments: 'none',
	logLevel: 'info',
	target: [ 'es2019', 'chrome90', 'firefox90', 'safari14' ],
};

const jobs = [
	{ entryPoints: [ 'src/css/main.css' ], outfile: 'assets/css/main.css', external: [ '../fonts/*' ] },
	{ entryPoints: [ 'src/css/editor.css' ], outfile: 'assets/css/editor.css', external: [ '../fonts/*' ] },
	{ entryPoints: [ 'src/js/main.js' ], outfile: 'assets/js/main.js', format: 'iife' },
	{ entryPoints: [ 'src/js/motion.js' ], outfile: 'assets/js/motion.js', format: 'iife' },
].map( ( job ) => ( { ...shared, ...job } ) );

if ( watch ) {
	const contexts = await Promise.all( jobs.map( ( job ) => context( job ) ) );

	await Promise.all( contexts.map( ( ctx ) => ctx.watch() ) );
	console.log( 'Watching src/ for changes. Press Ctrl+C to stop.' );
} else {
	await Promise.all( jobs.map( ( job ) => build( job ) ) );
}
