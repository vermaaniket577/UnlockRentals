# UnlockRentals - Visitor Tracking + Lead Capture + WhatsApp CRM System
## Complete Production Architecture & Deployment Guide

This document covers the comprehensive, privacy-first **Visitor Tracking + Lead Capture + WhatsApp CRM System** engineered for UnlockRentals.com.

---

## 1. System Architecture & Capabilities

### 🛡️ Privacy & Legal Compliance (DPDP Act India & GDPR)
- **Zero creepy fingerprinting**: Does not attempt to guess or harvest personal identity, names, phone numbers, or emails from anonymous browsers.
- **Explicit Consent**: Personal details are collected **only** when voluntarily entered into lead capture modals or contact forms with an explicit consent checkbox.
- **Anonymous Tracking**: Visitors are assigned a cryptographically secure random UUID (`ur_visitor_uuid`) stored in a 365-day cookie.
- **Automated Retention Enforcement**: Anonymous visitor logs and events older than the configured threshold (default: 30 days) are automatically purged from the database via an automated scheduled cron job.

---

## 2. File Map & Key Components

### Database & Migrations
| File | Description |
|---|---|
| `database/migrations/2026_09_20_100000_create_visitor_tracking_tables.php` | Creates `visitors`, `visitor_sessions`, `visitor_events`, `visitor_daily_statistics` |
| `database/migrations/2026_09_20_100001_create_leads_crm_tables.php` | Creates `leads`, `lead_follow_ups`, `communication_logs`, `consent_records`, `crm_audit_logs` |
| `database/seeders/VisitorCrmSeeder.php` | Seeds realistic Indian rental visitors, sessions, events, leads, and follow-ups |

### Eloquent Models
| File | Description |
|---|---|
| `app/Models/Visitor.php` | Tracks visitor lifecycle, engagement score, device, and sessions |
| `app/Models/VisitorSession.php` | Session management, duration, landing page, UTM tracking |
| `app/Models/VisitorEvent.php` | Granular telemetry (page views, property views, WhatsApp clicks) |
| `app/Models/VisitorDailyStatistic.php` | Daily pre-aggregated metrics for high-speed dashboards |
| `app/Models/Lead.php` | Lead records, requirements, intent, budget, status pipeline |
| `app/Models/LeadFollowUp.php` | Follow-up tasks (call, WhatsApp, site visit), outcomes, reminders |
| `app/Models/CommunicationLog.php` | Inbound/outbound WhatsApp message delivery & read receipt log |
| `app/Models/ConsentRecord.php` | DPDP consent audit log (IP, user agent, timestamp, action) |
| `app/Models/CrmAuditLog.php` | Internal staff activity audit trail |

### Tracking & WhatsApp Services
| File | Description |
|---|---|
| `app/Services/VisitorTrackingService.php` | Core engine managing cookies, sessions, and engagement scoring |
| `app/Http/Middleware/TrackVisitor.php` | Non-blocking web middleware capturing visits & UTM campaign tags |
| `public/js/visitor-tracker.js` | Ultra-lightweight (3KB), zero-dependency asynchronous client tracker |
| `app/Services/WhatsApp/WhatsAppService.php` | Provider-agnostic WhatsApp dispatcher with consent verification |
| `app/Services/WhatsApp/MetaCloudWhatsAppProvider.php` | Official Meta Graph API v18.0 Cloud API integration |
| `app/Services/WhatsApp/TwilioWhatsAppProvider.php` | Twilio WhatsApp API provider |
| `app/Services/WhatsApp/LogWhatsAppProvider.php` | Offline test driver logging messages to database and logs |

### Admin CRM Controllers & Views
| File | Description |
|---|---|
| `app/Http/Controllers/Admin/VisitorDashboardController.php` | Analytics dashboard & chronological visitor journeys |
| `app/Http/Controllers/Admin/LeadCrmController.php` | Leads pipeline, status updates, 1-click WhatsApp, CSV export |
| `app/Http/Controllers/Admin/FollowUpController.php` | Follow-up task pipeline (Today, Overdue, Upcoming, Completed) |
| `app/Http/Controllers/Admin/CrmSettingsController.php` | WhatsApp credentials, test dispatcher, and data retention settings |
| `resources/views/admin/visitors/index.blade.php` | Visual analytics, KPI cards, Chart.js area charts, visitor table |
| `resources/views/admin/visitors/show.blade.php` | 360° visitor timeline, viewed properties, and session history |
| `resources/views/admin/leads/index.blade.php` | CRM pipeline workspace, filters, quick actions, manual lead entry |
| `resources/views/admin/leads/show.blade.php` | Lead dossier, internal notes, WhatsApp chat modal, matching listings |
| `resources/views/admin/follow-ups/index.blade.php` | Central task management hub with status update forms |
| `resources/views/admin/settings/visitor-tracking.blade.php` | Settings UI for WhatsApp keys and data retention policies |

### Public Conversion & Lead Modals
| File | Description |
|---|---|
| `resources/views/components/cookie-consent.blade.php` | DPDP/GDPR compliant bottom consent bar |
| `resources/views/components/exit-intent-modal.blade.php` | Non-intrusive exit-intent capture ("Still looking for a rental?") |
| `resources/views/components/lead-modals.blade.php` | WhatsApp Alerts, Similar Properties, and Schedule Visit modals |

---

## 3. Production Deployment Guide (cPanel / Live Server)

### Step 1: Pull the Latest Code
On your live server terminal (or Git deployer):
```bash
git pull origin main
```

### Step 2: Run Database Migrations
You have two easy ways to migrate:

#### Option A: Via SSH / Terminal:
```bash
php artisan migrate --force
```

#### Option B: Via Secure Browser Web URL (cPanel):
Open this URL in your web browser:
```
https://unlockrentals.com/run-migrations?key=UnlockRentalsSecureMigrateKey2026
```
*(Both new migration files are registered in `$localMigrations` in `routes/web.php` and will execute automatically).*

### Step 3: Clear and Rebuild Laravel Caches
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan route:cache
```

---

## 4. WhatsApp Gateway Configuration

Navigate to your Admin Portal:
👉 **UnlockRentals Admin** → **WhatsApp & CRM Settings** (`/admin/crm-settings`)

### Option A: Meta Cloud API (Recommended)
1. Go to [developers.facebook.com](https://developers.facebook.com) and create or select your WhatsApp Business App.
2. In the WhatsApp product settings, copy your:
   - **Phone Number ID**
   - **WhatsApp Business Account ID**
   - **Permanent System User Access Token** (generated under Business Manager → System Users)
3. Paste these into `/admin/crm-settings`.
4. In Meta App Dashboard → WhatsApp → Configuration:
   - Set **Callback URL**: `https://unlockrentals.com/webhook/whatsapp`
   - Set **Verify Token**: `unlockrentals_whatsapp_verify_2026` (or the custom token set in your admin settings)
   - Subscribe to the `messages` webhook field.

### Option B: Twilio
1. Select **Twilio Messaging** in `/admin/crm-settings`.
2. Enter your **Account SID**, **Auth Token**, and **Twilio Sender Number** (e.g. `whatsapp:+14155238886`).
3. Set your Twilio Inbound WhatsApp Webhook URL to `https://unlockrentals.com/webhook/whatsapp`.

### Option C: Local Log (Development / Test)
- If you don't have active WhatsApp API credentials yet, select **Local Log (Dev)**.
- All outbound messages and property alerts will be stored in the database `communication_logs` table without failing or incurring costs.

---

## 5. Automated Background Tasks & Cron Setup

Add this single cron entry to your cPanel or server crontab:
```bash
* * * * * cd /path/to/unlockrentals && php artisan schedule:run >> /dev/null 2>&1
```

This automatically triggers:
1. `visitor:aggregate-daily` — Runs daily at 00:05 to calculate daily visits, bounce rate, and lead totals.
2. `visitor:cleanup-retention` — Runs daily at 02:00 to purge unconverted anonymous visitors older than the configured retention threshold (default: 30 days).

---

## 6. Testing & Validation Checklist

- [x] Database tables created & verified with foreign keys.
- [x] Visitor tracking cookie (`ur_visitor_uuid`) attached automatically on page load.
- [x] Client tracker emits non-blocking beacon events for page views, property views, and WhatsApp clicks.
- [x] DPDP/GDPR cookie banner records visitor consent audit.
- [x] Lead capture modal submits with CSRF & anti-spam honeypot protection.
- [x] Leads pipeline dashboard displays real-time statistics, filters, and CSV export.
- [x] 1-Click WhatsApp modal sends direct messages and logs delivery receipts.
- [x] Follow-ups Hub allows scheduling and marking completion with outcomes.
