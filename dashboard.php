<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sit-in Management System | University of Cebu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --uc-blue:      #003087;   /* UC deep blue */
            --uc-blue-mid:  #0047B3;   /* medium blue */
            --uc-blue-light:#E8EEF9;   /* very light blue tint */
            --uc-gold:      #F5A800;   /* UC gold */
            --uc-gold-dark: #C88A00;   /* deeper gold */
            --uc-white:     #FFFFFF;
            --uc-off:       #F4F6FB;   /* off-white page bg */
            --uc-text:      #1A2640;   /* dark blue-gray text */
            --uc-muted:     #6B7A99;   /* muted text */
            --uc-border:    #D6DFF0;   /* subtle border */
            --radius:       14px;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--uc-off);
            color: var(--uc-text);
            min-height: 100vh;
        }

        /* ─── NAVBAR ─── */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 48px; height: 72px;
            background: var(--uc-white);
            border-bottom: 1px solid var(--uc-border);
            box-shadow: 0 2px 16px rgba(0,48,135,0.07);
            transition: all 0.3s ease;
        }

        .nav-brand {
            display: flex; align-items: center; gap: 14px; text-decoration: none;
        }

        .nav-logo {
            width: 50px; height: 50px; border-radius: 50%;
            border: 2.5px solid var(--uc-gold);
            background: #fff;
            padding: 4px;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden; flex-shrink: 0;
            box-shadow: 0 2px 10px rgba(245,168,0,0.2);
            transition: transform 0.3s ease;
        }

        .nav-logo:hover { transform: scale(1.06); }

        .nav-logo img {
            width: 100%; height: 100%;
            object-fit: contain; display: block;
        }

        .nav-text { display: flex; flex-direction: column; }

        .nav-university {
            font-family: 'Playfair Display', serif;
            font-size: 15px; font-weight: 700;
            color: var(--uc-blue); line-height: 1.2;
        }

        .nav-system {
            font-size: 11px; font-weight: 500;
            color: var(--uc-muted);
            letter-spacing: 1.5px; text-transform: uppercase;
        }

        .nav-actions { display: flex; align-items: center; gap: 10px; }

        .btn-nav {
            display: flex; align-items: center; gap: 7px;
            padding: 9px 20px; border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 13px; font-weight: 600;
            cursor: pointer; border: none;
            transition: all 0.2s ease; text-decoration: none;
        }

        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--uc-blue);
            color: var(--uc-blue);
        }

        .btn-outline:hover {
            background: var(--uc-blue-light);
        }

        .btn-solid {
            background: var(--uc-blue);
            color: var(--uc-white);
            box-shadow: 0 3px 12px rgba(0,48,135,0.25);
        }

        .btn-solid:hover {
            background: var(--uc-blue-mid);
            box-shadow: 0 5px 18px rgba(0,48,135,0.35);
            transform: translateY(-1px);
        }

        /* ─── HERO ─── */
        .hero {
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 100px 48px 60px;
            background: linear-gradient(160deg, #fff 55%, var(--uc-blue-light) 100%);
            position: relative; overflow: hidden;
        }

        /* Decorative circle accents */
        .hero::before {
            content: '';
            position: absolute; top: -120px; right: -120px;
            width: 500px; height: 500px; border-radius: 50%;
            background: rgba(0,48,135,0.04);
            pointer-events: none;
        }

        .hero::after {
            content: '';
            position: absolute; bottom: -80px; left: -80px;
            width: 350px; height: 350px; border-radius: 50%;
            background: rgba(245,168,0,0.06);
            pointer-events: none;
        }

        .hero-inner {
            max-width: 680px; text-align: center; position: relative; z-index: 1;
        }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 16px;
            background: var(--uc-blue-light);
            border: 1px solid rgba(0,48,135,0.15);
            border-radius: 50px;
            font-size: 11px; font-weight: 600;
            color: var(--uc-blue);
            letter-spacing: 2px; text-transform: uppercase;
            margin-bottom: 28px;
        }

        .badge-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--uc-gold);
            animation: pulse 1.8s ease infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.4); }
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(38px, 6vw, 68px);
            font-weight: 700; line-height: 1.15;
            color: var(--uc-text);
            margin-bottom: 20px;
        }

        .hero-title .accent {
            color: var(--uc-blue);
            position: relative;
        }

        .hero-title .accent::after {
            content: '';
            position: absolute; bottom: 2px; left: 0; right: 0;
            height: 4px; border-radius: 2px;
            background: var(--uc-gold);
        }

        .hero-sub {
            font-size: 16px; font-weight: 400;
            color: var(--uc-muted);
            line-height: 1.7; max-width: 480px;
            margin: 0 auto 40px;
        }

        .hero-cta {
            display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;
        }

        .btn-cta {
            display: flex; align-items: center; gap: 9px;
            padding: 14px 32px; border-radius: var(--radius);
            font-size: 14px; font-weight: 600;
            cursor: pointer; border: none;
            text-decoration: none; transition: all 0.25s ease;
        }

        .btn-cta-primary {
            background: var(--uc-blue);
            color: #fff;
            box-shadow: 0 6px 20px rgba(0,48,135,0.3);
        }

        .btn-cta-primary:hover {
            background: var(--uc-blue-mid);
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(0,48,135,0.4);
        }

        .btn-cta-secondary {
            background: var(--uc-white);
            color: var(--uc-blue);
            border: 1.5px solid var(--uc-border);
        }

        .btn-cta-secondary:hover {
            border-color: var(--uc-blue);
            background: var(--uc-blue-light);
            transform: translateY(-2px);
        }

        /* ─── STATS ─── */
        .stats {
            background: var(--uc-blue);
            padding: 48px;
            display: flex; justify-content: center;
            flex-wrap: wrap; gap: 0;
        }

        .stat-item {
            flex: 1; min-width: 150px; max-width: 220px;
            text-align: center; padding: 20px 24px;
            border-right: 1px solid rgba(255,255,255,0.1);
        }

        .stat-item:last-child { border-right: none; }

        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 36px; font-weight: 700;
            color: var(--uc-gold); line-height: 1;
            margin-bottom: 6px;
        }

        .stat-label {
            font-size: 12px; font-weight: 500;
            color: rgba(255,255,255,0.6);
            letter-spacing: 1px; text-transform: uppercase;
        }

        /* ─── FEATURES ─── */
        .features {
            padding: 80px 48px;
            max-width: 1100px; margin: 0 auto;
        }

        .section-tag {
            font-size: 11px; font-weight: 600;
            color: var(--uc-gold-dark); letter-spacing: 3px;
            text-transform: uppercase; text-align: center;
            margin-bottom: 10px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(26px, 3.5vw, 38px);
            font-weight: 700; text-align: center;
            color: var(--uc-text); margin-bottom: 50px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .feature-card {
            background: var(--uc-white);
            border: 1px solid var(--uc-border);
            border-radius: var(--radius);
            padding: 28px;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            border-color: var(--uc-blue);
            box-shadow: 0 8px 30px rgba(0,48,135,0.1);
            transform: translateY(-4px);
        }

        .feature-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: var(--uc-blue-light);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: var(--uc-blue);
            margin-bottom: 16px;
        }

        .feature-title {
            font-size: 16px; font-weight: 700;
            color: var(--uc-text); margin-bottom: 8px;
        }

        .feature-desc {
            font-size: 14px; color: var(--uc-muted);
            line-height: 1.65;
        }

        /* ─── FOOTER ─── */
        .footer {
            background: var(--uc-white);
            border-top: 1px solid var(--uc-border);
            padding: 28px 48px;
            text-align: center;
            font-size: 13px; color: var(--uc-muted);
        }

        .footer span { color: var(--uc-blue); font-weight: 600; }

        /* ─── MODAL ─── */
        .modal-overlay {
            position: fixed; inset: 0; z-index: 200;
            background: rgba(0,20,60,0.55);
            backdrop-filter: blur(6px);
            display: none; align-items: center; justify-content: center;
            padding: 16px; overflow-y: auto;
        }

        .modal-overlay.active { display: flex; }

        .modal-card {
            width: 100%; max-width: 440px;
            background: var(--uc-white);
            border-radius: 20px;
            padding: 40px 36px 36px;
            position: relative;
            box-shadow: 0 24px 60px rgba(0,30,100,0.18);
            animation: modalIn 0.35s cubic-bezier(0.34,1.56,0.64,1) forwards;
            max-height: calc(100vh - 32px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--uc-border) transparent;
            margin: auto;
        }

        .modal-card::-webkit-scrollbar { width: 4px; }
        .modal-card::-webkit-scrollbar-thumb { background: var(--uc-border); border-radius: 4px; }

        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.93) translateY(16px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .modal-close {
            position: absolute; top: 16px; right: 16px;
            width: 32px; height: 32px;
            background: var(--uc-off); border: 1px solid var(--uc-border);
            border-radius: 8px; cursor: pointer;
            color: var(--uc-muted);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; transition: all 0.2s;
        }

        .modal-close:hover { background: var(--uc-blue-light); color: var(--uc-blue); }

        /* Logo inside modal header */
        .modal-logo {
            width: 60px; height: 60px; border-radius: 50%;
            border: 2.5px solid var(--uc-gold);
            background: #fff;
            padding: 5px;
            margin: 0 auto 14px;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(245,168,0,0.2);
        }

        .modal-logo img { width: 100%; height: 100%; object-fit: contain; display: block; }

        .modal-header { text-align: center; margin-bottom: 28px; }

        .modal-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px; font-weight: 700;
            color: var(--uc-text); margin-bottom: 4px;
        }

        .modal-sub { font-size: 13px; color: var(--uc-muted); }

        /* ─── FORM ─── */
        .form-group { margin-bottom: 14px; }

        .form-label {
            display: block; font-size: 12px; font-weight: 600;
            letter-spacing: 0.8px; text-transform: uppercase;
            color: var(--uc-muted); margin-bottom: 7px;
        }

        .form-input-wrap { position: relative; }

        .form-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: var(--uc-muted); font-size: 14px; pointer-events: none;
        }

        .form-input {
            width: 100%; padding: 11px 14px 11px 42px;
            background: var(--uc-off);
            border: 1.5px solid var(--uc-border);
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 14px; color: var(--uc-text);
            transition: all 0.2s ease; outline: none;
        }

        .form-input::placeholder { color: #B0BAD0; }

        .form-input:focus {
            border-color: var(--uc-blue);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(0,48,135,0.1);
        }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        .btn-submit {
            width: 100%; padding: 13px;
            background: var(--uc-blue);
            border: none; border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 14px; font-weight: 700;
            color: #fff; cursor: pointer;
            transition: all 0.25s ease; margin-top: 8px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            box-shadow: 0 4px 16px rgba(0,48,135,0.25);
        }

        .btn-submit:hover {
            background: var(--uc-blue-mid);
            transform: translateY(-1px);
            box-shadow: 0 8px 22px rgba(0,48,135,0.35);
        }

        .form-divider {
            text-align: center; margin: 16px 0;
            font-size: 12px; color: #B0BAD0;
            position: relative;
        }

        .form-divider::before, .form-divider::after {
            content: ''; position: absolute; top: 50%;
            width: 40%; height: 1px;
            background: var(--uc-border);
        }

        .form-divider::before { left: 0; }
        .form-divider::after { right: 0; }

        .form-switch {
            text-align: center; font-size: 13px;
            color: var(--uc-muted); margin-top: 14px;
        }

        .form-switch a {
            color: var(--uc-blue); text-decoration: none; font-weight: 600;
        }

        .form-switch a:hover { text-decoration: underline; }

        .alert {
            padding: 11px 14px; border-radius: 10px;
            font-size: 13px; margin-bottom: 16px;
            display: flex; align-items: center; gap: 9px;
        }

        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #DC2626;
        }

        .alert-success {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            color: #16A34A;
        }

        .password-toggle {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: var(--uc-muted); font-size: 14px; transition: color 0.2s;
        }

        .password-toggle:hover { color: var(--uc-blue); }

        /* Gold accent bar at top of modal */
        .modal-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--uc-blue), var(--uc-gold));
            border-radius: 20px 20px 0 0;
        }

        @media (max-width: 640px) {
            .navbar { padding: 0 20px; }
            .nav-text { display: none; }
            .hero { padding: 90px 20px 40px; }
            .form-row { grid-template-columns: 1fr; }
            .modal-card { padding: 32px 20px 28px; }
            .stats { padding: 32px 20px; }
            .features { padding: 60px 20px; }
            .stat-item { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.1); }
            .stat-item:last-child { border-bottom: none; }
        }
    </style>
</head>
<body>

    <!-- ─── NAVBAR ─── -->
    <nav class="navbar">
        <a href="index.php" class="nav-brand">
            <div class="nav-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/68/University_of_Cebu_Logo.png"
                     alt="UC Logo"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <span style="display:none; font-weight:700; color:var(--uc-blue); font-size:16px;">UC</span>
            </div>
            <div class="nav-text">
                <span class="nav-university">University of Cebu</span>
                <span class="nav-system">Sit-in Management System</span>
            </div>
        </a>
        <div class="nav-actions">
            <button class="btn-nav btn-outline" onclick="openModal('login')">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
            <button class="btn-nav btn-solid" onclick="openModal('register')">
                <i class="fas fa-user-shield"></i> Register
            </button>
        </div>
    </nav>

    <!-- ─── HERO ─── -->
    <section class="hero">
        <div class="hero-inner">
            <div class="hero-badge">
                <div class="badge-dot"></div>
                Admin Portal
            </div>
            <h1 class="hero-title">
                Sit-in Management<br>
                <span class="accent">System</span>
            </h1>
            <p class="hero-sub">
                A streamlined platform for managing student sit-in sessions at the University of Cebu. Secure, efficient, and built for administrators.
            </p>
            <div class="hero-cta">
                <button class="btn-cta btn-cta-primary" onclick="openModal('login')">
                    <i class="fas fa-shield-halved"></i> Admin Login
                </button>
                <button class="btn-cta btn-cta-secondary" onclick="openModal('register')">
                    <i class="fas fa-user-plus"></i> Register Admin
                </button>
            </div>
        </div>
    </section>

    <!-- ─── STATS ─── -->
    <div class="stats">
        <div class="stat-item">
            <div class="stat-num">500+</div>
            <div class="stat-label">Students Managed</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">200+</div>
            <div class="stat-label">Daily Sit-ins</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">24/7</div>
            <div class="stat-label">System Uptime</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">100%</div>
            <div class="stat-label">Secure Access</div>
        </div>
    </div>

    <!-- ─── FEATURES ─── -->
    <section class="features">
        <p class="section-tag">What We Offer</p>
        <h2 class="section-title">Built for UC Administrators</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-user-clock"></i></div>
                <h3 class="feature-title">Real-time Sit-in Tracking</h3>
                <p class="feature-desc">Monitor and manage student sit-ins in real-time with instant updates across all lab rooms.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-lock"></i></div>
                <h3 class="feature-title">Secure Admin Access</h3>
                <p class="feature-desc">Role-based authentication ensures only authorized personnel can access and manage system data.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                <h3 class="feature-title">Analytics & Reports</h3>
                <p class="feature-desc">Generate comprehensive reports and insights on sit-in trends and student usage patterns.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-bell"></i></div>
                <h3 class="feature-title">Smart Notifications</h3>
                <p class="feature-desc">Automated alerts for session limits, violations, and administrative announcements.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-database"></i></div>
                <h3 class="feature-title">Student Records</h3>
                <p class="feature-desc">Centralized database for complete student sit-in history, remarks, and compliance records.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-mobile-screen-button"></i></div>
                <h3 class="feature-title">Responsive Interface</h3>
                <p class="feature-desc">Fully responsive design works seamlessly on desktop, tablet, and mobile devices.</p>
            </div>
        </div>
    </section>

    <!-- ─── FOOTER ─── -->
    <footer class="footer">
        &copy; 2024 <span>University of Cebu</span> — Sit-in Management System. All rights reserved.
    </footer>

    <!-- ─── LOGIN MODAL ─── -->
    <div class="modal-overlay" id="loginModal">
        <div class="modal-card">
            <button class="modal-close" onclick="closeModal('login')"><i class="fas fa-times"></i></button>
            <div class="modal-header">
                <div class="modal-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/6/68/University_of_Cebu_Logo.png" alt="UC">
                </div>
                <h2 class="modal-title">Admin Login</h2>
                <p class="modal-sub">Sign in to your admin account</p>
            </div>
            <div id="loginAlert"></div>
            <form action="auth/login.php" method="POST">
                <div class="form-group">
                    <label class="form-label">Username or Email</label>
                    <div class="form-input-wrap">
                        <i class="fas fa-user form-icon"></i>
                        <input type="text" class="form-input" name="username" placeholder="Enter username or email" required autocomplete="username">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="form-input-wrap">
                        <i class="fas fa-lock form-icon"></i>
                        <input type="password" class="form-input" name="password" id="loginPass" placeholder="Enter your password" required autocomplete="current-password">
                        <button type="button" class="password-toggle" onclick="togglePass('loginPass', this)"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-sign-in-alt"></i> Sign In to Dashboard
                </button>
            </form>
            <div class="form-divider">or</div>
            <div class="form-switch">
                Don't have an account? <a href="javascript:void(0)" onclick="switchModal('login','register')">Register here</a>
            </div>
        </div>
    </div>

    <!-- ─── REGISTER MODAL ─── -->
    <div class="modal-overlay" id="registerModal">
        <div class="modal-card" style="max-width:500px;">
            <button class="modal-close" onclick="closeModal('register')"><i class="fas fa-times"></i></button>
            <div class="modal-header">
                <div class="modal-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/6/68/University_of_Cebu_Logo.png" alt="UC">
                </div>
                <h2 class="modal-title">Admin Registration</h2>
                <p class="modal-sub">Create a new administrator account</p>
            </div>
            <div id="registerAlert"></div>
            <form action="auth/register.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <div class="form-input-wrap">
                            <i class="fas fa-user form-icon"></i>
                            <input type="text" class="form-input" name="first_name" placeholder="Juan" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <div class="form-input-wrap">
                            <i class="fas fa-user form-icon"></i>
                            <input type="text" class="form-input" name="last_name" placeholder="Dela Cruz" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="form-input-wrap">
                        <i class="fas fa-envelope form-icon"></i>
                        <input type="email" class="form-input" name="email" placeholder="admin@uc.edu.ph" required autocomplete="email">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <div class="form-input-wrap">
                        <i class="fas fa-at form-icon"></i>
                        <input type="text" class="form-input" name="username" placeholder="Choose a username" required autocomplete="username">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="form-input-wrap">
                            <i class="fas fa-lock form-icon"></i>
                            <input type="password" class="form-input" name="password" id="regPass" placeholder="••••••••" required autocomplete="new-password">
                            <button type="button" class="password-toggle" onclick="togglePass('regPass', this)"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <div class="form-input-wrap">
                            <i class="fas fa-lock form-icon"></i>
                            <input type="password" class="form-input" name="confirm_password" id="regConfirm" placeholder="••••••••" required autocomplete="new-password">
                            <button type="button" class="password-toggle" onclick="togglePass('regConfirm', this)"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Admin Secret Key</label>
                    <div class="form-input-wrap">
                        <i class="fas fa-key form-icon"></i>
                        <input type="password" class="form-input" name="secret_key" placeholder="Enter admin registration key" required>
                    </div>
                </div>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-user-plus"></i> Create Admin Account
                </button>
            </form>
            <div class="form-divider">or</div>
            <div class="form-switch">
                Already have an account? <a href="javascript:void(0)" onclick="switchModal('register','login')">Sign in here</a>
            </div>
        </div>
    </div>

    <script>
        function openModal(type) {
            document.getElementById(type + 'Modal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeModal(type) {
            document.getElementById(type + 'Modal').classList.remove('active');
            document.body.style.overflow = '';
        }
        function switchModal(from, to) {
            closeModal(from);
            setTimeout(() => openModal(to), 200);
        }
        document.querySelectorAll('.modal-overlay').forEach(o => {
            o.addEventListener('click', e => {
                if (e.target === o) { o.classList.remove('active'); document.body.style.overflow = ''; }
            });
        });
        function togglePass(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye'); icon.classList.toggle('fa-eye-slash');
        }
        const params = new URLSearchParams(window.location.search);
        if (params.get('error')) {
            showAlert(params.get('type')||'login','error', decodeURIComponent(params.get('error')));
            openModal(params.get('type')||'login');
            // Clear URL params so refreshing won't re-open the modal
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        if (params.get('success')) {
            showAlert(params.get('type')||'login','success', decodeURIComponent(params.get('success')));
            openModal(params.get('type')||'login');
            // Clear URL params so refreshing won't re-open the modal
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        function showAlert(modal, type, msg) {
            document.getElementById(modal+'Alert').innerHTML = `<div class="alert alert-${type}"><i class="fas fa-${type==='error'?'circle-exclamation':'circle-check'}"></i>${msg}</div>`;
        }

        // Animate feature cards on scroll
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) { entry.target.style.opacity='1'; entry.target.style.transform='translateY(0)'; }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.feature-card').forEach((el, i) => {
            el.style.opacity='0'; el.style.transform='translateY(24px)';
            el.style.transition=`all 0.45s ease ${i*0.07}s`;
            observer.observe(el);
        });
    </script>
</body>
</html>