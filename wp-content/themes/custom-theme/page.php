<?php
/**
 * WMedi Plus Healthcare Landing Page
 */
get_header();
?>

<style>
.wmedi-landing-page {
    background: #f5f5f5;
    min-height: 100vh;
}

.hero-section {
    background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
    color: white;
    text-align: center;
    padding: 80px 20px;
}

.hero-section h1 {
    font-size: 48px;
    margin-bottom: 15px;
    font-weight: bold;
}

.hero-section p {
    font-size: 20px;
    margin: 10px 0;
}

.subtitle {
    font-size: 16px;
    opacity: 0.9;
}

.role-selection {
    padding: 80px 20px;
    background: white;
}

.role-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 40px;
    max-width: 1100px;
    margin: 0 auto;
}

.role-card {
    background: white;
    border: 3px solid #ddd;
    border-radius: 12px;
    padding: 50px 30px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.role-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.card-icon {
    font-size: 80px;
    margin-bottom: 20px;
    display: block;
}

.role-card h2 {
    font-size: 28px;
    color: #1a1a1a;
    margin-bottom: 15px;
}

.role-card p {
    color: #666;
    font-size: 16px;
    margin-bottom: 25px;
}

.features {
    text-align: left;
    list-style: none;
    padding: 20px;
    margin: 25px 0;
    background: #f9f9f9;
    border-radius: 8px;
}

.features li {
    padding: 10px 0;
    color: #333;
    font-size: 16px;
}

.btn {
    padding: 15px 30px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.3s ease;
    width: 100%;
    margin-top: 15px;
}

.btn-primary {
    background: #1a73e8;
    color: white;
}

.btn-primary:hover {
    background: #0d47a1;
    transform: scale(1.02);
}

.btn-secondary {
    background: #f57c00;
    color: white;
}

.btn-secondary:hover {
    background: #e65100;
    transform: scale(1.02);
}

.info-section {
    padding: 80px 20px;
    background: #f5f5f5;
}

.info-section h2 {
    text-align: center;
    font-size: 36px;
    color: #1a1a1a;
    margin-bottom: 50px;
}

.info-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    max-width: 1100px;
    margin: 0 auto;
}

.info-item {
    background: white;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.info-item h3 {
    font-size: 22px;
    color: #1a73e8;
    margin-bottom: 15px;
}

.info-item p {
    color: #666;
    font-size: 15px;
    line-height: 1.6;
}

.landing-container {
    max-width: 1200px;
    margin: 0 auto;
}

@media (max-width: 768px) {
    .hero-section h1 {
        font-size: 32px;
    }
    .role-cards {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="wmedi-landing-page">
    <div class="landing-container">
        <section class="hero-section">
            <h1>Welcome to WMedi Plus</h1>
            <p>Connect with Healthcare Experts</p>
            <p class="subtitle">Select your role to get started</p>
        </section>

        <section class="role-selection">
            <div class="role-cards">
                <div class="role-card">
                    <span class="card-icon">👤</span>
                    <h2>I'm a Patient</h2>
                    <p>Looking for healthcare guidance</p>
                    <ul class="features">
                        <li>✓ Connect with verified doctors</li>
                        <li>✓ Describe your symptoms</li>
                        <li>✓ Book instant appointments</li>
                        <li>✓ Get professional medical advice</li>
                    </ul>
                    <button class="btn btn-primary" onclick="wmediSelectRole('patient')">
                        Login / Signup as Patient
                    </button>
                </div>

                <div class="role-card">
                    <span class="card-icon">👨‍⚕️</span>
                    <h2>I'm a Doctor</h2>
                    <p>Join our network</p>
                    <ul class="features">
                        <li>✓ Expand your patient base</li>
                        <li>✓ Manage your availability</li>
                        <li>✓ View patient queries</li>
                        <li>✓ Provide consultations</li>
                    </ul>
                    <button class="btn btn-secondary" onclick="wmediSelectRole('doctor')">
                        Login / Signup as Doctor
                    </button>
                </div>
            </div>
        </section>

        <section class="info-section">
            <h2>Why Choose WMedi Plus?</h2>
            <div class="info-cards">
                <div class="info-item">
                    <h3>🔒 Secure</h3>
                    <p>Your medical information is encrypted and protected</p>
                </div>
                <div class="info-item">
                    <h3>⚡ Quick</h3>
                    <p>Get matched with doctors instantly</p>
                </div>
                <div class="info-item">
                    <h3>✅ Verified</h3>
                    <p>All doctors are authenticated professionals</p>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
function wmediSelectRole(role) {
    localStorage.setItem('wmedi_user_role', role);
    window.location.href = '<?php echo home_url('/auth'); ?>';
}
</script>

<?php get_footer(); ?>
