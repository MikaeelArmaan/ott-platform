let loading = false;

const trigger = document.getElementById("infinite-scroll-trigger");
const skeleton = document.getElementById("skeleton-container");
const grid = document.getElementById("browse-grid");

if (trigger) {
    const observer = new IntersectionObserver(
        async (entries) => {
            if (!entries[0].isIntersecting) return;
            if (loading) return;

            loading = true;

            if (skeleton) skeleton.classList.remove("hidden");

            const page = trigger.dataset.nextPage;

            try {
                const url = new URL(window.location.href);
                url.searchParams.set("page", page);

                const res = await fetch(url, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });

                const html = await res.text();

                if (!html.trim()) {
                    if (skeleton) skeleton.classList.add("hidden");

                    trigger.innerHTML = `
<div class="text-gray-500 text-sm py-6 text-center">
No more content available
</div>
`;

                    observer.disconnect();
                    loading = false;
                    return;
                }

                grid.insertAdjacentHTML("beforeend", html);

                trigger.dataset.nextPage = parseInt(page) + 1;
            } catch (e) {
                console.error(e);
            }

            if (skeleton) skeleton.classList.add("hidden");

            loading = false;
        },
        { rootMargin: "300px" },
    );

    observer.observe(trigger);
}
