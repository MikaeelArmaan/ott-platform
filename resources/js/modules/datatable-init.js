$(document).ready(function () {
    $(".datatable").each(function () {
        let tableId = $(this).attr("id");

        let pageLength = $(this).data("page-length") || 10;
        let orderColumn = $(this).data("order-column") || 0;
        let orderDir = $(this).data("order-dir") || "desc";

        if ($.fn.DataTable.isDataTable("#" + tableId)) {
            $("#" + tableId)
                .DataTable()
                .destroy();
        }

        const table = $("#" + tableId).DataTable({
            processing: true,
            pageLength: pageLength,
            order: [[orderColumn, orderDir]],

            responsive: {
                details: {
                    type: "column",
                    target: 0,
                },
            },

            autoWidth: false,
            lengthChange: true,
            scrollX: true,

            columnDefs: [{ orderable: false, targets: [-1, -2] }],
        });

        /*
        COLUMN SEARCH
        */

        if ($("#" + tableId + " tfoot").length) {
            $("#" + tableId + " tfoot th").each(function () {
                let title = $(this).text();

                if (title !== "") {
                    $(this).html(
                        '<input type="text" placeholder="Search ' +
                            title +
                            '" class="datatable-filter w-full px-2 py-1 bg-zinc-800 border border-zinc-700 rounded text-xs"/>',
                    );
                }
            });

            table.columns().every(function () {
                let column = this;

                $("input", this.footer()).on("keyup change", function () {
                    column.search(this.value).draw();
                });
            });
        }
    });
});
/*
DELETE CONFIRMATION
*/
$(document).on("click", ".delete-btn", function (e) {
    e.preventDefault();
    e.stopPropagation(); // IMPORTANT

    console.log("delete clicked");

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
