class Navigation {
    static init() {
        this.setupEventListeners();
    }

    static setupEventListeners() {
        // Dropdown toggle
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleDropdown(toggle);
            });
        });

        // Close dropdowns on outside click
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.categories-dropdown, .user-dropdown')) {
                this.closeAllDropdowns();
            }
        });

        // Handle keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllDropdowns();
            }
        });
    }

    static toggleDropdown(toggle) {
        const dropdown = toggle.nextElementSibling;
        const isVisible = dropdown.classList.contains('show');
        
        this.closeAllDropdowns();
        
        if (!isVisible) {
            dropdown.classList.add('show');
            toggle.setAttribute('aria-expanded', 'true');
        }
    }

    static closeAllDropdowns() {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.setAttribute('aria-expanded', 'false');
        });
    }

    static toggleNav() {
        const navContent = document.getElementById('navContent');
        navContent.classList.toggle('active');
    }
}

// Initialize navigation
document.addEventListener('DOMContentLoaded', () => Navigation.init());