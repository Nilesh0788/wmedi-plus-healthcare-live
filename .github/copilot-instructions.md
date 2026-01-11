# AI Coding Guidelines for WMedi Plus Healthcare Platform

## 📋 Project Overview

**WMedi Plus** is a WordPress-based healthcare platform connecting patients with doctors. It's implemented as a custom plugin (`wmedi-plus-healthcare`) with 7 user pages, 7 backend classes, and 6 custom database tables. The plugin runs on Docker with WordPress + MySQL.

**Key URLs:**
- Landing page: `http://localhost:8080/welcome`
- Plugin path: `wp-content/plugins/wmedi-plus-healthcare/`

---

## 🏗️ Architecture

### Plugin Structure
```
wmedi-plus-healthcare/
├── wmedi-plus-healthcare.php          # Main plugin file, initializes all classes
├── includes/
│   ├── class-database.php             # Creates 6 custom tables on activation
│   ├── class-authentication.php        # Login/signup AJAX handlers
│   ├── class-pages.php                 # Auto-creates pages + shortcode rendering
│   ├── class-doctor-matching.php       # Matching algorithm (symptom → doctors)
│   ├── class-appointments.php          # Booking system, email notifications
│   ├── class-ajax-handlers.php         # Form submissions, data validation
│   └── class-enqueue.php               # Asset loading + nonce injection
├── templates/                          # 8 public-facing templates
└── assets/css/, assets/js/            # Styles and scripts
```

### User Flow
1. **Landing** (landing-page.php) → Patient/Doctor role selection
2. **Auth** (authentication.php) → Signup/login with form validation
3. **Onboarding** (onboarding.php) → Process overview [Patient only]
4. **Medical Query** (medical-query.php) → Symptoms form [Patient only]
5. **Doctor Selection** (doctor-selection.php) → Matching results [Patient only]
6. **Appointment Booking** (appointment-booking.php) → Date/time selection [Patient only]
7. **Dashboards** (patient-dashboard.php, doctor-dashboard.php) → Role-specific views

---

## 🔑 Critical Patterns

### 1. **AJAX Handlers - Standard Pattern**
All AJAX endpoints follow this structure:
- Actions defined in `class-authentication.php` and `class-ajax-handlers.php`
- **Always check nonce**: `check_ajax_referer('wmedi_nonce', 'nonce');`
- **Sanitize inputs**: `sanitize_text_field()`, `sanitize_email()`, `intval()`
- **Response format**: `wp_send_json_success()` or `wp_send_json_error(array('message' => '...'))`

Example:
```php
public function handle_signup() {
    check_ajax_referer('wmedi_nonce', 'nonce');
    $email = sanitize_email($_POST['email']);
    if (email_exists($email)) {
        wp_send_json_error(array('message' => 'Email already registered'));
    }
    // Process...
    wp_send_json_success(array('user_id' => $user_id));
}
```

### 2. **Database Operations**
- Use global `$wpdb` for queries
- Custom tables use prefix: `$wpdb->prefix . 'wmedi_'` (e.g., `wp_wmedi_users`)
- **6 Core Tables**: `wmedi_users`, `wmedi_doctors`, `wmedi_medical_queries`, `wmedi_appointments`, `wmedi_availability_slots`, `wmedi_doctor_matches`
- Always use placeholders: `$wpdb->insert(..., array(...), array('%d', '%s', ...));`

### 3. **Page/Template System**
- Pages auto-created on plugin activation via `class-pages.php::create_pages()`
- Uses **shortcodes** as entry points: `[wmedi_landing_page]`, `[wmedi_auth]`, etc.
- Shortcode handlers render templates from `templates/` directory
- Check `get_current_user_id()` and user type to control access

### 4. **Doctor Matching Algorithm** (class-doctor-matching.php)
Scoring formula:
- Base: 50 points
- Symptom-specialization match: +20 per match
- Experience bonus: +1.5 per year (max 20)
- Rating bonus: +5 per rating point (max 15)
- **Total capped at 100**

Example: Cardiologist with 10 years & 4.5 rating treating "chest pain" = 50 + 20 + 15 + 22.5 = 100

### 5. **Authentication & Sessions**
- Uses WordPress native `wp_create_user()`, `wp_authenticate()`
- User type stored in `wmedi_users` table: `user_type` = 'patient' or 'doctor'
- Session check: `get_current_user_id()` and query `wmedi_users.user_type`
- No custom authentication tokens—relies on WordPress cookies

### 6. **Form Validation**
- Always validate **before** processing (see authentication.php for examples)
- Check: email format, required fields, email uniqueness
- Return `wp_send_json_error()` with user-friendly messages
- **No form will process without passing validation**

---

## 🔄 Data Flow Examples

### Patient Registration Flow
```
Frontend (authentication.php form)
  ↓ [AJAX: wmedi_signup + sanitization]
class-authentication.php::handle_signup()
  ↓ [Nonce verified, inputs validated]
wp_create_user() [WordPress core]
  ↓ [User stored in wp_users]
INSERT into wp_wmedi_users [Extended profile]
  ↓ [Response to frontend]
Frontend: Redirect to /get-started
```

### Doctor Matching Flow
```
Patient submits medical query (medical-query.php)
  ↓ [AJAX: wmedi_submit_query]
class-ajax-handlers.php::submit_medical_query()
  ↓ [Query stored in wp_wmedi_medical_queries]
class-doctor-matching.php::match_doctors()
  ↓ [Queries wp_wmedi_doctors, calculates scores]
Ranked doctors returned to frontend
  ↓
doctor-selection.php renders matched doctors with % scores
```

---

## 🛠️ Development Workflows

### Adding a New Page
1. Create template file in `templates/new-page.php`
2. Add shortcode in `class-pages.php::__construct()`: `add_shortcode('wmedi_new_page', array($this, 'render_new_page'));`
3. Add render method: `public function render_new_page() { include WMEDI_PLUGIN_DIR . 'templates/new-page.php'; }`
4. Create page in `create_pages()` with `post_content` = `[wmedi_new_page]`

### Adding a New AJAX Endpoint
1. Register action in class `__construct()`:
   ```php
   add_action('wp_ajax_nopriv_wmedi_action', array($this, 'handle_action'));
   add_action('wp_ajax_wmedi_action', array($this, 'handle_action'));
   ```
2. Implement handler with nonce check, sanitization, validation
3. Call via JS: `jQuery.post(ajaxurl, {action: 'wmedi_action', nonce: wmedi_nonce, ...}, callback);`

### Accessing Current User Type
```php
$user_id = get_current_user_id();
$user_type = $wpdb->get_var(
    $wpdb->prepare("SELECT user_type FROM {$wpdb->prefix}wmedi_users WHERE user_id = %d", $user_id)
);
// Check: if ($user_type === 'doctor') { ... }
```

---

## 📂 Key Files & Responsibilities

| File | Purpose |
|------|---------|
| `wmedi-plus-healthcare.php` | Plugin entry point, class initialization |
| `class-database.php` | Table creation, schema definition |
| `class-pages.php` | Page auto-creation, shortcode rendering |
| `class-authentication.php` | User signup/login, session management |
| `class-doctor-matching.php` | Symptom-to-doctor matching logic |
| `class-appointments.php` | Booking system, availability slots |
| `class-ajax-handlers.php` | Form submission handlers |
| `class-enqueue.php` | CSS/JS loading, nonce injection |

---

## ⚠️ Common Pitfalls

1. **Forgetting nonce check** → Security risk, AJAX fails
2. **Not sanitizing inputs** → Data corruption, XSS vulnerabilities
3. **Querying without `$wpdb->prepare()`** → SQL injection risk
4. **Hardcoding plugin path** → Use `WMEDI_PLUGIN_DIR` constant instead
5. **Checking user type from `wp_users.role`** → Wrong table, use `wmedi_users.user_type`
6. **Not including class files** → All 7 classes required at plugin init
7. **Assuming user is logged in** → Always check `get_current_user_id()` before querying user data

---

## 🔌 Docker & Testing

- **Start**: `docker-compose up` (runs WordPress on port 8080, MySQL on 3306)
- **Activate**: Go to `/wp-admin/` → Plugins → Activate "WMedi Plus Healthcare Platform"
- **Test Patient Flow**: `/welcome` → select Patient → signup → medical query → view doctors → book appointment
- **Test Doctor Flow**: `/welcome` → select Doctor → signup → `/doctor-dashboard`
- **Access DB**: MySQL host `db`, user `wordpress`, password `wordpress`, DB `wordpress`

---

## 📚 Reference Documentation

- Full build details: `WMEDI_BUILD_SUMMARY.md` (590 lines, all 7 pages explained)
- Implementation guide: `WMEDI_IMPLEMENTATION_GUIDE.md` (class details, customization)
- Quick start: `WMEDI_QUICK_START.md` (activation steps, testing flows)
---

## 🧪 Test & Debug Workflows

- **Local Dev/Test:**
  - Use `docker-compose up` for a full stack (WordPress+MySQL) local environment.
  - Access logs: `docker-compose logs wordpress` or `docker-compose logs db` for troubleshooting.
  - Use `/wp-admin/` for plugin activation and manual UI testing.
  - For AJAX: Use browser dev tools (Network tab) to inspect requests to `admin-ajax.php`.
  - Database: Connect to MySQL with `user: wordpress`, `password: wordpress`, DB `wordpress` (host: `db`).

- **Debugging:**
  - Add `error_log()` calls in PHP for quick diagnostics (logs to container's PHP error log).
  - Use `WP_DEBUG` and `WP_DEBUG_LOG` in `wp-config.php` for verbose error output.
  - For JS: Use `console.log` and browser dev tools.
  - To test email flows, use a local SMTP capture tool (e.g., MailHog) or check logs for mail errors.

- **Resetting State:**
  - To reset DB: `docker-compose down -v` (removes all data!)
  - To re-run plugin activation: deactivate and reactivate in `/wp-admin/`.

---

## 🔗 Cross-File & Integration Patterns

- **Class Initialization:** All 7 core classes are loaded in `wmedi-plus-healthcare.php` and must be present for the plugin to function.
- **AJAX → DB → Template:** AJAX handlers (in `class-authentication.php`, `class-ajax-handlers.php`) validate/sanitize, call DB methods (in `class-database.php`), and return data for rendering in templates.
- **Shortcodes:** Defined in `class-pages.php`, mapped to template files in `templates/`. Adding a new page requires updates in both `class-pages.php` and the `templates/` directory.
- **User Type Checks:** Always use the `wmedi_users` table for role logic, not WordPress roles. Example: see how dashboards and booking logic branch on user type.
- **Doctor Matching:** `class-doctor-matching.php` is called from AJAX handlers, which in turn is triggered by the medical query form. The result is rendered in `doctor-selection.php`.
- **Asset Loading:** All CSS/JS is enqueued via `class-enqueue.php`—never hardcode `<script>` or `<link>` tags in templates.

---

## 🛠️ Advanced Customization Examples

- **Add a New Specialization:**
  - Update doctor signup form in `templates/authentication.php` and validation in `class-authentication.php`.
  - Add new specialization logic in `class-doctor-matching.php` if needed for matching.

- **Custom Email Notifications:**
  - Edit or extend email logic in `class-appointments.php` (look for `wp_mail()` usage).
  - To add new triggers, hook into appointment status changes.

- **Change UI/Branding:**
  - Edit CSS in `assets/css/` and templates in `templates/`.
  - For new JS features, add scripts to `assets/js/` and enqueue via `class-enqueue.php`.

- **Add a New Dashboard Widget:**
  - Create a new PHP partial in `templates/`.
  - Render it from the appropriate dashboard template and control access by user type.

- **Integrate Third-Party APIs:**
  - Add API logic in a new class in `includes/`.
  - Call from AJAX handler or page template as needed.
  - Always sanitize and validate all external data.

---
