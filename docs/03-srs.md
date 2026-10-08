# Software requirements specification (SRS)

**Status:** System baseline v1.0 · 2026-09-15  
**System boundary:** English-language public website and inquiry workflow, implemented in PHP/Laravel. Hosting and delivery configuration are deployment decisions.

## Requirement notation

`M` = MVP requirement. Acceptance criteria describe observable outcomes. The project owner confirmed the product scope, Laravel stack, and accuracy of all published content on the audited site. Only new content and operational settings need separate review.

## Functional requirements

| ID | Priority | Requirement | Acceptance criterion |
| --- | --- | --- | --- |
| FR-01 | M | Present an approved company overview and service summary. | Visitor can identify the company, both service families, and a contact route. No unapproved factual claim appears. |
| FR-02 | M | Explain infrastructure and network services. | The approved categories in this family have clear descriptions and a route to inquire. |
| FR-03 | M | Explain cloud and software services. | The approved categories in this family have clear descriptions and a route to inquire. |
| FR-04 | M | Provide consistent navigation between company, services, and contact content. | Every primary route is reachable by keyboard and from all public pages; no broken internal link. |
| FR-05 | M | Display approved business contact channels. | Address, phone, and email match the approved content inventory; phone and email links invoke the appropriate device action. |
| FR-06 | M | Provide an inquiry form based on the approved current-site intake: email, optional service interest, and technical description/message. | Email and message are required and labeled; interest offers the four approved current-site choices. The form is usable without JavaScript. |
| FR-07 | M | Validate inquiry input in Laravel before delivery. | Email is valid and at most 254 characters; optional interest is blank or one of the four defined choices; message is 10–2,000 characters. Invalid requests are rejected and field errors are shown without losing valid entered data. |
| FR-08 | M | Deliver valid inquiries to a configured company-owned destination and show outcome feedback. | Success is shown only after Laravel accepts delivery; a delivery failure shows a retry path and is logged without the submitted message or email address. The recipient is configured outside source code. |
| FR-09 | M | Record the chosen service interest in the delivered inquiry. | Recipient sees the chosen interest when supplied; blank interest is handled as a general inquiry; no unapproved automatic routing to third parties occurs. |
| FR-10 | M | Protect the inquiry workflow. | Laravel request-forgery protection, server-side validation, rate limiting, and an approved spam control are active; repeated abusive requests are rejected. |
| FR-11 | M | Provide a safe, clear confirmation and retry experience. | One user action cannot create duplicate inquiries through repeated clicks; success/failure states are accessible; visitors can retry a failed submission. |
| FR-12 | M | Keep older public service URLs working if the existing domain is reused. | `/infrastructure.html` and `/cloud-software.html` redirect to the corresponding new content or remain valid; no service entry URL becomes a dead end. |
| FR-13 | M | Provide a useful response for an unknown public URL. | An invalid URL shows a branded, accessible not-found page with routes to company, services, and contact content. |
| FR-14 | M | Provide search-engine discovery controls for public pages. | Approved indexable pages have canonical URLs, a sitemap, and robots instructions consistent with the chosen launch domain; nonpublic routes are not listed. |
| FR-15 | M | Explain how inquiry data will be handled before submission. | The form links to or displays company-approved privacy wording; final text is supplied and reviewed before release. |

## Content requirements

| ID | Requirement | Acceptance criterion |
| --- | --- | --- |
| CR-01 | Trace company facts and service claims to the approved audited pages. | Content inventory records the source URLs and project-owner approval date; new claims need their own approval record. |
| CR-02 | Write new editorial copy while preserving approved meaning. | Copy review confirms accuracy; no wholesale transfer of current site's layout-dependent wording. |
| CR-03 | Preserve the scope of approved current-site claims and avoid inventing new ones. | Approved 24/7, enterprise SLA, migration, and technology statements may be used in their original context; no unstated SLA terms, certifications, partnerships, or guarantees appear. |
| CR-04 | Maintain clear page metadata and semantic headings. | Each indexable page has a unique title/description and a meaningful heading hierarchy. |
| CR-05 | Publish only English content in MVP and identify its language. | Every public page has reviewed English copy and a correct language declaration. |

## Nonfunctional requirements

| ID | Requirement | Verification |
| --- | --- | --- |
| NFR-01 | Core pages and inquiry flow work at mobile, tablet, and desktop widths, including 320 px, 768 px, and 1440 px. | Manual responsive review; no essential content or form control is clipped or hidden at these widths. |
| NFR-02 | Target WCAG 2.2 Level AA for the complete public pages and inquiry process. | Automated and manual review, including keyboard, focus, labels, contrast, errors, and screen-reader checks on core journeys. |
| NFR-03 | Serve the public website and inquiry transport over HTTPS. | Release environment inspection. |
| NFR-04 | Protect submitted inquiry data in transit and at rest where stored; restrict access to named operational owners. | Architecture and configuration review. |
| NFR-05 | Do not expose credentials, tokens, or private technical inquiries in client assets, logs, or analytics. | Build, log, and configuration review. |
| NFR-06 | Define a measurable performance budget after Figma assets and hosting are selected, before implementation is accepted. | Mobile and desktop page tests pass the recorded budget in the release environment. |
| NFR-07 | Provide observable form failures and operational ownership. | Simulate delivery failure; confirm alerting/diagnostic process and assigned owner. |
| NFR-08 | Provide a release rollback or recovery procedure. | Deployment rehearsal or documented operational validation before launch. |
| NFR-09 | Use a supported PHP/Laravel release and keep dependencies maintainable. | Laravel/PHP versions match the technical baseline and production platform; dependency and security checks pass before launch. |
| NFR-10 | Render essential public content and the form server-side. | Overview, service content, navigation, and inquiry remain usable when JavaScript is unavailable. |

## Proposed user flows

1. **Service discovery:** landing page or search result → service explanation → relevant inquiry route.
2. **Company validation:** overview → company facts/contact details → call, email, or inquiry.
3. **Inquiry:** select interest → enter required details → validate → submit → confirmation.
4. **Error recovery:** invalid or unavailable submission → clear error → corrected retry without losing entered content.

## System interfaces and data

- Public pages: Laravel routes with server-rendered templates. A CMS is outside MVP.
- Inquiry destination: a company-owned mailbox through Laravel's mail interface, or an equivalent company-owned queue if the technical owner approves it before implementation. Actual recipient and mail service are deployment configuration.
- Inquiry data: required email and message, plus optional service interest from the four approved choices. Do not persist the body in the application database for MVP unless retention and access are approved through change control.
- Privacy notice wording, mailbox owner, retention in the destination mailbox, and response expectation must be supplied by the company before launch.
- Visitor analytics, CRM, and third-party marketing integrations are outside MVP.

## Verification trace

| Stage | Check |
| --- | --- |
| Figma review | FR-01–06, FR-11, and all journeys; NFR-01–02 through prototype review. |
| Implementation review | FR-01–15; CR-01–05; NFR-03–10. |
| Release test | Valid and invalid inquiry, abuse and duplicate prevention, delivery failure, legacy URLs, navigation, content approval, responsive and accessibility checks, security configuration, performance budget, rollback. |

Detailed test cases are written after Figma and delivery architecture approval so they verify the chosen behavior.
