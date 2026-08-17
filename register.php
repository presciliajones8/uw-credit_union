<?php
require_once __DIR__ . '/auth.php';
redirect_if_authenticated();

$registerError = '';
$homeUrl = app_url();
$loginUrl = app_url('login');
$registerUrl = app_url('register');
$authCssUrl = app_url('css/auth.css');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim((string) ($_POST['first_name'] ?? ''));
    $lastName = trim((string) ($_POST['last_name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

    if ($firstName === '' || $lastName === '' || $email === '' || $phone === '' || $password === '' || $confirmPassword === '') {
        $registerError = 'Please complete all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registerError = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $registerError = 'Password must be at least 8 characters long.';
    } elseif ($password !== $confirmPassword) {
        $registerError = 'Passwords do not match.';
    } elseif (!register_user($firstName, $lastName, $email, $phone, $password)) {
        $registerError = 'This email address is already registered or the account could not be created.';
    } else {
        redirect_to('login?registered=1');
    }
}
?>
<?php
header("Content-Security-Policy: default-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; script-src 'self' 'unsafe-inline';");
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UW CREDIT UNION | Create Account</title>
    <meta
      name="description"
      content="Create your UW CREDIT UNION account. Open a digital banking account and manage personal or business finances securely."
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo htmlspecialchars($authCssUrl); ?>" />
  </head>
  <body>
    <main class="auth-page">
      <section class="auth-card" aria-labelledby="register-title">
        <div class="auth-header">
          <a href="<?php echo htmlspecialchars($homeUrl); ?>" class="brand" aria-label="UW CREDIT UNION home">
            <span class="brand-mark" aria-hidden="true">A</span>
            <span class="brand-copy">
              <strong>UW CREDIT UNION</strong>
              <span>Banking</span>
            </span>
          </a>
          <a href="<?php echo htmlspecialchars($homeUrl); ?>" class="text-link">Back to website</a>
        </div>

        <div>
          <h1 id="register-title" class="auth-heading">Create your account</h1>
          <p class="auth-subtitle">Open a secure digital banking account and begin managing your financial life with confidence.</p>
        </div>

        <?php if ($registerError !== ''): ?>
          <div class="auth-message auth-error" role="alert"><?php echo htmlspecialchars($registerError); ?></div>
        <?php endif; ?>

          <form class="form-grid" method="post" action="<?php echo htmlspecialchars($registerUrl); ?>" novalidate>
          <div class="field-row">
            <div class="field-group">
              <label for="first-name">First name</label>
              <input id="first-name" name="first_name" type="text" value="<?php echo htmlspecialchars((string) ($_POST['first_name'] ?? '')); ?>" placeholder="First name" required aria-invalid="false" />
            </div>
            <div class="field-group">
              <label for="last-name">Last name</label>
              <input id="last-name" name="last_name" type="text" value="<?php echo htmlspecialchars((string) ($_POST['last_name'] ?? '')); ?>" placeholder="Last name" required aria-invalid="false" />
            </div>
          </div>

          <div class="field-group">
            <label for="register-email">Email address</label>
            <input id="register-email" name="email" type="email" value="<?php echo htmlspecialchars((string) ($_POST['email'] ?? '')); ?>" placeholder="name@example.com" required aria-invalid="false" autocomplete="email" />
          </div>

          <div class="field-group">
            <label for="phone">Phone number</label>
            <input id="phone" name="phone" type="tel" value="<?php echo htmlspecialchars((string) ($_POST['phone'] ?? '')); ?>" placeholder="+1 (555) 000-0000" required aria-invalid="false" autocomplete="tel" />
          </div>

          <div class="field-group">
            <label for="register-password">Password</label>
            <div class="input-wrap">
              <input id="register-password" name="password" type="password" placeholder="Create a password" required aria-invalid="false" autocomplete="new-password" />
              <button type="button" class="password-toggle" aria-label="Show password">Show</button>
            </div>
          </div>

          <div class="field-group">
            <label for="confirm-password">Confirm password</label>
            <div class="input-wrap">
              <input id="confirm-password" name="confirm_password" type="password" placeholder="Confirm your password" required aria-invalid="false" autocomplete="new-password" />
              <button type="button" class="password-toggle" aria-label="Show password">Show</button>
            </div>
          </div>

          <button class="primary-btn" type="submit">Create account</button>
        </form>

        <div class="auth-meta" aria-live="polite">
          <span class="dot" aria-hidden="true"></span>
          Secure account setup
        </div>

        <div class="auth-footer">
          Already have an account? <a href="<?php echo htmlspecialchars($loginUrl); ?>">Sign in</a>
        </div>
      </section>
    </main>

    <script>
      const passwordFields = document.querySelectorAll('.password-toggle');
      passwordFields.forEach((toggle) => {
        const input = toggle.parentElement.querySelector('input');
        if (!input) return;

        toggle.addEventListener('click', () => {
          const isHidden = input.type === 'password';
          input.type = isHidden ? 'text' : 'password';
          toggle.textContent = isHidden ? 'Hide' : 'Show';
          toggle.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        });
      });

      const form = document.querySelector('form');
      const submitButton = form?.querySelector('button[type="submit"]');
      if (form && submitButton) {
        form.addEventListener('submit', () => {
          if (!form.checkValidity()) {
            return;
          }

          submitButton.disabled = true;
          submitButton.classList.add('is-loading');
          submitButton.textContent = 'Creating account';
        });
      }
    </script>
  </body>
</html>
