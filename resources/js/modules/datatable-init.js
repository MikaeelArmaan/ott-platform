$(document).ready(function () {
    $('.datatable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        order: [[0, "asc"]],
        responsive: true
    });

    $('#contentsTable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        lengthChange: true,
        responsive: true,
        order: [[0, "asc"]],
    });
});
