// ================= SHARE =================
const shareBtn = document.getElementById("shareBtn");
const modal = document.getElementById("shareModal");

shareBtn?.addEventListener("click", async () => {

    const url = window.location.href;
    const title = document.title;

    // ✅ Native Share (Mobile / supported browsers)
    if (navigator.share) {
        try {
            await navigator.share({
                title: title,
                text: "Check this out",
                url: url,
            });
            return;
        } catch (e) {
            console.log("Share cancelled");
        }
    }

    // ✅ Fallback Modal
    modal.classList.remove("hidden");

    document.getElementById("whatsappShare").href =
        `https://wa.me/?text=${encodeURIComponent(url)}`;

    document.getElementById("twitterShare").href =
        `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}`;

    // ✅ SAFE COPY (with fallback)
    document.getElementById("copyLink").onclick = () => {

        // Modern API
        if (navigator.clipboard && navigator.clipboard.writeText) {

            navigator.clipboard.writeText(url)
                .then(() => {
                    showToast("Link copied!");
                })
                .catch(() => {
                    fallbackCopy(url);
                });

        } else {
            fallbackCopy(url);
        }
    };
});


// ================= COPY FALLBACK =================
function fallbackCopy(text) {

    const textarea = document.createElement("textarea");
    textarea.value = text;

    document.body.appendChild(textarea);
    textarea.select();

    document.execCommand("copy");

    document.body.removeChild(textarea);

    showToast("Link copied!");
}


// ================= TOAST =================
function showToast(message) {

    // If SweetAlert exists → use it
    if (window.Swal) {
        toast(message,'success',1200);
    } else {
        toast(message,'error',3000);
    }
}