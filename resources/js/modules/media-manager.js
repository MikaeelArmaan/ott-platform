window.mediaManager = function (folder, initial = null, multiple = false) {
    return {
        open: false,
        tab: "library",
        files: [],
        selected: multiple ? [] : null,
        multiple: multiple,

        init() {
            if (!initial) return;

            if (this.multiple) {
                const paths = Array.isArray(initial) ? initial : [initial];

                this.selected = paths.map((path) => ({
                    path: path,
                    url: this.resolveUrl(path),
                    type: this.detectType(path),
                    name: path.split("/").pop(),
                }));
            } else {
                this.selected = {
                    path: initial,
                    url: this.resolveUrl(initial),
                    type: this.detectType(initial),
                };
            }
        },

        resolveUrl(path) {
            if (!path) return "";
            if (path.startsWith("http")) return path;
            return "/storage/" + path;
        },

        tabClass(tab) {
            return this.tab === tab
                ? "text-red-500 border-b-2 border-red-500 pb-1"
                : "text-gray-400";
        },

        // 🔥 THIS WAS YOUR MISSING FUNCTION
        loadFiles() {
            fetch(`/admin/media?folder=${folder}`)
                .then((res) => res.json())
                .then((data) => {
                    this.files = data.files || [];
                })
                .catch((err) => console.error("Load error:", err));
        },

        uploadFiles(fileList) {
            [...fileList].forEach((file) => this.upload(file));
        },

        upload(file) {
            let formData = new FormData();
            formData.append("file", file);
            formData.append("folder", folder);

            fetch("/admin/media/upload", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
                body: formData,
            })
                .then((res) => {
                    if (!res.ok) throw new Error("Upload failed");
                    return res.json();
                })
                .then((data) => {
                    if (!data.success) return;

                    const fileObj = {
                        path: data.path,
                        url: this.resolveUrl(data.path),
                        type: this.detectType(data.path),
                        name: data.name || data.path.split("/").pop(),
                    };

                    if (this.multiple) {
                        this.selected.push(fileObj);
                    } else {
                        this.selected = fileObj;
                    }

                    this.files.unshift(fileObj);
                })
                .catch((err) => {
                    console.error("Upload error:", err);
                });
        },

        select(file) {
            if (this.multiple) {
                const exists = this.selected.find((f) => f.path === file.path);

                if (!exists) {
                    this.selected.push(file);
                }
            } else {
                this.selected = file;
                this.open = false;
            }
        },

        remove(file) {
            if (!this.multiple) {
                this.selected = null;
                return;
            }

            this.selected = this.selected.filter((f) => f.path !== file.path);
        },

        detectType(path) {
            if (!path) return "file";
            if (path.match(/\.(jpg|jpeg|png|webp|gif)$/i)) return "image";
            if (path.match(/\.(mp4|webm)$/i)) return "video";
            return "file";
        },
    };
};
