<?php
/**
 * Landing Page Template - Welcome to WMedi Plus
 */
?>

<div class="wmedi-landing-page">
    <div class="landing-container">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1>Welcome to WMedi Plus</h1>
                <p>Connect with Healthcare Experts</p>
                <p class="subtitle">Select your role to get started with personalized healthcare solutions</p>
            </div>
        </section>

        <!-- Role Selection Cards -->
        <section class="role-selection">
            <div class="role-cards">
                <!-- Patient Card -->
                <div class="role-card patient-card">
                    <div class="card-icon">👤</div>
                    <h2>I'm a Patient</h2>
                    <p>Looking for healthcare guidance and expert medical consultation</p>
                    <ul class="features">
                        <li>✓ Connect with verified doctors</li>
                        <li>✓ Describe your symptoms</li>
                        <li>✓ Book instant appointments</li>
                        <li>✓ Get professional medical advice</li>
                    </ul>
                    <button class="btn btn-primary btn-large" onclick="wmediSelectRole('patient')">
                        Login / Signup as Patient
                    </button>
                </div>

                <!-- Doctor Card -->
                <div class="role-card doctor-card">
                    <div class="card-icon">👨‍⚕️</div>
                    <h2>I'm a Doctor</h2>
                    <p>Join our network and help patients with expert medical consultation</p>
                    <ul class="features">
                        <li>✓ Expand your patient base</li>
                        <li>✓ Manage your availability</li>
                        <li>✓ View patient queries</li>
                        <li>✓ Provide consultations online</li>
                    </ul>
                    <button class="btn btn-secondary btn-large" onclick="wmediSelectRole('doctor')">
                        Login / Signup as Doctor
                    </button>
                </div>
            </div>
        </section>

        <!-- Info Section -->
        <section class="info-section">
            <h2>Why Choose WMedi Plus?</h2>
            <div class="info-cards">
                <div class="info-item">
                    <h3>🔒 Secure & Confidential</h3>
                    <p>Your medical information is encrypted and protected with industry-standard security</p>
                </div>
                <div class="info-item">
                    <h3>⚡ Quick & Convenient</h3>
                    <p>Get matched with the right doctor and book appointments instantly</p>
                </div>
                <div class="info-item">
                    <h3>✅ Verified Professionals</h3>
                    <p>All doctors are verified and authenticated healthcare professionals</p>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Hidden form to pass role selection -->
<form id="wmedi-role-form" method="post" style="display: none;">
    <input type="hidden" name="action" value="wmedi_select_role">
    <input type="hidden" name="role" id="role-input">
</form>

<script>
function wmediSelectRole(role) {
    // Store role in session and redirect to auth page
    localStorage.setItem('wmedi_user_role', role);
    window.location.href = '<?php echo home_url('/auth'); ?>';
}
</script>
