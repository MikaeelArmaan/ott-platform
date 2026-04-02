/*
|--------------------------------------------------------------------------
| PUBLISH TOGGLE (unchanged)
|--------------------------------------------------------------------------
*/
$(document).on("change", ".content-toggle", function () {
    let toggle = $(this);
    let contentId = toggle.data("id");
    let field = toggle.data("field");

    let previousState = !toggle.is(":checked"); // for rollback
    let newState = toggle.is(":checked");

    let container = toggle.closest("label");

    // 🔥 disable + show loading
    toggle.prop("disabled", true);
    container.find(".loader").removeClass("hidden");

    $.ajax({
        url: "/admin/contents/" + contentId + "/toggle",
        method: "POST",
        data: {
            field: field,
            value: newState,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },

        success: function (res) {
            // ✅ success toast
            toast(res.message, "success");
            // Swal.fire({
            //     toast: true,
            //     position: "top-end",
            //     icon: "success",
            //     title: res.message,
            //     showConfirmButton: false,
            //     timer: 1800,
            // });
        },

        error: function (xhr) {
            // ❌ rollback UI
            toggle.prop("checked", previousState);
            toast(xhr.responseJSON?.message || "Update failed", "error");
            // Swal.fire({
            //     toast: true,
            //     position: "top-end",
            //     icon: "error",
            //     title: xhr.responseJSON?.message || "Update failed",
            //     showConfirmButton: false,
            //     timer: 2000,
            // });
        },

        complete: function () {
            // 🔥 re-enable + remove loader
            toggle.prop("disabled", false);
            container.find(".loader").addClass("hidden");
        },
    });
});
/*
|--------------------------------------------------------------------------
| CONTENT WIZARD (UPGRADED)
|--------------------------------------------------------------------------
*/
window.contentWizard = function (type = "movie", initialSeasons = []) {
    return {
        type,
        step: 1,

        seasons: (initialSeasons || []).map((s) => ({
            ...s,
            episodes: (s.episodes || []).map((ep) => ({
                ...ep,
                open: false,
                preview: false,
                thumbnail_preview: null,
                value: ep.video
                    ? ep.video.startsWith("http")
                        ? ep.video
                        : `/storage/${ep.video}`
                    : null,
                video_preview: ep.video
                    ? ep.video.startsWith("http")
                        ? ep.video
                        : `/storage/${ep.video}`
                    : null,
                is_processed: ep.is_processed ?? true,
            })),
        })),

        steps() {
            return this.type === "movie"
                ? ["Content", "Media", "Video", "Publish"]
                : ["Content", "Media", "Seasons", "Publish"];
        },

        next() {
            if (this.step < this.steps().length) this.step++;
        },

        prev() {
            if (this.step > 1) this.step--;
        },

        addSeason() {
            this.seasons.push({
                id: null,
                name: "Season " + (this.seasons.length + 1),
                season_number: this.seasons.length + 1,
                episodes: [],
            });
        },

        removeSeason(index) {
            this.seasons.splice(index, 1);
            this.reindexSeasons();
        },

        addEpisode(seasonIndex) {
            this.seasons[seasonIndex].episodes.push({
                id: null,
                title: "",
                description: "",
                runtime: "",
                episode_number: this.seasons[seasonIndex].episodes.length + 1,
                thumbnail: null,
                thumbnail_preview: null,
                video: null,
                video_preview: null,
                is_processed: false,
                open: true,
                preview: false,
            });
        },

        previewImage(event, ep) {
            const file = event.target.files[0];
            if (!file) return;
            ep.thumbnail_preview = URL.createObjectURL(file);
        },

        removeEpisode(seasonIndex, episodeIndex) {
            this.seasons[seasonIndex].episodes.splice(episodeIndex, 1);
            this.reindexEpisodes(seasonIndex);
        },

        reindexSeasons() {
            this.seasons.forEach((season, i) => {
                season.season_number = i + 1;
            });
        },

        reindexEpisodes(seasonIndex) {
            this.seasons[seasonIndex].episodes.forEach((ep, i) => {
                ep.episode_number = i + 1;
            });
        },

        initSeasonSortable(el) {
            new Sortable(el, {
                animation: 200,
                handle: ".drag-handle",
                onEnd: (evt) => {
                    const moved = this.seasons.splice(evt.oldIndex, 1)[0];
                    this.seasons.splice(evt.newIndex, 0, moved);
                    this.reindexSeasons();
                },
            });
        },

        initEpisodeSortable(el, seasonIndex) {
            new Sortable(el, {
                animation: 200,
                handle: ".drag-handle",
                onEnd: (evt) => {
                    const list = this.seasons[seasonIndex].episodes;
                    const moved = list.splice(evt.oldIndex, 1)[0];
                    list.splice(evt.newIndex, 0, moved);
                    this.reindexEpisodes(seasonIndex);
                },
            });
        },

        async forceSync() {
            // 🔥 WAIT FOR ALPINE TO FINISH UPDATES
            await this.$nextTick();

            const cleaned = this.cleanSeasons();

            if (this.$refs.seasonsInput) {
                this.$refs.seasonsInput.value = cleaned;
            }

            return true;
        },
        async submitForm() {
            
            await this.forceSync();
            // 🔥 NOW submit form safely
            this.$el.submit();
        },
        handleMedia(event) {
            const { target, path, url } = event.detail;
            if (!target) return;

            const [type, sIndex, eIndex] = target.split("-");

            const s = parseInt(sIndex);
            const e = parseInt(eIndex);

            if (isNaN(s) || isNaN(e)) return;

            const episode = this.seasons[s]?.episodes[e];
            if (!episode) return;
            
            if (type === "video") {
                episode.video = path;
                episode.video_preview = url;
            }

            if (type === "thumb") {
                episode.thumbnail = path;
                episode.thumbnail_preview = url;
            }
        },
        cleanSeasons() {
            return JSON.stringify(
                this.seasons
                    .map((season, sIndex) => {
                        const episodes = (season.episodes || [])
                            .filter((ep) => {
                                // ✅ keep only valid episodes
                                return ep.title || ep.video;
                            })
                            .map((ep, eIndex) => ({
                                episode_number: eIndex + 1,
                                title: ep.title || null,
                                description: ep.description || null,

                                // ✅ FIXED FIELD
                                duration: ep.runtime || null,

                                release_date: ep.release_date || null,

                                video: ep.video || null,
                                thumbnail: ep.thumbnail || null,
                            }));

                        // ❌ skip empty seasons
                        if (!episodes.length) return null;

                        return {
                            season_number: sIndex + 1,
                            title: season.name || null,
                            episodes,
                        };
                    })
                    .filter(Boolean), // remove null seasons
            );
        },
    };
};
