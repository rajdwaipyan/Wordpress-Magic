# Security notes

## Production safety

| Fact | Consequence |
|---|---|
| Production is WordPress installation 28176794 at `edshieldsolutions.com`; staging is installation 31502311 at `staging.edshieldsolutions.com` | Any Hostinger WordPress operation must name 31502311, never 28176794 |
| Staging files are at `public_html/staging`, inside production's document root | A wrong path in a file or Git operation would write into production's tree |
| Hostinger does not list staging as its own website | Domain-addressed operations (Git deploy, theme and plugin deploy, cache clear) name the production website: not used without explicit approval |
| PHP version, server cache, CDN and SSL are set per website | They are shared with production and are never changed |
| Staging database is `u584021243_BkzJw`; production's is a different database | Content written on staging cannot reach production |

The same hosting account also holds `ieltsparadise.com` and
`edlearningpoint.com`, and sites from other accounts are visible through shared
access. None of them is part of this project.

## Changes made on staging

| Date | Change | Reason | Undo |
|---|---|---|---|
| 10 November 2026 | Deactivated the plugin Bravis Addons (installation 31502311 only) | With the ED Shield theme active it stopped pages and the feed with a fatal error; it requires the Biznax theme | Reactivate it under Plugins (only with Biznax active) |

Bravis User and Redux Framework are still active on staging. Theme 1.0.0's
setup screen offers to deactivate the Bravis companions if they cause trouble.

## Credentials

- A WordPress login for a `claudeadmin` account was pasted into the chat on
  10 November 2026. Treat it as exposed and change it wherever it is used. It is
  not recorded anywhere.
- No secret is stored in this repository. Do not add any.
- The lead webhook secret is entered in the Customizer and stored in the
  database (theme mods), never in code.

## Theme 1.0.0

| Area | Measure |
|---|---|
| Output | Escaped at render; rich text passes `wp_kses_post` |
| Enquiry form | Nonce, honeypot field, 3-second time trap, consent, five messages per hour per visitor (hashed IP in a transient), sanitised fields, header-safe Reply-To |
| Leads | Private posts of a non-public post type, no REST exposure, visible and editable by administrators only |
| Webhook | Optional; HMAC-SHA256 signature header; URL validated; non-blocking |
| Admin actions | Setup tasks require `edit_theme_options` (plugin deactivation also `activate_plugins`) and a nonce; page reset needs an explicit confirmation tick |
| Remote requests | None, except the webhook the owner configures |
| Front-end assets | All self-hosted; no third-party fonts, scripts or trackers |
| Contact details | Printed only when entered in the Customizer |
