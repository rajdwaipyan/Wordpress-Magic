# ED Shield theme

Multi-page corporate WordPress theme for ED Shield Solutions, with one-click
demo import. Version 1.1.3.

## What you get

- **One-click demo import** (Appearance › Import Demo Data): every page with its
  design, menus, widgets, articles, placeholder case studies, theme settings,
  site icon and Elementor design settings.
- **32 pages** following the structure of the reference site:
  Home · Services and nine service pages · For Startups · For Growing Businesses ·
  Work and case studies · Industries and six industry pages · About · Our Process ·
  Pricing · FAQ · Careers · Contact · Insights (blog) · Website Audit ·
  Technology · Privacy Policy · Terms of Service.
- **Header and footer**: announcement bar, sticky header that hides on scroll,
  Services mega menu, Resources and Company dropdowns, full-screen mobile menu,
  four-column footer with the ED SHIELD wordmark.
- **23 reusable sections**, each an Elementor widget ("ED Shield" category) with
  content controls and a Style tab (colours, typography, background, padding,
  width, corner radius). Without Elementor the theme renders the same pages.
- **Elementor Theme Builder** locations, plus Site header and Site footer widgets.
- **Enquiry form** with spam protection, saved leads (administrators only) and an
  optional signed webhook for n8n or similar.
- Navy and gold brand palette, Geist and IBM Plex Mono self-hosted, no
  third-party requests. CSS about 13 KB and core script 3.7 KB (gzip); the
  motion layer loads on idle.

## Requirements

- WordPress 6.6 or later (tested on 7.1.4), PHP 8.0 or later.
- **Elementor** (free) for visual editing. Tested with Elementor 4.0.
- Optional: Elementor Pro (Theme Builder templates), One Click Demo Import (the
  theme has its own importer, and also registers its demo with that plugin).

## Install and import

1. Appearance › Themes › Add New Theme › Upload Theme › `edshield-1.1.3.zip` ›
   Install Now › Activate. **If an older ED Shield is already installed,
   WordPress says "Destination folder already exists": click
   Replace active with uploaded**, otherwise nothing changes.
2. Appearance › **Import Demo Data**.
3. Under **1. Plugins**, click **Install and activate** for Elementor (or
   activate it if already installed).
4. Under **2. Import**, keep the defaults and click **Import demo**. It takes a
   few seconds. Pages are created as Elementor pages.
5. Customize › ED Shield: add your email, phone, address and social links.

The import never edits or deletes your existing content. It only adds what is
missing, so it can be run again safely. Options on the screen:

| Option | Default | Effect |
|---|---|---|
| Pages and their designs | on | Creates the 32 pages (Elementor pages when Elementor is active) |
| Articles and placeholder case studies | on | 4 starter articles, 6 case studies marked "Placeholder" |
| Menus | on | Main menu (mega menu), footer company, resources and legal menus, assigned to the theme's menu locations. A menu that held a location before is kept under Appearance › Menus |
| Widgets | on | Footer contact column and article sidebar. Widgets already in those areas move to Inactive widgets |
| Theme settings | on | Header button, announcement bar, mega menu card, footer texts, site icon, Elementor colours and fonts. Settings you already changed are kept |
| Front page | on | Home becomes the front page, Insights the blog |
| Sample content | on | Moves WordPress's untouched "Hello world!" and "Sample Page" to the Trash |
| Replace pages with the same address | off | Moves existing pages such as /about/ to the Trash so the demo pages get clean addresses |

WordPress's own unedited "Privacy Policy" draft is moved to the Trash so the
demo Privacy Policy keeps the /privacy-policy/ address. Everything moved to the
Trash can be restored.

**With One Click Demo Import active**, its Appearance › Import Demo Data screen
lists "ED Shield — Complete website" and runs the same importer; the theme's
own screen is then called **ED Shield Demo**.

**Activated Elementor after importing?** Use **Build pages in Elementor** on the
import screen.

## Editing pages

Open any page with **Edit with Elementor**. Every section is an ED Shield widget:

- **Content tab**: texts, buttons and links, images (each slot replaces the
  illustration), repeaters for cards, steps, questions, plans and testimonials.
- **Style tab**: section tone and spacing; colours (background, text, accent,
  highlighted words, cards, lines, brand accent); typography for headings,
  titles, body text and labels; background image or gradient; padding; content
  width; card corner radius.
- Native Elementor widgets can be placed between sections.

**Reset page layout** on the import screen restores one page's demo design.

## Menus and widgets

- **Primary**: a top-level item with the CSS class `edst-mega` opens a mega
  panel; other parents open a dropdown. A child with `edst-view-all` becomes the
  panel's footer link; `edst-icon-<name>` sets an icon (for example
  `edst-icon-shield`); the description becomes the subtitle. Turn on *CSS
  Classes* and *Description* in Screen Options.
- **Footer company**, **Footer resources**, **Footer legal**: flat lists.
- **Widget areas**: *Footer: get in touch* (replaces the footer contact column)
  and *Article sidebar* (beside blog articles on wide screens).

## Case studies, testimonials and legal pages

Projects › Add New, then fill in the **Case study details** box (client,
industry, services, year, website, headline result, challenge, goal,
objectives, results, illustration). Replace or delete the six placeholders
before launch. Testimonials are placeholders in the Testimonials sections.
The Privacy Policy and Terms of Service pages are templates to complete.

## Contact form and leads

The Contact and Website Audit pages, the Contact widget and `[edst_contact_form]`
show the form. Submissions are saved under **Leads** and emailed to the contact
email in the Customizer (or the admin email). To use a form plugin instead,
enter its shortcode in Customize › ED Shield › Contact. For automation, set a
webhook URL and secret: each lead is POSTed as JSON with the header
`X-EDShield-Signature: sha256=<HMAC>`. Developers can hook `edst_lead_submitted`.

## Elementor Pro Theme Builder

Theme Builder templates replace the theme's header, footer, single and archive
templates only when you choose so: Customize › ED Shield › Header and menu ›
*Use Elementor Pro Theme Builder templates*, or the switch on the import screen.
This keeps templates left over from a previous theme from hiding the ED Shield
header and footer. **Create header and footer templates** on the import screen
makes Pro templates that contain the theme header and footer and turns the
switch on.

## Demo package

`demo/` holds the package as JSON: `content.json` (pages with their sections,
articles, case studies), `menus.json`, `widgets.json`, `customizer.json`,
`settings.json`, `elementor-kit.json`, plus `preview.jpg` and
`assets/site-icon.png`. Links are stored as tokens (`page:contact`,
`service:web`) and turned into real addresses on import, so the package works on
any domain. Regenerate it after changing layouts or content:

```bash
php tools/export-demo.php /path/to/wordpress
```

## Filters and hooks

| Name | Purpose |
|---|---|
| `edst_services` | Change the nine service definitions |
| `edst_enable_projects` | Return false to disable the Projects post type |
| `edst_meta_description` | Change the generated meta description |
| `edst_is_app_page` | Give a page the full-width content column |
| `edst_content_width` | Embed width |
| `edst_lead_submitted` (action) | After a lead is stored and sent |

## Development

Sources are in `src/`, compiled files in `assets/`.

```bash
npm install
npm run build
```

| Path | Contents |
|---|---|
| `inc/content.php`, `inc/content-pages.php` | Company content: services, audiences, industries, resources, articles |
| `inc/sections/definitions.php` | Section schemas (fields and defaults) |
| `inc/sections/render.php` | Section markup |
| `inc/layouts.php` | The layout of every page |
| `inc/demo.php` | Demo package and importer |
| `inc/admin.php` | Import Demo Data screen, plugin installer, One Click Demo Import bridge |
| `inc/elementor*.php` | Elementor widgets, style controls, locations, page data |
| `inc/nav.php` | Mega menu, dropdowns, mobile menu |
| `src/css/*.css` | Tokens, base, components, header, sections, footer, content, compat |
| `src/js/main.js` | Header, menus, sliders, filters, FAQ, counters, anchors |
| `src/js/motion.js` | GSAP reveal, hero depth, magnetic buttons |

## Content rules

The theme ships with verified company information only. Testimonials, client
names and case studies are clearly marked placeholders; pricing shows how work
is scoped instead of figures. Replace placeholders with real, approved content
before launch.

## License

GPL-2.0-or-later. Fonts: Geist and IBM Plex Mono (SIL Open Font License 1.1).
GSAP is free for commercial use under its standard license.
