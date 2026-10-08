# Tricetech Solutions public website

Production-oriented Laravel 13 implementation of the approved Tricetech public website design. The application serves the company overview, infrastructure services, cloud/software services, privacy notice, inquiry workflow, SEO discovery files, legacy redirects, and a branded not-found page.

## Local setup

Requirements: PHP 8.3+, Composer 2, Node.js 20+ and npm.

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
php artisan serve
```

No application database is required. Sessions and rate-limit cache use the filesystem, and inquiry delivery runs synchronously through Laravel Mail.

## Inquiry configuration

Set the production mail transport and recipient in `.env`:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=website@example.com
MAIL_FROM_NAME="Tricetech Solutions"
INQUIRY_RECIPIENT="info@tricetech.ca,junaid@tricetech.ca,coo@tricetech.ca"
```

`INQUIRY_RECIPIENT` accepts a comma separated list. Every listed mailbox receives its
own copy of the notification, whose reply-to is set to the visitor so any recipient
can answer them directly.

The form validates email, approved service interest and a 10–2,000 character message. It includes CSRF protection, a honeypot, route throttling, ten-minute duplicate suppression, accessible errors, retained input after delivery failure, and generic logging that excludes the visitor's email and message.

## Validation

```bash
npm run build
php artisan test
vendor/bin/pint --test
composer audit
npm audit
```

Implementation and release decisions are recorded in [docs/09-implementation-and-release-handoff.md](docs/09-implementation-and-release-handoff.md).
