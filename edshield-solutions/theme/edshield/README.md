# ED Shield theme

Multi-page corporate WordPress theme for ED Shield Solutions. Version 1.0.0.

- 20 ready pages: Home, Services, nine service pages, Work, Industries, About,
  Process, Engagement models, FAQ, Careers, Contact and Insights (blog).
- Case studies (Projects), articles, archives, search and 404 templates.
- Sticky header with a Services mega menu, dropdowns, announcement bar and a
  full-screen mobile menu. Large footer with the ED SHIELD wordmark.
- 23 reusable sections. Each one is an Elementor widget ("ED Shield" category)
  and is also rendered by the theme itself when Elementor is not installed.
- Elementor Theme Builder locations (header, footer, single, archive), plus
  Site header and Site footer widgets for Pro templates.
- Built-in enquiry form with spam protection, saved leads and an optional
  signed webhook (for n8n, Zapier and similar).
- No required plugins, no jQuery, no third-party requests. Self-hosted fonts.
  CSS 12.5 KB and core script 3.7 KB (gzip); the motion layer loads on idle.

## Requirements

- WordPress 6.6 or later (tested on 7.1.4)
- PHP 8.0 or later
- Optional: Elementor 3.5 or later (checked against the 4.0.8 source); Elementor Pro for Theme Builder

## Install

1. Appearance › Themes › Add New Theme › Upload Theme, choose `edshield-1.0.0.zip`.
2. Live Preview first, then Activate. Activation changes no content.
3. Appearance › **ED Shield Setup** › **Create pages and menus**.
   - Creates only pages that do not exist yet (existing pages are never changed).
   - Optionally sets Home as the front page and Insights as the posts page,
     creates the primary menu (with the Services mega menu) and footer menus,
     and adds three clearly marked placeholder case studies.
   - With Elementor active, the new pages are built in Elementor: every section
     becomes an editable ED Shield widget.
   - Sets permalinks to "Post name" when they are still "Plain".
4. Appearance › Customize › **ED Shield**: contact details, social links,
   header button, announcement bar, mega menu card and footer texts.

If the site still runs another theme's companion plugins (for example Bravis
Addons, which stops the site when its own theme is not active), the setup screen
offers to deactivate them.

## Editing pages

**With Elementor:** open a page in Elementor and edit any ED Shield widget:
texts, links, images, repeater items (cards, steps, questions), and on the Style
tab the section tone (ink, charcoal, paper, white, bone), spacing and anchor.
Drag new ED Shield widgets from the panel; a container holding one needs no
padding or width settings. **ED Shield Setup › Reset page layout** restores a
page's starter layout (it asks for confirmation).

**Without Elementor:** pages created by the setup screen render their starter
layout until you add your own content in the block editor; from then on the
page shows the standard page header and your content.

## Sections (Elementor widgets)

Home hero, Page hero, Services grid, Features, Industries, Services list, Work
(case-study rail or filterable grid), Numbers, Testimonials, Steps, Numbered rows,
Latest articles, FAQ, FAQ groups, Call to action, Statement with visual, What is
included, Engagement models, Contact, Case study overview, Gallery, Moving strip,
Text block.

Every image slot accepts a photo; without one, the section shows the theme's
original line illustration.

## Menus

- **Primary**: give a top-level item the CSS class `edst-mega` to open a mega
  panel (its children show icons and descriptions). Other parents open a
  dropdown. A child with the class `edst-view-all` becomes the panel's footer
  link; `edst-icon-<name>` (for example `edst-icon-shield`) sets an icon. Turn
  on *CSS Classes* and *Description* in Screen Options.
- **Footer company** and **Legal**: flat lists. The footer services column
  lists the nine service pages automatically.

## Case studies

Projects › Add New. Fill in the **Case study details** box (client, industry,
services, year, website, headline result, challenge, goal, objectives, results
and the illustration used when there is no featured image). Build the body in
Elementor or the block editor. Placeholders created by the setup screen are
marked "Placeholder" and should be replaced or deleted before launch.

## Contact form and leads

The Contact widget, the Contact page and `[edst_contact_form]` show the form.
Submissions are saved under **Leads** (private) and emailed to the contact email
set in the Customizer, or to the site admin email. To use a form plugin instead, enter its shortcode in Customize ›
ED Shield › Contact. For automation, set a webhook URL and secret: each lead is
POSTed as JSON with the header `X-EDShield-Signature: sha256=<HMAC>`. Developers
can also hook `edst_lead_submitted`.

## Elementor Pro Theme Builder

Templates assigned to the header, footer, single or archive locations replace
the theme's own markup. **ED Shield Setup › Create header and footer templates**
creates Pro templates that contain the theme header (with mega menu) and footer,
ready for display conditions. **Match Elementor to the theme** adds the ED Shield
colours to Global Colors and turns off Elementor's default colours and fonts.

## Filters

| Filter | Purpose |
|---|---|
| `edst_services` | Change the nine service definitions (titles, texts, items, FAQs) |
| `edst_enable_projects` | Return false to disable the Projects post type |
| `edst_meta_description` | Change the generated meta description |
| `edst_is_app_page` | Give a page the full-width content column |
| `edst_content_width` | Embed width |

## Development

Sources are in `src/`, compiled files in `assets/`.

```bash
npm install
npm run build
```

`npm run watch` rebuilds on change. PHP needs no build step.

| Path | Contents |
|---|---|
| `inc/content.php` | Company content: services, industries, process, FAQs |
| `inc/sections/definitions.php` | Section schemas (fields and defaults) |
| `inc/sections/render.php` | Section markup |
| `inc/layouts.php` | Starter layouts for every page |
| `inc/elementor*.php` | Elementor widgets, locations and data import |
| `inc/nav.php` | Mega menu, dropdowns, mobile menu |
| `src/css/*.css` | Tokens, base, components, header, sections, footer, content, compat |
| `src/js/main.js` | Header, menus, sliders, filters, FAQ, counters, anchors |
| `src/js/motion.js` | GSAP reveal, hero depth and magnetic buttons |

## Content rules

The theme ships with verified company information only. Testimonials, client
logos and case studies are clearly marked placeholders; engagement models show
"Scoped after discovery" instead of prices. Replace placeholders with real,
approved content before launch.

## License

GPL-2.0-or-later. Fonts: Newsreader and IBM Plex (SIL Open Font License 1.1).
GSAP is free for commercial use under its standard license.
