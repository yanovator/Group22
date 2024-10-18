<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Belinda Hok"/>
    <title>Production Operator Home</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <header>
        <h1>Home</h1>
        <div class="notification">
            <img src="images/bell.jpg" alt="Notifications" class="bell-icon">
            <span class="notification-count">2</span>
        </div>
        <div class="user-info">
            <span>John Smith</span>
            <span class="user-role">Production Operator</span>
        </div>
    </header>

    <nav>
        <ul class="nav-menu">
            <li><a href="homeProductionOp.php" class="active"><img src="images/home.png" alt="Home"> </a></li>
            <li><a href="#"><img src="images/performance.png" alt="Factory Performance"> </a></li>
            <li><a href="updateMachines.php"><img src="images/machine.png" alt="Machine Management"> </a></li>
            <li><a href="updateJobs.php"><img src="images/employees.png" alt="Job Management"> </a></li>
            <li><a href="inbox.html"><img src="images/inbox.png" alt="Inbox"> </a></li>
            <li><a href="events.html"><img src="images/calendar.png" alt="Events"> </a></li>
            <li><a href="settings.html"><img src="images/settings.png" alt="Settings"> </a></li>
        </ul>
    </nav>

    <div class="prodOpContainer">
        <input type="button" value="Manage Jobs" class="prodOpNav" id="btn-jobs">
        <input type="button" value="Manage Machines" class="prodOpNav" id="btn-machines">
    </div>
</body>

<script>
    document.getElementById("btn-jobs").onclick = function () {
    location.href = "updateJobs.php";
    };

    document.getElementById("btn-machines").onclick = function () {
        location.href = "updateMachines.php";
    };
</script>
</html>