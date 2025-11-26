PEI Lean Theme

A lean, mobile-first WordPress theme for PEI. No frameworks. Custom CSS layout, accessible navigation, and simple PHP templates.

- Mobile-first header with Customizer logo and hamburger menu (vanilla JS)
- Primary navigation and footer links use WordPress menus
- Footer content is editable in the Customizer and uses schema.org LocalBusiness
- Minimal assets: one stylesheet (style.css) and one small JS file (navigation.js)

Requirements
- WordPress 6.0+
- PHP 7.4+

Quick Start
1) Install and activate
   - Place this folder at: wp-content/themes/pei
   - In WP Admin: Appearance → Themes → Activate “PEI Lean Theme”.
2) Assign menus
   - Appearance → Menus (or Customizer → Menus)
   - Assign a menu to “Primary Menu” for the header.
   - Assign a menu to “Footer Useful Links” for the footer.
3) Set site logo
   - Customizer → Site Identity → Logo → Upload/Select.
4) Configure footer
   - Customizer → Footer Settings: Phone, Email, Address (street/city/state/postal), Hours (weekday/weekend), and optional copyright text.
5) View the site
   - Header shows logo and responsive nav. Footer shows configured links and contact info.

Directory Structure
wp-content/
  themes/
    pei/
      assets/
        js/
          navigation.js    (Mobile menu toggle)
      footer.php           (LocalBusiness footer with Customizer content)
      functions.php        (Theme setup, menus, Customizer, asset enqueue)
      header.php           (Header with custom logo and primary nav)
      index.php            (Minimal loop template)
      style.css            (Layout, header, footer, utilities)
      README.md            (This file)

How components are brought in
- functions.php
  - Adds theme supports: title-tag, custom-logo, html5
  - Registers menus: primary, footer_links
  - Enqueues: style.css and assets/js/navigation.js (in footer)
  - Registers Customizer fields under “Footer Settings”
- header.php
  - Uses the_custom_logo() (falls back to site name link)
  - Renders primary nav via wp_nav_menu('primary')
  - Includes a button .menu-toggle; navigation.js toggles .main-navigation.is-open and aria-expanded
- assets/js/navigation.js
  - Listens for clicks on .menu-toggle
  - Toggles the mobile menu open/closed and updates aria-expanded
- footer.php
  - Uses schema.org LocalBusiness
  - Left: wp_nav_menu('footer_links'); Center: custom logo; Right: contact + hours from Customizer
- style.css
  - Custom container/row/col layout (no framework)
  - Header: 25% logo / 75% nav on desktop; stacked on mobile
  - Gold nav buttons with subtle shadow; focus-visible styles; RTL-safe margins
  - Footer: stacked on mobile; three columns on desktop

Build and development

Option A — Lean, no build (default in PHP):
- CSS: edit style.css directly
- JS: edit assets/js/navigation.js
- Cache busting: version is controlled by PEI_VERSION in functions.php

Option B — Vite pipeline (src → dist):
This theme includes an optional Vite setup for bundling modern JS/CSS and processing assets in src/ into dist/. Use this when you want multiple entry points, ES modules, SCSS, or asset hashing.

Prerequisites
- Node.js 18+ and npm

Install once
```bash
npm install
```

Useful scripts
- Development server (serves and watches src/):
  ```bash
  npm run dev
  ```
  Vite will serve files with HMR at a local URL it prints in the console. During PHP/WordPress development, you typically continue to load templates via http://localhost (XAMPP), while Vite serves front-end modules and assets. For a pure asset build without HMR, use the build command below.

- Production build (outputs to dist/ with hashed filenames + manifest):
  ```bash
  npm run build
  ```
  This runs:
  - vite build --config vite.config.js (creates dist/ and manifest.json)
  - node ./scripts/optimize-images.mjs (optional image optimization step)

- Preview a built bundle:
  ```bash
  npm run preview
  ```

What gets built
- Source root: wp-content/themes/pei/src/
  - JS entries: src/main.js and any src/components/*/*.js files
  - You can add SCSS/CSS imported by these entries; Vite extracts CSS to dist/assets/*.css
- Output root: wp-content/themes/pei/dist/
  - Hashed files under dist/assets/
  - dist/manifest.json describing input → output file mappings
- Base URL (for assets): /wp-content/themes/pei/dist/

Config highlights (vite.config.js)
- Multiple entry points are auto-discovered via globby for src/components/*/*.js
- Sourcemaps enabled in dev and build
- SCSS supported (preprocessorOptions.scss)
- Manifest file enabled for PHP integration

Blocks and components: how they function today

- Philosophy: keep the front end lean and server-rendered. The theme primarily uses core Gutenberg blocks styled via the main `style.css` so what you see in the editor matches the front end (thanks to `add_editor_style('style.css')`).
- Custom pieces live under `src/components/<name>/` and are currently implemented as simple PHP templates/shortcodes rather than fully registered custom block types. You’ll see files like `hero/hero.php`, `services/services.php`, and `simple-cta/simple-cta.php`.
- Because there is no PHP manifest loader yet, component-specific assets built by Vite are not automatically enqueued on the front end or in the editor. Until that glue is added, favor placing production CSS in the single `style.css`, and keep any experimental editor JS in `src/components/*/*-editor.js` for future use.

Typical component folder contents
- `<component>.php` — server-rendered template (can be included from a theme template or wired as a shortcode).
- `<component>.css` — styles authored near the component during development; today these should be merged into `style.css` for production until Vite bundles are enqueued via the manifest.
- `<component>-editor.js` (optional) — editor-only behaviors or block UI scaffolding. Vite will pick this up as its own entry, but enqueueing is pending.

How components render in practice
- In templates you can include a component’s PHP file directly (e.g., `get_template_part()` or `require`), or expose it via a shortcode if convenient. The PHP emits semantic HTML. Styling comes from `style.css` today.
- Core blocks (Paragraph, Heading, Columns, etc.) are styled globally by `style.css`. That same stylesheet is loaded inside the editor so the editing experience is visually consistent.

Build pattern for blocks/components

- Auto-discovered entries: Vite automatically treats every `src/components/*/*.js` as an entry in addition to `src/main.js`.
- CSS extraction: If a component JS imports CSS/SCSS (e.g., `import './simple-cta.css'`), Vite extracts it to `dist/assets/*.css` alongside the JS chunk.
- Output mapping: `dist/manifest.json` maps entry keys (like `src/components/simple-cta/simple-cta-editor.js`) to their hashed JS and CSS URLs under `/wp-content/themes/pei/dist/assets/...`.
- Enqueue strategy (planned):
  1) Front end — read the manifest, resolve the entry for the component, enqueue its CSS and JS if needed.
  2) Editor — hook into `enqueue_block_editor_assets`, resolve and enqueue any `*-editor.js` (and its extracted CSS) that you want available in the block editor.
  3) Keep `PEI_VERSION` for non-Vite assets; rely on hashed filenames for Vite outputs.

Example: simple-cta component

Folder
```
src/components/simple-cta/
  simple-cta.php
  simple-cta.css
  simple-cta-editor.js   (optional; picked up by Vite)
```

Editor script stub (`src/components/simple-cta/simple-cta-editor.js`)
```
// This file is discovered and built by Vite as its own entry.
// Use it later to register a custom block, add block styles/variations,
// or enhance the editor UI. For now, keep it minimal.
import './simple-cta.css';

window.pei = Object.assign(window.pei || {}, {
  editorReady: (fn) => (document.readyState !== 'loading' ? fn() : document.addEventListener('DOMContentLoaded', fn))
});

window.pei.editorReady(() => {
  // Editor-only behavior can go here.
});
```

Front-end rendering (`src/components/simple-cta/simple-cta.php`)
```
<?php
// Minimal server-rendered example. Include this from a template or wire as a shortcode.
?>
<section class="simple-cta" role="region" aria-label="Call to action">
  <div class="container">
    <h2 class="simple-cta__title">Ready to start your project?</h2>
    <a class="btn btn--gold" href="/contact">Contact us</a>
  </div>
</section>
```

Until the manifest loader is added, copy needed CSS into the main `style.css` so the component renders correctly in production. Once the loader exists, you can keep CSS colocated and let PHP enqueue the extracted `dist/assets/*.css`.

Checklist: add a new block/component today
1) Create `src/components/<name>/` and add your `php` and `css` files.
2) If you plan editor behavior, create `<name>-editor.js` and import your CSS there as well.
3) Run `npm run build` during development to produce `dist/` outputs you can inspect.
4) For production until enqueue glue is in place, merge the essential CSS into `style.css`.
5) Include the PHP from your theme template (or register a shortcode) to render the component.

What’s next for blocks/components
- Add a small PHP helper that reads `dist/manifest.json` and enqueues entry points (front end and editor). This will unlock colocated CSS/JS per component and optional custom block registration without bloating the global bundle.
- Document the enqueue helper and provide examples mapping `src/components/*/*-editor.js` to `enqueue_block_editor_assets` once added.

How to load dist assets in WordPress (optional)
The theme currently enqueues style.css and assets/js/navigation.js directly to stay lean. If you choose to consume the Vite-built assets in PHP:
1) Build the assets: `npm run build`
2) Read dist/manifest.json in functions.php and enqueue the hashed files, for example:
   - Resolve entry keys like 'src/main.js' to their output paths
   - Enqueue script(s) and style(s) using get_stylesheet_directory_uri() . '/dist/...'

Tip: Keep PEI_VERSION for cache-busting non-Vite assets (style.css, navigation.js). Vite-built files are already cache-busted via hashed filenames.

Extending the theme
- Create templates like page.php, single.php, archive.php for custom layouts
- Use a child theme for larger customizations
- Follow the existing class names and layout utilities to keep it lean

Accessibility and RTL
- Focus-visible outlines on links and buttons
- Hamburger is a native button with aria-expanded and a screen reader label
- RTL: uses logical margins to keep alignment correct in RTL locales

Troubleshooting
- Menu missing: assign menus to “Primary Menu” and “Footer Useful Links”
- Logo missing: set a logo in Customizer → Site Identity
- Footer details empty: fill fields in Customizer → Footer Settings
- Stale assets: clear caches and bump PEI_VERSION in functions.php if needed

Changelog
- 1.1.0: Mobile-first header, Customizer footer settings, LocalBusiness schema, lean assets

License
Follows the standard WordPress licensing model. See the repository license for details.