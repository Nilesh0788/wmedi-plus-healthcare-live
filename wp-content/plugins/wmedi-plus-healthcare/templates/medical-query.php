<?php
/**
 * Medical Query Template - Tell Us About Your Health
 */
get_header();
?>

<div class="wmedi-medical-query-page">
    <div class="query-container">
        <div class="query-box">
            <h1>Tell Us About Your Health</h1>
            <p class="subtitle">Help us understand your symptoms to find the right doctor for you</p>

            <form id="wmedi-medical-query-form" onsubmit="wmediSaveMedicalQuery(event)">
                <div class="form-section">
                    <h3>🏥 Primary Symptoms</h3>
                    <div class="form-group">
                        <label for="symptoms">What are your main symptoms?</label>
                        <div class="symptom-input">
                            <input type="text" id="symptoms" name="primary_symptoms" required 
                                   placeholder="e.g., Headache, Fever, Chest pain">
                            <small>Enter symptoms separated by commas</small>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>⏱️ Duration & Severity</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="duration">How long have you had these symptoms?</label>
                            <select id="duration" name="duration" required>
                                <option value="">Select duration</option>
                                <option value="less_than_24h">Less than 24 hours</option>
                                <option value="1_to_7_days">1-7 days</option>
                                <option value="1_to_4_weeks">1-4 weeks</option>
                                <option value="1_to_3_months">1-3 months</option>
                                <option value="more_than_3_months">More than 3 months</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="severity">How severe are your symptoms?</label>
                            <select id="severity" name="severity" required>
                                <option value="">Select severity</option>
                                <option value="mild">Mild (Bothersome but manageable)</option>
                                <option value="moderate">Moderate (Affects daily activities)</option>
                                <option value="severe">Severe (Very disruptive)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>🩺 Medical History</h3>
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="fever" value="1">
                            <span>I have fever</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="pain" value="1">
                            <span>I experience pain</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="chronic" value="1">
                            <span>I have a chronic condition</span>
                        </label>
                    </div>
                </div>

                <div class="form-section">
                    <h3>📝 Detailed Description (Optional)</h3>
                    <div class="form-group">
                        <label for="description">Tell us more about your condition</label>
                        <textarea id="description" name="description" rows="6" 
                                  placeholder="Provide any additional details that might help the doctor understand your condition better..."></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large">Find Suitable Doctors</button>
                    <a href="<?php echo home_url('/get-started'); ?>" class="btn btn-secondary">Back</a>
                </div>
            </form>
        </div>

        <div class="query-help">
            <h3>Why This Information?</h3>
            <ul>
                <li><strong>Symptoms:</strong> Help identify the right specialist</li>
                <li><strong>Duration:</strong> Indicates urgency and condition type</li>
                <li><strong>Severity:</strong> Helps prioritize appointments</li>
                <li><strong>Medical History:</strong> Ensures doctor compatibility</li>
            </ul>
            
            <div class="privacy-notice">
                🔒 <strong>Your Privacy is Protected</strong><br>
                All medical information is encrypted and only shared with matched doctors.
            </div>
        </div>
    </div>
</div>

<script>
function wmediSaveMedicalQuery(e) {
    e.preventDefault();
    
    const formData = new FormData(document.getElementById('wmedi-medical-query-form'));
    formData.append('action', 'wmedi_save_medical_query');
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
