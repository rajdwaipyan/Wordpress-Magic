# Integration guide

Last updated: 11 November 2026.

## Connections

| System | State | Notes |
|---|---|---|
| Hostinger | Connected (official connector, OAuth) | Sites, installations, plugins, themes, databases, PHP settings; one-time wp-admin sign-in links. No backup or staging operations for shared hosting |
| Staging WordPress MCP endpoint | Answers | `/wp-json/mcp/mcp-adapter-default-server`, three read-only abilities |
| GitHub | Connected | Repository `rajdwaipyan/wordpress-magic`, folder `edshield-solutions/` |
| One Click Demo Import (plugin, optional) | Supported | The theme registers its demo (`ocdi/import_files`), runs its own importer after import (`ocdi/after_import`) and asks the plugin to install Elementor (`ocdi/register_plugins`) |
| n8n | Not connected | |
| GoHighLevel | Not connected | |
| Telegram | Not connected | |

## Website lead capture (built in theme 1.0.0)

Every enquiry from the Contact widget, the Contact and Website Audit pages or `[edst_contact_form]`:

1. passes nonce, honeypot, a 3-second time trap, consent and a limit of five
   messages per hour per visitor;
2. is stored as a private **Lead** (post type `edst_lead`);
3. is emailed to the contact email from the Customizer (or the admin email);
4. is POSTed to the webhook URL from Customize › ED Shield › Contact, if set.

Webhook request:

```
POST <webhook URL>
Content-Type: application/json
X-EDShield-Signature: sha256=<HMAC-SHA256 of the raw body, keyed with the secret>

{
  "event": "lead.created",
  "lead_id": 123,
  "site": "https://…/",
  "time": "2026-11-11T06:30:00+00:00",
  "lead": { "name", "email", "company", "phone", "service", "message", "source" }
}
```

In n8n: Webhook node → Code node that recomputes the HMAC over the raw body and
rejects mismatches → the rest of the flow. The request is non-blocking, so a
slow automation never delays the visitor.

To use a form plugin instead, enter its shortcode in Customize › ED Shield ›
Contact; the theme then shows that form and stores nothing itself.

## Hooks for developers

| Hook | Type | Purpose |
|---|---|---|
| `edst_lead_submitted` | action | `( int $lead_id, array $data )` after a lead is stored and sent |
| `edst_services` | filter | Change the nine service definitions |
| `edst_enable_projects` | filter | Disable the case-study post type |
| `edst_meta_description` | filter | Override the meta description |
| `edst_is_app_page` | filter | Full-width content column for a page |

## Planned flow (proposal)

```
Website form  ->  signed webhook  ->  n8n
n8n  ->  AI qualification  ->  GoHighLevel contact and opportunity
n8n  ->  Telegram and email notification
Human approval  ->  outreach or proposal
```

Nothing is sent to a lead without human approval.
