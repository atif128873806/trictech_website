# Laravel technical baseline

**Status:** Architecture constraint v1.0 · 2026-09-15  
**User-confirmed stack:** PHP and Laravel.  
**Planned release:** Laravel 13 with PHP 8.3 or newer, subject to compatibility with the chosen production host. [Laravel's current release notes](https://laravel.com/framework/docs/13.x/releases) list PHP 8.3 as the Laravel 13 minimum and security support through 2028-03-17. Pin the exact framework and PHP patch versions during implementation.

## Application boundary

- One Laravel application serves public, English-language company and service pages.
- Essential content, navigation, and inquiry form render on the server; Blade templates are the default implementation approach. Progressive JavaScript may improve interaction but must not be required for core tasks.
- No visitor account, admin portal, CMS, CRM, analytics, or application database is required for MVP.
- A company-owned inquiry destination is configured by environment, not hard-coded. Laravel's mail interface is the default; an alternative company-owned queue requires technical review.
- The new site's public URLs are defined after Figma information architecture. Legacy infrastructure and cloud/software URLs must redirect or resolve if the existing domain is used.

## Required Laravel behavior

| Area | Implementation constraint | SRS link |
| --- | --- | --- |
| Public pages | Routes return semantic server-rendered pages and approved metadata. | FR-01–05, CR-01–05, NFR-10 |
| Inquiry input | Use server-side validation for required fields, allowed service choices, formats, and length limits; present accessible field errors. | FR-06–07 |
| Inquiry outcome | Delivery uses a configured company-owned destination; only confirmed acceptance shows success; failures are observable and recoverable. | FR-08, FR-11, NFR-07 |
| Abuse protection | Keep Laravel request-forgery middleware, rate-limit submissions, and add the chosen spam control. | FR-10 |
| Sensitive data | Do not put inquiry bodies or addresses in application logs, client assets, or analytics; use HTTPS and restricted operational access. | NFR-03–05 |
| Deployment | Use supported PHP/Laravel, environment-based secrets, health/error monitoring, and a tested recovery path. | NFR-08–09 |

The framework provides [validation](https://laravel.com/framework/docs/13.x/validation), [request-forgery protection](https://laravel.com/framework/docs/13.x/csrf), and [route rate limiting](https://laravel.com/framework/docs/13.x/routing). Their use here is an implementation constraint derived from the SRS; configuration details are decided during development.

## Later technical decisions

Hosting/PHP runtime, mail provider or company queue, spam-control mechanism, domain/DNS, monitoring, backups if data is persisted, and deployment/rollback ownership must be recorded before implementation or release as appropriate. Design can proceed with the baselined form and content behavior while these operational settings are settled.
