// Wait for the DOM to be fully loaded
document.addEventListener("DOMContentLoaded", () => {
    
    // ============================================
    // Hamburger menu functionality
    // ============================================
    const hamburger = document.querySelector(".navbar_hamburger");
    const navLinks = document.querySelector(".navbar_nav");

    if (hamburger && navLinks) {
        hamburger.addEventListener("click", () => {
            hamburger.classList.toggle("navbar_hamburger--active");
            navLinks.classList.toggle("navbar_nav--active");
        });
    }

    
    // ============================================
    // Mobile dropdown functionality
    // ============================================
    const dropdownItems = document.querySelectorAll('.navbar_nav__item--dropdown');
    const isMobile = () => window.innerWidth <= 768;

    dropdownItems.forEach(item => {
        const dropdownLink = item.querySelector('.navbar_nav__link');
        const dropdown = item.querySelector('.navbar_dropdown');

        if (!dropdownLink || !dropdown) return;

        const toggleDropdown = (e) => {
            if (isMobile()) {
                if (!item.classList.contains('dropdown-active')) {
                    e.preventDefault();

                    dropdownItems.forEach(otherItem => {
                        if (otherItem !== item && otherItem.classList.contains('dropdown-active')) {
                            otherItem.classList.remove('dropdown-active');
                            otherItem.querySelector('.navbar_dropdown').style.display = 'none';
                        }
                    });

                    item.classList.add('dropdown-active');
                    dropdown.style.display = 'block';
                }
            }
        };

        dropdownLink.addEventListener('click', toggleDropdown);
        dropdownLink.addEventListener('touchstart', toggleDropdown);
    });


    // ============================================
    // Close dropdowns when clicking outside
    // ============================================
    document.addEventListener('click', (e) => {
        if (isMobile()) {
            const isDropdownOrTrigger = e.target.closest('.navbar_nav__item--dropdown');
            if (!isDropdownOrTrigger) {
                dropdownItems.forEach(item => {
                    item.classList.remove('dropdown-active');
                    const dropdown = item.querySelector('.navbar_dropdown');
                    if (dropdown) dropdown.style.display = 'none';
                });
            }
        }
    });

    
    // ============================================
    // Reset styles on resize
    // ============================================
    window.addEventListener('resize', () => {
        const isMobileView = isMobile();
        dropdownItems.forEach(item => {
            const dropdown = item.querySelector('.navbar_dropdown');
            if (!dropdown) return;

            if (!isMobileView) {
                item.classList.remove('dropdown-active');
                dropdown.style.removeProperty('display');
            } else if (!item.classList.contains('dropdown-active')) {
                dropdown.style.display = 'none';
            }
        });
    });

    
    // ============================================
    // Google Translate Trigger
    // ============================================
    const triggerGoogleTranslate = (lang) => {
        document.cookie = `googtrans=/en/${lang}; path=/; domain=${location.hostname}`;
        document.cookie = `googtrans=/en/${lang}; path=/`;
        location.reload();
    };

    // Translation links
    document.querySelectorAll('.translate-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const lang = this.getAttribute('data-lang');
            if (lang) triggerGoogleTranslate(lang);
        });
    });

    
    // ============================================
    // Search filter for languages
    // ============================================
    const searchInput = document.getElementById('languageSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            const filter = this.value.toLowerCase();
            const items = document.querySelectorAll('.navbar_dropdown__item');

            items.forEach(item => {
                const link = item.querySelector('.navbar_dropdown__link');
                if (link) {
                    const text = link.textContent.toLowerCase();
                    item.style.display = text.includes(filter) ? 'block' : 'none';
                }
            });
        });
    }
});
