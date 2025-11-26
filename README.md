PEI WordPress Project

This repository contains the PEI WordPress site. Sensitive files and volatile runtime data are excluded from version control using the provided .gitignore.

Included
- Custom theme: `wp-content/themes/pei/`
- WordPress core files (you may choose to ignore core in the future and manage via deploys)
- Project metadata: `.gitignore`, `.gitattributes`, this `README.md`

Excluded (for safety)
- Secrets and environment files: `wp-config.php`, `.env*`, `.htaccess`, etc.
- Runtime data: `wp-content/uploads/`, caches, backups, logs
- Third-party plugins and themes (all plugins and themes are ignored except the `pei` theme)

First-time setup
1) Initialize Git in this folder, add a remote, and push the initial commit.
2) Example commands (run in this directory):
   - `git remote add origin <YOUR_REMOTE_URL>`
   - `git push -u origin main`

Daily workflow
- Make changes, then run: `git add -A`, `git commit -m "Describe your change"`, `git push`

Restoring secrets on a new environment
- Copy `wp-config.php` from a secure location or recreate from `wp-config-sample.php` with correct DB credentials and salts.
- Recreate any environment files (`.env`) if you use them.
- Uploads are not versioned; restore from backups if needed.

Line endings
- `.gitattributes` normalizes text files to LF in the repo and keeps Windows scripts as CRLF to avoid cross-platform issues.

Adjusting ignore rules
- To track a specific plugin or theme, remove it from the ignore patterns in `.gitignore`.
- To version certain uploads (generally not recommended), add exceptions (for example: `!wp-content/uploads/some-folder/`).

Safe-by-default: No secrets or user uploads are pushed to Git.

---

Theme build: how it’s set up so far

Overview
- The custom theme lives at `wp-content/themes/pei`.
- A Vite-based front-end build pipeline is configured but only partially integrated on the PHP side. Today, the theme enqueues a lean, hand-authored stylesheet (`style.css`) and a minimal navigation script; Vite outputs are prepared for component/editor assets as the theme evolves.

Key parts
- `wp-content/themes/pei/package.json`
  - Scripts:
    - `npm run dev` → starts Vite in dev mode using `vite.config.js`
    - `npm run build` → builds assets to `dist/` and then optimizes images
    - `npm run preview` → previews the Vite build output
    - `npm run images` → runs image optimization only
- `wp-content/themes/pei/vite.config.js`
  - Multi-entry build:
    - Always includes `src/main.js` (theme-wide entry). This imports `src/styles/main.scss`.
    - Automatically includes every `*.js` file directly under `src/components/*/` as a separate entry. This is intended for block/editor scripts and future component-specific JS.
  - Output: `dist/` with hashed filenames and `manifest.json`.
  - Base path: `/wp-content/themes/pei/dist/`.
- `wp-content/themes/pei/scripts/optimize-images.mjs`
  - After a build, images are optimized in-place across the theme and (if present) the site-wide `wp-content/uploads` directory using imagemin (JPEG, PNG, GIF, SVG; WEBP left as-is except for SVGO where applicable).

What is currently enqueued in WordPress
- In `functions.php`:
  - `style.css` is enqueued as the main front-end stylesheet: `wp_enqueue_style('pei-style', get_stylesheet_uri(), ..., PEI_VERSION)`.
  - A small navigation toggle script is enqueued from `assets/js/navigation.js`.
- Vite-built assets (from `dist/`) are not yet enqueued on the front-end or editor. The manifest and multi-entry setup are in place to support that in a next step.

Source layout (theme)
- `style.css` → canonical theme stylesheet used in production right now (also loaded in the editor via `add_editor_style`).
- `src/main.js` → theme-wide JS entry; imports `src/styles/main.scss`.
- `src/styles/` → Sass entry point(s) for the Vite pipeline; eventual consolidation point for shared variables/mixins.
- `src/components/<component>/` → component folders. Typical files you may see:
  - `<component>.css` → front-end styles for the component (currently used by being compiled/managed during development; front-end enqueueing via Vite is not wired yet).
  - `<component>.php` and/or `*-shortcode.php` → server-rendered output or shortcode definition for the component.
  - `<component>-editor.js` (or any `.js` in the component folder) → picked up by Vite as its own entry; intended for block editor UIs or component-specific JS when enqueueing is added.

Local development
1) Open a terminal in `wp-content/themes/pei`.
2) Install dependencies: `npm install`.
3) During development:
   - Option A (build-on-save): `npm run build` to produce `dist/` bundles and then refresh the site.
   - Option B (dev server): `npm run dev` to run Vite. Note: front-end HMR is not yet wired into WordPress enqueueing; this is mainly useful to iterate on styles/scripts and to verify the `dist/` output with `npm run preview`.

Image pipeline
- The image optimizer runs after `npm run build`, scanning both the theme directory and (if present) `wp-content/uploads`.
- It writes results in-place, preserving directory structure. WEBP files are generally skipped for lossy recompression; SVGs are cleaned via SVGO with safe defaults.

How to add a new component (current workflow)
1) Create a folder under `src/components/<your-component>/`.
2) Add any of the following as needed:
   - `<your-component>.css` for styles.
   - `<your-component>-editor.js` or `<your-component>.js` for component/editor behavior (Vite will build it as its own entry).
   - A PHP render file (e.g., `<your-component>.php` or a shortcode file) under `src/components/<your-component>/` or wherever it logically belongs in the theme.
3) Run `npm run build` to emit updated assets to `dist/`.
4) Wire-up enqueueing (next step): the theme will need a small PHP layer to read `dist/manifest.json` and enqueue the relevant entries on the front-end and/or block editor. That glue is not committed yet.

Current state summary
- Production front-end assets: `style.css` + `assets/js/navigation.js`.
- Build system: Vite v5 with multi-entry, Sass, PostCSS (autoprefixer, cssnano), hashed assets, and a manifest file.
- Media: imagemin-based optimizer available via `npm run build` or `npm run images`.
- Components: CSS/PHP structure and several editor JS stubs exist; actual enqueueing of Vite-built bundles is the planned next step.