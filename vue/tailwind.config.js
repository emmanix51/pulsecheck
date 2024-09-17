/** @type {import('tailwindcss').Config} */
export default {
    content: ["./index.html", "./src/**/*.{vue,js,ts,jsx,tsx}"],
    theme: {
        extend: {
            colors: {
                spccolor: {
                    500: "rgba(103,9,9,0.9)",
                    600: "rgba(103,9,9,1)",
                },
            },
        },
    },
    plugins: [require("@tailwindcss/forms")],
};
