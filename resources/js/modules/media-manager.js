window.mediaManager = function (dataset) {
    const folder = dataset.folder;

    // ✅ FIX: handle boolean correctly
    const multiple = dataset.multiple === true || dataset.multiple === "true";

    // ✅ FIX: safe value parsing
    const initial = dataset.value;

    return {
        open: false,
        tab: "library",
        files: [],
        selected: multiple ? [] : null,
        multiple,

        uploading: false,
        progress: 0,
        loading: false,
        queue: [],

        // ✅ ALWAYS USE THIS
        target: dataset.target || null,

        /*
        |---------------------------------
        | INIT
        |---------------------------------
        */
        init() {
            this.target = this.$el.dataset.target;

            this.open = false;

            // 🔥 HARD RESET AFTER RENDER
            setTimeout(() => {
                this.open = false;
            }, 0);
            if (!initial) return;

            if (this.multiple) {
                const paths = Array.isArray(initial) ? initial : [initial];

                this.selected = paths.map((path) => ({
                    path: this.cleanPath(path),
                    url: this.resolveUrl(path),
                    type: this.detectType(path),
                    name: String(path).split("/").pop(),
                }));
            } else {
                this.selected = {
                    path: this.cleanPath(initial),
                    url: this.resolveUrl(initial),
                    type: this.detectType(initial),
                    name: String(initial).split("/").pop(),
                };
            }
        },

        cleanPath(path) {
            if (!path) return null;

            return path
                .replace(/\\/g, "/") // ✅ ADD THIS
                .replace(/^https?:\/\/[^/]+\/storage\//, "")
                .replace(/^\/storage\//, "")
                .replace(/^\/+/, "");
        },

        resolveUrl(path) {
            if (!path) return "";

            if (path.startsWith("http")) return path;
            if (path.startsWith("/storage/")) return path;

            return "/storage/" + path;
        },

        tabClass(tab) {
            return this.tab === tab
                ? "text-red-500 border-b-2 border-red-500 pb-1"
                : "text-gray-400";
        },

        loadFiles() {
            this.loading = true;

            fetch(`/admin/media?folder=${encodeURIComponent(folder)}`)
                .then((res) => res.json())
                .then((data) => {
                    this.files = data.files || [];
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        uploadFiles(fileList) {
            this.queue = [...fileList];
            this.processQueue();
        },

        async processQueue() {
            if (!this.queue.length) return;
            const file = this.queue.shift();
            await this.upload(file);
            this.processQueue();
        },

        async upload(file) {
            const formData = new FormData();
            formData.append("file", file);
            formData.append("folder", folder);

            this.uploading = true;
            this.progress = 0;

            try {
                const res = await axios.post("/admin/media/upload", formData, {
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]',
                        ).content,
                    },
                    onUploadProgress: (e) => {
                        if (!e.total) return;
                        this.progress = Math.round((e.loaded * 100) / e.total);
                    },
                });

                const fileObj = res.data.file;
                if (!fileObj) return;

                fileObj.path = this.cleanPath(fileObj.path);

                this.emit(fileObj);
                this.files.unshift(fileObj);
            } catch (error) {
                console.error("Media upload failed:", error);
            } finally {
                this.uploading = false;
            }
        },

        select(file) {
            file.path = this.cleanPath(file.path);
            this.emit(file);
        },

        /*
        |---------------------------------
        | 🔥 CORE FIXED EVENT SYSTEM
        |---------------------------------
        */
        emit(file) {
            if (this.multiple) {
                const exists = this.selected.find((f) => f.path === file.path);
                if (!exists) this.selected.push(file);
            } else {
                this.selected = file;
                this.open = false;
            }

            // ✅ ALWAYS USE this.target
            window.dispatchEvent(
                new CustomEvent("media-selected", {
                    detail: {
                        path: file.path,
                        url: file.url,
                        type: file.type,
                        target: this.target,
                    },
                }),
            );
        },

        remove(file = null) {
            if (!this.multiple) {
                this.selected = null;

                window.dispatchEvent(
                    new CustomEvent("media-selected", {
                        detail: {
                            path: null,
                            url: null,
                            type: null,
                            target: this.target,
                        },
                    }),
                );

                return;
            }

            this.selected = this.selected.filter((f) => f.path !== file.path);
        },

        detectType(path) {
            if (!path) return "file";
            if (path.match(/\.(jpg|jpeg|png|webp|gif)$/i)) return "image";
            if (path.match(/\.(mp4|webm|mkv|mov)$/i)) return "video";
            return "file";
        },
    };
};
