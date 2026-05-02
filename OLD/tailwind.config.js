export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                vendor: "#4f46e5",
                admin: "#7c3aed",
            },
            fontFamily: {
                tajawal: ["Tajawal", "sans-serif"],
                rubik: ["Rubik", "sans-serif"],
            }
        },
    },
    plugins: [],
};
