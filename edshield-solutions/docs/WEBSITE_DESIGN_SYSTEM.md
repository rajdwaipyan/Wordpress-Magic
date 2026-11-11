# Website design system

Status: v1.1, implemented in `theme/edshield`. Tokens live in
`src/css/tokens.css` and are mirrored in `theme.json` for the block editor.

## Direction

A premium technology partner in ED Shield's own brand colours: deep navy and
shield gold, taken from the company logo. Dark navy opening sections with a
lighter diagonal band (as on the reference site), white and cool grey content
bands, Geist for headings and text, IBM Plex Mono for labels, and original line
illustrations instead of stock photography.

## Taken from the reference (carrilagency.com) as structure

Studied page by page: home, services, service detail, work, case study, about,
process, pricing, FAQ, blog, contact, header, mega menu, mobile menu, footer.

| Reference pattern | ED Shield version |
|---|---|
| Announcement bar above a sticky dark header | Same structure; header hides on scroll down, returns on scroll up |
| Services mega menu with icons, one-line benefits and a feature card | Nine divisions with icons and benefits, "Book a discovery call" card, "View all services" footer link |
| Hero with moving project tiles | Two columns of service illustration tiles drifting in opposite directions (CSS only, paused off screen) |
| Technology strip | "Technologies we work with" moving strip |
| Case-study cards with a headline metric, rail with progress bar | Same layout; placeholders until real results exist |
| Numbers band, testimonials rail, three steps, FAQ, closing call to action | Same order of persuasion, original styling |
| Service page: hero, why it matters, deliverables, outcomes, process, FAQ | Same page skeleton for all nine services |
| Oversized footer wordmark | "ED SHIELD" in Geist, fading into the footer |
| Navy hero with a lighter diagonal band | Same treatment in the ED Shield navy |
| Navigation: Work, Services (mega), Resources, Company | Same structure; Services lists the nine divisions and two audience pages |
| Audience pages (founders, marketers) | For Startups, For Growing Businesses |
| Industries page with industry detail pages | Industries page and six industry pages |
| Pricing page with three offers | Pricing page with three engagement models, scoped after discovery (no invented prices) |
| Blog with category pills, search and illustrated overlay cards | Same layout with gold spine cards |
| Contact page with form, testimonials and dark FAQ | Same order |

Not copied: copy, illustrations, photography, logos or code. The colours come from the ED Shield logo (navy and gold) and the typefaces differ from the reference's.

## Colour

| Token | Hex | Use |
|---|---|---|
| ink (navy) | `#071533` | dark sections, text on light |
| charcoal (navy raised) | `#0C1D44` | illustrations, raised dark surfaces |
| graphite (navy light) | `#13285A` | surfaces inside dark sections |
| slate | `#5B6478` | secondary text on light |
| fog | `#A9B3C9` | secondary text on dark |
| bone | `#EEF1F6` | alternate light band |
| paper | `#F6F7F9` | light grey band, cards on white |
| white | `#FFFFFF` | default light band |
| gold | `#D8A848` | buttons (navy text), icons, highlights |
| gold light | `#E9C46A` | accent text on navy |
| gold deep | `#86600F` | accent text on light bands |
| green | `#34D399` | status dots only |

Contrast (measured): navy on white 18.0, navy on light grey 16.8, slate on light
grey 5.5, navy text on gold buttons 8.3, gold light on navy 10.8, fog on navy
8.6, gold deep on light grey 5.3.

Headline highlights: on navy, highlighted words are gold; on light bands they
stay navy with a gold marker underline.

## Tones

Every section has a tone: `ink`, `charcoal`, `paper`, `white`, `bone`. Components
read only the local roles `--edst-bg`, `--edst-fg`, `--edst-muted`,
`--edst-line`, `--edst-line-strong`, `--edst-accent`, `--edst-surface`,
`--edst-field`, so any section works on any tone.

## Type

| Role | Family | Size |
|---|---|---|
| Display and headings | Geist (variable, weight 600) | display `clamp(2.6rem, 1.4rem + 4.2vw, 5.25rem)`, h1 to 4rem, h2 to 3rem, h3 to 1.75rem |
| Text | Geist (400 to 600) | 16 to 17 px, line height 1.6 |
| Label | IBM Plex Mono 500 | 11 to 12 px, uppercase, tracking 0.08 to 0.1em |

A highlighted phrase in a heading is written `<em>…</em>`. Fonts are
self-hosted latin subsets (Geist 29 KB, Plex Mono 15 KB) with a metric-matched
fallback, so text does not reflow when they load.

## Layout

- Content width 82rem, gutter `clamp(1.25rem, 4vw, 3rem)`.
- Section spacing `clamp(4.5rem, 8vw, 7.5rem)`; compact, large, flush and none
  variants per section.
- Radii 8 px (buttons, fields), 14 px (cards), 22 px (frames, large panels).
- Breakpoints: 40rem (two columns), 48rem, 64rem (desktop navigation, full grids).

## Components

Buttons (primary, ghost, light, outline, text link with arrow), badges with a
live dot, chips, tags, check lists, cards (media, feature, icon), case cards,
rails with progress and arrows, numbered rows, steps, accordions, plans, form
fields, breadcrumbs, illustrations (13 drawings, plus every icon as a drawing).

## Motion

| Effect | Technique |
|---|---|
| Hero tiles drift, moving strips | CSS keyframes, paused on hover and off screen |
| Header hides on scroll down | class toggle, transform only |
| Reveal of content below the fold | GSAP ScrollTrigger batch, 28 px rise, once |
| Hero visual depth | scrubbed 8% drift, desktop only |
| Magnetic primary buttons | GSAP quickTo, fine pointers only |
| Counters | count up once, only if below the fold at load |

Nothing visible on load is ever hidden. With reduced motion the GSAP bundle is
not downloaded and CSS animations stop.
