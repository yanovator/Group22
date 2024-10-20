document.addEventListener("DOMContentLoaded", function() {
    // Function to refresh user activity table
    function fetchUserActivity() {
        fetch('fetch_user_activity.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector("#user-activity-table tbody");
                tableBody.innerHTML = ''; // Clear existing rows
                data.forEach(activity => {
                    const row = document.createElement("tr");
                    row.innerHTML = `<td>${activity.user}</td><td>${activity.action}</td><td>${activity.timestamp}</td>`;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => console.error('Error fetching user activity:', error));
    }

    // Function to refresh access logs table
    function fetchAccessLogs() {
        fetch('fetch_access_logs.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector("#access-logs-table tbody");
                tableBody.innerHTML = ''; // Clear existing rows
                data.forEach(log => {
                    const row = document.createElement("tr");
                    row.innerHTML = `<td>${log.date}</td><td>${log.user}</td><td>${log.access_type}</td><td>${log.status}</td>`;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => console.error('Error fetching access logs:', error));
    }

    // Refresh tables every 10 seconds
    setInterval(fetchUserActivity, 10000);
    setInterval(fetchAccessLogs, 10000);
});