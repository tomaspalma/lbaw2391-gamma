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
        "resources/js/search/admin_user_search.js",
        "resources/js/components/confirmation_modal.js",
        "resources/js/admin/user/delete.js",
        "resources/js/admin/user/unblock.js",
        "resources/js/edit_profile.js",
      ],
      refresh: true,
    }),
  ],
});
