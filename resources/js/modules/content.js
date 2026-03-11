$(document).on("change", ".publish-toggle", function () {
    let contentId = $(this).data("id");
    let status = $(this).is(":checked");

    $.ajax({
        url: "/admin/contents/" + contentId + "/toggle-publish",
        method: "POST",

        data: {
            status: status,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
    });
});
