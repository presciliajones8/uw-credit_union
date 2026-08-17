<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Accounts | UW CREDIT UNION | Modern Banking & Personal Finance</title>
   <meta
     name="description"
     content="Explore personal banking accounts at UW CREDIT UNION. Secure checking and current accounts designed for everyday financial management."
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

     .account-grid {
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
       background: var(--primary-soft);
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

     .compare-table {
       margin-top: 60px;
       border-radius: 24px;
       background: var(--surface);
      border: 1px solid var(--border);
      box-shadow: var(--shadow-sm);
      overflow: hidden;
     }
     .compare-table table {
       width: 100%;
       border-collapse: collapse;
     }
     .compare-table th, .compare-table td {
       padding: 20px 24px;
       text-align: left;
       border-bottom: 1px solid var(--border);
     }
     .compare-table th {
       background: var(--surface-alt);
       font-weight: 700;
       color: var(--primary);
     }
     .compare-table tr:last-child td { border-bottom: 0; }

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
       .account-grid, .footer-grid {
         grid-template-columns: repeat(2, minmax(0, 1fr));
       }
     }

     @media (max-width: 760px) {
       .section { padding: 80px 0; }
       .page-header { padding: 100px 0 60px; }
       .account-grid, .footer-grid {
         grid-template-columns: 1fr;
       }
       .header-inner { min-height: 72px; }
       .brand-copy span { display: none; }
       .compare-table { overflow-x: auto; }
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
         <span class="eyebrow">Personal Banking</span>
         <h1>Accounts designed for everyday banking.</h1>
         <p style="margin-top: 20px; max-width: 600px;">
           Choose the account that fits your lifestyle. From everyday checking to specialized accounts, UW CREDIT UNION offers secure, flexible options for managing your money.
         </p>
         <div style="margin-top: 30px;">
           <a href="register.php" class="btn btn-primary">Open an Account</a>
           <a href="index.html#products" class="btn btn-secondary">Compare Products</a>
         </div>
       </div>
     </header>

     <section class="section">
       <div class="container">
         <div class="section-header">
           <h2>Account options</h2>
           <p>Select the account type that best matches your financial needs and goals.</p>
         </div>

         <div class="account-grid">
           <article class="account-card">
             <div class="account-icon" aria-hidden="true">▣</div>
             <h3>Everyday Checking</h3>
             <p>A straightforward account for daily spending, bill payments, and cash management with no monthly fees.</p>
             <ul class="features-list">
               <li>No monthly maintenance fees</li>
               <li>Free debit card included</li>
               <li>Mobile banking access</li>
               <li>Free online bill pay</li>
               <li>ATM access nationwide</li>
             </ul>
             <a href="register.php" class="btn btn-primary">Open Now</a>
           </article>

           <article class="account-card">
             <div class="account-icon" aria-hidden="true">◎</div>
             <h3>Premium Checking</h3>
             <p>Enhanced features for customers who want more from their everyday banking experience.</p>
             <ul class="features-list">
               <li>Higher transaction limits</li>
               <li>Premium debit card benefits</li>
               <li>Priority customer support</li>
               <li>Waived ATM fees</li>
               <li> overdraft protection</li>
             </ul>
             <a href="register.php" class="btn btn-primary">Open Now</a>
           </article>

           <article class="account-card">
             <div class="account-icon" aria-hidden="true">◍</div>
             <h3>Student Account</h3>
             <p>Designed for students with features that support academic life and financial independence.</p>
             <ul class="features-list">
               <li>No minimum balance</li>
               <li>Student-focused rewards</li>
               <li>Financial education tools</li>
               <li>Mobile-first banking</li>
               <li>Free transfers</li>
             </ul>
             <a href="register.php" class="btn btn-primary">Open Now</a>
           </article>

           <article class="account-card">
             <div class="account-icon" aria-hidden="true">⎈</div>
             <h3>Senior Account</h3>
             <p>Specialized features for customers 55+ with simplified banking and enhanced support.</p>
             <ul class="features-list">
               <li>No monthly fees</li>
               <li>Priority phone support</li>
               <li>Larger statement fonts</li>
               <li>In-branch assistance</li>
               <li>Health account integration</li>
             </ul>
             <a href="register.php" class="btn btn-primary">Open Now</a>
           </article>

           <article class="account-card">
             <div class="account-icon" aria-hidden="true">⇄</div>
             <h3>Joint Account</h3>
             <p>Share banking responsibilities with a partner or family member through a joint account.</p>
             <ul class="features-list">
               <li>Shared account access</li>
               <li>Joint debit cards</li>
               <li>Combined transaction limits</li>
               <li>Shared online banking</li>
               <li>Joint statements</li>
             </ul>
             <a href="register.php" class="btn btn-primary">Open Now</a>
           </article>

           <article class="account-card">
             <div class="account-icon" aria-hidden="true">↗</div>
             <h3>Business Personal</h3>
             <p>A personal account designed for freelancers and sole proprietors who need business features.</p>
             <ul class="features-list">
               <li>Business expense tracking</li>
               <li>Invoice integration</li>
               <li>Tax reporting tools</li>
               <li>Separate business categorization</li>
               <li>Higher transaction limits</li>
             </ul>
             <a href="register.php" class="btn btn-primary">Open Now</a>
           </article>
         </div>
       </div>
     </section>

     <section class="section" style="background: var(--surface);">
       <div class="container">
         <div class="section-header">
           <h2>Account comparison</h2>
           <p>Compare features across our account types to find the best fit for your needs.</p>
         </div>

         <div class="compare-table">
           <table>
             <thead>
               <tr>
                 <th>Feature</th>
                 <th>Everyday Checking</th>
                 <th>Premium Checking</th>
                 <th>Student Account</th>
               </tr>
             </thead>
             <tbody>
               <tr>
                 <td>Monthly Fee</td>
                 <td>$0</td>
                 <td>$15</td>
                 <td>$0</td>
               </tr>
               <tr>
                 <td>Minimum Balance</td>
                 <td>$0</td>
                 <td>$1,000</td>
                 <td>$0</td>
               </tr>
               <tr>
                 <td>Free ATM Transactions</td>
                 <td>Unlimited</td>
                 <td>Unlimited</td>
                 <td>5 per month</td>
               </tr>
               <tr>
                 <td>Mobile Banking</td>
                 <td>✓</td>
                 <td>✓</td>
                 <td>✓</td>
               </tr>
               <tr>
                 <td>Bill Pay</td>
                 <td>Free</td>
                 <td>Free</td>
                 <td>Free</td>
               </tr>
               <tr>
                 <td>Overdraft Protection</td>
                 <td>Optional</td>
                 <td>Included</td>
                 <td>Optional</td>
               </tr>
             </tbody>
           </table>
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