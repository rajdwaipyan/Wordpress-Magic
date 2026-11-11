# QA report

## Theme 1.0.0, tested 11 November 2026

Local WordPress 7.1.4 (PHP 8.3, MariaDB 10.11) in the build sandbox, fresh
install, setup screen run with pages, menus and placeholders. Chromium only.
**Not yet tested on staging.**

### Passed

| Area | Check | Result |
|---|---|---|
| PHP | `php -l` on every PHP file | no syntax errors |
| PHP | 20 pages, 3 case studies, article, blog, category, search, 404, feed with `WP_DEBUG` on | no notices or warnings from the theme; 404 page returns 404 |
| Importer | Setup screen on an empty site | 20 pages, primary menu with mega menu, footer menus, 3 placeholder case studies, permalinks set to Post name |
| Elementor data | Every page's Elementor data (JSON round trip) rendered through the widgets' render path | 19 pages, 126 widgets: same section structure as the theme's own rendering, no warnings |
| Elementor widgets | Every section type rendered with only its defaults (a freshly dropped widget) | all 23 render |
| Elementor API | Overridden methods against Elementor 4.0.8 base classes | signatures compatible |
| Layout | Horizontal overflow at 1440 px and 390 px on all page types | none (one FAQ overflow on phones found and fixed) |
| Front end | Console errors on all page types | none |
| Motion | Elements hidden by the reveal after scrolling every page | 0 |
| Navigation | Mega menu: hover open and close, keyboard open, Tab into panel, Escape returns focus | pass |
| Navigation | Dropdown: click open, outside click closes | pass |
| Navigation | Header hides on scroll down, returns on scroll up | pass |
| Navigation | Mobile menu: opens under the header (also after scrolling), locks page scroll, nested group, closes | pass |
| Announcement | Close persists across reloads without a flash | pass |
| Sliders | Next button, progress bar, controls hidden when nothing overflows | pass |
| Work | Industry filter and "All" | pass |
| FAQ | Accordion; category navigation jumps and highlights | pass |
| Anchors | Back to top and in-page links scroll smoothly | pass |
| Form | Submission stored as a private lead and redirected with a success notice | pass |
| Admin | Setup screen, Customizer panel, Leads, Projects, case-study details box, Menus | load without PHP messages or script errors |

### Lighthouse 13.6, home page, local server

| Performance | Accessibility | Best practices | SEO | LCP | TBT | CLS |
|---|---|---|---|---|---|---|
| 99 | 100 | 96 | 63 | 1.7 s | 80 ms | 0 |

SEO and best practices are lowered by local artefacts: the test site is set to
discourage search engines, and it has no favicon. The local server sends
uncompressed files; with gzip the stylesheet is 12.5 KB and the core script
3.7 KB. Measure on staging before quoting figures.

### Not tested

- The Elementor editor itself: Elementor could not be downloaded into the
  sandbox (network policy), and its source checkout has no built editor assets.
  Widget code was verified against the Elementor source and through the render
  path above. Check the editor on staging.
- Elementor Pro Theme Builder templates (same reason).
- Staging: PHP 8.5, MariaDB 11.8, LiteSpeed, the four ED Shield plugins.
- Email delivery (the sandbox has no mail server) and a live webhook receiver.
- Safari, Firefox, real devices, screen readers.

### Known limitations

- Case studies and testimonials are placeholders until real ones are supplied.
- Pages built without Elementor show their starter layout only while the page
  content is empty; once content is added in the block editor, that content is
  shown instead.
