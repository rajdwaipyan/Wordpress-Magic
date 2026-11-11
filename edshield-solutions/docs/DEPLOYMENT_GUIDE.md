# Deployment guide

## Rule

All work goes to `staging.edshieldsolutions.com`. Production
`edshieldsolutions.com` is never touched without the owner's explicit approval.

## The package

`edshield-solutions/release/edshield-1.0.0.zip` in the repository (also built by
`tools/zip.sh` into `dist/`). It contains the compiled theme and its sources;
`node_modules` is excluded.

## Installing 1.0.0 on staging (done by the site owner)

1. Back up staging (files and database).
2. Staging wp-admin › Appearance › Themes › Add New Theme › Upload Theme,
   choose `edshield-1.0.0.zip`, then **Replace current with uploaded** (0.1.0 is
   installed there).
3. Appearance › **ED Shield Setup**:
   - tick "Build the new pages in Elementor" so every page is editable in
     Elementor;
   - leave "Set Home as the front page" ticked only if staging should show the
     new homepage;
   - **Create pages and menus**. Existing pages are never changed.
4. On the same screen: **Match Elementor to the theme** (adds the colours to
   Global Colors, turns off Elementor's default fonts and colours) and, with
   Elementor Pro, optionally **Create header and footer templates**.
5. Appearance › Customize › ED Shield: contact email, phone, WhatsApp, address,
   hours, social links, header button, announcement text, lead webhook.
6. Settings › General: site title (currently "edshieldsolutions").
7. Elementor › Tools › Regenerate CSS & Data if the old Biznax styles linger.

## Things to watch on staging

- Existing pages built with Biznax widgets will not look right under the new
  theme. The new pages are created next to them; nothing is deleted.
- 1.0.0 no longer includes the 0.1.0 "ED Shield" Gutenberg blocks. If any page
  was built with them, recreate it with the setup screen or in Elementor.
- Bravis User and Redux Framework (Biznax companions) are still active. If they
  log errors, the setup screen offers to deactivate the Bravis plugins.
- Debug logging is on at staging: check `wp-content/debug.log` after the first
  visits.
- Elementor Pro's licence shows "Mismatch" on staging; Theme Builder may be
  limited until it is fixed.

## Rollback

Appearance › Themes › activate the previous theme. Pages, menus and placeholder
projects created by the setup screen remain and can be deleted. If the setup
screen changed the front page, set it back under Settings › Reading.

## Updating the theme later

Build with `npm run build`, package with `tools/zip.sh`, upload the zip and
choose "Replace current with uploaded".

## Deployment routes that exist but are not used

Hostinger does not treat staging as a website of its own: it is the folder
`public_html/staging` inside production's document root. Hostinger's Git
deployment and deploy operations are addressed by domain and would have to name
the production website, so they are not used without explicit approval for each
use. Hostinger settings for PHP, cache, CDN and SSL are shared with production
and must never be changed for staging's sake.
