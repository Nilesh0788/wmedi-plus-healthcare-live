<?php
/**
 * Authentication Template - Login & Signup
 */
get_header();
?>

<div class="wmedi-auth-page">
    <div class="auth-container">
        <div class="auth-box">
            <!-- Tab Navigation -->
            <div class="auth-tabs">
                <button class="tab-btn active" onclick="wmediSwitchTab('login')">Login</button>
                <button class="tab-btn" onclick="wmediSwitchTab('signup')">Sign Up</button>
            </div>

            <!-- Login Form -->
            <form id="wmedi-login-form" class="auth-form active" onsubmit="wmediHandleLogin(event)">
                <h2>Welcome Back</h2>
                <p>Login to your WMedi Plus account</p>

                <div class="form-group">
                    <label for="login-email">Email Address</label>
                    <input type="email" id="login-email" name="email" required placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" required placeholder="Enter your password">
                </div>

                <button type="submit" class="btn btn-primary btn-full">Login</button>
                <p class="form-help">Don't have an account? <a href="#" onclick="wmediSwitchTab('signup')">Sign up here</a></p>
            </form>

            <!-- Signup Form -->
            <form id="wmedi-signup-form" class="auth-form" onsubmit="wmediHandleSignup(event)">
                <h2>Create Account</h2>
                <p>Join WMedi Plus and get started with healthcare</p>

                <input type="hidden" id="signup-role" name="user_type">

                <div class="form-group">
                    <label for="signup-name">Full Name</label>
                    <input type="text" id="signup-name" name="full_name" required placeholder="Enter your full name">
                </div>

                <div class="form-group">
                    <label for="signup-email">Email Address</label>
                    <input type="email" id="signup-email" name="email" required placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label for="signup-phone">Mobile Number</label>
                    <input type="tel" id="signup-phone" name="phone" required placeholder="Enter your mobile number">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="signup-gender">Gender</label>
                        <select id="signup-gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="signup-dob">Date of Birth</label>
                        <input type="date" id="signup-dob" name="date_of_birth" required>
                    </div>
                </div>

                <!-- Doctor Specific Fields -->
                <div id="doctor-fields" style="display: none;">
                    <div class="form-group">
                        <label for="signup-specialization">Specialization</label>
                        <input type="text" id="signup-specialization" name="specialization" placeholder="e.g., Cardiology, Neurology">
                    </div>

                    <div class="form-group">
                        <label for="signup-experience">Years of Experience</label>
                        <input type="number" id="signup-experience" name="years_of_experience" min="0" placeholder="e.g., 10">
                    </div>
                </div>

                <div class="form-group">
                    <label for="signup-password">Password</label>
                    <input type="password" id="signup-password" name="password" required placeholder="Create a strong password">
                </div>

                <div class="form-group">
                    <label for="signup-confirm">Confirm Password</label>
                    <input type="password" id="signup-confirm" name="confirm_password" required placeholder="Confirm your password">
                </div>

                <button type="submit" class="btn btn-primary btn-full">Sign Up</button>
            </form>

            <div class="security-badge">
                🔒 Your information is secure and encrypted
            </div>
        </div>

        <!-- Side Info -->
        <div class="auth-info">
            <h3>Why WMedi Plus?</h3>
            <ul>
                <li>✓ 256-bit SSL Encryption</li>
                <li>✓ HIPAA Compliant</li>
                <li>✓ Verified Healthcare Professionals</li>
                <li>✓ 24/7 Customer Support</li>
                <li>✓ Instant Appointment Booking</li>
            </ul>
        </div>
    </div>
</div>

<script>
// Get user role from localStorage
document.addEventListener('DOMContentLoaded', function() {
    const role = localStorage.getItem('wmedi_user_role') || 'patient';
    document.getElementById('signup-role').value = role;
    
    if (role === 'doctor') {
        document.getElementById('doctor-fields').style.display = 'block';
    }
});

function wmediSwitchTab(tab) {
    // Hide all forms
    document.querySelectorAll('.auth-form').forEach(form => form.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    
    // Show selected form
    if (tab === 'login') {
        document.getElementById('wmedi-login-form').classList.add('active');
    } else {
        document.getElementById('wmedi-signup-form').classList.add('active');
    }
    
    // Mark active tab
    event.target.classList.add('active');
}

function wmediHandleLogin(e) {
    e.preventDefault();
    
    const formData = new FormData(document.getElementById('wmedi-login-form'));
    formData.append('action', 'wmedi_login');
    formData.append('nonce', wmediNonce);
    
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.data.message);
            window.location.href = data.data.redirect;
        } else {
            alert('Error: ' + data.data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function wmediHandleSignup(e) {
    e.preventDefault();
    
    const password = document.getElementById('signup-password').value;
    const confirm = document.getElementById('signup-confirm').value;
    
    if (password !== confirm) {
        alert('Passwords do not match');
        return;
    }
    
    const formData = new FormData(document.getElementById('wmedi-signup-form'));
    formData.append('action', 'wmedi_signup');
    formData.append('nonce', wmediNonce);
    
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.data.message);
            window.location.href = data.data.redirect;
        } else {
            alert('Error: ' + data.data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<?php get_footer(); ?>
