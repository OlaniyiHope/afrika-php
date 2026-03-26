<?php
$page_title = 'Institution Enablement';
require_once 'helpers.php';

$success = false; $errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inst_submit'])) {
    $name   = trim($_POST['inst_name'] ?? '');
    $email  = trim($_POST['inst_email'] ?? '');
    $org    = trim($_POST['org'] ?? '');
    $type   = trim($_POST['type'] ?? '');
    $needs  = trim($_POST['needs'] ?? '');
    if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$org) {
        $errors[] = 'Please fill all required fields correctly.';
    }
    if (empty($errors)) {
        $id = get_next_id('network_applications.csv');
        append_csv('network_applications.csv', [$id, $name, $email, $org, $type, $needs, date('Y-m-d'), 'institution']);
        $success = true;
    }
}
include 'header.php';
?>

<style>
/* ══ INSTITUTION PAGE — scoped styles ══════════════════════════════════ */

/* Section titles: normal weight, near-black — matching React */
.inst-title {
    font-size: clamp(20px, 2.5vw, 28px);
    font-weight: 700;
    color: #111827;
    margin: 0 0 12px;
    line-height: 1.3;
    font-family: Georgia, 'Times New Roman', serif;
}
.inst-eyebrow {
    font-size: 10px; font-weight: 700;
    letter-spacing: .14em; text-transform: uppercase;
    color: var(--orange, #e8651a); margin-bottom: 10px;
}
.inst-sub {
    font-size: 13px; color: #64748b;
    max-width: 520px; margin: 0 auto; line-height: 1.65;
}
.inst-center { text-align: center; }

/* Layout */
.inst-section     { padding: 64px 20px; background: #fff; }
.inst-section-alt { padding: 64px 20px; background: #f8f9ff; border-top: 1px solid #e5e7eb; }
.inst-wrap        { max-width: 1060px; margin: 0 auto; }
.inst-wrap-md     { max-width: 860px;  margin: 0 auto; }

/* ── HERO ── */
.inst-hero {
    position: relative;
    min-height: 520px;
    display: flex;
    align-items: center;
    overflow: hidden;
}
.inst-hero-bg {
    position: absolute; inset: 0;
    /* Use the same asset the React version uses.
       Adjust path to match where the image lives in your project. */
    background-image: url('asset/network-collaboration.jpg');
    background-size: cover;
    background-position: center top;
    z-index: 0;
}
/* Deep navy overlay — matches React bg-primary/85 */
.inst-hero-overlay {
    position: absolute; inset: 0;
    background: rgba(10, 22, 55, 0.85);
    z-index: 1;
}
/* Subtle dot-grid pattern */
.inst-hero-dots {
    position: absolute; inset: 0; z-index: 2; opacity: .07;
    background-image: radial-gradient(circle, #fff 1px, transparent 1px);
    background-size: 20px 20px;
}
.inst-hero-inner {
    position: relative; z-index: 3;
    max-width: 720px;
    padding: 80px 40px;
    color: #fff;
}
.inst-hero-eyebrow {
    font-size: 11px; font-weight: 700; letter-spacing: .14em;
    text-transform: uppercase; color: var(--orange,#e8651a); margin-bottom: 16px;
}
.inst-hero-title {
    font-size: clamp(26px, 3.8vw, 44px); font-weight: 800;
    text-transform: uppercase; line-height: 1.15;
    margin: 0 0 20px; color: #fff;
}
.inst-hero-desc  { font-size: 15px; color: rgba(255,255,255,.80); margin-bottom: 10px; line-height: 1.65; max-width: 580px; }
.inst-hero-note  { font-size: 12px; font-style: italic; color: rgba(255,255,255,.52); margin-bottom: 28px; }
.inst-hero-btns  { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; }

/* ── BUTTONS ── */
.btn-io {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--orange,#e8651a); color: #fff;
    border: none; border-radius: 7px;
    padding: 11px 22px; font-size: 13px; font-weight: 700;
    cursor: pointer; text-decoration: none; transition: opacity .18s;
}
.btn-io:hover      { opacity: .86; color: #fff; }
.btn-io.lg         { padding: 13px 28px; font-size: 14px; }
.btn-io.full       { width: 100%; justify-content: center; }
.btn-io-ghost {
    display: inline-flex; align-items: center; gap: 6px;
    background: transparent; color: #fff;
    border: 1.5px solid #fff; border-radius: 7px;
    padding: 11px 22px; font-size: 13px; font-weight: 700;
    text-decoration: none; transition: all .18s;
}
.btn-io-ghost:hover { background: rgba(255,255,255,.10); color: #fff; }

/* ── PAIN-POINT CHECK CARDS ── */
.io-check-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(min(100%, 340px), 1fr));
    gap: 10px; margin-bottom: 32px;
}
.io-check-card {
    display: flex; align-items: flex-start; gap: 10px;
    border: 1px solid #e5e7eb; border-radius: 10px;
    padding: 13px 16px; background: #fff;
}
.io-check-card i    { color: var(--orange,#e8651a); font-size: 14px; margin-top: 2px; flex-shrink:0; }
.io-check-card span { font-size: 13px; font-weight: 600; color: #111827; }

/* ── SERVICE CARDS ── */
.io-services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(min(100%, 420px), 1fr));
    gap: 22px;
}
.io-service-card {
    display: flex; flex-direction: column;
    background: #fff; border-radius: 16px;
    border: 1px solid rgba(0,0,0,.06);
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
    padding: 26px;
}
.io-service-icon  { font-size: 26px; color: var(--orange,#e8651a); margin-bottom: 14px; }
.io-service-title { font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 14px; font-family: Georgia, serif; }
.io-service-list  { list-style: none; padding: 0; margin: 0 0 20px; flex: 1; }
.io-service-list li {
    display: flex; align-items: center; gap: 7px;
    font-size: 12.5px; color: #64748b; margin-bottom: 7px;
}
.io-service-list li i { color: var(--orange,#e8651a); font-size: 12px; flex-shrink:0; }

/* ── DIFFERENTIATORS ── */
.io-diff-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(min(100%, 180px), 1fr));
    gap: 14px; margin-bottom: 32px;
}
.io-diff-card {
    display: flex; flex-direction: column; align-items: center;
    text-align: center; padding: 22px 16px; gap: 8px;
    border-radius: 12px; border: 1px solid rgba(0,0,0,.08);
    box-shadow: 0 1px 5px rgba(0,0,0,.05); background: #fff;
}
.io-diff-icon  { font-size: 24px; color: var(--orange,#e8651a); }
.io-diff-label { font-size: 12.5px; font-weight: 700; color: #111827; line-height: 1.35; }
.io-diff-sub   { font-size: 11.5px; color: #64748b; line-height: 1.55; }

/* ── STEPS ── */
.io-steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(min(100%, 155px), 1fr));
    gap: 22px; margin-bottom: 36px;
}
.io-step       { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 10px; }
.io-step-num   {
    width: 54px; height: 54px; border-radius: 50%;
    background: var(--orange,#e8651a); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; font-weight: 800;
    box-shadow: 0 2px 8px rgba(0,0,0,.14);
}
.io-step-label { font-size: 12.5px; font-weight: 700; color: #111827; line-height: 1.4; }

/* ── AUDIENCE TAGS ── */
.io-audience-wrap { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; }
.io-audience-tag {
    background: rgba(232,101,26,.09); color: var(--orange,#e8651a);
    border: 1px solid rgba(232,101,26,.22);
    font-weight: 700; font-size: 12.5px;
    padding: 7px 18px; border-radius: 999px;
}

@media (max-width: 600px) {
    .inst-hero-inner { padding: 60px 20px; }
}
</style>


<!-- ══ A) HERO ═══════════════════════════════════════════════════════════ -->
<section class="inst-hero">
    <div class="inst-hero-bg"></div>
    <div class="inst-hero-overlay"></div>
    <div class="inst-hero-dots"></div>
    <div class="inst-hero-inner">
        <div class="inst-hero-eyebrow">INSTITUTION ENABLEMENT</div>
        <h1 class="inst-hero-title">
            Academic Deployment &amp;<br>Institutional Partnerships
        </h1>
        <p class="inst-hero-desc">
            Empowering institutions, EdTech platforms, research organizations, and professional bodies with
            verified academic talent, structured deployment, and governance-aligned academic support
        </p>
        <p class="inst-hero-note">
            We are not a staffing agency. We are an academic coordination and deployment infrastructure.
        </p>
        <div class="inst-hero-btns">
            <a href="#partner-form" class="btn-io lg">Request Academic Support &nbsp;→</a>
            <a href="#partner-form" class="btn-io-ghost">Become an Institutional Partner</a>
        </div>
    </div>
</section>


<!-- ══ B) CHALLENGES WE SOLVE ════════════════════════════════════════════ -->
<section class="inst-section">
    <div class="inst-wrap-md">
        <div class="inst-center" style="margin-bottom:32px">
            <div class="inst-eyebrow">CHALLENGES WE SOLVE</div>
            <h2 class="inst-title">Why Institution Partners with Afrika Scholar</h2>
            <p class="inst-sub">Common institutional challenges our deployment infrastructure is built to address.</p>
        </div>

        <?php $painPoints = [
            'Accreditation requirements demanding qualified lecturers',
            'Urgent program expansion without faculty capacity',
            'EdTech licensing needing named academic leads',
            'Inconsistent teaching quality across modules',
            'Research capacity gaps',
            'Curriculum requiring validation or redesign',
        ]; ?>

        <div class="io-check-grid">
            <?php foreach ($painPoints as $p): ?>
            <div class="io-check-card">
                <i class="fas fa-check-circle"></i>
                <span><?= htmlspecialchars($p) ?></span>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="inst-center">
            <a href="#partner-form" class="btn-io lg">Request Support Now &nbsp;→</a>
        </div>
    </div>
</section>


<!-- ══ C) WHAT YOU CAN ACCESS ════════════════════════════════════════════ -->
<section class="inst-section-alt">
    <div class="inst-wrap" style="max-width:960px">
        <div class="inst-center" style="margin-bottom:32px">
            <h2 class="inst-title">What You Can Access Through Our Network</h2>
            <p class="inst-sub">Structured academic services designed for institutional deployment.</p>
        </div>

        <?php $serviceBlocks = [
            ['fas fa-graduation-cap', 'Teaching & Academy Delivery', [
                'Part-time or short-term teaching',
                'Online and blended delivery for EdTech platforms',
                'Guest lectures and masterclasses',
                'Specialist module instruction',
                'Academic supervision and mentoring',
            ], 'Request Teaching Support'],
            ['fas fa-search', 'Research & Scholarly Engagement', [
                'Institutional and industry-linked research',
                'Policy and applied research development',
                'Cross-institutional research collaborations',
                'Research advisory and validation roles',
                'Subject-matter expert consultations',
            ], 'Request Research Support'],
            ['fas fa-award', 'Peer Review & Academic Validation', [
                'Journal peer review support',
                'Editorial advisory services',
                'Academic quality assurance',
                'Independent research validation',
                'Content verification and review',
            ], 'Request Validation Support'],
            ['fas fa-book-open', 'Curriculum & Content Development', [
                'Curriculum design and academic review',
                'Development of structured learning materials',
                'Knowledge translation from academia to industry',
                'Accreditation-aligned program structuring',
                'Academic governance alignment',
            ], 'Request Curriculum Support'],
        ]; ?>

        <div class="io-services-grid">
            <?php foreach ($serviceBlocks as [$icon, $title, $items, $cta]): ?>
            <div class="io-service-card">
                <div class="io-service-icon"><i class="<?= $icon ?>"></i></div>
                <div class="io-service-title"><?= htmlspecialchars($title) ?></div>
                <ul class="io-service-list">
                    <?php foreach ($items as $item): ?>
                    <li><i class="fas fa-check-circle"></i><?= htmlspecialchars($item) ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="#partner-form" class="btn-io full"><?= htmlspecialchars($cta) ?> &nbsp;→</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<!-- ══ D) WHAT MAKES AFRIKA SCHOLAR DIFFERENT ════════════════════════════ -->
<section class="inst-section">
    <div class="inst-wrap-md">
        <div class="inst-center" style="margin-bottom:32px">
            <div class="inst-eyebrow">OUR DIFFERENCE</div>
            <h2 class="inst-title">What Makes Afrika Scholar Different</h2>
        </div>

        <?php $differentiators = [
            ['fas fa-shield-alt',    'Verified Academic Credentials',       'All lecturers and researchers are vetted and verified before inclusion in the network.'],
            ['fas fa-briefcase',     'Structured Academic Deployment',       'We do not simply connect — we coordinate, structure, and align engagements.'],
            ['fas fa-balance-scale', 'Governance & Compliance Alignment',    'Our model supports accreditation, licensing, and academic integrity requirements.'],
            ['fas fa-building',      'Pan-African Reach',                    'Access expertise across disciplines and countries within Africa.'],
            ['fas fa-globe',         'Ethical & Professional Framework',     'We protect both institutions and academics through structured engagement protocols.'],
        ]; ?>

        <div class="io-diff-grid">
            <?php foreach ($differentiators as [$icon, $label, $sub]): ?>
            <div class="io-diff-card">
                <div class="io-diff-icon"><i class="<?= $icon ?>"></i></div>
                <div class="io-diff-label"><?= htmlspecialchars($label) ?></div>
                <div class="io-diff-sub"><?= htmlspecialchars($sub) ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="inst-center">
            <a href="#how-engagement" class="btn-io lg">See How Engagement Works &nbsp;→</a>
        </div>
    </div>
</section>


<!-- ══ E) HOW THE PARTNERSHIP WORKS ══════════════════════════════════════ -->
<section id="how-engagement" class="inst-section-alt">
    <div class="inst-wrap-md">
        <div class="inst-center" style="margin-bottom:40px">
            <div class="inst-eyebrow">PROCESS</div>
            <h2 class="inst-title">How the Partnership Works</h2>
            <p class="inst-sub">A transparent, structured process from first contact to active deployment.</p>
        </div>

        <?php $steps = [
            'Submit Your Academic Requirement',
            'Define Scope, Duration &amp; Expertise Needed',
            'We Match Verified Academic Partners',
            'Structured Coordination &amp; Engagement',
            'Ongoing Oversight &amp; Quality Assurance',
        ]; ?>

        <div class="io-steps-grid">
            <?php foreach ($steps as $i => $step): ?>
            <div class="io-step">
                <div class="io-step-num"><?= $i + 1 ?></div>
                <div class="io-step-label"><?= $step ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="inst-center">
            <a href="#partner-form" class="btn-io lg">Start Request &nbsp;→</a>
        </div>
    </div>
</section>


<!-- ══ F) WHO THIS IS FOR ════════════════════════════════════════════════ -->
<section class="inst-section">
    <div class="inst-wrap">
        <div class="inst-center" style="margin-bottom:28px">
            <div class="inst-eyebrow">OUR PARTNERS</div>
            <h2 class="inst-title">Who This Is For</h2>
        </div>

        <?php $audiences = [
            'Universities and Higher Education Institutions',
            'EdTech Platforms & Online Universities',
            'Professional & Certification Bodies',
            'Research & Policy Organizations',
            'International Institutions operating in Africa',
        ]; ?>

        <div class="io-audience-wrap">
            <?php foreach ($audiences as $a): ?>
            <span class="io-audience-tag"><?= htmlspecialchars($a) ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
