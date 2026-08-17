<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>FAQs | UW CREDIT UNION | Modern Banking & Personal Finance</title>
   <meta
     name="description"
     content="Frequently asked questions about UW CREDIT UNION banking services. Find answers about accounts, loans, digital banking, and security."
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

     .faq-wrap {
       display: grid;
       grid-template-columns: 0.9fr 1.1fr;
       gap: 26px;
       align-items: start;
     }
     .faq-list {
       background: var(--surface);
       border: 1px solid var(--border);
       border-radius: 22px;
       overflow: hidden;
       box-shadow: var(--shadow-sm);
     }
     .faq-item {
       border-bottom: 1px solid var(--border);
     }
     .faq-item:last-child { border-bottom: 0; }
     .faq-question {
       width: 100%;
       display: flex;
       justify-content: space-between;
       align-items: center;
       gap: 16px;
       padding: 20px 22px;
       background: transparent;
       border: 0;
       text-align: left;
       color: var(--primary);
       font-weight: 600;
       cursor: pointer;
    }
    .faq-question span:last-child {
      font-size: 1.4rem;
      color: var(--secondary);
    }
    .faq-answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.25s ease;
    }
    .faq-answer p {
      padding: 0 22px 20px;
    }
    .faq-item.open .faq-answer { max-height: 200px; }
    .faq-item.open .faq-question span:last-child { transform: rotate(45deg); }

    .contact-box {
      padding: 28px 24px;
      background: linear-gradient(180deg, #ffffff, #f8fafc);
      border: 1px solid var(--border);
      border-radius: 24px;
      box-shadow: var(--shadow-sm);
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
       .faq-wrap, .footer-grid {
         grid-template-columns: 1fr;
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
         <span class="eyebrow">Resources</span>
         <h1>Frequently asked questions.</h1>
         <p style="margin-top: 20px; max-width: 600px;">
           Find answers to common questions about our banking services, accounts, digital banking, and security features.
         </p>
         <div style="margin-top: 30px;">
           <a href="support.php" class="btn btn-primary">Contact Support</a>
           <a href="financial-education.php" class="btn btn-secondary">Browse Resources</a>
         </div>
       </div>
     </header>

     <section class="section">
       <div class="container faq-wrap">
         <div>
           <span class="eyebrow">Getting Started</span>
           <h2>Account & Services Questions</h2>
           <p style="margin-top: 12px;">Find answers about opening accounts, getting started with digital banking, and basic service questions.</p>
         </div>

         <div class="faq-list" aria-label="FAQ list">
           <div class="faq-item open">
             <button class="faq-question" type="button" aria-expanded="true">
               <span>How do I open an account?</span>
               <span aria-hidden="true">+</span>
             </button>
             <div class="faq-answer">
               <p>Begin by choosing the account type that matches your needs, complete the application, and follow the verification steps provided by the onboarding process.</p>
             </div>
           </div>
           <div class="faq-item">
             <button class="faq-question" type="button" aria-expanded="false">
               <span>What information is required?</span>
               <span aria-hidden="true">+</span>
             </button>
             <div class="faq-answer">
               <p>Customer onboarding typically requires identifying information and details necessary for account setup and secure verification.</p>
             </div>
           </div>
           <div class="faq-item">
             <button class="faq-question" type="button" aria-expanded="false">
               <span>How does online banking work?</span>
               <span aria-hidden="true">+</span>
             </button>
             <div class="faq-answer">
               <p>Online banking gives you access to your account information, payment tools, and financial overview through a secure digital dashboard.</p>
             </div>
           </div>
           <div class="faq-item">
             <button class="faq-question" type="button" aria-expanded="false">
               <span>How can I manage my account?</span>
               <span aria-hidden="true">+</span>
             </button>
             <div class="faq-answer">
               <p>You can review balances, track transaction history, update settings, and access financial tools through your secure account dashboard.</p>
             </div>
           </div>
           <div class="faq-item">
             <button class="faq-question" type="button" aria-expanded="false">
               <span>How do transfers work?</span>
               <span aria-hidden="true">+</span>
             </button>
             <div class="faq-answer">
               <p>Transfers are initiated through the secure banking interface, where you enter the payment details and review the transaction before sending.</p>
             </div>
           </div>
           <div class="faq-item">
             <button class="faq-question" type="button" aria-expanded="false">
               <span>How do I protect my account?</span>
               <span aria-hidden="true">+</span>
             </button>
             <div class="faq-answer">
               <p>Use secure credentials, keep login information private, and follow the security guidance available within your account and support resources.</p>
             </div>
           </div>
         </div>
       </div>
     </section>

     <section class="section" style="background: var(--surface);">
       <div class="container faq-wrap">
         <div class="contact-box">
           <span class="eyebrow">Still need help?</span>
           <h2>Contact our support team</h2>
           <p style="margin-top: 12px;">Can't find the answer you're looking for? Our support team is here to help.</p>
           <div style="margin-top: 20px;">
             <a href="support.php" class="btn btn-primary">Get Support</a>
           </div>
         </div>

         <div class="faq-list" aria-label="FAQ list">
           <div class="faq-item">
             <button class="faq-question" type="button" aria-expanded="false">
               <span>What are the fees for accounts?</span>
               <span aria-hidden="true">+</span>
             </button>
             <div class="faq-answer">
               <p>Fee structures vary by account type. Many of our accounts offer no monthly maintenance fees. Contact us for specific fee information based on your account choice.</p>
             </div>
           </div>
           <div class="faq-item">
             <button class="faq-question" type="button" aria-expanded="false">
               <span>How do I reset my password?</span>
               <span aria-hidden="true">+</span>
             </button>
             <div class="faq-answer">
               <p>You can reset your password through the login page using the "Forgot Password" link. Follow the verification steps to securely reset your credentials.</p>
             </div>
           </div>
           <div class="faq-item">
             <button class="faq-question" type="button" aria-expanded="false">
               <span>Is mobile banking available?</span>
               <span aria-hidden="true">+</span>
             </button>
             <div class="faq-answer">
               <p>Yes, we offer a comprehensive mobile banking app that allows you to manage accounts, make transfers, pay bills, and access most features available on desktop.</p>
             </div>
           </div>
           <div class="faq-item">
             <button class="faq-question" type="button" aria-expanded="false">
               <span>How do I close my account?</span>
               <span aria-hidden="true">+</span>
             </button>
             <div class="faq-answer">
               <p>To close your account, contact customer support or visit a branch. Ensure all transactions are complete and your balance is zero before requesting closure.</p>
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

     const faqItems = document.querySelectorAll('.faq-item');
     faqItems.forEach((item) => {
       const button = item.querySelector('.faq-question');
       button.addEventListener('click', () => {
         const isOpen = item.classList.contains('open');
         faqItems.forEach((faq) => {
           faq.classList.remove('open');
           faq.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
         });
         if (!isOpen) {
           item.classList.add('open');
           button.setAttribute('aria-expanded', 'true');
         }
       });
     });
   </script>
</body>
</html>