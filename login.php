<?php
require_once __DIR__ . '/auth.php';
redirect_if_authenticated();

$loginError = '';
$homeUrl = app_url();
$loginUrl = app_url('login');
$registerUrl = app_url('register');
$authCssUrl = app_url('css/auth.css');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $loginError = 'Please enter both your email address and password.';
    } elseif (!login_user($email, $password)) {
        $loginError = 'Invalid email or password. Please try again.';
    } else {
        redirect_to('dash.php');
    }
}

if (isset($_GET['registered']) && $_GET['registered'] === '1') {
    $loginSuccess = 'Registration successful. Please sign in to continue.';
} else {
    $loginSuccess = '';
}
?>
<?php
header("Content-Security-Policy: default-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; script-src 'self' 'unsafe-inline';");
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UW CREDIT UNION | Sign In</title>
    <meta
      name="description"
      content="Secure sign in for UW CREDIT UNION customers. Access your account, manage spending, savings, and digital banking tools."
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo htmlspecialchars($authCssUrl); ?>" />
  </head>
  <body>
    <main class="auth-page">
      <section class="auth-card" aria-labelledby="login-title">
        <div class="auth-header">
          <a href="<?php echo htmlspecialchars(app_url()); ?>" class="brand" aria-label="UW CREDIT UNION home">
            <span class="brand-mark" aria-hidden="true">A</span>
            <span class="brand-copy">
              <strong>UW CREDIT UNION</strong>
              <span>Banking</span>
            </span>
          </a>
          <a href="<?php echo htmlspecialchars($homeUrl); ?>" class="text-link">Back to website</a>
        </div>

        <div>
          <h1 id="login-title" class="auth-heading">Welcome back</h1>
          <p class="auth-subtitle">Sign in to manage your accounts, monitor spending, and stay in control of your finances.</p>
        </div>

        <?php if ($loginSuccess !== ''): ?>
          <div class="auth-message auth-success" role="alert"><?php echo htmlspecialchars($loginSuccess); ?></div>
        <?php endif; ?>

        <?php if ($loginError !== ''): ?>
          <div class="auth-message auth-error" role="alert"><?php echo htmlspecialchars($loginError); ?></div>
        <?php endif; ?>

          <form class="form-grid" method="post" action="<?php echo htmlspecialchars($loginUrl); ?>" novalidate>
          <div class="field-group">
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" value="<?php echo htmlspecialchars((string) ($_POST['email'] ?? '')); ?>" placeholder="name@example.com" required aria-invalid="false" autocomplete="email" />
          </div>

          <div class="field-group">
            <label for="password">Password</label>
            <div class="input-wrap">
              <input id="password" name="password" type="password" placeholder="Enter your password" required aria-invalid="false" autocomplete="current-password" />
              <button type="button" class="password-toggle" aria-label="Show password">Show</button>
            </div>
          </div>

          <div class="inline-meta">
            <label class="checkbox" for="remember-me">
              <input id="remember-me" name="remember_me" type="checkbox" />
              <span>Remember me</span>
            </label>
            <a href="<?php echo htmlspecialchars($homeUrl); ?>" class="helper-link">Need help?</a>
          </div>

          <button class="primary-btn" type="submit">Sign in</button>
        </form>

        <div class="auth-meta" aria-live="polite">
          <span class="dot" aria-hidden="true"></span>
          Protected banking access
        </div>

        <div class="auth-footer">
          Don’t have an account? <a href="<?php echo htmlspecialchars($registerUrl); ?>">Create one</a>
        </div>
      </section>
    </main>

    <script>
      const toggle = document.querySelector('.password-toggle');
      const passwordInput = document.getElementById('password');
      if (toggle && passwordInput) {
        toggle.addEventListener('click', () => {
          const isHidden = passwordInput.type === 'password';
          passwordInput.type = isHidden ? 'text' : 'password';
          toggle.textContent = isHidden ? 'Hide' : 'Show';
          toggle.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        });
      }

      const form = document.querySelector('form');
      const submitButton = form?.querySelector('button[type="submit"]');
      if (form && submitButton) {
        form.addEventListener('submit', () => {
          if (!form.checkValidity()) {
            return;
          }

          submitButton.disabled = true;
          submitButton.classList.add('is-loading');
          submitButton.textContent = 'Signing in';
        });
      }
    </script>
  </body>
</html>
