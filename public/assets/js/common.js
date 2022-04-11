$(document).ready(function () {
    $('select').select2({
        theme: 'bootstrap4'
    })

    if ($('.textarea').length) {
    //     $(".textarea").summernote({
    //         toolbar: [
    //             // [groupName, [list of button]]
    //             ['style', ['bold', 'italic', 'underline']],
    //             ['para', ['ul', 'ol', 'paragraph']],
    //             ['insert', ['link']],
    //             ['view', ['fullscreen']],
    //             // ['view', ['fullscreen', 'codeview']],
    //         ]
    //     });
        //CKEDITOR.replace('.textarea', {"toolbar":[["Source","Cut","Copy","Paste","PasteText","PasteFromWord","-","Undo","Redo","-","Bold","Italic","Underline","Strike","CopyFormatting","RemoveFormat","-","NumberedList","BulletedList","-","JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock","Maximize"]],"allowedContent":true,"language":"en"});
    }

    // Data Table
    if ($('#dataTable').length > 0) {
        var data = $('#dataTable');
        data.DataTable({
            "paging": true,
            "lengthChange": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "oLanguage": {
                "sSearch": "Search:",
                "sInfo": "Total records(s): _TOTAL_"

            },
            "language": {
                "paginate": {
                    "previous": "&laquo;&nbsp;Previous",
                    "next": "Next&nbsp;&raquo;"
                }
            },
            initComplete: (settings, json) => {
                $('.dataTables_paginate').appendTo($('.dompage'));
                $('.dataTables_info').appendTo($('.domrecord'));
            },
        });
    }
});