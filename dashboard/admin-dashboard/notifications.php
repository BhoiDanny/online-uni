<?php
// Start the session (if needed for user authentication)
session_start();

// You might want to include any necessary PHP files here
// include 'config.php';
// include 'functions.php';

// Check if user is logged in as admin (implement your own logic)
// if (!isAdminLoggedIn()) {
//     header('Location: login.php');
//     exit();
// }

// Function to fetch notifications from the database
function getNotifications() {
    // This is a placeholder. In a real application, you would fetch this from a database.
    return [
        ['id' => 1, 'message' => 'New appointment request from John Doe', 'time' => '2 hours ago', 'read' => false],
        ['id' => 2, 'message' => 'System maintenance scheduled for tonight', 'time' => '1 day ago', 'read' => true],
        // Add more notifications as needed
    ];
}

// Handle AJAX requests
if (isset($_POST['action'])) {
    if ($_POST['action'] === 'markRead') {
        $notificationId = $_POST['id'];
        // Add logic to mark notification as read in the database
        echo json_encode(['success' => true]);
        exit;
    }
}

$notifications = getNotifications();
?>

<!DOCTYPE html>
<html lang="en">
<!-- Head content from base template -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProBookSys - Notifications</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Your existing styles here */
        :root {
            --primary-color: #1a237e;
            --secondary-color: #3f51b5;
            --text-color: #212121;
            --bg-color: #f5f5f5;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        .dark-theme {
            --primary-color: #3f51b5;
            --secondary-color: #1a237e;
            --text-color: #f5f5f5;
            --bg-color: #212121;
        }

        .dark-theme .card {
            background-color: #333;
            color: #f5f5f5;
        }

        .dark-theme .navbar {
            background-color: #333 !important;
            color: #f5f5f5;
        }

        .dark-theme .navbar-brand,
        .dark-theme .navbar-nav .nav-link {
            color: #f5f5f5;
        }

        .dark-theme .table {
            color: #f5f5f5;
        }

        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: var(--primary-color);
            color: white;
            transition: all 0.3s;
            z-index: 1000;
        }

        #sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        #sidebar .nav-link {
            color: white;
            transition: all 0.3s;
        }

        #sidebar .nav-link:hover {
            background-color: var(--secondary-color);
        }

        #content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s;
        }

        #content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        .card {
            transition: all 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        #theme-toggle, #sidebar-toggle {
            cursor: pointer;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .dropdown-menu {
            background-color: var(--primary-color);
        }

        .dropdown-item {
            color: white;
        }

        .dropdown-item:hover {
            background-color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <!-- Sidebar content -->
    <div id="sidebar">
        <div class="p-3">
            <h3>ProBookSys</h3>
            <hr>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="admin-dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-users"></i> Manage Users</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="add-lecturer.php"><i class="fas fa-user-plus"></i> Add Lecture</a></li>
                        <li><a class="dropdown-item" href="faculty.php"><i class="fas fa-chalkboard-teacher"></i> Faculty</a></li>
                        <li><a class="dropdown-item" href="department.php"><i class="fas fa-building"></i> Department</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a href="manage-appointments.php" class="nav-link"><i class="fas fa-calendar-alt"></i> Manage Appointments</a></li>
                <li class="nav-item"><a href="system-configuration.php" class="nav-link"><i class="fas fa-cogs"></i> System Configuration</a></li>
                <li class="nav-item"><a href="reports.php" class="nav-link"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li class="nav-item"><a href="notifications.php" class="nav-link"><i class="fas fa-bell"></i> Notifications</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </div>

    <div id="content">
        <!-- Navbar content -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <div class="container-fluid">
                <span id="sidebar-toggle" class="me-3"><i class="fas fa-bars"></i></span>
                <a class="navbar-brand" href="#">Admin Dashboard</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-bell"></i> Notifications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-user"></i> Admin User</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" id="theme-toggle"><i class="fas fa-moon"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <h2>Notifications</h2>
            <div id="notificationsList">
                <?php foreach ($notifications as $notif): ?>
                    <div class="alert <?php echo $notif['read'] ? 'alert-secondary' : 'alert-primary'; ?> d-flex justify-content-between">
                        <div>
                            <strong><?php echo htmlspecialchars($notif['message']); ?></strong>
                            <p class="mb-0"><small><?php echo htmlspecialchars($notif['time']); ?></small></p>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary markReadBtn" data-id="<?php echo $notif['id']; ?>">
                            <?php echo $notif['read'] ? 'Mark Unread' : 'Mark Read'; ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('content').classList.toggle('expanded');
        });

        // Toggle dark theme
        document.getElementById('theme-toggle').addEventListener('click', function() {
            document.body.classList.toggle('dark-theme');
            this.innerHTML = document.body.classList.contains('dark-theme') ? 
                '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        });

        // Mark notification as read/unread
        document.querySelectorAll('.markReadBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                const notificationId = this.getAttribute('data-id');
                fetch('notifications.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=markRead&id=${notificationId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the page to reflect changes
                        location.reload();
                    }
                });
            });
        });
    </script>
</body>
</html>