<?php
$page_title = 'Sign In';
$mode = $_GET['register'] ?? '0';
$isRegister = $mode === '1';
$errors = []; $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $email = trim($_POST['email'] ?? '');
        $pass  = trim($_POST['password'] ?? '');
        if (!$email || !$pass) { $errors[] = 'Email and password are required.'; }
        else { $errors[] = 'Invalid credentials. Please try again or create an account.'; }
    } elseif (isset($_POST['register'])) {
        $name  = trim($_POST['name'] ?? '');
        $email = trim($_POST['reg_email'] ?? '');
        $pass  = trim($_POST['reg_password'] ?? '');
        if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 8) {
            $errors[] = 'Please fill all fields. Password must be at least 8 characters.';
        } else {
            $success = 'Account created! You can now sign in.';
            $isRegister = false;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?= $isRegister ? 'Create Account' : 'Sign In' ?> | Afrika Scholar</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Georgia&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  :root{
    --accent:#e85d1a;
    --primary:#1a2744;
    --border:#e5e7eb;
    --muted:#6b7280;
    --background:#f8f9ff;
    --foreground:#111827;
  }
  body{font-family:'Inter',sans-serif;background:var(--background);min-height:100vh;display:flex;flex-direction:column;}
  a{text-decoration:none;color:inherit;}

  /* ---- Page ---- */
  .page{flex:1;display:flex;align-items:center;justify-content:center;padding:40px 20px;position:relative;overflow:hidden;}
  .deco-circle-1{position:absolute;top:-120px;right:-120px;width:400px;height:400px;border-radius:50%;background:var(--accent);opacity:.04;pointer-events:none;}
  .deco-circle-2{position:absolute;bottom:-80px;left:-80px;width:300px;height:300px;border-radius:50%;background:var(--primary);opacity:.06;pointer-events:none;}

  /* ---- Card ---- */
  .card{width:100%;max-width:460px;background:#fff;border:1px solid var(--border);border-radius:20px;padding:40px 32px;box-shadow:0 8px 40px rgba(0,0,0,.08);position:relative;z-index:1;}

  /* ---- Header ---- */
  .card-head{text-align:center;margin-bottom:32px;}
  .card-head img{height:44px;margin:0 auto 20px;display:block;}
  .card-head h1{font-size:28px;font-weight:800;font-family:Georgia,serif;color:var(--foreground);margin-bottom:6px;}
  .card-head h1 span{color:var(--accent);}
  .card-head p{font-size:14px;color:var(--muted);}

  /* ---- Alerts ---- */
  .alert{display:flex;align-items:center;gap:8px;padding:10px 16px;border-radius:8px;font-size:13px;margin-bottom:20px;}
  .alert-redirect{background:rgba(232,93,26,.06);border:1px solid rgba(232,93,26,.2);color:var(--accent);}
  .alert-error{background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.25);color:#ef4444;}
  .alert-success{background:rgba(16,185,129,.06);border:1px solid rgba(16,185,129,.25);color:#059669;}
  .alert i{font-size:13px;flex-shrink:0;}

  /* ---- Form ---- */
  .form{display:flex;flex-direction:column;gap:18px;}
  .field-label{display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:6px;}
  .field-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;}
  .field-row .field-label{margin-bottom:0;}
  .forgot-link{font-size:12px;color:var(--accent);font-weight:600;}
  .forgot-link:hover{text-decoration:underline;}
  .input-wrap{position:relative;display:flex;align-items:center;}
  .input-icon{position:absolute;left:14px;color:var(--muted);font-size:14px;pointer-events:none;}
  .form-input{width:100%;padding:12px 14px 12px 40px;border:1px solid #111827;border-radius:10px;font-size:14px;font-family:'Inter',sans-serif;color:var(--foreground);background:#fff;outline:none;transition:border-color .15s;}
  .form-input:focus{border-color:var(--accent);}
  .form-input.no-icon{padding-left:14px;}
  .eye-btn{position:absolute;right:14px;background:none;border:none;cursor:pointer;color:var(--muted);padding:0;display:flex;align-items:center;font-size:14px;}
  .eye-btn:hover{color:var(--foreground);}
  .form-input.has-eye{padding-right:40px;}
  .remember-row{display:flex;align-items:center;gap:10px;cursor:pointer;}
  .remember-row input{width:15px;height:15px;accent-color:var(--accent);flex-shrink:0;cursor:pointer;}
  .remember-row span{font-size:13px;color:var(--muted);}

  /* ---- Submit button ---- */
  .btn-submit{margin-top:8px;width:100%;padding:14px;border-radius:10px;border:none;background:#381b92;color:#fff;font-size:15px;font-weight:700;font-family:'Inter',sans-serif;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:opacity .15s;}
  .btn-submit:hover{opacity:.88;}
  .btn-submit:disabled{opacity:.72;cursor:not-allowed;}
  @keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
  .spin{animation:spin .8s linear infinite;}

  /* ---- Divider ---- */
  .divider{display:flex;align-items:center;gap:12px;margin:24px 0 16px;}
  .divider-line{flex:1;height:1px;background:var(--border);}
  .divider-text{font-size:12px;color:var(--muted);white-space:nowrap;}

  /* ---- Create account link ---- */
  .create-btn{display:block;text-align:center;padding:12px;border-radius:10px;border:1.5px solid var(--border);font-size:14px;font-weight:600;color:var(--foreground);transition:border-color .15s;}
  .create-btn:hover{border-color:var(--accent);}

  /* ---- Select ---- */
  select.form-input{padding-left:14px;cursor:pointer;}
</style>
</head>
<body>

<!-- Page -->
<div class="page">
  <div class="deco-circle-1"></div>
  <div class="deco-circle-2"></div>

  <div class="card">

    <!-- Header -->
    <div class="card-head">
      <a href="index.php"><img src="asset/logo.png" alt="Afrika Scholar" style="height:44px;margin:0 auto 20px;display:block;"/></a>
      <?php if($isRegister): ?>
        <h1>Create <span>Account</span></h1>
        <p>Join the Afrika Scholar academic community</p>
      <?php else: ?>
        <h1>Welcome <span>Back</span></h1>
        <p>Sign in to your Afrika Scholar account</p>
      <?php endif; ?>
    </div>

    <!-- Alerts -->
    <?php if(!empty($errors)): ?>
    <div class="alert alert-error">
      <i class="fas fa-exclamation-circle"></i>
      <span><?= htmlspecialchars($errors[0]) ?></span>
    </div>
    <?php endif; ?>

    <?php if($success): ?>
    <div class="alert alert-success">
      <i class="fas fa-check-circle"></i>
      <span><?= htmlspecialchars($success) ?></span>
    </div>
    <?php endif; ?>

    <?php if(!$isRegister): ?>
    <!-- ===== LOGIN FORM ===== -->
    <form method="POST" class="form" id="loginForm">

      <!-- Email -->
      <div>
        <label class="field-label">Email Address</label>
        <div class="input-wrap">
          <i class="fas fa-envelope input-icon"></i>
          <input type="email" name="email" class="form-input" placeholder="you@example.com"
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required/>
        </div>
      </div>

      <!-- Password -->
      <div>
        <div class="field-row">
          <label class="field-label">Password</label>
          <a href="forgot_password.php" class="forgot-link">Forgot password?</a>
        </div>
        <div class="input-wrap">
          <i class="fas fa-lock input-icon"></i>
          <input type="password" name="password" id="loginPass" class="form-input has-eye"
                 placeholder="Enter your password" required/>
          <button type="button" class="eye-btn" onclick="togglePass('loginPass', this)">
            <i class="fas fa-eye"></i>
          </button>
        </div>
      </div>

      <!-- Remember me -->
      <label class="remember-row">
        <input type="checkbox" name="remember" value="1"/>
        <span>Remember me</span>
      </label>

      <!-- Submit -->
      <button type="submit" name="login" class="btn-submit" id="loginBtn"
              onmouseenter="this.style.opacity='.88'" onmouseleave="this.style.opacity='1'">
        Sign In <i class="fas fa-arrow-right"></i>
      </button>
    </form>

    <!-- Divider -->
    <div class="divider">
      <div class="divider-line"></div>
      <span class="divider-text">Don't have an account?</span>
      <div class="divider-line"></div>
    </div>

    <a href="signin.php?register=1" class="create-btn">Create an Account</a>

    <?php else: ?>
    <!-- ===== REGISTER FORM ===== -->
    <form method="POST" class="form" id="registerForm">

      <!-- Full Name -->
      <div>
        <label class="field-label">Full Name</label>
        <div class="input-wrap">
          <i class="fas fa-user input-icon"></i>
          <input type="text" name="name" class="form-input" placeholder="Dr. Jane Osei" required/>
        </div>
      </div>

      <!-- Email -->
      <div>
        <label class="field-label">Email Address</label>
        <div class="input-wrap">
          <i class="fas fa-envelope input-icon"></i>
          <input type="email" name="reg_email" class="form-input" placeholder="you@university.edu" required/>
        </div>
      </div>

      <!-- Password -->
      <div>
        <label class="field-label">Password</label>
        <div class="input-wrap">
          <i class="fas fa-lock input-icon"></i>
          <input type="password" name="reg_password" id="regPass" class="form-input has-eye"
                 placeholder="Minimum 8 characters" required/>
          <button type="button" class="eye-btn" onclick="togglePass('regPass', this)">
            <i class="fas fa-eye"></i>
          </button>
        </div>
      </div>

      <!-- Role -->
      <div>
        <label class="field-label">I am a...</label>
        <select name="role" class="form-input no-icon">
          <option>Researcher / Academic</option>
          <option>Postgraduate Student</option>
          <option>Institution / Organisation</option>
          <option>Other</option>
        </select>
      </div>

      <!-- Submit -->
      <button type="submit" name="register" class="btn-submit"
              onmouseenter="this.style.opacity='.88'" onmouseleave="this.style.opacity='1'">
        Create Account <i class="fas fa-arrow-right"></i>
      </button>
    </form>

    <!-- Divider -->
    <div class="divider">
      <div class="divider-line"></div>
      <span class="divider-text">Already have an account?</span>
      <div class="divider-line"></div>
    </div>

    <a href="signin.php" class="create-btn">Sign In Instead</a>

    <?php endif; ?>

    <!-- Terms -->
    <p style="text-align:center;font-size:12px;color:var(--muted);margin-top:24px;">
      By continuing, you agree to our
      <a href="terms.php" style="color:var(--accent)">Terms of Service</a> and
      <a href="privacy.php" style="color:var(--accent)">Privacy Policy</a>
    </p>

  </div>
</div>

<script>
function togglePass(inputId, btn) {
  const input = document.getElementById(inputId);
  const icon  = btn.querySelector('i');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.replace('fa-eye', 'fa-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.replace('fa-eye-slash', 'fa-eye');
  }
}

// Loading state on submit
['loginForm','registerForm'].forEach(id => {
  const form = document.getElementById(id);
  if (!form) return;
  form.addEventListener('submit', () => {
    const btn = form.querySelector('.btn-submit');
    if (!btn) return;
    btn.disabled = true;
    btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="spin"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg> Signing In...';
  });
});
</script>

</body>
</html>
