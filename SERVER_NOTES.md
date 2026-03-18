# Server and Architecture Notes

This document contains important technical quirks and configurations specific to the MadaaQ live server and frontend environment. **Any developer or AI agent working on this project must read this before modifying server config or frontend deployment pipelines.**

## 1. Nginx DocumentRoot and Vite Assets (Symlink Requirement)

**The Issue:**
On the live server (`173.249.52.218`), the Nginx web server is configured with the DocumentRoot pointing directly to `/home/madaaq/public_html` rather than the typical Laravel standard of `/home/madaaq/public_html/public`.

**The Impact on Vite:**
When `npm run build` generates frontend assets, Laravel places them in `public/build/assets/`. However, because the server root is one level higher, requests from the browser for `https://madaaq.com/build/...` were resolving to `/home/madaaq/public_html/build/...` resulting in **404 Not Found** errors for all CSS and JS compiled files.

**The Fix (DO NOT REVERT):**
To resolve this without altering the core server configuration (which is likely managed by cPanel/WHM or a similar panel), a symbolic link was created in the `public_html` root directory:
`ln -s public/build build`

If you ever clear the root directory or need to migrate the site, **you must recreate this symlink**, otherwise Vite compiled assets will break.

## 2. Safari / WebKit SVG Icon Size Bug (Tailwind CSS)

**The Issue:**
In Safari and some older WebKit browsers on iOS/macOS, SVG icons styled with Tailwind CSS dimensions (e.g., `w-5 h-5` or `w-6 h-6`) would completely ignore these constraints and scale up massively (sometimes taking up the whole screen, ~900x1100 pixels). This happened because the SVG lacked an explicit intrinsic `viewBox` or width definition, and the browser's default scaling rules overrode Tailwind's utility classes.

**The Previous (Incorrect) Fix:**
An attempt was made to fix this by adding `img, svg { max-width: 100%; height: auto; }` globally or inline in blade templates. **This is wrong** because setting `height: auto` on an SVG without inherent dimensions causes it to expand infinitely to match its width ratio, directly conflicting with Tailwind's `h-5` (which is `height: 1.25rem`).

**The Permanent Fix (Implemented in app.css):**
1. Removed `svg` from the global `img` responsive rule.
2. Added forceful CSS rules at the bottom of `resources/css/app.css` to strictly bind `svg` tags to their Tailwind classes:

```css
/* Force SVG scaling boundaries to prevent the huge Safari/Webkit icon bug */
svg.w-3 { width: 0.75rem !important; height: 0.75rem !important; }
svg.w-4 { width: 1rem !important; height: 1rem !important; }
svg.w-5 { width: 1.25rem !important; height: 1.25rem !important; }
svg.w-6 { width: 1.5rem !important; height: 1.5rem !important; }
svg.h-3 { height: 0.75rem !important; }
svg.h-4 { height: 1rem !important; }
svg.h-5 { height: 1.25rem !important; }
svg.h-6 { height: 1.5rem !important; }
```

**Do not remove these `!important` rules**, or the huge icon bug will return on Apple devices.
