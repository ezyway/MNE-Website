// Wait for the DOM to be fully loaded
document.addEventListener("DOMContentLoaded", () => {
    // Hamburger menu functionality
    const hamburger = document.querySelector(".navbar_hamburger");
    const navLinks = document.querySelector(".navbar_nav");
    hamburger.addEventListener("click", () => {
        hamburger.classList.toggle("navbar_hamburger--active");
        navLinks.classList.toggle("navbar_nav--active");
    });

    // Modal functionality
    const modalOverlay = document.getElementById("modalOverlay");
    const logoTrigger = document.getElementById("logoTrigger");
    const closeBtn = document.getElementById("closeBtn");
    // Show Modal
    logoTrigger.addEventListener("click", () => {
        modalOverlay.classList.add("navbar_modal--active");
    });
    // Close Modal (Button Click)
    closeBtn.addEventListener("click", () => {
        modalOverlay.classList.remove("navbar_modal--active");
    });
    // Close Modal (Click Outside)
    modalOverlay.addEventListener("click", (e) => {
        if (e.target === modalOverlay) {
            modalOverlay.classList.remove("navbar_modal--active");
        }
    });

    // Mobile dropdown functionality
    const dropdownItems = document.querySelectorAll('.navbar_nav__item--dropdown');
    
    dropdownItems.forEach(item => {
        const dropdownLink = item.querySelector('.navbar_nav__link');
        const dropdown = item.querySelector('.navbar_dropdown');
        
        // Check if we're on mobile
        const isMobile = () => window.innerWidth <= 768;
        
        dropdownLink.addEventListener('click', function(e) {
            if (isMobile()) {
                // If we're on mobile
                if (!item.classList.contains('dropdown-active')) {
                    // First tap - prevent navigation and show dropdown
                    e.preventDefault();
                    
                    // Close any other open dropdowns first
                    dropdownItems.forEach(otherItem => {
                        if (otherItem !== item && otherItem.classList.contains('dropdown-active')) {
                            otherItem.classList.remove('dropdown-active');
                            otherItem.querySelector('.navbar_dropdown').style.display = 'none';
                        }
                    });
                    
                    // Open this dropdown
                    item.classList.add('dropdown-active');
                    dropdown.style.display = 'block';
                }
                // Second tap - navigation happens naturally (we don't prevent default)
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        const isDropdownOrTrigger = e.target.closest('.navbar_nav__item--dropdown');
        
        if (!isDropdownOrTrigger) {
            dropdownItems.forEach(item => {
                if (item.classList.contains('dropdown-active')) {
                    item.classList.remove('dropdown-active');
                    item.querySelector('.navbar_dropdown').style.display = 'none';
                }
            });
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        const isMobileView = window.innerWidth <= 768;
        
        dropdownItems.forEach(item => {
            const dropdown = item.querySelector('.navbar_dropdown');
            
            if (!isMobileView) {
                // Reset mobile-specific styles when returning to desktop
                item.classList.remove('dropdown-active');
                dropdown.style.removeProperty('display');
            } else if (!item.classList.contains('dropdown-active')) {
                // Ensure dropdowns are hidden in mobile if not active
                dropdown.style.display = 'none';
            }
        });
    });
});