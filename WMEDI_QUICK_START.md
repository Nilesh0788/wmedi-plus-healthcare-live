# 🏥 WMedi Plus Healthcare Platform - Quick Start Guide

## ⚡ Get Started in 3 Steps

### Step 1: Activate the Plugin
1. Open WordPress Admin Panel
2. Go to **Plugins** → **Installed Plugins**
3. Find **"WMedi Plus Healthcare Platform"**
4. Click **"Activate"**
5. Database tables will be created automatically ✅

### Step 2: Verify Pages Were Created
1. Go to **Pages** in WordPress Admin
2. You should see 8 new pages:
   - Welcome to WMedi Plus
   - Secure Authentication
   - Get Started with WMedi Plus
   - Tell Us About Your Health
   - Choose Your Doctor
   - Book Appointment
   - Patient Dashboard
   - Doctor Dashboard

### Step 3: Access Your Platform
Open these URLs in your browser:

#### Patient Flow
```
1. http://localhost:8080/welcome           (Start here!)
2. Click "Login / Signup as Patient"
3. Fill signup form
4. You're in the patient onboarding!
```

#### Doctor Flow
```
1. http://localhost:8080/welcome           (Start here!)
2. Click "Login / Signup as Doctor"
3. Fill signup form (add specialization)
4. You're in the doctor dashboard!
```

---

## 🧪 Test the Platform

### Test Patient Journey (5 minutes)
```
Step 1: Register as Patient
- URL: http://localhost:8080/auth
- Enter: Full Name, Email, Phone, Gender, DOB, Password
- Click: Sign Up

Step 2: Start Onboarding
- URL: http://localhost:8080/get-started
- Click: "Continue to Health Evaluation"

Step 3: Describe Your Health
- URL: http://localhost:8080/medical-query
- Enter: Symptoms (e.g., "Headache, Fever")
- Select: Duration, Severity
- Check: Fever checkbox
- Click: "Find Suitable Doctors"

Step 4: Choose Doctor
- URL: http://localhost:8080/choose-doctor?query_id=1
- View: Matched doctors with ratings
- Click: "Select & Book Appointment"

Step 5: Book Appointment
- URL: http://localhost:8080/book-appointment
- Select: Date (pick tomorrow or later)
- Select: Time slot
- Click: "Confirm Appointment"

Step 6: View Dashboard
- URL: http://localhost:8080/dashboard
- See: Your appointment listed
```

### Test Doctor Journey (3 minutes)
```
Step 1: Register as Doctor
- URL: http://localhost:8080/auth
- Enter: Full Name, Email, Phone, Gender, DOB
- Enter: Specialization (e.g., "Cardiology")
- Enter: Years of Experience (e.g., 10)
- Click: Sign Up

Step 2: Access Doctor Dashboard
- URL: http://localhost:8080/doctor-dashboard
- Set: Availability hours
- View: Any appointments
```

---

## 🔍 What to Look For

### Success Indicators
✅ Pages load without errors
✅ Forms accept input
✅ Data gets saved to database
✅ Validation messages appear
✅ Redirects work correctly
✅ Dashboards display data
✅ Responsive design works on mobile

### Common Issues & Solutions

**Issue**: Pages not found (404)
- **Solution**: Flush WordPress permalinks (Settings → Permalinks → Save)

**Issue**: Database tables not created
- **Solution**: Deactivate and reactivate the plugin

**Issue**: AJAX errors in console
- **Solution**: Check browser console, ensure jQuery is loaded

---

## 📊 Database Verification

Check if tables were created:

```bash
# Login to MySQL
mysql -u root -p wordpress

# List WMedi tables
SHOW TABLES LIKE 'wp_wmedi_%';
```

You should see:
```
wp_wmedi_users
wp_wmedi_doctors
wp_wmedi_medical_queries
wp_wmedi_appointments
wp_wmedi_availability_slots
wp_wmedi_doctor_matches
```

---

## 🔐 Security Verified

The platform includes:
- ✅ CSRF token verification (nonce)
- ✅ Password hashing
- ✅ SQL injection prevention
- ✅ Input sanitization
- ✅ User role verification
- ✅ Email validation
- ✅ HTTPS ready

---

## 🎯 Key Pages & Features

### Public Pages (No Login Required)
| Page | URL | Purpose |
|------|-----|---------|
| Landing | `/welcome` | Role selection |
| Auth | `/auth` | Login/Signup |

### Protected Pages (Login Required)
| Page | URL | Role | Purpose |
|------|-----|------|---------|
| Onboarding | `/get-started` | Patient | Explain journey |
| Medical Query | `/medical-query` | Patient | Describe symptoms |
| Doctor Selection | `/choose-doctor` | Patient | View matched doctors |
| Booking | `/book-appointment` | Patient | Schedule appointment |
| Patient Dashboard | `/dashboard` | Patient | Manage appointments |
| Doctor Dashboard | `/doctor-dashboard` | Doctor | Manage schedule |

---

## 💾 Sample Test Data

### Create Test Doctor
1. Go to `/welcome`
2. Click "Doctor" button
3. Register with:
   - Name: Dr. John Smith
   - Email: doctor@test.com
   - Specialization: Cardiology
   - Experience: 10 years

### Create Test Patient
1. Go to `/welcome`
2. Click "Patient" button
3. Register with:
   - Name: Jane Doe
   - Email: patient@test.com
   - Phone: 1234567890
   - Gender: Female
   - DOB: 01/01/1990

### Test Appointment
1. Login as patient
2. Describe symptoms: "Chest pain, shortness of breath"
3. See Dr. John Smith matched (Cardiology matches symptoms)
4. Book appointment
5. Check Doctor Dashboard to see the appointment

---

## 📧 Email Testing

To test emails locally, you can use:
- **MailHog**: Catches emails without sending
- **Mailtrap.io**: Free email testing service
- **Gmail SMTP**: Configure in WordPress settings

---

## 🛠️ Customization Examples

### Change Primary Color
Edit `/wp-content/plugins/wmedi-plus-healthcare/assets/css/wmedi-style.css`:
```css
:root {
    --primary-color: #ff0000;  /* Change to red */
}
```

### Add New Doctor Specialization
Update doctor matching logic in `class-doctor-matching.php`:
```php
$specialization_keywords = ['Cardiology', 'Neurology', 'Orthopedics', 'NEW_SPECIALTY'];
```

### Customize Email Message
Edit `class-appointments.php` `send_confirmation_emails()`:
```php
$message = "Hello {$patient->display_name}, your appointment is confirmed!";
```

---

## 📱 Mobile Testing

The platform is fully responsive. Test on:
- ✅ iPhone (Safari)
- ✅ Android (Chrome)
- ✅ Tablet
- ✅ Desktop

All pages adapt to screen size automatically.

---

## 🐛 Troubleshooting

### Check Error Logs
Add to `wp-config.php` for debugging:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Logs appear in `/wp-content/debug.log`

### Browser Console
Press F12 to open Developer Tools → Console
Look for JavaScript errors

### Database Errors
Check WordPress admin for error messages
Review MySQL error log if tables didn't create

---

## 📞 Support Resources

**Documentation Files**:
- `WMEDI_BUILD_SUMMARY.md` - Complete overview
- `WMEDI_IMPLEMENTATION_GUIDE.md` - Technical details
- `wp-content/plugins/wmedi-plus-healthcare/README.md` - Plugin docs

**Files to Review**:
1. `/templates/` - Page layouts
2. `/includes/` - Backend logic
3. `/assets/` - Styling & scripts

---

## ✅ Verification Checklist

After activation, verify:
- [ ] All 8 pages created
- [ ] Login page loads
- [ ] Signup form works
- [ ] Styling looks good
- [ ] Database tables exist
- [ ] No console errors
- [ ] Responsive on mobile
- [ ] Forms validate input

---

## 🎉 You're Ready!

The platform is fully functional and ready to:
1. ✅ Register patients and doctors
2. ✅ Match doctors to symptoms
3. ✅ Book appointments
4. ✅ Manage dashboards
5. ✅ Send notifications

**Start by visiting**: http://localhost:8080/welcome

---

## 🚀 Next Steps

1. **Test thoroughly** - Go through patient and doctor flows
2. **Customize** - Update colors, specializations, messages
3. **Configure email** - Set up SMTP for notifications
4. **Deploy** - Push to GitHub and production
5. **Monitor** - Check logs and user feedback
6. **Extend** - Add features like video calls, payments, etc.

---

**Enjoy your enterprise healthcare platform! 🏥💙**

**Questions?** Check the README.md files or review the source code.

**Version**: 1.0.0
**Status**: ✅ Production Ready
