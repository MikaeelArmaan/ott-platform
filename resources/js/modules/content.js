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
        type: type,
        step: 1,

        /*
        |--------------------------------------------------------------------------
        | INITIALIZE DATA
        |--------------------------------------------------------------------------
        */
        seasons: (initialSeasons || []).map((s) => ({
            ...s,
            episodes: (s.episodes || []).map((ep) => ({
                ...ep,
                open: false,
                preview: false,
                thumbnail_preview: null,
                video_preview: null,
            })),
        })),

        /*
        |--------------------------------------------------------------------------
        | STEPS
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | SEASONS
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | EPISODES
        |--------------------------------------------------------------------------
        */
        addEpisode(seasonIndex) {
            this.seasons[seasonIndex].episodes.push({
                id: null,
                title: "",
                description: "",
                runtime: "",
                episode_number: this.seasons[seasonIndex].episodes.length + 1,
                open: true,
                preview: false,
            });
        },
        previewImage(event, ep) {
            const file = event.target.files[0];
            if (!file) return;

            ep.thumbnail_preview = URL.createObjectURL(file);
        },

        previewVideo(event, ep) {
            const file = event.target.files[0];
            if (!file) return;

            ep.video_preview = URL.createObjectURL(file);
            ep.preview = true; // auto open preview
        },

        removeEpisode(seasonIndex, episodeIndex) {
            this.seasons[seasonIndex].episodes.splice(episodeIndex, 1);
            this.reindexEpisodes(seasonIndex);
        },

        /*
        |--------------------------------------------------------------------------
        | REINDEX (IMPORTANT)
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | SORTABLE - SEASONS
        |--------------------------------------------------------------------------
        */
        initSeasonSortable(el) {
            new Sortable(el, {
                animation: 200,
                handle: ".drag-handle",

                onEnd: (evt) => {
                    let moved = this.seasons.splice(evt.oldIndex, 1)[0];
                    this.seasons.splice(evt.newIndex, 0, moved);

                    this.reindexSeasons();
                },
            });
        },

        /*
        |--------------------------------------------------------------------------
        | SORTABLE - EPISODES
        |--------------------------------------------------------------------------
        */
        initEpisodeSortable(el, seasonIndex) {
            new Sortable(el, {
                animation: 200,
                handle: ".drag-handle",

                onEnd: (evt) => {
                    let list = this.seasons[seasonIndex].episodes;

                    let moved = list.splice(evt.oldIndex, 1)[0];
                    list.splice(evt.newIndex, 0, moved);

                    this.reindexEpisodes(seasonIndex);
                },
            });
        },

        cleanSeasons() {
            return JSON.stringify(
                this.seasons.map((season) => ({
                    id: season.id,
                    name: season.name,
                    season_number: season.season_number,

                    episodes: season.episodes.map((ep) => ({
                        id: ep.id,
                        title: ep.title,
                        description: ep.description,
                        runtime: ep.runtime,
                        episode_number: ep.episode_number,
                        // ❌ DO NOT SEND preview / blob fields
                    })),
                })),
            );
        },
    };
};
