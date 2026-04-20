# Typography Migration

I need you to update the typography system in my WordPress child theme located in the current directory. The theme is a child of Twenty Twenty-Five (TT5) and uses `theme.json` for all style configuration. Please make all changes to `theme.json`, `style.css`, and any template or pattern files that reference old font size slugs.

---

## Step 1 — Update `settings.typography.fontSizes` in `theme.json`

Replace the existing font size definitions with the following. Keep fluid scaling using the Figma `px` values as the `max` and approximately 85% of that as the `min`. Set `defaultFontSizes: false` to suppress TT5 defaults.

| Slug      | Label   | Max (Figma) | Min (fluid) |
|-----------|---------|-------------|-------------|
| `caption` | Caption | 14px        | 13px        |
| `body`    | Body    | 16px        | 15px        |
| `h6`      | H6      | 15px        | 14px        |
| `h5`      | H5      | 20px        | 17px        |
| `h4`      | H4      | 22.5px      | 19px        |
| `h3`      | H3      | 32px        | 26px        |
| `h2`      | H2      | 45px        | 34px        |
| `h1`      | H1      | 64px        | 42px        |

---

## Step 2 — Update `styles.elements` in `theme.json`

### Heading elements

All headings use font-family Roboto, font-weight 700, font-style normal.

| Element          | fontSize slug | letterSpacing | lineHeight |
|------------------|---------------|---------------|------------|
| `heading` (global) | —           | —             | —          |
| `h1`             | `h1`          | -1.28px       | 1.0        |
| `h2`             | `h2`          | -0.9px        | 1.1        |
| `h3`             | `h3`          | -0.48px       | 1.3        |
| `h4`             | `h4`          | —             | 1.3        |
| `h5`             | `h5`          | —             | 1.5        |
| `h6`             | `h6`          | —             | 1.5        |

### Global body typography (`styles.typography`)

| Property      | Value      |
|---------------|------------|
| fontFamily    | Open Sans  |
| fontSize      | `body` slug |
| fontWeight    | 400        |
| lineHeight    | 1.6        |
| letterSpacing | 1px        |

### Caption element (`styles.elements.caption`)

| Property      | Value        |
|---------------|--------------|
| fontFamily    | Open Sans    |
| fontSize      | `caption` slug |
| fontWeight    | 400          |
| lineHeight    | 1.5          |
| letterSpacing | 0.14px       |

### Button element (`styles.elements.button.typography`)

| Property   | Value      |
|------------|------------|
| fontFamily | Roboto     |
| fontSize   | `h6` slug  |
| fontWeight | 700        |
| lineHeight | 1.35       |

---

## Step 3 — Find and replace all old slug references

Search every file in the theme directory (`.json`, `.html`, `.php`, `.css`) for the old font size slug names and replace them with the new ones.

| Old slug   | New slug  |
|------------|-----------|
| `small`    | `caption` |
| `medium`   | `body`    |
| `large`    | `h4`      |
| `x-large`  | `h2`      |
| `xx-large` | `h1`      |

In `theme.json` slugs appear in three forms — replace all three:

```
"slug": "medium"
var:preset|font-size|medium
var(--wp--preset--font-size--medium)
```

In `.html` and `.php` files they appear as block attributes and CSS custom property strings:

```
"fontSize":"large"
var(--wp--preset--font-size--large)
```

---

## Step 4 — Verify

After making all changes:

1. Scan the entire theme directory for any remaining references to the old slugs (`small`, `medium`, `large`, `x-large`, `xx-large`) and report any that were missed or could not be safely replaced.
2. Report if any block attributes used numeric font size values directly instead of slugs, as those will need manual review.

---

## Important constraints

- Do not modify any files in the parent TT5 theme directory
- Do not change font family assignments — Roboto for headings/button, Open Sans for body/caption