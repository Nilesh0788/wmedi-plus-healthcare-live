# WMedi Plus Healthcare Platform

A comprehensive healthcare platform that connects patients with qualified doctors for instant consultations, medical advice, and appointment booking.

## 📋 Features

### For Patients
- **Easy Registration**: Simple sign-up process with email verification
- **Health Evaluation**: Describe symptoms and medical concerns
- **Smart Doctor Matching**: AI-powered algorithm matches patients with suitable doctors
- **Instant Booking**: Book appointments with preferred timing
- **Appointment Dashboard**: View, reschedule, or cancel appointments
- **Secure Medical Records**: Encrypted storage of medical history and queries

### For Doctors
- **Professional Profile**: Showcase qualifications and specialization
- **Availability Management**: Set working hours and availability
- **Patient Appointments**: View and manage upcoming consultations
- **Patient Queries**: Review patient medical information before appointments
- **Prescription Management**: Store and manage patient records

### Platform Features
- **HIPAA Compliant**: Secure medical data handling
- **256-bit SSL Encryption**: All communications encrypted
- **Real-time Notifications**: Email alerts for appointments
- **Responsive Design**: Works on all devices
- **Scalable Architecture**: Built for growth

## 🔧 Technical Stack

- **WordPress Core**: Plugin-based architecture
- **Database**: Custom MySQL tables for users, doctors, appointments, and medical queries
- **Frontend**: Vanilla JavaScript with responsive CSS
- **Security**: Password hashing, AJAX nonce verification, SQL prepared statements
- **API**: WordPress AJAX for secure communication

## 📁 Plugin Structure

```
wmedi-plus-healthcare/
├── wmedi-plus-healthcare.php       # Main plugin file
├── includes/
│   ├── class-database.php          # Database initialization
│   ├── class-authentication.php    # Login/signup handling
│   ├── class-pages.php             # Page management
│   ├── class-doctor-matching.php   # Doctor matching algorithm
│   ├── class-appointments.php      # Appointment management
│   ├── class-ajax-handlers.php     # AJAX handlers
│   └── class-enqueue.php           # Scripts & styles
├── templates/
│   ├── landing-page.php            # Page 1: Landing
│   ├── authentication.php          # Page 2: Auth
│   ├── onboarding.php              # Page 3: Onboarding
│   ├── medical-query.php           # Page 4: Medical form
│   ├── doctor-selection.php        # Page 5: Doctor selection
│   ├── appointment-booking.php     # Page 6: Booking
│   ├── patient-dashboard.php       # Page 7a: Patient dashboard
│   └── doctor-dashboard.php        # Page 7b: Doctor dashboard
├── assets/
│   ├── css/
│   │   └── wmedi-style.css         # Styles
│   └── js/
│       └── wmedi-script.js         # Frontend JS
└── README.md                        # This file
```

## 🚀 Installation

### Requirements
- WordPress 5.0 or higher
- PHP 7.4 or higher
- MySQL 5.7 or higher

### Steps

1. **Download/Clone**
```bash
cd wp-content/plugins/
git clone https://github.com/yourusername/wmedi-plus-healthcare.git
```

2. **Activate Plugin**
   - Go to WordPress Admin Dashboard
   - Navigate to Plugins
   - Find "WMedi Plus Healthcare Platform"
   - Click "Activate"

3. **Database Setup**
   - Plugin automatically creates required tables on activation
   - All tables are prefixed with `wp_wmedi_`

4. **Access Portal**
   - Landing Page: `yourdomain.com/welcome`
   - Auth Page: `yourdomain.com/auth`
   - Get Started: `yourdomain.com/get-started`

## 📊 Database Schema

### wmedi_users
- Extended user information
- Stores patient/doctor type
- Phone, gender, date of birth

### wmedi_doctors
- Doctor-specific information
- Specialization, experience, rating
- Availability settings

### wmedi_medical_queries
- Patient symptom descriptions
- Duration, severity, medical history
- Links to appointments

### wmedi_appointments
- Appointment records
- Patient-doctor linkage
- Status, notes, prescriptions

### wmedi_availability_slots
- Doctor availability timings
- Day-wise working hours

### wmedi_doctor_matches
- Matching results
- Match scores and reasons

## 🔐 Security Features

- **Password Hashing**: WordPress wp_hash_password()
- **AJAX Nonce**: CSRF protection on all AJAX calls
- **Prepared Statements**: SQL injection prevention with $wpdb->prepare()
- **Input Validation**: Sanitization with sanitize_* functions
- **Output Escaping**: Proper escaping with wp_kses_post()
- **Role-Based Access**: User type verification
- **HTTPS**: Recommend SSL certificate

## 🎯 API Endpoints

### Authentication
- `POST /wp-admin/admin-ajax.php?action=wmedi_signup` - Register new user
- `POST /wp-admin/admin-ajax.php?action=wmedi_login` - User login
- `POST /wp-admin/admin-ajax.php?action=wmedi_logout` - User logout

### Medical Queries
- `POST /wp-admin/admin-ajax.php?action=wmedi_save_medical_query` - Save health query

### Doctor Matching
- `POST /wp-admin/admin-ajax.php?action=wmedi_get_matched_doctors` - Get matched doctors

### Appointments
- `POST /wp-admin/admin-ajax.php?action=wmedi_book_appointment` - Book appointment
- `POST /wp-admin/admin-ajax.php?action=wmedi_get_doctor_slots` - Get available slots
- `POST /wp-admin/admin-ajax.php?action=wmedi_get_appointments` - Get user appointments

## 🔄 User Flow

### Patient Journey
1. **Welcome Page** - Select "Patient" role
2. **Authentication** - Sign up with personal details
3. **Onboarding** - Understand the process
4. **Medical Query** - Describe symptoms
5. **Doctor Selection** - Choose from matched doctors
6. **Appointment Booking** - Select date/time
7. **Dashboard** - Manage appointments and health records

### Doctor Journey
1. **Welcome Page** - Select "Doctor" role
2. **Authentication** - Sign up with credentials
3. **Doctor Dashboard** - Manage profile and availability
4. **View Appointments** - See upcoming consultations
5. **Patient Management** - Review queries and prescriptions

## 🧠 Matching Algorithm

The doctor matching system considers:
- **Symptom Match**: Keywords in specialization vs symptoms
- **Experience**: Years of practice (weighted)
- **Rating**: Doctor ratings and reviews
- **Availability**: Real-time slot availability

Scoring Formula: `Score = (Symptom Match * 25) + (Experience * 2) + (Rating * 5)`

## 📝 Configuration

### Setting Available Slots
Doctors can set availability from their dashboard:
- Select days of week
- Set start/end times
- Manage recurring schedules

### Appointment Duration
Default: 30-minute slots (configurable in class-appointments.php)

### Consultation Fees
Can be set per doctor in the `wmedi_doctors` table

## 🧪 Testing

### Test Patient Flow
1. Create patient account
2. Submit medical query (e.g., "Headache, fever")
3. View matched doctors
4. Book appointment

### Test Doctor Flow
1. Create doctor account (Specialization: "Neurology")
2. Set availability in dashboard
3. View appointments
4. Add notes/prescriptions

## 🚀 Deployment

### Pre-Deployment Checklist
- [ ] Test all user flows
- [ ] Verify SSL certificate
- [ ] Configure email for notifications
- [ ] Backup database
- [ ] Update privacy policy
- [ ] Enable logging

### Environment Variables (Recommended)
```php
define('WMEDI_SMTP_HOST', 'your-smtp-host');
define('WMEDI_SMTP_USER', 'your-email');
define('WMEDI_SMTP_PASS', 'your-password');
```

## 📧 Email Notifications

- Signup confirmation
- Appointment scheduled
- Appointment reminder (24 hours before)
- Appointment completed
- Doctor response

Configure SMTP in WordPress settings.

## 🐛 Troubleshooting

### Database Tables Not Created
- Deactivate and reactivate plugin
- Check database permissions
- Review error logs

### Pages Not Showing
- Verify plugin is activated
- Check WordPress permalink settings
- Flush rewrite rules

### AJAX Not Working
- Verify nonce in frontend
- Check browser console for errors
- Ensure jQuery is loaded

## 📈 Future Enhancements

- [ ] Video consultation integration
- [ ] Prescription management
- [ ] Medical records export (PDF)
- [ ] Multi-language support
- [ ] Mobile app integration
- [ ] Payment gateway integration
- [ ] Advanced analytics dashboard
- [ ] Insurance verification
- [ ] Referral system
- [ ] Telemedicine features

## 📄 License

GPL v2 or later

## 👨‍💻 Author

WMedi Plus Team

## 🤝 Contributing

Contributions are welcome! Please follow these guidelines:
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## 📞 Support

For support, email: support@wmediplus.com

## 🙏 Acknowledgments

Built for rtCamp, showcasing WordPress expertise and real-world problem solving.
