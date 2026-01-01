/* let jquery_datatable = $("#table1").DataTable({
    responsive: true
})
let customized_datatable = $("#table2").DataTable({
    responsive: true,
    pagingType: 'simple',
    dom:
		"<'row'<'col-3'l><'col-9'f>>" +
		"<'row dt-row'<'col-sm-12'tr>>" +
		"<'row'<'col-4'i><'col-8'p>>",
    "language": {
        "info": "Page _PAGE_ of _PAGES_",
        "lengthMenu": "_MENU_ ",
        "search": "",
        "searchPlaceholder": "Search.."
    }
})

const setTableColor = () => {
    document.querySelectorAll('.dataTables_paginate .pagination').forEach(dt => {
        dt.classList.add('pagination-primary')
    })
}
setTableColor()
jquery_datatable.on('draw', setTableColor) */

let jquery_datatable = $("#table1").DataTable({
    responsive: true,
    dom:
        "<'row mb-2'<'col-sm-6'l><'col-sm-6 text-end'f>>" +   // length kiri, search kanan
        "<'row dt-row'<'col-sm-12'tr>>" +                     // table
        "<'row mt-2'<'col-sm-6'i><'col-sm-6 text-end'p>>",    // info kiri, pagination kanan
    language: {
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        lengthMenu: "_MENU_ ",
        search: "",
        searchPlaceholder: "Cari..."
    }
});

const setTableColor = () => {
    document.querySelectorAll('.dataTables_paginate .pagination').forEach(dt => {
        dt.classList.add('pagination-primary');
        dt.classList.add('justify-content-end'); // biar pagination nempel kanan
    });
};
setTableColor();
jquery_datatable.on('draw', setTableColor);
