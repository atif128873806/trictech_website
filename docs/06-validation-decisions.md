# Validation and decision register

**Status:** Product/system scope and current-site content approved · 2026-09-15. Remaining items concern new delivery/privacy settings, original design, and release operations.

| ID | Decision or validation | Why it matters | Owner | Status |
| --- | --- | --- | --- | --- |
| D-01 | New public company website using PHP/Laravel. | Sets all PRD/SRS scope. | Project owner | Confirmed 2026-09-15 |
| D-02 | Public name, address, phone, and email on the site are correct. Legal entity name is not published and only needs a decision if legal copy requires it. | Keeps contact content accurate. | Project owner | Published details approved 2026-09-15 |
| D-03 | The complete current-site service catalog is correct; Figma will set presentation priority. | Defines sitemap and copy. | Project owner | Catalog approved 2026-09-15 |
| D-04 | Published SLA, 24/7, migration, vendor-lock-in, sovereignty, and nationwide statements are correct. Preserve their stated context. | Enables accurate new copy. | Project owner | Published claims approved 2026-09-15 |
| D-05 | Named technology/product capabilities are correct. The site does not grant new third-party logo or partnership claims. | Enables accurate technology content. | Project owner | Published names approved 2026-09-15; new logos need permission |
| D-06 | Confirm new Laravel inquiry recipient, mailbox retention, privacy wording, response expectations, and spam-control mechanism. Current form posts to Formspree; its fields are approved and reflected in SRS. | Completes delivery and privacy/security checks. | Business + technical owner | New-site operations open; does not block Figma |
| D-07 | English only for MVP; decide launch domain and any required regional content. | Defines deployment and later expansion. | Project owner | Language baselined; domain open |
| D-08 | No CMS or visitor analytics in MVP; assign content owner. | Defines editorial operations. | Project owner | Scope baselined; owner open |
| D-09 | Approve sitemap, copy, user journeys, and Figma visual direction. | Controls design-to-code handoff. | Project owner | Approved for development 2026-09-16 |
| D-10 | PHP/Laravel stack confirmed; select hosting, deployment, monitoring, and rollback owners. | Enables implementation and release. | Technical owner | Stack confirmed; operations open |
| D-11 | Select production PHP/Laravel versions compatible with hosting. | Keeps a supported deployable stack. | Technical owner | Laravel 13.32 with PHP 8.3+ implemented; hosting runtime verification remains a release check |

## Current-site approval record

| Content | Source | Approver | Date |
| --- | --- | --- | --- |
| Company name, positioning, address, phone, email, technology names, two service families, detailed service descriptions, and published claims | [Homepage](https://tricetech.ca/), [Infrastructure](https://tricetech.ca/infrastructure.html), [Cloud & Software](https://tricetech.ca/cloud-software.html) | Project owner (user) | 2026-09-15 |

## Approval record for any new claim or asset

| Statement / asset | Source | Approved wording or use | Approver | Date |
| --- | --- | --- | --- | --- |
| _Add each release claim here_ | _URL or internal evidence_ | _Final text or asset permission_ | _Name_ | _YYYY-MM-DD_ |

## Change control

When a decision changes scope or behavior, update the PRD and SRS requirement IDs, then update affected Figma screens and test cases. Record the decision owner and date. No implementation choice should silently become a business requirement.
