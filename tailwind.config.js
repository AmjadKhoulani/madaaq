export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            "colors": {
                // Core Brand
                "primary": "#00355f",
                "primary-light": "#0f4c81",
                "primary-dark": "#001c37",
                
                // Radiant Accents
                "neon-cyan": "#00e5ff",
                "vibrant-purple": "#7c4dff",
                "electric-blue": "#3b82f6",
                
                // Functional Tones
                "success": "#00c853",
                "warning": "#ffd600",
                "error": "#ff1744",
                
                // Surfaces (Radiant Monolith Hybrid)
                "surface-dark": "#0f172a",
                "surface-light": "#f8f9ff",
                "surface-glass": "rgba(255, 255, 255, 0.7)",
                
                // Legacy Mappings (Maintain compatibility during transition)
                "background": "#f8f9ff",
                "surface": "#f8f9ff",
                "on-surface": "#121c28",
                "surface-container": "#e5eeff",
                "surface-container-low": "#eef4ff",
                "surface-container-lowest": "#ffffff",
                "outline-variant": "#c2c7d1",
                "secondary": "#006c4a",
                "secondary-container": "#82f5c1",
                "on-secondary-container": "#00714e",
            },
            fontFamily: {
                headline: ["Manrope", "sans-serif"],
                body: ["IBM Plex Sans Arabic", "sans-serif"],
            },
            borderRadius: {
                "none": "0",
                "sm": "0.25rem",
                "DEFAULT": "0.5rem",
                "md": "0.75rem",
                "lg": "1rem",
                "xl": "1.5rem",
                "2xl": "2rem",
                "full": "9999px",
            },
            boxShadow: {
                "radiant": "0 10px 40px -10px rgba(0, 53, 95, 0.15)",
                "glow-cyan": "0 0 20px rgba(0, 229, 255, 0.3)",
                "glow-purple": "0 0 20px rgba(124, 77, 255, 0.3)",
                "glass": "inset 0 0 0 1px rgba(255, 255, 255, 0.1), 0 8px 32px 0 rgba(31, 38, 135, 0.07)",
            },
            backgroundImage: {
                "radiant-gradient": "linear-gradient(135deg, #00355f 0%, #0f4c81 100%)",
                "cyber-gradient": "linear-gradient(135deg, #001c37 0%, #0f172a 100%)",
                "accent-gradient": "linear-gradient(90deg, #00e5ff 0%, #7c4dff 100%)",
            }
        },
    },
    plugins: [],
};
