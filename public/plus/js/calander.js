(function($) {
    $('#openTime').datetimepicker({
        format: 'HH:mm',
    });
    $('#closeTime').datetimepicker({
        format: 'HH:mm',
    });
    $('.DateTime').datetimepicker({
        format: 'YYYY-MM-DD',
        locale: 'en',
    });
    $('#endDate').datetimepicker({
        format: 'YYYY-MM-DD',
        locale: 'en',
    });
    $('.select2').select2();
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });
})(jQuery);