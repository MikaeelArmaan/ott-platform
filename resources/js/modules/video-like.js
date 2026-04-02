document.getElementById("likeBtn")?.addEventListener("click", function () {

    const btn = this;
    const videoId = btn.dataset.id;

    const icon = document.getElementById("likeIcon");
    const text = document.getElementById("likeText");
    const count = document.getElementById("likeCount");

    axios.post(`/video-assets/${videoId}/like`)
        .then(res => {

            const liked = res.data.liked;
            const total = res.data.count;

            // ✅ update UI
            icon.innerText = liked ? "❤️" : "👍";
            text.innerText = liked ? "Liked" : "Like";
            count.innerText = total;

        })
        .catch(() => {
            alert("Like failed");
        });
});