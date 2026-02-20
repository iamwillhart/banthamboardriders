# Astro + Prismic Starter

A minimal, editorial Astro starter with Prismic CMS integration. Zero JavaScript by default, sustainable by design.

> ⚠️ **Work in Progress** — This template is under active development. Review all documentation and test thoroughly before using on client projects.

---

## 📖 Documentation

- **[CONTENT_GUIDE.md](./CONTENT_GUIDE.md)** — Image sizes and content recommendations for clients

---

## 🚀 Quick Start (New Project)

### 1. Clone & Install

```bash
# Clone template
git clone [this-repo] my-project
cd my-project
npm install
```

### 2. Create Prismic Repository

1. Go to [prismic.io](https://prismic.io) and create a new repository
2. Open `src/lib/prismic.ts` and update the repository name:

```ts
const repositoryName = 'your-repo-name';
```

### 3. Configure Routes (⚠️ IMPORTANT)

In `src/lib/prismic.ts`, the `routes` array **must only include document types that exist in your Prismic repo**. Prismic validates this — if you declare a type that doesn't exist, all fetches will fail silently.

```ts
const routes: Array<{ type: string; path: string }> = [
  // ✅ Only include types that exist in YOUR Prismic repo
  { type: "homepage", path: "/" },
  { type: "about", path: "/about" },
  
  // ❌ Don't include types you haven't created yet
  // { type: "blog_post", path: "/blog/:uid" },  // Will break everything!
];
```

**When adding new document types:**
1. Create the custom type in Prismic first
2. Then add the route to the `routes` array
3. Create the corresponding page file in `src/pages/`

### 4. Import Custom Types

In your Prismic dashboard:
1. Go to **Custom Types**
2. Click **Create new** → **Import from JSON**
3. Import each file from `/customtypes/`:
   - `homepage/index.json` — Single type
   - `page/index.json` — Repeatable
   - `project/index.json` — Repeatable
   - `blog_post/index.json` — Repeatable

### 5. Customise Design

Open `src/styles/global.css` and change:

1. **Colors** → `--color-bg`, `--color-text`, `--color-accent`
2. **Fonts** → `--font-serif`, `--font-sans`
3. **Radius** → `0` for sharp, keep for rounded
4. **Gutter** → `--space-2` for editorial, `--space-4`+ for classic

Everything cascades to all components.

### 6. Configure Layout.astro

Open `src/layouts/Layout.astro` and update:

1. **Default Meta Description** (line ~17) — Fallback for pages without a description:
   ```ts
   const siteDescription = "Your client's tagline or default description here.";
   ```

2. **Fonts** — Self-hosted via Fontsource (no Google Fonts CDN). To change fonts:
   - Uninstall: `npm uninstall @fontsource/stack-sans-text`
   - Install new font: `npm install @fontsource/[font-name]`
   - Update imports in Layout.astro
   - Update `--font-serif` and `--font-sans` in global.css

### 7. Create Content & Build

```bash
# Start dev server
npm run dev

# Build for production
npm run build
```

---

## 📁 Project Structure

```
├── customtypes/          # Prismic type definitions (importable)
│   ├── homepage/
│   ├── page/
│   ├── project/
│   └── blog_post/
├── src/
│   ├── components/
│   │   ├── slices/       # Prismic slice components
│   │   ├── SliceZone.astro
│   │   ├── Header.astro
│   │   └── Footer.astro
│   ├── layouts/
│   │   └── Layout.astro
│   ├── lib/
│   │   └── prismic.ts    # Prismic client config
│   ├── pages/
│   │   ├── index.astro         # → Prismic homepage
│   │   ├── [uid].astro         # → Prismic pages (about, contact, services)
│   │   ├── projects/
│   │   │   ├── index.astro     # Project listing
│   │   │   └── [uid].astro     # Project detail
│   │   └── blog/
│   │       ├── index.astro     # Blog listing
│   │       └── [uid].astro     # Blog post
│   └── styles/
│       ├── global.css          # Design system
│       └── _presets/           # Quick-start color schemes
└── public/
```

---

## 📄 Default Pages

| URL | Prismic Type | Purpose |
|-----|--------------|---------|
| `/` | `homepage` | Homepage (single type) |
| `/about` | `page` | About page |
| `/contact` | `page` | Contact page |
| `/services` | `page` | Services page |
| `/projects` | — | Project listing (auto-generated) |
| `/projects/[slug]` | `project` | Project detail |
| `/blog` | — | Blog listing (auto-generated) |
| `/blog/[slug]` | `blog_post` | Blog post |

Create pages in Prismic with matching UIDs (e.g., `about`, `contact`, `services`).

---

## 🧩 Available Slices

### Tier A* (0KB JS — use freely)

| Slice | API ID | Purpose |
|-------|--------|---------|
| HeroBasic | `hero_basic` | Text-focused hero |
| HeroWithImage | `hero_with_image` | Hero with side image |
| TextStatement | `text_statement` | Bold intro text, manifestos |
| TextProse | `text_prose` | Long-form body copy |
| TwoColumn | `two_column` | Text + image side by side |
| Features | `features` | Feature cards grid |
| Stats | `stats` | Number highlights |
| Quote | `quote` | Testimonial |
| Accordion | `Accordion` | Accordion |
| CTA | `cta` | Call-to-action banner |
| ImageGallery | `image_gallery` | Image grid |
| LogoCloud | `logo_cloud` | Client logos |
| ContactForm | `contact_form` | Contact form |
| NewsletterSignup | `newsletter_signup` | Email capture |
| Spacer | `spacer` | Vertical space |
| Divider | `divider` | Visual separator |

### Tier A (external resources)

| Slice | API ID | Purpose |
|-------|--------|---------|
| VideoEmbed | `video_embed` | YouTube/Vimeo |

### Tier C (~45KB JS — use sparingly)

| Slice | API ID | Purpose |
|-------|--------|---------|
| HorizontalGallery | `horizontal_gallery` | Horizontal scroll |
| ScrollAccordion | `scroll_accordion` | Scroll-driven accordion |

---

## 🎨 Styling System

### CSS Variables

All styling flows from `src/styles/global.css`:

```css
:root {
  /* Brand Colors */
  --color-bg: #dfd9d1;
  --color-bg-alt: #ffffff;
  --color-text: #1a1a1a;
  --color-text-muted: rgba(26, 26, 26, 0.6);
  --color-accent: #277060;
  
  /* Typography */
  --font-serif: Georgia, serif;
  --font-sans: system-ui, sans-serif;
  
  /* Layout */
  --gutter: var(--space-4);  /* Editorial: --space-2, Classic: --space-6 */
  
  /* Borders */
  --radius-sm: 0.25rem;      /* Set to 0 for sharp aesthetic */
  --radius-md: 0.5rem;
  --radius-lg: 1rem;
}
```

### Presets

Quick-start schemes in `src/styles/_presets/`:
- `warm-minimal.css` — Calm, organic
- `dark-luxury.css` — Premium, sophisticated
- `bold-modern.css` — Energetic, confident
- `soft-sage.css` — Natural, trustworthy
- `clean-mono.css` — Minimal, professional

---

## 🔧 Development

```bash
npm run dev      # Start dev server
npm run build    # Build for production
npm run preview  # Preview production build
```

### Dev Tools

In development, a CSS tweaker widget appears (bottom-right corner) for live customisation:
- Switch color presets
- Adjust colors, radius, gutter
- Copy CSS to clipboard

Remove `<DevTools />` from `src/layouts/Layout.astro` before deploying.

---

## 📦 Deployment

### Krystal cPanel (via GitHub Actions + rsync)

> 💡 **Note:** This workflow was tested on Krystal's Unity reseller package in Feb 2026. If anything has changed, check [Krystal's SSH guide](https://krystal.uk/knowledgebase/article/how-do-i-enable-and-disable-ssh-secure-shell-access) or ask their support team — they're responsive.

#### Prerequisites (one-time setup per client)

1. **Create a WHM Package with Shell Access**
   - WHM → Packages → Add a Package
   - Tick **Shell Access** — rsync over SSH won't work without it
   - Set sensible disk/bandwidth limits for the client

2. **Create the cPanel account** using that package
   - Note the **username** — you'll need it for secrets and the rsync path

3. **Generate an SSH key pair** (no passphrase, for CI use):
   ```bash
   ssh-keygen -t rsa -b 4096 -f ~/.ssh/{client}-deploy -N ""
   ```


4. **Add the public key to cPanel**
   - Client's cPanel → SSH Access → Manage SSH Keys → Import Key
   - Paste contents of `~/.ssh/{client}-deploy.pub`
   - **Authorize** the key after importing (easy to forget!)

5. **Add GitHub repo secrets** (Settings → Secrets and variables → Actions):

   | Secret | Value |
   |--------|-------|
   | `SSH_HOST` | Server hostname (e.g. `uranium-lon2.cloudhosting.uk`) — **not** the domain |
   | `SSH_USERNAME` | cPanel username |
   | `SSH_PRIVATE_KEY` | Full contents of private key file, **including** `-----BEGIN/END-----` lines |

#### Workflow File

Create `.github/workflows/deploy.yml`:

```yaml
name: Deploy to cPanel

on:
  push:
    branches:
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: '20'
          cache: 'npm'

      - name: Install dependencies
        run: npm ci

      - name: Build site
        run: npm run build

      - name: Setup SSH key
        run: |
          mkdir -p ~/.ssh
          echo "${{ secrets.SSH_PRIVATE_KEY }}" > ~/.ssh/deploy_key
          chmod 600 ~/.ssh/deploy_key
          ssh-keyscan -H -p 722 ${{ secrets.SSH_HOST }} >> ~/.ssh/known_hosts

      - name: Deploy via rsync
        run: |
          rsync -avzr --delete \
            -e "ssh -i ~/.ssh/deploy_key -p 722" \
            dist/ \
            ${{ secrets.SSH_USERNAME }}@${{ secrets.SSH_HOST }}:/home/CPANEL_USERNAME/public_html/
```

> ⚠️ Replace CPANEL_USERNAME in the rsync path with the actual cPanel username. This must match — it's the server filesystem path, not a variable.

#### After First Deploy

- **SSL:** cPanel → SSL/TLS Status — AutoSSL should issue a cert automatically once DNS is pointing at Krystal. If not, click "Run AutoSSL"
- **Force HTTPS:** cPanel → Domains → toggle "Force HTTPS Redirects" ON (it's off by default 🙄)
- **DNS:** Point nameservers to ns1.cloudhosting.uk / ns2.cloudhosting.uk, or use A records to the shared IP. Make sure both root domain and www resolve — AutoSSL needs both

#### Common Gotchas

| Symptom | Likely Cause | Fix |
|---------|-------------|-----|
| ssh-keyscan hangs/times out | Wrong SSH port | Krystal uses port 722, not 22 |
| protocol version mismatch | No shell access on cPanel account | Enable Jailed Shell in the WHM package |
| SSH key rejected by server | Key missing BEGIN/END headers in GitHub secret | Copy full key with `cat ~/.ssh/key \| pbcopy` |
| AutoSSL fails | DNS not propagated or www not configured | Check both @ and www DNS records resolve |
| Site loads on HTTP, not HTTPS | HTTPS redirect not forced | cPanel → Domains → Force HTTPS Redirects ON |


---

## 🌱 Sustainability

This template is optimised for low environmental impact:

- **Zero JS by default** — Only adds JS when explicitly needed
- **KB ratings** — Each slice rated for JavaScript payload
- **Carbon tiers** — Guide for building A-rated sites on websitecarbon.com
- **Static output** — No server runtime, CDN-friendly

### Building for A* Rating

Use only Tier A* slices:
```
HeroBasic → Features → Stats → Quote → CTA
```

---

## 📋 Project Finishing Checklist

- [ ] Update `repositoryName` in `src/lib/prismic.ts`
- [ ] Update `routes` array to match your Prismic document types (see section 3)
- [ ] Customise colors/fonts in `src/styles/global.css`
- [ ] **Update default meta description** in `src/layouts/Layout.astro` (line ~17)
- [ ] **Change fonts** if needed — install via Fontsource, update Layout.astro imports
- [ ] Update Header/Footer with real content
- [ ] Remove DevTools from Layout.astro
- [ ] Remove `_starters/` folder if not needed
- [ ] Update favicon in `public/`
- [ ] **Review CONTENT_GUIDE.md** — update for client-specific needs
- [ ] Test all pages and slices
- [ ] Run Lighthouse audit

---

## Licence

MIT — use this however you like.
