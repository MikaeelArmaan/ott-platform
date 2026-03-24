document.querySelectorAll(".content-scroll").forEach((slider) => {
    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener("mousedown", (e) => {
        isDown = true;
        slider.classList.add("cursor-grabbing");
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });

    slider.addEventListener("mouseleave", () => {
        isDown = false;
    });

    slider.addEventListener("mouseup", () => {
        isDown = false;
    });

    slider.addEventListener("mousemove", (e) => {
        if (!isDown) return;

        e.preventDefault();

        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 1.5;

        slider.scrollLeft = scrollLeft - walk;
    });
});
