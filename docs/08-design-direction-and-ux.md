# Design direction and UX specification

**Phase:** Figma UX/UI design · 2026-09-15  
**Figma file:** [Tricetech Solutions — Public Website UX/UI](https://www.figma.com/design/KLC6xLAlHz0GzwG3ggLjLg)  
**State:** Approved as the implementation baseline on 2026-09-16. Figma contains foundations, desktop screens, mobile/tablet screens, inquiry states, sitemap, wireframes, and desktop service-discovery prototype links. The Laravel implementation follows these frames and the responsive rules below.

## Visual direction

The site should feel like an established technical partner: assured, precise, and calm. Use an **editorial grid**, clear typography, generous white space, and thin rules to organize dense service information. Keep surfaces flat. Avoid gradients, glossy effects, floating cards, stock-tech imagery, decorative blobs, oversized slogans, and animation that obscures reading.

Red is a functional brand signal for primary actions, section labels, active states, and small rules. White carries the majority of the page. Neutral ink and gray are supporting colors for readable text and borders.

| Token | Value | Use |
| --- | --- | --- |
| Brand red | `#AA1E2B` | Primary button, links, section labels, focus. |
| Brand red hover | `#881724` | Hover/pressed state. |
| Red tint | `#FBEDEF` | Limited emphasis or form feedback. |
| White | `#FFFFFF` | Main page surface and button text. |
| Subtle surface | `#F5F6F7` | Alternate major section, especially hero. |
| Primary ink | `#1E252B` | Headings and key content. |
| Secondary text | `#53616B` | Body supporting text. |
| Rule/border | `#D9DFE3` | Section rules and form outlines. |

Brand red has a **7.17:1** contrast ratio against white; secondary text has **6.38:1** against white. Border gray is for non-text structure only. Confirm contrast again for every final Figma pairing. Target [WCAG 2.2 Level AA](https://www.w3.org/TR/WCAG22/).

Use **IBM Plex Sans** for its clear, restrained technical character. Desktop display is 56/64 SemiBold; section heading 36/44 SemiBold; subsection 24/32 Medium; large body 18/28 Regular; regular body 16/24 Regular; small label 13/20 Medium. Mobile display should be 36/42 SemiBold and section heading 28/34. Avoid all-caps in long copy; reserve it for short category labels.

## Information architecture

| Destination | Visitor question answered | Core content |
| --- | --- | --- |
| Company / Home | Who is Tricetech, and what does it do? | Company statement, two service pathways, ecosystem, contact route. |
| Infrastructure & Networks | Can they design and support our environment? | All nine approved infrastructure services, practical review CTA. |
| Cloud & Software | Can they host, migrate, and build applications? | All four approved cloud/software areas, practical discussion CTA. |
| Architecture Discussion | How do I describe my need and reach an engineer? | Address, phone, email, approved three-field intake, feedback states. |

The site may keep contact as a homepage section, a separate route, or both after prototype review. Service entry routes should remain direct and clear.

## Desktop screen compositions

### Company / Home

1. **Header:** typographic wordmark, Company, Infrastructure, Cloud & Software, and one red “Request a discussion” action. No large navigation menu.
2. **Hero:** left narrative on a subtle neutral canvas; concise approved-value statement; one primary action and a secondary service route. Right side holds a ruled, short technical-priorities panel. This panel is functional orientation, not decorative card content.
3. **Company overview:** two editorial columns. Left contains the company statement and long-term approach. Right draws the approved technology capabilities as a plate schedule (Figure 11) rather than tabulating them under cloud/virtualization, operating platforms, security, identity and edge.
4. **Service pathways:** two full-width ruled rows, each with a number, service-family title, one precise summary, and a text route. Avoid a card grid.
5. **Architecture discussion:** two columns. Left gives a direct invitation and the corporate contact channels. Right is a calm form with visible labels, helper text, and errors.
6. **Footer:** restrained wordmark, two service routes, address, phone, email, and copyright.

### Infrastructure & Networks

Open with a plain service title and one-paragraph explanation. Use a numbered, ruled list for the nine published areas: routing/switching, consulting/planning, audits/optimization, Microsoft systems, monitoring/support, communications, server protection, VPN/remote access, and email protection. Every row has a title and a short accurate explanation. End with a specific invitation for an infrastructure architecture review, then provide the main contact route.

### Cloud & Software

Open with the compute/software positioning. Present four larger ruled sections: IaaS & Managed VPS; Private Cloud Storage; Business Applications & Oracle APEX ERP; Enterprise Messaging Hosting & Application Migration. Keep the published capability lists attached to the correct area. End with an inquiry route.

## Inquiry UX

The current site approves **email (required), service interest (optional), and technical message (required)**. The optional interest choices are Enterprise Network Architecture; Cybersecurity & Server Protection Operations; Oracle APEX ERP & Software Automation; Cloud Infrastructure (IaaS) Scaling.

Use persistent visible labels, not placeholder-only labels. Give the message field room for a useful technical description. On validation, put specific errors beside fields and move focus to an error summary or first invalid field. Preserve all valid input. Use a concise success state only after the Laravel backend accepts delivery. On delivery failure, retain input and offer a clear retry and the public phone/email alternatives. Avoid implying an immediate response time that the current site does not state.

## Responsive and interaction rules

- Desktop content width: approximately 1280 px within a 1440 px frame, 80 px side margins.
- Tablet: reduce side margins and stack complex two-column sections when copy becomes cramped.
- Mobile: 390 px reference frame with 24 px side margins; single-column reading order; service rows expand vertically; no horizontal scrolling at 320 px.
- Navigation: a labeled menu button on mobile and a visible primary inquiry route. The menu opens as a simple vertical list with focus management.
- Touch targets: at least 44 × 44 px. The Figma primary button is 52 px high.
- Focus: visible 2 px red outline with separation from the control; never rely on color alone for state.
- Motion: optional subtle transitions only; honor reduced-motion preferences in implementation.

## Revision — implemented visual system (2026-09-17)

The direction above still governs: flat surfaces, the approved palette, red as a functional signal, the editorial grid, and IBM Plex Sans for reading. This revision records how the implemented design was extended after review found the first build too thin to read as an established technical partner. Nothing here changes approved copy meaning, claims, or the service catalogue.

| Change | Why |
| --- | --- |
| Two-tier header: a utility strip above the navigation carrying office location, telephone, 24/7 monitoring status and email. | Contact and operational facts are the first credibility signals an enterprise buyer looks for; the previous header deferred them to the footer. |
| A drawing language: four hand-authored inline SVG schematics — network topology, enforcement perimeter, service stack, engagement phases. | Gives the pages technical substance without stock photography, decorative illustration or an icon library. |
| IBM Plex Mono added for labels, coordinates, specification values and figure captions. | Same type family as the approved sans, so the addition reads as technical rather than as a second brand voice. |
| Numbered, captioned figures (Figure 01 / TOP-01). | Diagrams are presented as titled drawings, consistent with the editorial grid. |
| Published claims strip directly below the hero. | States the five approved claims plainly, with scope in the supporting text. |
| New sections: engagement phases, environment coverage matrix, per-service scope detail, architecture-review method, cutover method. | Expands the context without inventing numbers, certifications, partnership status or response-time promises. |

Drawing grammar (all schematics): hairline edges in `--line-strong`, right-angle routing, solid nodes for endpoints, a single red path for the live or primary signal, dashed edges for monitoring, and zone reference marks on the frame. Figures must remain legible at the ~480 px figure column width, which sets their minimum label size.

## Revision 2 — colour, type and motion (2026-09-17, at project owner's direction)

The project owner superseded the single-accent red/white pairing and asked for a richer palette, motion, and a fuller redesign. The earlier constraints on palette size, imagery and animation no longer apply. The editorial grid, flat surfaces, service catalogue, approved copy meaning and accessibility targets still do.

**Colour.** Three families, each with one job, on a petrol-tinted neutral scale rather than grey:

| Family | Role | Steps in use |
| --- | --- | --- |
| Oxblood `#a81c2a` | The signal: primary action, live path in drawings, headline rule | 3 tint, 8 border, 9 solid, 10 hover, 11 text |
| Petrol `#0d6b73` | The data: telemetry drawings, specification values, secondary interactive, focus | 3 tint, 8 border, 9 solid, 10 hover, 11 text |
| Brass `#9a7b3f` | The record: drawing numbers, figure IDs, sheet codes, small rules | 3 tint, 8 rule, 9 mark, 11 text |
| Midnight `#08161d`–`#14303a` | Page frame: utility bar, header, hero, dark bands, footer | — |

Neutral roles follow a 12-step model (1–2 surfaces, 3–5 element states, 6–8 borders, 9–10 fills, 11–12 text). Text pairings meet WCAG 2.2 AA: `--n11` on `--n1`, `--red11` on `--n1`, and the lit variants (`--redlit`, `--teallit`, `--brasslit`) on `--mid1`.

**Type.** IBM Plex Serif 600 for display headlines only, Plex Sans for reading, Plex Mono for drawing labels. Three cuts of one superfamily, so the mix reads as editorial-technical rather than as separate brands.

**Motion.** Progressive enhancement only, never load-bearing: scroll reveals, schematic contours that draw themselves when the figure enters the viewport, a signal pulse on the monitoring status light, and hover transitions. The stylesheet renders the finished state by default, `js-motion` is added only when motion is welcome, and `prefers-reduced-motion: reduce` disables every one of them. If JavaScript fails, the page is complete and static.

## Revision 3 — visual density, drawing geometry and surface legibility (2026-09-17)

Review feedback after Revision 2: the pages still read as text, the drawings were too few to carry the explanation, and the light canvas was uncomfortable to read against. Three rules came out of it.

**1. Draw it when it is a structure, write it when it is a sentence.** Repeating lists of controls were replaced by drawings: the three-layer environment coverage map, the four-stage engagement sequence, the nine-area service map on Infrastructure, the four-step review method, and the four-area service map plus cutover sequence on Cloud & Software. Per-service bullets became short capability chips. Two paragraphs of company positioning became two stated assertions. Nothing factual was dropped — the same catalogue and the same claims are now read as a drawing first and prose second.

**2. Drawings have measured geometry, not decorative geometry.** SVG text cannot reflow, so every drawing's plates are sized from its longest label before the drawing is written, and every component header states the budget it was built to:

| Class | Type size | Placed in | Character budget |
| --- | --- | --- | --- |
| `s-label--cell` | 11.5 px mono | Measured plates | 24 |
| `s-label--fine` | 10 px mono | Small-scale drawings and plate rows | 17 |
| `s-label--teal` | 12.5 px mono | Stage outputs inside plates | 21 |
| `s-label--micro` | 11 px mono | Frame footer rails | Not bounded (full width) |

A feature test enforces the `s-label--cell` budget so a future edit cannot push a label under the next cell. The measurements were taken by rendering each page and comparing every text bounding box against its frame and its plate; the first pass found three genuine overflows that code review had missed.

**3. Blade escapes once.** A component attribute or a component array is escaped by `{{ }}`, so an entity such as `&amp;` written into one of those values prints as `&amp;` on the page. Component attributes and component arrays therefore carry a plain `&`; literal markup and `{!! !!}` output carry the entity. A feature test fails on any doubled entity in the rendered HTML.

**Surfaces.** The canvas is `#efe9de`, a warm paper tone rather than white, so long-form text is read at lower glare; white is reserved for cards, figures, chips and form fields so those lift off the page instead of blending into it. Bands alternate canvas, surface (`#e7e1d4`) and paper (`#f4f0e6`). The previous `#f5f2ec` canvas and white page surface are superseded.

**Narrow screens.** A drawing cannot reflow, so below 780 px the content figures keep a 620 px minimum width and pan horizontally inside their frame, the way a folded sheet is read; hero drawings still scale to fit their column.

## Revision 4 — drawings decluttered, page finish, marketing scale (2026-09-17)

Review feedback on Revision 3: the drawings had too many lines to read, and on the service pages the closing call to action and the footer were the same colour, so the final ask dissolved into the footer. Both are fixed here, and the page finish was raised to a marketing standard.

**The drawings lost their scaffolding, not their language.** The drafting look is deliberate and stays: square plates, hairline edges, monospace lettering, one red signal path. What went was the line noise. Measured review of the rendered pages showed those strokes were competing with the labels they were supposed to support — the drawings read as noise at exactly the moment they were meant to explain something.

| Element | Before | Now |
| --- | --- | --- |
| Field | 20-unit drafting grid across the whole frame | nothing |
| Frame | outer rect, inner rect, zone letters A–I, zone numbers 1–6 | nothing; the figure frame already groups the drawing |
| Rules | hairline rules inside and between plates, arrow chains, margin brackets | none; space groups the plates |
| Panels | 126-unit cells forcing 10px labels | 296–564-unit plates, 14px names and 13px values, all monospace |
| Colour | five label colours | three: ink for names, petrol for values, brass for codes, with red reserved for the accent path |
| Accent | several red edges plus dashed monitoring edges | one accent path; monitoring is a dashed enclosure, not another colour |

**Figure 10 — the operations cycle (home).** The claims strip above the company section states what is published; a list of commitments cannot show how they are delivered. Figure 10 sits directly beneath it and draws the operating relationship: continuous monitoring, predictive fault discovery, remediation, scheduled patching and review, with the return path as the accent because the cycle never stops. No new claim was introduced — the four steps are the four operating capabilities the service catalogue already publishes. It is the only drawing on the site that shows a relationship rather than a structure, which is the reason it earns its place.

**Figure 11 — the technology schedule (home).** The company column's technology set was a text table of five ruled rows, which read as five unrelated lists rather than one estate. It is now a schedule of five plates — domain name in ink, the published technologies in petrol, a numbered code and accent tick per row, framed by the same sheet footer the other drawings carry (`TECH-01`). The domains, the products and the note beneath it are unchanged; nothing was added to fill the frame. The plate is built for its 526px column: value text starts at x=96, is set at 12.5px monospace and measures 7.86 units per character, so 67 characters is the widest string the plate holds with a margin left over, and the longest shipped value is exactly 67. That number is declared as `data-label-budget` and enforced by the drawing test.

Two rounds of feedback shaped this. The first asked for fewer lines, and the drawing system was replaced rather than thinned — rounded cards and sans labels — which was more change than was asked for. The drafting language was restored, keeping the reduction. The lesson is recorded here because it applies to every future revision of these drawings: **reduce strokes, keep the language.**

The perimeter drawing is now two by two rather than four in a row, which is what let its labels reach normal reading size. The coverage map is three cards with one control per line instead of a five-column divided grid. The stack drawing lost its four bracketed margin annotations: the page already carries those controls.

**Budgets are declared, not assumed.** Each drawing carries `data-label-budget`, the character count its plates were measured to hold, and a feature test holds every drawing to its own declaration. A full-width rail caption uses `.foot`, which is outside the budget by design because it is not constrained by a plate.

**The closing call to action is no longer midnight.** It is deep oxblood with a soft radial highlight, which separates it from the midnight footer, puts the brand signal on the final ask, and stops the page from ending in an undifferentiated dark block. The closing also gained a "Next step" eyebrow and a direct telephone and email line beside the button, so a visitor who will not fill in a form still has a way through.

**Marketing scale.** Section headings are up to 3.05rem and band padding is 132px, the claims strip groups by space instead of vertical rules, the header is sticky so the primary action is always reachable, and figures lift on hover. The hero display is held at 3.35rem — an earlier 4.6rem setting pushed the headline to five lines and made the hero band 950px tall, which read as a long preamble rather than an opening statement. The palette, the drawing language, the approved copy and the accessibility targets are unchanged.

**Narrow screens.** Every figure, including the hero figures, keeps a 620px minimum and pans inside its frame. Scaled to a phone column the drawings' labels fell to about 5px, which is the same as shipping no drawing. Fixing that exposed a grid bug: an item's automatic minimum size is its min-content size, so the minimum width was stretching the column and pushing the page wider than the viewport. Every grid column that holds a figure now uses `minmax(0, …)`. The single-column layout at 980px repeated the same mistake — a bare `1fr` track — which the technology schedule then triggered at full width, because its plates declare a 620px minimum. It is `minmax(0, 1fr)` there too: measured at a 551px viewport, page overflow is 0 and every figure frame pans.

## Implementation review checklist

Review the home, infrastructure, cloud/software, mobile, and inquiry success/error states together. Check every approved service area is represented, headings form a useful reading order, body copy is legible, red/gray contrast passes, forms have visible labels and error recovery, and the mobile flow reaches contact without hidden steps. The implemented pages passed automated feature tests and responsive inspection at 320, 390, 768, and 1440 pixel targets; production mail and deployment settings remain release configuration.
