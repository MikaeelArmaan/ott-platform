import $ from "jquery";
import select2 from "select2";

// 🔥 attach plugin manually (THIS IS THE KEY)
select2($);

function initSelect2() {
    if (!$.fn.select2) {
        console.error("❌ Select2 not attached");
        return;
    }

    $(".select2").each(function () {
        if ($(this).hasClass("select2-hidden-accessible")) return;

        $(this).select2({
            width: "100%",
            placeholder: $(this).data("placeholder") || "Select",
            allowClear: true,
            minimumResultsForSearch: Infinity // always show search
        });
    });
}

window.addEventListener("load", initSelect2);

window.initSelect2 = initSelect2;
document.addEventListener("reinit-select2", initSelect2);
