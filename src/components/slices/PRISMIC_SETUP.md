# Prismic Slice Setup Guide

How to configure each slice in Prismic's Custom Type builder.

---

## Centralized Theming System

All slices use a shared `Section` wrapper with standardized layout props. This means you can control spacing, backgrounds, and container widths consistently across all slices.

### Global Design Tokens (src/styles/global.css)

Change these once to affect all slices:

| Token | Purpose | Values |
|-------|---------|--------|
| `--color-bg` | Page background | Any color |
| `--color-bg-alt` | Alt section backgrounds | Any color |
| `--color-accent` | Accent color (buttons, highlights) | Any color |
| `--color-text` | Body text | Any color |
| `--font-serif` | Headings | Font stack |
| `--font-sans` | UI elements, labels | Font stack |
| `--space-*` | Spacing scale | `--space-1` to `--space-32` |
| `--container-*` | Container widths | `sm`, `md`, `lg`, `xl`, `2xl` |

### Shared Layout Props

Most slices accept these optional layout props:

| Prop | API ID | Type | Options | Default |
|------|--------|------|---------|---------|
| Background | `background` | Select | `default`, `alt`, `accent` | `default` |
| Spacing | `spacing` | Select | `default`, `compact`, `hero` | `default` |
| Container | `container` | Select | `default`, `narrow`, `wide`, `full` | varies |

**When to use each:**
- **background: alt** — subtle gray/off-white sections
- **background: accent** — bold colored sections (e.g., Stats, CTA)
- **spacing: compact** — tighter sections (e.g., between related content)
- **spacing: hero** — generous spacing for heroes and key sections
- **container: narrow** — prose content, forms
- **container: wide** — galleries, feature grids

---

## Quick Reference

| Slice | API ID | Carbon Tier | Complexity |
|-------|--------|-------------|------------|
| HeroHomepage | `herohomepage` | A | Simple |
| HeroSecondary | `hero_secondary` | A | Simple |
| TextStatement | `text_statement` | A* | Simple |
| TextProse | `text_prose` | A* | Simple |
| ImageBlock | `image_block` | A | Simple |
| TwoColumn | `two_column` | A* | Medium |
| Features | `features` | A* | Medium (repeatable) |
| Stats | `stats` | A* | Medium (repeatable) |
| Quote | `quote` | A* | Simple |
| Accordion | `accordion` | A* | Medium (repeatable) |
| CTA | `cta` | A* | Simple |
| ImageGallery | `image_gallery` | A | Medium (repeatable) |
| TeamGallery | `team_gallery` | A | Medium (repeatable) |
| VideoEmbed | `video_embed` | A | Simple |
| LogoCloud | `logo_cloud` | A* | Medium (repeatable) |
| ContactForm | `contact_form` | A* | Simple |
| NewsletterSignup | `newsletter_signup` | A* | Simple |
| Spacer | `spacer` | A* | Minimal |
| Divider | `divider` | A* | Minimal |
| ScrollAccordion | `scroll_accordion` | C | Medium (repeatable) |
| HorizontalGallery | `horizontal_gallery` | C | Medium (repeatable) |

---

## Slice Configurations

### hero_homepage

**Purpose:** Homepage hero with background image, heading, tagline, and button.

**Primary Fields (Non-repeatable):**
| Label | API ID | Type |
|-------|--------|------|
| Heading | `heading` | Key Text |
| Tagline | `tagline` | Key Text |
| Image | `image` | Image |
| Button Label | `button_label` | Key Text |
| Button Link | `button_link` | Link |

**Repeatable Fields:** None

**Developer Controls (set in code, not Prismic):**
- Position: line ~95 (default: `bottom-left`)
- Height: line ~96 (default: `large` = 90vh)
- Overlay: line ~97 (default: `40`)

---

### hero_secondary

**Purpose:** Coloured hero for inner/secondary pages. Solid background with optional decorative textural PNG/SVG (not for photo backgrounds — use hero_homepage for that).

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Heading | `heading` | Key Text | Main H1 text |
| Tagline | `tagline` | Key Text | Supporting text below heading |
| Button Label | `button_label` | Key Text | Optional button text |
| Button Link | `button_link` | Link | Button destination |
| Textural Image | `textural_image` | Image | Optional decorative PNG/SVG texture (blobs, scribbles) |
| Background Colour | `background_colour` | Select | Options: `accent` (default), `main`, `secondary` |

**Repeatable Fields:** None

**Developer Controls (set in code, not Prismic):**
- Height: line ~105 — `"large"` (85vh, default), `"medium"` (65vh), `"half"` (50vh)
- Text alignment: line ~106 — `"center"` (default), `"left"`
- Heading max-width: line ~185 — `20ch` default
- Texture opacity: line ~175 — `0.15` default (range 0.05–0.4)
- Texture sizing: line ~170 — `cover` default

**Notes:**
- Button style auto-adapts: outlined/inverted on accent, outlined on main/secondary
- Textural image should be a **PNG with transparency** — it overlays the solid colour
- Uses `eager` loading since heroes are above-the-fold

---

### text_statement

**Purpose:** Bold intro text for manifestos, opening statements, and impactful short-form copy.

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Content | `content` | Rich Text | The statement text |
| Background Colour | `background_colour` | Select | Options: `main` (Main colour), `secondary` (Secondary colour) |

**Repeatable Fields:** None

**Developer Controls (set in code, not Prismic):**
- Max-width: line ~75 (default: `55ch`)
- Positioning: line ~76 (default: `margin-inline: auto`)

---

### text_prose

**Purpose:** Long-form body copy for case studies, articles, and detailed content.

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Content | `content` | Rich Text | The prose content |
| Background Colour | `background_colour` | Select | Options: `main` (Main colour), `secondary` (Secondary colour) |

**Repeatable Fields:** None

**Developer Controls (set in code, not Prismic):**
- Max-width: line ~70 (default: `65ch`)
- Positioning: line ~71 (default: `margin-inline: auto`)

---

### image_block

**Purpose:** Standalone image with optional caption. Full-width or inset display.

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Image | `image` | Image | The image (includes alt, dimensions) |
| Caption | `caption` | Key Text | Optional caption text |
| Display Mode | `display_mode` | Select | Options: `full-width` (Full width, default), `inset` (Inset) |
| Background Colour | `background_colour` | Select | For inset only. Options: `main`, `secondary`, `split-main-secondary`, `split-secondary-main` |

**Repeatable Fields:** None

**Developer Controls (set in code, not Prismic):**
- Full-width aspect ratio: mobile ~140 (default: `3 / 2`), desktop ~145 (default: `2.35 / 1`)
- Inset aspect ratio: line ~175 (default: `16 / 9`)
- Border radius: line ~125 (default: `0` — sharp)
- Inset max-width: line ~165 (default: `1200px` — matches TextStatement)

**Display Mode Behaviors:**
- **Full width:** Edge-to-edge, 3:2 on mobile → 2.35:1 on desktop, caption overlaid
- **Inset:** 16:9 aspect ratio, caption below, optional split background effect

---

### two_column

**Purpose:** Text and image side by side

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Eyebrow | `eyebrow` | Key Text | Small label above headline |
| Headline | `headline` | Key Text | Section heading |
| Content | `content` | Rich Text | Body text (prose) |
| Image | `image` | Image | Section image |
| Image Position | `image_position` | Select | Options: `left`, `right` |

**Repeatable Fields:** None

---

### features

**Purpose:** Grid of feature/benefit cards

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Eyebrow | `eyebrow` | Key Text | Small label above headline |
| Headline | `headline` | Key Text | Section heading |
| Subheadline | `subheadline` | Key Text | Supporting text |
| Columns | `columns` | Select | Options: `2`, `3`, `4` |
| Background | `background` | Select | Options: `default`, `alt`, `accent` |
| Spacing | `spacing` | Select | Options: `default`, `compact`, `hero` |

**Repeatable Fields (Items):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Icon | `icon` | Key Text | Emoji or text icon |
| Title | `title` | Key Text | Feature title |
| Description | `description` | Key Text | Feature description |

---

### stats

**Purpose:** Number/metric highlights

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Background | `background` | Select | Options: `default`, `alt`, `accent` |
| Spacing | `spacing` | Select | Options: `default`, `compact`, `hero` |

**Repeatable Fields (Items):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Value | `value` | Key Text | The number (e.g., "50+") |
| Label | `label` | Key Text | What it measures |

**Note:** Use `background: accent` for the bold colored background effect.

---

### quote

**Purpose:** Testimonial or pullquote

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Quote | `quote` | Key Text | The quote text |
| Author | `author` | Key Text | Who said it |
| Role | `role` | Key Text | Their title/company |
| Image | `image` | Image | Author photo |
| Variant | `variant` | Select | Options: `default`, `large` |
| Background | `background` | Select | Options: `default`, `alt`, `accent` |
| Spacing | `spacing` | Select | Options: `default`, `compact`, `hero` |

**Repeatable Fields:** None

---

### faq

**Purpose:** Accordion Q&A section

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Headline | `headline` | Key Text | Section heading |
| Subheadline | `subheadline` | Key Text | Supporting text |
| Background | `background` | Select | Options: `default`, `alt`, `accent` |
| Spacing | `spacing` | Select | Options: `default`, `compact`, `hero` |

**Repeatable Fields (Items):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Question | `question` | Key Text | The question |
| Answer | `answer` | Key Text | The answer |

**Note:** Uses narrow container by default.

---

### cta

**Purpose:** Full-width call-to-action banner with optional decorative blob shapes.

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Title | `title` | Key Text | Small text above main text (e.g., brand name) |
| Text | `text` | Key Text | Main CTA text |
| Button Label | `button_label` | Key Text | Button text |
| Button Link | `button_link` | Link | Button destination |
| Background Colour | `background_colour` | Select | Options: `accent` (default), `main`, `secondary` |

**Repeatable Fields:** None

**Developer Controls (set in code, not Prismic):**
- Display mode: line ~95 (default: `"full-width"` — options: `"full-width"`, `"inset"`)
- Text alignment: line ~96 (default: `"center"` — options: `"center"`, `"left"`)
- Blob decorations: line ~97 (default: `true` — set to `false` to disable)
- Blob SVG paths: lines ~130-165 (replace with hand-drawn blobs per project)
- Blob colors: line ~260 (default: `rgba(0,0,0,0.15)`)

**Features:**
- **Entire section is clickable** (if link provided) — no JS required
- **4 unique blobs** animate in from corners on hover (with varied rotations)
- **Inset mode**: CTA is contained, outer background auto-contrasts
- Pill-shaped button, hover state triggered by section hover
- Button style auto-switches based on background color

**Inset Mode Background Logic:**
- CTA = `main` → outer = `secondary`
- CTA = `secondary` → outer = `main`
- CTA = `accent` → outer = `main`

---

### image_gallery

**Purpose:** Grid of images

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Columns | `columns` | Select | Options: `2`, `3`, `4` |
| Aspect Ratio | `aspect_ratio` | Select | Options: `square`, `landscape`, `portrait` |

**Repeatable Fields (Items):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Image | `image` | Image | Gallery image |

---

### team_gallery

**Purpose:** Team member gallery — 2-column grid with photo, role, name, and bio.

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Heading | `heading` | Key Text | Section title (e.g. "Meet the Team") |
| Description | `description` | Rich Text | Optional intro paragraph |
| Background Colour | `background_colour` | Select | Options: `main` (Main colour), `secondary` (Secondary colour), `accent` (Accent colour) |

**Repeatable Fields (Items):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Image | `image` | Image | Team member photo (includes alt text) |
| Job Title | `job_title` | Key Text | Role / position (displayed uppercase) |
| Name | `name` | Key Text | Person's full name |
| Bio | `bio` | Rich Text | Short biography paragraph |

**Developer Controls (set in code, not Prismic):**
- Aspect ratio: line ~130 (default: `"square"` — options: `"square"`, `"landscape"`, `"portrait"`)
- Marker style: line ~135 (default: `"square"` — options: `"square"`, `"svg"`)
- Custom marker SVG: line ~140 (replace SVG string with your own icon, uses `currentColor`)
- Gap size: line ~185 (default: `var(--space-12)`)
- Border radius: line ~205 (default: `0`)

**Colour Logic:**
- `main` / `secondary` background → marker uses `--color-accent`
- `accent` background → marker uses `--color-bg-alt`, text uses `--color-bg` (inherited from Section)

**Notes:**
- Layout is always 2-column on desktop, 1-column on mobile
- Each member card is itself a 2-column sub-grid (image left, info right) on desktop
- The marker before job titles can be swapped from a square to a custom SVG per project (Step 4/5 in code)

---

### video_embed

**Purpose:** YouTube or Vimeo embed

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| URL | `url` | Key Text | YouTube or Vimeo URL |
| Title | `title` | Key Text | Accessible title for iframe |
| Aspect Ratio | `aspect_ratio` | Select | Options: `16:9`, `4:3`, `1:1` |

**Repeatable Fields:** None

---

### logo_cloud

**Purpose:** Client/partner/sponsor logos for social proof

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Headline | `headline` | Key Text | e.g., "Our Sponsors", "Trusted by" |
| Description | `description` | Rich Text | Optional intro paragraph |
| Background Colour | `background_colour` | Select | Options: `main` (Main colour), `secondary` (Secondary colour) |

**Repeatable Fields (Items):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Logo | `logo` | Image | Logo image (includes alt text) |
| Name | `name` | Key Text | Company/sponsor name |
| Link | `link` | Link | Optional link to company website |
| Featured | `is_featured` | Boolean | Flag as main/primary sponsor |

**Developer Controls (set in code, not Prismic):**
- Show names: line ~105 (default: `false` — logo-only grid)
- Featured treatment: line ~106 (default: `true` — featured logos larger + full colour)
- Logo height: line ~185 (default: `32px`, featured: `48px`)
- Grayscale: line ~190 (default: `grayscale(100%)`, featured always `0%`)

**Notes:**
- Featured logos render above regular logos, larger and in full colour
- Non-featured logos are grayscale at 60% opacity, full colour on hover
- All logos can optionally link to company websites (opens in new tab)

---

### contact_form

**Purpose:** Standard contact form

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Headline | `headline` | Key Text | Section heading |
| Subheadline | `subheadline` | Key Text | Supporting text |
| Submit Label | `submit_label` | Key Text | Button text |
| Form Name | `form_name` | Key Text | For form handler |
| Background | `background` | Select | Options: `default`, `alt`, `accent` |
| Spacing | `spacing` | Select | Options: `default`, `compact`, `hero` |

**Repeatable Fields:** None

**Note:** Uses narrow container by default.

---

### newsletter_signup

**Purpose:** Email capture form

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Headline | `headline` | Key Text | Section heading |
| Subheadline | `subheadline` | Key Text | Supporting text |
| Placeholder | `placeholder` | Key Text | Input placeholder |
| Submit Label | `submit_label` | Key Text | Button text |
| Form Name | `form_name` | Key Text | For form handler |

**Repeatable Fields:** None

---

### spacer

**Purpose:** Vertical space between sections

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Size | `size` | Select | Options: `sm`, `md`, `lg`, `xl` |

**Repeatable Fields:** None

---

### divider

**Purpose:** Visual separator

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Variant | `variant` | Select | Options: `full`, `centered`, `accent` |

**Repeatable Fields:** None

---

### scroll_accordion (Tier C)

**Purpose:** Scroll-driven collapsing accordion

**Primary Fields (Non-repeatable):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Eyebrow | `eyebrow` | Key Text | Small label |
| Headline | `headline` | Key Text | Sticky headline |
| Description | `description` | Key Text | Optional text |

**Repeatable Fields (Items):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Icon | `icon` | Key Text | Emoji or text icon |
| Title | `title` | Key Text | Item title |
| Description | `description` | Key Text | Item description |

---

### horizontal_gallery (Tier C)

**Purpose:** Horizontal scroll gallery

**Primary Fields (Non-repeatable):** None

**Repeatable Fields (Items):**
| Field | API ID | Type | Notes |
|-------|--------|------|-------|
| Image | `image` | Image | Gallery image |

---

## Notes

### Field Type Reference

| Prismic Type | Use For |
|--------------|---------|
| Key Text | Short single-line text |
| Rich Text | Long-form content with formatting |
| Image | Images (with alt text) |
| Link | URLs (internal or external) |
| Select | Dropdown options |
| Boolean | True/false toggles |
| Number | Numeric values |

### Naming Convention

- Use **snake_case** for API IDs (e.g., `hero_basic`, `primary_cta_label`)
- Prismic will auto-generate display names from API IDs
- Keep names consistent between Prismic and this codebase

### Adding New Slices

1. Create the slice in Prismic's Custom Type builder
2. Add the mapping in `SliceZone.astro`
3. Ensure your Astro component handles the props
4. Test with the prismic-test page


