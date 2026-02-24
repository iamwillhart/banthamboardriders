/**
 * Slice Components Index
 * Export all slice components for easy importing
 *
 * NOTE: Heavy slices (C-tier, GSAP-dependent) are in the heavy/ subfolder.
 * For low-carbon projects, delete the heavy/ folder and remove those exports.
 */

// Heroes
export { default as HeroHomepage } from './HeroHomepage.astro';
export { default as HeroSecondary } from './HeroSecondary.astro';

// Content — Text
export { default as TextStatement } from './TextStatement.astro';
export { default as TextProse } from './TextProse.astro';
export { default as ImageBlock } from './ImageBlock.astro';
export { default as TickerTape } from './TickerTape.astro';

// Content — Layout
export { default as TwoColumn } from './TwoColumn.astro';
export { default as Features } from './Features.astro';
export { default as Stats } from './Stats.astro';
export { default as Quote } from './Quote.astro';
export { default as Accordion } from './Accordion.astro';
export { default as CTA } from './CTA.astro';
export { default as FeaturedItems } from './FeaturedItems.astro';

// Media
export { default as TeamGallery } from './TeamGallery.astro';
export { default as ImageGallery } from './ImageGallery.astro';
export { default as ImageGalleryMasonry } from './ImageGalleryMasonry.astro';
export { default as ImageGalleryEditorial } from './ImageGalleryEditorial.astro';
export { default as VideoEmbed } from './VideoEmbed.astro';

// Collections
export { default as LogoCloud } from './LogoCloud.astro';

// Forms
export { default as ContactForm } from './ContactForm.astro';
export { default as NewsletterSignup } from './NewsletterSignup.astro';

// Utility
export { default as Spacer } from './Spacer.astro';
export { default as Divider } from './Divider.astro';

// ============================================================
// HEAVY SLICES — C-tier, GSAP-dependent (~45KB JS each)
// Delete this section + heavy/ folder for low-carbon projects
// ============================================================
export { default as ScrollAccordion } from './heavy/ScrollAccordion.astro';
export { default as HorizontalGallery } from './heavy/HorizontalGallery.astro';
