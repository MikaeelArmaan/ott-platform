import $ from "jquery";
import "datatables.net-dt";

window.$ = window.jQuery = $;

$(document).ready(function () {
    $(".datatable").each(function () {
        let tableId = $(this).attr("id");
        if (!tableId) return;

        let selector = "#" + tableId;

        let pageLength = $(this).data("page-length") || 10;
        let orderColumn = $(this).data("order-column") ||0;
        let orderDir = $(this).data("order-dir") || "asc";
        
        const table = $(selector).DataTable({
            processing: true,
            pageLength: pageLength,
            order: [[orderColumn, orderDir]],
            language: {
                search: "", // remove "Search:" label
                searchPlaceholder: "Enter search...",
            },
            dom: '<"dt-top flex justify-between items-center mb-4"lf>rtip',
            responsive: true,
            autoWidth: false,
            lengthChange: true,
            columnDefs: [{ orderable: false, targets: [-1] }],
        });
    });
});
/*
DELETE CONFIRMATION
*/
$(document).on("click", ".delete-btn", function (e) {
    e.preventDefault();
    e.stopPropagation(); // IMPORTANT
    const form = $(this).closest("form");

    Swal.fire({
        title: "Delete this item?",
        text: "This action cannot be undone.",
        icon: "error",
        showCancelButton: true,
        confirmButtonColor: "#dc2626",
        confirmButtonText: "Delete",

        customClass: {
            popup: "swal-dark-popup modal-border-error",
            title: "swal-dark-title",
        },
    }).then((result) => {
        if (!result.isConfirmed) return;

        $.ajax({
            url: form.attr("action"),
            method: "POST",
            data: form.serialize(),

            success: function (res) {
                toast(res.message || "Deleted successfully");

                form.closest("tr").fadeOut(300, function () {
                    $(this).remove();
                });
            },

            error: function () {
                toast("Delete failed", "error");
            },
        });
    });
});
