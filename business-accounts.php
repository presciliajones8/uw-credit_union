<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Business Accounts | UW CREDIT UNION | Modern Banking & Personal Finance</title>
   <meta
     name="description"
     content="Business checking and savings accounts at UW CREDIT UNION. Manage business finances with specialized accounts, multiple user access, and integrated tools."
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

     .accounts-grid {
       display: grid;
       grid-template-columns: repeat(3, minmax(0, 1fr));
       gap: 24px;
       margin-top: 40px;
     }
     .account-card {
       padding: 32px 28px;
       border-radius: 24px;
       background: var(--surface);
       border: 1px solid var(--border);
       box-shadow: var(--shadow-sm);
       transition: transform 0.25s ease, box-shadow 0.25s ease;
     }
     .account-card:hover {
       transform: translateY(-4px);
       box-shadow: var(--shadow-md);
     }
     .account-icon {
       width: 56px;
       height: 56px;
       display: grid;
       place-items: center;
       border-radius: 16px;
       background: var(--secondary-soft);
       color: var(--secondary);
       font-weight: 700;
       font-size: 1.5rem;
       margin-bottom: 20px;
     }
     .account-card h3 { font-size: 1.4rem; margin-bottom: 12px; }
     .account-card p { margin-bottom: 20px; }
     .features-list {
       display: grid;
       gap: 12px;
       margin-bottom: 24px;
     }
     .features-list li {
       display: flex;
       align-items: center;
       gap: 10px;
       color: var(--muted);
       font-size: 0.9rem;
     }
     .features-list li::before {
       content: "✓";
       color: var(--accent);
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
       .accounts-grid, .footer-grid {
         grid-template-columns: repeat(2, minmax(0, 1fr));
       }
     }

     @media (max-width: 760px) {
       .section { padding: 80px 0; }
       .page-header { padding: 100px 0 60px; }
       .accounts-grid, .footer-grid {
         grid-template-columns: 1fr;
       }
       .header-inner { min-height: 72px; }
       .brand-copy span { display: none; }
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
         <span class="eyebrow">Business Banking</span>
         <h1>Business accounts built for growth.</h1>
         <p style="margin-top: 20px; max-width: 600px;">
           Manage your business finances with specialized checking and savings accounts designed for cash flow, growth, and operational efficiency.
         </p>
         <div style="margin-top: 30px;">
           <a href="register.php" class="btn btn-primary">Open Business Account</a>
           <a href="business-banking.php" class="btn btn-secondary">View Business Solutions</a>
         </div>
       </div>
     </header>

     <section class="section">
       <div class="container">
         <div class="section-header">
           <h2>Business checking accounts</h2>
           <p>Choose the checking account that matches your business transaction volume and needs.</p>
         </div>

         <div class="accounts-grid">
           <article class="account-card">
             <div class="account-icon" aria-hidden="true">▣</div>
             <h3>Basic Business Checking</h3>
             <p>Essential business checking for startups and small businesses with moderate transaction needs.</p>
             <ul class="features-list">
               <li>Up to 200 free transactions per month</li>
               <li>No monthly maintenance fee</li>
               <li>Free debit card included</li>
               <li>Online banking access</li>
               <li>Mobile check deposit</li>
             </ul>
             <a href="register.php" class="btn btn-primary">Open Account</a>
           </article>

           <article class="account-card">
             <div class="account-icon" aria-hidden="true">◍</div>
             <h3>Growth Business Checking</h3>
             <p>Enhanced features for growing businesses with higher transaction volumes and cash management needs.</p>
             <ul class="features-list">
               <li>Up to 500 free transactions per month</li>
               <li>Multiple user access</li>
               <li>Integrated payroll services</li>
               <li>Advanced cash management tools</li>
               <li>Priority customer support</li>
             </ul>
             <a href="register.php" class="btn btn-primary">Open Account</a>
           </article>

           <article class="account-card">
             <div class="account-icon" aria-hidden="true">⎈</div>
             <h3>Enterprise Business Checking</h3>
             <p>Comprehensive banking solution for established businesses with complex financial operations.</p>
             <ul class="features-list">
               <li>Unlimited transactions</li>
               <li>Dedicated relationship manager</li>
               <li>Treasury management services</li>
               <li>Custom reporting solutions</li>
               <li>International banking services</li>
             </ul>
             <a href="register.php" class="btn btn-primary">Open Account</a>
           </article>
         </div>
       </div>
     </section>

     <section class="section" style="background: var(--surface);">
       <div class="container">
         <div class="section-header">
           <h2>Business savings accounts</h2>
           <p>Build your business reserves with savings options designed for commercial needs.</p>
         </div>

         <div class="accounts-grid">
           <article class="account-card">
             <div class="account-icon" aria-hidden="true">◎</div>
             <h3>Business Savings</h3>
             <p>Standard business savings for building reserves and managing excess cash flow.</p>
             <ul class="features-list">
               <li>Competitive interest rates</li>
               <li>No minimum balance requirement</li>
               <li>Online transfers between accounts</li>
               <li>FDIC insurance</li>
               <li>Monthly statements</li>
             </ul>
             <a href="register.php" class="btn btn-primary">Open Account</a>
           </article>

           <article class="account-card">
             <div class="account-icon" aria-hidden="true">↗</div>
             <h3>Money Market Business</h3>
             <p>Higher interest rates with check-writing privileges for businesses that want liquidity and growth.</p>
             <ul class="features-list">
               <li>Higher interest rates</li>
               <li>Limited check-writing</li>
               <li>Tiered interest structure</li>
               <li>FDIC insurance</li>
               <li>Online account management</li>
             </ul>
             <a href="register.php" class="btn btn-primary">Open Account</a>
           </article>

           <article class="account-card">
             <div class="account-icon" aria-hidden="true">⇄</div>
             <h3>Business CDs</h3>
             <p>Lock in guaranteed returns with fixed-term certificates of deposit for business funds.</p>
             <ul class="features-list">
               <li>Fixed interest rates</li>
               <li>Flexible terms (3 months to 5 years)</li>
               <li>Guaranteed returns</li>
               <li>FDIC insurance</li>
               <li>Automatic renewal options</li>
             </ul>
             <a href="register.php" class="btn btn-primary">Open Account</a>
           </article>
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