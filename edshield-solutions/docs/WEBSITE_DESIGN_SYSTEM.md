# Website design system

Status: v1.0, implemented in `theme/edshield`. Tokens live in
`src/css/tokens.css` and are mirrored in `theme.json` for the block editor.

## Direction

A premium technology partner: dark, confident opening sections, warm light
content bands, serif display type with a technical mono label system, one
electric blue, and original line illustrations instead of stock photography.

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
| Oversized footer wordmark | "ED SHIELD" in Newsreader, fading into the footer |

Not copied: colours, typefaces, copy, illustrations, photography, logos or code.

## Colour

| Token | Hex | Use |
|---|---|---|
| ink | `#0E1012` | dark sections, text on light |
| charcoal | `#15181B` | illustrations, raised dark surfaces |
| graphite | `#1E2226` | surfaces inside dark sections |
| slate | `#5A6068` | secondary text on light |
| fog | `#A3A9B1` | secondary text on dark |
| bone | `#EDECE8` | alternate light band |
| paper | `#F3F1EC` | default light band |
| white | `#FCFBF9` | raised light surfaces, white band |
| blue | `#2A4BFF` | buttons, accents on light |
| blue-bright | `#7C93FF` | accents on dark |
| green | `#34D399` | status dots only |

Contrast (measured): ink on paper 16.9, slate on paper 5.6, blue on paper 5.2,
white on blue 5.9, fog on ink 8.1, blue-bright on ink 6.8.

## Tones

Every section has a tone: `ink`, `charcoal`, `paper`, `white`, `bone`. Components
read only the local roles `--edst-bg`, `--edst-fg`, `--edst-muted`,
`--edst-line`, `--edst-line-strong`, `--edst-accent`, `--edst-surface`,
`--edst-field`, so any section works on any tone.

## Type

| Role | Family | Size |
|---|---|---|
| Display | Newsreader (300 to 600) | display `clamp(2.75rem, 1.3rem + 4.6vw, 5.5rem)`, h1 to 4.25rem, h2 to 3.25rem, h3 to 1.85rem |
| Text | IBM Plex Sans (400 to 600) | 17 px base, line height 1.6 |
| Label | IBM Plex Mono 500 | 11 to 12 px, uppercase, tracking 0.08 to 0.1em |

A highlighted phrase in a heading is written `<em>…</em>` and shows in the
accent colour. Fonts are self-hosted latin subsets with metric-matched
fallbacks, so text does not reflow when they load.

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
