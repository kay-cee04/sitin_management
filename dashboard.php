<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php?error=' . urlencode('Please log in to access the dashboard.') . '&type=login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Sit-in Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --uc-gold: #F5A623;
            --uc-deep-gold: #C8820A;
            --uc-dark: #0A0E1A;
            --uc-darker: #060810;
            --uc-blue: #0D1B3E;
            --uc-navy: #152952;
            --uc-accent: #E8C547;
            --glass: rgba(255,255,255,0.04);
            --glass-border: rgba(245,166,35,0.2);
            --sidebar-w: 260px;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--uc-darker);
            color: #fff;
            min-height: 100vh;
            display: flex;
        }

        .bg-canvas {
            position: fixed; inset: 0; z-index: 0;
            background: radial-gradient(ellipse at 80% 20%, rgba(200,130,10,0.08) 0%, transparent 50%),
                        linear-gradient(135deg, #060810 0%, #0A0E1A 60%, #0D1B3E 100%);
        }

        /* ─── SIDEBAR ─── */
        .sidebar {
            position: fixed; left: 0; top: 0; bottom: 0;
            width: var(--sidebar-w); z-index: 50;
            background: rgba(10,14,26,0.98);
            border-right: 1px solid var(--glass-border);
            backdrop-filter: blur(20px);
            display: flex; flex-direction: column;
        }

        .sidebar-brand {
            padding: 20px 24px;
            border-bottom: 1px solid var(--glass-border);
            display: flex; align-items: center; gap: 14px;
        }

        .sidebar-logo {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            border: 2.5px solid var(--uc-gold);
            flex-shrink: 0;
            background: #ffffff;        
            padding: 5px;               
            box-shadow: 0 0 18px rgba(245,166,35,0.4);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;        
            display: block;
        }

        .sidebar-name {
            font-family: 'Playfair Display', serif;
            font-size: 13px; font-weight: 700;
            color: var(--uc-gold); line-height: 1.3;
        }

        .sidebar-system {
            font-size: 10px; color: rgba(255,255,255,0.3);
            letter-spacing: 1.5px; text-transform: uppercase;
            margin-top: 2px;
        }

        .sidebar-nav {
            flex: 1; padding: 20px 0; overflow-y: auto;
        }

        .nav-section-label {
            padding: 8px 24px 4px;
            font-size: 10px; letter-spacing: 2px; text-transform: uppercase;
            color: rgba(255,255,255,0.2);
        }

        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 24px; margin: 2px 12px; border-radius: 10px;
            color: rgba(255,255,255,0.5);
            text-decoration: none; font-size: 14px; font-weight: 500;
            transition: all 0.2s ease; cursor: pointer; border: none;
            background: transparent; width: calc(100% - 24px); text-align: left;
        }

        .nav-item:hover, .nav-item.active {
            background: rgba(245,166,35,0.08);
            color: var(--uc-gold);
        }

        .nav-item.active { border: 1px solid rgba(245,166,35,0.2); }
        .nav-item i { width: 18px; text-align: center; }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid var(--glass-border);
        }

        .admin-profile {
            display: flex; align-items: center; gap: 12px;
            padding: 12px; border-radius: 12px;
            background: rgba(245,166,35,0.05);
            border: 1px solid rgba(245,166,35,0.1);
            margin-bottom: 12px;
        }

        .admin-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, var(--uc-gold), var(--uc-deep-gold));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Space Mono', monospace;
            font-size: 14px; font-weight: 700;
            color: var(--uc-darker); flex-shrink: 0;
        }

        .admin-info { flex: 1; min-width: 0; }
        .admin-name { font-size: 13px; font-weight: 600; color: #fff; }
        .admin-role { font-size: 11px; color: var(--uc-gold); text-transform: capitalize; }

        .btn-logout {
            width: 100%; padding: 10px;
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 10px; color: #F87171;
            font-size: 13px; font-weight: 600;
            cursor: pointer; transition: all 0.2s ease;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            text-decoration: none;
        }

        .btn-logout:hover { background: rgba(239,68,68,0.15); }

        /* ─── MAIN CONTENT ─── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1; padding: 40px;
            position: relative; z-index: 1;
            min-height: 100vh;
        }

        .topbar {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 36px;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 32px; font-weight: 700;
        }

        .page-time {
            font-family: 'Space Mono', monospace;
            font-size: 13px; color: rgba(255,255,255,0.4);
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px; margin-bottom: 36px;
        }

        .card {
            padding: 28px;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            position: relative; overflow: hidden;
        }

        .card::after {
            content: '';
            position: absolute; top: 0; right: 0;
            width: 80px; height: 80px;
            border-radius: 50%;
            background: rgba(245,166,35,0.05);
            transform: translate(30%, -30%);
        }

        .card:hover { transform: translateY(-4px); border-color: rgba(245,166,35,0.4); }

        .card-icon {
            width: 48px; height: 48px; border-radius: 14px;
            background: linear-gradient(135deg, rgba(245,166,35,0.15), rgba(245,166,35,0.05));
            border: 1px solid rgba(245,166,35,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: var(--uc-gold);
            margin-bottom: 16px;
        }

        .card-value {
            font-family: 'Space Mono', monospace;
            font-size: 32px; font-weight: 700; color: #fff;
            margin-bottom: 4px;
        }

        .card-label {
            font-size: 13px; color: rgba(255,255,255,0.4);
            text-transform: uppercase; letter-spacing: 1px;
        }

        .card-change { font-size: 12px; margin-top: 8px; color: #34D399; }

        .welcome-banner {
            padding: 32px;
            background: linear-gradient(135deg, rgba(13,27,62,0.8), rgba(245,166,35,0.08));
            border: 1px solid var(--glass-border);
            border-radius: 20px; margin-bottom: 36px;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 20px;
        }

        .welcome-text h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px; font-weight: 700; margin-bottom: 6px;
        }

        .welcome-text p { font-size: 14px; color: rgba(255,255,255,0.4); }

        .welcome-badge {
            padding: 10px 20px;
            background: rgba(245,166,35,0.1);
            border: 1px solid rgba(245,166,35,0.3);
            border-radius: 50px;
            font-family: 'Space Mono', monospace;
            font-size: 12px; color: var(--uc-gold);
            letter-spacing: 1px;
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="bg-canvas"></div>

    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/68/University_of_Cebu_Logo.png"
                     alt="UC Logo"
                     onerror="this.parentElement.style.background='var(--uc-gold)'; this.parentElement.innerHTML='<span style=\'color:#060810;font-weight:700;font-size:16px;\'>UC</span>'">
            </div>
            <div>
                <div class="sidebar-name">University of Cebu</div>
                <div class="sidebar-system">Sit-in System</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Main</div>
            <a class="nav-item active" href="dashboard.php">
                <i class="fas fa-gauge-high"></i> Dashboard
            </a>
            <a class="nav-item" href="#">
                <i class="fas fa-users"></i> Students
            </a>
            <a class="nav-item" href="#">
                <i class="fas fa-chair"></i> Sit-in Records
            </a>
            <a class="nav-item" href="#">
                <i class="fas fa-calendar-check"></i> Sessions
            </a>
            <div class="nav-section-label" style="margin-top:16px;">Reports</div>
            <a class="nav-item" href="#">
                <i class="fas fa-chart-bar"></i> Analytics
            </a>
            <a class="nav-item" href="#">
                <i class="fas fa-file-lines"></i> Generate Report
            </a>
            <div class="nav-section-label" style="margin-top:16px;">Admin</div>
            <a class="nav-item" href="#">
                <i class="fas fa-bell"></i> Announcements
            </a>
            <a class="nav-item" href="#">
                <i class="fas fa-gear"></i> Settings
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="admin-profile">
                <div class="admin-avatar">
                    <?= strtoupper(substr($_SESSION['admin_name'], 0, 1)) ?>
                </div>
                <div class="admin-info">
                    <div class="admin-name"><?= htmlspecialchars($_SESSION['admin_name']) ?></div>
                    <div class="admin-role"><?= htmlspecialchars($_SESSION['admin_role']) ?></div>
                </div>
            </div>
            <a href="auth/logout.php" class="btn-logout">
                <i class="fas fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <main class="main">
        <div class="topbar">
            <h1 class="page-title">Dashboard</h1>
            <div class="page-time" id="datetime"></div>
        </div>

        <div class="welcome-banner">
            <div class="welcome-text">
                <h2>Welcome back, <?= htmlspecialchars(explode(' ', $_SESSION['admin_name'])[0]) ?>! 👋</h2>
                <p>Here's what's happening in the Sit-in Management System today.</p>
            </div>
            <div class="welcome-badge">
                <i class="fas fa-shield-halved"></i> <?= strtoupper($_SESSION['admin_role']) ?>
            </div>
        </div>

        <div class="cards-grid">
            <div class="card">
                <div class="card-icon"><i class="fas fa-users"></i></div>
                <div class="card-value">0</div>
                <div class="card-label">Total Students</div>
                <div class="card-change"><i class="fas fa-plus"></i> No records yet</div>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-chair"></i></div>
                <div class="card-value">0</div>
                <div class="card-label">Active Sit-ins</div>
                <div class="card-change"><i class="fas fa-circle"></i> None active</div>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="card-value">0</div>
                <div class="card-label">Today's Sessions</div>
                <div class="card-change">No sessions today</div>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-user-shield"></i></div>
                <div class="card-value">1</div>
                <div class="card-label">Admins Online</div>
                <div class="card-change" style="color:#34D399;"><i class="fas fa-circle"></i> You're active</div>
            </div>
        </div>

        <div class="card" style="text-align:center; padding: 60px 40px;">
            <i class="fas fa-wrench" style="font-size:48px; color:var(--uc-gold); margin-bottom:16px;"></i>
            <h3 style="font-family:'Playfair Display',serif; font-size:22px; margin-bottom:10px;">System Ready</h3>
            <p style="color:rgba(255,255,255,0.4); font-size:14px;">
                The Sit-in Management System is set up and ready. Continue building your modules — students, sit-in records, sessions, and reporting features can be added here.
            </p>
        </div>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            const options = { weekday:'long', year:'numeric', month:'long', day:'numeric', hour:'2-digit', minute:'2-digit', second:'2-digit' };
            document.getElementById('datetime').textContent = now.toLocaleDateString('en-PH', options);
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>
</html>