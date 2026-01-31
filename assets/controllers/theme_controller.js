import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        // Check local storage or system preference
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
            this.updateIcon('light');
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
            this.updateIcon('dark');
        }
    }

    toggle() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';

        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);

        this.updateIcon(newTheme);
    }

    updateIcon(theme) {
        // Simple logic to toggle an icon class or text if needed
        // For the specific CSS toggle, the checkbox state might need handling if we used a checkbox
        // If it's a button, we can just toggle a class

        const checkbox = this.element.querySelector('input[type="checkbox"]');
        if (checkbox) {
            checkbox.checked = (theme === 'dark');
            // The CodePen example usually has "checked" = night/dark
        }
    }
}
