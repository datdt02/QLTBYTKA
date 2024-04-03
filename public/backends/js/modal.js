(function($) {
    'use strict';
    $(".dev-form .btn-danger").click(function(){
        var $direction = $(this).attr("data-direct");
        if($(".modal-del .modal-dialog").hasClass($direction)==false){
            $(".modal-del .modal-dialog").addClass($direction);
        }
        $(".modal-del form").attr("action",$(this).attr("href"));
    });
    $('.dev-form a[data-target="#sideModal"]').click(function(){
        var $direction = $(this).attr("data-direct");
        if($(".modal-del .modal-dialog").hasClass($direction)==false){
            $(".modal-del .modal-dialog").addClass($direction);
        }
        $(".modal-del form").attr("action",$(this).attr("href"));
    });

    $('a.direct-modal').click(function(){
        var direction = $(this).attr('data-direct'),
            target = $(this).attr('data-target');
        if($(target).hasClass(direction)==false){
            $(target).addClass(direction);
        }
        if($(target).find('input[name="date_of_action"]').length > 0 && $(this).attr('data-date') != null) {
            $(target).find('input[name="date_of_action"]').val($(this).attr('data-date'));
        }
        if($(target).find('input[name="code"]').length > 0 && $(this).attr('data-code') != null) {
            $(target).find('input[name="code"]').val($(this).attr('data-code'));
        }
        $(target).find('form').attr("action",$(this).attr("href"));
    });

})(jQuery);

$(function () {
    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()
})