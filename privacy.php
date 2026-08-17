<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Privacy Policy | UW CREDIT UNION | Modern Banking & Personal Finance</title>
   <meta
     name="description"
     content="Privacy policy for UW CREDIT UNION. Learn how we collect, use, and protect your personal information in accordance with financial privacy regulations."
   />
   <meta name="theme-color" content="#0f172a" />
   <link rel="preconnect" href="https://fonts.googleapis.com" />
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
   <link
     href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
     rel="stylesheet"
   />
   <style>
     :root {
       --primary: #0f172a;
       --primary-dark: #0b1220;
       --primary-soft: #eff6ff;
       --secondary: #1d4ed8;
       --secondary-soft: #dbeafe;
       --accent: #22c55e;
       --accent-soft: #dcfce7;
       --surface: #ffffff;
       --surface-alt: #f8fafc;
       --surface-muted: #f1f5f9;
       --text: #111827;
       --muted: #475569;
       --border: #e2e8f0;
       --success: #166534;
       --warning: #9a6700;
       --error: #b91c1c;
       --shadow-sm: 0 8px 24px rgba(15, 23, 42, 0.06);
       --shadow-md: 0 18px 42px rgba(15, 23, 42, 0.12);
       --radius-sm: 12px;
       --radius-md: 18px;
       --radius-lg: 28px;
       --container: 1200px;
     }

     * { box-sizing: border-box; }
     html { scroll-behavior: smooth; }
     body {
       margin: 0;
       font-family: "Inter", "Segoe UI", sans-serif;
       background: #f8fafc;
       color: var(--text);
       line-height: 1.6;
     }

     img { max-width: 100%; display: block; }
     a { text-decoration: none; color: inherit; }
     button, input, select, textarea { font: inherit; }
     ul { list-style: none; padding: 0; margin: 0; }

     .container {
       width: min(var(--container), calc(100% - 32px));
       margin: 0 auto;
     }

     .section {
       padding: 96px 0;
     }

     .section-header {
       max-width: 700px;
       margin-bottom: 40px;
     }
     .eyebrow {
       display: inline-flex;
       align-items: center;
       gap: 8px;
       padding: 8px 12px;
       border-radius: 999px;
       font-size: 12px;
       font-weight: 700;
       letter-spacing: 0.08em;
       text-transform: uppercase;
       background: var(--primary-soft);
       color: var(--secondary);
     }

     h1, h2, h3, h4 {
       margin: 0;
       line-height: 1.1;
       letter-spacing: -0.04em;
       color: var(--primary);
     }
     h1 { font-size: clamp(2.9rem, 5vw, 5rem); }
     h2 { font-size: clamp(2.1rem, 3vw, 3.2rem); }
     h3 { font-size: clamp(1.35rem, 2vw, 1.8rem); }
     p { margin: 0; color: var(--muted); }

     .btn {
       display: inline-flex;
       align-items: center;
       justify-content: center;
       gap: 10px;
       min-height: 52px;
       padding: 0 22px;
       border-radius: 14px;
       font-weight: 600;
       transition: all 0.2s ease;
       border: 1px solid transparent;
       cursor: pointer;
     }
     .btn-primary {
       background: var(--primary);
       color: #fff;
       box-shadow: var(--shadow-sm);
     }
     .btn-primary:hover { background: var(--primary-dark); }
     .btn-secondary {
       background: #fff;
       color: var(--primary);
       border-color: var(--border);
     }
     .btn-secondary:hover {
       background: var(--surface-alt);
     }

     .site-header {
       position: sticky;
       top: 0;
       z-index: 40;
       background: rgba(255,255,255,0.9);
       backdrop-filter: blur(12px);
       border-bottom: 1px solid rgba(226, 232, 240, 0.8);
     }
     .header-inner {
       display: flex;
       align-items: center;
       justify-content: space-between;
       min-height: 82px;
       gap: 20px;
     }
     .brand {
       display: inline-flex;
       align-items: center;
       gap: 12px;
       font-weight: 700;
       color: var(--primary);
     }
     .brand-mark {
       width: 42px; 
       height: 42px;
       border-radius: 12px;
       display: grid;
       place-items: center;
       background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
       color: white;
       font-size: 17px;
       font-weight: 700;
     }
     .brand-copy {
       display: flex;
       flex-direction: column;
       line-height: 1.1;
     }
     .brand-copy strong { letter-spacing: -0.05em; }
     .brand-copy span {
       font-size: 10px;
       color: var(--muted);
       text-transform: uppercase;
       letter-spacing: 0.12em;
     }

     .nav {
       display: flex;
       align-items: center;
       gap: 26px;
       color: var(--muted);
       font-size: 0.96rem;
     }
     .nav a {
       position: relative;
       transition: color 0.2s ease;
     }
     .nav a:hover { color: var(--primary); }
     .nav a::after {
       content: "";
       position: absolute;
       left: 0;
       bottom: -8px;
       width: 100%;
       height: 2px;
       background: var(--secondary);
       opacity: 0;
       transform: scaleX(0.7);
       transition: all 0.2s ease;
     }
     .nav a:hover::after { opacity: 1; transform: scaleX(1); }

     .header-actions {
       display: flex;
       align-items: center;
       gap: 12px;
     }
     .header-actions .btn {
       min-height: 44px;
       padding: 0 18px;
     }
     .mobile-menu-toggle {
       display: none;
       width: 42px;
       height: 42px;
       border: 1px solid var(--border);
       border-radius: 10px;
       background: #fff;
       color: var(--primary);
     }

     .page-header {
       background: linear-gradient(180deg, #f8fafc 0%, #eef6ff 100%);
      padding: 120px 0 80px;
     }

     .privacy-content {
       background: var(--surface);
       border-radius: 28px;
       padding: 48px 40px;
      margin-top: 40px;
      box-shadow: var(--shadow-sm);
    }
    .privacy-section {
      margin-bottom: 40px;
    }
    .privacy-section h3 {
      font-size: 1.4rem;
      margin-bottom: 16px;
    }
    .privacy-section p {
      margin-bottom: 16px;
    }
    .privacy-section ul {
      padding-left: 20px;
      margin-bottom: 16px;
    }
    .privacy-section li {
      margin-bottom: 8px;
      position: relative;
    }
    .privacy-section li::before {
      content: "•";
      position: absolute;
      left: -16px;
      color: var(--secondary);
      font-weight: 700;
    }

     .site-footer {
       background: var(--primary-dark);
       color: rgba(255,255,255,0.79);
       padding: 60px 0 24px;
     }
     .footer-grid {
       display: grid;
       grid-template-columns: 1.2fr repeat(4, minmax(0, 1fr));
       gap: 24px;
     }
     .footer-brand {
       display: flex;
       align-items: center;
       gap: 12px;
       margin-bottom: 18px;
       color: white;
     }
     .footer-brand .brand-mark {
       background: rgba(255,255,255,0.08);
     }
     .footer-col h4 {
       color: white;
       font-size: 1rem;
       margin-bottom: 16px;
     }
     .footer-col a {
       display: block;
       margin-bottom: 10px;
       color: rgba(255,255,255,0.7);
     }
     .footer-base {
       margin-top: 28px;
       padding-top: 20px;
       border-top: 1px solid rgba(255,255,255,0.12);
       display: flex;
       justify-content: space-between;
       gap: 16px;
       flex-wrap: wrap;
     }

     @media (max-width: 1100px) {
       .nav { display: none; }
       .header-actions .btn-login { display: none; }
       .mobile-menu-toggle { display: inline-flex; align-items: center; justify-content: center; }
       .footer-grid {
         grid-template-columns: repeat(2, minmax(0, 1fr));
       }
     }

     @media (max-width: 760px) {
       .section { padding: 80px 0; }
       .page-header { padding: 100px 0 60px; }
       .footer-grid {
         grid-template-columns: 1fr;
       }
       .header-inner { min-height: 72px; }
       .brand-copy span { display: none; }
       .privacy-content { padding: 32px 24px; }
     }

     .mobile-nav {
       display: none;
       padding: 14px 0 18px;
       border-top: 1px solid var(--border);
     }
     .mobile-nav.open { display: block; }
     .mobile-nav-list {
       display: grid;
       gap: 10px;
     }
     .mobile-nav-list a {
       display: block;
       padding: 12px 14px;
       border-radius: 12px;
       background: var(--surface-alt);
       color: var(--primary);
       font-weight: 600;
     }
     .mobile-nav-actions {
       margin-top: 14px;
       display: flex;
       gap: 10px;
     }
   </style>
</head>
<body>
   <header class="site-header">
     <div class="container header-inner">
       <a href="index.html" class="brand" aria-label="UW CREDIT UNION home">
         <span class="brand-mark" aria-hidden="true">A</span>
         <span class="brand-copy">
           <strong>UW CREDIT UNION</strong>
           <span>Banking</span>
         </span>
       </a>

       <nav class="nav" aria-label="Main navigation">
         <a href="accounts.php">Accounts</a>
         <a href="savings.php">Savings</a>
         <a href="loans.php">Loans</a>
         <a href="cards.php">Cards</a>
         <a href="transfers.php">Transfers</a>
         <a href="business-banking.php">Business</a>
         <a href="financial-education.php">Resources</a>
         <a href="about.php">About</a>
       </nav>

       <div class="header-actions">
         <a href="login.php" class="btn btn-ghost btn-login">Login</a>
         <a href="register.php" class="btn btn-primary">Open an Account</a>
         <button class="mobile-menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-nav" aria-label="Toggle menu">
           <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
         </button>
       </div>
     </div>
     <div class="container mobile-nav" id="mobile-nav">
       <nav class="mobile-nav-list" aria-label="Mobile navigation">
         <a href="accounts.php">Accounts</a>
         <a href="savings.php">Savings</a>
         <a href="loans.php">Loans</a>
         <a href="cards.php">Cards</a>
         <a href="transfers.php">Transfers</a>
         <a href="business-banking.php">Business</a>
         <a href="financial-education.php">Resources</a>
         <a href="about.php">About</a>
       </nav>
       <div class="mobile-nav-actions">
         <a class="btn btn-ghost" href="login.php">Login</a>
         <a class="btn btn-primary" href="register.php">Open an Account</a>
       </div>
     </div>
   </header>

   <main>
     <header class="page-header">
       <div class="container">
         <span class="eyebrow">Legal</span>
         <h1>Privacy Policy</h1>
         <p style="margin-top: 20px; max-width: 600px;">
           Your privacy is important to us. This policy explains how we collect, use, and protect your personal information.
         </p>
         <div style="margin-top: 30px;">
           <a href="contact.php" class="btn btn-primary">Contact Us</a>
           <a href="terms.php" class="btn btn-secondary">View Terms</a>
         </div>
       </div>
     </header>

     <section class="section">
       <div class="container">
         <div class="privacy-content">
           <div class="privacy-section">
             <h3>Information We Collect</h3>
             <p>We collect information you provide directly to us, such as when you create an account, apply for a loan, or contact us for support. This may include:</p>
             <ul>
               <li>Name, address, email address, and phone number</li>
               <li>Social Security number and government identification</li>
               <li>Financial information, including account numbers and transaction history</li>
               <li>Employment and income information</li>
               <li>Device information and usage data</li>
             </ul>
           </div>

           <div class="privacy-section">
             <h3>How We Use Your Information</h3>
             <p>We use your information to provide, maintain, and improve our banking services. Specific uses include:</p>
             <ul>
               <li>Processing transactions and managing your accounts</li>
               <li>Verifying your identity and protecting against fraud</li>
               <li>Communicating with you about your accounts and services</li>
               <li>Complying with legal and regulatory requirements</li>
               <li>Analyzing usage patterns to improve our services</li>
             </ul>
           </div>

           <div class="privacy-section">
             <h3>Information Sharing</h3>
             <p>We do not sell your personal information. We may share your information only in specific circumstances:</p>
             <ul>
               <li>With service providers who perform services on our behalf</li>
               <li>When required by law or to protect our rights</li>
               <li>With your consent or at your direction</li>
               <li>In connection with a business transfer or merger</li>
             </ul>
           </div>

           <div class="privacy-section">
             <h3>Data Security</h3>
             <p>We implement industry-standard security measures to protect your information, including encryption, secure authentication, and regular security monitoring. However, no method of transmission over the internet is completely secure.</p>
           </div>

           <div class="privacy-section">
             <h3>Your Rights</h3>
             <p>You have certain rights regarding your personal information, including:</p>
             <ul>
               <li>Access to your personal information</li>
               <li>Correction of inaccurate information</li>
               <li>Deletion of your information (where legally permitted)</li>
               <li>Opt-out of certain data uses</li>
               <li>Restriction of processing of your information</li>
             </ul>
           </div>

           <div class="privacy-section">
             <h3>Changes to This Policy</h3>
             <p>We may update this privacy policy from time to time. We will notify you of any material changes by posting the new policy on our website and updating the effective date.</p>
           </div>

           <div class="privacy-section">
             <h3>Contact Us</h3>
             <p>If you have questions about this privacy policy or our data practices, please contact us through our support channels or visit our contact page.</p>
             <div style="margin-top: 20px;">
               <a href="contact.php" class="btn btn-primary">Contact Support</a>
             </div>
           </div>
         </div>
       </div>
     </section>
   </main>

   <footer class="site-footer">
     <div class="container">
       <div class="footer-grid">
         <div>
           <div class="footer-brand">
             <span class="brand-mark" aria-hidden="true">A</span>
             <strong>UW CREDIT UNION</strong>
           </div>
           <p>Modern digital banking built around trust, straightforward financial tools, and clear account management.</p>
         </div>

         <div class="footer-col">
           <h4>Personal</h4>
           <a href="accounts.php">Accounts</a>
           <a href="savings.php">Savings</a>
           <a href="loans.php">Loans</a>
           <a href="cards.php">Cards</a>
           <a href="transfers.php">Transfers</a>
         </div>

         <div class="footer-col">
           <h4>Business</h4>
           <a href="business-banking.php">Business Banking</a>
           <a href="business-accounts.php">Business Accounts</a>
           <a href="payments.php">Payments</a>
           <a href="business-services.php">Business Services</a>
         </div>

         <div class="footer-col">
           <h4>Resources</h4>
           <a href="financial-education.php">Financial Education</a>
           <a href="faqs.php">FAQs</a>
           <a href="security.php">Security</a>
           <a href="support.php">Support</a>
         </div>

         <div class="footer-col">
           <h4>Company</h4>
           <a href="about.php">About</a>
           <a href="contact.php">Contact</a>
           <a href="careers.php">Careers</a>
           <a href="privacy.php">Privacy</a>
           <a href="terms.php">Terms</a>
         </div>
       </div>

       <div class="footer-base">
         <span>© 2025 UW CREDIT UNION. All rights reserved.</span>
         <span>Designed for secure, dependable digital banking.</span>
       </div>
     </div>
   </footer>

   <script>
     const toggle = document.querySelector('.mobile-menu-toggle');
     const mobileNav = document.getElementById('mobile-nav');
     if (toggle && mobileNav) {
       toggle.addEventListener('click', () => {
         const isOpen = mobileNav.classList.toggle('open');
         toggle.setAttribute('aria-expanded', String(isOpen));
       });
     }
   </script>
</body>
</html>