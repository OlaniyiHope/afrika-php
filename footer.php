<?php
require_once __DIR__ . '/helpers.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newsletter_email'])) {
    $email = filter_var(trim($_POST['newsletter_email']), FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $subs = read_csv('subscribers.csv');
        $exists = array_filter($subs, fn($s) => $s['email'] === $email);
        if ($exists) {
            $msg = '<div class="alert alert-error" style="margin:0">You are already subscribed.</div>';
        } else {
            $id = get_next_id('subscribers.csv');
            append_csv('subscribers.csv', [$id, $email, date('Y-m-d')]);
            $msg = '<div class="alert alert-success" style="margin:0">Thank you for subscribing!</div>';
        }
    } else {
        $msg = '<div class="alert alert-error" style="margin:0">Please enter a valid email.</div>';
    }
}
?>
<div class="scroll-top" onclick="window.scrollTo({top:0,behavior:'smooth'})"><i class="fas fa-arrow-up"></i></div>

<div class="footer-newsletter">
    <div>
        <h3>Stay Updated</h3>
        <p>Get the latest research, publications, and opportunities delivered to your inbox.</p>
    </div>
    <div style="flex:1;max-width:500px">
        <?= $msg ?>
        <?php if (!$msg || strpos($msg,'error') !== false): ?>
        <form method="POST" style="display:flex;gap:10px;">
            <input type="email" name="newsletter_email" class="form-control" placeholder="Enter your email" style="flex:1;background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.3);color:#fff">
            <button type="submit" class="btn btn-outline" style="white-space:nowrap;border-color:#fff;color:#fff;background:transparent">Subscribe</button>
        </form>
        <?php endif; ?>
    </div>
</div>

<footer class="footer-main">
    <div class="footer-grid">
        <div class="footer-brand">
            <div class="logo" style="margin-bottom:16px">
                <span class="blue" style="color:#fff;font-size:20px;font-weight:800">Afrika</span><span class="orange" style="font-size:20px;font-weight:800">scholar</span>
            </div>
            <p>Pan-African Academic Publishing, Research & University Enablement Infrastructure. Empowering African scholarship and bridging knowledge gaps across the continent.</p>
            <div class="footer-contact">
                <a href="mailto:info@afrikascholar.org"><i class="far fa-envelope"></i> info@afrikascholar.org</a>
                <a href="tel:+2348109976152"><i class="far fa-comment"></i> +234 810 997 6152</a>
                <a href="#"><i class="far fa-map-pin"></i> Lagos, Nigeria</a>
            </div>
        </div>
        <div class="footer-col">
            <h4>Platform</h4>
            <a href="about.php">About Us</a>
            <a href="insights.php">Blog</a>
            <a href="publeesh_ai.php">Publeesh Ai</a>
            <a href="compliance.php">Compliance</a>
        </div>
        <div class="footer-col">
            <h4>Publications</h4>
            <a href="publications.php">Browse Publications</a>
            <a href="submit_manuscript.php">Submit Manuscript</a>
            <a href="start_journal.php">Start a Journal</a>
            <a href="call_for_papers.php">Call for Papers</a>
        </div>
        <div class="footer-col">
            <h4>Advisory</h4>
            <a href="transcript_advisory.php">Transcript Advisory</a>
            <a href="degree_programs.php">Degree Programs</a>
            <a href="study_in_africa.php">Study in Africa</a>
        </div>
        <div class="footer-col">
            <h4>Quick Links</h4>
            <a href="network.php">Join Network</a>
            <a href="institution.php">For Institutions</a>
        </div>
    </div>
</footer>
<div class="footer-bottom">
    <p>© 2026 Afrika Scholar. All rights reserved. &nbsp;&bull;&nbsp; <a href="privacy.php" style="color:rgba(255,255,255,.5)">Privacy Policy</a> &nbsp; <a href="terms.php" style="color:rgba(255,255,255,.5)">Terms of Service</a></p>
    <div style="display:flex;align-items:center;gap:24px">
        <span class="powered-by">Powered by <strong>CycleBreeze●</strong></span>
        <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
</div>
</body>
</html>
