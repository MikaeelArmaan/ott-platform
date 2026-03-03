$(document).ready(function () {
    $('.datatable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true
    });

    $('#contentsTable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        lengthChange: true,
        responsive: true
    });
});
