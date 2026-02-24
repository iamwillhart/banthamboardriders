// @ts-check
import { defineConfig } from 'astro/config';
import markdoc from '@astrojs/markdoc';

import cloudflare from '@astrojs/cloudflare';

// https://astro.build/config
export default defineConfig({
  integrations: [markdoc()],

  // Pure static - maximum sustainability
  output: 'static',

  adapter: cloudflare()
});