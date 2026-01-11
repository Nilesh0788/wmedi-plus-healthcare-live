<?php
/**
 * Onboarding Template - Get Started with WMedi Plus
 */
get_header();
?>

<div class="wmedi-onboarding-page">
    <div class="onboarding-container">
        <div class="onboarding-box">
            <h1>Get Started with WMedi Plus</h1>
            <p class="subtitle">Your journey to better health begins here</p>

            <div class="onboarding-steps">
                <div class="step completed">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Account Created</h3>
                        <p>Your secure WMedi Plus account is ready</p>
                    </div>
                </div>

                <div class="step-connector"></div>

                <div class="step active">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>Describe Your Health</h3>
                        <p>Tell us about your symptoms or health concerns</p>
                    </div>
                </div>

                <div class="step-connector"></div>

                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Match with Doctor</h3>
                        <p>Get matched with the right healthcare professional</p>
                    </div>
                </div>

                <div class="step-connector"></div>

                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h3>Book Appointment</h3>
                        <p>Schedule consultation at your preferred time</p>
                    </div>
                </div>
            </div>

            <div class="onboarding-info">
                <h2>Here's What Happens Next</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="icon">📝</div>
                        <h4>Medical Evaluation</h4>
                        <p>You'll answer a few medical questions to help us understand your condition better</p>
                    </div>
                    <div class="info-item">
                        <div class="icon">👨‍⚕️</div>
                        <h4>Doctor Matching</h4>
                        <p>Our AI system matches you with the most suitable doctor based on your needs</p>
                    </div>
                    <div class="info-item">
                        <div class="icon">📅</div>
                        <h4>Quick Booking</h4>
                        <p>Book an appointment at your preferred time with just one click</p>
                    </div>
                    <div class="info-item">
                        <div class="icon">💬</div>
                        <h4>Consultation</h4>
                        <p>Get professional medical advice from verified healthcare experts</p>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary btn-large" onclick="wmediContinueOnboarding()">
                Continue to Health Evaluation
            </button>

            <p class="help-text">
                This typically takes 5 minutes. Your medical information is completely confidential and secure.
            </p>
        </div>
    </div>
</div>

<script>
function wmediContinueOnboarding() {
    window.location.href = '<?php echo home_url('/medical-query'); ?>';
}
</script>

<?php get_footer(); ?>
