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
            --uc-light: #F8F4EC;
            --glass: rgba(255, 255, 255, 0.04);
            --glass-border: rgba(245, 166, 35, 0.2);
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--uc-darker);
            color: #fff;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ─── BACKGROUND ─── */
        .bg-canvas {
            position: fixed; inset: 0; z-index: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(13,27,62,0.8) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(200,130,10,0.15) 0%, transparent 50%),
                        radial-gradient(ellipse at 60% 80%, rgba(13,27,62,0.6) 0%, transparent 50%),
                        linear-gradient(135deg, #060810 0%, #0A0E1A 50%, #0D1B3E 100%);
        }

        .grid-overlay {
            position: fixed; inset: 0; z-index: 0;
            background-image:
                linear-gradient(rgba(245,166,35,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(245,166,35,0.04) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            0% { transform: translateY(0); }
            100% { transform: translateY(60px); }
        }

        .floating-orb {
            position: fixed; border-radius: 50%; filter: blur(80px); z-index: 0;
            animation: floatOrb 8s ease-in-out infinite;
        }
        .orb-1 { width: 400px; height: 400px; background: rgba(245,166,35,0.08); top: -100px; right: 10%; }
        .orb-2 { width: 300px; height: 300px; background: rgba(13,27,62,0.6); bottom: 10%; left: -50px; animation-delay: 3s; }
        .orb-3 { width: 200px; height: 200px; background: rgba(245,166,35,0.05); top: 50%; left: 50%; animation-delay: 5s; }

        @keyframes floatOrb {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        /* ─── NAVBAR ─── */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 40px;
            height: 80px;
            background: rgba(6, 8, 16, 0.7);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(6, 8, 16, 0.95);
            box-shadow: 0 4px 30px rgba(245,166,35,0.1);
        }

        .nav-brand {
            display: flex; align-items: center; gap: 14px; text-decoration: none;
        }

        /* ─── KEY LOGO FIX ─── */
        .nav-logo {
            width: 56px; height: 56px;
            border-radius: 50%;
            border: 2.5px solid var(--uc-gold);
            padding: 5px;                   /* breathing room */
            background: #ffffff;            /* white bg = logo colors show properly */
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            transition: all 0.3s ease;
            box-shadow: 0 0 20px rgba(245,166,35,0.25);
        }

        .nav-logo:hover {
            box-shadow: 0 0 32px rgba(245,166,35,0.55);
            transform: scale(1.06);
        }

        .nav-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;            /* full logo, no cropping */
            display: block;
        }

        .nav-logo-fallback {
            font-family: 'Space Mono', monospace;
            font-weight: 700; font-size: 18px;
            color: var(--uc-gold);
            display: none;
        }

        .nav-text { display: flex; flex-direction: column; }

        .nav-university {
            font-family: 'Playfair Display', serif;
            font-size: 14px; font-weight: 700;
            color: var(--uc-gold);
            letter-spacing: 0.5px; line-height: 1.2;
        }

        .nav-system {
            font-family: 'DM Sans', sans-serif;
            font-size: 11px; font-weight: 400;
            color: rgba(255,255,255,0.5);
            letter-spacing: 2px; text-transform: uppercase;
        }

        .nav-actions { display: flex; align-items: center; gap: 12px; }

        .btn-nav {
            display: flex; align-items: center; gap: 8px;
            padding: 10px 22px; border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px; font-weight: 600;
            text-decoration: none; cursor: pointer;
            border: none; transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .btn-nav-ghost {
            background: transparent;
            border: 1.5px solid var(--glass-border);
            color: rgba(255,255,255,0.8);
        }

        .btn-nav-ghost:hover {
            border-color: var(--uc-gold);
            color: var(--uc-gold);
            background: rgba(245,166,35,0.05);
            transform: translateY(-1px);
        }

        .btn-nav-gold {
            background: linear-gradient(135deg, var(--uc-gold), var(--uc-deep-gold));
            color: var(--uc-darker);
            box-shadow: 0 4px 15px rgba(245,166,35,0.3);
        }

        .btn-nav-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245,166,35,0.5);
        }

        /* ─── HERO ─── */
        .hero {
            position: relative; z-index: 1;
            min-height: 100vh;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 120px 40px 60px;
            text-align: center;
        }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 20px;
            background: rgba(245,166,35,0.1);
            border: 1px solid rgba(245,166,35,0.3);
            border-radius: 50px;
            font-family: 'Space Mono', monospace;
            font-size: 11px; letter-spacing: 3px;
            color: var(--uc-gold); text-transform: uppercase;
            margin-bottom: 32px;
            animation: fadeSlideDown 0.8s ease forwards;
        }

        .badge-dot {
            width: 6px; height: 6px;
            background: var(--uc-gold); border-radius: 50%;
            animation: pulseDot 1.5s ease infinite;
        }

        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.5); }
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(42px, 7vw, 82px);
            font-weight: 900; line-height: 1.1;
            margin-bottom: 20px;
            animation: fadeSlideDown 0.8s 0.2s ease both;
        }

        .hero-title .line-gold {
            display: block;
            background: linear-gradient(135deg, var(--uc-gold), var(--uc-accent), var(--uc-gold));
            background-size: 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s ease infinite;
        }

        @keyframes shimmer {
            0%, 100% { background-position: 0%; }
            50% { background-position: 100%; }
        }

        .hero-subtitle {
            font-size: 16px; font-weight: 300;
            color: rgba(255,255,255,0.5);
            max-width: 500px; margin: 0 auto 48px;
            line-height: 1.7; letter-spacing: 0.3px;
            animation: fadeSlideDown 0.8s 0.4s ease both;
        }

        .hero-cta {
            display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;
            animation: fadeSlideDown 0.8s 0.6s ease both;
        }

        .btn-hero {
            display: flex; align-items: center; gap: 10px;
            padding: 16px 36px; border-radius: 12px;
            font-size: 15px; font-weight: 600;
            text-decoration: none; transition: all 0.3s ease;
            border: none; cursor: pointer;
        }

        .btn-hero-primary {
            background: linear-gradient(135deg, var(--uc-gold), var(--uc-deep-gold));
            color: var(--uc-darker);
            box-shadow: 0 8px 30px rgba(245,166,35,0.4);
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(245,166,35,0.6);
        }

        .btn-hero-secondary {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            color: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
        }

        .btn-hero-secondary:hover {
            border-color: var(--uc-gold);
            color: var(--uc-gold);
            transform: translateY(-3px);
        }

        /* ─── STATS ─── */
        .stats-strip {
            position: relative; z-index: 1;
            display: flex; justify-content: center; flex-wrap: wrap;
            gap: 1px; padding: 0 40px 80px;
        }

        .stat-card {
            flex: 1; min-width: 160px; max-width: 220px;
            padding: 30px 24px;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            text-align: center; transition: all 0.3s ease;
        }

        .stat-card:first-child { border-radius: 16px 0 0 16px; }
        .stat-card:last-child { border-radius: 0 16px 16px 0; }

        .stat-card:hover {
            background: rgba(245,166,35,0.06);
            border-color: var(--uc-gold);
            transform: translateY(-4px);
        }

        .stat-icon { font-size: 24px; color: var(--uc-gold); margin-bottom: 10px; }

        .stat-num {
            font-family: 'Space Mono', monospace;
            font-size: 28px; font-weight: 700;
            color: #fff; line-height: 1;
        }

        .stat-label {
            font-size: 12px; color: rgba(255,255,255,0.4);
            text-transform: uppercase; letter-spacing: 1.5px; margin-top: 6px;
        }

        /* ─── FEATURES ─── */
        .features {
            position: relative; z-index: 1;
            padding: 80px 40px;
            max-width: 1100px; margin: 0 auto;
        }

        .section-label {
            font-family: 'Space Mono', monospace;
            font-size: 11px; letter-spacing: 4px;
            color: var(--uc-gold); text-transform: uppercase;
            text-align: center; margin-bottom: 16px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 4vw, 44px);
            font-weight: 700; text-align: center;
            margin-bottom: 60px; color: #fff;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .feature-card {
            padding: 32px;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            transition: all 0.4s ease;
            position: relative; overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute; top: 0; left: 0;
            width: 100%; height: 3px;
            background: linear-gradient(90deg, transparent, var(--uc-gold), transparent);
            transform: scaleX(0); transition: transform 0.4s ease;
        }

        .feature-card:hover::before { transform: scaleX(1); }
        .feature-card:hover {
            transform: translateY(-6px);
            border-color: rgba(245,166,35,0.4);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        .feature-icon {
            width: 54px; height: 54px;
            background: linear-gradient(135deg, rgba(245,166,35,0.15), rgba(245,166,35,0.05));
            border: 1px solid rgba(245,166,35,0.2);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; color: var(--uc-gold);
            margin-bottom: 20px;
        }

        .feature-title {
            font-family: 'Playfair Display', serif;
            font-size: 18px; font-weight: 700;
            color: #fff; margin-bottom: 10px;
        }

        .feature-desc { font-size: 14px; color: rgba(255,255,255,0.45); line-height: 1.7; }

        /* ─── FOOTER ─── */
        .footer {
            position: relative; z-index: 1;
            padding: 40px; border-top: 1px solid var(--glass-border);
            text-align: center;
        }

        .footer-text { font-size: 13px; color: rgba(255,255,255,0.3); }
        .footer-text span { color: var(--uc-gold); }

        @keyframes fadeSlideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ─── MODALS ─── */
        .modal-overlay {
            position: fixed; inset: 0; z-index: 200;
            background: rgba(6, 8, 16, 0.95);
            backdrop-filter: blur(10px);
            display: none; align-items: center; justify-content: center;
            padding: 16px; overflow-y: auto;
        }

        .modal-overlay.active { display: flex; }

        .modal-card {
            width: 100%; max-width: 460px;
            background: linear-gradient(145deg, rgba(13,27,62,0.95), rgba(10,14,26,0.95));
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 36px 36px 32px;
            position: relative;
            box-shadow: 0 40px 80px rgba(0,0,0,0.6), 0 0 60px rgba(245,166,35,0.05);
            animation: modalIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            max-height: calc(100vh - 32px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(245,166,35,0.3) transparent;
            margin: auto;
        }

        .modal-card::-webkit-scrollbar { width: 4px; }
        .modal-card::-webkit-scrollbar-track { background: transparent; }
        .modal-card::-webkit-scrollbar-thumb { background: rgba(245,166,35,0.3); border-radius: 4px; }

        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.9) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .modal-close {
            position: absolute; top: 20px; right: 20px;
            width: 36px; height: 36px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px; cursor: pointer;
            color: rgba(255,255,255,0.5);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; transition: all 0.2s ease;
        }

        .modal-close:hover { color: #fff; background: rgba(255,255,255,0.1); }

        .modal-header { text-align: center; margin-bottom: 24px; }

        .modal-icon {
            width: 52px; height: 52px; margin: 0 auto 12px;
            background: linear-gradient(135deg, rgba(245,166,35,0.2), rgba(245,166,35,0.05));
            border: 1px solid rgba(245,166,35,0.3);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; color: var(--uc-gold);
        }

        .modal-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px; font-weight: 700; color: #fff;
        }

        .modal-sub { font-size: 12px; color: rgba(255,255,255,0.4); margin-top: 4px; }

        .form-group { margin-bottom: 14px; }

        .form-label {
            display: block; font-size: 12px; font-weight: 600;
            letter-spacing: 1px; text-transform: uppercase;
            color: rgba(255,255,255,0.5); margin-bottom: 8px;
        }

        .form-input-wrap { position: relative; }

        .form-icon {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            color: rgba(255,255,255,0.3); font-size: 15px; pointer-events: none;
        }

        .form-input {
            width: 100%; padding: 12px 16px 12px 44px;
            background: rgba(255,255,255,0.04);
            border: 1.5px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px; color: #fff;
            transition: all 0.3s ease; outline: none;
        }

        .form-input::placeholder { color: rgba(255,255,255,0.2); }

        .form-input:focus {
            border-color: var(--uc-gold);
            background: rgba(245,166,35,0.04);
            box-shadow: 0 0 0 3px rgba(245,166,35,0.1);
        }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        .btn-submit {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, var(--uc-gold), var(--uc-deep-gold));
            border: none; border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px; font-weight: 700;
            color: var(--uc-darker); cursor: pointer;
            transition: all 0.3s ease; margin-top: 6px;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            box-shadow: 0 8px 25px rgba(245,166,35,0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(245,166,35,0.5);
        }

        .form-divider {
            text-align: center; margin: 14px 0;
            font-size: 12px; color: rgba(255,255,255,0.2);
            position: relative;
        }

        .form-divider::before, .form-divider::after {
            content: '';
            position: absolute; top: 50%;
            width: 38%; height: 1px;
            background: rgba(255,255,255,0.08);
        }

        .form-divider::before { left: 0; }
        .form-divider::after { right: 0; }

        .form-switch {
            text-align: center; font-size: 13px;
            color: rgba(255,255,255,0.4); margin-top: 14px;
        }

        .form-switch a { color: var(--uc-gold); text-decoration: none; font-weight: 600; }
        .form-switch a:hover { text-decoration: underline; }

        .alert {
            padding: 12px 16px; border-radius: 10px;
            font-size: 13px; margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
        }

        .alert-error {
            background: rgba(220,38,38,0.1);
            border: 1px solid rgba(220,38,38,0.3);
            color: #F87171;
        }

        .alert-success {
            background: rgba(16,185,129,0.1);
            border: 1px solid rgba(16,185,129,0.3);
            color: #34D399;
        }

        .password-toggle {
            position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: rgba(255,255,255,0.3); font-size: 15px;
            transition: color 0.2s;
        }

        .password-toggle:hover { color: var(--uc-gold); }

        .scroll-indicator {
            position: absolute; bottom: 40px; left: 50%;
            transform: translateX(-50%);
            display: flex; flex-direction: column; align-items: center; gap: 8px;
            color: rgba(255,255,255,0.3); font-size: 11px;
            letter-spacing: 2px; text-transform: uppercase;
            animation: bounce 2s ease infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(8px); }
        }

        @media (max-width: 640px) {
            .navbar { padding: 0 20px; }
            .nav-text { display: none; }
            .hero { padding: 100px 20px 40px; }
            .form-row { grid-template-columns: 1fr; }
            .modal-card { padding: 32px 24px; }
            .stat-card:first-child { border-radius: 16px 16px 0 0; }
            .stat-card:last-child { border-radius: 0 0 16px 16px; }
            .stats-strip { flex-direction: column; align-items: center; }
            .stat-card { max-width: 100%; width: 100%; }
        }
    </style>
</head>
<body>
    <div class="bg-canvas"></div>
    <div class="grid-overlay"></div>
    <div class="floating-orb orb-1"></div>
    <div class="floating-orb orb-2"></div>
    <div class="floating-orb orb-3"></div>

    <!-- ─── NAVBAR ─── -->
    <nav class="navbar" id="navbar">
        <a href="index.php" class="nav-brand">
            <div class="nav-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/68/University_of_Cebu_Logo.png"
                     alt="UC Logo"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="nav-logo-fallback">UC</div>
            </div>
            <div class="nav-text">
                <span class="nav-university">University of Cebu</span>
                <span class="nav-system">Sit-in Management System</span>
            </div>
        </a>
        <div class="nav-actions">
            <button class="btn-nav btn-nav-ghost" onclick="openModal('login')">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
            <button class="btn-nav btn-nav-gold" onclick="openModal('register')">
                <i class="fas fa-user-shield"></i> Register
            </button>
        </div>
    </nav>

    <!-- ─── HERO ─── -->
    <section class="hero">
        <div class="hero-badge">
            <div class="badge-dot"></div>
            Admin Portal Active
        </div>
        <h1 class="hero-title">
            Sit-in Management
            <span class="line-gold">System</span>
        </h1>
        <p class="hero-subtitle">
            A powerful, streamlined platform for managing student sit-in sessions at the University of Cebu. Secure, efficient, and designed for administrators.
        </p>
        <div class="hero-cta">
            <button class="btn-hero btn-hero-primary" onclick="openModal('login')">
                <i class="fas fa-shield-halved"></i> Admin Login
            </button>
            <button class="btn-hero btn-hero-secondary" onclick="openModal('register')">
                <i class="fas fa-user-plus"></i> Register Admin
            </button>
        </div>
        <div class="scroll-indicator">
            <span>Scroll</span>
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- ─── STATS ─── -->
    <div class="stats-strip">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-num">500+</div>
            <div class="stat-label">Students Managed</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-chair"></i></div>
            <div class="stat-num">200+</div>
            <div class="stat-label">Daily Sit-ins</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-num">24/7</div>
            <div class="stat-label">System Uptime</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-shield-halved"></i></div>
            <div class="stat-num">100%</div>
            <div class="stat-label">Secure Access</div>
        </div>
    </div>

    <!-- ─── FEATURES ─── -->
    <section class="features">
        <p class="section-label">What We Offer</p>
        <h2 class="section-title">Built for UC Administrators</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-user-clock"></i></div>
                <h3 class="feature-title">Real-time Sit-in Tracking</h3>
                <p class="feature-desc">Monitor and manage student sit-ins in real-time with instant updates and status tracking across all lab rooms.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-lock"></i></div>
                <h3 class="feature-title">Secure Admin Access</h3>
                <p class="feature-desc">Role-based authentication ensures only authorized personnel can access and manage system data.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                <h3 class="feature-title">Analytics & Reports</h3>
                <p class="feature-desc">Generate comprehensive reports and insights on sit-in trends, frequency, and student usage patterns.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-bell"></i></div>
                <h3 class="feature-title">Smart Notifications</h3>
                <p class="feature-desc">Automated alerts for session limits, rule violations, and administrative announcements in real-time.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-database"></i></div>
                <h3 class="feature-title">Student Records</h3>
                <p class="feature-desc">Centralized database for complete student sit-in history, remarks, and compliance records.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-mobile-screen-button"></i></div>
                <h3 class="feature-title">Responsive Interface</h3>
                <p class="feature-desc">Fully responsive design works seamlessly on desktop, tablet, and mobile devices for on-the-go management.</p>
            </div>
        </div>
    </section>

    <!-- ─── FOOTER ─── -->
    <footer class="footer">
        <p class="footer-text">
            &copy; 2024 <span>University of Cebu</span> — Sit-in Management System. All rights reserved.
        </p>
    </footer>

    <!-- ─── LOGIN MODAL ─── -->
    <div class="modal-overlay" id="loginModal">
        <div class="modal-card">
            <button class="modal-close" onclick="closeModal('login')"><i class="fas fa-times"></i></button>
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-shield-halved"></i></div>
                <h2 class="modal-title">Admin Login</h2>
                <p class="modal-sub">Sign in to your admin account</p>
            </div>
            <div id="loginAlert"></div>
            <form id="loginForm" action="auth/login.php" method="POST">
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
                        <button type="button" class="password-toggle" onclick="togglePass('loginPass', this)">
                            <i class="fas fa-eye"></i>
                        </button>
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
        <div class="modal-card" style="max-width: 520px;">
            <button class="modal-close" onclick="closeModal('register')"><i class="fas fa-times"></i></button>
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-user-shield"></i></div>
                <h2 class="modal-title">Admin Registration</h2>
                <p class="modal-sub">Create a new administrator account</p>
            </div>
            <div id="registerAlert"></div>
            <form id="registerForm" action="auth/register.php" method="POST">
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
        window.addEventListener('scroll', () => {
            document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 20);
        });

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

        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        });

        function togglePass(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        const params = new URLSearchParams(window.location.search);
        if (params.get('error')) {
            const type = params.get('type') || 'login';
            showAlert(type, 'error', decodeURIComponent(params.get('error')));
            openModal(type);
        }
        if (params.get('success')) {
            const type = params.get('type') || 'login';
            showAlert(type, 'success', decodeURIComponent(params.get('success')));
            openModal(type);
        }

        function showAlert(modal, alertType, msg) {
            const el = document.getElementById(modal + 'Alert');
            el.innerHTML = `<div class="alert alert-${alertType}"><i class="fas fa-${alertType === 'error' ? 'circle-exclamation' : 'circle-check'}"></i>${msg}</div>`;
        }

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.feature-card, .stat-card').forEach((el, i) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = `all 0.5s ease ${i * 0.08}s`;
            observer.observe(el);
        });
    </script>
</body>
</html>