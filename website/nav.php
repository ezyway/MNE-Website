<head>
    <!-- Linking external stylesheet for navbar styles -->
    <link href="styles/nav.css" rel="stylesheet">

    <!-- Linking JavaScript for navbar interactivity -->
    <script src="scripts/nav.js" defer></script>

    <!-- Google Translate API -->
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</head>

<!-- ============================================
     Navigation Bar Section
     ============================================ -->
<nav class="navbar_container">
    <div class="navbar_container__inner">

        <!-- Logo Section -->
        <div class="navbar_logo">
            <img src="assets/logo.png" alt="Company Logo" class="navbar_logo__image" id="logoTrigger">
        </div>

        <!-- Navigation Links -->
        <ul class="navbar_nav">

            <!-- Mobile Logo -->
            <div class="navbar_logo--mobile">
                <img src="assets/logo.png" alt="Company Logo">
            </div>

            <li class="navbar_nav__item"><a href="index.php" class="navbar_nav__link">Home</a></li>
            <li class="navbar_nav__item"><a href="about.php" class="navbar_nav__link">About Us</a></li>

            <!-- Products Dropdown -->
            <li class="navbar_nav__item navbar_nav__item--dropdown">
                <a href="products.php?category=all" class="navbar_nav__link">Our Products <span class="navbar_nav__arrow">&#9662;</span></a>
                <ul class="navbar_dropdown">
                    <li class="navbar_dropdown__item"><a href="products.php?category=wholeSpices" class="navbar_dropdown__link">Whole Spices</a></li>
                    <li class="navbar_dropdown__item"><a href="products.php?category=groundSpices" class="navbar_dropdown__link">Grounded Spices</a></li>
                    <!-- <li class="navbar_dropdown__item"><a href="products.php?category=blendedSpices" class="navbar_dropdown__link">Blended Spices</a></li> -->
                    <li class="navbar_dropdown__item"><a href="products.php?category=grainsAndPulses" class="navbar_dropdown__link">Grains & Pulses</a></li>
                    <li class="navbar_dropdown__item"><a href="products.php?category=dryFruits" class="navbar_dropdown__link">Dry Fruits</a></li>
                    <li class="navbar_dropdown__item"><a href="products.php?category=makhana" class="navbar_dropdown__link">Makhana</a></li>
                </ul>
            </li>

            <!-- Google Translate Dropdown -->
            <li class="navbar_nav__item navbar_nav__item--dropdown">
                <a href="#" class="navbar_nav__link">Translate <span class="navbar_nav__arrow">&#9662;</span></a>
                <?php include("lang_dropdown.html"); ?>
            </li>
            
            <li class="navbar_nav__item"><a href="contact.php" class="navbar_nav__link">Contact Us</a></li>


        </ul>

        <!-- Hamburger Menu -->
        <div class="navbar_hamburger">
            <div class="navbar_hamburger__bar"></div>
            <div class="navbar_hamburger__bar"></div>
            <div class="navbar_hamburger__bar"></div>
        </div>
    </div>
</nav>

<!-- Hidden Google Translate element -->
<div id="google_translate_element" style="display: none;"></div>

<!-- Google Translate Init -->
<script>
    const supportedLanguages = [
        'af','sq','am','ar','hy','az','eu','be','bn','bs','bg','ca','ceb','zh-CN','zh-TW','co','hr','cs','da',
        'nl','en','eo','et','fi','fr','fy','gl','ka','de','el','gu','ht','ha','haw','iw','hi','hmn','hu','is',
        'ig','id','ga','it','ja','jw','kn','kk','km','ko','ku','ky','lo','la','lv','lt','lb','mk','mg','ms',
        'ml','mt','mi','mr','mn','my','ne','no','ny','ps','fa','pl','pt','pa','ro','ru','sm','gd','sr','st',
        'sn','sd','si','sk','sl','so','es','su','sw','sv','tl','tg','ta','te','th','tr','uk','ur','uz','vi',
        'cy','xh','yi','yo','zu'
    ];

    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: supportedLanguages.join(','),
            autoDisplay: false
        }, 'google_translate_element');
    }
</script>

