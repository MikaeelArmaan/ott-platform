import { http } from "../utils/http";
async function refreshWatchlist() {
    const wrapper = document.getElementById("watchlist-wrapper");
    if (!wrapper) return;

    wrapper.style.opacity = 0.4;

    try {
        const res = await fetch("/watchlist/partial", {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        });

        if (!res.ok) throw new Error("Failed to load watchlist");

        const html = await res.text();

        wrapper.innerHTML = html;
    } catch (err) {
        console.error("Watchlist refresh failed", err);
    } finally {
        wrapper.style.opacity = 1;
    }
}

document.addEventListener("click", async function (e) {
    const btn = e.target.closest(".watchlist-btn");
    if (!btn) return;

    e.preventDefault();

    const form = btn.closest(".watchlist-form");
    const contentId = form.dataset.contentId;

    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    if (!csrf) {
        console.error("CSRF token missing");
        return;
    }

    btn.disabled = true;
    btn.innerHTML = "...";

    try {
        const res = await http("/watchlist/toggle", {
            method: "POST",
            credentials: "same-origin", // very important
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrf,
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify({
                content_id: contentId,
            }),
        });

        if (!res.ok) {
            throw new Error("Server error");
        }

        const data = await res.json();

        if (data.status === "added") {
            btn.innerHTML = "✓";
            toast("Added to Watchlist", "success");
        } else {
            btn.innerHTML = "+";
            toast("Removed from Watchlist", "info");
        }

        // refresh row
        refreshWatchlist();
    } catch (e) {
        console.error(e);
        toast("Something went wrong", "error");
    }

    btn.disabled = false;
});
