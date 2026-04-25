export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                // Core Brand - Madaaq Deep Blue
                "primary": {
                    DEFAULT: "#00355f",
                    50: "#e6f1f9",
                    100: "#cce3f3",
                    200: "#99c6e7",
                    300: "#66a9db",
                    400: "#338ccf",
                    500: "#006fc3",
                    600: "#00599c",
                    700: "#004275",
                    800: "#002b4e",
                    900: "#001427",
                },
                
                // Role Specific Palettes
                "vendor": {
                    DEFAULT: "#4f46e5", // Indigo
                    light: "#818cf8",
                    dark: "#3730a3",
                    glow: "rgba(79, 70, 229, 0.15)",
                },
                "super-admin": {
                    DEFAULT: "#7c3aed", // Violet/Purple
                    light: "#a78bfa",
                    dark: "#5b21b6",
                    glow: "rgba(124, 58, 237, 0.15)",
                },
                "customer": {
                    DEFAULT: "#0284c7", // Sky Blue
                    light: "#38bdf8",
                    dark: "#075985",
                    glow: "rgba(2, 132, 199, 0.15)",
                },
                
                // Radiant Accents
                "neon-cyan": "#00e5ff",
                "vibrant-purple": "#7c4dff",
                "electric-blue": "#3b82f6",
                "magic-pink": "#ff00e5",
                
                // Functional Tones
                "success": "#10b981",
                "warning": "#f59e0b",
                "error": "#ef4444",
                
                // Surfaces (Radiant Monolith Hybrid)
                "surface": {
                    "dark": "#0f172a",
                    "darker": "#020617",
                    "light": "#f8fafc",
                    "glass": "rgba(255, 255, 255, 0.75)",
                    "glass-dark": "rgba(15, 23, 42, 0.75)",
                }
            },
            fontFamily: {
                tajawal: ["Tajawal", "sans-serif"],
                rubik: ["Rubik", "sans-serif"],
                inter: ["Inter", "sans-serif"],
            },
            borderRadius: {
                "xl": "1rem",
                "2xl": "1.5rem",
                "3xl": "2rem",
                "4xl": "2.5rem",
            },
            boxShadow: {
                "radiant": "0 20px 50px -12px rgba(0, 0, 0, 0.1)",
                "glow-indigo": "0 0 25px rgba(79, 70, 229, 0.35)",
                "glow-cyan": "0 0 25px rgba(0, 229, 255, 0.35)",
                "glass": "inset 0 0 0 1px rgba(255, 255, 255, 0.2), 0 8px 32px 0 rgba(0, 0, 0, 0.05)",
                "glass-dark": "inset 0 0 0 1px rgba(255, 255, 255, 0.05), 0 8px 32px 0 rgba(0, 0, 0, 0.3)",
            },
            backgroundImage: {
                "mesh-gradient": "radial-gradient(at top left, #f8fafc, #f1f5f9, #e2e8f0)",
                "radiant-indigo": "linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%)",
                "radiant-purple": "linear-gradient(135deg, #7c3aed 0%, #ec4899 100%)",
                "radiant-dark": "linear-gradient(135deg, #0f172a 0%, #1e293b 100%)",
                "glass-gradient": "linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.1) 100%)",
            },
            animation: {
                "pulse-slow": "pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite",
                "float": "float 6s ease-in-out infinite",
            },
            keyframes: {
                float: {
                    "0%, 100%": { transform: "translateY(0)" },
                    "50%": { transform: "translateY(-10px)" },
                }
            }
        },
    },
    plugins: [],
};
