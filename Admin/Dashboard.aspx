<%@ Page Title="Admin Dashboard" Language="C#" MasterPageFile="~/MasterPage.master" AutoEventWireup="true" CodeFile="Dashboard.aspx.cs" Inherits="Admin_Dashboard" %>

<asp:Content ID="Content1" ContentPlaceHolderID="TitleContent" Runat="Server">
    Admin Dashboard
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="HeadContent" Runat="Server">
    <style>
        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 15px;
        }
        
        .admin-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .admin-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .stat-widget {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-widget:hover {
            transform: translateY(-5px);
        }
        
        .stat-widget::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #667eea;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #666;
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        
        .stat-change {
            font-size: 0.875rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .stat-change.positive {
            background: #e8f5e8;
            color: #2e7d32;
        }
        
        .stat-change.negative {
            background: #ffebee;
            color: #c62828;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .recent-activities {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.2rem;
        }
        
        .activity-user {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        
        .activity-event {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }
        
        .activity-message {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-weight: 500;
            margin-bottom: 0.25rem;
        }
        
        .activity-time {
            font-size: 0.875rem;
            color: #666;
        }
        
        .quick-actions {
            display: grid;
            gap: 1rem;
        }
        
        .action-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .action-card:hover {
            transform: translateY(-3px);
        }
        
        .action-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #667eea;
        }
        
        .action-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .action-desc {
            font-size: 0.875rem;
            color: #666;
            margin-bottom: 1rem;
        }
        
        .charts-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin: 2rem 0;
        }
        
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }
        
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .charts-section {
                grid-template-columns: 1fr;
            }
            
            .admin-title {
                font-size: 2rem;
            }
        }
    </style>
</asp:Content>

<asp:Content ID="Content3" ContentPlaceHolderID="MainContent" Runat="Server">
    <div class="container">
        <!-- Admin Header -->
        <div class="admin-header">
            <div style="text-align: center;">
                <h1 class="admin-title">
                    <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                </h1>
                <p class="admin-subtitle">
                    Welcome back, Administrator! Here's what's happening in your alumni community.
                </p>
            </div>
        </div>

        <!-- Dashboard Statistics -->
        <section class="dashboard-stats">
            <div class="stat-widget">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value">
                    <asp:Label ID="lblTotalUsers" runat="server" Text="0"></asp:Label>
                </div>
                <div class="stat-label">Total Alumni</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +12% this month
                </div>
            </div>
            
            <div class="stat-widget">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-value">
                    <asp:Label ID="lblTotalEvents" runat="server" Text="0"></asp:Label>
                </div>
                <div class="stat-label">Active Events</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +5 new events
                </div>
            </div>
            
            <div class="stat-widget">
                <div class="stat-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-value">
                    <asp:Label ID="lblTotalMessages" runat="server" Text="0"></asp:Label>
                </div>
                <div class="stat-label">Messages Today</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +23% increase
                </div>
            </div>
            
            <div class="stat-widget">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-value">
                    <asp:Label ID="lblActiveUsers" runat="server" Text="0"></asp:Label>
                </div>
                <div class="stat-label">Active This Week</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +8% engagement
                </div>
            </div>
        </section>

        <!-- Main Dashboard Content -->
        <div class="dashboard-grid">
            <!-- Recent Activities -->
            <div class="recent-activities">
                <h3 style="margin-bottom: 1.5rem; color: #333;">
                    <i class="fas fa-clock"></i> Recent Activities
                </h3>
                
                <asp:UpdatePanel ID="upActivities" runat="server">
                    <ContentTemplate>
                        <asp:Repeater ID="rptActivities" runat="server">
                            <ItemTemplate>
                                <div class="activity-item">
                                    <div class="activity-icon activity-<%# Eval("Type").ToString().ToLower() %>">
                                        <i class="fas fa-<%# GetActivityIcon(Eval("Type").ToString()) %>"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title"><%# Eval("Description") %></div>
                                        <div class="activity-time"><%# FormatActivityTime(Convert.ToDateTime(Eval("CreatedDate"))) %></div>
                                    </div>
                                </div>
                            </ItemTemplate>
                        </asp:Repeater>
                        
                        <asp:Panel ID="pnlNoActivities" runat="server" Visible="false" style="text-align: center; padding: 2rem; color: #666;">
                            <i class="fas fa-history" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                            <p>No recent activities to display</p>
                        </asp:Panel>
                    </ContentTemplate>
                </asp:UpdatePanel>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3 style="margin-bottom: 1.5rem; color: #333;">
                    <i class="fas fa-rocket"></i> Quick Actions
                </h3>
                
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="action-title">Add New Alumni</div>
                    <div class="action-desc">Register a new alumni member</div>
                    <a href="ManageUsers.aspx" class="btn">Add Alumni</a>
                </div>
                
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="action-title">Create Event</div>
                    <div class="action-desc">Organize a new alumni event</div>
                    <a href="ManageEvents.aspx" class="btn">Create Event</a>
                </div>
                
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <div class="action-title">Post News</div>
                    <div class="action-desc">Share important announcements</div>
                    <a href="ManageNews.aspx" class="btn">Post News</a>
                </div>
                
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="action-title">View Reports</div>
                    <div class="action-desc">Generate detailed analytics</div>
                    <a href="Reports.aspx" class="btn">View Reports</a>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="chart-container">
                <h3 class="chart-title">
                    <i class="fas fa-chart-line"></i> Registration Trends
                </h3>
                <canvas id="registrationChart" width="400" height="200"></canvas>
            </div>
            
            <div class="chart-container">
                <h3 class="chart-title">
                    <i class="fas fa-chart-pie"></i> User Activity
                </h3>
                <canvas id="activityChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- System Status -->
        <section class="card" style="margin-top: 2rem;">
            <div class="card-header">
                <h3><i class="fas fa-server"></i> System Status</h3>
            </div>
            <div class="card-body">
                <div class="grid grid-4">
                    <div style="text-align: center;">
                        <div style="font-size: 2rem; color: #4caf50; margin-bottom: 0.5rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <strong>Database</strong>
                        <p style="color: #4caf50; margin: 0;">Online</p>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 2rem; color: #4caf50; margin-bottom: 0.5rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <strong>Email Service</strong>
                        <p style="color: #4caf50; margin: 0;">Active</p>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 2rem; color: #4caf50; margin-bottom: 0.5rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <strong>File Upload</strong>
                        <p style="color: #4caf50; margin: 0;">Working</p>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 2rem; color: #ff9800; margin-bottom: 0.5rem;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <strong>Backup</strong>
                        <p style="color: #ff9800; margin: 0;">Due</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</asp:Content>

<asp:Content ID="Content4" ContentPlaceHolderID="ScriptContent" Runat="Server">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize charts
            initializeCharts();
            
            // Auto-refresh activities every 30 seconds
            setInterval(function() {
                __doPostBack('<%= upActivities.UniqueID %>', '');
            }, 30000);
            
            // Animate counters
            animateCounters();
        });

        function initializeCharts() {
            // Registration Trends Chart
            const regCtx = document.getElementById('registrationChart').getContext('2d');
            new Chart(regCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'New Registrations',
                        data: [12, 19, 15, 25, 22, 30],
                        borderColor: 'rgb(102, 126, 234)',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
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

            // Activity Chart
            const actCtx = document.getElementById('activityChart').getContext('2d');
            new Chart(actCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Logins', 'Messages', 'Events', 'Profile Updates'],
                    datasets: [{
                        data: [45, 25, 20, 10],
                        backgroundColor: [
                            '#667eea',
                            '#764ba2',
                            '#f093fb',
                            '#4facfe'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        function animateCounters() {
            $('.stat-value').each(function() {
                var $this = $(this);
                var countTo = parseInt($this.text().replace(/[^0-9]/g, '')) || 0;
                
                $({ countNum: 0 }).animate({
                    countNum: countTo
                }, {
                    duration: 2000,
                    easing: 'swing',
                    step: function() {
                        $this.text(Math.floor(this.countNum).toLocaleString());
                    },
                    complete: function() {
                        $this.text(countTo.toLocaleString());
                    }
                });
            });
        }
    </script>
</asp:Content>