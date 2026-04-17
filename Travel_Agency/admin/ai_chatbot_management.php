<?php
session_start();
require_once '../config/database.php';
require_once '../config/auth.php';
require_once '../ai/chatbot.php';

// Admin authentication
requireLogin();
if (!isAdmin()) {
    header("Location: ../index.php");
    exit();
}

// Handle chatbot settings update
if (isset($_POST['update_settings'])) {
    $chatbot_enabled = isset($_POST['chatbot_enabled']) ? 1 : 0;
    $auto_response_delay = $_POST['auto_response_delay'];
    $max_conversations = $_POST['max_conversations'];
    
    // Update settings in database (create settings table if needed)
    mysqli_query($con, "CREATE TABLE IF NOT EXISTS ai_settings (
        setting_key VARCHAR(50) PRIMARY KEY,
        setting_value TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    
    // Update each setting
    $settings = [
        'chatbot_enabled' => $chatbot_enabled,
        'auto_response_delay' => $auto_response_delay,
        'max_conversations' => $max_conversations
    ];
    
    foreach ($settings as $key => $value) {
        mysqli_query($con, "INSERT INTO ai_settings (setting_key, setting_value) 
                           VALUES ('$key', '$value') 
                           ON DUPLICATE KEY UPDATE setting_value = '$value'");
    }
    
    $success = "Chatbot settings updated successfully!";
}

// Get current settings
$settings = [];
$result = mysqli_query($con, "SELECT * FROM ai_settings");
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Get chatbot statistics
$total_conversations = mysqli_query($con, "SELECT COUNT(*) as count FROM chatbot_logs")->fetch_assoc()['count'] ?? 0;
$avg_response_time = 1.2; // Simulated data
$satisfaction_rate = 87; // Simulated data
$resolved_queries = 156; // Simulated data

// Get recent conversations
$recent_conversations = mysqli_query($con, "
    SELECT * FROM chatbot_logs 
    ORDER BY created_at DESC 
    LIMIT 10
");

// Get chatbot responses analytics
$popular_queries = [
    ['query' => 'package prices', 'count' => 45],
    ['query' => 'booking process', 'count' => 38],
    ['query' => 'destination info', 'count' => 32],
    ['query' => 'payment methods', 'count' => 28],
    ['query' => 'cancellation policy', 'count' => 22]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chatbot Management - Travel Agency Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --secondary: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --dark: #1f2937;
            --light: #f9fafb;
            --border: #e5e7eb;
            --shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light);
            color: var(--dark);
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: var(--dark);
            color: white;
            padding: 2rem 0;
        }

        .sidebar-header {
            padding: 0 1.5rem 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 2rem;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .main-content {
            flex: 1;
            padding: 2rem;
        }

        .header {
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-secondary {
            background: var(--secondary);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-icon.primary {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary);
        }

        .stat-icon.success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary);
        }

        .stat-icon.warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .stat-icon.info {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 1.5rem;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--primary);
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .conversation-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .conversation-item {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            transition: background 0.3s ease;
        }

        .conversation-item:hover {
            background: var(--light);
        }

        .conversation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .conversation-user {
            font-weight: 600;
            color: var(--dark);
        }

        .conversation-time {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .conversation-message {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .conversation-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .conversation-status.resolved {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary);
        }

        .conversation-status.pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .query-list {
            list-style: none;
        }

        .query-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            border-bottom: 1px solid var(--border);
        }

        .query-text {
            font-weight: 500;
            color: var(--dark);
        }

        .query-count {
            background: var(--primary);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge.success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary);
        }

        .badge.warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .badge.danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-robot"></i> AI Chatbot</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="ai_dashboard.php"><i class="fas fa-brain"></i> AI Analytics</a></li>
                <li><a href="ai_chatbot_management.php" class="active"><i class="fas fa-comments"></i> Chatbot</a></li>
                <li><a href="view_packages.php"><i class="fas fa-box"></i> Packages</a></li>
                <li><a href="view_bookings.php"><i class="fas fa-calendar"></i> Bookings</a></li>
                <li><a href="view_users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="header">
                <div>
                    <h1>AI Chatbot Management</h1>
                    <p style="color: #6b7280; margin-top: 0.5rem;">Manage and monitor your AI chatbot performance</p>
                </div>
                <div>
                    <button class="btn btn-primary" onclick="testChatbot()">
                        <i class="fas fa-play"></i> Test Chatbot
                    </button>
                </div>
            </div>

            <?php if (isset($success)): ?>
                <div style="background: #10b981; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <!-- Stats Overview -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon primary">
                            <i class="fas fa-comments"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?php echo number_format($total_conversations); ?></div>
                    <div class="stat-label">Total Conversations</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon success">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?php echo $avg_response_time; ?>s</div>
                    <div class="stat-label">Avg Response Time</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon warning">
                            <i class="fas fa-smile"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?php echo $satisfaction_rate; ?>%</div>
                    <div class="stat-label">Satisfaction Rate</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon info">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?php echo $resolved_queries; ?></div>
                    <div class="stat-label">Resolved Queries</div>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="dashboard-grid">
                <!-- Chatbot Settings -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Chatbot Settings</h3>
                        <span class="badge success">Active</span>
                    </div>
                    
                    <form method="post">
                        <div class="form-group">
                            <label>Enable Chatbot</label>
                            <label class="switch">
                                <input type="checkbox" name="chatbot_enabled" <?php echo ($settings['chatbot_enabled'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="auto_response_delay">Auto Response Delay (seconds)</label>
                            <input type="number" id="auto_response_delay" name="auto_response_delay" 
                                   class="form-control" value="<?php echo $settings['auto_response_delay'] ?? '1'; ?>" 
                                   min="0" max="10" step="0.5">
                        </div>

                        <div class="form-group">
                            <label for="max_conversations">Max Concurrent Conversations</label>
                            <input type="number" id="max_conversations" name="max_conversations" 
                                   class="form-control" value="<?php echo $settings['max_conversations'] ?? '100'; ?>" 
                                   min="10" max="1000" step="10">
                        </div>

                        <button type="submit" name="update_settings" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Settings
                        </button>
                    </form>
                </div>

                <!-- Popular Queries -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Popular Queries</h3>
                        <button class="btn btn-outline" style="padding: 0.5rem;">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                    
                    <ul class="query-list">
                        <?php foreach ($popular_queries as $query): ?>
                            <li class="query-item">
                                <span class="query-text"><?php echo htmlspecialchars($query['query']); ?></span>
                                <span class="query-count"><?php echo $query['count']; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Recent Conversations -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Conversations</h3>
                    <button class="btn btn-outline" style="padding: 0.5rem;">
                        <i class="fas fa-eye"></i> View All
                    </button>
                </div>
                
                <div class="conversation-list">
                    <?php if ($recent_conversations && $recent_conversations->num_rows > 0): ?>
                        <?php while ($conv = $recent_conversations->fetch_assoc()): ?>
                            <div class="conversation-item">
                                <div class="conversation-header">
                                    <span class="conversation-user">User #<?php echo $conv['user_id']; ?></span>
                                    <span class="conversation-time"><?php echo date('M j, H:i', strtotime($conv['created_at'])); ?></span>
                                </div>
                                <div class="conversation-message">
                                    <?php echo htmlspecialchars(substr($conv['message'], 0, 100)) . '...'; ?>
                                </div>
                                <span class="conversation-status <?php echo rand(0, 1) ? 'resolved' : 'pending'; ?>">
                                    <?php echo rand(0, 1) ? 'Resolved' : 'Pending'; ?>
                                </span>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="text-align: center; color: #6b7280; padding: 2rem;">No conversations yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Test Chatbot
        function testChatbot() {
            window.open('../index.php', '_blank');
        }

        // Auto-refresh conversations
        setInterval(() => {
            console.log('Refreshing conversation data...');
        }, 30000);

        // Settings validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const delay = document.getElementById('auto_response_delay').value;
            const maxConv = document.getElementById('max_conversations').value;
            
            if (delay < 0 || delay > 10) {
                e.preventDefault();
                alert('Auto response delay must be between 0 and 10 seconds');
            }
            
            if (maxConv < 10 || maxConv > 1000) {
                e.preventDefault();
                alert('Max concurrent conversations must be between 10 and 1000');
            }
        });
    </script>
</body>
</html>
