window.previewImage = function (event, targetId) {
    const input = event.target;
    const preview = document.getElementById(targetId);

    if (!input.files.length) return;

    const reader = new FileReader();

    reader.onload = function (e) {
        preview.src = e.target.result;
        preview.classList.remove("hidden");
    };

    reader.readAsDataURL(input.files[0]);
};

document.addEventListener("click", function (e) {
    const box = e.target.closest(".image-upload");
    if (!box) return;

    const inputId = box.dataset.input;
    document.getElementById(inputId).click();
});
