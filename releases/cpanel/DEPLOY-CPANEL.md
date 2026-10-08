# Deploying the Tricetech website to cPanel

This archive installs the site in a **split layout**: the Laravel application sits
next to `public_html`, and only the public entry point is reachable from the web.

```
~ (your cPanel home directory)
├── tricetech_app/          <- Laravel application, config, vendor, storage
└── public_html/            <- web root: index.php, .htaccess, build assets
```

Nothing inside `tricetech_app/` is ever served directly, so `.env`, `storage/`,
`vendor/` and the application source are not exposed to the internet.

---

## 1. Before you upload

**Server requirements**

- PHP **8.3 or newer** (8.3, 8.4 or 8.5). Laravel 13 does not run on 8.2 or older.
  Set it in cPanel → **MultiPHP Manager**, or confirm it under
  cPanel → **Select PHP Version**.
- PHP extensions: `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`,
  `openssl`, `pcre`, `pdo`, `session`, `tokenizer`, `xml`. Nearly every cPanel host
  enables these by default; verify under **Select PHP Version → Extensions**.
- No database is required. Sessions, cache and rate limiting use files.
- An SSL certificate. Most hosts issue a free AutoSSL certificate. Confirm
  **Force HTTPS Redirect** is on after the site loads.

**Know where the web root is**

- **Main domain** → the web root is `public_html`. This archive is ready as-is.
- **Addon domain or subdomain** → cPanel gives it its own document root, for example
  `~/domains/demo.example.com/public_html`. Put the contents of the archive's
  `public_html/` folder into *that* folder, and place `tricetech_app/` so it is its
  **sibling** (one level up from the document root). The rule: `tricetech_app/` must
  sit directly beside the folder that `index.php` lives in.

---

## 2. Upload and extract

1. cPanel → **File Manager**.
2. Click the **Home** icon in the address bar so you are in your account's home
   directory — **not** inside `public_html`.
3. Click **Upload**, choose this `.zip`, and wait for it to finish. Go back to the
   File Manager view.
4. Right-click the uploaded `.zip` → **Extract** → **Extract Files**.
   Extract into the home directory (the path box should show `/home/youruser`).
   Leave the folder name field empty. Click **Extract**, then **Close**.
5. Delete the `.zip` from your home directory.

You should now see `tricetech_app/` and `public_html/` side by side.

> **If `public_html` already contained files** (a placeholder page from the host,
> or an older site), extract as above and then remove the leftover files —
> typically `default.php`, `index.html`, or a `cgi-bin` folder. Do not delete a
> `.well-known` folder; your SSL provider uses it. Back up anything you did not
> create before deleting it.

---

## 3. Confirm the files landed

In File Manager, open **Settings** (top right) and enable **Show Hidden Files**,
then **Save**. Hidden files matter here: `.htaccess` in `public_html` and `.env`
in `tricetech_app` are both required.

Expected contents:

```
tricetech_app/   app/  bootstrap/  config/  database/  resources/  routes/
                 storage/  vendor/  artisan  composer.json  composer.lock
                 .env  env.production.example
public_html/     index.php  .htaccess  favicon.svg  favicon.ico  build/
```

If `.env` is missing, copy `env.production.example` to `.env` and paste in the
`APP_KEY` value from this project's `releases/cpanel/app.key` file.

---

## 4. Set the site address

1. Open `tricetech_app/.env` in File Manager → **Edit**.
2. Change this line to the exact URL you will open in the browser:

   ```
   APP_URL=https://demo.example.com
   ```

   Use `https://` if SSL is active, and include `www.` only if you will browse with it.
3. **Save Changes.**

`.env` ships ready to use: `APP_ENV=production`, `APP_DEBUG=false`, and an
`APP_KEY` already generated. No terminal access is needed.

---

## 5. Make the writable folders writable

Laravel must be able to write sessions, compiled views and logs.

1. In File Manager, open `tricetech_app/`.
2. Right-click `storage` → **Change Permissions** → set to `755` → check
   **Recurse into subdirectories** → **Apply to** *all files and directories*.
3. Repeat for `bootstrap/cache`.

On most cPanel hosts PHP runs as your own account and `755` is correct. If the site
later reports "file_put_contents(): failed to open stream: Permission denied",
re-apply the same step with `775`, and only as a last resort `777` on `storage/`
and `bootstrap/cache/` alone.

---

## 6. Open the site

Visit your domain. Check:

- `/` loads with the red-on-white Tricetech design, fonts and spacing intact.
  **If the page appears as plain unstyled text**, the compiled assets did not land
  in `public_html/build/` — re-extract the archive.
- `/infrastructure`, `/cloud-software`, `/privacy` all load.
- A made-up address such as `/nothing-here` shows the branded "This page is not
  available." page.
- The menu button works at a narrow browser width.
- Submit the inquiry form with a test message. You should see a green
  "Inquiry received" notice.

---

## 7. Make the inquiry form send real email

**The shipped `.env` deliberately uses `MAIL_MAILER=log`.** In that mode the form
still reports success, but instead of emailing anyone it writes the message into
`tricetech_app/storage/logs/laravel.log`. That is why a submission produces no
notification — nothing is wrong with the form.

Confirm it first: open `tricetech_app/storage/logs/laravel.log`, and your test
submission is at the bottom, with the email address and the message body.

Note: an identical submission (same email, interest and message) inside 10 minutes
is treated as a duplicate and produces no second email. Change the message text when
testing repeatedly.

### 7.1 Test delivery before editing anything

Upload `mailcheck.php` **from the separate `mailcheck-for-cpanel.zip`** into
`public_html`, then open:

```
https://yourdomain/mailcheck.php?key=tt-mail-9f3c2b71a4
```

It prints the active mail settings and a preview of the notification without
sending. Add `&send=1` to actually send one to the configured recipients, and
`&to=you@yourdomain.com` to send to a single address instead. Add
`&mailer=sendmail` or `&mailer=smtp` to test a transport without editing `.env`:

```
https://yourdomain/mailcheck.php?key=tt-mail-9f3c2b71a4&mailer=sendmail&send=1
```

Whatever the mail server replies, this page shows it — the SMTP handshake error or
`SUCCESS`. **Delete `mailcheck.php` when you are done.**

### 7.2 Option A — the server's own mail service (no credentials)

Usually works on cPanel and needs nothing but an edit to `.env`:

```
MAIL_MAILER=sendmail
MAIL_SENDMAIL_PATH="/usr/sbin/sendmail -bs -i"
MAIL_FROM_ADDRESS="website@tricetech.ca"
MAIL_FROM_NAME="${APP_NAME}"
```

**Keep those quotes.** An unquoted space in a `.env` value — as in Laravel's own
stock example — makes the entire `.env` unparsable, and *every* page then returns a
blank 500 with `The environment file is invalid!` in the log. Quote any value that
contains a space.

### 7.3 Option B — SMTP through a real mailbox

Create the sending mailbox in cPanel → **Email Accounts** (for example
`website@tricetech.ca`), then **Connect Devices** shows the host. Replace the mail
block in `.env`:

```
MAIL_MAILER=smtp
MAIL_HOST=mail.tricetech.ca
MAIL_PORT=465
MAIL_USERNAME=website@tricetech.ca
MAIL_PASSWORD="the-mailbox-password"
MAIL_SCHEME=smtps
MAIL_FROM_ADDRESS="website@tricetech.ca"
MAIL_FROM_NAME="${APP_NAME}"
```

`MAIL_SCHEME` is `smtps` for port 465 (implicit TLS) and `smtp` for port 587
(STARTTLS). It may be omitted entirely: the application auto-selects `smtps` when
the port is 465 and `smtp` otherwise.

**Do not use `MAIL_ENCRYPTION`.** Laravel 11 and newer ignore it completely — the
framework no longer reads that key at all. Port and scheme decide the encryption.

### 7.4 Option C — the domain's email is on Microsoft 365 or Google Workspace

If `tricetech.ca` mail is hosted outside this cPanel server, `sendmail` will be
accepted locally but can be rejected or spam-foldered by the real mailbox provider,
because the sending server is not authorised in the domain's SPF record. Send
through the provider instead, and keep two rules:

- `MAIL_FROM_ADDRESS` **must be a mailbox you can authenticate as.** Office 365
  rejects `Sender address rejected` otherwise.
- Microsoft 365 blocks SMTP password auth by default: enable **SMTP AUTH** for that
  mailbox in the Microsoft 365 admin centre, and use an app password if MFA is on.

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_USERNAME=website@tricetech.ca
MAIL_PASSWORD="the-app-password"
MAIL_SCHEME=smtp
MAIL_FROM_ADDRESS="website@tricetech.ca"
```

For Google Workspace use `smtp.gmail.com`, port `587`, and an app password.

### 7.5 If it still does not arrive

- cPanel → **Email Deliverability**: names the missing `SPF` and `DKIM` DNS records
  for the domain. Fix them there; without them mail lands in spam or is rejected.
- cPanel → **Track Delivery**: shows what happened to each individual message.
- cPanel → **Email Accounts → Connect Devices**: the exact SMTP host and port.
- Keep `LOG_LEVEL=debug` while testing, so a failed delivery is visible in
  `laravel.log`. Set it to `error` once delivery works.

When real delivery fails the form shows a red error notice and keeps the visitor's
input — that is the intended behaviour, and it means you can test failure handling
safely without losing a real lead.

### 7.6 Who gets notified

`INQUIRY_RECIPIENT` takes a comma separated list, so several people are notified
about each submission at once — by default `info@`, `junaid@` and `coo@tricetech.ca`.
Every listed mailbox receives the notification, and replying to it answers the
visitor directly because the reply-to is set to their address. Remove or add
addresses in `.env`; no code change is needed. Only use mailboxes you can actually
read, and keep `MAIL_FROM_ADDRESS` on the sending domain so it is not marked as spam.

---

## 8. Troubleshooting

| Symptom | Cause and fix |
| --- | --- |
| Blank white page, no error text | Read the last lines of `tricetech_app/storage/logs/laravel.log`. Most often `storage/` permissions, or PHP below 8.3. |
| `500` on every page | Wrong PHP version. Switch to 8.3+ in MultiPHP Manager. |
| Unstyled plain-text page | `public_html/build/` is missing or incomplete. Re-extract the archive. |
| `Unable to locate file in Vite manifest` | The same cause as above. `index.php` must be the copy from this archive — it tells Laravel that `public_html` is the public path. |
| `No application encryption key has been specified` | `.env` is missing, or `APP_KEY` is empty. Copy `env.production.example` to `.env` and add the key. |
| Form says "Inquiry received" but no email arrives | `MAIL_MAILER=log` is still set — nothing is sent. See step 7, and run `mailcheck.php`. |
| Form shows a red "Delivery could not complete" notice | The mail transport refused the message. Run `mailcheck.php?send=1` to see the exact reply, or check `laravel.log`. |
| SMTP fails with `535` or `Authentication unsuccessful` | Wrong mailbox username or password. Re-copy them from cPanel → Email Accounts → Connect Devices. |
| SMTP fails with `Sender address rejected` | `MAIL_FROM_ADDRESS` is not the mailbox you logged in as. Office 365 refuses that. |
| Mail sends but never arrives, or lands in spam | DNS authentication is missing. cPanel → Email Deliverability shows the `SPF` and `DKIM` records to add; cPanel → Track Delivery shows each message's fate. |
| Only the first test submission arrives | Duplicate submissions within 10 minutes are suppressed on purpose. Change the message text between tests. |
| Form shows "Delivery could not complete" | Expected when the mail transport is unreachable. Check the mail settings in step 7. |
| `403 Forbidden` on the whole site | `index.php` permissions. Right-click it → Change Permissions → `644`. |
| Page loads over `http://` but not `https://` | SSL not issued yet, or Force HTTPS is off. Check the host's SSL/TLS Status page. |
| Redirects or links point at `example.com` | `APP_URL` in `.env` was not updated. See step 4. |

---

## 9. Redeploying after a change

From the project directory on the machine where the code lives, run:

```bash
bash releases/cpanel/build-release.sh
```

That rebuilds the frontend assets, installs production-only dependencies, and
writes a fresh `releases/tricetech-cpanel-<date>.zip`. Upload and extract it the
same way. To replace a previous deployment, extract over the top and keep your
existing `.env` (do not overwrite it).

---

## 10. Known issues in this build

These do not block a demo, but they affect launch:

1. **`public_html/robots.txt` is a static placeholder.** It is served directly by
   the web server and overrides the application's `/robots.txt` route, so the
   `Sitemap:` line never reaches search engines. Delete that file to let the route
   take over, or add the `Sitemap:` line to the file by hand.
2. **`npm run dev` would look broken locally.** The Content-Security-Policy allows
   scripts and styles from the site's own origin only, which blocks Vite's
   development server. Always check local changes with `npm run build` and a normal
   browser refresh.
3. **The privacy page contains a visible "Release review" note.** The company must
   confirm final privacy wording and mailbox retention before launch, then that
   paragraph should be removed.
4. **Rate limiting and duplicate suppression use the filesystem cache.** They work
   on a single server. A multi-server or load-balanced deployment needs a shared
   cache store.
5. **Inquiries are emailed during the request.** A slow mail host makes the visitor
   wait. A queue would fix this if submission volume grows.
