<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Contact | UW CREDIT UNION | Modern Banking & Personal Finance</title>
   <meta
     name="description"
     content="Contact UW CREDIT UNION. Get in touch with our team for banking inquiries, support, or general questions about our financial services."
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

     .contact-panel {
       display: grid;
       grid-template-columns: 1fr 0.96fr;
       gap: 26px;
       align-items: center;
    }
    .contact-box {
      padding: 28px 24px;
      background: linear-gradient(180deg, #ffffff, #f8fafc);
      border: 1px solid var(--border);
      border-radius: 24px;
      box-shadow: var(--shadow-sm);
    }
    .contact-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 18px;
      margin-top: 22px;
    }
    .contact-item {
      padding: 18px;
      border-radius: 18px;
      background: var(--surface);
      border: 1px solid var(--border);
    }
    .contact-item h4 {
      font-size: 1rem;
      margin-bottom: 8px;
    }

    .form-section {
      background: var(--surface);
      border-radius: 28px;
      padding: 48px 40px;
      margin-top: 60px;
      box-shadow: var(--shadow-sm);
    }
    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 20px;
      margin-top: 32px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group.full-width {
      grid-column: span 2;
    }
    .form-group label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
      color: var(--primary);
    }
    .form-group input, .form-group textarea, .form-group select {
      width: 100%;
      padding: 14px 16px;
      border: 1px solid var(--border);
      border-radius: 12px;
      font-size: 1rem;
      background: var(--surface-alt);
    }
    .form-group textarea {
      min-height: 120px;
      resize: vertical;
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
       .contact-panel, .form-grid, .footer-grid {
         grid-template-columns: repeat(2, minmax(0, 1fr));
       }
     }

     @media (max-width: 760px) {
       .section { padding: 80px 0; }
       .page-header { padding: 100px 0 60px; }
       .contact-panel, .form-grid, .footer-grid {
         grid-template-columns: 1fr;
       }
       .header-inner { min-height: 72px; }
       .brand-copy span { display: none; }
       .contact-grid { grid-template-columns: 1fr; }
       .form-group.full-width { grid-column: span 1; }
       .form-section { padding: 32px 24px; }
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
         <span class="eyebrow">Contact Us</span>
         <h1>Get in touch with our team.</h1>
         <p style="margin-top: 20px; max-width: 600px;">
          Whether you have questions about our services, need support with your account, or want to learn more about UW CREDIT UNION, we're here to help.
         </p>
         <div style="margin-top: 30px;">
           <a href="support.php" class="btn btn-primary">Get Support</a>
           <a href="faqs.php" class="btn btn-secondary">View FAQs</a>
         </div>
       </div>
     </header>

     <section class="section">
       <div class="container">
         <div class="contact-panel">
           <div class="contact-box">
             <span class="eyebrow">Contact Information</span>
             <h2>Reach out to us</h2>
             <div class="contact-grid">
               <div class="contact-item">
                 <h4>Phone</h4>
                 <p>1-800-555-0123</p>
                 <p style="font-size: 0.85rem; margin-top: 4px;">Mon-Fri 8am-8pm EST</p>
               </div>
               <div class="contact-item">
                 <h4>Email</h4>
                 <p>contact@uwcu.com</p>
                 <p style="font-size: 0.85rem; margin-top: 4px;">Response within 24 hours</p>
               </div>
               <div class="contact-item">
                 <h4>Address</h4>
                 <p>123 Banking Street</p>
                 <p>Seattle, WA 98101</p>
               </div>
               <div class="contact-item">
                 <h4>Hours</h4>
                 <p>Branch: Mon-Fri 9am-5pm</p>
                 <p style="font-size: 0.85rem; margin-top: 4px;">Digital: 24/7</p>
               </div>
             </div>
           </div>

           <div class="contact-box" aria-label="Quick contact options">
             <span class="eyebrow">Quick Actions</span>
             <h3>How can we help?</h3>
             <p style="margin-top: 12px;">Choose the best way to connect with us based on your needs.</p>
             <div style="margin-top:20px; display:grid; gap:12px;">
               <div style="padding:14px 16px; background:#fff; border:1px solid var(--border); border-radius:12px;">
                 <strong style="display:block; color:var(--primary); margin-bottom:4px;">New Account Inquiries</strong>
                 <span style="color: var(--muted);">Questions about opening accounts or our services.</span>
               </div>
               <div style="padding:14px 16px; background:#fff; border:1px solid var(--border); border-radius:12px;">
                 <strong style="display:block; color:var(--primary); margin-bottom:4px;">Existing Account Support</strong>
                 <span style="color: var(--muted);">Help with your current account or banking needs.</span>
               </div>
               <div style="padding:14px 16px; background:#fff; border:1px solid var(--border); border-radius:12px;">
                 <strong style="display:block; color:var(--primary); margin-bottom:4px;">Business Banking</strong>
                 <span style="color: var(--muted);">Solutions for business accounts and commercial services.</span>
               </div>
             </div>
           </div>
         </div>
       </div>
     </section>

     <section class="section">
       <div class="container">
         <div class="form-section">
           <h2>Send us a message</h2>
           <p style="margin-top: 12px;">Fill out the form below and we'll get back to you within one business day.</p>
           <form class="form-grid">
             <div class="form-group">
               <label for="name">Full Name</label>
               <input type="text" id="name" placeholder="John Doe" required>
             </div>
             <div class="form-group">
               <label for="email">Email Address</label>
               <input type="email" id="email" placeholder="john@example.com" required>
             </div>
             <div class="form-group">
               <label for="phone">Phone Number</label>
               <input type="tel" id="phone" placeholder="(555) 123-4567">
             </div>
             <div class="form-group">
               <label for="subject">Subject</label>
               <select id="subject" required>
                 <option value="">Select a topic</option>
                 <option value="account">Account Inquiry</option>
                 <option value="support">Technical Support</option>
                 <option value="business">Business Banking</option>
                 <option value="feedback">Feedback</option>
                 <option value="other">Other</option>
               </select>
             </div>
             <div class="form-group full-width">
               <label for="message">Message</label>
               <textarea id="message" placeholder="How can we help you?" required></textarea>
             </div>
             <div class="form-group full-width">
               <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
             </div>
           </form>
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