import $ from "jquery";
import select2 from "select2";

select2($);

function initSelect2() {
    if (!$.fn.select2) {
        console.error("❌ Select2 not attached");
        return;
    }

    $(".select2").each(function () {
        if ($(this).hasClass("select2-hidden-accessible")) return;

        let $el = $(this);

        $el.select2({
            width: "100%",
            placeholder: $el.data("placeholder") || "Select",
            allowClear: true,
            minimumResultsForSearch: Infinity,
        });

        // 🔥🔥🔥 CRITICAL PART (YOU WERE MISSING THIS)
        $el.on("change", function () {
            let value = $el.val();

            // Sync with Alpine (x-model)
            if (this._x_model) {
                this._x_model.set(value);
            }

            // fallback (important for nested components)
            this.dispatchEvent(new Event("input", { bubbles: true }));
        });
    });
}

window.addEventListener("load", initSelect2);

window.initSelect2 = initSelect2;
document.addEventListener("reinit-select2", initSelect2);
