<?php
$page_title = 'About Us';
include 'header.php';
?>

<style>
.about-hero {
    position: relative; min-height: 420px;
    display: flex; align-items: center; justify-content: center; overflow: hidden;
}
.about-hero-bg {
    position: absolute; inset: 0;
    background-image: url('asset/about-conference.jpg');
    background-size: cover; background-position: center; z-index: 0;
}
.about-hero-overlay { position: absolute; inset: 0; background: rgba(10,22,55,.85); z-index: 1; }
.about-hero-dots {
    position: absolute; inset: 0; z-index: 2; opacity: .10;
    background-image: radial-gradient(circle, #fff 1px, transparent 1px);
    background-size: 16px 16px;
}
.about-hero-inner {
    position: relative; z-index: 3; text-align: center;
    color: #fff; padding: 80px 20px; max-width: 800px;
}
.about-hero-eyebrow {
    font-size: 11px; font-weight: 700; letter-spacing: .14em;
    text-transform: uppercase; color: var(--orange,#e8651a); margin-bottom: 14px;
}
.about-hero-title {
    font-size: clamp(28px,5vw,48px); font-weight: 800;
    margin: 0 0 20px; line-height: 1.15; color: #fff;
}

/* Who We Are */
.about-who { max-width: 1100px; margin: 0 auto; display: flex; gap: 60px; align-items: center; padding: 80px 20px; }
.about-who-text { flex: 1; }
.about-who-img  { flex: 1; position: relative; }
.about-who-img img { width: 100%; height: 400px; object-fit: cover; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,.15); border: 4px solid var(--orange,#e8651a); display: block; }
.about-who-img-placeholder { width: 100%; height: 400px; border-radius: 20px; background: linear-gradient(135deg,#c87941,#8B5E3C); display: flex; align-items: center; justify-content: center; box-shadow: 0 20px 60px rgba(0,0,0,.15); border: 4px solid var(--orange,#e8651a); }
.about-blockquote { border-left: 4px solid var(--orange,#e8651a); padding-left: 20px; font-style: italic; color: #64748b; font-size: 15px; line-height: 1.7; margin-top: 20px; }
.about-deco-br { position: absolute; bottom: -16px; left: -16px; width: 96px; height: 96px; border-radius: 16px; background: rgba(232,101,26,.2); z-index: -1; }
.about-deco-tr { position: absolute; top: -16px; right: -16px; width: 80px; height: 80px; border-radius: 16px; background: rgba(45,43,143,.2); z-index: -1; }

/* Mission & Vision */
.mv-grid { max-width: 900px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 32px; }
.mv-card { background: #fff; border-radius: 16px; padding: 32px; box-shadow: 0 2px 12px rgba(0,0,0,.06); }
.mv-card.navy { border-left: 4px solid var(--navy,#2d2b8f); }
.mv-card.orange { border-left: 4px solid var(--orange,#e8651a); }
.mv-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 16px; }
.mv-icon.navy { background: rgba(45,43,143,.1); color: var(--navy,#2d2b8f); }
.mv-icon.orange { background: rgba(232,101,26,.1); color: var(--orange,#e8651a); }

/* Why section */
.why-grid { max-width: 1100px; margin: 0 auto; display: flex; gap: 60px; align-items: center; }
.why-img { flex: 1; position: relative; }
.why-img img { width: 100%; height: 400px; object-fit: cover; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,.15); display: block; }
.why-img-placeholder { width: 100%; height: 400px; border-radius: 20px; background: linear-gradient(135deg,#1a2f5a,#2d2b8f); display: flex; align-items: center; justify-content: center; box-shadow: 0 20px 60px rgba(0,0,0,.15); }
.why-img-deco { position: absolute; bottom: -16px; right: -16px; width: 112px; height: 112px; border-radius: 16px; background: rgba(45,43,143,.1); z-index: -1; }
.why-body { flex: 1; }
.why-gap-item { display: flex; align-items: flex-start; gap: 14px; background: #f1f5f9; border-radius: 12px; padding: 16px; margin-bottom: 12px; }
.why-gap-num { width: 32px; height: 32px; border-radius: 50%; background: var(--orange,#e8651a); color: #fff; font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

/* Pillars accordion */
.pillars-wrap { max-width: 900px; margin: 0 auto; }
.pillar-item { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 12px; overflow: hidden; }
.pillar-trigger { display: flex; align-items: center; gap: 16px; padding: 20px 24px; cursor: pointer; width: 100%; background: none; border: none; text-align: left; }
.pillar-trigger:hover { background: #f8f9ff; }
.pillar-icon-wrap { width: 48px; height: 48px; border-radius: 10px; background: rgba(232,101,26,.1); display: flex; align-items: center; justify-content: center; color: var(--orange,#e8651a); font-size: 18px; flex-shrink: 0; }
.pillar-title { font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 4px; }
.pillar-desc  { font-size: 13px; color: #64748b; line-height: 1.5; }
.pillar-chevron { margin-left: auto; color: #94a3b8; font-size: 13px; transition: transform .2s; flex-shrink: 0; }
.pillar-body { display: none; padding: 0 24px 20px 88px; }
.pillar-body.open { display: block; }
.pillar-chevron.open { transform: rotate(180deg); }
.pillar-detail { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-size: 13px; color: #374151; }
.pillar-detail i { color: var(--orange,#e8651a); font-size: 13px; }
.pillar-link { display: inline-flex; align-items: center; gap: 6px; margin-top: 12px; font-size: 13px; font-weight: 600; color: var(--orange,#e8651a); border: 1px solid #e5e7eb; border-radius: 7px; padding: 7px 14px; text-decoration: none; transition: all .15s; }
.pillar-link:hover { border-color: var(--orange,#e8651a); background: rgba(232,101,26,.05); }

/* Commitments */
.commit-grid { max-width: 1100px; margin: 0 auto; display: flex; gap: 60px; align-items: center; }
.commit-body { flex: 1; }
.commit-img  { flex: 1; position: relative; }
.commit-img img { width: 100%; height: 400px; object-fit: cover; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,.15); display: block; }
.commit-img-placeholder { width: 100%; height: 400px; border-radius: 20px; background: linear-gradient(135deg,#2d5a3d,#1a7a4a); display: flex; align-items: center; justify-content: center; box-shadow: 0 20px 60px rgba(0,0,0,.15); }
.commit-img-deco { position: absolute; top: -16px; left: -16px; width: 80px; height: 80px; border-radius: 16px; background: rgba(232,101,26,.15); z-index: -1; }
.commit-item { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; font-size: 15px; color: #374151; }
.commit-item i { color: var(--orange,#e8651a); font-size: 16px; flex-shrink: 0; }
.sdg-pill { display: inline-flex; align-items: center; gap: 6px; background: rgba(232,101,26,.1); border-radius: 999px; padding: 5px 12px; font-size: 12px; margin: 4px; }
.sdg-num { font-weight: 700; color: var(--orange,#e8651a); }
.sdg-label { color: #64748b; }

/* CycleBreeze */
.cyclebreeze-wrap { max-width: 720px; margin: 0 auto; text-align: center; padding: 80px 20px; }
.cyclebreeze-wrap h2 { font-size: 32px; font-weight: 800; margin: 8px 0 16px; }
.cyclebreeze-wrap p  { font-size: 15px; color: #64748b; line-height: 1.7; margin-bottom: 24px; }
.btn-outline-ghost { display: inline-flex; align-items: center; gap: 6px; border: 1.5px solid #e5e7eb; border-radius: 8px; padding: 10px 22px; font-size: 14px; font-weight: 600; color: #374151; text-decoration: none; transition: all .15s; background: #fff; }
.btn-outline-ghost:hover { border-color: var(--orange,#e8651a); color: var(--orange,#e8651a); }

/* Engage CTA */
.engage-cta { position: relative; overflow: hidden; padding: 80px 20px; background: #0f1f3d; text-align: center; color: #fff; }
.engage-dots { position: absolute; inset: 0; opacity: .08; background-image: radial-gradient(circle,#fff 1px,transparent 1px); background-size: 12px 12px; z-index: 0; }
.engage-inner { position: relative; z-index: 1; max-width: 720px; margin: 0 auto; }
.engage-inner h2 { font-size: clamp(24px,3vw,36px); font-weight: 800; margin-bottom: 12px; color: #fff; }
.engage-btns { display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; margin-top: 28px; }
.btn-engage-outline { display: inline-flex; align-items: center; gap: 6px; border: 1.5px solid rgba(255,255,255,.3); border-radius: 8px; padding: 11px 22px; font-size: 14px; font-weight: 600; color: var(--orange,#e8651a); text-decoration: none; transition: all .15s; }
.btn-engage-outline:hover { background: rgba(255,255,255,.08); }
.btn-engage-solid { display: inline-flex; align-items: center; gap: 6px; background: var(--orange,#e8651a); color: #fff; border: none; border-radius: 8px; padding: 13px 28px; font-size: 15px; font-weight: 700; text-decoration: none; transition: opacity .15s; margin-top: 20px; }
.btn-engage-solid:hover { opacity: .88; color: #fff; }

@media (max-width: 900px) {
    .about-who, .why-grid, .commit-grid { flex-direction: column; padding: 40px 16px; gap: 32px; }
    .mv-grid { grid-template-columns: 1fr; }
    .pillar-body { padding-left: 24px; }
}
</style>


<!-- ══ HERO ════════════════════════════════════════════════════════════ -->
<section class="about-hero">
    <div class="about-hero-bg"></div>
    <div class="about-hero-overlay"></div>
    <div class="about-hero-dots"></div>
    <div class="about-hero-inner">
        <div class="about-hero-eyebrow">About Afrika Scholar</div>
        <h1 class="about-hero-title">Pan-African Academic Publishing, Research &amp; University Enablement Infrastructure</h1>
    </div>
</section>


<!-- ══ WHO WE ARE ══════════════════════════════════════════════════════ -->
<section style="background:#fff">
    <div class="about-who">
        <div class="about-who-text">
            <h2 class="section-title">Who We Are</h2>
            <p style="color:#64748b;font-size:15px;line-height:1.7;margin-bottom:16px">Afrika Scholar is a Pan-African Academic Publishing, Research &amp; University Enablement Infrastructure built to strengthen Africa's role, visibility, and credibility within the global knowledge ecosystem.</p>
            <p style="color:#64748b;font-size:15px;line-height:1.7;margin-bottom:16px">We operate as a journal-first platform and a broader academic infrastructure that enables scholars, institutions, professionals, and education platforms to publish, collaborate, coordinate, and advance knowledge responsibly.</p>
            <div class="about-blockquote">Afrika Scholar is designed as long-term academic infrastructure — not a media site, consultancy, or short-term EdTech product.</div>
        </div>
        <div class="about-who-img">
<img src="asset/hero-scholars.jpg" alt="African scholars collaborating">            <div class="about-deco-br"></div>
            <div class="about-deco-tr"></div>
        </div>
    </div>
</section>


<!-- ══ MISSION & VISION ════════════════════════════════════════════════ -->
<section class="section" style="background:#f8f9ff;border-top:1px solid #e5e7eb" id="mission">
    <div class="section-center" style="margin-bottom:40px">
        <h2 class="section-title">Mission &amp; Vision</h2>
    </div>
    <div class="mv-grid">
        <div class="mv-card navy">
            <div class="mv-icon navy"><i class="fas fa-bullseye"></i></div>
            <h3 style="font-size:20px;font-weight:800;margin-bottom:12px">Our Mission</h3>
            <p style="color:#64748b;font-size:15px;line-height:1.7">To build and sustain African-owned academic infrastructure that empowers scholars, strengthens institutions, and advances global knowledge equity.</p>
        </div>
        <div class="mv-card orange">
            <div class="mv-icon orange"><i class="far fa-eye"></i></div>
            <h3 style="font-size:20px;font-weight:800;margin-bottom:12px">Our Vision</h3>
            <p style="color:#64748b;font-size:15px;line-height:1.7">A future where African scholarship is globally visible, institutionally strong, ethically published, and preserved for generations.</p>
        </div>
    </div>
</section>


<!-- ══ STATS ═══════════════════════════════════════════════════════════ -->
<section class="section section-dark" style="padding:60px 20px">
    <div class="stats-row" style="max-width:900px;margin:0 auto;display:grid;grid-template-columns:repeat(4,1fr);gap:32px;text-align:center">
        <div>
            <div style="font-size:38px;font-weight:800;color:var(--orange,#e8651a)">12+</div>
            <div style="color:rgba(255,255,255,.75);font-size:14px;margin-top:6px">Partner Universities</div>
        </div>
        <div>
            <div style="font-size:38px;font-weight:800;color:var(--orange,#e8651a)">500+</div>
            <div style="color:rgba(255,255,255,.75);font-size:14px;margin-top:6px">Published Articles</div>
        </div>
        <div>
            <div style="font-size:38px;font-weight:800;color:var(--orange,#e8651a)">30+</div>
            <div style="color:rgba(255,255,255,.75);font-size:14px;margin-top:6px">Countries Represented</div>
        </div>
        <div>
            <div style="font-size:38px;font-weight:800;color:var(--orange,#e8651a)">1,200+</div>
            <div style="color:rgba(255,255,255,.75);font-size:14px;margin-top:6px">Network Members</div>
        </div>
    </div>
</section>


<!-- ══ WHY WE EXIST ════════════════════════════════════════════════════ -->
<section class="section" id="why">
    <div class="why-grid">
        <div class="why-img">
<img src="asset/about-campus.jpg" alt="African university campus" style="width:100%;height:400px;object-fit:cover;border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.15);display:block">            <div class="why-img-deco"></div>
        </div>
        <div class="why-body">
            <h2 class="section-title">Why Afrika Scholar Exists</h2>
            <p style="color:#64748b;font-size:15px;line-height:1.7;margin-bottom:24px">African scholarship is rich, diverse, and impactful — yet often constrained by:</p>

            <div class="why-gap-item">
                <div class="why-gap-num">1</div>
                <p style="color:#64748b;margin:0;font-size:14px">Limited access to credible publishing infrastructure</p>
            </div>
            <div class="why-gap-item">
                <div class="why-gap-num">2</div>
                <p style="color:#64748b;margin:0;font-size:14px">Fragmented research dissemination systems</p>
            </div>
            <div class="why-gap-item">
                <div class="why-gap-num">3</div>
                <p style="color:#64748b;margin:0;font-size:14px">Underutilised academic expertise</p>
            </div>
            <div class="why-gap-item">
                <div class="why-gap-num">4</div>
                <p style="color:#64748b;margin:0;font-size:14px">Weak coordination between academia, institutions, industry, and education platforms</p>
            </div>

            <p style="color:#64748b;font-size:15px;line-height:1.7;margin-top:20px">Afrika Scholar was created to address these gaps systemically.</p>
        </div>
    </div>
</section>


<!-- ══ FIVE CORE PILLARS ═══════════════════════════════════════════════ -->
<section class="section" style="background:#f8f9ff;border-top:1px solid #e5e7eb" id="pillars">
    <div class="section-center" style="margin-bottom:40px">
        <h2 class="section-title">Our Five Core Pillars</h2>
        <p class="section-subtitle">Afrika Scholar is structured around five equally important pillars, each reinforcing the other.</p>
    </div>
    <div class="pillars-wrap">

        <div class="pillar-item">
            <button class="pillar-trigger" onclick="togglePillar(this)">
                <div class="pillar-icon-wrap"><i class="fas fa-book-open"></i></div>
                <div>
                    <div class="pillar-title">1. Academic Publishing</div>
                    <div class="pillar-desc">Publishing is the foundation of Afrika Scholar — peer-reviewed, open-access academic journals aligned with international standards.</div>
                </div>
                <i class="fas fa-chevron-down pillar-chevron"></i>
            </button>
            <div class="pillar-body">
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Credible and rigorously reviewed</div>
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Openly accessible without compromising quality</div>
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Globally visible, index-ready, and citable</div>
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Preserved as a long-term scholarly asset</div>
                <a href="publications.php" class="pillar-link">Explore Academic Publishing <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="pillar-item">
            <button class="pillar-trigger" onclick="togglePillar(this)">
                <div class="pillar-icon-wrap"><i class="fas fa-users"></i></div>
                <div>
                    <div class="pillar-title">2. Lecturer &amp; Academic Partners Network</div>
                    <div class="pillar-desc">A curated network of lecturers, researchers, editors, reviewers, and academically qualified professionals.</div>
                </div>
                <i class="fas fa-chevron-down pillar-chevron"></i>
            </button>
            <div class="pillar-body">
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Academics publish, review, and collaborate within Afrika Scholar</div>
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Peer review and editorial capacity are strengthened</div>
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Qualified academics gain visibility and recognition</div>
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Structured, standards-aligned academic opportunities</div>
                <a href="network.php" class="pillar-link">Explore Network <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="pillar-item">
            <button class="pillar-trigger" onclick="togglePillar(this)">
                <div class="pillar-icon-wrap"><i class="fas fa-graduation-cap"></i></div>
                <div>
                    <div class="pillar-title">3. Educational &amp; University Advisory</div>
                    <div class="pillar-desc">Academic coordination layer delivered in partnership with universities and institutions across Africa.</div>
                </div>
                <i class="fas fa-chevron-down pillar-chevron"></i>
            </button>
            <div class="pillar-body">
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Transcript facilitation and academic documentation coordination</div>
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Academic pathway and progression guidance</div>
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Liaison with partner universities across Africa</div>
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Support for structured academic processes</div>
                <a href="advisory.php" class="pillar-link">Explore Advisory <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="pillar-item">
            <button class="pillar-trigger" onclick="togglePillar(this)">
                <div class="pillar-icon-wrap"><i class="fas fa-puzzle-piece"></i></div>
                <div>
                    <div class="pillar-title">4. Knowledge Enablement &amp; Integration</div>
                    <div class="pillar-desc">Enabling professionals, practitioners, institutions, and education platforms to engage academia credibly.</div>
                </div>
                <i class="fas fa-chevron-down pillar-chevron"></i>
            </button>
            <div class="pillar-body">
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Applied and professional research publishing</div>
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Collaboration between industry, policy, and academia</div>
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Academic governance, validation, and compliance for EdTech platforms</div>
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Curriculum, content, and research alignment to global standards</div>
                <a href="about.php#enablement" class="pillar-link">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="pillar-item">
            <button class="pillar-trigger" onclick="togglePillar(this)">
                <div class="pillar-icon-wrap"><i class="fas fa-robot"></i></div>
                <div>
                    <div class="pillar-title">5. Publeesh (AI Tool)</div>
                    <div class="pillar-desc">AI-powered publishing and research tool to help with writing, research, and datasets.</div>
                </div>
                <i class="fas fa-chevron-down pillar-chevron"></i>
            </button>
            <div class="pillar-body">
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Structured drafting assistance</div>
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Literature review organization</div>
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Citation guidance and formatting</div>
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Global institutional dataset access</div>
                <div class="pillar-detail"><i class="fas fa-check-circle"></i> Comparative research intelligence tool</div>
                <a href="publeesh.php" class="pillar-link">Explore Publeesh AI <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

    </div>
</section>


<!-- ══ COMMITMENTS ═════════════════════════════════════════════════════ -->
<section class="section">
    <div class="commit-grid">
        <div class="commit-body">
            <h2 class="section-title">Our Commitments</h2>
            <div class="commit-item"><i class="fas fa-heart"></i> Academic integrity and rigorous peer review</div>
            <div class="commit-item"><i class="fas fa-heart"></i> Institutional respect and collaboration</div>
            <div class="commit-item"><i class="fas fa-heart"></i> Knowledge equity and open access</div>
            <div class="commit-item"><i class="fas fa-heart"></i> Capacity building across Africa</div>
            <div class="commit-item"><i class="fas fa-heart"></i> Responsible innovation and long-term impact</div>

            <p style="color:#94a3b8;font-size:13px;margin-top:20px;margin-bottom:12px">Our work aligns with the United Nations Sustainable Development Goals (SDGs).</p>
            <div>
                <span class="sdg-pill"><span class="sdg-num">SDG 4</span><span class="sdg-label">Quality Education</span></span>
                <span class="sdg-pill"><span class="sdg-num">SDG 8</span><span class="sdg-label">Decent Work</span></span>
                <span class="sdg-pill"><span class="sdg-num">SDG 9</span><span class="sdg-label">Innovation</span></span>
                <span class="sdg-pill"><span class="sdg-num">SDG 17</span><span class="sdg-label">Partnerships</span></span>
            </div>
        </div>
        <div class="commit-img">
<img src="asset/network-collaboration.jpg" alt="Research collaboration" style="width:100%;height:400px;object-fit:cover;border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.15);display:block">            <div class="commit-img-deco"></div>
        </div>
    </div>
</section>


<!-- ══ POWERED BY CYCLEBREEZE ══════════════════════════════════════════ -->
<section style="background:#f8f9ff;border-top:1px solid #e5e7eb">
    <div class="cyclebreeze-wrap">
        <i class="fas fa-globe" style="font-size:36px;color:#94a3b8;margin-bottom:12px;display:block"></i>
        <p style="font-size:12px;color:#94a3b8;margin:0">Powered by</p>
        <h2>CycleBreeze</h2>
        <p>Afrika Scholar is powered by CycleBreeze, a research and technology development company providing the secure digital infrastructure, publishing systems, and long-term technical support that enable Afrika Scholar to operate as a durable academic institution.</p>
        <a href="https://cyclebreeze.com" target="_blank" rel="noopener noreferrer" class="btn-outline-ghost">
            Learn More About CycleBreeze <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</section>


<!-- ══ ENGAGE CTA ══════════════════════════════════════════════════════ -->
<section class="engage-cta">
    <div class="engage-dots"></div>
    <div class="engage-inner">
        <i class="fas fa-lightbulb" style="font-size:40px;color:var(--orange,#e8651a);margin-bottom:20px;display:block"></i>
        <h2>Engage With Afrika Scholar</h2>
        <p style="color:rgba(255,255,255,.8);font-size:15px;line-height:1.7">Whether you are a researcher, institution, or academic professional, Afrika Scholar has a place for your contribution to Africa's knowledge future.</p>
        <div class="engage-btns">
            <a href="submit_manuscript.php" class="btn-engage-outline">Publish with Afrika Scholar <i class="fas fa-arrow-right"></i></a>
            <a href="network.php" class="btn-engage-outline">Join the Academic Partners Network <i class="fas fa-arrow-right"></i></a>
            <a href="advisory.php" class="btn-engage-outline">Access Educational &amp; University Advisory <i class="fas fa-arrow-right"></i></a>
            <a href="network.php" class="btn-engage-outline">Partner for Enablement &amp; Integration <i class="fas fa-arrow-right"></i></a>
        </div>
        <div>
            <a href="submit_manuscript.php" class="btn-engage-solid">Get Started <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<script>
function togglePillar(btn) {
    var body    = btn.parentElement.querySelector('.pillar-body');
    var chevron = btn.querySelector('.pillar-chevron');
    var isOpen  = body.classList.contains('open');
    // Close all
    document.querySelectorAll('.pillar-body').forEach(function(b){ b.classList.remove('open'); });
    document.querySelectorAll('.pillar-chevron').forEach(function(c){ c.classList.remove('open'); });
    // Open clicked (if it was closed)
    if (!isOpen) {
        body.classList.add('open');
        chevron.classList.add('open');
    }
}
</script>

<?php include 'footer.php'; ?>
