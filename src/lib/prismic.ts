/**
 * Prismic Client
 *
 * Connects to your Prismic repository for content fetching.
 * All fetches happen at build time (SSG) — zero runtime requests.
 *
 * SETUP:
 * 1. Create a Prismic repository at prismic.io
 * 2. Replace 'your-repo-name' below with your repo name
 * 3. Add your document types to the routes array below
 * 4. Create content and build!
 */
import * as prismic from "@prismicio/client";

// ============================================
// 🔧 CHANGE THIS to your Prismic repository name
// ============================================
const repositoryName = "bantham-boardriders";

// ============================================
// 🔧 ROUTES — define URL patterns for each document type
// ============================================
//
// ⚠️  IMPORTANT: Only include types that EXIST in your Prismic repo!
//     Prismic validates this server-side. If you declare a type that
//     doesn't exist, ALL fetches will fail with a cryptic error.
//
// ✅ When adding a new document type:
//    1. Create the custom type in Prismic FIRST
//    2. Then add the route here
//    3. Create the page file in src/pages/
//
// Syntax:
//   { type: 'doc_type', path: '/url-pattern' }
//   { type: 'doc_type', path: '/:uid' }           ← :uid is replaced with document UID
//   { type: 'doc_type', path: '/prefix/:uid' }
// ============================================
const routes: Array<{ type: string; path: string }> = [
  // Singletons (no UID)
  { type: "homepage", path: "/" },

  // Repeatable types (use :uid)
  // Only add routes for types that exist in YOUR Prismic repo!
  // Prismic validates these — non-existent types will cause errors.
  { type: "page", path: "/:uid" },
  { type: "event", path: "/events/:uid" },
];

/**
 * Prismic client instance
 * Used to fetch content from your Prismic repository
 */
export const client = prismic.createClient(repositoryName, {
  routes,
  // Add access token here if your repo is private:
  // accessToken: import.meta.env.PRISMIC_ACCESS_TOKEN,
});

/**
 * Link resolver (fallback)
 *
 * With Route Resolver configured above, you rarely need this.
 * It's kept as a fallback for edge cases where the route resolver
 * doesn't have the URL pre-computed (e.g., dynamically constructed links).
 */
export const linkResolver = (doc: { type?: string; uid?: string }): string => {
  const route = routes.find((r) => r.type === doc.type);
  if (!route) return "/";

  // Replace :uid placeholder with actual UID
  if (route.path.includes(":uid") && doc.uid) {
    return route.path.replace(":uid", doc.uid);
  }
  return route.path;
};

/**
 * Fetch global settings
 *
 * Returns the settings document or undefined if not found.
 * This prevents build errors before the user creates the document.
 */
export const getSettings = async () => {
  try {
    return await client.getSingle("settings");
  } catch (error) {
    if (import.meta.env.DEV) {
      console.warn(
        'Settings document not found. Create a "settings" document in Prismic.'
      );
    }
    return null;
  }
};
