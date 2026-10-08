# Project charter and SDLC

**Status:** Requirements baseline v1.0 · 2026-09-15  
**Project:** New public website for Tricetech Solutions, built with PHP/Laravel  
**Source of company context:** [tricetech.ca](https://tricetech.ca/)

## Purpose

Create a clear, credible public website that explains Tricetech Solutions' enterprise IT offerings and gives prospective customers a direct way to start a technical discussion. The current site supplies company facts and descriptions; the new site's information architecture, copy, and visual design will be developed independently.

## Confirmed scope

The project owner confirmed a **new public company website** using **PHP and Laravel**. The order is requirements → Figma UX/UI → Laravel implementation → tests → release. The existing site supplies business context, not the new design.

## Stakeholders and users

- **Business owner / approver:** Tricetech project owner (to identify).
- **Content owners:** company leadership and service experts (to identify).
- **Primary users:** IT leaders, operations leaders, and procurement or business decision makers evaluating enterprise technology services.
- **Secondary users:** existing clients seeking contact or support information; mobile visitors; search-engine visitors landing on a service page.

User roles above are proposed based on the site's service and contact language; they are not verified audience research.

## Lifecycle and gates

| Phase | Deliverable | Exit gate |
| --- | --- | --- |
| 1. Discovery and requirements | Charter, PRD, SRS, source inventory, decision register, Laravel baseline | Scope, stack, and current-site content confirmed; baseline recorded. |
| 2. UX/UI in Figma | Sitemap, journeys, wireframes, visual direction, responsive page designs, component library, prototype | Approve content structure, interactions, and visual direction. |
| 3. Architecture and implementation | PHP/Laravel technical plan, coded website, content and contact handling | Review against approved Figma designs and SRS. |
| 4. Verification | Functional, accessibility, responsive, performance, security, and content checks | Close critical defects and approve release readiness. |
| 5. Launch and support | Deployment, monitoring, rollback plan, ownership handoff | Business owner approves publishing and post-launch responsibilities. |

Approval here means a recorded project decision, not a request to publish anything now.

## Boundaries for this phase

- Capture company name, offerings, technology context, and contact channels from the existing site.
- Baseline the public website requirements using the approved company facts and claims; record any new claims for later approval.
- Do not select brand colors, typography, layout patterns, imagery, or interaction styling from the existing site.
- Use PHP/Laravel. Hosting and production contact-form delivery are operations decisions. CRM, CMS, and visitor analytics are outside MVP unless scope changes.
- Complete Figma design and review before coding; derive detailed tests from the approved design and Laravel behavior.

## Key risks and mitigations

| Risk | Mitigation |
| --- | --- |
| Laravel/PHP version or deployment environment is incompatible with hosting. | Check hosting requirements against the technical baseline before implementation. |
| Newly written copy may broaden an approved claim beyond its current-site context. | Trace new copy to the approved source inventory; approve any genuinely new claim. |
| Contact requests may contain business-sensitive technical details. | Decide retention, routing, access, and security requirements before implementation. |
| Service information may be too broad for visitors to evaluate. | Test navigation and service comprehension in Figma before coding. |
| Website ownership may be unclear after launch. | Assign content, form, infrastructure, and support owners before release. |
