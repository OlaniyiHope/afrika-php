<?php
$page_title = 'Academic Network';
require_once 'helpers.php';

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply'])) {
    $name       = trim($_POST['name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $institution= trim($_POST['institution'] ?? '');
    $discipline = trim($_POST['discipline'] ?? '');
    $cv         = trim($_POST['cv_summary'] ?? '');

    if (!$name)       $errors[] = 'Full name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if (!$institution) $errors[] = 'Institution is required.';
    if (!$discipline)  $errors[] = 'Discipline is required.';

    if (empty($errors)) {
        $id = get_next_id('network_applications.csv');
        append_csv('network_applications.csv', [$id, $name, $email, $institution, $discipline, $cv, date('Y-m-d'), 'pending']);
        $success = true;
    }
}

include 'header.php';
?>

<style>
/* ══ NETWORK PAGE — scoped styles ══════════════════════════════════════ */

.nw-title {
    font-size: clamp(22px, 3vw, 34px);
    font-weight: 700;
    color: #111827;
    margin: 0 0 16px;
    line-height: 1.25;
    font-family: Georgia, 'Times New Roman', serif;
}
.nw-sub {
    font-size: 14px; color: #64748b;
    max-width: 560px; margin: 0 auto; line-height: 1.65;
}
.nw-center { text-align: center; }

/* Layout */
.nw-section     { padding: 64px 20px; background: #fff; }
.nw-section-alt { padding: 64px 20px; background: #f1f5f9; }
.nw-section-dark{
    position: relative; overflow: hidden;
    padding: 80px 20px; background: #0f1f3d;
}
.nw-wrap    { max-width: 1060px; margin: 0 auto; }
.nw-wrap-md { max-width: 900px;  margin: 0 auto; }
.nw-wrap-sm { max-width: 700px;  margin: 0 auto; }

/* ── HERO ── */
.nw-hero {
    position: relative; min-height: 520px;
    display: flex; align-items: center; overflow: hidden;
}
.nw-hero-bg {
    position: absolute; inset: 0;
    background-image: url('asset/network-collaboration.jpg');
    background-size: cover; background-position: center;
    z-index: 0;
}
.nw-hero-overlay {
    position: absolute; inset: 0;
    background: rgba(10, 22, 55, 0.85); z-index: 1;
}
.nw-hero-dots {
    position: absolute; inset: 0; z-index: 2; opacity: .07;
    background-image: radial-gradient(circle, #fff 1px, transparent 1px);
    background-size: 20px 20px;
}
.nw-hero-inner {
    position: relative; z-index: 3;
    max-width: 760px; padding: 80px 40px; color: #fff;
}
.nw-hero-title {
    font-size: clamp(26px, 3.8vw, 44px); font-weight: 800;
    line-height: 1.15; margin: 0 0 22px; color: #fff;
}
.nw-hero-desc {
    font-size: 15px; color: rgba(255,255,255,.80);
    margin-bottom: 28px; line-height: 1.7; max-width: 620px;
}

/* ── BUTTONS ── */
.btn-nw {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--orange,#e8651a); color: #fff;
    border: none; border-radius: 7px;
    padding: 11px 22px; font-size: 13px; font-weight: 700;
    cursor: pointer; text-decoration: none; transition: opacity .18s;
}
.btn-nw:hover { opacity: .86; color: #fff; }
.btn-nw.lg    { padding: 13px 28px; font-size: 14px; }
.btn-nw.full  { width: 100%; justify-content: center; }

/* ── CHECK LIST ── */
.nw-check-list { list-style: none; padding: 0; margin: 0; }
.nw-check-list li {
    display: flex; align-items: flex-start; gap: 10px;
    font-size: 14px; color: #374151; margin-bottom: 10px; line-height: 1.5;
}
.nw-check-list li i,
.nw-check-list li .nw-ci {
    color: var(--orange,#e8651a); font-size: 15px; flex-shrink:0; margin-top:2px;
}
.nw-check-list.sm li { font-size: 13px; color: #64748b; }

/* ── ICON SQUARE (rounded bg) ── */
.nw-icon-sq {
    width: 40px; height: 40px; border-radius: 8px;
    background: rgba(232,101,26,.10);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.nw-icon-sq i { color: var(--orange,#e8651a); font-size: 16px; }
.nw-icon-sq.navy { background: rgba(15,31,61,.10); }
.nw-icon-sq.navy i { color: #0f1f3d; }

/* ── PROVIDES CARDS ── */
.nw-provides-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(min(100%, 260px), 1fr));
    gap: 16px;
}
.nw-provides-card {
    display: flex; align-items: flex-start; gap: 14px;
    background: #fff; border: 1px solid #e5e7eb; border-radius: 12px;
    padding: 20px; transition: box-shadow .18s;
}
.nw-provides-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,.09); }
.nw-provides-card span { font-size: 14px; font-weight: 600; color: #111827; }

/* ── ENGAGEMENT CARDS ── */
.nw-eng-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(min(100%, 400px), 1fr));
    gap: 22px;
}
.nw-eng-card {
    background: #fff; border: 1px solid #e5e7eb; border-radius: 12px;
    padding: 24px; transition: box-shadow .18s;
}
.nw-eng-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.09); }
.nw-eng-card-header {
    display: flex; align-items: center; gap: 12px; margin-bottom: 16px;
}
.nw-eng-card-title { font-size: 15px; font-weight: 700; color: #111827; }

/* ── VALUE CARDS ── */
.nw-value-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(min(100%, 400px), 1fr));
    gap: 24px;
}
.nw-value-card {
    background: #fff; border: 1px solid rgba(232,101,26,.18); border-radius: 12px; padding: 28px;
    transition: box-shadow .18s;
}
.nw-value-card.navy-border { border-color: rgba(15,31,61,.15); }
.nw-value-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.09); }
.nw-value-card-header {
    display: flex; align-items: center; gap: 12px; margin-bottom: 18px;
}
.nw-value-card-title { font-size: 16px; font-weight: 700; color: #111827; }

/* ── WHO + HOW GRID ── */
.nw-who-how-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(min(100%, 400px), 1fr));
    gap: 48px;
}
.nw-step {
    display: flex; align-items: flex-start; gap: 14px;
    background: #f1f5f9; border-radius: 10px; padding: 16px;
    margin-bottom: 12px;
}
.nw-step-num {
    width: 32px; height: 32px; border-radius: 50%;
    background: var(--orange,#e8651a); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700; flex-shrink: 0;
}
.nw-step span { font-size: 14px; font-weight: 600; color: #111827; margin-top: 4px; }

/* ── IMPORTANT NOTE ── */
.nw-note-card {
    max-width: 860px; margin: 0 auto;
    background: #fff; border: 1px solid rgba(232,101,26,.2);
    border-radius: 12px; padding: 32px;
    display: flex; align-items: flex-start; gap: 16px;
}
.nw-note-card i { color: var(--orange,#e8651a); font-size: 26px; flex-shrink:0; margin-top:2px; }
.nw-note-card h4 { font-size: 16px; font-weight: 700; margin-bottom: 8px; color: #111827; }
.nw-note-card p  { font-size: 14px; color: #64748b; line-height: 1.7; margin: 0; }

/* ── CTA SECTION dot grid ── */
.nw-cta-dots {
    position: absolute; inset: 0; opacity: .08;
    background-image: radial-gradient(circle, #fff 1px, transparent 1px);
    background-size: 12px 12px; z-index: 0;
}

/* ── APPLY FORM (dark section) ── */
.nw-apply-section {
    padding: 80px 20px;
    background: #0f1f3d;
    position: relative; overflow: hidden;
}
.nw-apply-dots {
    position: absolute; inset: 0; opacity: .07;
    background-image: radial-gradient(circle, #fff 1px, transparent 1px);
    background-size: 14px 14px; z-index: 0;
}

@media (max-width: 600px) {
    .nw-hero-inner { padding: 60px 20px; }
    .nw-apply-section { padding: 60px 20px; }
}
</style>


<!-- ══ HERO ══════════════════════════════════════════════════════════════ -->
<section class="nw-hero">
    <div class="nw-hero-bg"></div>
    <div class="nw-hero-overlay"></div>
    <div class="nw-hero-dots"></div>
    <div class="nw-hero-inner">
        <h1 class="nw-hero-title">
            Earn Beyond the Classroom. Work With Global Institutions. Extend Your Academic Impact.
        </h1>
        <p class="nw-hero-desc">
            The Afrika Scholar Lecturer &amp; Academic Partners Network is a curated academic collaboration
            program for lecturers, professors, researchers, and academically qualified professionals who want
            to extend their impact beyond their primary institutions.
        </p>
        <a href="#apply" class="btn-nw lg">Apply to Join the Network &nbsp;→</a>
    </div>
</section>


<!-- ══ WHY THIS NETWORK EXISTS ══════════════════════════════════════════ -->
<section class="nw-section" id="why">
    <div class="nw-wrap">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(min(100%,440px),1fr));gap:48px;align-items:center;max-width:960px;margin:0 auto">
            <div>
                <h2 class="nw-title">Why This Network Exists</h2>
                <p style="font-size:14px;color:#64748b;line-height:1.7;margin-bottom:20px">
                    Across Africa, thousands of highly qualified academics possess deep expertise that is
                    underutilised beyond their home institutions. At the same time, institutions struggle to access:
                </p>
                <ul class="nw-check-list">
                    <?php foreach ([
                        'Verified lecturers required for accreditation or licensing',
                        'Qualified academics for short-term or part-time teaching',
                        'Researchers for institutional, policy, or industry-linked projects',
                        'Peer reviewers and academic validators',
                        'Subject experts for curriculum and content development',
                    ] as $item): ?>
                    <li><i class="fas fa-check-circle"></i><?= htmlspecialchars($item) ?></li>
                    <?php endforeach; ?>
                </ul>
                <p style="font-size:14px;color:#64748b;line-height:1.7;margin-top:18px;font-weight:600">
                    The Network bridges this gap — unlocking opportunity for academics while providing institutions with trusted academic capacity.
                </p>
            </div>
            <!-- Right: real photo with decorative accent -->
            <div style="position:relative">
                <div style="border-radius:16px;overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,.16)">
                    <img src="asset/hero-scholars.jpg"
                         alt="Scholars collaborating"
                         style="width:100%;height:400px;object-fit:cover;display:block"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <!-- Fallback if image missing -->
                    <div style="display:none;height:400px;background:linear-gradient(135deg,#c87941,#8B5E3C);align-items:center;justify-content:center">
                        <i class="fas fa-users" style="font-size:80px;color:rgba(255,255,255,.3)"></i>
                    </div>
                </div>
                <div style="position:absolute;bottom:-14px;right:-14px;width:80px;height:80px;border-radius:14px;background:rgba(232,101,26,.18);z-index:-1"></div>
            </div>
        </div>
    </div>
</section>


<!-- ══ WHAT AFRIKA SCHOLAR PROVIDES ════════════════════════════════════ -->
<section class="nw-section-alt">
    <div class="nw-wrap-md">
        <div class="nw-center" style="margin-bottom:40px">
            <h2 class="nw-title">What Afrika Scholar Provides</h2>
            <p class="nw-sub">Afrika Scholar manages the Network as a curated academic ecosystem, not an open marketplace.</p>
        </div>
        <?php $provides = [
            ['fas fa-shield-alt',    'Verify academic credentials'],
            ['fas fa-users',         'Maintain structured academic profiles'],
            ['fas fa-search',        'Match academics to relevant opportunities'],
            ['fas fa-briefcase',     'Coordinate engagements professionally'],
            ['fas fa-check-circle',  'Enforce ethical and academic standards'],
            ['fas fa-star',          'Protect both academic and institution'],
        ]; ?>
        <div class="nw-provides-grid">
            <?php foreach ($provides as [$icon, $text]): ?>
            <div class="nw-provides-card">
                <div class="nw-icon-sq"><i class="<?= $icon ?>"></i></div>
                <span><?= htmlspecialchars($text) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<!-- ══ TYPES OF ENGAGEMENTS ════════════════════════════════════════════ -->
<section class="nw-section" id="engagements">
    <div class="nw-wrap-md">
        <div class="nw-center" style="margin-bottom:40px">
            <h2 class="nw-title">Types of Engagements Available</h2>
            <p class="nw-sub">Approved Academic Partners may be engaged across four core areas</p>
        </div>
        <?php $engagements = [
            ['fas fa-graduation-cap', 'Teaching & Academic Delivery', [
                'Part-time or short-term lecturing',
                'Online and blended teaching for EdTech platforms',
                'Guest lectures, masterclasses, and specialist modules',
                'Academic supervision and mentoring',
            ]],
            ['fas fa-search', 'Research & Scholarly Engagement', [
                'Institutional and industry-linked research',
                'Policy, technical, and applied research',
                'Collaborative research across institutions',
                'Research advisory and validation roles',
            ]],
            ['fas fa-file-edit', 'Peer Review & Editorial Roles', [
                'Peer review for Afrika Scholar journals',
                'Editorial and review support for partner publications',
                'Academic quality assurance and validation',
            ]],
            ['fas fa-book-open', 'Curriculum & Content Development', [
                'Curriculum design and academic review',
                'Development of learning materials and academic content',
                'Knowledge transfer from academia to industry-aligned programs',
            ]],
        ]; ?>
        <div class="nw-eng-grid">
            <?php foreach ($engagements as $idx => [$icon, $title, $items]): ?>
            <div class="nw-eng-card">
                <div class="nw-eng-card-header">
                    <div class="nw-icon-sq"><i class="<?= $icon ?>"></i></div>
                    <div class="nw-eng-card-title"><?= $idx + 1 ?>. <?= htmlspecialchars($title) ?></div>
                </div>
                <ul class="nw-check-list sm">
                    <?php foreach ($items as $item): ?>
                    <li><i class="fas fa-check-circle"></i><?= htmlspecialchars($item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="nw-center" style="margin-top:36px">
            <a href="#apply" class="btn-nw lg">Apply to Explore the Network &nbsp;→</a>
        </div>
    </div>
</section>


<!-- ══ HOW VALUE IS CREATED ════════════════════════════════════════════ -->
<section class="nw-section-alt" id="institutions">
    <div class="nw-wrap-md">
        <div class="nw-center" style="margin-bottom:40px">
            <h2 class="nw-title">How Value Is Created</h2>
            <p class="nw-sub">Afrika Scholar operates a reciprocal value model.</p>
        </div>
        <div class="nw-value-grid">
            <div class="nw-value-card">
                <div class="nw-value-card-header">
                    <div class="nw-icon-sq"><i class="fas fa-graduation-cap"></i></div>
                    <div class="nw-value-card-title">Academic Partners Receive</div>
                </div>
                <ul class="nw-check-list sm">
                    <?php foreach ([
                        'Priority consideration for paid external engagements',
                        'Increased visibility across institutional and partner networks',
                        'Preferential access to Afrika Scholar services',
                    ] as $item): ?>
                    <li><i class="fas fa-check-circle"></i><?= htmlspecialchars($item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="nw-value-card navy-border">
                <div class="nw-value-card-header">
                    <div class="nw-icon-sq navy"><i class="fas fa-briefcase"></i></div>
                    <div class="nw-value-card-title">Partner Institutions Receive</div>
                </div>
                <ul class="nw-check-list sm">
                    <?php foreach ([
                        'Access to verified, Africa-based academic talent',
                        'Structured academic deployment',
                        'Support for accreditation, licensing, and research needs',
                    ] as $item): ?>
                    <li><i class="fas fa-check-circle" style="color:#0f1f3d"></i><?= htmlspecialchars($item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>


<!-- ══ WHO CAN JOIN + HOW TO JOIN ══════════════════════════════════════ -->
<section class="nw-section" id="who-can-join">
    <div class="nw-wrap-md">
        <div class="nw-who-how-grid">
            <!-- WHO -->
            <div>
                <h2 class="nw-title">Who Can Join</h2>
                <p style="font-size:14px;color:#64748b;margin-bottom:16px">The Network is open to:</p>
                <ul class="nw-check-list" style="margin-bottom:16px">
                    <?php foreach ([
                        'University lecturers and professors',
                        'Postdoctoral researchers and senior PhD holders',
                        'Academics seeking project-based engagements',
                        'Qualified professionals contributing to academic delivery',
                    ] as $item): ?>
                    <li><i class="fas fa-check-circle"></i><?= htmlspecialchars($item) ?></li>
                    <?php endforeach; ?>
                </ul>
                <p style="font-size:12px;color:#94a3b8;font-style:italic">
                    Participation is selective and subject to credential verification.
                </p>
            </div>
            <!-- HOW -->
            <div>
                <h2 class="nw-title">How to Join</h2>
                <div style="margin-bottom:16px">
                    <?php foreach ([
                        'Submit an expression of interest',
                        'Provide academic and professional credentials',
                        'Indicate areas of expertise and engagement preferences',
                    ] as $i => $step): ?>
                    <div class="nw-step">
                        <div class="nw-step-num"><?= $i + 1 ?></div>
                        <span><?= htmlspecialchars($step) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <p style="font-size:13px;color:#64748b;margin-bottom:20px">
                    Approved applicants are onboarded and contacted when relevant opportunities arise.
                </p>
                <a href="#apply" class="btn-nw">Apply to Join the Network &nbsp;→</a>
            </div>
        </div>
    </div>
</section>


<!-- ══ IMPORTANT NOTE ══════════════════════════════════════════════════ -->
<section class="nw-section-alt" style="padding:48px 20px">
    <div class="nw-note-card">
        <i class="fas fa-shield-alt"></i>
        <div>
            <h4>Important Note</h4>
            <p>Afrika Scholar is not an employer and does not replace a lecturer's primary institution. We operate as an academic coordination and enablement platform, ensuring all engagements are ethical, compliant, and professionally aligned.</p>
        </div>
    </div>
</section>


<!-- ══ A STRONGER ACADEMIC ECOSYSTEM (CTA) ═════════════════════════════ -->
<section class="nw-section-dark" id="partnerships">
    <div class="nw-cta-dots"></div>
    <div style="position:relative;z-index:1;max-width:680px;margin:0 auto;text-align:center;color:#fff">
        <i class="fas fa-globe" style="font-size:44px;color:var(--orange,#e8651a);margin-bottom:20px;display:block"></i>
        <h2 style="font-size:clamp(24px,3vw,34px);font-weight:800;margin-bottom:14px;color:#fff">
            A Stronger Academic Ecosystem
        </h2>
        <p style="font-size:15px;color:rgba(255,255,255,.80);margin-bottom:16px">
            Through the Network, Afrika Scholar is:
        </p>
        <ul class="nw-check-list" style="max-width:520px;margin:0 auto 28px;text-align:left">
            <?php foreach ([
                'Creating structured income opportunities for African academics',
                'Supporting institutions with credible academic capacity',
                'Bridging teaching, research, and professional practice',
                'Ensuring African expertise plays a central role in global knowledge systems',
            ] as $item): ?>
            <li style="color:rgba(255,255,255,.90)">
                <i class="fas fa-check-circle" style="color:var(--orange,#e8651a)"></i>
                <?= htmlspecialchars($item) ?>
            </li>
            <?php endforeach; ?>
        </ul>
        <p style="color:var(--orange,#e8651a);font-weight:700;font-size:16px;margin-bottom:28px">
            Afrika Scholar — where African academic expertise meets global opportunity.
        </p>
        <a href="#apply" class="btn-nw lg">Apply to Join Network &nbsp;→</a>
    </div>
</section>


<!-- ══ APPLY FORM (backend untouched) ══════════════════════════════════ -->
<?php include 'footer.php'; ?>
