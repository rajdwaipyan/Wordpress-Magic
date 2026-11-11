# Project master plan

Last updated: 11 November 2026.

## The five projects

| # | Project | Status |
|---|---|---|
| 01 | Premium corporate website | Theme 1.0.0 complete: 20 pages, mega menu, 23 Elementor-editable sections, case studies, enquiry form. Tested locally; ready for staging |
| 02 | ED Shield BusinessOS | Not started. Scope question open (see below) |
| 03 | AI lead generation engine | Not started. The website form already sends signed webhooks for it |
| 04 | Automated sales system (n8n, GoHighLevel, email, Telegram) | Not started |
| 05 | AI business assistant | Not started |

## Done

- Environment audit of Hostinger and staging WordPress.
- Reference study of carrilagency.com (structure, navigation, page types,
  motion) and an original ED Shield design system.
- Theme 0.1.0 (single page, blocks), installed and active on staging.
- Staging fix: Bravis Addons (a companion of the old Biznax theme) stopped the
  site with the new theme active; it was deactivated on staging only.
- Theme 1.0.0: complete multi-page site, see `TECHNICAL_ARCHITECTURE.md`.
- Repository: `rajdwaipyan/wordpress-magic`, folder `edshield-solutions/`.

## Next for the website

1. Owner installs 1.0.0 on staging (Upload Theme › Replace current), then runs
   Appearance › ED Shield Setup with "Build the new pages in Elementor" ticked.
2. Fill in Customize › ED Shield: contact details, social links, announcement.
3. Replace the placeholder case studies and testimonials with approved ones, or
   remove those sections until real content exists.
4. Review the service page texts with the team.
5. Measure performance on staging; fix what staging reveals.
6. Only then plan the move to production (requires the owner's approval).

## Open decisions for the owner

| Decision | Why it matters |
|---|---|
| What happens to the attendance, cost and dashboard plugins | They overlap with the planned BusinessOS |
| Single-colour version of the logo | The gold and navy shield does not match the new palette; upload it under Customize › Site Identity |
| Real case studies, testimonials and client logos | The theme shows marked placeholders until they exist |
| GoHighLevel and n8n access | Needed before projects 03 and 04 |

## Content that is deliberately missing

No client names, logos, testimonials, statistics, awards or completed projects
are invented anywhere. Numbers on the site are facts about the offer (nine
service divisions, two markets, one team). Placeholders say "Placeholder".
