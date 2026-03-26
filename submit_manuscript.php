<?php
$page_title = 'Submit Manuscript';
require_once 'helpers.php';
$journals = read_csv('journals.csv');

$step = (int)($_POST['step'] ?? 1);
$success = false;
$errors = [];

$data = [
    'journal_id'   => trim($_POST['journal_id'] ?? ''),
    'title'        => trim($_POST['title'] ?? ''),
    'abstract'     => trim($_POST['abstract'] ?? ''),
    'keywords'     => trim($_POST['keywords'] ?? ''),
    'article_type' => trim($_POST['article_type'] ?? ''),
    'discipline'   => trim($_POST['discipline'] ?? ''),
    'authors'      => trim($_POST['authors'] ?? ''),
    'affiliations' => trim($_POST['affiliations'] ?? ''),
    'corresponding_email' => trim($_POST['corresponding_email'] ?? ''),
    'confirm_original'    => isset($_POST['confirm_original']) ? '1' : '',
    'confirm_ethics'      => isset($_POST['confirm_ethics']) ? '1' : '',
    'confirm_disclosure'  => isset($_POST['confirm_disclosure']) ? '1' : '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['next_step'])) {
        $next = (int)$_POST['next_step'];
        if ($next === 2 && !$data['journal_id']) { $errors[] = 'Please select a journal.'; }
        elseif ($next === 3 && (!$data['title'] || !$data['abstract'])) { $errors[] = 'Title and abstract are required.'; }
        elseif ($next === 4 && !$data['authors']) { $errors[] = 'Author name(s) are required.'; }
        if (empty($errors)) $step = $next;
    } elseif (isset($_POST['prev_step'])) {
        $step = max(1, (int)$_POST['prev_step']);
    } elseif (isset($_POST['submit_manuscript'])) {
        if (!$data['confirm_original'] || !$data['confirm_ethics']) {
            $errors[] = 'You must confirm all declarations.'; $step = 6;
        } else {
            $id = get_next_id('manuscripts.csv');
            $jname = '';
            foreach ($journals as $j) { if ($j['id'] === $data['journal_id']) { $jname = $j['name']; break; } }
            append_csv('manuscripts.csv', [$id, $data['title'], $data['authors'], $data['journal_id'], $data['discipline'], $data['abstract'], $data['keywords'], date('Y-m-d'), 'submitted']);
            $success = true; $step = 7;
        }
    }
}

include 'header.php';

$step_labels = ['Journal','Author','Manuscript','Authors','Upload','Declarations','Confirmation'];
$step_icons  = ['fas fa-book-open','fas fa-search','fas fa-file-alt','fas fa-user-friends','fas fa-upload','fas fa-shield-alt','fas fa-check-circle'];
?>

<style>
/* ── Hero ── */
.sm-hero{position:relative;overflow:hidden;min-height:400px;display:flex;align-items:center;justify-content:center}
.sm-hero-bg{position:absolute;inset:0;background:url('asset/about-conference.jpg') center/cover no-repeat}
.sm-hero-overlay{position:absolute;inset:0;background:rgba(10,25,60,.85)}
.sm-hero-dots{position:absolute;inset:0;opacity:.10;background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:24px 24px}
.sm-hero-content{position:relative;z-index:1;text-align:center;padding:80px 20px;max-width:700px;margin:0 auto}
.sm-hero-eyebrow{font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--orange);margin-bottom:16px}
.sm-hero h1{color:#fff;font-size:clamp(2rem,5vw,3rem);font-weight:800;margin:0 0 20px}
.sm-hero p{color:rgba(255,255,255,.8);font-size:17px;margin:0}

/* ── Section helpers ── */
.sm-section{padding:72px 0}
.sm-section-muted{background:#f8f9ff}
.container{max-width:1200px;margin:0 auto;padding:0 24px}
.sm-section-head{text-align:center;margin-bottom:48px}
.sm-section-head h2{font-size:clamp(1.6rem,3vw,2.2rem);font-weight:800;color:var(--navy);margin:0 0 12px}
.sm-section-head p{color:var(--text-gray);font-size:15px;max-width:560px;margin:0 auto}

/* ── Steps row ── */
.sm-steps{display:flex;flex-wrap:wrap;justify-content:center;gap:12px;max-width:960px;margin:0 auto 48px}
.sm-step-wrap{display:flex;align-items:center;gap:10px}
.sm-step-card{width:168px;background:#fff;border:1px solid var(--border);border-radius:12px;padding:20px 16px;text-align:center;transition:box-shadow .2s,transform .2s}
.sm-step-card:hover{box-shadow:0 6px 24px rgba(10,25,60,.08);transform:translateY(-2px)}
.sm-step-icon{width:40px;height:40px;border-radius:50%;background:rgba(232,101,26,.10);display:flex;align-items:center;justify-content:center;margin:0 auto 10px;color:var(--orange);font-size:16px}
.sm-step-card h3{font-size:13px;font-weight:700;color:var(--navy);margin:0 0 4px}
.sm-step-card p{font-size:12px;color:var(--text-gray);margin:0}
.sm-step-arrow{color:var(--text-gray);font-size:14px;opacity:.5}
@media(max-width:700px){.sm-step-arrow{display:none}}

/* ── Start card ── */
.sm-start-card{max-width:600px;margin:0 auto;border:1px solid var(--border);border-radius:16px;padding:40px;text-align:center;background:#fff}
.sm-start-card h3{font-size:22px;font-weight:700;color:var(--navy);margin:0 0 8px}
.sm-start-card .sub{color:var(--text-gray);font-size:14px;margin:0 0 28px}
.sm-highlights{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px}
.sm-highlight{padding:16px;background:#f8f9ff;border-radius:10px;text-align:center}
.sm-highlight i{font-size:22px;color:var(--orange);margin-bottom:8px;display:block}
.sm-highlight p{font-size:13px;font-weight:600;color:var(--navy);margin:0}
.sm-begin-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:14px;background:var(--orange);color:#fff;border:none;border-radius:8px;font-size:16px;font-weight:700;cursor:pointer;transition:background .2s}
.sm-begin-btn:hover{background:rgba(232,101,26,.85)}
.sm-guidelines-note{margin-top:16px;font-size:13px;color:var(--text-gray)}
.sm-guidelines-note a{color:var(--orange);text-decoration:none}
.sm-guidelines-note a:hover{text-decoration:underline}

/* ── Progress bar ── */
.sm-progress-bar{background:#fff;border-bottom:1px solid var(--border);padding:16px 24px}
.sm-progress-bar .container{max-width:760px}
.sm-progress-meta{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}
.sm-progress-meta .step-count{font-size:13px;color:var(--text-gray)}
.sm-progress-meta .step-name{font-size:13px;color:var(--orange);font-weight:600}
.sm-progress-track{background:#f3f4f6;border-radius:4px;height:4px;margin-bottom:10px}
.sm-progress-fill{background:var(--navy);height:4px;border-radius:4px;transition:width .3s}
.sm-progress-icons{display:flex;justify-content:space-between}
.sm-progress-icons i{font-size:16px}
</style>

<!-- Hero -->
<section class="sm-hero">
  <div class="sm-hero-bg"></div>
  <div class="sm-hero-overlay"></div>
  <div class="sm-hero-dots"></div>
  <div class="sm-hero-content">
    <p class="sm-hero-eyebrow">Submit Your Manuscript</p>
    <h1>Submit Your Manuscript</h1>
    <p>Share your research with the world through Afrika Scholar's peer-reviewed journals.</p>
  </div>
</section>

<?php if ($step === 1 && !isset($_POST['next_step'])): ?>

<!-- Submission Process overview -->
<section class="sm-section">
  <div class="container">
    <div class="sm-section-head">
      <h2>Submission Process</h2>
      <p>Our streamlined 5-step process from submission to publication</p>
    </div>

    <?php
    $process_steps = [
      ['fas fa-book-open',  'Select Journal',    'Choose the right journal for your research'],
      ['fas fa-file-alt',   'Prepare Manuscript','Format according to guidelines'],
      ['fas fa-upload',     'Submit',            'Upload your manuscript and files'],
      ['fas fa-user-friends','Peer Review',      'Expert evaluation of your work'],
      ['fas fa-check-circle','Publication',      'Get published and indexed'],
    ];
    ?>

    <div class="sm-steps">
      <?php foreach ($process_steps as $i => $s): ?>
      <div class="sm-step-wrap">
        <div class="sm-step-card">
          <div class="sm-step-icon"><i class="<?= $s[0] ?>"></i></div>
          <h3><?= $s[1] ?></h3>
          <p><?= $s[2] ?></p>
        </div>
        <?php if ($i < count($process_steps) - 1): ?>
        <span class="sm-step-arrow"><i class="fas fa-arrow-right"></i></span>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Start card -->
    <div class="sm-start-card">
      <h3>Start Your Submission</h3>
      <p class="sub">The full manuscript submission form will guide you through each step</p>

      <div class="sm-highlights">
        <div class="sm-highlight">
          <i class="fas fa-clock"></i>
          <p>4-6 Week Review</p>
        </div>
        <div class="sm-highlight">
          <i class="fas fa-user-friends"></i>
          <p>Double-Blind Review</p>
        </div>
        <div class="sm-highlight">
          <i class="fas fa-shield-alt"></i>
          <p>Open Access</p>
        </div>
      </div>

      <form method="POST">
        <input type="hidden" name="step" value="1">
        <?php foreach ($data as $k => $v): ?>
        <input type="hidden" name="<?= $k ?>" value="<?= htmlspecialchars($v) ?>">
        <?php endforeach; ?>
        <button type="submit" name="next_step" value="1" class="sm-begin-btn">
          Begin Submission <i class="fas fa-arrow-right"></i>
        </button>
      </form>

      <p class="sm-guidelines-note">
        Need help? <a href="peer_review_policy.php">View our submission guidelines</a>
      </p>
    </div>

  </div>
</section>

<?php else: ?>

<!-- Step progress bar -->
<div class="sm-progress-bar">
  <div class="container">
    <div class="sm-progress-meta">
      <span class="step-count">Step <?= min($step, 7) ?> of 7</span>
      <span class="step-name"><?= $step_labels[min($step, 7) - 1] ?? '' ?></span>
    </div>
    <div class="sm-progress-track">
      <div class="sm-progress-fill" style="width:<?= (min($step,7)/7*100) ?>%"></div>
    </div>
    <div class="sm-progress-icons">
      <?php foreach ($step_icons as $i => $icon): ?>
      <i class="<?= $icon ?>" style="color:<?= ($i+1)<$step ? 'var(--orange)' : (($i+1)===$step ? 'var(--navy)' : '#d1d5db') ?>"></i>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<section class="sm-section sm-section-muted">
  <div class="container" style="max-width:760px">

    <?php if (!empty($errors)): ?>
    <div class="alert alert-error"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
    <?php endif; ?>

    <?php if ($success || $step === 7): ?>
    <!-- SUCCESS -->
    <div style="background:#fff;border:1px solid var(--border);border-radius:16px;padding:60px;text-align:center">
      <div style="width:72px;height:72px;background:rgba(232,101,26,.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:32px;color:var(--orange)">
        <i class="fas fa-check-circle"></i>
      </div>
      <h2 style="font-size:24px;font-weight:800;margin-bottom:12px">Manuscript Submitted Successfully!</h2>
      <p style="color:var(--text-gray);margin-bottom:8px">Your manuscript <strong style="color:var(--navy)">"<?= htmlspecialchars(substr($data['title'],0,60)) ?>..."</strong> has been received.</p>
      <p style="color:var(--text-gray);font-size:14px;margin-bottom:32px">Our editorial team will review your submission and you will receive an acknowledgement email within 48 hours. The peer review process typically takes 4-6 weeks.</p>
      <div style="display:flex;gap:12px;justify-content:center">
        <a href="submit_manuscript.php" class="btn btn-outline">Submit Another</a>
        <a href="publications.php" class="btn btn-navy">Browse Publications</a>
      </div>
    </div>

    <?php else: ?>
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="step" value="<?= $step ?>">
      <?php foreach ($data as $k => $v): ?>
      <input type="hidden" name="<?= $k ?>" value="<?= htmlspecialchars($v) ?>">
      <?php endforeach; ?>

      <?php if ($step === 1): ?>
      <!-- STEP 1: Select Journal -->
      <div style="background:#fff;border:1px solid var(--border);border-radius:16px;padding:40px">
        <h3 style="font-size:20px;font-weight:700;margin-bottom:6px">Select a Journal</h3>
        <p style="color:var(--text-gray);font-size:14px;margin-bottom:28px">Choose the journal that best fits your research discipline and scope.</p>
        <div style="display:flex;flex-direction:column;gap:12px">
          <?php foreach ($journals as $j): ?>
          <label style="display:flex;align-items:center;gap:14px;padding:16px;border:2px solid <?= $data['journal_id']===$j['id']?'var(--orange)':'var(--border)' ?>;border-radius:10px;cursor:pointer">
            <input type="radio" name="journal_id" value="<?= $j['id'] ?>" <?= $data['journal_id']===$j['id']?'checked':'' ?> style="accent-color:var(--orange);width:18px;height:18px">
            <div style="flex:1">
              <div style="font-size:15px;font-weight:700;color:var(--navy)"><?= htmlspecialchars($j['name']) ?></div>
              <div style="font-size:13px;color:var(--text-gray);margin-top:2px"><?= htmlspecialchars($j['category']) ?> &bull; <?= htmlspecialchars($j['frequency']) ?> <?= $j['open_access']==='1'?'&bull; <span style="color:var(--orange)">Open Access</span>':'' ?></div>
            </div>
          </label>
          <?php endforeach; ?>
        </div>
        <div style="display:flex;justify-content:flex-end;margin-top:24px">
          <button type="submit" name="next_step" value="2" class="btn btn-navy">Save & Continue →</button>
        </div>
      </div>

      <?php elseif ($step === 2): ?>
      <!-- STEP 2: Manuscript Details -->
      <div style="background:#fff;border:1px solid var(--border);border-radius:16px;padding:40px">
        <h3 style="font-size:20px;font-weight:700;margin-bottom:6px">Manuscript Details</h3>
        <p style="color:var(--text-gray);font-size:14px;margin-bottom:28px">Provide the core metadata for your manuscript.</p>
        <div class="form-group">
          <label class="form-label">Article Title</label>
          <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($data['title']) ?>" placeholder="Enter the full title of your manuscript">
        </div>
        <div class="form-group">
          <label class="form-label">Abstract (max 300 words)</label>
          <textarea name="abstract" class="form-control" rows="6" placeholder="Enter your abstract..."><?= htmlspecialchars($data['abstract']) ?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Keywords (comma-separated)</label>
          <input type="text" name="keywords" class="form-control" value="<?= htmlspecialchars($data['keywords']) ?>" placeholder="e.g., public health, governance, East Africa">
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
          <div class="form-group">
            <label class="form-label">Article Type</label>
            <select name="article_type" class="form-control">
              <option value="">Select type</option>
              <?php foreach (['Original Research','Review Article','Case Study','Short Communication','Opinion/Perspective','Book Review'] as $t): ?>
              <option <?= $data['article_type']===$t?'selected':'' ?>><?= $t ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Discipline</label>
            <select name="discipline" class="form-control">
              <option value="">Select discipline</option>
              <?php foreach (['STEM','Social Sciences','Law & Governance','Business & Economics','Environmental Sciences','Health Sciences','Humanities'] as $d): ?>
              <option <?= $data['discipline']===$d?'selected':'' ?>><?= $d ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div style="background:rgba(232,101,26,.05);border:1px solid rgba(232,101,26,.2);border-radius:8px;padding:12px;display:flex;gap:8px;align-items:flex-start">
          <i class="fas fa-lightbulb" style="color:var(--orange);margin-top:2px"></i>
          <span style="font-size:13px;color:var(--text-gray)">Accurate metadata improves discoverability. Use specific, descriptive keywords.</span>
        </div>
        <div style="display:flex;justify-content:space-between;margin-top:24px">
          <button type="submit" name="prev_step" value="1" class="btn btn-outline">← Back</button>
          <button type="submit" name="next_step" value="3" class="btn btn-navy">Save & Continue →</button>
        </div>
      </div>

      <?php elseif ($step === 3): ?>
      <!-- STEP 3: Authors -->
      <div style="background:#fff;border:1px solid var(--border);border-radius:16px;padding:40px">
        <h3 style="font-size:20px;font-weight:700;margin-bottom:6px">Author Information</h3>
        <p style="color:var(--text-gray);font-size:14px;margin-bottom:28px">Provide details for all authors. List in order of contribution.</p>
        <div class="form-group">
          <label class="form-label">Author Name(s) *</label>
          <input type="text" name="authors" class="form-control" value="<?= htmlspecialchars($data['authors']) ?>" placeholder="Dr. Jane Osei, Prof. Kwame Mensah">
        </div>
        <div class="form-group">
          <label class="form-label">Institutional Affiliations</label>
          <textarea name="affiliations" class="form-control" rows="3" placeholder="University of Ghana, Legon; University of Lagos, Nigeria"><?= htmlspecialchars($data['affiliations']) ?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Corresponding Author Email</label>
          <input type="email" name="corresponding_email" class="form-control" value="<?= htmlspecialchars($data['corresponding_email']) ?>" placeholder="corresponding@university.edu">
        </div>
        <div style="display:flex;justify-content:space-between;margin-top:24px">
          <button type="submit" name="prev_step" value="2" class="btn btn-outline">← Back</button>
          <button type="submit" name="next_step" value="4" class="btn btn-navy">Save & Continue →</button>
        </div>
      </div>

      <?php elseif ($step === 4): ?>
      <!-- STEP 4: File Upload -->
      <div style="background:#fff;border:1px solid var(--border);border-radius:16px;padding:40px">
        <h3 style="font-size:20px;font-weight:700;margin-bottom:6px">Upload Manuscript Files</h3>
        <p style="color:var(--text-gray);font-size:14px;margin-bottom:28px">Upload your manuscript and any supplementary files.</p>
        <div style="border:2px dashed var(--border);border-radius:12px;padding:48px;text-align:center;margin-bottom:24px">
          <i class="fas fa-cloud-upload-alt" style="font-size:48px;color:var(--text-gray);opacity:.4;margin-bottom:16px;display:block"></i>
          <p style="font-size:15px;font-weight:600;color:var(--navy);margin-bottom:4px">Drop your manuscript file here</p>
          <p style="font-size:13px;color:var(--text-gray);margin-bottom:16px">Accepted formats: .docx, .doc, .pdf (max 20MB)</p>
          <input type="file" name="manuscript_file" accept=".docx,.doc,.pdf" style="display:none" id="fileInput">
          <button type="button" onclick="document.getElementById('fileInput').click()" class="btn btn-outline">Choose File</button>
        </div>
        <div style="background:#f8f9ff;border-radius:10px;padding:20px">
          <h4 style="font-size:14px;font-weight:700;margin-bottom:12px">Formatting Requirements</h4>
          <ul class="check-list">
            <li><i class="fas fa-check-circle"></i> Times New Roman 12pt, double-spaced</li>
            <li><i class="fas fa-check-circle"></i> Remove author names for double-blind review</li>
            <li><i class="fas fa-check-circle"></i> APA or Vancouver citation style</li>
            <li><i class="fas fa-check-circle"></i> Tables and figures embedded in document</li>
          </ul>
        </div>
        <div style="display:flex;justify-content:space-between;margin-top:24px">
          <button type="submit" name="prev_step" value="3" class="btn btn-outline">← Back</button>
          <button type="submit" name="next_step" value="5" class="btn btn-navy">Save & Continue →</button>
        </div>
      </div>

      <?php elseif ($step === 5): ?>
      <!-- STEP 5: Declarations -->
      <div style="background:#fff;border:1px solid var(--border);border-radius:16px;padding:40px">
        <h3 style="font-size:20px;font-weight:700;margin-bottom:6px">Declarations & Ethics</h3>
        <p style="color:var(--text-gray);font-size:14px;margin-bottom:28px">Please confirm the following declarations before submitting.</p>
        <div style="display:flex;flex-direction:column;gap:16px">
          <?php
          $decls = [
            ['confirm_original',   'I confirm this manuscript is original and has not been published or submitted elsewhere.'],
            ['confirm_ethics',     'I confirm this research was conducted in accordance with ethical guidelines and relevant regulations.'],
            ['confirm_disclosure', 'I confirm all conflicts of interest, funding sources, and author contributions have been disclosed.'],
          ];
          foreach ($decls as $d): ?>
          <label style="display:flex;align-items:flex-start;gap:12px;padding:16px;border:1.5px solid var(--border);border-radius:10px;cursor:pointer">
            <input type="checkbox" name="<?= $d[0] ?>" value="1" <?= $data[$d[0]]?'checked':'' ?> style="accent-color:var(--orange);width:18px;height:18px;flex-shrink:0;margin-top:2px">
            <span style="font-size:14px;color:var(--navy)"><?= $d[1] ?></span>
          </label>
          <?php endforeach; ?>
        </div>
        <div style="display:flex;justify-content:space-between;margin-top:28px">
          <button type="submit" name="prev_step" value="4" class="btn btn-outline">← Back</button>
          <button type="submit" name="submit_manuscript" value="1" class="btn btn-orange" style="padding:12px 32px">Submit Manuscript →</button>
        </div>
      </div>
      <?php endif; ?>
    </form>
    <?php endif; ?>

  </div>
</section>
<?php endif; ?>

<?php include 'footer.php'; ?>
