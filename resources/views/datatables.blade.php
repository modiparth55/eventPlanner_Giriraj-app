@push('styles')
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.13.1/af-2.5.1/b-2.3.3/b-colvis-2.3.3/b-html5-2.3.3/b-print-2.3.3/cr-1.6.1/fc-4.2.1/fh-3.3.1/r-2.4.0/sc-2.0.7/sb-1.4.0/sp-2.1.0/sl-1.5.0/datatables.min.css" />
@endpush
@push('scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.13.1/af-2.5.1/b-2.3.3/b-colvis-2.3.3/b-html5-2.3.3/b-print-2.3.3/cr-1.6.1/fc-4.2.1/fh-3.3.1/r-2.4.0/sc-2.0.7/sb-1.4.0/sp-2.1.0/sl-1.5.0/datatables.min.js">
    </script>
    <script type="text/javascript">
        $(function() {
            $.extend(true, $.fn.dataTable.defaults, {
                // responsive: true,
                // autoWidth: true,
                // stateSave: true,
                // deferRender: true,
                // dom: 'l<"float-right"fB>rtip',
                language: {
                    "sLengthMenu": "_MENU_",
                    "sSearch": "",
                    "sSearchPlaceholder": "Search",
                    "infoEmpty": "Showing 0 entries",
                    "sEmptyTable": "No Data Available To View.",
                },
            });

            // $.fn.DataTable.ext.pager.numbers_length = 5;
            // var dt_buttons = $.extend(true, {}, $.fn.dataTable.defaults).buttons;
        });
    </script>
@endpush
