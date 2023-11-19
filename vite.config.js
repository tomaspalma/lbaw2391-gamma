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
                "resources/js/admin/user/block.js",
                "resources/js/profile_edit/validate_password.js",
                "resources/js/auth/login.js",
                "resources/js/auth/register.js",
                "resources/js/post/delete.js",
                "resources/js/components/navbar_mobile_menu.js"
            ],
            refresh: true,
        }),
    ],
    server: {
        hmr: {
            host: 'localhost',
        },
    }
});
