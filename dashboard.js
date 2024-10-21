// Function to handle job status updates
document.querySelectorAll('.update-btn').forEach(button => {
    button.addEventListener('click', function() {
        const jobCard = this.closest('.job-card');
        const statusElement = jobCard.querySelector('p');
        
        // Simulate updating the job status
        if (this.innerText === 'Mark as Complete') {
            statusElement.innerText = 'Status: Completed';
            this.innerText = 'Reopen Job';
        } else if (this.innerText === 'Reopen Job') {
            statusElement.innerText = 'Status: In Progress';
            this.innerText = 'Mark as Complete';
        }

        // Show a success message
        showSuccessMessage('Job status updated successfully!');
    });
});

// Function to display a success message
function showSuccessMessage(message) {
    const successMsg = document.createElement('div');
    successMsg.className = 'success-msg';
    successMsg.innerText = message;
    
    document.body.appendChild(successMsg);
    
    // Remove the message after 3 seconds
    setTimeout(() => {
        successMsg.remove();
    }, 3000);
}

// Function to simulate real-time factory performance update
function autoRefreshFactoryStats() {
    // Simulate updating factory stats every 10 seconds
    const avgUtilizationBar = document.querySelector('.factory-stats .progress');
    const avgUtilizationPercentage = Math.floor(Math.random() * 100);
    const totalDowntimeElement = document.querySelector('.factory-stats .stat:nth-child(2) p');
    const totalDowntimeHours = Math.floor(Math.random() * 10) + 1; // Simulate random downtime

    // Update the average utilization progress bar and text
    avgUtilizationBar.style.width = `${avgUtilizationPercentage}%`;
    avgUtilizationBar.innerText = `${avgUtilizationPercentage}%`;
    
    // Update the total downtime text
    totalDowntimeElement.innerText = `Total Downtime: ${totalDowntimeHours} hours`;

    // Simulate alert if utilization falls below a certain percentage
    if (avgUtilizationPercentage < 30) {
        showFactoryAlert('Warning: Factory utilization is below 30%!');
    }
}

// Function to display factory alerts
function showFactoryAlert(message) {
    const alertMsg = document.createElement('div');
    alertMsg.className = 'alert alert-warning';
    alertMsg.innerText = message;

    document.body.appendChild(alertMsg);
    
    // Remove the alert message after 5 seconds
    setTimeout(() => {
        alertMsg.remove();
    }, 5000);
}

// Automatically refresh factory stats every 10 seconds
setInterval(autoRefreshFactoryStats, 10000);

