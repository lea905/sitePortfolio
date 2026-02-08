import { Controller } from '@hotwired/stimulus';

/*
 * Controls the mobile menu toggle.
 * 
 * Usage in template:
 * <nav data-controller="mobile-menu">
 *   <button data-action="mobile-menu#toggle">Menu</button>
 *   <div data-mobile-menu-target="menu">...links...</div>
 * </nav>
 */
export default class extends Controller {
    static targets = ["menu", "icon"];

    connect() {
        // Ensure menu is closed on load
        this.close();
    }

    toggle() {
        if (this.menuTarget.classList.contains('active')) {
            this.close();
        } else {
            this.open();
        }
    }

    open() {
        this.menuTarget.classList.add('active');
        this.iconTarget.classList.remove('fa-bars');
        this.iconTarget.classList.add('fa-times');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }

    close() {
        this.menuTarget.classList.remove('active');
        this.iconTarget.classList.remove('fa-times');
        this.iconTarget.classList.add('fa-bars');
        document.body.style.overflow = ''; // Restore scrolling
    }

    // Close menu when clicking a link
    closeOnNavigate() {
        this.close();
    }
}
