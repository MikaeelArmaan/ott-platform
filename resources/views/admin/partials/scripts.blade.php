<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const toggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        toggle?.addEventListener('click', () => {
            sidebar?.classList.toggle('active');
            overlay?.classList.toggle('hidden');
        });

        overlay?.addEventListener('click', () => {
            sidebar?.classList.remove('active');
            overlay?.classList.add('hidden');
        });

    });

    document.addEventListener("DOMContentLoaded", function() {

        document.querySelectorAll(".image-upload").forEach(upload => {

            const inputId = upload.dataset.input;
            const previewId = upload.dataset.preview;

            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            upload.addEventListener("click", () => input.click());

            input.addEventListener("change", function() {

                if (this.files && this.files[0]) {

                    const reader = new FileReader();

                    reader.onload = e => {
                        preview.src = e.target.result;
                        preview.classList.remove("hidden");
                    };

                    reader.readAsDataURL(this.files[0]);

                }

            });

        });

    });

    document.addEventListener("DOMContentLoaded", function() {

        document.querySelectorAll(".video-upload").forEach(wrapper => {

            const inputId = wrapper.dataset.input
            const input = document.getElementById(inputId)

            const dropZone = wrapper.querySelector(".drop-zone")
            const preview = document.getElementById(inputId + "Preview")
            const filename = document.getElementById(inputId + "Filename")
            const progress = document.getElementById(inputId + "Progress")
            const progressWrap = wrapper.querySelector(".progress-wrap")

            // click
            dropZone.addEventListener("click", () => input.click())

            // drag
            dropZone.addEventListener("dragover", e => {
                e.preventDefault()
                dropZone.classList.add("border-red-500")
            })

            dropZone.addEventListener("dragleave", () => {
                dropZone.classList.remove("border-red-500")
            })

            dropZone.addEventListener("drop", e => {

                e.preventDefault()

                input.files = e.dataTransfer.files
                input.dispatchEvent(new Event("change"))

            })

            // change
            input.addEventListener("change", function() {

                const file = this.files[0]

                if (!file) return

                // max 500MB
                if (file.size > 524288000) {
                    alert("Video too large (max 500MB)")
                    this.value = ""
                    return
                }

                filename.innerText = file.name

                // preview
                preview.src = URL.createObjectURL(file)
                preview.classList.remove("hidden")

                // progress animation
                progressWrap.classList.remove("hidden")

                let p = 0
                const timer = setInterval(() => {

                    p += 10
                    progress.style.width = p + "%"

                    if (p >= 100) clearInterval(timer)

                }, 100)

            })

        })

    })
</script>