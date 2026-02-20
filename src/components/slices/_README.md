# Slice Components

Quick reference for all available slices.

---

## KB Rating & Carbon Tier System

Every slice is rated for sustainability and performance:

### KB Rating (JavaScript payload)
| Rating | JS Size | Description |
|--------|---------|-------------|
| **A*** | 0KB | Pure CSS, no JavaScript |
| **A** | <5KB | Minimal JS (e.g., tiny vanilla script) |
| **B** | 5-20KB | Light interactivity |
| **C** | 20-50KB | Animation libraries (GSAP) |
| **D** | 50KB+ | Heavy libraries (avoid) |

### Carbon Tier (overall environmental impact)
| Tier | Impact | Use Case |
|------|--------|----------|
| **A*** | Minimal | Use freely — best for websitecarbon.com scores |
| **A** | Low | Use freely — slight external resource load |
| **B** | Medium | Use as needed |
| **C** | Higher | Use sparingly — high-impact features only |
| **D** | High | Avoid unless essential |

---

## Centralized Layout System

All content slices use the shared `Section` wrapper component. This provides consistent layout props across all slices:

### Shared Layout Props

| Prop | Options | Default | Effect |
|------|---------|---------|--------|
| `background` | `default`, `alt`, `accent` | `default` | Section background color |
| `spacing` | `default`, `compact`, `hero` | varies | Vertical padding |
| `container` | `default`, `narrow`, `wide`, `full` | varies | Content width |

### How It Works

1. **Change global tokens** in `src/styles/global.css` → affects ALL slices
2. **Pass layout props** to individual slices → overrides defaults per-instance
3. **Slice-specific props** (alignment, columns, etc.) → content-specific options

```astro
<!-- Example: CTA with accent background and compact spacing -->
<CTA 
  headline="Ready to get started?"
  background="accent"
  spacing="compact"
  primaryCta={{ label: "Contact Us", href: "/contact" }}
/>
```

---

## Slice Reference by Carbon Tier

### Tier A* (0KB JS — use freely)
| Slice | Purpose | Key Props |
|-------|---------|-----------|
| `HeroBasic` | Text-focused hero | alignment, + layout props |
| `HeroWithImage` | Hero with side image | imagePosition, + layout props |
| `TextStatement` | Bold intro text, manifestos | background_colour |
| `TextProse` | Long-form body copy | background_colour |
| `TwoColumn` | Text + image side by side | imagePosition, + layout props |
| `Features` | Feature cards grid | columns, + layout props |
| `Stats` | Number highlights | + layout props (use `background: accent`) |
| `Quote` | Testimonial | variant, + layout props |
| `Accordion` | Accordion | items, + layout props |
| `CTA` | Call-to-action banner | background_colour, text_alignment, blobs on hover |
| `ImageGallery` | Image grid | columns, aspectRatio |
| `LogoCloud` | Client logos | logos: [{ src, alt, href? }] |
| `ContactForm` | Full contact form | formName, + layout props |
| `NewsletterSignup` | Email capture | headline, placeholder |
| `Spacer` | Vertical space | size: sm \| md \| lg \| xl |
| `Divider` | Visual separator | variant: full \| centered \| accent |

### Tier A (external resources)
| Slice | Purpose | Key Props |
|-------|---------|-----------|
| `ImageBlock` | Standalone image with caption | display_mode, background_colour (inset only) |
| `VideoEmbed` | YouTube/Vimeo iframe | url (auto-converts) |

### Tier C (~45KB JS — use sparingly)
| Slice | Purpose | Key Props |
|-------|---------|-----------|
| `HorizontalGallery` | Horizontal scroll gallery | images: [{ src, alt }] |
| `ScrollAccordion` | Scroll-driven accordion | items: [{ title, description }] |

---

## ImageBlock — Visual Bridging

The `ImageBlock` slice supports split backgrounds that create visual continuity between sections:

| Background | Effect | Use Case |
|------------|--------|----------|
| `main` | Solid main color | Standard inset image |
| `secondary` | Solid alt color | Inset on alt background |
| `split-main-secondary` | Top main, bottom secondary | Bridges main → alt sections |
| `split-secondary-main` | Top secondary, bottom main | Bridges alt → main sections |

**Split backgrounds have tighter padding** to emphasize the bridging effect. The background bleeds edge-to-edge while the image stays contained.

---

## Building for Sustainability Targets

### For A* websitecarbon.com rating
Use only Tier A* slices:
```
HeroBasic → Features → Stats → Quote → CTA
```

### For A rating (with wow factor)
Add one Tier C slice max:
```
HeroBasic → HorizontalGallery → Features → FAQ → CTA
```

### For B rating (richer experience)
Use Tier C slices as needed, but be mindful:
```
HeroWithImage → ScrollAccordion → Features → VideoEmbed → CTA
```

---

## Common Page Patterns

### Homepage
```
HeroBasic (center)
LogoCloud
Features
Stats (accent)
TwoColumn
Quote
CTA (accent)
```

### About Page
```
HeroWithImage
TwoColumn (alternating x2)
Stats
Quote
CTA
```

### Services Page
```
HeroBasic
Features
TwoColumn
FAQ
CTA
```

### Contact Page
```
HeroBasic (simple)
TwoColumn (with map or image)
ContactForm
```

---

## Editorial vs Classic

The site's overall feel is controlled by `--gutter` in global.css:

| Style | Gutter | Feel |
|-------|--------|------|
| **Editorial** | `--space-2` or `0` | Edge-to-edge, trendy, magazine-like |
| **Classic** | `--space-4` to `--space-6` | Comfortable margins, traditional |

### Full-bleed slices

Some slices should *always* be edge-to-edge regardless of gutter setting:
- `HorizontalGallery` — always full viewport
- Hero images (when using `.container--bleed`)

Use `.container--bleed` when you want a section to ignore the global gutter.

---

## Prismic Integration

All slices support both direct props (for Astro pages) and Prismic field formats.

### Key Differences

| Feature | Direct (Astro) | Prismic |
|---------|---------------|---------|
| Repeatable items | `features: []` | `items: []` |
| CTA buttons | `primaryCta: { label, href }` | `primary_cta_label`, `primary_cta_link` |
| Images | `{ src, alt }` | `{ url, alt }` |
| Select fields | lowercase | May be Capitalized |

### Using with Prismic

1. Import `SliceZone` component
2. Fetch page data from Prismic
3. Pass slices: `<SliceZone slices={page.data.body} />`

See `PRISMIC_SETUP.md` for full field configuration guide.

---

## Quick Customisation

### Per-project (global.css)

Change these design tokens to affect ALL slices instantly:

| Token | What it changes |
|-------|-----------------|
| `--color-bg` | Page + default section backgrounds |
| `--color-bg-alt` | Alt section backgrounds |
| `--color-accent` | Buttons, accent sections, highlights |
| `--color-text` | Body text |
| `--font-serif` | Headings |
| `--font-sans` | UI elements, labels |
| `--gutter` | Global horizontal padding |
| `--radius-*` | Border radius (set to 0 for sharp) |

### Per-slice

1. Check annotation block at top of each `.astro` file
2. Pass layout props (`background`, `spacing`, `container`)
3. For deeper customization, edit scoped styles in the slice

### Creating New Slices

Use the `Section` wrapper for consistency:

```astro
---
import Section from '../Section.astro';

interface Props {
  headline: string;
  background?: 'default' | 'alt' | 'accent';
  spacing?: 'default' | 'compact' | 'hero';
}

const { headline, background = 'default', spacing = 'default' } = Astro.props;
---

<Section spacing={spacing} background={background}>
  <h2>{headline}</h2>
  <!-- slice content -->
</Section>
```


