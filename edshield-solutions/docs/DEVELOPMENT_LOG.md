# Development log

## 10 November 2026

### Audit

- Checked which tools and connections work in the build environment.
- Read-only audit of staging through its public pages and REST index.
- Baseline Lighthouse run of the current staging homepage: mobile 54, desktop 75.
- Hostinger connector authorised by the owner; identified production and
  staging installations, plugins, themes, databases and PHP configuration.
- Signed in to staging through a Hostinger one-time link for read-only checks:
  MCP endpoint, Site Health, Elementor licence, backups. Signed out afterwards.
- Finding: staging is the folder `public_html/staging` inside production's
  document root and is not a separate Hostinger website.
- Finding: four custom ED Shield plugins hold business functions and data.

### Decision

The owner installs the theme from a zip. No deployment is performed by the
build agent.

### Design

- Studied carrilagency.com with a headless browser (structure, type, colour, motion).
- Chose the "engineering journal" direction: Newsreader, IBM Plex Sans and
  Mono, charcoal and off-white, one electric blue.
- Subset the fonts (103 KB total) and computed metric-matched fallbacks.

### Theme 0.1.0

- Built the theme: 10 blocks, starter homepage, templates, navigation with
  index panels, mobile menu, footer, Customizer options, Elementor locations,
  meta description and Open Graph tags.
- Build tooling: one esbuild script.
- Local test site: WordPress 7.1.4, PHP 8.3.6, MariaDB 10.11.

### Problems found and fixed during testing

| Problem | Cause | Fix |
|---|---|---|
| Work rail started at the viewport edge | Percentage padding resolved against the rail, not the page grid | Edge distance computed from the viewport and grid width |
| Divisions ticker fell below the first screen | Hero taller than the viewport | Smaller display size and tighter hero padding |
| Content stayed hidden when a page opened at an anchor | CSS hid content before the motion script could reveal it | Motion script now hides only what is below the viewport; nothing is hidden by CSS |
| Direct visit to an anchor jumped back to the top | CSS smooth scrolling was interrupted when ScrollTrigger measured the page | Removed CSS smooth scrolling; in-page clicks are smoothed by script |
| Dim statement words failed contrast | Start opacity 0.16 | Start opacity 0.5 (3.48:1) |
| Logo link name did not match its text | `aria-label` set to the site title | Removed; the visible wordmark is the name |
| No meta description | Not implemented | Added, with automatic stand-down when an SEO plugin is active |
| Core table block drew boxed cells | Core block styles | Ruled lines only |
| Shortcode pages squeezed into the reading column | Narrow column applied to every page | Shortcode pages use the full grid width |

### Result

Local Lighthouse: performance 100 and accessibility 100 on mobile and desktop.
Not yet installed or measured on staging.

## 10 to 11 November 2026

### Staging incident

- With theme 0.1.0 active, staging pages and the feed stopped with a fatal
  error. Cause: the Bravis Addons plugin requires the Biznax theme. Deactivated
  Bravis Addons on staging only (Hostinger connector, installation 31502311).
  Home, feed, search and inner pages returned 200 afterwards.

### Theme 1.0.0

- Requirement changed from a single homepage to a complete multi-page site with
  the reference's structure and Elementor editing.
- Studied the reference's page types, mega menu, mobile menu and footer again.
- Rebuilt the theme around one schema per section (23 sections) used by the
  Elementor widgets, the theme's own rendering and the importer.
- Added 20 starter layouts, the Services mega menu, dropdowns, announcement
  bar, mobile menu, footer wordmark, case-study post type, enquiry form with
  leads and signed webhook, setup screen, Theme Builder support.
- Removed the 0.1.0 Gutenberg blocks and homepage pattern (superseded).
- Repository: work committed to `rajdwaipyan/wordpress-magic`.

### Problems found and fixed during testing

| Problem | Cause | Fix |
|---|---|---|
| Lookups returned no page right after the importer created it | Static cache in `layout_page_id()` | Cache refresh after import |
| Mobile menu misplaced | Fixed overlay inside a transformed header | Overlay printed after the header |
| Permalinks still "plain" right after import | Rewrite object not re-initialised | `set_permalink_structure()` |
| FAQ page overflowed on phones | Single-column grid sized to the nowrap category list | `minmax(0, 1fr)` on all single-column grids |
| Third step had no illustration | Icon name used where a drawing key was expected | Drawing key fixed; any icon name now falls back to an icon drawing |
| Illustrations cropped in wide frames | `slice` scaling | Whole drawing (`meet`) on a CSS grid that runs edge to edge |
| Case-card text over busy artwork | Artwork filled the whole card | Artwork limited to the top two thirds with a solid fade |
| Technology strip squeezed on phones | Strip could not wrap below its label | Flexible basis lets it wrap |
| FAQ category stayed highlighted after scrolling back up | Observer only fired inside a narrow band | Highlight computed from scroll position |
| Contact text pointed to details that were not set | Fixed default copy | Copy no longer refers to them |
| Work and Insights not highlighted on case studies and articles | Menu context unknown for those views | Section page marked current |
| Leads visible to editors | Post capabilities | Administrators only |

## 11 November 2026 (theme 1.1.0)

### Feedback

The owner saw only a homepage after installing 1.0.0 (the pages were created by
a setup screen they had not found) and asked for the reference site's complete
structure, its visual design and a standard one-click demo import.

### Changes

- One-click demo import at Appearance › Import Demo Data: required-plugin
  install and activation, import options, results with view and Elementor edit
  links. Demo package in `demo/` (JSON), written by `tools/export-demo.php`.
- One Click Demo Import bridge: the demo appears on that plugin's screen and runs
  the same importer.
- Page inventory matched to the reference: audience pages, six industry pages,
  pricing, website audit, technology, privacy and terms (32 pages); six
  placeholder case studies; four starter articles.
- Navigation matched to the reference: Work, Services (mega), Resources, Company.
- Rebrand to the ED Shield logo colours (navy and gold) with Geist type and the
  reference's navy hero with a diagonal band; reference-style blog cards.
- Elementor Style tab on every section widget (colours, typography, background,
  padding, width, radius).
- Widget areas (footer contact column, article sidebar), with demo widgets.

### Problems found and fixed during testing

| Problem | Cause | Fix |
|---|---|---|
| Demo Privacy Policy got /privacy-policy-2/ | WordPress's default draft holds the address | The untouched default draft moves to the Trash first |
| Demo widgets not added | WordPress put its default widgets into the theme's first widget area | Existing widgets move to Inactive widgets, once |
| "Uncategorized" pill and "Hello world!" in the blog | WordPress sample content | Untouched sample post and page move to the Trash (option, on by default) |
| Articles scrolled sideways by 30 px | Article sidebar grid track sized to its widest widget | `minmax(0, 1fr)` track |
| Industries linked twice in the navigation | Listed under Resources and Company | Kept under Company only |
| Form on the audit page returned to the wrong place | Redirect anchor fixed to the contact page's form | Each form returns to its own anchor |
| Style controls seemed missing in a CLI check | Elementor keeps CSS-only controls separately on front-end requests | Verified in the editor context instead; no change needed |
