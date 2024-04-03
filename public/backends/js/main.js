(function($) {
    'use strict';
    $('.editor').summernote({
        height: 300,
    });
    //check all
    $("#check-all input").click(function() {
        if ($(this).is(":checked") && $('.check input[name="checkbox[]"]').length > 0) {
            $(".dev-form tbody .check input").prop("checked", true);
            if ($(".dev-form .dell-all").length < 1) {
                $(".dev-form .table-striped").before('<a class="dell-all btn btn-top" href="#" data-toggle="modal" data-target="#deleteChooseModal">Xóa tất cả</a>');
                $(".dev-form .table-striped").after('<a class="dell-all btn btn-top" href="#" data-toggle="modal" data-target="#deleteChooseModal">Xóa tất cả</a>');
            }
        } else {
            $(".dev-form tbody .check input").prop('checked', false);
            if ($(".dev-form .dell-all").length > 0) {
                $(".dev-form .dell-all").remove();
            }
        }
    });
    $(".dev-form tbody .check").click(function() {
        var items = new Array();
        $(".dev-form .delete-choose").html();
        $(".dev-form tbody tr").each(function() {
            if ($(this).find(".check input").is(":checked")) {
                items.push($(this).find("input").val());
            }
        });
        if (items.length > 0) {
            if ($(".dev-form .dell-all").length < 1) {
                $(".dev-form .table-striped").before('<a class="dell-all btn btn-top" href="#" data-toggle="modal" data-target="#deleteChooseModal">Xóa</a>');
                $(".dev-form .table-striped").after('<a class="dell-all btn btn-top" href="#" data-toggle="modal" data-target="#deleteChooseModal">Xóa</a>');
            }
        } else {
            if ($(".dev-form .dell-all").length > 0) {
                $(".dev-form .dell-all").remove();
            }
        }
    });
    //delele items choosed
    $("form").on('click', '.dell-all', function() {
        var items = new Array();
        $(".dev-form tbody tr").each(function() {
            if ($(this).find(".check input").is(":checked")) {
                items.push($(this).find("input").val());
            }
        });
        $("#deleteChooseModal form").attr("action", $(".dev-form").attr("action"));
        $("#deleteChooseModal form .choose-items").val(items.toString());
    });

    //check change password
    $("#edit-user .check-password input").click(function() {
        if ($(this).is(":checked")) {
            $("#edit-user .change-password .form-control").removeAttr("disabled");
            $("#edit-user .change-password").slideDown();
            $(this).val("on");
        } else {
            $(this).val("");
            $("#edit-user .change-password .form-control").attr("disabled", "");
            $("#edit-user .change-password").slideUp();
        }
    });
    $("#edit-user .check-password input").click(function() {
            if ($(this).is(":checked")) {
                $("#edit-user .change-password .form-control").removeAttr("disabled");
                $("#edit-user .change-password").slideDown();
                $(this).val("on");
            } else {
                $(this).val("");
                $("#edit-user .change-password .form-control").attr("disabled", "");
                $("#edit-user .change-password").slideUp();
            }
        })
        //change phone
    $("#frm-phone .check-password input").click(function() {
        if ($(this).is(":checked")) {
            $("#edit-user .change-password .form-control").removeAttr("disabled");
            $("#edit-user .change-password").slideDown();
            $(this).val("on");
        } else {
            $(this).val("");
            $("#edit-user .change-password .form-control").attr("disabled", "");
            $("#edit-user .change-password").slideUp();
        }
    });
    //change phone
    $(".custom-switch input").click(function() {
        if ($(this).val() == "off") {
            $(this).val("on");
            $(this).parents(".form-group").find(".value-switch").attr("readonly", false);
        } else {
            $(this).val("off");
            $(this).parents(".form-group").find(".value-switch").attr("readonly", true);
        }

    });
    $('form.dev-form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    $("#datepicker").datetimepicker();

    $('form.equipments select[name="per_page"]').on('change', function() {
        $(this).closest('form.equipments').submit();
    });


})(jQuery);