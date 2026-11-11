# Technical architecture

## Environment (verified 10 November 2026)

| Item | Value |
|---|---|
| Hosting | Hostinger Premium shared plan, account `u584021243`, LiteSpeed |
| Production | `edshieldsolutions.com`, WordPress installation 28176794 |
| Staging | `staging.edshieldsolutions.com`, installation 31502311, folder `public_html/staging` |
| WordPress | 7.1.4 |
| PHP | 8.5.12 (memory and upload limits 1536M, max execution 360 s) |
| Database | MariaDB 11.8.9; staging uses `u584021243_BkzJw` |
| Caching | no page-cache plugin, object cache off |
| Builders | Elementor 4.3.6, Elementor Pro 4.2.3 (licence mismatch on staging) |
| Own plugins | EDShield Attendance Pro 4.8.3, ED Shield Cost Manager 1.0.0, EDShield Dashboard 1.0.0, EDS Smart Analytics 1.0.0 |

## Website: theme 1.0.0

```
Browser
  assets/css/main.css    one stylesheet (12.5 KB gzip)
  assets/js/main.js      header, menus, sliders, filter, FAQ, counters (3.7 KB gzip, deferred)
  assets/js/motion.js    GSAP + ScrollTrigger, fetched on idle, never with reduced motion

WordPress theme "edshield"
  inc/content.php            company content: 9 services, industries, process, FAQs
  inc/sections/definitions   23 section schemas: fields, defaults, common style fields
  inc/sections/render        one renderer per section
  inc/layouts.php            starter layouts for 20 pages (lists of sections + overrides)
  inc/elementor.php          locations, category, widget registration, page data import
  inc/elementor-widgets.php  one widget per section, controls generated from the schema
  inc/nav.php                mega menu, dropdowns, mobile menu (from WordPress menus)
  inc/projects.php           post type edst_project (/work/<slug>/) + details box
  inc/contact.php            enquiry form, post type edst_lead, mail, signed webhook
  inc/admin.php              ED Shield Setup screen (import, Elementor tools)
  templates                  front-page, page, single, single-edst_project, index, 404, Full width
```

### One schema, three uses

Each section type is defined once (`section_definitions()`). The same schema
produces:

1. the Elementor widget's controls (text, textarea, URL, media, select,
   switch, repeater) and their defaults;
2. the theme's own rendering when Elementor is absent or a page is not built
   in Elementor;
3. the Elementor data written by the importer (one full-width container per
   section, zero padding and gap).

A test renders every page's Elementor data through the section renderers and
compares it with the theme's own output: identical structure for all 19 pages
and 126 widgets.

### Decisions

- **Classic PHP theme with `theme.json`**: full control of markup and the best
  fit with Elementor Pro's Theme Builder.
- **Sections, not page templates**: every page is a list of sections, so a page
  edited in Elementor uses exactly the components the theme renders.
- **Tokens on `body` with Elementor fallbacks**: `--edst-blue:
  var(--e-global-color-edstblue, #2a4bff)`. Changing the ED Shield colours in
  Elementor's Global Colors recolours the theme.
- **Component selectors prefixed with `.edst`** (body class) so Elementor kit
  styles cannot override them; form fields use `.edst.edst` to beat kit field
  selectors.
- **Containers holding an ED Shield widget get no default padding, gap or boxed
  width** (`:where(.e-con):has(...)`), so a widget dropped into a new container
  looks right. Values set in Elementor still win.
- **Theme Builder header support**: if a Pro header template contains the Site
  header widget, the template wrapper becomes the sticky element.
- **Leads in WordPress, automation outside it**: the form stores a private
  `edst_lead` post and can POST a signed JSON webhook to n8n.
- **No required plugins**: Elementor is optional; without it the theme renders
  the same pages.
- **Namespace `EDShield\Theme`, prefix `edst`**: avoids collisions with the
  site's existing `edshield-*` plugins.

## Planned, not built (proposal for review)

| Part | Proposal | Reason |
|---|---|---|
| BusinessOS | Separate web application with its own database | Business data should not live in WordPress |
| Automation | n8n receiving the website's signed webhooks | Decouples the site from the CRM |
| CRM | GoHighLevel as the system of record for leads and pipeline | One source of truth for sales |

Attendance, cost and dashboard functions already exist as WordPress plugins with
their own data. Decide whether they move into BusinessOS before designing it.

## Constraints that shape deployment

- Staging is a folder inside production's document root and is not a separate
  Hostinger website. See `SECURITY_NOTES.md`.
- No SSH from the build sandbox. Code reaches staging as a zip installed by the
  owner.
