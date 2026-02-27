/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./components/**/*.{js,vue,ts}",
        "./layouts/**/*.vue",
        "./pages/**/*.vue",
        "./features/**/*.vue",
        "./plugins/**/*.{js,ts}",
        "./app.vue",
        "./error.vue",
    ],
    theme: {
        extend: {
            colors: {
                danger: "#ea868f",
                success: "",
            },
            fontFamily: {
                urbanist: ["Urbanist", "sans-serif"],
            },
        },
    },
    plugins: [],
    darkMode: "selector",
};
