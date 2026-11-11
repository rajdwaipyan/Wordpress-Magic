# QA report

## Theme 1.1.0, tested 11 November 2026

Local WordPress 7.1.4 (PHP 8.3, MariaDB 10.11) in the build sandbox, each run on
a fresh install. Chromium only. **Not yet tested on staging.**

### One-click import

| Route | Result |
|---|---|
| Appearance › Import Demo Data, Elementor not active | 32 pages, 4 articles, 6 placeholder case studies, 4 menus assigned, 5 widgets, theme settings, site icon, front page and blog set; about 1 second |
| Same screen with Elementor 4.0 active | 31 pages built as Elementor pages (Insights is the blog), Elementor Site Settings updated (ED Shield global colours and fonts) |
| One Click Demo Import 3.4 screen with Elementor active | "Import Complete!"; identical result through the theme's importer |
| Second run | Nothing duplicated; reports what already existed |
| Default WordPress content | Untouched "Hello world!", "Sample Page" and the Privacy Policy draft moved to the Trash (restorable); default widgets moved to Inactive widgets |

### Every imported page

All 48 addresses (32 pages, 4 articles, 6 case studies, 5 category archives,
search, 404) at 1440 px and 390 px:

| Check | Theme rendering | Elementor rendering |
|---|---|---|
| HTTP status | 200 (404 page returns 404) | 200 |
| PHP notices or warnings from the theme | none | none |
| Browser errors from the theme | none | none |
| Horizontal overflow | none (one article-sidebar overflow found and fixed) | none |
| Content left hidden by animations | none | none |
| One h1 per page | yes | yes |
| Internal links | 48 checked, none broken | 49 checked, none broken |

The Elementor runs used Elementor's source code, which has no compiled
front-end files. Each Elementor page therefore logged 404s for Elementor's own
`frontend.min.css` and three of its scripts; those requests are not made by the
theme and resolve on a normal Elementor install.

### Elementor

| Check | Result |
|---|---|
| Widgets registered | 25 in the "ED Shield" category |
| Controls in the editor context | content controls and the Style tab (colours, typography, background, padding, width, radius) on every section widget |
| CSS generated from style controls | correct rules for colour tokens and padding |
| Saving a page through Elementor's document API | new heading and background colour shown on the live page |
| Page data | 32 pages, 238 widgets: same structure as the theme rendering, every link resolved to a real address |

### Interactions

Mega menu (hover, keyboard, Escape), dropdowns, header hide and show, mobile
menu (under the header, scroll lock), announcement close persisting across
reloads, sliders, work filter, FAQ accordion and category navigation, smooth
anchors, contact form and website audit form (stored as leads, success notice
shown at the form that was sent): all pass.

### Not tested

- The Elementor editor interface itself (no compiled editor assets in the
  sandbox); the controls, rendering and saving behind it were tested.
- Installing plugins from wordpress.org through the screen (no access to
  wordpress.org from the sandbox).
- Staging (PHP 8.5, LiteSpeed, Elementor 4.3.6, Elementor Pro 4.2.3).
- Email delivery, a live webhook receiver, Safari, Firefox, real devices,
  screen readers.
