<!-- ============================================
     Navigation Bar Component
     Apple Frosted Glass System
     ============================================ -->
<link href="styles/nav.css" rel="stylesheet">
<script src="scripts/nav.js" defer></script>
<!-- Google Translate API -->
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<header class="site-header" id="siteHeader">
    <nav class="navbar" aria-label="Main Navigation">
        <div class="navbar__container">
            
            <!-- Brand Identity -->
            <a href="index.php" class="navbar__brand" aria-label="Maruti Nandan Exports Home">
                <img src="assets/logo.png" alt="Maruti Nandan Exports Logo" class="navbar__logo-img" width="48" height="48">
                <div class="navbar__brand-text">
                    <span class="navbar__brand-name">Maruti Nandan</span>
                    <span class="navbar__brand-tagline">Exports • India</span>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <ul class="navbar__menu" id="desktopNavMenu">
                <li class="navbar__item">
                    <a href="index.php" class="navbar__link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'navbar__link--active' : ''; ?>">Home</a>
                </li>
                
                <li class="navbar__item">
                    <a href="about.php" class="navbar__link <?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'navbar__link--active' : ''; ?>">About Us</a>
                </li>

                <!-- Products Dropdown -->
                <li class="navbar__item navbar__item--has-dropdown">
                    <a href="products.php?category=all" class="navbar__link navbar__link--dropdown <?php echo (basename($_SERVER['PHP_SELF']) == 'products.php') ? 'navbar__link--active' : ''; ?>" aria-expanded="false" aria-haspopup="true">
                        <span>Our Products</span>
                        <svg class="navbar__chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </a>
                    
                    <div class="navbar__dropdown glass-panel">
                        <div class="navbar__dropdown-header">
                            <span class="navbar__dropdown-title">Export Catalog</span>
                            <a href="products.php?category=all" class="navbar__dropdown-viewall">View All →</a>
                        </div>
                        <ul class="navbar__dropdown-grid">
                            <li>
                                <a href="products.php?category=wholeSpices" class="navbar__dropdown-item">
                                    <span class="navbar__dropdown-icon">🌿</span>
                                    <div class="navbar__dropdown-text">
                                        <strong>Whole Spices</strong>
                                        <small>Cardamom, Clove, Cumin & Seeds</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="products.php?category=groundSpices" class="navbar__dropdown-item">
                                    <span class="navbar__dropdown-icon">✨</span>
                                    <div class="navbar__dropdown-text">
                                        <strong>Grounded Spices</strong>
                                        <small>Turmeric, Chilli, Coriander Powders</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="products.php?category=grains" class="navbar__dropdown-item">
                                    <span class="navbar__dropdown-icon">🌾</span>
                                    <div class="navbar__dropdown-text">
                                        <strong>Grains & Cereals</strong>
                                        <small>Basmati Rice, Wheat, Millets</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="products.php?category=pulses" class="navbar__dropdown-item">
                                    <span class="navbar__dropdown-icon">🌱</span>
                                    <div class="navbar__dropdown-text">
                                        <strong>Pulses & Legumes</strong>
                                        <small>Chickpeas, Beans, Lentils</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="products.php?category=dryFruits" class="navbar__dropdown-item">
                                    <span class="navbar__dropdown-icon">🥜</span>
                                    <div class="navbar__dropdown-text">
                                        <strong>Premium Dry Fruits</strong>
                                        <small>Almonds, Cashews, Pistachios</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="products.php?category=makhana" class="navbar__dropdown-item">
                                    <span class="navbar__dropdown-icon">🍿</span>
                                    <div class="navbar__dropdown-text">
                                        <strong>Gourmet Makhana</strong>
                                        <small>Roasted & Flavored Foxnuts</small>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                
                <li class="navbar__item">
                    <a href="contact.php" class="navbar__link <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'navbar__link--active' : ''; ?>">Contact</a>
                </li>

                <!-- Language Dropdown -->
                <li class="navbar__item navbar__item--has-dropdown navbar__item--lang">
                    <button type="button" class="navbar__link navbar__link--btn" aria-label="Select Language" aria-expanded="false" aria-haspopup="true">
                        <span class="navbar__lang-icon" aria-hidden="true">🌐</span>
                        <span>Language</span>
                        <svg class="navbar__chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="navbar__dropdown navbar__dropdown--lang glass-panel">
                        <?php include("lang_dropdown.html"); ?>
                    </div>
                </li>
            </ul>

            <!-- Navbar Actions & CTA -->
            <div class="navbar__actions">
                <a href="contact.php" class="btn btn-primary navbar__cta">
                    <span>Request Quote</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>

                <!-- Mobile Hamburger Toggle -->
                <button type="button" class="navbar__hamburger" id="navHamburgerBtn" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="mobileDrawer">
                    <span class="navbar__hamburger-line"></span>
                    <span class="navbar__hamburger-line"></span>
                    <span class="navbar__hamburger-line"></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Drawer Overlay & Menu -->
    <div class="navbar__drawer-backdrop" id="drawerBackdrop" aria-hidden="true"></div>
    <div class="navbar__drawer" id="mobileDrawer" role="dialog" aria-modal="true" aria-label="Mobile Navigation">
        <div class="navbar__drawer-header">
            <div class="navbar__brand">
                <img src="assets/logo.png" alt="Maruti Nandan Exports" class="navbar__logo-img" width="40" height="40">
                <div class="navbar__brand-text">
                    <span class="navbar__brand-name">Maruti Nandan</span>
                    <span class="navbar__brand-tagline">Exports</span>
                </div>
            </div>
            <button type="button" class="navbar__drawer-close" id="drawerCloseBtn" aria-label="Close menu">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <div class="navbar__drawer-body">
            <ul class="navbar__drawer-nav">
                <li><a href="index.php" class="navbar__drawer-link">Home</a></li>
                <li><a href="about.php" class="navbar__drawer-link">About Us</a></li>
                
                <li class="navbar__drawer-item--accordion">
                    <button type="button" class="navbar__drawer-accordion-btn" aria-expanded="false">
                        <span>Our Products</span>
                        <svg class="navbar__chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="navbar__drawer-accordion-content">
                        <a href="products.php?category=all" class="navbar__drawer-sublink"><strong>All Categories →</strong></a>
                        <a href="products.php?category=wholeSpices" class="navbar__drawer-sublink">🌿 Whole Spices</a>
                        <a href="products.php?category=groundSpices" class="navbar__drawer-sublink">✨ Grounded Spices</a>
                        <a href="products.php?category=grains" class="navbar__drawer-sublink">🌾 Grains & Cereals</a>
                        <a href="products.php?category=pulses" class="navbar__drawer-sublink">🌱 Pulses & Legumes</a>
                        <a href="products.php?category=dryFruits" class="navbar__drawer-sublink">🥜 Premium Dry Fruits</a>
                        <a href="products.php?category=makhana" class="navbar__drawer-sublink">🍿 Gourmet Makhana</a>
                    </div>
                </li>

                <li><a href="contact.php" class="navbar__drawer-link">Contact Us</a></li>
            </ul>

            <div class="navbar__drawer-footer">
                <a href="contact.php" class="btn btn-primary" style="width: 100%;">Request Export Quote</a>
                <div class="navbar__drawer-contacts">
                    <a href="tel:+917435924700" class="navbar__drawer-contact-item">
                        <span>📞 +91 74359 24700</span>
                    </a>
                    <a href="mailto:mahekshial@gmail.com" class="navbar__drawer-contact-item">
                        <span>✉️ mahekshial@gmail.com</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Hidden Google Translate Element Container -->
<div id="google_translate_element" style="display: none;" aria-hidden="true"></div>

<!-- Google Translate Init -->
<script>
    const supportedLanguages = [
        'af','sq','am','ar','hy','az','eu','be','bn','bs','bg','ca','ceb',
        'zh-CN','zh-TW','co','hr','cs','da','nl','en','eo','et','fi','fr',
        'fy','gl','ka','de','el','gu','ht','ha','haw','iw','hi','hmn','hu',
        'is','ig','id','ga','it','ja','jw','kn','kk','km','ko','ku','ky',
        'lo','la','lv','lt','lb','mk','mg','ms','ml','mt','mi','mr','mn',
        'my','ne','no','ny','ps','fa','pl','pt','pa','ro','ru','sm','gd',
        'sr','st','sn','sd','si','sk','sl','so','es','su','sw','sv','tl',
        'tg','ta','te','th','tr','uk','ur','uz','vi','cy','xh','yi','yo','zu'
    ];

    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: supportedLanguages.join(','),
            autoDisplay: false
        }, 'google_translate_element');
    }
</script>


