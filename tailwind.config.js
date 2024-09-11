/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: "var(--color-primary)",
                    light: "var(--color-primary-light)",
                    lighter: "var(--color-primary-lighter)",
                    dark: "var(--color-primary-dark)",
                    darker: "var(--color-primary-darker)",
                },
                secondary: {
                    DEFAULT: "var(--color-secondary)",
                    light: "var(--color-secondary-light)",
                    lighter: "var(--color-secondary-lighter)",
                    dark: "var(--color-secondary-dark)",
                    darker: "var(--color-secondary-darker)",
                },
                background: "var(--color-background)",
                foreground: "var(--color-foreground)",
                success: "var(--color-success)",
                warning: "var(--color-warning)",
                danger: "var(--color-danger)",
                info: "var(--color-info)",
                price: "var(--color-price)",
                discount: "var(--color-discount)",
                dark: "var(--color-dark)",
                light: "var(--color-light)",
                muted: "var(--color-muted)",
                highlight: "var(--color-highlight)",
                overlay: "var(--color-overlay)",
            },
        },
    },
    plugins: [],
};
