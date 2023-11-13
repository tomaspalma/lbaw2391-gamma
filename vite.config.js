import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
  plugins: [
    laravel({
      input: [
        "resources/css/app.css",
        "resources/js/app.js",
        "resources/js/search/search_input_preview.js",
        "resources/js/search/search.js",
        "resources/js/search/main_search_preview.js",
        "resources/js/profile_edit/validate_password.js",
      ],
      refresh: true,
    }),
  ],
});
