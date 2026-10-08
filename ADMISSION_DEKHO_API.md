# Admission Dekho — Lead Intake API Documentation

> **Version:** 1.0  
> **Base URL:** `https://www.unlockrentals.com`  
> **Last Updated:** October 2026

---

## Overview

This API allows **Admission Dekho** (`admissionsdekho.com`) to send student enquiry form submissions directly to the **UnlockRentals CRM** database. Every valid form submission is stored as a lead and is immediately visible in the admin CRM dashboard.

---

## Authentication

| Item | Value |
|------|-------|
| **Auth Required** | ❌ No (Public API) |
| **CSRF Token** | ❌ Not required |
| **CORS** | ✅ All origins allowed (`*`) |
| **Rate Limiting** | Standard server-level throttle |

> The API is open for direct `POST` submissions from any origin (browser forms, JavaScript fetch, server-to-server, etc.).

---

## Endpoints

### 1. Submit Lead / Enquiry

Submit a student enquiry form to create or update a lead in the CRM.

| Method | URL |
|--------|-----|
| `POST` | `/api/admission-dekho/enquiry` |
| `POST` | `/api/admission-dekho/lead` |
| `POST` | `/api/admission-dekho` |
| `POST` | `/admission-dekho/enquiry` |
| `POST` | `/admission-dekho` |
| `POST` | `/api/v1/admission-enquiry` |

All endpoints above are **identical** — use whichever is most convenient.

---

### 2. API Status Check

Check if the API is online and view the accepted field schema.

| Method | URL |
|--------|-----|
| `GET`  | `/api/admission-dekho` |

**Response:** JSON with API status, version, and accepted form fields.

---

## Request Format

The API accepts data in any of these formats:
- `application/json` (recommended)
- `application/x-www-form-urlencoded`
- `multipart/form-data`

---

## Form Fields

### Required Fields

| Field | Type | Description | Example |
|-------|------|-------------|---------|
| `contact` | string | **10-digit mobile number** (Primary identifier for deduplication) | `"9876543210"` |

> **Alternate field names accepted:** `phone`, `mobile`, `phoneNumber`, `phone_number`, `student_mobile`, `tel`

### Optional Fields

| Field | Type | Description | Example |
|-------|------|-------------|---------|
| `firstName` | string | First name of the student | `"Rahul"` |
| `lastName` | string | Last name of the student | `"Sharma"` |
| `name` | string | Full name (used if firstName/lastName not provided) | `"Rahul Sharma"` |
| `email` | string | Email address | `"rahul@example.com"` |
| `state` | string | State / Region | `"Uttar Pradesh"` |
| `city` | string | City | `"Lucknow"` |
| `course` | string | Desired course or program | `"B.Tech Computer Science"` |
| `stream` | string | Academic stream (alias for course) | `"Engineering"` |
| `subject` | string | Academic subject or form context | `"Mathematics"` |
| `specialization` | string | Area of specialization | `"Data Science"` |
| `degree` | string | Degree type | `"MBA"` |
| `college` | string | Preferred college | `"IIT Delhi"` |
| `university` | string | Preferred university | `"Delhi University"` |
| `message` | string | Student enquiry / query text | `"I want admission in B.Tech"` |
| `pageRef` | string | URL/path where form was submitted | `"/colleges/iit-delhi"` |

### Alternate Field Names

The API is **flexible** and accepts multiple naming conventions for each field:

| Standard Field | Also Accepts |
|---------------|--------------|
| `firstName` | `first_name`, `first`, `fname` |
| `lastName` | `last_name`, `last`, `lname` |
| `name` | `full_name`, `student_name`, `candidate_name` |
| `email` | `student_email` |
| `contact` | `phone`, `mobile`, `phoneNumber`, `phone_number`, `student_mobile`, `tel` |
| `state` | `region`, `province` |
| `city` | `preferred_city`, `district` |
| `course` | `stream`, `program` |
| `message` | `enquiry`, `query`, `notes`, `comments` |
| `pageRef` | `page`, `url` |

---

## Response Format

### Success — New Lead Created (HTTP 201)

```json
{
    "success": true,
    "message": "Admission enquiry received and saved successfully in CRM.",
    "action": "created",
    "lead_id": 42,
    "lead": {
        "id": 42,
        "name": "Rahul Sharma",
        "first_name": "Rahul",
        "last_name": "Sharma",
        "email": "rahul@example.com",
        "phone": "9876543210",
        "contact": "9876543210",
        "city": "Lucknow",
        "state": "Uttar Pradesh",
        "course": "B.Tech Computer Science",
        "stream": "B.Tech Computer Science",
        "source": "Admission Dekho",
        "status": "new",
        "created_at": "2026-10-08T09:00:00+05:30"
    }
}
```

### Success — Existing Lead Updated (HTTP 200)

When a lead with the same phone number already exists, the enquiry is appended to the existing record:

```json
{
    "success": true,
    "message": "Admission enquiry received and saved successfully in CRM.",
    "action": "updated",
    "lead_id": 42,
    "lead": { ... }
}
```

### Error — Missing Phone Number (HTTP 422)

```json
{
    "success": false,
    "message": "A valid 10-digit mobile or contact number is required.",
    "errors": {
        "contact": ["Please enter a valid 10-digit phone number."]
    }
}
```

---

## Integration Examples

### 1. JavaScript (Fetch API)

```javascript
// Submit enquiry from browser form
async function submitEnquiry(formData) {
    const response = await fetch('https://www.unlockrentals.com/api/admission-dekho/enquiry', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            firstName: formData.firstName,
            lastName: formData.lastName,
            contact: formData.phone,
            email: formData.email,
            state: formData.state,
            city: formData.city,
            course: formData.course,
            message: formData.message,
            pageRef: window.location.pathname
        })
    });

    const result = await response.json();
    
    if (result.success) {
        console.log('Lead saved:', result.lead_id);
        // Show success message to student
    } else {
        console.error('Error:', result.message);
        // Show validation error
    }
}
```

### 2. HTML Form (Direct POST)

```html
<form action="https://www.unlockrentals.com/api/admission-dekho/enquiry" 
      method="POST" 
      enctype="application/x-www-form-urlencoded">
    
    <input type="text" name="firstName" placeholder="First Name" required>
    <input type="text" name="lastName" placeholder="Last Name">
    <input type="tel" name="contact" placeholder="Mobile Number" required>
    <input type="email" name="email" placeholder="Email">
    <input type="text" name="state" placeholder="State">
    <input type="text" name="city" placeholder="City">
    <input type="text" name="course" placeholder="Course / Stream">
    <textarea name="message" placeholder="Your Query"></textarea>
    <input type="hidden" name="pageRef" value="/enquiry-form">
    
    <button type="submit">Submit Enquiry</button>
</form>
```

### 3. jQuery AJAX

```javascript
$.ajax({
    url: 'https://www.unlockrentals.com/api/admission-dekho/enquiry',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({
        name: $('#fullName').val(),
        contact: $('#phone').val(),
        email: $('#email').val(),
        course: $('#course').val(),
        city: $('#city').val(),
        state: $('#state').val(),
        message: $('#message').val()
    }),
    success: function(res) {
        alert('Enquiry submitted successfully!');
    },
    error: function(xhr) {
        alert('Error: ' + xhr.responseJSON.message);
    }
});
```

### 4. PHP (cURL — Server-to-Server)

```php
<?php
$data = [
    'firstName'  => 'Rahul',
    'lastName'   => 'Sharma',
    'contact'    => '9876543210',
    'email'      => 'rahul@example.com',
    'state'      => 'Uttar Pradesh',
    'city'       => 'Lucknow',
    'course'     => 'B.Tech Computer Science',
    'message'    => 'I want admission details for B.Tech CSE',
    'pageRef'    => '/colleges/iit-delhi'
];

$ch = curl_init('https://www.unlockrentals.com/api/admission-dekho/enquiry');
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'Accept: application/json'],
    CURLOPT_POSTFIELDS     => json_encode($data),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if ($result['success']) {
    echo "Lead #{$result['lead_id']} {$result['action']} successfully!";
} else {
    echo "Error: {$result['message']}";
}
```

### 5. Python (requests)

```python
import requests

data = {
    "firstName": "Rahul",
    "lastName": "Sharma",
    "contact": "9876543210",
    "email": "rahul@example.com",
    "state": "Uttar Pradesh",
    "city": "Lucknow",
    "course": "B.Tech Computer Science",
    "message": "I want admission details"
}

response = requests.post(
    "https://www.unlockrentals.com/api/admission-dekho/enquiry",
    json=data,
    headers={"Accept": "application/json"}
)

result = response.json()
print(f"Lead {result['action']}: ID #{result['lead_id']}")
```

### 6. Node.js (Axios / Fetch)

```javascript
const axios = require('axios');

async function sendAdmissionLead(leadData) {
    try {
        const response = await axios.post('https://www.unlockrentals.com/api/admission-dekho/enquiry', {
            firstName: leadData.name.split(' ')[0] || leadData.name,
            lastName: leadData.name.split(' ').slice(1).join(' ') || '',
            contact: leadData.phone || leadData.mobile,
            email: leadData.email,
            course: leadData.course,
            city: leadData.city,
            state: leadData.state,
            message: leadData.query || leadData.message,
            pageRef: leadData.url || 'admissionsdekho.com'
        }, {
            headers: { 'Content-Type': 'application/json' }
        });

        console.log('Lead synced to UnlockRentals CRM:', response.data);
        return response.data;
    } catch (error) {
        console.error('Failed to sync lead:', error.response?.data || error.message);
    }
}
```

### 7. WordPress (functions.php Webhook for CF7 / Elementor / WPForms)

If Admission Dekho runs on WordPress, paste this in your theme's `functions.php`:

```php
// Hook into Contact Form 7 submissions and sync to UnlockRentals CRM
add_action('wpcf7_mail_sent', function($contact_form) {
    $submission = WPCF7_Submission::get_instance();
    if ($submission) {
        $posted_data = $submission->get_posted_data();

        $payload = [
            'name'    => $posted_data['your-name'] ?? $posted_data['name'] ?? '',
            'contact' => $posted_data['your-tel'] ?? $posted_data['phone'] ?? $posted_data['mobile'] ?? '',
            'email'   => $posted_data['your-email'] ?? $posted_data['email'] ?? '',
            'course'  => $posted_data['your-course'] ?? $posted_data['course'] ?? '',
            'city'    => $posted_data['your-city'] ?? $posted_data['city'] ?? '',
            'message' => $posted_data['your-message'] ?? $posted_data['message'] ?? '',
            'pageRef' => home_url($_SERVER['REQUEST_URI'] ?? ''),
        ];

        wp_remote_post('https://www.unlockrentals.com/api/admission-dekho/enquiry', [
            'headers'     => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'body'        => json_encode($payload),
            'blocking'    => false, // Non-blocking: will never slow down user form submission
            'timeout'     => 5,
        ]);
    }
});
```

---

## Database Migration & Bulk Sync

If you want to migrate existing leads from your **Admission Dekho database** directly to the **UnlockRentals database**:

### Batch Import API Endpoint

| Method | URL |
|--------|-----|
| `POST` | `/api/admission-dekho/bulk` |
| `POST` | `/api/admission-dekho/enquiry` (also accepts arrays) |

### Bulk Payload Example:

```json
{
  "leads": [
    {
      "name": "Amit Kumar",
      "contact": "9812345678",
      "email": "amit@gmail.com",
      "course": "B.Tech CSE",
      "city": "Noida",
      "created_at": "2026-05-15 14:20:00"
    },
    {
      "name": "Pooja Verma",
      "contact": "9876543211",
      "email": "pooja@gmail.com",
      "course": "MBA",
      "city": "Delhi",
      "created_at": "2026-06-10 11:00:00"
    }
  ]
}
```

### Standalone Migration Script (`sync_admission_dekho_db.php`)

A ready-to-run PHP script is included in the UnlockRentals root folder:
[sync_admission_dekho_db.php](file:///c:/xampp/htdocs/UnlockRentals-main/UnlockRentals-main/sync_admission_dekho_db.php)

To run the migration:
1. Open `sync_admission_dekho_db.php` and set your Admission Dekho database credentials (`host`, `database`, `username`, `password`, `table`).
2. Run in terminal:
   ```bash
   php sync_admission_dekho_db.php
   ```
3. It will connect to Admission Dekho's database, extract all student enquiries, map fields, preserve timestamps, and sync them in batches into UnlockRentals database!

---

## Where Leads Appear in UnlockRentals CRM

Once leads arrive from Admission Dekho:
1. **Live Leads CRM Pipeline:** Visit `/admin/leads`
2. **Category Filter:** Click the **Admission & Education Leads** tab or filter by **🎓 Admission Dekho** in the Sources dropdown.
3. **Lead Details Displayed:**
   - Student Name, Mobile Number, Email
   - Source Tag: `🎓 Admission Dekho`
   - Course / Stream Pill (e.g., `🎓 B.Tech Computer Science`, `🎓 MBA`)
   - Submission Timestamp & Live "Time Ago" ticker (e.g. `Just now`, `5 mins ago`)
   - Direct Outreach: One-click **WhatsApp Chat** (`wa.me/91...`) and **Direct Phone Call**.

---

## Deduplication Logic

- **Primary Key:** 10-digit mobile number (last 10 digits extracted)
- **New phone → New lead** is created (HTTP 201)
- **Existing phone → Lead updated** with the new enquiry appended to message history (HTTP 200)
- The system automatically strips country codes, spaces, dashes, and special characters from phone numbers
- Original database creation timestamps are preserved for accurate historical records

---

## CORS Configuration

The API supports **cross-origin requests** from any domain:

```
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS
Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept
```

Preflight `OPTIONS` requests are handled automatically and return `204 No Content`.

---

## Testing

### Quick Test with cURL

```bash
curl -X POST https://www.unlockrentals.com/api/admission-dekho/enquiry \
  -H "Content-Type: application/json" \
  -d '{
    "firstName": "Test",
    "lastName": "Student",
    "contact": "9999999999",
    "email": "test@admissiondekho.com",
    "course": "MBA",
    "city": "New Delhi",
    "state": "Delhi",
    "message": "Test enquiry from API docs"
  }'
```

### Check API Status

```bash
curl https://www.unlockrentals.com/api/admission-dekho
```

---

## Support & Contact

For integration support or technical queries:

- **Website:** [https://www.unlockrentals.com](https://www.unlockrentals.com)
- **API Status:** `GET /api/admission-dekho`

---

*© 2026 UnlockRentals. All rights reserved.*

