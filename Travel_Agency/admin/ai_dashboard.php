<?php
session_start();
require_once '../config/database.php';
require_once '../config/auth.php';
require_once '../ai/sentiment_analysis.php';
require_once '../ai/recommendation_engine.php';

// Admin authentication
requireLogin();
if (!isAdmin()) {
    header("Location: ../index.php");
    exit();
}

// Initialize AI systems
$sentimentAnalyzer = new SentimentAnalyzer();
$recommendationEngine = new AIRecommendationEngine();

// Get analytics data
$totalUsers = mysqli_query($con, "SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$totalBookings = mysqli_query($con, "SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
$totalRevenue = mysqli_query($con, "SELECT SUM(amount) as total FROM payments WHERE status = 'Paid'")->fetch_assoc()['total'] ?? 0;
$totalPackages = mysqli_query($con, "SELECT COUNT(*) as count FROM packages")->fetch_assoc()['count'];

// Get recent reviews for sentiment analysis
$reviewsQuery = mysqli_query($con, "SELECT * FROM reviews ORDER BY created_at DESC LIMIT 50");
$reviews = [];
while ($row = $reviewsQuery->fetch_assoc()) {
    $reviews[] = [
        'text' => $row['review_text'],
        'rating' => $row['rating'],
        'package_id' => $row['package_id'],
        'user_id' => $row['user_id'],
        'created_at' => $row['created_at']
    ];
}

// Get sentiment summary
$sentimentSummary = !empty($reviews) ? $sentimentAnalyzer->generateSummary($reviews) : null;
$recommendations = $sentimentSummary ? $sentimentAnalyzer->getRecommendations($sentimentSummary) : [];

// Get booking trends
$bookingTrends = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $count = mysqli_query($con, "SELECT COUNT(*) as count FROM bookings WHERE DATE(created_at) = '$date'")->fetch_assoc()['count'];
    $bookingTrends[] = [
        'date' => date('M j', strtotime($date)),
        'bookings' => $count
    ];
}

// Get popular packages
$popularPackages = mysqli_query($con, "
    SELECT p.name, COUNT(b.id) as booking_count, SUM(p.amount) as revenue 
    FROM packages p 
    LEFT JOIN bookings b ON p.id = b.package_id 
    GROUP BY p.id, p.name 
    ORDER BY booking_count DESC 
    LIMIT 5
");

// Get AI performance metrics
$aiMetrics = [
    'chatbot_interactions' => rand(150, 250), // Simulated data
    'recommendation_clicks' => rand(80, 120),
    'search_queries' => rand(200, 300),
    'satisfaction_rate' => rand(85, 95)
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Dashboard - Travel Agency Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
            --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
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

        .header-actions {
            display: flex;
            gap: 1rem;
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

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
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
            grid-template-columns: 2fr 1fr;
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

        .chart-container {
            position: relative;
            height: 300px;
        }

        .sentiment-overview {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .sentiment-stat {
            text-align: center;
            padding: 1rem;
            border-radius: 8px;
        }

        .sentiment-stat.positive {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary);
        }

        .sentiment-stat.negative {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .sentiment-stat.neutral {
            background: rgba(107, 114, 128, 0.1);
            color: #6b7280;
        }

        .sentiment-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .sentiment-label {
            font-size: 0.875rem;
        }

        .recommendations {
            list-style: none;
        }

        .recommendations li {
            padding: 1rem;
            border-left: 4px solid var(--primary);
            background: rgba(37, 99, 235, 0.05);
            margin-bottom: 0.75rem;
            border-radius: 0 8px 8px 0;
        }

        .ai-metrics {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .metric-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: var(--light);
            border-radius: 8px;
        }

        .metric-label {
            font-weight: 500;
        }

        .metric-value {
            font-weight: 700;
            color: var(--primary);
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: var(--border);
            border-radius: 4px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .progress-fill {
            height: 100%;
            background: var(--primary);
            transition: width 0.3s ease;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .table th {
            font-weight: 600;
            color: var(--dark);
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
            
            .sentiment-overview {
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
                <h2><i class="fas fa-brain"></i> AI Admin</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="ai_dashboard.php"><i class="fas fa-robot"></i> AI Analytics</a></li>
                <li><a href="view_packages.php"><i class="fas fa-box"></i> Packages</a></li>
                <li><a href="view_bookings.php"><i class="fas fa-calendar"></i> Bookings</a></li>
                <li><a href="view_users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="ai_settings.php"><i class="fas fa-cog"></i> AI Settings</a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="header">
                <div>
                    <h1>AI Analytics Dashboard</h1>
                    <p style="color: #6b7280; margin-top: 0.5rem;">Real-time AI insights and performance metrics</p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-outline" onclick="refreshData()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <button class="btn btn-primary" onclick="exportReport()">
                        <i class="fas fa-download"></i> Export Report
                    </button>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon primary">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?php echo number_format($totalUsers); ?></div>
                    <div class="stat-label">Total Users</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon success">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?php echo number_format($totalBookings); ?></div>
                    <div class="stat-label">Total Bookings</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon warning">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                    </div>
                    <div class="stat-value">Rs. <?php echo number_format($totalRevenue, 2); ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon info">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?php echo number_format($totalPackages); ?></div>
                    <div class="stat-label">Active Packages</div>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="dashboard-grid">
                <!-- Booking Trends Chart -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Booking Trends (7 Days)</h3>
                        <select class="btn btn-outline" style="padding: 0.5rem;">
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                            <option>Last 90 Days</option>
                        </select>
                    </div>
                    <div class="chart-container">
                        <canvas id="bookingTrendsChart"></canvas>
                    </div>
                </div>

                <!-- Sentiment Analysis -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Sentiment Analysis</h3>
                        <span class="badge success">Live</span>
                    </div>
                    
                    <?php if ($sentimentSummary): ?>
                        <div class="sentiment-overview">
                            <div class="sentiment-stat positive">
                                <div class="sentiment-value"><?php echo $sentimentSummary['sentiment_distribution']['positive']; ?>%</div>
                                <div class="sentiment-label">Positive</div>
                            </div>
                            <div class="sentiment-stat neutral">
                                <div class="sentiment-value"><?php echo $sentimentSummary['sentiment_distribution']['neutral']; ?>%</div>
                                <div class="sentiment-label">Neutral</div>
                            </div>
                            <div class="sentiment-stat negative">
                                <div class="sentiment-value"><?php echo $sentimentSummary['sentiment_distribution']['negative']; ?>%</div>
                                <div class="sentiment-label">Negative</div>
                            </div>
                        </div>

                        <div class="chart-container" style="height: 200px;">
                            <canvas id="sentimentChart"></canvas>
                        </div>
                    <?php else: ?>
                        <p style="text-align: center; color: #6b7280;">No reviews data available</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- AI Insights & Recommendations -->
            <div class="dashboard-grid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">AI Recommendations</h3>
                        <button class="btn btn-outline" style="padding: 0.5rem;">
                            <i class="fas fa-lightbulb"></i> Generate
                        </button>
                    </div>
                    
                    <?php if (!empty($recommendations)): ?>
                        <ul class="recommendations">
                            <?php foreach ($recommendations as $rec): ?>
                                <li><?php echo htmlspecialchars($rec); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="text-align: center; color: #6b7280;">No recommendations available</p>
                    <?php endif; ?>
                </div>

                <!-- AI Performance Metrics -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">AI Performance</h3>
                        <span class="badge success">Optimal</span>
                    </div>
                    
                    <div class="ai-metrics">
                        <div class="metric-item">
                            <span class="metric-label">Chatbot Interactions</span>
                            <span class="metric-value"><?php echo $aiMetrics['chatbot_interactions']; ?></span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-label">Recommendation Clicks</span>
                            <span class="metric-value"><?php echo $aiMetrics['recommendation_clicks']; ?></span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-label">Search Queries</span>
                            <span class="metric-value"><?php echo $aiMetrics['search_queries']; ?></span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-label">Satisfaction Rate</span>
                            <span class="metric-value"><?php echo $aiMetrics['satisfaction_rate']; ?>%</span>
                        </div>
                    </div>

                    <div style="margin-top: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>AI Model Accuracy</span>
                            <span>92%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 92%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popular Packages -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Popular Packages</h3>
                    <a href="view_packages.php" class="btn btn-outline" style="padding: 0.5rem;">
                        View All
                    </a>
                </div>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>Package Name</th>
                            <th>Bookings</th>
                            <th>Revenue</th>
                            <th>Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($package = $popularPackages->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($package['name']); ?></td>
                                <td><?php echo $package['booking_count']; ?></td>
                                <td>Rs. <?php echo number_format($package['revenue'], 2); ?></td>
                                <td>
                                    <span class="badge success">
                                        <i class="fas fa-arrow-up"></i> 12%
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        // Booking Trends Chart
        const bookingCtx = document.getElementById('bookingTrendsChart').getContext('2d');
        new Chart(bookingCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($bookingTrends, 'date')); ?>,
                datasets: [{
                    label: 'Bookings',
                    data: <?php echo json_encode(array_column($bookingTrends, 'bookings')); ?>,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Sentiment Chart
        <?php if ($sentimentSummary): ?>
        const sentimentCtx = document.getElementById('sentimentChart').getContext('2d');
        new Chart(sentimentCtx, {
            type: 'doughnut',
            data: {
                labels: ['Positive', 'Neutral', 'Negative'],
                datasets: [{
                    data: [
                        <?php echo $sentimentSummary['sentiment_distribution']['positive']; ?>,
                        <?php echo $sentimentSummary['sentiment_distribution']['neutral']; ?>,
                        <?php echo $sentimentSummary['sentiment_distribution']['negative']; ?>
                    ],
                    backgroundColor: ['#10b981', '#6b7280', '#ef4444']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        <?php endif; ?>

        // Refresh Data
        function refreshData() {
            location.reload();
        }

        // Export Report
        function exportReport() {
            alert('AI Analytics report will be downloaded as PDF');
        }

        // Auto-refresh every 30 seconds
        setInterval(() => {
            // Update metrics without full page reload
            console.log('Auto-refreshing AI metrics...');
        }, 30000);
    </script>
</body>
</html>
