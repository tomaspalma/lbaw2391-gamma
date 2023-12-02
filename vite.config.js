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
                "resources/js/search/infinite_scroll.js",
                "resources/js/search/feed/scroll.js",
                "resources/js/components/confirmation_modal.js",
                "resources/js/admin/user/delete.js",
                "resources/js/admin/user/unblock.js",
                "resources/js/admin/user/block.js",
                "resources/js/auth/seePassword.js",
                "resources/js/auth/register.js",
                "resources/js/post/delete.js",
                "resources/js/edit_profile/edit_profile.js",
                "resources/js/components/navbar_mobile_menu.js",
                "resources/js/components/dropdown_dots.js",
                "resources/js/profile/delete.js",
                "resources/js/comment/add.js",
                "resources/js/comment/delete.js",
                "resources/js/post/reactions.js",
                "resources/js/utils.js",
                "resources/js/notifications/filter.js",
                "resources/js/admin/user/show_appeal_reason.js",
                "resources/js/components/dropdown.js"
            ],
            refresh: true,
        }),
    ],
    server: {
        hmr: {
            host: "localhost",
        },
    },
});


