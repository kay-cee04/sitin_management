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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --uc-blue:      #003087;
            --uc-blue-mid:  #0047B3;
            --uc-blue-light:#E8EEF9;
            --uc-gold:      #F5A800;
            --uc-gold-dark: #C88A00;
            --uc-white:     #FFFFFF;
            --uc-off:       #F4F6FB;
            --uc-text:      #1A2640;
            --uc-muted:     #6B7A99;
            --uc-border:    #D6DFF0;
            --sidebar-w:    256px;
            --radius:       12px;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--uc-off);
            color: var(--uc-text);
            min-height: 100vh;
            display: flex;
        }

        /* ─── SIDEBAR ─── */
        .sidebar {
            position: fixed; left: 0; top: 0; bottom: 0;
            width: var(--sidebar-w); z-index: 50;
            background: var(--uc-blue);
            display: flex; flex-direction: column;
            box-shadow: 4px 0 20px rgba(0,48,135,0.15);
        }

        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: center; gap: 12px;
        }

        .sidebar-logo {
            width: 48px; height: 48px; border-radius: 50%;
            border: 2px solid var(--uc-gold);
            background: #fff; padding: 4px;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden; flex-shrink: 0;
            box-shadow: 0 2px 10px rgba(245,168,0,0.3);
        }

        .sidebar-logo img {
            width: 100%; height: 100%;
            object-fit: contain; display: block;
        }

        .sidebar-name {
            font-family: 'Playfair Display', serif;
            font-size: 13px; font-weight: 700;
            color: #fff; line-height: 1.3;
        }

        .sidebar-system {
            font-size: 10px; color: rgba(255,255,255,0.5);
            letter-spacing: 1.5px; text-transform: uppercase;
            margin-top: 2px;
        }

        .sidebar-nav { flex: 1; padding: 16px 0; overflow-y: auto; }

        .nav-section-label {
            padding: 10px 20px 4px;
            font-size: 10px; letter-spacing: 2px; text-transform: uppercase;
            color: rgba(255,255,255,0.35); font-weight: 600;
        }

        .nav-item {
            display: flex; align-items: center; gap: 11px;
            padding: 11px 20px; margin: 2px 10px; border-radius: 9px;
            color: rgba(255,255,255,0.65);
            text-decoration: none; font-size: 13.5px; font-weight: 500;
            transition: all 0.2s ease; cursor: pointer; border: none;
            background: transparent; width: calc(100% - 20px); text-align: left;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        .nav-item.active {
            background: var(--uc-gold);
            color: var(--uc-blue);
            font-weight: 700;
        }

        .nav-item i { width: 17px; text-align: center; font-size: 14px; }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .admin-profile {
            display: flex; align-items: center; gap: 11px;
            padding: 12px; border-radius: 10px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 10px;
        }

        .admin-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--uc-gold);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700;
            color: var(--uc-blue); flex-shrink: 0;
        }

        .admin-name { font-size: 13px; font-weight: 600; color: #fff; }
        .admin-role { font-size: 11px; color: rgba(255,255,255,0.5); text-transform: capitalize; margin-top: 1px; }

        .btn-logout {
            width: 100%; padding: 10px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 9px; color: rgba(255,255,255,0.7);
            font-size: 13px; font-weight: 600; cursor: pointer;
            transition: all 0.2s ease;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            text-decoration: none;
        }

        .btn-logout:hover {
            background: rgba(239,68,68,0.2);
            border-color: rgba(239,68,68,0.4);
            color: #FCA5A5;
        }

        /* ─── MAIN ─── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1; padding: 36px;
            min-height: 100vh;
        }

        .topbar {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 28px;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 28px; font-weight: 700;
            color: var(--uc-text);
        }

        .page-time {
            font-size: 13px; color: var(--uc-muted);
            background: var(--uc-white);
            border: 1px solid var(--uc-border);
            padding: 8px 16px; border-radius: 8px;
        }

        /* ─── WELCOME BANNER ─── */
        .welcome-banner {
            padding: 28px 32px;
            background: linear-gradient(135deg, var(--uc-blue) 0%, var(--uc-blue-mid) 100%);
            border-radius: var(--radius);
            margin-bottom: 28px;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 16px;
            box-shadow: 0 4px 20px rgba(0,48,135,0.2);
        }

        .welcome-text h2 {
            font-family: 'Playfair Display', serif;
            font-size: 22px; font-weight: 700;
            color: #fff; margin-bottom: 4px;
        }

        .welcome-text p { font-size: 14px; color: rgba(255,255,255,0.65); }

        .welcome-badge {
            padding: 8px 18px;
            background: var(--uc-gold);
            border-radius: 50px;
            font-size: 12px; font-weight: 700;
            color: var(--uc-blue);
            letter-spacing: 1px; text-transform: uppercase;
        }

        /* ─── STAT CARDS ─── */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 18px; margin-bottom: 28px;
        }

        .card {
            background: var(--uc-white);
            border: 1px solid var(--uc-border);
            border-radius: var(--radius);
            padding: 24px;
            transition: all 0.25s ease;
        }

        .card:hover {
            border-color: var(--uc-blue);
            box-shadow: 0 6px 24px rgba(0,48,135,0.1);
            transform: translateY(-3px);
        }

        .card-icon {
            width: 44px; height: 44px; border-radius: 11px;
            background: var(--uc-blue-light);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: var(--uc-blue);
            margin-bottom: 14px;
        }

        .card-value {
            font-family: 'Playfair Display', serif;
            font-size: 30px; font-weight: 700;
            color: var(--uc-text); margin-bottom: 3px;
        }

        .card-label {
            font-size: 12px; color: var(--uc-muted);
            text-transform: uppercase; letter-spacing: 1px; font-weight: 600;
        }

        .card-change {
            font-size: 12px; margin-top: 8px;
            color: #16A34A; font-weight: 500;
        }

        /* Gold accent on top of stat cards */
        .card-gold { border-top: 3px solid var(--uc-gold); }

        /* ─── READY CARD ─── */
        .ready-card {
            background: var(--uc-white);
            border: 1px solid var(--uc-border);
            border-radius: var(--radius);
            padding: 60px 40px;
            text-align: center;
        }

        .ready-icon {
            width: 64px; height: 64px;
            background: var(--uc-blue-light);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; color: var(--uc-blue);
            margin: 0 auto 18px;
        }

        .ready-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px; font-weight: 700;
            color: var(--uc-text); margin-bottom: 10px;
        }

        .ready-desc { font-size: 14px; color: var(--uc-muted); line-height: 1.65; max-width: 440px; margin: 0 auto; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; padding: 20px; }
        }
    </style>
</head>
<body>

    <!-- ─── SIDEBAR ─── -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/68/University_of_Cebu_Logo.png"
                     alt="UC Logo"
                     onerror="this.parentElement.innerHTML='<span style=\'color:var(--uc-blue);font-weight:700;\'>UC</span>'">
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
            <div class="nav-section-label" style="margin-top:12px;">Reports</div>
            <a class="nav-item" href="#">
                <i class="fas fa-chart-bar"></i> Analytics
            </a>
            <a class="nav-item" href="#">
                <i class="fas fa-file-lines"></i> Generate Report
            </a>
            <div class="nav-section-label" style="margin-top:12px;">Admin</div>
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
                <div>
                    <div class="admin-name"><?= htmlspecialchars($_SESSION['admin_name']) ?></div>
                    <div class="admin-role"><?= htmlspecialchars($_SESSION['admin_role']) ?></div>
                </div>
            </div>
            <a href="auth/logout.php" class="btn-logout">
                <i class="fas fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <!-- ─── MAIN ─── -->
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
            <div class="card card-gold">
                <div class="card-icon"><i class="fas fa-users"></i></div>
                <div class="card-value">0</div>
                <div class="card-label">Total Students</div>
                <div class="card-change"><i class="fas fa-plus"></i> No records yet</div>
            </div>
            <div class="card card-gold">
                <div class="card-icon"><i class="fas fa-chair"></i></div>
                <div class="card-value">0</div>
                <div class="card-label">Active Sit-ins</div>
                <div class="card-change" style="color:var(--uc-muted);">None active</div>
            </div>
            <div class="card card-gold">
                <div class="card-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="card-value">0</div>
                <div class="card-label">Today's Sessions</div>
                <div class="card-change" style="color:var(--uc-muted);">No sessions today</div>
            </div>
            <div class="card card-gold">
                <div class="card-icon"><i class="fas fa-user-shield"></i></div>
                <div class="card-value">1</div>
                <div class="card-label">Admins Online</div>
                <div class="card-change"><i class="fas fa-circle" style="font-size:8px;"></i> You're active</div>
            </div>
        </div>

        <div class="ready-card">
            <div class="ready-icon"><i class="fas fa-check"></i></div>
            <h3 class="ready-title">System Ready</h3>
            <p class="ready-desc">The Sit-in Management System is set up and ready. Continue building your modules — students, sit-in records, sessions, and reporting features can be added here.</p>
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