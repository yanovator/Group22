<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Belinda Hok"/>
    <title>Production Operator Home</title>
    <link rel="stylesheet" href="Styles/styles.css">
</head>

<body>
    <header>
        <h1>Home</h1>
        <div class="notification">
            <img src="images/bell.jpg" alt="Notifications" class="bell-icon">
            <span class="notification-count">2</span>
        </div>
        <div class="user-info">
            <span>Brian Wessex</span>
            <span class="user-role">Production Operator</span>
        </div>
    </header>

    <nav>
        <ul class="nav-menu">
            <li><a href="homeProductionOp.php" class="active"><img src="images/home.png" alt="Home"> </a></li>
            <li><a href="index.php"><img src="images/performance.png" alt="Factory Performance"> </a></li>
            <li><a href="updateMachines.php"><img src="images/machine.png" alt="Machine Management"> </a></li>
            <li><a href="updateJobs.php"><img src="images/employees.png" alt="Job Management"> </a></li>
            <li><a href="inbox.html"><img src="images/inbox.png" alt="Inbox"> </a></li>
            <li><a href="events.html"><img src="images/calendar.png" alt="Events"> </a></li>
            <li><a href="settings.html"><img src="images/settings.png" alt="Settings"> </a></li>
        </ul>
    </nav>

    <div class="prodOpContainer">
        <button class="prodOpNav" id="btn-performance">Dashboard</button>
        <button class="prodOpNav" id="btn-machines">Manage Machines</button>
        <button class="prodOpNav" id="btn-jobs">Manage Jobs</button>
        <button class="prodOpNav" id="btn-notes">Task Notes</button>
    </div>
</body>

<script>
    document.getElementById("btn-performance").onclick = function () {
        location.href = "index.php";
    };

    document.getElementById("btn-machines").onclick = function () {
        location.href = "updateMachines.php";
    };

    document.getElementById("btn-jobs").onclick = function () {
    location.href = "updateJobs.php";
    };

    document.getElementById("btn-notes").onclick = function () {
        location.href = "create_note.php";
    };
</script>
</html>
