/** @type {import('tailwindcss').Config} */
export default {
  darkMode: "class",
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          primary: "rgb(var(--c-primary) / <alpha-value>)",
          primaryHover: "rgb(var(--c-primary-hover) / <alpha-value>)",
          secondary: "rgb(var(--c-secondary) / <alpha-value>)",
          secondaryHover: "rgb(var(--c-secondary-hover) / <alpha-value>)",
          success: "rgb(var(--c-success) / <alpha-value>)",
        },
        ui: {
          bg: "rgb(var(--c-bg) / <alpha-value>)",
          surface: "rgb(var(--c-surface) / <alpha-value>)",
          border: "rgb(var(--c-border) / <alpha-value>)",
          text: "rgb(var(--c-text) / <alpha-value>)",
          muted: "rgb(var(--c-text-muted) / <alpha-value>)",
        },
      },
      fontFamily: {
        fa: ["PersianUI", "sans-serif"],
        ku: ["KurdishUI", "sans-serif"],
      },
      borderRadius: {
        xl: "0.875rem",
        "2xl": "1.25rem",
      },
      boxShadow: {
        soft: "0 10px 30px rgba(0,0,0,0.08)",
      },
    },
  },
  plugins: [],
};