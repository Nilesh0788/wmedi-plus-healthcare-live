<?php
/**
 * Appointment Booking Template
 */
get_header();

if (!is_user_logged_in()) {
    wp_redirect(home_url('/auth'));
    exit;
}
?>

<div class="wmedi-appointment-booking-page">
    <div class="booking-container">
        <h1>Book Your Appointment</h1>
        <p class="subtitle">Select a date and time that works best for you</p>

        <div class="booking-content">
            <form id="wmedi-appointment-form" onsubmit="wmediBookAppointment(event)">
                <div class="form-section">
                    <h3>📅 Select Date</h3>
                    <div class="form-group">
                        <label for="appointment-date">Appointment Date</label>
                        <input type="date" id="appointment-date" name="appointment_date" required 
                               min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                    </div>
                </div>

                <div class="form-section">
                    <h3>⏰ Select Time</h3>
                    <div class="form-group">
                        <label for="appointment-time">Available Time Slots</label>
                        <select id="appointment-time" name="appointment_time" required disabled>
                            <option value="">Select a date first</option>
                        </select>
                    </div>
                </div>

                <div class="form-section">
                    <h3>📝 Additional Notes</h3>
                    <div class="form-group">
                        <label for="appointment-notes">Any specific requests or concerns? (Optional)</label>
                        <textarea id="appointment-notes" name="notes" rows="4" 
                                  placeholder="e.g., Prefer video consultation, have limited availability..."></textarea>
                    </div>
                </div>

                <div class="booking-summary">
                    <h3>Booking Summary</h3>
                    <div id="summary-content">
                        <p>Select date and time to see summary</p>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large">Confirm Appointment</button>
                    <a href="<?php echo home_url('/choose-doctor'); ?>" class="btn btn-secondary">Back</a>
                </div>
            </form>

            <div class="booking-info">
                <h3>Important Information</h3>
                <ul>
                    <li>✓ Appointments can be scheduled up to 30 days in advance</li>
                    <li>✓ You can cancel or reschedule up to 2 hours before</li>
                    <li>✓ Confirmation email will be sent immediately</li>
                    <li>✓ Consultation link will be provided before appointment</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
const doctorId = localStorage.getItem('wmedi_selected_doctor');
const queryId = localStorage.getItem('wmedi_query_id');

document.getElementById('appointment-date').addEventListener('change', function() {
    if (!this.value || !doctorId) return;
    
    const formData = new FormData();
    formData.append('action', 'wmedi_get_doctor_slots');
    formData.append('doctor_id', doctorId);
    formData.append('date', this.value);
    formData.append('nonce', wmediNonce);
    
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const timeSelect = document.getElementById('appointment-time');
        if (data.success && data.data.slots.length > 0) {
            timeSelect.innerHTML = '<option value="">Select time</option>';
            data.data.slots.forEach(slot => {
                const option = document.createElement('option');
                option.value = slot;
                option.textContent = slot;
                timeSelect.appendChild(option);
            });
            timeSelect.disabled = false;
        } else {
            timeSelect.innerHTML = '<option value="">No slots available</option>';
            timeSelect.disabled = true;
        }
    })
    .catch(error => console.error('Error:', error));
});

function wmediBookAppointment(e) {
    e.preventDefault();
    
    if (!doctorId || !queryId) {
        alert('Invalid selection. Please start over.');
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'wmedi_book_appointment');
    formData.append('doctor_id', doctorId);
    formData.append('query_id', queryId);
    formData.append('appointment_date', document.getElementById('appointment-date').value);
    formData.append('appointment_time', document.getElementById('appointment-time').value);
    formData.append('notes', document.getElementById('appointment-notes').value);
    formData.append('nonce', wmediNonce);
    
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.data.message);
            localStorage.removeItem('wmedi_selected_doctor');
            localStorage.removeItem('wmedi_query_id');
            window.location.href = data.data.redirect;
        } else {
            alert('Error: ' + data.data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<?php get_footer(); ?>
