# Product requirements document (PRD)

**Status:** Product baseline v1.0 · 2026-09-15  
**Product:** New public Tricetech Solutions company website  
**Implementation constraint:** PHP/Laravel

## Problem and opportunity

Prospective customers need to understand what Tricetech Solutions does, which services fit their environment, and how to start a useful technical conversation. The current site communicates this across a homepage and two service pages. The new product should preserve accurate business context while improving clarity and ease of inquiry through a fresh design process.

This is a **project hypothesis**, not a measured diagnosis of the current site. No traffic, conversion, or customer-research data has been supplied.

## Product goal

Help a qualified visitor answer three questions quickly: **Who is Tricetech Solutions? What can it help us with? How do we talk to the right person?**

## Audience and jobs to be done

| Audience (proposed) | Job |
| --- | --- |
| IT / infrastructure leader | Find network, server, security, monitoring, and migration capabilities relevant to an existing environment. |
| Operations or business leader | Understand cloud and software capabilities in business terms, then request a discussion. |
| Procurement / evaluator | Verify company identity, service fit, location, and contact details. |
| Existing client | Locate the appropriate public contact channel; the site must not imply an authenticated support portal unless one is specified. |

## Minimum viable product

1. A company overview with an accurate summary of services and clear next steps.
2. Distinct explanations of infrastructure/network services and cloud/software services.
3. A contact/intake path that provides enough context for routing an inquiry without asking for unnecessary details.
4. Mobile, keyboard, and assistive-technology access to all public content and the intake path.
5. Search-readable page titles, descriptions, headings, and service URLs.

The required **content areas** are company overview, infrastructure/network services, cloud/software services, and contact. Figma will determine the page structure and presentation, provided every area and journey remains discoverable.

## Outcomes and measurement

| Outcome | Proposed measure | Baseline / target |
| --- | --- | --- |
| Service comprehension | Moderated task: participant can identify the relevant service and explain why. | Baseline not supplied; set target after research. |
| Inquiry completion | Count accepted inquiries and delivery failures from operational records without storing message content. | Baseline and target to determine after launch. |
| Contact discoverability | Visitor can find phone, email, or inquiry route from relevant pages. | Validate during prototype review. |
| Content accuracy | Every externally visible fact or service claim traces to the approved current site or a later approval record. | 100% at release gate. |

No analytics vendor or tracking of individual visitors is in MVP.

## Product principles

- Explain technical services precisely and in plain language.
- Preserve the meaning and scope of the approved current-site claims; obtain approval for new claims.
- Make contact straightforward while respecting the sensitivity of submitted technical details.
- Build a coherent experience across service entry pages and devices.
- Develop visual identity in Figma; do not import the current site's colors or layout.

## Release boundaries

**In scope for MVP:** English-language public company/service content, navigation, contact information, inquiry workflow, responsive behavior, accessibility, basic SEO, and operational handoff. The site is implemented in PHP/Laravel.

**Outside MVP unless approved through change control:** client login, support ticket portal, ecommerce, online payment, careers management, blog/news CMS, multilingual pages, live chat, pricing calculator, marketing automation, and visitor analytics.

## Dependencies and open decisions

- The current site's public name, location, contact channels, full service catalog, technologies, and published claims are approved. A legal entity name is not published there and is not required for MVP unless legal copy calls for it.
- Confirm new Laravel inquiry recipient, data retention, privacy notice wording, and response expectations. The MVP field set and behavior are in the SRS.
- Decide launch domain, hosting, deployment ownership, and post-launch support.
- A CMS and analytics are excluded from MVP; add either only through an approved scope change.

The decision register tracks these items in [validation and decisions](06-validation-decisions.md).
