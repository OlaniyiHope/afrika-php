<?php
$page_title = 'Peer Review Policy';
include 'header.php';
$sections = [
    'Purpose & Philosophy','Academic Integrity','Types of Peer Review',
    'Reviewer Selection','Conflict of Interest','Review Workflow',
    'Decision Categories','Revision Timelines','Ethical Oversight',
    'Appeals Process','Responsibilities','Handling Misconduct',
    'Corrections & Retractions','FAQs'
];
?>

<div style="display:flex;align-items:center;gap:8px;padding:14px 80px;font-size:13px;color:var(--text-gray);border-bottom:1px solid var(--border)">
    <a href="publications.php" style="color:var(--text-gray)">← Back to Publishing Standards</a>
</div>

<div class="page-hero" style="min-height:280px">
    <div class="hero-pattern"></div>
    <div class="page-hero-content">
        <div class="section-eyebrow">PUBLISHING FRAMEWORK</div>
        <h1 style="color:#fff;font-size:42px;font-weight:800;margin-bottom:16px">Peer Review Policy</h1>
        <p style="color:rgba(255,255,255,.8);font-size:15px;max-width:580px;margin:0 auto 28px">Afrika Scholar's comprehensive peer review framework — designed to uphold rigour, fairness, and transparency across all journals.</p>
        <button onclick="window.print()" class="btn btn-white-outline"><i class="fas fa-download"></i> Download PDF</button>
    </div>
</div>

<section class="section">
    <div class="sidebar-layout">
        <div class="sidebar">
            <h5>CONTENTS</h5>
            <div class="sidebar-nav">
                <?php foreach ($sections as $i => $s): ?>
                <a href="#section-<?= $i ?>" class="<?= $i===0?'active':'' ?>">
                    <i class="<?= ['fas fa-bullseye','fas fa-shield-alt','fas fa-eye','fas fa-user','fas fa-balance-scale','fas fa-project-diagram','fas fa-list','fas fa-clock','fas fa-check-circle','fas fa-gavel','fas fa-users','fas fa-exclamation-triangle','fas fa-redo','fas fa-question-circle'][$i] ?>"></i>
                    <?= $s ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div>
            <!-- SECTION 1 -->
            <div id="section-0" style="margin-bottom:48px">
                <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px">
                    <div style="width:40px;height:40px;background:var(--orange);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;font-weight:700">1</div>
                    <h2 style="font-size:24px;font-weight:800">Purpose & Editorial Philosophy</h2>
                </div>
                <p style="color:var(--text-blue);font-size:15px;line-height:1.8;margin-bottom:16px">The peer review process is the cornerstone of academic publishing at Afrika Scholar. It serves as the primary mechanism for ensuring the quality, validity, and significance of published research. Our peer review system is designed to uphold the highest standards of academic rigour while remaining fair, transparent, and constructive.</p>
                <p style="color:var(--text-blue);font-size:15px;line-height:1.8;margin-bottom:16px">Afrika Scholar believes that peer review should be a collaborative process that improves research quality rather than merely serving as a gatekeeping mechanism. We are committed to fostering an environment where reviewers provide constructive, actionable feedback that helps authors strengthen their work, regardless of the final editorial decision.</p>
                <p style="color:var(--text-blue);font-size:15px;line-height:1.8;margin-bottom:16px">Our editorial philosophy is rooted in the conviction that credible academic publishing must be accessible, equitable, and free from commercial pressures. We reject pay-to-publish models and ensure that editorial decisions are based exclusively on academic merit, methodological soundness, and contribution to knowledge.</p>
                <div style="border-left:3px solid var(--orange);padding-left:20px;font-style:italic;color:var(--text-gray);font-size:14px;line-height:1.8">We recognise the unique challenges and opportunities in African scholarship and are committed to ensuring our peer review process supports, not hinders, the development of African academic voices.</div>
            </div>

            <!-- SECTION 2 -->
            <div id="section-1" style="margin-bottom:48px">
                <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px">
                    <div style="width:40px;height:40px;background:var(--orange);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;font-weight:700">2</div>
                    <h2 style="font-size:24px;font-weight:800">Academic Integrity</h2>
                </div>
                <p style="color:var(--text-blue);font-size:15px;line-height:1.8;margin-bottom:16px">All submissions to Afrika Scholar journals must adhere to the highest standards of academic integrity. This includes originality, honest reporting, proper attribution, and ethical research conduct.</p>
                <div class="cards-grid cards-2" style="gap:16px;margin-top:20px">
                    <?php foreach ([['Plagiarism Policy','All manuscripts are screened using advanced plagiarism detection tools. A similarity index above 15% will trigger a detailed review.'],['Data Integrity','Authors must provide access to raw data upon request and must not fabricate, falsify, or selectively report results.'],['Authorship Standards','All listed authors must have made substantial contributions. Ghost authorship and gift authorship are not permitted.'],['Disclosure Requirements','All funding sources, conflicts of interest, and relevant relationships must be disclosed at submission.']] as $c): ?>
                    <div class="card">
                        <div class="card-title"><?= $c[0] ?></div>
                        <p class="card-text"><?= $c[1] ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- SECTION 3: Types of Peer Review -->
            <div id="section-2" style="margin-bottom:48px">
                <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px">
                    <div style="width:40px;height:40px;background:var(--orange);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;font-weight:700">3</div>
                    <h2 style="font-size:24px;font-weight:800">Types of Peer Review</h2>
                </div>
                <p style="color:var(--text-blue);font-size:15px;line-height:1.8;margin-bottom:24px">Afrika Scholar supports multiple peer review models, allowing individual journals to adopt the approach best suited to their disciplinary norms and editorial needs.</p>
                <?php
                $reviews = [
                    ['fas fa-eye-slash','Single-Blind Review','Most common model','Reviewer identities are concealed from authors, but reviewers know who the authors are. Allows reviewers to provide candid feedback while enabling them to consider the authors\' institutional context and track record.','Best for: Established fields where author identity may provide relevant context for evaluating significance and feasibility.','Editors are trained to mitigate potential biases related to author reputation or institutional prestige.'],
                    ['fas fa-eye','Double-Blind Review','Recommended for bias minimisation','Neither authors nor reviewers know each other\'s identities. Manuscripts are evaluated purely on their academic merits. Authors must prepare manuscripts for anonymous review, removing identifying information from text, acknowledgements, and metadata.','Best for: Journals where author identity might significantly influence reviewer assessments, or where emerging scholars may face disadvantages.','Afrika Scholar provides detailed guidelines for manuscript anonymisation.'],
                    ['fas fa-user-friends','Open Review','Opt-in basis','Identities of both authors and reviewers are disclosed, and review reports may be published alongside the accepted article. Promotes transparency and accountability, encouraging more constructive and respectful reviewer feedback.','Best for: Journals that wish to promote full transparency and where disciplinary norms support open review.','Currently offered on an opt-in basis. May not be appropriate where significant power imbalances exist between authors and reviewers.'],
                ];
                foreach ($reviews as $r): ?>
                <div style="border:1px solid var(--border);border-radius:12px;padding:24px;margin-bottom:16px">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
                        <div style="width:40px;height:40px;background:rgba(232,101,26,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--orange)"><i class="<?= $r[0] ?>"></i></div>
                        <div>
                            <h3 style="font-size:17px;font-weight:700"><?= $r[1] ?></h3>
                        </div>
                        <span style="margin-left:auto;font-size:11px;padding:3px 10px;border-radius:20px;background:rgba(232,101,26,.1);color:var(--orange);font-weight:600"><?= $r[2] ?></span>
                    </div>
                    <p style="color:var(--text-blue);font-size:14px;line-height:1.7;margin-bottom:10px"><?= $r[3] ?></p>
                    <p style="font-size:13px;color:var(--text-dark);margin-bottom:4px"><strong>Best for:</strong> <?= substr($r[4], 10) ?></p>
                    <p style="font-size:13px;color:var(--text-gray);font-style:italic"><?= $r[5] ?></p>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- SECTION 4: Reviewer Selection -->
            <div id="section-3" style="margin-bottom:48px">
                <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px">
                    <div style="width:40px;height:40px;background:var(--orange);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;font-weight:700">4</div>
                    <h2 style="font-size:24px;font-weight:800">Reviewer Selection Criteria</h2>
                </div>
                <p style="color:var(--text-blue);font-size:15px;line-height:1.8;margin-bottom:16px">The selection of appropriate peer reviewers is critical to the quality and credibility of the review process. Reviewers must possess demonstrable expertise in the subject area of the manuscript.</p>
                <p style="color:var(--text-blue);font-size:15px;line-height:1.8">This typically includes a doctoral degree or equivalent research experience in the relevant field, a track record of published research in the topic area, and recognition by peers as a knowledgeable authority in the subject matter.</p>
            </div>

            <!-- Additional sections placeholder -->
            <?php foreach (array_slice($sections, 4) as $i => $section): ?>
            <div id="section-<?= $i+4 ?>" style="margin-bottom:48px">
                <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px">
                    <div style="width:40px;height:40px;background:var(--orange);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;font-weight:700"><?= $i+5 ?></div>
                    <h2 style="font-size:24px;font-weight:800"><?= $section ?></h2>
                </div>
                <p style="color:var(--text-blue);font-size:15px;line-height:1.8">Afrika Scholar maintains comprehensive standards and guidelines for <?= strtolower($section) ?>. Our editorial team operates according to COPE (Committee on Publication Ethics) guidelines and international best practices.</p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
