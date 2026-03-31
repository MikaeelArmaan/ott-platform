import $ from "jquery";
window.$ = window.jQuery = $;

import axios from "axios";
window.axios = axios;

// ✅ Required for Laravel AJAX
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// ✅ ADD THIS (CRITICAL)
const token = document.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] =
        token.getAttribute("content");
} else {
    console.error("CSRF token not found");
}
