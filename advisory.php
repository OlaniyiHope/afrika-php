<?php
$page_title = 'Advisory Services';
require_once 'helpers.php';

$step = (int)($_POST['step'] ?? $_GET['step'] ?? 1);
$success = false;
$errors = [];

$data = [
    'full_name' => trim($_POST['full_name'] ?? ''),
    'email'     => trim($_POST['email'] ?? ''),
    'phone'     => trim($_POST['phone'] ?? ''),
    'country'   => trim($_POST['country'] ?? ''),
    'advisory_type' => trim($_POST['advisory_type'] ?? ''),
    'institution'   => trim($_POST['institution'] ?? ''),
    'engagement'    => trim($_POST['engagement'] ?? ''),
    'additional'    => trim($_POST['additional'] ?? ''),
    'urgency'       => trim($_POST['urgency'] ?? ''),
    'agree'         => isset($_POST['agree']) ? '1' : '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['next_step'])) {
        $next = (int)$_POST['next_step'];
        if ($next === 2) {
            if (!$data['full_name']) $errors[] = 'Full name is required.';
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
            if (!$data['phone'])   $errors[] = 'Phone number is required.';
            if (!$data['country']) $errors[] = 'Country is required.';
            if (empty($errors)) $step = 2;
        } elseif ($next === 3) {
            if (!$data['advisory_type']) $errors[] = 'Please select an advisory type.';
            if (empty($errors)) $step = 3;
        } elseif ($next === 4) {
            $step = 4;
        }
    } elseif (isset($_POST['submit_final'])) {
        if (!$data['agree']) {
            $errors[] = 'You must accept the terms to proceed.';
            $step = 4;
        } else {
            $id = get_next_id('network_applications.csv');
            append_csv('network_applications.csv', [
                $id, $data['full_name'], $data['email'],
                $data['institution'] ?: 'N/A', $data['advisory_type'],
                $data['additional'], date('Y-m-d'), 'advisory'
            ]);
            $success = true;
            $step = 5;
        }
    } elseif (isset($_POST['prev_step'])) {
        $step = max(1, (int)$_POST['prev_step']);
        $errors = [];
    }
}

include 'header.php';
?>

<style>
/* ══ ADVISORY PAGE ═══════════════════════════════════════════════════ */

/* Hero */
.adv-hero {
    position: relative; min-height: 420px;
    display: flex; align-items: center; justify-content: center; overflow: hidden;
}
.adv-hero-bg {
    position: absolute; inset: 0;
    background-image: url('asset/about-conference.jpg');
    background-size: cover; background-position: center; z-index: 0;
}
.adv-hero-overlay { position: absolute; inset: 0; background: rgba(10,22,55,.85); z-index: 1; }
.adv-hero-dots {
    position: absolute; inset: 0; z-index: 2; opacity: .10;
    background-image: radial-gradient(circle, #fff 1px, transparent 1px);
    background-size: 16px 16px;
}
.adv-hero-inner {
    position: relative; z-index: 3; text-align: center;
    color: #fff; padding: 80px 20px; max-width: 760px;
}
.adv-hero-eyebrow {
    font-size: 11px; font-weight: 700; letter-spacing: .14em;
    text-transform: uppercase; color: var(--orange,#e8651a); margin-bottom: 14px;
}
.adv-hero-title { font-size: clamp(28px,5vw,48px); font-weight: 800; margin: 0 0 18px; line-height: 1.15; color: #fff; }
.adv-hero-sub   { font-size: 17px; color: rgba(255,255,255,.80); margin: 0; line-height: 1.6; }

/* What We Offer cards */
.adv-cards-grid { display: grid; grid-template-columns: repeat(auto-fill,minmax(min(100%,360px),1fr)); gap: 24px; max-width: 900px; margin: 0 auto; }
.adv-card {
    background: #fff; border: 1px solid #e5e7eb; border-radius: 16px;
    padding: 28px; display: flex; flex-direction: column;
    transition: box-shadow .18s, transform .18s;
}
.adv-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.10); transform: translateY(-2px); }
.adv-card.dimmed { opacity: .75; }
.adv-card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
.adv-card-icon { width: 48px; height: 48px; border-radius: 10px; background: rgba(232,101,26,.1); display: flex; align-items: center; justify-content: center; color: var(--orange,#e8651a); font-size: 20px; }
.adv-soon-badge { font-size: 11px; background: #f1f5f9; border: 1px solid #e5e7eb; border-radius: 999px; padding: 3px 10px; color: #64748b; font-weight: 600; }
.adv-card-title { font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 8px; }
.adv-card-desc  { font-size: 14px; color: #64748b; line-height: 1.6; flex: 1; margin-bottom: 20px; }
.adv-card-btn {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--orange,#e8651a); color: #fff; border: none; border-radius: 8px;
    padding: 10px 20px; font-size: 13px; font-weight: 700; text-decoration: none;
    transition: opacity .15s; align-self: flex-start;
}
.adv-card-btn:hover { opacity: .88; color: #fff; }
.adv-card-btn.disabled {
    background: #f1f5f9; color: #9ca3af; border: 1px solid #e5e7eb; cursor: not-allowed;
}
.adv-card-btn.disabled:hover { opacity: 1; }

/* Alert / disclaimer */
.adv-alert {
    background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px;
    padding: 20px 24px; display: flex; gap: 14px; align-items: flex-start;
    margin-bottom: 32px; max-width: 860px; margin-left: auto; margin-right: auto;
}
.adv-alert-icon { color: #3b82f6; font-size: 18px; flex-shrink: 0; margin-top: 2px; }
.adv-alert-title { font-size: 14px; font-weight: 700; color: #1e40af; margin-bottom: 4px; }
.adv-alert-body  { font-size: 13px; color: #1e3a8a; line-height: 1.6; }

/* Do / Don't cards */
.adv-boundary-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; max-width: 860px; margin: 0 auto; }
.adv-boundary-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 28px; }
.adv-boundary-title { display: flex; align-items: center; gap: 10px; font-size: 15px; font-weight: 700; color: #111827; margin-bottom: 16px; }
.adv-boundary-title i.green { color: #16a34a; }
.adv-boundary-title i.amber { color: #d97706; }
.adv-boundary-list { list-style: none; padding: 0; margin: 0; }
.adv-boundary-list li { font-size: 13px; color: #374151; padding: 6px 0; border-bottom: 1px solid #f1f5f9; display: flex; align-items: flex-start; gap: 8px; }
.adv-boundary-list li:last-child { border-bottom: none; }
.adv-boundary-list li::before { content: '•'; color: #94a3b8; flex-shrink: 0; }

/* Request CTA */
.adv-request-cta { text-align: center; max-width: 680px; margin: 0 auto; padding: 80px 20px; }
.adv-request-cta h2 { font-size: clamp(24px,3vw,34px); font-weight: 800; margin-bottom: 14px; }
.adv-request-cta p  { font-size: 15px; color: #64748b; line-height: 1.7; margin-bottom: 28px; }
.adv-request-btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--orange,#e8651a); color: #fff; border: none; border-radius: 9px;
    padding: 14px 32px; font-size: 15px; font-weight: 700; text-decoration: none;
    transition: opacity .15s;
}
.adv-request-btn:hover { opacity: .88; color: #fff; }

/* Form section divider */
.adv-form-hero {
    position: relative; overflow: hidden; background: #0f1f3d;
    padding: 60px 20px; text-align: center; color: #fff;
}
.adv-form-hero-dots {
    position: absolute; inset: 0; opacity: .08;
    background-image: radial-gradient(circle,#fff 1px,transparent 1px);
    background-size: 12px 12px; z-index: 0;
}
.adv-form-hero-inner { position: relative; z-index: 1; max-width: 600px; margin: 0 auto; }
.adv-form-hero h2 { font-size: clamp(22px,3vw,32px); font-weight: 800; margin-bottom: 10px; color: #fff; }
.adv-form-hero p  { color: rgba(255,255,255,.75); font-size: 15px; }

@media (max-width: 700px) {
    .adv-boundary-grid { grid-template-columns: 1fr; }
}
</style>


<!-- ══ HERO ══════════════════════════════════════════════════════════════ -->
<section class="adv-hero">
    <div class="adv-hero-bg"></div>
    <div class="adv-hero-overlay"></div>
    <div class="adv-hero-dots"></div>
    <div class="adv-hero-inner">
        <div class="adv-hero-eyebrow">Advisory Services</div>
        <h1 class="adv-hero-title">Educational &amp; University Advisory</h1>
        <p class="adv-hero-sub">Expert guidance to navigate African university systems — transcripts, degrees, mobility, and institutional engagement.</p>
    </div>
</section>


<!-- ══ WHAT WE OFFER ═════════════════════════════════════════════════════ -->
<section class="section">
    <div class="section-center" style="margin-bottom:40px">
        <h2 class="section-title">What We Offer</h2>
        <p class="section-subtitle">Afrika Scholar's advisory services help you navigate the often complex processes of African higher education systems. We provide guidance, resources, and support to make your academic journey smoother.</p>
    </div>
    <div class="adv-cards-grid">

        <div class="adv-card">
            <div class="adv-card-top">
                <div class="adv-card-icon"><i class="fas fa-file-alt"></i></div>
            </div>
            <div class="adv-card-title">Transcript Advisory</div>
            <div class="adv-card-desc">Guidance on obtaining academic transcripts from Nigerian universities with step-by-step processes and institutional contacts.</div>
            <a href="transcript_advisory.php" class="adv-card-btn">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="adv-card">
            <div class="adv-card-top">
                <div class="adv-card-icon"><i class="fas fa-graduation-cap"></i></div>
            </div>
            <div class="adv-card-title">Degree Programs</div>
            <div class="adv-card-desc">Navigation support for part-time, Master's, and Doctoral programs at African universities.</div>
            <a href="degree_programs.php" class="adv-card-btn">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="adv-card dimmed">
            <div class="adv-card-top">
                <div class="adv-card-icon"><i class="fas fa-globe-africa"></i></div>
                <span class="adv-soon-badge">Coming Soon</span>
            </div>
            <div class="adv-card-title">Study in Africa</div>
            <div class="adv-card-desc">Academic mobility support for studying across African countries.</div>
            <span class="adv-card-btn disabled">Coming Soon</span>
        </div>

        <div class="adv-card dimmed">
            <div class="adv-card-top">
                <div class="adv-card-icon"><i class="fas fa-building"></i></div>
                <span class="adv-soon-badge">Coming Soon</span>
            </div>
            <div class="adv-card-title">Institutional Liaison</div>
            <div class="adv-card-desc">Facilitation of formal engagements between individuals and academic institutions.</div>
            <span class="adv-card-btn disabled">Coming Soon</span>
        </div>

    </div>
</section>


<!-- ══ POSITIONING & BOUNDARIES ══════════════════════════════════════════ -->
<section class="section" style="background:#f8f9ff;border-top:1px solid #e5e7eb">
    <div style="max-width:860px;margin:0 auto">

        <div class="adv-alert">
            <i class="fas fa-info-circle adv-alert-icon"></i>
            <div>
                <div class="adv-alert-title">Important Disclaimer</div>
                <div class="adv-alert-body">Afrika Scholar provides advisory and guidance services only. We are not a recruitment agency, degree mill, or credential verification authority. All formal processes must be completed directly with the relevant institutions.</div>
            </div>
        </div>

        <div class="adv-boundary-grid">
            <div class="adv-boundary-card">
                <div class="adv-boundary-title">
                    <i class="fas fa-check-circle green"></i> What We Do
                </div>
                <ul class="adv-boundary-list">
                    <li>Provide accurate, up-to-date process information</li>
                    <li>Share institutional contacts and requirements</li>
                    <li>Offer guidance on documentation and timelines</li>
                    <li>Connect you with relevant resources</li>
                    <li>Support you through complex processes</li>
                </ul>
            </div>
            <div class="adv-boundary-card">
                <div class="adv-boundary-title">
                    <i class="fas fa-exclamation-triangle amber"></i> What We Don't Do
                </div>
                <ul class="adv-boundary-list">
                    <li>Act as agents for universities</li>
                    <li>Guarantee admission or transcript issuance</li>
                    <li>Process applications on your behalf</li>
                    <li>Issue or verify credentials</li>
                    <li>Charge for third-party services</li>
                </ul>
            </div>
        </div>
    </div>
</section>


<!-- ══ REQUEST CTA ════════════════════════════════════════════════════════ -->
<section style="background:#fff;border-top:1px solid #e5e7eb">
    <div class="adv-request-cta">
        <h2>Request Advisory Support</h2>
        <p>Need personalized guidance? Submit a request and our team will connect you with the right resources and support for your specific situation.</p>
        <a href="#request-form" class="adv-request-btn">Request Advisory <i class="fas fa-arrow-right"></i></a>
    </div>
</section>


<!-- ══ FORM SECTION HEADER ════════════════════════════════════════════════ -->
<div class="adv-form-hero" id="request-form">
    <div class="adv-form-hero-dots"></div>
    <div class="adv-form-hero-inner">
        <h2>Advisory Request Form</h2>
        <p>Tell us about your needs and we'll provide personalized guidance for your academic journey.</p>
    </div>
</div>


<!-- ══ STEP INDICATOR ════════════════════════════════════════════════════ -->
<?php if ($step < 5): ?>
<div style="background:#fff;border-bottom:1px solid #e5e7eb;padding:20px">
    <div style="display:flex;align-items:center;max-width:700px;margin:0 auto">
        <?php
        $steps = ['Personal Info','Advisory Type','Details','Review'];
        foreach ($steps as $i => $label):
            $num = $i + 1;
            $done   = $num < $step;
            $active = $num === $step;
        ?>
        <div style="display:flex;align-items:center;flex:1">
            <div style="display:flex;align-items:center;gap:10px;white-space:nowrap">
                <div style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;
                    <?= $done ? 'background:var(--orange,#e8651a);color:#fff' : ($active ? 'background:var(--orange,#e8651a);color:#fff' : 'background:#f3f4f6;color:#9ca3af;border:2px solid #e5e7eb') ?>">
                    <?= $done ? '<i class="fas fa-check" style="font-size:11px"></i>' : $num ?>
                </div>
                <span style="font-size:13px;font-weight:<?= $active ? '700' : '500' ?>;color:<?= $active ? '#111827' : ($done ? 'var(--orange,#e8651a)' : '#9ca3af') ?>"><?= $label ?></span>
            </div>
            <?php if ($i < 3): ?>
            <div style="flex:1;height:2px;background:<?= $done ? 'var(--orange,#e8651a)' : '#e5e7eb' ?>;margin:0 12px"></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>


<!-- ══ FORM STEPS ════════════════════════════════════════════════════════ -->
<section class="section" style="background:#f8f9ff">
    <div style="max-width:700px;margin:0 auto">

        <?php if (!empty($errors)): ?>
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:16px;margin-bottom:20px;color:#dc2626;font-size:14px">
            <?= implode('<br>', array_map('htmlspecialchars', $errors)) ?>
        </div>
        <?php endif; ?>

        <?php if ($step === 5 || $success): ?>
        <!-- SUCCESS -->
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:60px;text-align:center">
            <div style="width:72px;height:72px;background:rgba(232,101,26,.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:32px;color:var(--orange,#e8651a)">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 style="font-size:24px;font-weight:800;margin-bottom:12px">Advisory Request Submitted!</h2>
            <p style="color:#64748b;font-size:15px;line-height:1.7;margin-bottom:32px">Thank you for reaching out. Our advisory team will review your request and contact you within 2–3 business days with personalized guidance.</p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
                <a href="advisory.php" class="btn btn-outline">Back to Advisory</a>
                <a href="index.php" class="btn btn-navy">Go to Home</a>
            </div>
        </div>

        <?php else: ?>
        <form method="POST">
            <?php foreach ($data as $k => $v): ?>
            <input type="hidden" name="<?= $k ?>" value="<?= htmlspecialchars($v) ?>">
            <?php endforeach; ?>

            <?php if ($step === 1): ?>
            <!-- STEP 1 -->
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:40px">
                <h3 style="font-size:20px;font-weight:700;margin-bottom:6px">Personal Information</h3>
                <p style="color:#64748b;font-size:14px;margin-bottom:28px">Tell us about yourself so we can personalise our guidance.</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($data['full_name']) ?>" placeholder="Enter your full name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email']) ?>" placeholder="your.email@example.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number *</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($data['phone']) ?>" placeholder="+234 800 000 0000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Country of Residence *</label>
                        <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($data['country']) ?>" placeholder="e.g., Nigeria">
                    </div>
                </div>
                <div style="display:flex;justify-content:flex-end;margin-top:20px">
                    <button type="submit" name="next_step" value="2" class="btn btn-navy">Next <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <?php elseif ($step === 2): ?>
            <!-- STEP 2 -->
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:40px">
                <h3 style="font-size:20px;font-weight:700;margin-bottom:6px">Advisory Type</h3>
                <p style="color:#64748b;font-size:14px;margin-bottom:28px">What kind of advisory support do you need?</p>
                <div style="display:flex;flex-direction:column;gap:12px">
                    <?php foreach (['Transcript Advisory','Degree Programs','Study in Africa','Institutional Liaison','General Academic Guidance'] as $type): ?>
                    <label style="display:flex;align-items:center;gap:14px;padding:16px;border:2px solid <?= $data['advisory_type']===$type?'var(--orange,#e8651a)':'#e5e7eb' ?>;border-radius:10px;cursor:pointer;transition:border-color .15s">
                        <input type="radio" name="advisory_type" value="<?= $type ?>" <?= $data['advisory_type']===$type?'checked':'' ?> style="accent-color:var(--orange,#e8651a);width:18px;height:18px">
                        <span style="font-size:15px;font-weight:600;color:#111827"><?= $type ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:28px">
                    <button type="submit" name="prev_step" value="1" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Previous</button>
                    <button type="submit" name="next_step" value="3" class="btn btn-navy">Next <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <?php elseif ($step === 3): ?>
            <!-- STEP 3 -->
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:40px">
                <h3 style="font-size:20px;font-weight:700;margin-bottom:6px">Institutional Details</h3>
                <p style="color:#64748b;font-size:14px;margin-bottom:28px">Provide specific details about your request.</p>
                <div class="form-group">
                    <label class="form-label">Institution Name</label>
                    <input type="text" name="institution" class="form-control" value="<?= htmlspecialchars($data['institution']) ?>" placeholder="Name of the institution you want to engage with">
                </div>
                <div class="form-group">
                    <label class="form-label">Type of Engagement</label>
                    <select name="engagement" class="form-control">
                        <option value="">Select engagement type</option>
                        <?php foreach (['Transcript Request','Degree Verification','Program Enrollment','Study Abroad','Institutional Liaison','Other'] as $eng): ?>
                        <option <?= $data['engagement']===$eng?'selected':'' ?>><?= $eng ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Additional Information</label>
                    <textarea name="additional" class="form-control" rows="4" placeholder="Any other details that would help us assist you better..."><?= htmlspecialchars($data['additional']) ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Urgency Level</label>
                    <div style="display:flex;flex-wrap:wrap;gap:16px;margin-top:10px">
                        <?php foreach (['Low (Within 2 weeks)','Medium (Within 1 week)','High (Urgent)'] as $u): ?>
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                            <input type="radio" name="urgency" value="<?= $u ?>" <?= $data['urgency']===$u?'checked':'' ?> style="accent-color:var(--orange,#e8651a)">
                            <span style="font-size:14px;color:#374151"><?= $u ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:20px">
                    <button type="submit" name="prev_step" value="2" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Previous</button>
                    <button type="submit" name="next_step" value="4" class="btn btn-navy">Next <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <?php elseif ($step === 4): ?>
            <!-- STEP 4 -->
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:40px">
                <h3 style="font-size:20px;font-weight:700;margin-bottom:6px">Review Your Request</h3>
                <p style="color:#64748b;font-size:14px;margin-bottom:28px">Please review your information before submitting.</p>

                <div style="background:#f8f9ff;border-radius:12px;padding:24px;margin-bottom:16px">
                    <h4 style="font-size:14px;font-weight:700;color:#111827;margin-bottom:16px">Personal Information</h4>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <?php foreach (['Name'=>$data['full_name'],'Email'=>$data['email'],'Phone'=>$data['phone'],'Country'=>$data['country']] as $label=>$val): ?>
                        <div>
                            <div style="font-size:12px;color:#94a3b8;margin-bottom:2px"><?= $label ?></div>
                            <div style="font-size:14px;font-weight:600;color:#111827"><?= htmlspecialchars($val) ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div style="background:#f8f9ff;border-radius:12px;padding:24px;margin-bottom:16px">
                    <h4 style="font-size:14px;font-weight:700;color:#111827;margin-bottom:8px">Advisory Type</h4>
                    <span style="font-size:14px;color:var(--orange,#e8651a);font-weight:600"><?= htmlspecialchars($data['advisory_type']) ?></span>
                </div>

                <div style="background:#f8f9ff;border-radius:12px;padding:24px;margin-bottom:24px">
                    <h4 style="font-size:14px;font-weight:700;color:#111827;margin-bottom:8px">Additional Information</h4>
                    <p style="font-size:14px;color:#64748b;margin:0"><?= htmlspecialchars($data['additional'] ?: 'None provided') ?></p>
                </div>

                <label style="display:flex;align-items:flex-start;gap:12px;padding:18px;border:1.5px solid <?= in_array('You must accept the terms to proceed.', $errors)?'#ef4444':'#e5e7eb' ?>;border-radius:10px;cursor:pointer;margin-bottom:8px">
                    <input type="checkbox" name="agree" value="1" <?= $data['agree']?'checked':'' ?> style="accent-color:var(--orange,#e8651a);width:18px;height:18px;flex-shrink:0;margin-top:2px">
                    <div>
                        <div style="font-size:14px;font-weight:600;color:#111827;margin-bottom:4px">I understand and accept the terms</div>
                        <div style="font-size:13px;color:#64748b;line-height:1.6">I understand that Afrika Scholar provides advisory services only. All formal processes must be completed directly with the relevant institutions.</div>
                    </div>
                </label>
                <?php if (in_array('You must accept the terms to proceed.', $errors)): ?>
                <p style="color:#ef4444;font-size:13px;margin-bottom:16px">You must accept the terms to proceed.</p>
                <?php endif; ?>

                <div style="display:flex;justify-content:space-between;margin-top:24px">
                    <button type="submit" name="prev_step" value="3" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Previous</button>
                    <button type="submit" name="submit_final" value="1" class="btn btn-navy" style="padding:12px 32px">Submit Request <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
            <?php endif; ?>
        </form>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
