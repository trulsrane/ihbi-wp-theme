# IHBI Lab – WordPress Theme

This folder contains the custom theme that powers **ihbilab.org**. It's the part of the website that controls the visual design (colours, typography, spacing), the page layouts, and the structure of the specialised content you publish — projects, publications, team members, funding, and news.

The theme is one half of the site. The other half is the content you and your colleagues create through the WordPress admin. This README explains how the two halves fit together, so you understand what lives where and what you can change without a developer.

---

## What is a child theme?

Every WordPress site runs on a **theme** — a package of templates and styles that decides how the site looks and behaves. Rather than building one from scratch, this project is built as a **child theme** layered on top of WordPress's official **Twenty Twenty-Five** theme.

A useful way to picture it:

- **Twenty Twenty-Five** is the foundation — a polished, tested, and actively maintained theme that WordPress ships with. It handles the heavy lifting: the block editor integration, accessibility, responsive defaults, and security.
- **This child theme (`ihbi-wp-theme`)** sits on top and customises only the parts that make the IHBI site unique — your colours, your fonts, your content types, your patterns.

The benefit of this setup is that when WordPress releases an update to Twenty Twenty-Five — a bug fix, a security patch, or a new feature — the IHBI site gets it automatically. Only the specific files this theme chooses to override are affected; everything else continues to inherit from the parent.

Because of that inheritance, this folder is relatively small. It doesn't contain a complete website — it contains *the differences* between Twenty Twenty-Five and the IHBI site.

---

## How this theme reaches the live site

```
GitHub repository (this folder, version-controlled)
            │
            │   deployed automatically by WP Pusher
            ▼
Live WordPress install on Hostinger (ihbilab.org)
            │
            ▼
Visitor's browser
```

The theme code lives in a GitHub repository: **github.com/trulsrane/ihbi-wp-theme**. On the live site, the **WP Pusher** plugin watches that repository. When the developer pushes a change to GitHub, WP Pusher pulls it down and installs it on ihbilab.org — no manual FTP upload, no zipping and re-uploading through the WordPress admin.

This is also why theme files should **not** be edited through the WordPress admin's built-in file editor (Tools → Theme File Editor). Any edit made there would be reverted the next time a change is deployed from GitHub, and the edit would not be preserved in version history.

---

## What lives in the theme vs what lives in WordPress

This is the most useful distinction for day-to-day work.

### Lives in this theme (code, version-controlled)

These things require a developer to change:

- **Page templates** — the layout of a single Project page, the Publications archive, and so on
- **Custom content types** — what fields a Project, Publication, Team Member, or Funding post has, and how they behave
- **Research Directions taxonomy** — the category system used to tag projects and publications
- **Design tokens** — the colour palette, font families, heading scale, and spacing units, all defined in `theme.json`
- **Block patterns** — the IHBI-branded pre-made layouts (header, hero sections, call-to-action blocks) available in the block inserter
- **Shortcodes** — dynamic content snippets like `[team_list]`, `[publication_list]`, `[funding_list]`, `[project_details_bar]`

### Lives in WordPress (the database, editable from the admin)

These things are managed by lab staff with no developer involvement:

- **The content itself** — every project, publication, team member, funding entry, and news post you create
- **Static pages** — Home, About, Research, People, Funding, etc. were built in the WordPress block editor and can be edited there
- **Media** — images, PDFs, and other uploads in the Media Library
- **Menus, site title, tagline, widgets**
- **WordPress settings and plugin settings**

The rule of thumb: if you can do it through a menu in the WordPress admin sidebar, it's in WordPress and safe to change. If you're being asked to edit a `.php`, `.json`, or `.css` file, it's in the theme and should be done by a developer via the GitHub repository.

---

## Getting content onto the site

### Adding a team member

Go to **Team Members → Add New** in the admin. Set a title (the person's name), upload a featured image (their photo), pick a **Team Group** from the sidebar (Researchers / PhD & MSc Students / Alumni & Visiting Scholars), and fill in **Role**, **Email**, **Phone**, and **Background**. They appear automatically on the People page, grouped by Team Group and sorted alphabetically.

### Adding a project, publication, funding, or news item

Each has its own menu in the WordPress admin sidebar. Fill in the fields — they were designed specifically for your content — and click Publish. New posts appear on their respective archive pages and in any query loops that reference them.

### Building or editing a page

Use **Pages → All Pages** in the admin. Layouts are built using Full Site Editing with the WordPress block editor — you can drop in shortcodes, pick patterns from the **IHBI** category in the block inserter, or build freely with core blocks. The preset colours, fonts, and the `is-style-card` group style are all available in the block editor's sidebar.

---

## Requirements

- WordPress 6.7+
- Twenty Twenty-Five (parent theme — ships with WordPress core)
- Advanced Custom Fields (ACF) — provides the custom field infrastructure used by the Project, Publication, Team Member, and Funding post types

All three are already installed and configured on the live site.

---

## Folder structure

```
ihbi-wp-theme/
├── assets/         Fonts (Roboto, Open Sans) and other static assets
├── parts/          Header and footer template parts
├── patterns/       IHBI-branded block patterns
├── templates/      Page templates (single project, publication archive, etc.)
├── functions.php   Post type, taxonomy, ACF field, and shortcode registration
├── theme.json      Colour palette, typography scale, spacing, block defaults
└── style.css       Additional CSS not expressible in theme.json
```

---

## Further reading

For a complete technical reference — system architecture, hosting details, plugin inventory, design system specifics, known limitations, and contact responsibilities — see **IHBI_Technical_Design_Documentation.docx**, maintained alongside this project.

---

# For developers

Everything below is aimed at a developer picking up the project. The sections above are enough for day-to-day site management; this one covers local setup, code conventions, and the patterns used throughout the theme.

## Local setup

1. Install WordPress locally (LocalWP, DDEV, or a manual LAMP stack all work).
2. Install and activate the **Advanced Custom Fields** plugin.
3. Make sure **Twenty Twenty-Five** is present in `wp-content/themes/` — it ships with WordPress core.
4. Clone this repo into `wp-content/themes/`:
   ```bash
   git clone https://github.com/trulsrane/ihbi-wp-theme.git
   ```
5. Activate **IHBI Lab** from Appearance → Themes.
6. Visit **Settings → Permalinks** and click Save once — this flushes rewrite rules for the custom post types.

To mirror live content locally, export a database and media snapshot from the Hostinger hPanel or an UpdraftPlus backup.

## No build step

No Sass, no bundler, no `npm install`. Edit `.php`, `.css`, and `.json` files directly. Cache-busting is handled in `functions.php` via `wp_get_theme()->get('Version') . '.' . time()` on the enqueue, so CSS changes show up on refresh without manually bumping versions.

## Deployment

The live site uses **WP Pusher** to pull from GitHub:

1. Commit to `main`.
2. Push to `origin/main`.
3. WP Pusher picks up the change and replaces the theme files on ihbilab.org.

There is no staging environment. Test locally before pushing to `main`.

## Architectural principles

- **PHP-first.** Custom post types, taxonomies, ACF field groups, block patterns, and shortcodes are all registered in PHP — not through the WordPress admin UI. This keeps the site portable and diffable.
- **`theme.json` drives design.** Colours, fonts, spacing, and block-level defaults live in `theme.json` and are exposed as WordPress custom properties (`--wp--preset--color--accent-3`, `--wp--preset--spacing--40`, etc.). Use those in `style.css` rather than hard-coding values.
- **Static pages are the exception.** Home, About, Research, People, and Funding were built in the block editor and live in the database. They are not version-controlled; rely on UpdraftPlus backups for recovery.

## Adding a new custom post type

The existing CPTs (`project`, `publication`, `funding`, `team_member`) all follow the same three-part pattern in `functions.php`:

1. **Registration** — a function hooked to `init` that calls `register_post_type`.
2. **ACF field group** — registered via `acf_add_local_field_group` on the `acf/init` hook, with a `location` rule matching the new post type.
3. **Shortcode** — registered with `add_shortcode`, whose render callback uses `ob_start` / `ob_get_clean` and wraps the output in `shortcode_unautop( trim( ... ) )`.

The Projects block is the most comprehensive — copy it and adapt. After registering a new CPT, flush permalinks once via Settings → Permalinks.

## Adding a taxonomy

Follow `ihbi_register_direction_taxonomy` or `ihbi_register_team_group_taxonomy`. The latter also shows the pattern for seeding terms programmatically — useful when a taxonomy has a fixed set of values that should exist out of the box.

## Shortcode conventions

Every render callback in the theme:

1. Early-returns `''` if the context is wrong (e.g. wrong post type, no posts).
2. Uses `ob_start` / `ob_get_clean` rather than string concatenation.
3. Returns `shortcode_unautop( trim( ob_get_clean() ) )` so WordPress's `wpautop` filter doesn't wrap the output in stray `<p>` tags.

Note the deliberate reorder at the top of `functions.php`:

```php
remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop', 99 );
```

This makes `wpautop` run **after** shortcodes, which combined with `shortcode_unautop` in each callback keeps shortcode markup clean.

## Styling conventions

- Prefer `theme.json` over `style.css`. Colours, font sizes, spacing, and block defaults should be expressed as presets or block style definitions whenever possible.
- Reference WordPress custom properties (`--wp--preset--color--*`, `--wp--preset--font-family--*`, `--wp--preset--spacing--*`, `--wp--preset--font-size--*`) in `style.css` — don't hard-code hex values or font names.
- The `is-style-card` class is a registered block style for `core/group` (see `ihbi_register_block_styles`). Any group block can opt in via the editor's Styles sidebar; plain HTML can apply the class directly.

### Gotcha — outline button hover

The `.wp-block-button.is-style-outline` hover override lives in `style.css` with `!important`, not in `theme.json`. The core `elements.button:hover` rule generated from `theme.json` out-specificitys the variation `css` field even with `!important`, so the override must sit in `style.css` to win.

### Missing featured images

Archive templates use a CSS-only placeholder for posts without a featured image. The `post_thumbnail_html` filter in `functions.php` returns a `<div class="placeholder-thumbnail">` when no thumbnail is set, and `style.css` styles it with a centred image-icon mask.

## Testing changes

No automated test suite. After any non-trivial change, manually verify:

1. Homepage loads.
2. Each archive (Projects, Publications, People, News) loads and shows content.
3. One single post of each type renders correctly.
4. The block editor still opens for editing a static page.
5. The shortcode you touched (if any) still produces valid markup.

If you edit `functions.php`, also confirm `/wp-admin` doesn't white-screen — a fatal PHP error there will lock editors out of the admin.

## Known limitations and future work

Documented in full in the Technical Design Documentation. Short list:

- No multilingual support. Recommended plugin if needed later: Polylang.
- No dedicated SEO plugin (Yoast, Rank Math). Core WordPress + Google Site Kit is the current baseline.
- Responsive breakpoints are functional but not polished for tablet/phone.
- Static pages (Home, About, Research, People, Funding) live in the database, not in version control.
- An "Awards" post type was considered but not implemented — would follow the same pattern as Projects and Publications.

---

## Contact

- **Responsible person:** Kailun Feng — `kailun.feng@umu.se`
- **Developer:** Truls Rane — `truls.rane@gmail.com`

For feature requests, bug reports, or access to the GitHub repository, contact the developer directly.
