<?php
/**
 * Doctor Dashboard Template
 */
get_header();

if (!is_user_logged_in() || WMedi_Authentication::get_user_type() !== 'doctor') {
    wp_redirect(home_url('/auth'));
    exit;
}

$user_id = get_current_user_id();
?>

<div class="wmedi-doctor-dashboard">
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <div class="user-profile">
                <div class="avatar">👨‍⚕️</div>
                <h3>Dr. <?php echo wp_get_current_user()->display_name; ?></h3>
                <p><?php echo wp_get_current_user()->user_email; ?></p>
            </div>

            <nav class="dashboard-nav">
                <a href="#appointments" class="nav-item active" data-section="appointments">
                    📅 Upcoming Appointments
                </a>
                <a href="#queries" class="nav-item" data-section="queries">
                    📝 Patient Queries
                </a>
                <a href="#availability" class="nav-item" data-section="availability">
                    🕐 Availability
                </a>
                <a href="#profile" class="nav-item" data-section="profile">
                    👤 My Profile
                </a>
                <a href="<?php echo wp_logout_url(home_url('/')); ?>" class="nav-item logout">
                    🚪 Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-content">
            <!-- Appointments Section -->
            <section id="appointments-section" class="dashboard-section active">
                <h2>Upcoming Appointments</h2>
                <div id="appointments-list" class="appointments-list">
                    <div class="loading">Loading appointments...</div>
                </div>
            </section>

            <!-- Queries Section -->
            <section id="queries-section" class="dashboard-section" style="display: none;">
                <h2>Patient Queries</h2>
                <div id="queries-list" class="queries-list">
                    <div class="loading">Loading queries...</div>
                </div>
            </section>

            <!-- Availability Section -->
            <section id="availability-section" class="dashboard-section" style="display: none;">
                <h2>Manage Availability</h2>
                <div class="availability-form">
                    <p>Set your availability timings to accept new appointments</p>
                    <div class="form-group">
                        <label>Monday</label>
                        <div class="time-input">
                            <input type="time" placeholder="From"> 
                            <input type="time" placeholder="To">
                            <label><input type="checkbox"> Available</label>
                        </div>
                    </div>
                    <button class="btn btn-primary">Save Availability</button>
                </div>
            </section>

            <!-- Profile Section -->
            <section id="profile-section" class="dashboard-section" style="display: none;">
                <h2>My Profile</h2>
                <div class="profile-form">
                    <div class="form-group">
                        <label>Full Name</label>
                        <p><?php echo wp_get_current_user()->display_name; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <p><?php echo wp_get_current_user()->user_email; ?></p>
                    </div>
                    <button class="btn btn-secondary">Edit Profile</button>
                </div>
            </section>
        </main>
    </div>
</div>

<script>
// Dashboard navigation
document.querySelectorAll('.dashboard-nav .nav-item').forEach(link => {
    link.addEventListener('click', function(e) {
        if (this.href.includes('wp-logout')) return;
        
        e.preventDefault();
        const section = this.getAttribute('data-section');
        
        // Update nav
        document.querySelectorAll('.dashboard-nav .nav-item').forEach(item => {
            item.classList.remove('active');
        });
        this.classList.add('active');
        
        // Update content
        document.querySelectorAll('.dashboard-section').forEach(sec => {
            sec.style.display = 'none';
        });
        document.getElementById(section + '-section').style.display = 'block';
        
        // Load content
        if (section === 'appointments') loadAppointments();
        if (section === 'queries') loadQueries();
    });
});

function loadAppointments() {
    const formData = new FormData();
    formData.append('action', 'wmedi_get_appointments');
    formData.append('nonce', wmediNonce);
    
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('appointments-list');
        if (data.success && data.data.appointments.length > 0) {
            let html = '';
            data.data.appointments.forEach(apt => {
                html += `
                    <div class="appointment-card">
                        <h4>${apt.patient_name}</h4>
                        <p><strong>Phone:</strong> ${apt.patient_phone}</p>
                        <p><strong>Date & Time:</strong> ${apt.appointment_date} at ${apt.appointment_time}</p>
                        <p><strong>Status:</strong> <span class="badge">${apt.status}</span></p>
                        <button class="btn btn-primary btn-small">Start Consultation</button>
                        <button class="btn btn-secondary btn-small">Add Notes</button>
                    </div>
                `;
            });
            container.innerHTML = html;
        } else {
            container.innerHTML = '<p>No upcoming appointments.</p>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('appointments-list').innerHTML = '<p>Error loading appointments.</p>';
    });
}

function loadQueries() {
    document.getElementById('queries-list').innerHTML = '<p>No new queries yet.</p>';
}

// Load appointments on page load
window.addEventListener('load', loadAppointments);
</script>

<?php get_footer(); ?>
