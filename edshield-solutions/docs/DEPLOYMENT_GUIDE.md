# Deployment guide

## Rule

All work goes to `staging.edshieldsolutions.com`. Production
`edshieldsolutions.com` is never touched without the owner's explicit approval.

## The package

`edshield-solutions/release/edshield-1.1.0.zip` in the repository (also built
by `tools/zip.sh` into `dist/`). It contains the compiled theme, its sources and
the demo package; `node_modules` is excluded.

## Installing 1.1.0 on staging (done by the site owner)

1. Back up staging (files and database).
2. Staging wp-admin › Appearance › Themes › Add New Theme › Upload Theme,
   choose `edshield-1.1.0.zip`, then **Replace current with uploaded**.
3. Make sure Elementor is active (it is on staging).
4. Appearance › **Import Demo Data** (called **ED Shield Demo** if the One Click
   Demo Import plugin is active; its own Import Demo Data screen also works).
5. Keep the defaults. Tick **Move existing pages that use the same addresses to
   the Trash** if staging already has pages such as /about/, /services/ or
   /contact/ from the previous theme and the demo pages should take those
   addresses. Click **Import demo**.
6. Customize › ED Shield: contact email, phone, WhatsApp, address, hours, social
   links, lead webhook.
7. Settings › General: site title (currently "edshieldsolutions").

## Things to watch on staging

- Existing pages built with Biznax widgets will not look right under the new
  theme. The import does not change them.
- Bravis User and Redux Framework (Biznax companions) are still active. If they
  log errors, the import screen offers to deactivate the Bravis plugins.
- Debug logging is on at staging: check `wp-content/debug.log` after the first
  visits.
- Elementor Pro's licence shows "Mismatch" on staging; Theme Builder may be
  limited until it is fixed. The theme header and footer do not need it.
- Elementor › Tools › Regenerate CSS & Data if old styles linger.

## Rollback

Appearance › Themes › activate the previous theme. Imported pages, posts,
menus and widgets remain and can be deleted; anything the import moved to the
Trash can be restored. If the import changed the front page, set it back under
Settings › Reading.

## Updating the theme later

Build with `npm run build`, regenerate the demo package with
`php tools/export-demo.php /path/to/wordpress` if layouts changed, package with
`tools/zip.sh`, upload the zip and choose "Replace current with uploaded".

## Deployment routes that exist but are not used

Hostinger does not treat staging as a website of its own: it is the folder
`public_html/staging` inside production's document root. Hostinger's Git
deployment and deploy operations are addressed by domain and would have to name
the production website, so they are not used without explicit approval for each
use. Hostinger settings for PHP, cache, CDN and SSL are shared with production
and must never be changed for staging's sake.
