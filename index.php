<?php
require_once 'helpers.php';
$articles = read_csv('articles.csv');
$featured = array_filter($articles, fn($a) => $a['featured'] == '1');
$recent = array_slice($articles, 0, 6);
$insights = array_slice(read_csv('insights.csv'), 0, 3);
$active_filter = $_GET['cat'] ?? 'All';
$categories = ['All','STEM','Social Sciences','Law & Governance','Business & Economics'];
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Afrika Scholar</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
  :root {
    --primary: #1a2744;
    --primary-fg: #ffffff;
    --accent: #e85d1a;
    --accent-hover: #c94e0f;
    --secondary: #f1f4f9;
    --border: #e2e8f0;
    --muted: #64748b;
    --background: #ffffff;
    --card-shadow: 0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.04);
    --card-shadow-hover: 0 10px 25px rgba(0,0,0,.12);
    --radius: 8px;
  }
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Inter', sans-serif; color: #1e293b; background: var(--background); }
  a { text-decoration: none; color: inherit; }

  /* ---- Layout ---- */
  .container-section { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
  .section-padding { padding: 80px 0; }

  /* ---- Buttons ---- */
  .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: var(--radius); font-size: 14px; font-weight: 600; cursor: pointer; border: none; transition: all .2s; white-space: nowrap; }
  .btn-lg { padding: 14px 24px; font-size: 15px; }
  .btn-sm { padding: 7px 14px; font-size: 13px; }
  .btn-accent { background: var(--accent); color: #fff; }
  .btn-accent:hover { background: var(--accent-hover); }
  .btn-outline { background: transparent; border: 1px solid var(--border); color: #1e293b; }
  .btn-outline:hover { background: var(--secondary); }
  .btn-white-outline { background: transparent; border: 1px solid rgba(255,255,255,.6); color: #fff; }
  .btn-white-outline:hover { background: rgba(255,255,255,.1); }
  .btn-primary-fg { background: #fff; color: var(--accent); border: 1px solid #fff; }
  .btn-primary-fg:hover { background: rgba(255,255,255,.1); color: #fff; }

  /* ---- Cards ---- */
  .card { background: #fff; border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--card-shadow); transition: box-shadow .2s, transform .2s; overflow: hidden; }
  .card:hover { box-shadow: var(--card-shadow-hover); transform: translateY(-2px); }
  .card-header { padding: 24px 24px 0; }
  .card-content { padding: 0 24px 24px; }
  .card-title { font-size: 17px; font-weight: 700; line-height: 1.4; color: #1e293b; margin-bottom: 6px; }
  .card-title:hover { color: var(--accent); }
  .card-desc { font-size: 13px; color: var(--muted); }
  .card-text { font-size: 14px; color: var(--muted); line-height: 1.6; margin-bottom: 16px; }
  .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
  .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
  .line-clamp-1 { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .cards-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
  .cards-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
  .cards-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
  @media(max-width:1024px){.cards-grid-4{grid-template-columns:repeat(2,1fr)}.cards-grid-3{grid-template-columns:repeat(2,1fr)}}
  @media(max-width:640px){.cards-grid-4,.cards-grid-3,.cards-grid-2{grid-template-columns:1fr}}

  /* ---- Hero ---- */
  .hero { position: relative; overflow: hidden; min-height: 600px; display: flex; align-items: center; }
  .hero-bg-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
  .hero-overlay { position: absolute; inset: 0; background: rgba(26,39,68,.85); }
  .hero-overlay-grad { position: absolute; inset: 0; background: linear-gradient(to right, rgba(26,39,68,.95) 40%, transparent); }
  .hero-pattern { position: absolute; inset: 0; opacity: .1; background-image: radial-gradient(circle, rgba(255,255,255,.8) 1px, transparent 1px); background-size: 40px 40px; }
  .hero-inner { position: relative; z-index: 2; padding: 96px 0; }
  .hero-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center; }
  @media(max-width:900px){ .hero-grid { grid-template-columns: 1fr; } .hero-animated { display: none; } }
  .hero-fg { color: #fff; }
  .hero-title { font-size: clamp(24px, 3vw, 40px); font-weight: 800; line-height: 1.25; margin-bottom: 24px; }
  .hero-desc { font-size: 15px; color: rgba(255,255,255,.8); line-height: 1.75; margin-bottom: 40px; max-width: 560px; }
  .hero-actions { display: flex; gap: 12px; flex-wrap: nowrap; }

  /* ---- Animated Hero Visual ---- */
  .hero-animated { display: flex; justify-content: center; align-items: center; }
  .animated-visual { width: 340px; height: 340px; position: relative; }
  .av-ring { position: absolute; border-radius: 50%; border: 1px solid rgba(255,255,255,.15); animation: pulse-ring 3s ease-in-out infinite; }
  .av-ring-1 { width: 100%; height: 100%; top: 0; left: 0; animation-delay: 0s; }
  .av-ring-2 { width: 75%; height: 75%; top: 12.5%; left: 12.5%; animation-delay: .5s; }
  .av-ring-3 { width: 50%; height: 50%; top: 25%; left: 25%; animation-delay: 1s; }
  @keyframes pulse-ring { 0%,100%{opacity:.3;transform:scale(1)}50%{opacity:.7;transform:scale(1.03)} }
  .av-center { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); width: 80px; height: 80px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 40px rgba(232,93,26,.5); }
  .av-center i { font-size: 32px; color: #fff; }
  .av-node { position: absolute; width: 52px; height: 52px; border-radius: 50%; background: rgba(255,255,255,.1); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,.2); display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,.9); font-size: 18px; animation: float-node 4s ease-in-out infinite; }
  .av-node-1 { top: 10%; left: 50%; transform: translateX(-50%); animation-delay: 0s; }
  .av-node-2 { top: 50%; right: 5%; transform: translateY(-50%); animation-delay: .7s; }
  .av-node-3 { bottom: 10%; left: 50%; transform: translateX(-50%); animation-delay: 1.4s; }
  .av-node-4 { top: 50%; left: 5%; transform: translateY(-50%); animation-delay: 2.1s; }
  @keyframes float-node { 0%,100%{transform:translateX(-50%) translateY(0)}50%{transform:translateX(-50%) translateY(-8px)} }
  .av-node-2, .av-node-4 { animation-name: float-node-h; }
  @keyframes float-node-h { 0%,100%{transform:translateY(-50%) translateX(0)}50%{transform:translateY(-50%) translateX(-6px)} }

  /* ---- Stats Row ---- */
  .stats-row { background: var(--background); padding: 48px 0; }
  .stats-inner { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
  @media(max-width:640px){.stats-inner{grid-template-columns:repeat(2,1fr)}}
  .stat-card { text-align: center; padding: 24px; background: #fff; border: 1px solid var(--border); border-radius: var(--radius); }
  .stat-number { font-size: 36px; font-weight: 800; color: var(--primary); }
  .stat-label { font-size: 14px; color: var(--muted); margin-top: 4px; }

  /* ---- Section headings ---- */
  .section-eyebrow { font-size: 12px; text-transform: uppercase; letter-spacing: .1em; color: var(--accent); font-weight: 600; margin-bottom: 8px; }
  .section-title { font-size: clamp(24px, 3vw, 34px); font-weight: 800; line-height: 1.25; margin-bottom: 16px; }
  .section-subtitle { color: var(--muted); max-width: 480px; margin: 0 auto; font-size: 15px; }

  /* ---- Pillar Cards ---- */
  .pillar-icon { width: 48px; height: 48px; border-radius: var(--radius); background: rgba(232,93,26,.1); display: flex; align-items: center; justify-content: center; margin-bottom: 16px; transition: all .2s; }
  .pillar-icon i { font-size: 20px; color: var(--accent); transition: color .2s; }
  .card:hover .pillar-icon { background: var(--accent); }
  .card:hover .pillar-icon i { color: #fff; }
  .card-link { font-size: 14px; font-weight: 600; color: var(--accent); display: inline-flex; align-items: center; gap: 4px; }
  .card-link:hover { text-decoration: underline; }

  /* ---- Publeesh Section ---- */
  .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center; }
  @media(max-width:900px){.two-col{grid-template-columns:1fr}}
  .feature-mini-card { background: #fff; border: 1px solid var(--border); border-radius: var(--radius); padding: 24px 16px; text-align: center; box-shadow: var(--card-shadow); }
  .feature-mini-card.highlighted { border-color: var(--accent); }
  .feature-mini-icon { font-size: 28px; color: var(--accent); margin-bottom: 10px; }
  .feature-mini-label { font-size: 14px; font-weight: 600; color: var(--primary); }
  .feature-mini-card.highlighted .feature-mini-label { color: var(--accent); }

  /* ---- Tabs ---- */
  .tabs-list { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; background: var(--secondary); padding: 6px; border-radius: var(--radius); width: fit-content; }
  .tab-trigger { padding: 7px 16px; border-radius: 6px; font-size: 13px; font-weight: 500; color: var(--muted); cursor: pointer; border: none; background: transparent; transition: all .15s; white-space: nowrap; }
  .tab-trigger.active, .tab-trigger:hover { background: #fff; color: #1e293b; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
  .tab-nav-btn { width: 36px; height: 36px; border-radius: var(--radius); border: 1px solid var(--border); background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--muted); transition: all .15s; flex-shrink: 0; }
  .tab-nav-btn:hover:not(:disabled) { background: var(--secondary); }
  .tab-nav-btn:disabled { opacity: .4; cursor: default; }
  .tabs-header { display: flex; align-items: center; gap: 8px; margin-bottom: 24px; flex-wrap: wrap; }
  .tab-panel { display: none; }
  .tab-panel.active { display: block; }

  /* ---- Publication Card ---- */
  .pub-card { display: flex; flex-direction: column; height: 100%; }
  .pub-card .card-header { flex: 1; }
  .pub-tag { font-size: 11px; font-weight: 600; color: var(--accent); background: rgba(232,93,26,.08); padding: 3px 10px; border-radius: 4px; }
  .pub-year { font-size: 12px; color: var(--muted); }
  .pub-actions { display: flex; align-items: center; justify-content: space-between; }
  .pub-journal { font-size: 12px; color: var(--muted); }
  .read-more-link { font-size: 13px; font-weight: 600; color: var(--accent); display: inline-flex; align-items: center; gap: 4px; }
  .read-more-link:hover { text-decoration: underline; }

  /* ---- Get Involved ---- */
  .involve-section { position: relative; overflow: hidden; }
  .involve-bg { position: absolute; inset: 0; background: var(--primary); }
  .involve-pattern { position: absolute; inset: 0; opacity: .1; }
  .involve-inner { position: relative; z-index: 2; color: #fff; }
  .dark-card { background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15); border-radius: var(--radius); padding: 28px; text-align: center; backdrop-filter: blur(8px); transition: box-shadow .2s, transform .2s; }
  .dark-card:hover { box-shadow: 0 10px 30px rgba(0,0,0,.3); transform: translateY(-2px); }
  .dark-card-icon { width: 56px; height: 56px; border-radius: 50%; background: rgba(232,93,26,.2); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
  .dark-card-icon i { font-size: 22px; color: var(--accent); }
  .dark-card h3 { font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 8px; }
  .dark-card p { font-size: 13px; color: rgba(255,255,255,.65); margin-bottom: 16px; line-height: 1.6; }

  /* ---- Blog ---- */
  .blog-card { background: #fff; border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; display: flex; flex-direction: column; transition: box-shadow .2s, transform .2s; height: 100%; }
  .blog-card:hover { box-shadow: var(--card-shadow-hover); transform: translateY(-2px); }
  .blog-img-wrap { height: 192px; overflow: hidden; }
  .blog-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
  .blog-card:hover .blog-img-wrap img { transform: scale(1.05); }
  .blog-body { padding: 20px; flex: 1; display: flex; flex-direction: column; }
  .blog-date { font-size: 12px; color: var(--muted); margin-bottom: 8px; }
  .blog-title { font-size: 16px; font-weight: 700; line-height: 1.45; margin-bottom: 10px; color: #1e293b; }
  .blog-excerpt { font-size: 13px; color: var(--muted); line-height: 1.6; flex: 1; margin-bottom: 12px; }
  .blog-link { font-size: 13px; font-weight: 600; color: var(--accent); display: inline-flex; align-items: center; gap: 4px; }
  .blog-link:hover { text-decoration: underline; }

  /* ---- Dialog / Modal ---- */
  .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 1000; align-items: center; justify-content: center; }
  .modal-overlay.open { display: flex; }
  .modal { background: #fff; border-radius: 12px; padding: 32px; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,.2); }
  .modal-title { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
  .modal-sub { font-size: 13px; color: var(--muted); margin-bottom: 16px; }
  .modal-cite-box { background: var(--secondary); padding: 16px; border-radius: var(--radius); font-size: 13px; font-family: monospace; line-height: 1.7; margin-bottom: 20px; }
  .modal-actions { display: flex; gap: 12px; justify-content: flex-end; }

  /* ---- Toast ---- */
  .toast { position: fixed; bottom: 24px; right: 24px; background: #1e293b; color: #fff; padding: 14px 20px; border-radius: var(--radius); font-size: 14px; font-weight: 500; box-shadow: 0 4px 20px rgba(0,0,0,.3); z-index: 2000; opacity: 0; transform: translateY(8px); transition: all .3s; pointer-events: none; }
  .toast.show { opacity: 1; transform: translateY(0); }
  .toast strong { display: block; font-size: 15px; }

  /* ---- Misc ---- */
  .text-center { text-align: center; }
  .mb-12 { margin-bottom: 48px; }
  .mb-8 { margin-bottom: 32px; }
  .flex-between { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
  .sparkles-icon { width: 40px; height: 40px; margin: 0 auto 16px; color: var(--accent); font-size: 32px; }
</style>
</head>
<body>

<!-- Citation Modal -->
<div class="modal-overlay" id="citeModal">
  <div class="modal">
    <div class="modal-title">Cite This Article</div>
    <div class="modal-sub">APA Format Citation</div>
    <div class="modal-cite-box" id="citationText"></div>
    <div class="modal-actions">
      <button class="btn btn-outline" onclick="closeCiteModal()">Close</button>
      <button class="btn btn-accent" onclick="copyCitation()">Copy Citation</button>
    </div>
  </div>
</div>

<!-- Toast -->
<div class="toast" id="toast"><strong id="toastTitle"></strong><span id="toastDesc"></span></div>

<!-- ===== HERO ===== -->
<section class="hero">
  <img src="asset/hero-scholars.jpg" alt="African scholars collaborating" class="hero-bg-img"/>
  <div class="hero-overlay"></div>
  <div class="hero-overlay-grad"></div>
  <div class="hero-pattern"></div>
  <div class="container-section hero-inner">
    <div class="hero-grid">
      <div class="hero-fg">
        <h1 class="hero-title">Pan-African Academic Publishing, Research &amp; University Enablement with AI-Powered Research Intelligence</h1>
        <p class="hero-desc">Afrika Scholar is a journal-first academic infrastructure platform designed to publish, validate, preserve, and amplify African scholarship to global standards. It is strengthened by responsible AI-powered research intelligence tools that enhance productivity without compromising academic integrity.</p>
        <div class="hero-actions">
          <a href="publications.php" class="btn btn-accent btn-lg">Explore Journals <i class="fas fa-arrow-right"></i></a>
          <a href="submit_manuscript.php" class="btn btn-primary-fg btn-lg">Publish With Us</a>
          <a href="publeesh_ai.php" class="btn btn-white-outline btn-lg">Explore Research Intelligence</a>
        </div>
      </div>
      <div class="hero-animated">
        <div class="animated-visual">
          <div class="av-ring av-ring-1"></div>
          <div class="av-ring av-ring-2"></div>
          <div class="av-ring av-ring-3"></div>
          <div class="av-center"><i class="fas fa-book-open"></i></div>
          <div class="av-node av-node-1"><i class="fas fa-graduation-cap"></i></div>
          <div class="av-node av-node-2"><i class="fas fa-globe-africa"></i></div>
          <div class="av-node av-node-3"><i class="fas fa-file-alt"></i></div>
          <div class="av-node av-node-4"><i class="fas fa-users"></i></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== STATS ===== -->
<section class="stats-row">
  <div class="container-section">
    <div class="stats-inner">
      <div class="stat-card"><div class="stat-number" data-target="50">0</div><div class="stat-label">Active Journals</div></div>
      <div class="stat-card"><div class="stat-number" data-target="2000">0</div><div class="stat-label">Published Articles</div></div>
      <div class="stat-card"><div class="stat-number" data-target="500">0</div><div class="stat-label">Academic Partners</div></div>
      <div class="stat-card"><div class="stat-number" data-target="35">0</div><div class="stat-label">African Countries</div></div>
    </div>
  </div>
</section>

<!-- ===== FEATURED RESEARCH ===== -->
<section class="section-padding" style="background: #f1f4f9;">
  <div class="container-section">
    <div class="text-center mb-12">
      <h2 class="section-title">Featured Research</h2>
      <p class="section-subtitle">Highlighting impactful research from across the African continent</p>
    </div>
    <div class="cards-grid-3">
      <?php foreach (array_slice(array_values($featured), 0, 3) as $pub): ?>
      <div class="card">
        <div class="card-header">
          <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--accent);font-weight:600;margin-bottom:10px">
            <i class="fas fa-file-alt" style="font-size:14px"></i>
            <?= htmlspecialchars($pub['journal']) ?>
          </div>
          <div class="card-title line-clamp-2"><?= htmlspecialchars($pub['title']) ?></div>
          <div class="card-desc line-clamp-1"><?= htmlspecialchars($pub['authors']) ?> &bull; <?= htmlspecialchars($pub['year']) ?></div>
        </div>
        <div class="card-content">
          <p class="card-text line-clamp-3"><?= htmlspecialchars($pub['abstract']) ?></p>
          <div style="display:flex;gap:8px">
            <a href="article.php?id=<?= $pub['id'] ?>" class="btn btn-accent btn-sm">Read Full Article</a>
            <button class="btn btn-outline btn-sm" onclick="openCite('<?= addslashes($pub['authors']) ?>', '<?= addslashes($pub['year']) ?>', '<?= addslashes($pub['title']) ?>', '<?= addslashes($pub['journal']) ?>', '<?= addslashes($pub['doi'] ?? 'N/A') ?>')">Cite</button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== WHAT AFRIKA SCHOLAR ENABLES ===== -->
<section class="section-padding">
  <div class="container-section">
    <div class="text-center mb-12">
      <div class="section-eyebrow">What Afrika Scholar Enables</div>
      <h2 class="section-title">Academic Publishing, Research &amp; Institutional Enablement<br>Built for Africa</h2>
    </div>
    <div class="cards-grid-4">
      <?php
      $pillars = [
        ['icon'=>'fa-book-open','title'=>'Journal Publishing','desc'=>'End-to-end journal creation, hosting, and management including article submission, peer review workflows, editorial governance, call for papers, DOI & citation readiness, article analytics, and optional revenue-sharing models.','label'=>'Publishing Standards','link'=>'peer_review_policy.php'],
        ['icon'=>'fa-graduation-cap','title'=>'Institutional Enablement & Support','desc'=>'Access to verified lecturers and researchers for teaching, adjunct roles, research and policy advisory, peer review, curriculum development, and accreditation or licensing support for universities and EdTech platforms.','label'=>'Institutional Enablement','link'=>'institution.php'],
        ['icon'=>'fa-user-friends','title'=>'Academic Advisory','desc'=>'University transcript facilitation, part-time and postgraduate program advisory, cross-border "Study in Africa" coordination, academic documentation support, and structured partnerships with selected universities.','label'=>'How Academics Engage','link'=>'advisory.php'],
        ['icon'=>'fa-puzzle-piece','title'=>'Academic Engagement and Network','desc'=>'A structured platform connecting verified academics to part-time lecturing roles, EdTech delivery, research and industry-linked projects, peer review and editorial work, curriculum development, and income-generating academic engagements.','label'=>'Learn More','link'=>'network.php'],
      ];
      foreach($pillars as $p): ?>
      <div class="card" style="padding:24px">
        <div class="pillar-icon"><i class="fas <?= $p['icon'] ?>"></i></div>
        <div class="card-title" style="font-size:16px;margin-bottom:10px"><?= $p['title'] ?></div>
        <p class="card-text"><?= $p['desc'] ?></p>
        <a href="<?= $p['link'] ?>" class="card-link"><?= $p['label'] ?> <i class="fas fa-arrow-right" style="font-size:12px"></i></a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== PUBLEESH / RESEARCH INTELLIGENCE ===== -->
<section class="section-padding" style="background:#f1f4f9;border-top:1px solid var(--border);border-bottom:1px solid var(--border)">
  <div class="container-section">
    <div class="two-col">
      <div>
        <div class="section-eyebrow">Research Intelligence</div>
        <h2 class="section-title">Research Intelligence, <span style="color:var(--primary)">Powered by Responsible AI</span></h2>
        <p style="color:var(--muted);font-size:15px;line-height:1.75;margin-bottom:28px">Integrated within Afrika Scholar, Publeesh AI enhances research workflows through structured drafting support, citation guidance, and global dataset access that empowers scholars while <strong style="color:#1e293b">preserving academic integrity</strong>.</p>
        <div style="display:flex;gap:12px;flex-wrap:wrap">
          <a href="publeesh_ai.php" class="btn btn-accent btn-lg">Explore Research Intelligence <i class="fas fa-arrow-right"></i></a>
          <a href="signin.php" class="btn btn-outline btn-lg">View Subscription Plans</a>
        </div>
      </div>
      <div class="cards-grid-2">
        <?php
        $features = [
          ['icon'=>'fa-file-alt','label'=>'Structured Drafting','hi'=>false],
          ['icon'=>'fa-book-open','label'=>'Citation Guidance','hi'=>false],
          ['icon'=>'fa-globe','label'=>'Global Datasets','hi'=>true],
          ['icon'=>'fa-chart-bar','label'=>'Comparative Tools','hi'=>false],
          ['icon'=>'fa-lightbulb','label'=>'Thesis Frameworks','hi'=>false],
          ['icon'=>'fa-robot','label'=>'AI-Powered Insights','hi'=>false],
        ];
        foreach($features as $f): ?>
        <div class="feature-mini-card <?= $f['hi']?'highlighted':'' ?>">
          <div class="feature-mini-icon"><i class="fas <?= $f['icon'] ?>"></i></div>
          <div class="feature-mini-label"><?= $f['label'] ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ===== RECENT PUBLICATIONS WITH TABS ===== -->
<section class="section-padding" style="background:#f1f4f9;border-top:1px solid var(--border)">
  <div class="container-section">
    <div class="flex-between mb-8">
      <div>
        <h2 class="section-title" style="margin-bottom:4px">Recent Publications</h2>
        <p style="color:var(--muted);font-size:14px">Latest research across disciplines</p>
      </div>
      <a href="publications.php" class="btn btn-outline">View All Publications <i class="fas fa-arrow-right"></i></a>
    </div>

    <?php
    $all_disciplines = array_unique(array_column($articles, 'category'));
    $all_disciplines = array_values(array_filter($all_disciplines));
    $tabs = array_merge(['All'], $all_disciplines);
    $VISIBLE_TABS = 4;
    $tab_offset = isset($_GET['tab_offset']) ? (int)$_GET['tab_offset'] : 0;
    $visible_tabs = array_slice($tabs, $tab_offset + 1, $VISIBLE_TABS); // skip 'All'
    $active_tab = $_GET['tab'] ?? 'All';
    ?>

    <div class="tabs-header">
      <div class="tabs-list">
        <button class="tab-trigger <?= $active_tab==='All'?'active':'' ?>" onclick="switchTab('All')">All</button>
        <?php foreach($visible_tabs as $disc): ?>
        <button class="tab-trigger <?= $active_tab===$disc?'active':'' ?>" onclick="switchTab('<?= htmlspecialchars(addslashes($disc)) ?>')"><?= htmlspecialchars($disc) ?></button>
        <?php endforeach; ?>
      </div>
      <button class="tab-nav-btn" id="tabPrev" onclick="shiftTab(-1)" <?= $tab_offset===0?'disabled':'' ?>>
        <i class="fas fa-chevron-left" style="font-size:13px"></i>
      </button>
      <button class="tab-nav-btn" id="tabNext" onclick="shiftTab(1)" <?= $tab_offset>=count($all_disciplines)-$VISIBLE_TABS?'disabled':'' ?>>
        <i class="fas fa-chevron-right" style="font-size:13px"></i>
      </button>
    </div>

    <?php
    // Filter publications for display
    if($active_tab === 'All') {
      $display_pubs = array_slice($articles, 0, 6);
    } else {
      $display_pubs = array_slice(array_values(array_filter($articles, fn($a)=> $a['category']===$active_tab)), 0, 6);
    }
    ?>
    <div class="cards-grid-3" id="pubGrid">
      <?php foreach($display_pubs as $pub): ?>
      <div class="card pub-card">
        <div class="card-header" style="flex:1">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px">
            <span class="pub-tag"><?= htmlspecialchars($pub['category'] ?? '') ?></span>
            <span class="pub-year"><?= htmlspecialchars($pub['year']) ?></span>
          </div>
          <div class="card-title line-clamp-2"><?= htmlspecialchars($pub['title']) ?></div>
          <div class="card-desc line-clamp-1" style="margin-top:4px"><?= htmlspecialchars($pub['authors']) ?></div>
        </div>
        <div class="card-content">
          <p class="card-text line-clamp-3"><?= htmlspecialchars($pub['abstract']) ?></p>
          <div class="pub-actions">
            <span class="pub-journal"><?= htmlspecialchars($pub['journal']) ?></span>
            <a href="article.php?id=<?= $pub['id'] ?>" class="read-more-link">Read More <i class="fas fa-lock" style="font-size:10px"></i></a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== GET INVOLVED ===== -->
<section class="section-padding involve-section">
  <div class="involve-bg"></div>
  <svg class="involve-pattern" viewBox="0 0 100 100" preserveAspectRatio="none" style="position:absolute;inset:0;width:100%;height:100%;opacity:.1">
    <defs><pattern id="inv-pat" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M0 10 L10 0 L20 10 L10 20Z" fill="none" stroke="white" stroke-width="0.3"/></pattern></defs>
    <rect width="100" height="100" fill="url(#inv-pat)"/>
  </svg>
  <div class="container-section involve-inner">
    <div class="text-center mb-12">
      <div class="sparkles-icon"><i class="fas fa-star"></i></div>
      <h2 style="font-size:34px;font-weight:800;margin-bottom:12px">Get Involved</h2>
      <p style="color:rgba(255,255,255,.75);font-size:15px;max-width:580px;margin:0 auto">Join Africa's growing academic community. Whether you're a researcher, educator, or institution, there's a place for you.</p>
    </div>
    <div class="cards-grid-4">
      <div class="dark-card">
        <div class="dark-card-icon"><i class="fas fa-file-upload"></i></div>
        <h3>Submit Research</h3>
        <p>Submit your research for peer-reviewed publication</p>
        <a href="submit_manuscript.php" class="btn btn-accent" style="width:100%;justify-content:center">Submit Manuscript</a>
      </div>
      <div class="dark-card">
        <div class="dark-card-icon"><i class="fas fa-users"></i></div>
        <h3>Join Network</h3>
        <p>Join our network for global academic opportunities</p>
        <a href="network.php" class="btn btn-accent" style="width:100%;justify-content:center">Apply Now</a>
      </div>
      <div class="dark-card">
        <div class="dark-card-icon"><i class="fas fa-graduation-cap"></i></div>
        <h3>Partner With Us</h3>
        <p>Partner with us to expand African scholarship</p>
        <a href="institution.php" class="btn btn-accent" style="width:100%;justify-content:center">Explore Partnership</a>
      </div>
      <div class="dark-card">
        <div class="dark-card-icon"><i class="fas fa-graduation-cap"></i></div>
        <h3>Request Advisory</h3>
        <p>Join us as professional and academic collaborator</p>
        <a href="advisory.php" class="btn btn-accent" style="width:100%;justify-content:center">Request Advisory</a>
      </div>
    </div>
  </div>
</section>

<!-- ===== BLOG & INSIGHTS ===== -->
<section class="section-padding" style="background:#f1f4f9;">
  <div class="container-section">
    <div class="text-center mb-12">
      <div class="section-eyebrow">Insights &amp; Updates</div>
      <h2 class="section-title">From the Afrika Scholar Knowledge Desk</h2>
    </div>
    <div class="cards-grid-3" style="margin-bottom:40px">
      <?php
      $blog_posts = [
        ['title'=>'Why Africa Needs Its Own Academic Publishing Infrastructure','excerpt'=>'Exploring the critical need for localized publishing platforms to ensure African research is prioritized...','date'=>'May 15, 2024','slug'=>'africa-publishing','image'=>'asset/blog1.png'],
        ['title'=>'Improving Global Visibility of African Research','excerpt'=>'Strategies and tools for researchers to increase the impact and reach of their academic work...','date'=>'June 2, 2024','slug'=>'global-visibility','image'=>'asset/blog2.png'],
        ['title'=>'Peer Review and Research Integrity in Africa','excerpt'=>'Maintaining high ethical standards and robust peer-review processes in the evolving landscape...','date'=>'June 20, 2024','slug'=>'peer-review','image'=>'asset/blog3.png'],
      ];
      // Override with CSV insights if available
      $insight_index = 0;
      foreach($blog_posts as $i => $bp):
        $post = isset($insights[$i]) ? $insights[$i] : $bp;
        $image = $bp['image']; // always use static image assets
      ?>
      <div class="blog-card">
        <div class="blog-img-wrap">
          <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($post['title'] ?? $bp['title']) ?>"/>
        </div>
        <div class="blog-body">
          <div class="blog-date"><?= htmlspecialchars($post['date'] ?? $bp['date']) ?></div>
          <div class="blog-title"><?= htmlspecialchars($post['title'] ?? $bp['title']) ?></div>
          <div class="blog-excerpt"><?= htmlspecialchars($post['excerpt'] ?? $bp['excerpt']) ?></div>
          <a href="<?= isset($insights[$i]) ? 'insight.php?id='.$insights[$i]['id'] : 'insights.php' ?>" class="blog-link">Read More <i class="fas fa-arrow-right" style="font-size:11px"></i></a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center">
      <a href="insights.php" class="btn btn-outline btn-lg">View All Insights <i class="fas fa-arrow-right"></i></a>
    </div>
  </div>
</section>

<script>
// ---- Toast ----
function showToast(title, desc) {
  const t = document.getElementById('toast');
  document.getElementById('toastTitle').textContent = title;
  document.getElementById('toastDesc').textContent = desc;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3000);
}

// ---- Citation Modal ----
function openCite(authors, year, title, journal, doi) {
  const text = authors + ' (' + year + '). ' + title + '. ' + journal + '. DOI: ' + doi;
  document.getElementById('citationText').textContent = text;
  document.getElementById('citeModal').classList.add('open');
}
function closeCiteModal() {
  document.getElementById('citeModal').classList.remove('open');
}
function copyCitation() {
  const text = document.getElementById('citationText').textContent;
  navigator.clipboard.writeText(text).then(() => {
    showToast('Copied!', 'Citation copied to clipboard.');
    closeCiteModal();
  });
}
document.getElementById('citeModal').addEventListener('click', function(e) {
  if(e.target === this) closeCiteModal();
});

// ---- Count Up Stats ----
document.addEventListener('DOMContentLoaded', () => {
  const nums = document.querySelectorAll('.stat-number[data-target]');
  const suffixes = { 50: '+', 2000: '+', 500: '+', 35: '' };
  nums.forEach(el => {
    const target = parseInt(el.dataset.target);
    const suffix = suffixes[target] || '';
    let start = 0;
    const duration = 1800;
    const step = target / (duration / 16);
    const timer = setInterval(() => {
      start += step;
      if(start >= target) { el.textContent = target.toLocaleString() + suffix; clearInterval(timer); }
      else el.textContent = Math.floor(start).toLocaleString() + suffix;
    }, 16);
  });
});

// ---- Tab switching (client-side for UX, page reload fallback for filter) ----
function switchTab(tab) {
  const url = new URL(window.location);
  url.searchParams.set('tab', tab);
  window.location = url;
}
function shiftTab(dir) {
  const url = new URL(window.location);
  const offset = parseInt(url.searchParams.get('tab_offset') || 0) + dir;
  url.searchParams.set('tab_offset', Math.max(0, offset));
  window.location = url;
}
</script>

<?php include 'footer.php'; ?>
</body>
</html>
