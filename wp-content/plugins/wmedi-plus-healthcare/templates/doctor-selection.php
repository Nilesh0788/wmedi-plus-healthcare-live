<?php
/**
 * Doctor Selection Template - Choose Your Doctor
 */
get_header();

if (!is_user_logged_in()) {
    wp_redirect(home_url('/auth'));
    exit;
}

$query_id = isset($_GET['query_id']) ? intval($_GET['query_id']) : 0;
?>

<div class="wmedi-doctor-selection-page">
    <div class="doctor-selection-container">
        <h1>Choose Your Doctor</h1>
        <p class="subtitle">Based on your medical query, we've found these suitable doctors for you</p>

        <div id="doctors-list" class="doctors-grid">
            <div class="loading">Loading doctors...</div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const queryId = <?php echo $query_id; ?>;
    
    if (!queryId) {
        document.getElementById('doctors-list').innerHTML = '<p>No query found. Please start over.</p>';
        return;
    }
    
    // Fetch matched doctors
    const formData = new FormData();
    formData.append('action', 'wmedi_get_matched_doctors');
    formData.append('query_id', queryId);
    formData.append('nonce', wmediNonce);
    
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayDoctors(data.data.doctors, queryId);
        } else {
            document.getElementById('doctors-list').innerHTML = '<p>No doctors available. Please try again later.</p>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('doctors-list').innerHTML = '<p>Error loading doctors.</p>';
    });
});

function displayDoctors(doctors, queryId) {
    const container = document.getElementById('doctors-list');
    
    if (doctors.length === 0) {
        container.innerHTML = '<p>No suitable doctors found. Please update your query.</p>';
        return;
    }
    
    let html = '';
    doctors.forEach(doctor => {
        html += `
            <div class="doctor-card">
                <div class="doctor-header">
                    <div class="doctor-avatar">👨‍⚕️</div>
                    <div class="doctor-basics">
                        <h3>Dr. ${doctor.name}</h3>
                        <p class="specialization">${doctor.specialization}</p>
                    </div>
                </div>
                
                <div class="doctor-info">
                    <p><strong>Experience:</strong> ${doctor.experience} years</p>
                    <p><strong>Rating:</strong> ⭐ ${doctor.rating || '0'}/5</p>
                    <p><strong>Match Score:</strong> ${Math.round(doctor.score)}%</p>
                </div>
                
                <button class="btn btn-primary btn-full" onclick="wmediSelectDoctor(${doctor.user_id}, ${queryId})">
                    Select & Book Appointment
                </button>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function wmediSelectDoctor(doctorId, queryId) {
    // Store selected doctor and redirect to booking
    localStorage.setItem('wmedi_selected_doctor', doctorId);
    localStorage.setItem('wmedi_query_id', queryId);
    window.location.href = '<?php echo home_url('/book-appointment'); ?>';
}
</script>

<?php get_footer(); ?>
