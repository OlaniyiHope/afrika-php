<?php
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' | Afrika Scholar' : 'Afrika Scholar – Pan-African Academic Publishing' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --navy: #2D2B8F;
            --navy-dark: #1e1c6e;
            --orange: #E8651A;
            --orange-light: #f07230;
            --text-dark: #1a1a2e;
            --text-gray: #6b7280;
            --text-blue: #4B5FAB;
            --border: #e5e7eb;
            --bg-light: #f9fafb;
            --white: #ffffff;
        }
        body { font-family: 'Inter', sans-serif; color: var(--text-dark); background: #fff; }
        a { text-decoration: none; color: inherit; }
        img { max-width: 100%; }

        /* NAV */
        .navbar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 40px; height: 60px; background: #fff;
            border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 1000;
        }
        .logo { display: flex; align-items: center; gap: 0; font-size: 22px; font-weight: 800; }
        .logo span.blue { color: var(--navy); }
        .logo span.orange { color: var(--orange); }
        .nav-links { display: flex; align-items: center; gap: 28px; }
        .nav-links a { font-size: 14px; font-weight: 500; color: var(--text-dark); transition: color .2s; position: relative; }
        .nav-links a:hover, .nav-links a.active { color: var(--orange); }
        .nav-links .dropdown { position: relative; }
        .nav-links .dropdown > a { display: flex; align-items: center; gap: 4px; cursor: pointer; }
        .dropdown-menu {
            display: none; position: absolute; top: calc(100% + 16px); left: 0;
            background: #fff; border: 1px solid var(--border); border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0,0,0,.12); min-width: 260px; z-index: 999; padding: 8px 0;
        }
        .dropdown:hover .dropdown-menu { display: block; }
        .dropdown-menu a {
            display: block; padding: 10px 20px; font-size: 14px; color: var(--text-dark);
            transition: background .15s;
        }
        .dropdown-menu a:hover { background: var(--bg-light); }
        .dropdown-menu .dm-title { font-weight: 600; font-size: 14px; color: var(--text-dark); }
        .dropdown-menu .dm-sub { font-size: 12px; color: var(--text-gray); margin-top: 1px; }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 20px; border-radius: 7px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all .2s; border: 2px solid transparent; }
        .btn-outline { border-color: var(--border); color: var(--text-dark); background: #fff; }
        .btn-outline:hover { border-color: var(--navy); color: var(--navy); }
        .btn-orange { background: var(--orange); color: #fff; border-color: var(--orange); }
        .btn-orange:hover { background: var(--orange-light); border-color: var(--orange-light); }
        .btn-navy { background: var(--navy); color: #fff; border-color: var(--navy); }
        .btn-navy:hover { background: var(--navy-dark); }
        .btn-orange-outline { border-color: var(--orange); color: var(--orange); background: #fff; }
        .btn-orange-outline:hover { background: var(--orange); color: #fff; }
        .btn-white-outline { border-color: #fff; color: #fff; background: transparent; }
        .btn-white-outline:hover { background: #fff; color: var(--navy); }

        /* HERO */
        .hero {
            position: relative; background: var(--navy);
            min-height: 520px; display: flex; align-items: center;
            overflow: hidden;
        }
        .hero-bg {
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(45,43,143,.92) 50%, rgba(45,43,143,.7));
            z-index: 1;
        }
        .hero-img {
            position: absolute; inset: 0; width: 100%; height: 100%;
            object-fit: cover; opacity: .35;
        }
        .hero-pattern {
            position: absolute; inset: 0; z-index: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,.06) 1px, transparent 1px);
            background-size: 28px 28px;
        }
        .hero-content { position: relative; z-index: 2; padding: 60px 80px; max-width: 700px; }
        .hero-eyebrow { font-size: 12px; font-weight: 700; letter-spacing: 2px; color: var(--orange); text-transform: uppercase; margin-bottom: 16px; }
        .hero-title { font-size: 42px; font-weight: 800; color: #fff; line-height: 1.2; margin-bottom: 20px; }
        .hero-desc { font-size: 15px; color: rgba(255,255,255,.85); line-height: 1.7; margin-bottom: 32px; max-width: 560px; }
        .hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }
        .hero-full { min-height: 600px; }

        /* SECTIONS */
        .section { padding: 80px 80px; }
        .section-sm { padding: 60px 80px; }
        .section-center { text-align: center; }
        .section-eyebrow { font-size: 12px; font-weight: 700; letter-spacing: 2px; color: var(--orange); text-transform: uppercase; margin-bottom: 12px; }
        .section-title { font-size: 34px; font-weight: 800; color: var(--text-dark); line-height: 1.25; margin-bottom: 16px; }
        .section-subtitle { font-size: 15px; color: var(--text-gray); line-height: 1.7; max-width: 600px; margin: 0 auto 48px; }

        /* STATS */
        .stats-row { display: flex; gap: 0; justify-content: center; padding: 60px 80px; }
        .stat-item { flex: 1; text-align: center; }
        .stat-number { font-size: 48px; font-weight: 900; color: var(--navy); }
        .stat-label { font-size: 14px; color: var(--text-gray); margin-top: 4px; }

        /* CARDS */
        .cards-grid { display: grid; gap: 24px; }
        .cards-3 { grid-template-columns: repeat(3, 1fr); }
        .cards-4 { grid-template-columns: repeat(4, 1fr); }
        .cards-2 { grid-template-columns: repeat(2, 1fr); }
        .card {
            background: #fff; border: 1px solid var(--border);
            border-radius: 12px; padding: 28px; transition: box-shadow .2s;
        }
        .card:hover { box-shadow: 0 8px 32px rgba(0,0,0,.08); }
        .card-icon {
            width: 48px; height: 48px; background: rgba(232,101,26,.1);
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            color: var(--orange); font-size: 20px; margin-bottom: 16px;
        }
        .card-icon.navy { background: rgba(45,43,143,.1); color: var(--navy); }
        .card-title { font-size: 17px; font-weight: 700; color: var(--text-dark); margin-bottom: 10px; }
        .card-text { font-size: 14px; color: var(--text-blue); line-height: 1.65; }
        .card-link { display: inline-block; margin-top: 16px; font-size: 14px; font-weight: 600; color: var(--orange); }
        .card-link:hover { text-decoration: underline; }

        /* ARTICLE CARD */
        .article-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 24px; display: flex; flex-direction: column; }
        .article-journal { font-size: 13px; font-weight: 600; color: var(--orange); margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
        .article-journal i { font-size: 14px; }
        .article-title { font-size: 16px; font-weight: 700; color: var(--text-dark); line-height: 1.4; margin-bottom: 8px; }
        .article-authors { font-size: 13px; color: var(--text-gray); margin-bottom: 12px; }
        .article-abstract { font-size: 13px; color: var(--text-gray); line-height: 1.6; flex: 1; }
        .article-actions { display: flex; gap: 10px; margin-top: 16px; align-items: center; }
        .article-tag { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background: rgba(232,101,26,.1); color: var(--orange); }
        .article-tag.blue { background: rgba(45,43,143,.08); color: var(--navy); }
        .article-tag.green { background: rgba(16,185,129,.1); color: #059669; }
        .article-year { font-size: 12px; color: var(--text-gray); }

        /* BLOG CARD */
        .blog-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
        .blog-img { width: 100%; height: 200px; background: linear-gradient(135deg, #4B5FAB, var(--navy)); display: flex; align-items: center; justify-content: center; }
        .blog-img img { width: 100%; height: 100%; object-fit: cover; }
        .blog-body { padding: 20px; }
        .blog-date { font-size: 12px; color: var(--orange); margin-bottom: 8px; }
        .blog-title { font-size: 16px; font-weight: 700; color: var(--text-dark); line-height: 1.4; margin-bottom: 8px; }
        .blog-excerpt { font-size: 13px; color: var(--text-gray); line-height: 1.6; }
        .blog-link { display: inline-flex; align-items: center; gap: 4px; margin-top: 12px; font-size: 13px; font-weight: 600; color: var(--orange); }

        /* DARK SECTION */
        .section-dark {
            background: var(--navy);
            position: relative; overflow: hidden;
        }
        .section-dark .diamond-bg {
            position: absolute; inset: 0;
            background-image: repeating-linear-gradient(45deg, rgba(255,255,255,.03) 0, rgba(255,255,255,.03) 1px, transparent 0, transparent 50%);
            background-size: 60px 60px;
        }
        .section-dark .section-title, .section-dark p { color: #fff; }
        .section-dark .section-subtitle { color: rgba(255,255,255,.7); }
        .dark-card {
            background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15);
            border-radius: 14px; padding: 28px; text-align: center;
        }
        .dark-card .icon-circle {
            width: 56px; height: 56px; background: rgba(255,255,255,.15);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: var(--orange); font-size: 22px; margin: 0 auto 16px;
        }
        .dark-card h3 { color: #fff; font-size: 17px; font-weight: 700; margin-bottom: 8px; }
        .dark-card p { color: rgba(255,255,255,.75); font-size: 13px; line-height: 1.6; margin-bottom: 16px; }

        /* FOOTER */
        .footer-newsletter {
            background: var(--navy); padding: 40px 80px;
            display: flex; align-items: center; justify-content: space-between; gap: 40px;
        }
        .footer-newsletter h3 { color: #fff; font-size: 20px; font-weight: 700; }
        .footer-newsletter p { color: rgba(255,255,255,.7); font-size: 14px; margin-top: 4px; }
        .newsletter-form { display: flex; gap: 10px; flex: 1; max-width: 500px; }
        .newsletter-form input {
            flex: 1; padding: 12px 16px; border-radius: 7px; border: 1px solid rgba(255,255,255,.3);
            background: rgba(255,255,255,.1); color: #fff; font-size: 14px; outline: none;
        }
        .newsletter-form input::placeholder { color: rgba(255,255,255,.5); }
        .footer-main {
            background: #1e1c6e; padding: 60px 80px 40px;
        }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr; gap: 40px; }
        .footer-brand .logo { font-size: 20px; margin-bottom: 16px; display: flex; }
        .footer-brand p { font-size: 13px; color: rgba(255,255,255,.65); line-height: 1.7; margin-bottom: 20px; }
        .footer-contact { display: flex; flex-direction: column; gap: 8px; }
        .footer-contact a { color: rgba(255,255,255,.65); font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .footer-col h4 { color: #fff; font-size: 15px; font-weight: 700; margin-bottom: 16px; }
        .footer-col a { display: block; color: rgba(255,255,255,.65); font-size: 13px; margin-bottom: 10px; transition: color .2s; }
        .footer-col a:hover { color: #fff; }
        .footer-bottom {
            background: #1e1c6e; padding: 20px 80px;
            border-top: 1px solid rgba(255,255,255,.1);
            display: flex; align-items: center; justify-content: space-between;
        }
        .footer-bottom p { color: rgba(255,255,255,.5); font-size: 13px; }
        .footer-bottom .footer-links { display: flex; gap: 20px; }
        .footer-bottom .footer-links a { color: rgba(255,255,255,.5); font-size: 13px; }
        .footer-bottom .footer-links a:hover { color: #fff; }
        .social-icons { display: flex; gap: 12px; }
        .social-icons a {
            width: 32px; height: 32px; border-radius: 50%; border: 1px solid rgba(255,255,255,.3);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.6); font-size: 14px; transition: all .2s;
        }
        .social-icons a:hover { background: var(--orange); border-color: var(--orange); color: #fff; }
        .powered-by { color: rgba(255,255,255,.5); font-size: 13px; }
        .powered-by strong { color: rgba(255,255,255,.8); }

        /* BREADCRUMB */
        .breadcrumb {
            padding: 14px 80px; display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: var(--text-gray); background: #fff; border-bottom: 1px solid var(--border);
        }
        .breadcrumb a { color: var(--text-gray); }
        .breadcrumb a:hover { color: var(--navy); }
        .breadcrumb i { font-size: 11px; }

        /* FORMS */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 14px; font-weight: 600; color: var(--text-dark); margin-bottom: 6px; }
        .form-control {
            width: 100%; padding: 11px 14px; border: 1.5px solid var(--border);
            border-radius: 8px; font-size: 14px; color: var(--text-dark);
            outline: none; transition: border-color .2s; font-family: inherit;
        }
        .form-control:focus { border-color: var(--navy); }
        textarea.form-control { resize: vertical; min-height: 120px; }
        select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; }

        /* ALERT */
        .alert { padding: 14px 20px; border-radius: 8px; font-size: 14px; margin-bottom: 20px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* FILTER TABS */
        .filter-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 32px; align-items: center; }
        .filter-tab {
            padding: 8px 18px; border-radius: 30px; font-size: 14px; font-weight: 500;
            border: 1.5px solid var(--border); cursor: pointer; transition: all .2s; color: var(--text-gray);
            background: #fff;
        }
        .filter-tab.active, .filter-tab:hover { background: var(--navy); color: #fff; border-color: var(--navy); }

        /* STEP PROCESS */
        .steps-row { display: flex; align-items: flex-start; gap: 0; }
        .step-item { flex: 1; text-align: center; position: relative; }
        .step-item:not(:last-child)::after {
            content: '→'; position: absolute; top: 30px; right: -10px;
            font-size: 18px; color: var(--text-gray); transform: translateY(-50%);
        }
        .step-icon {
            width: 60px; height: 60px; background: #fff; border: 2px solid var(--border);
            border-radius: 12px; display: flex; align-items: center; justify-content: center;
            color: var(--orange); font-size: 24px; margin: 0 auto 12px;
        }
        .step-title { font-size: 13px; font-weight: 700; color: var(--text-dark); }
        .step-desc { font-size: 12px; color: var(--text-blue); margin-top: 4px; }

        /* TWO COL */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
        .two-col.reverse { direction: rtl; }
        .two-col.reverse > * { direction: ltr; }
        .col-img { border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,.15); }
        .col-img img { width: 100%; height: 380px; object-fit: cover; display: block; }
        .blockquote { border-left: 3px solid var(--orange); padding-left: 16px; margin-top: 20px; font-style: italic; color: var(--text-gray); font-size: 14px; line-height: 1.7; }

        /* NUMBERED LIST */
        .check-list { list-style: none; display: flex; flex-direction: column; gap: 12px; margin-top: 16px; }
        .check-list li { display: flex; align-items: flex-start; gap: 10px; font-size: 14px; color: var(--text-dark); }
        .check-list li i { color: var(--orange); margin-top: 2px; }

        /* PAGE HERO (dark with pattern dots) */
        .page-hero {
            background: var(--navy); min-height: 320px;
            display: flex; align-items: center; justify-content: center;
            text-align: center; position: relative; overflow: hidden;
        }
        .page-hero .hero-pattern { opacity: .4; }
        .page-hero-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: .2; }
        .page-hero-content { position: relative; z-index: 2; padding: 60px 40px; }

        /* SIDEBAR LAYOUT */
        .sidebar-layout { display: grid; grid-template-columns: 280px 1fr; gap: 40px; }
        .sidebar { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 24px; position: sticky; top: 80px; align-self: start; }
        .sidebar h5 { font-size: 12px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--text-gray); margin-bottom: 16px; }
        .sidebar-nav { display: flex; flex-direction: column; gap: 2px; }
        .sidebar-nav a {
            display: flex; align-items: center; gap: 10px; padding: 10px 12px;
            border-radius: 7px; font-size: 14px; color: var(--text-gray);
            transition: all .2s;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active { background: var(--bg-light); color: var(--navy); font-weight: 600; }
        .sidebar-nav a i { width: 18px; color: var(--orange); }

        /* PROPOSAL WIZARD */
        .wizard-steps { display: flex; align-items: center; gap: 0; margin-bottom: 40px; }
        .wstep {
            display: flex; flex-direction: column; align-items: center; flex: 1; position: relative;
        }
        .wstep:not(:last-child)::after {
            content: ''; position: absolute; top: 20px; left: 50%; width: 100%; height: 2px;
            background: var(--border); z-index: 0;
        }
        .wstep.done::after, .wstep.active::after { background: var(--navy); }
        .wstep-circle {
            width: 40px; height: 40px; border-radius: 50%; border: 2px solid var(--border);
            display: flex; align-items: center; justify-content: center; font-size: 14px;
            font-weight: 700; background: #fff; color: var(--text-gray); position: relative; z-index: 1;
        }
        .wstep.active .wstep-circle { border-color: var(--navy); background: var(--navy); color: #fff; }
        .wstep.done .wstep-circle { border-color: var(--navy); background: var(--navy); color: #fff; }
        .wstep-label { font-size: 12px; margin-top: 6px; color: var(--text-gray); font-weight: 500; }
        .wstep.active .wstep-label { color: var(--navy); font-weight: 700; }

        /* SIGN IN PAGE */
        .auth-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: var(--bg-light); }
        .auth-box { background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 48px 40px; width: 440px; }
        .auth-logo { text-align: center; margin-bottom: 32px; }
        .auth-title { font-size: 24px; font-weight: 800; text-align: center; margin-bottom: 8px; }
        .auth-sub { text-align: center; color: var(--text-gray); font-size: 14px; margin-bottom: 32px; }
        .auth-divider { text-align: center; color: var(--text-gray); font-size: 13px; margin: 20px 0; position: relative; }
        .auth-divider::before, .auth-divider::after { content: ''; position: absolute; top: 50%; width: 40%; height: 1px; background: var(--border); }
        .auth-divider::before { left: 0; }
        .auth-divider::after { right: 0; }
        .auth-link { text-align: center; font-size: 14px; color: var(--text-gray); margin-top: 20px; }
        .auth-link a { color: var(--navy); font-weight: 600; }

        /* SCROLL TO TOP */
        .scroll-top {
            position: fixed; bottom: 30px; right: 30px;
            width: 44px; height: 44px; background: var(--navy); color: #fff;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 18px; cursor: pointer; box-shadow: 0 4px 20px rgba(0,0,0,.2);
            transition: all .2s; z-index: 999;
        }
        .scroll-top:hover { background: var(--orange); transform: translateY(-3px); }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .navbar { padding: 0 20px; }
            .nav-links { display: none; }
            .section, .section-sm { padding: 50px 24px; }
            .hero-content { padding: 50px 24px; }
            .cards-3, .cards-4 { grid-template-columns: 1fr; }
            .cards-2 { grid-template-columns: 1fr; }
            .two-col { grid-template-columns: 1fr; gap: 32px; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .footer-newsletter { flex-direction: column; padding: 40px 24px; }
            .footer-main, .footer-bottom { padding: 40px 24px; }
            .stats-row { flex-wrap: wrap; }
            .sidebar-layout { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<nav class="navbar">
    <a href="index.php" class="logo">
        <span class="blue">Afrika</span><span class="orange">scholar</span>
    </a>
    <div class="nav-links">
        <a href="about.php" <?= $current_page==='about'?'class="active"':'' ?>>About</a>
        <a href="publications.php" <?= $current_page==='publications'?'class="active"':'' ?>>Publications</a>
        <a href="network.php" <?= $current_page==='network'?'class="active"':'' ?>>Network</a>
        <a href="institution.php" <?= $current_page==='institution'?'class="active"':'' ?>>Institution</a>
        <div class="dropdown">
            <a href="advisory.php" <?= in_array($current_page,['advisory','transcript_advisory','degree_programs','study_in_africa'])?'class="active"':'' ?>>Advisory <i class="fas fa-chevron-down" style="font-size:10px"></i></a>
            <div class="dropdown-menu">
                <a href="advisory.php"><span class="dm-title">Overview</span><br><span class="dm-sub">Educational & University Advisory services</span></a>
                <a href="transcript_advisory.php"><span class="dm-title">Transcript Advisory</span><br><span class="dm-sub">University transcript processing guidance</span></a>
                <a href="degree_programs.php"><span class="dm-title">Degree Programs</span><br><span class="dm-sub">Part-time, Master's & Doctoral pathways</span></a>
                <a href="study_in_africa.php"><span class="dm-title">Study in Africa</span><br><span class="dm-sub">Academic mobility opportunities</span></a>
            </div>
        </div>
        <div class="dropdown">
            <a href="publishing.php" <?= in_array($current_page,['publishing','submit_manuscript','start_journal','call_for_papers','peer_review_policy'])?'class="active"':'' ?>>Publishing <i class="fas fa-chevron-down" style="font-size:10px"></i></a>
            <div class="dropdown-menu">
                <a href="submit_manuscript.php"><span class="dm-title">Submit Manuscript</span><br><span class="dm-sub">Submit your research for publication</span></a>
                <a href="start_journal.php"><span class="dm-title">Start a Journal</span><br><span class="dm-sub">Launch a new academic journal</span></a>
                <a href="call_for_papers.php"><span class="dm-title">Call for Papers</span><br><span class="dm-sub">Open calls and special issues</span></a>
            </div>
        </div>
    </div>
    <div class="nav-actions">
        <a href="publish_paper.php" class="btn btn-outline">Publish Paper</a>
        <a href="publeesh_ai.php" class="btn btn-orange">Publeesh Ai</a>
        <a href="signin.php" class="btn btn-navy">Sign In</a>
    </div>
</nav>
