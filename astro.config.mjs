// @ts-check
import { defineConfig } from 'astro/config';
import markdoc from '@astrojs/markdoc';

// https://astro.build/config
export default defineConfig({
  integrations: [markdoc()],
  output: 'static', // Pure static - maximum sustainability
});
