/**
 * Maruti Nandan Exports — Navigation Interactivity
 * Optimized for Smooth Performance & Accessibility
 */
document.addEventListener("DOMContentLoaded", () => {
    
    // ============================================
    // 1. Header Shrink on Scroll
    // ============================================
    const header = document.getElementById("siteHeader");
    const shrinkThreshold = 60;
    let lastScrollY = window.scrollY;
    let ticking = false;

    const updateHeaderState = () => {
        if (!header) return;
        if (window.scrollY > shrinkThreshold) {
            header.classList.add("site-header--shrink");
        } else {
            header.classList.remove("site-header--shrink");
        }
        ticking = false;
    };

    window.addEventListener("scroll", () => {
        if (!ticking) {
            window.requestAnimationFrame(updateHeaderState);
            ticking = true;
        }
    }, { passive: true });

    updateHeaderState();

    // ============================================
    // 2. Mobile Drawer & Backdrop Controls
    // ============================================
    const hamburgerBtn = document.getElementById("navHamburgerBtn");
    const drawerCloseBtn = document.getElementById("drawerCloseBtn");
    const drawerBackdrop = document.getElementById("drawerBackdrop");
    const mobileDrawer = document.getElementById("mobileDrawer");

    const openDrawer = () => {
        if (!mobileDrawer || !drawerBackdrop) return;
        hamburgerBtn?.setAttribute("aria-expanded", "true");
        drawerBackdrop.classList.add("is-active");
        mobileDrawer.classList.add("is-active");
        document.body.style.overflow = "hidden"; // Prevent body scroll
    };

    const closeDrawer = () => {
        if (!mobileDrawer || !drawerBackdrop) return;
        hamburgerBtn?.setAttribute("aria-expanded", "false");
        drawerBackdrop.classList.remove("is-active");
        mobileDrawer.classList.remove("is-active");
        document.body.style.removeProperty("overflow");
    };

    hamburgerBtn?.addEventListener("click", () => {
        const isExpanded = hamburgerBtn.getAttribute("aria-expanded") === "true";
        if (isExpanded) {
            closeDrawer();
        } else {
            openDrawer();
        }
    });

    drawerCloseBtn?.addEventListener("click", closeDrawer);
    drawerBackdrop?.addEventListener("click", closeDrawer);

    // Keyboard ESC key to close drawer
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && mobileDrawer?.classList.contains("is-active")) {
            closeDrawer();
        }
    });

    // ============================================
    // 3. Mobile Accordion for Products
    // ============================================
    const accordionBtn = document.querySelector(".navbar__drawer-accordion-btn");
    const accordionContent = document.querySelector(".navbar__drawer-accordion-content");

    accordionBtn?.addEventListener("click", () => {
        const isOpen = accordionContent?.classList.contains("is-open");
        if (isOpen) {
            accordionContent?.classList.remove("is-open");
            accordionBtn.setAttribute("aria-expanded", "false");
            const chevron = accordionBtn.querySelector(".navbar__chevron");
            if (chevron) chevron.style.transform = "rotate(0deg)";
        } else {
            accordionContent?.classList.add("is-open");
            accordionBtn.setAttribute("aria-expanded", "true");
            const chevron = accordionBtn.querySelector(".navbar__chevron");
            if (chevron) chevron.style.transform = "rotate(180deg)";
        }
    });

    // ============================================
    // 3.5 Desktop Dropdowns — Hover Intent with Grace Delay
    // ============================================
    // The dropdown panels float slightly below the nav links, so a slow mouse
    // can pause in the gap between them. Instead of hiding the instant the
    // pointer leaves the nav item (CSS :hover), we wait a short grace period
    // so moving between the link and its panel never closes the menu.
    const dropdownItems = document.querySelectorAll(".navbar__item--has-dropdown");
    let dropdownCloseTimer = null;
    const DROPDOWN_CLOSE_DELAY = 350;

    const setDropdownState = (item, open) => {
        item.classList.toggle("is-open", open);
        const trigger = item.querySelector(".navbar__link--dropdown, .navbar__link--btn");
        trigger?.setAttribute("aria-expanded", open ? "true" : "false");
    };

    dropdownItems.forEach((item) => {
        const closeOthers = () => {
            dropdownItems.forEach((other) => {
                if (other !== item) setDropdownState(other, false);
            });
        };

        const openDropdown = () => {
            clearTimeout(dropdownCloseTimer);
            closeOthers();
            setDropdownState(item, true);
        };

        const scheduleClose = () => {
            clearTimeout(dropdownCloseTimer);
            dropdownCloseTimer = setTimeout(() => {
                setDropdownState(item, false);
            }, DROPDOWN_CLOSE_DELAY);
        };

        item.addEventListener("mouseenter", openDropdown);
        item.addEventListener("mouseleave", scheduleClose);
    });

    // ============================================
    // 4. Google Translate Integration (Lazy-Loaded)
    // ============================================
    let googleTranslateLoaded = false;

    const loadGoogleTranslate = () => {
        if (googleTranslateLoaded) return;
        googleTranslateLoaded = true;

        // Define init function before loading script
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

        window.googleTranslateElementInit = () => {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: supportedLanguages.join(','),
                autoDisplay: false
            }, 'google_translate_element');
        };

        const script = document.createElement('script');
        script.src = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
        script.async = true;
        document.body.appendChild(script);
    };

    const triggerGoogleTranslate = (lang) => {
        document.cookie = `googtrans=/en/${lang}; path=/; domain=${location.hostname}`;
        document.cookie = `googtrans=/en/${lang}; path=/`;
        location.reload();
    };

    // Load Google Translate on first language dropdown interaction
    const langDropdownBtn = document.querySelector('.navbar__item--lang .navbar__link--btn');
    langDropdownBtn?.addEventListener('mouseenter', loadGoogleTranslate, { once: true });
    langDropdownBtn?.addEventListener('focus', loadGoogleTranslate, { once: true });
    langDropdownBtn?.addEventListener('touchstart', loadGoogleTranslate, { once: true });

    document.querySelectorAll('.translate-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const lang = this.getAttribute('data-lang');
            if (lang) triggerGoogleTranslate(lang);
        });
    });

    // Search filter for language dropdown
    const searchInput = document.getElementById('languageSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const filter = this.value.toLowerCase().trim();
            const items = document.querySelectorAll('.navbar_dropdown__item');

            items.forEach(item => {
                // Skip the search input row
                if (item.contains(searchInput)) return;
                const link = item.querySelector('.navbar_dropdown__link');
                if (link) {
                    const text = link.textContent.toLowerCase();
                    item.style.display = text.includes(filter) ? 'block' : 'none';
                }
            });
        });
    }
});