(function($) {
	'use strict';
	/**
	 * library
	 */
	$(".dev-form").on('click','.library',function(e){
		e.preventDefault();
		$("#library-op #file-detail").empty();
		var _token = $(".dev-form input[name='_token']").val();
		var link = $(this).attr("href");
		var tag_id = $(this).parents(".img-upload").attr("id");
		$("#library-op .modal-footer .btn-primary").attr("id",tag_id);
		// $(".loading").show();
		$.ajax({
			type:'POST',
			url:link,
			cache: false,
			data:{
				'_token': _token
			},
			success:function(data){
				// $(".loading").hide();
				if(data.message != 'error'){
					$('#library-op .modal-body #files .list-media').html(data.html);
					$("#library-op #files .limit").val(data.limit);
					$("#library-op #files .current").val(data.current);
					$("#library-op #files .total").val(data.total);
					$("#library-op").modal('toggle');
				}
			}
		})
		return false;
	});
	
	$("#library-op #files").scroll(function(){
		var _token = $(".dev-form input[name='_token']").val();
		var mediaCatId = $("#library-op #media-cat .dropdown-toggle").attr("data-value");
		var s = $("#library-op #media-find input").val();
		total = parseInt($("#library-op #files .total").val());
		current = parseInt($("#library-op #files .current").val());
		limit = $("#library-op #files .limit").val();
		if(total>current){
			if($("#library-op #files").scrollTop() + $("#library-op #files").height()>= $("#library-op .list-media").height() + 10) {
				$.ajax({
					type:'POST',
					url:$("#library-op .more-media").val(),
					cache: false,
					data:{
						'_token': _token,
						'catId': mediaCatId,
						's': s,
						'limit': $("#library-op #files .limit").val(),	
						'current': $("#library-op #files .current").val(),
					},
					success:function(data){
						if(data!="error"){
							total = data.total;
							current = data.current
							$('#library-op .modal-body #files .list-media').append(data.html);
							$("#library-op #files .limit").val(data.limit);
							$("#library-op #files .current").val(data.current);
							$("#library-op #files .total").val(data.total);
						}
					}
				});
		    }
	    }
	});
    $("#library-op #files").on("click",".modal-body li a",function(){
        var tab = $(this).attr("href");
        $(".modal-body .tab-content div").each(function(){
            $(this).removeClass("in active");
        });
        $(".modal-body .tab-content "+tab).addClass("in active");
    });
	//change thumbnail
	$("#library-op .modal-footer").on('click','.btn-primary',function(){
		$("#library-op .modal-footer .library-notify").empty();
		var img_url = $("#library-op .modal-body .image-item.active img").attr("src");
		var img_alt = $("#library-op .modal-body .image-item.active img").attr("alt");
		var img_id = $(".list-media .image-item.active").attr("id").split("-");
		var tag_id = $(this).attr("id");
		if(img_url === undefined){
			$("#library-op .modal-footer .library-notify").text("Please choose file!");
		}else{
			$(".dev-form #"+ tag_id+ " img").attr("src", img_url);
			$(".dev-form #"+ tag_id+ " .thumb-media").val(img_id[1]);
			$("#library-op").modal('toggle');
			$(".modal-backdrop").modal('toggle');
		}
		return false;
	});

	//detail media file
	$("#library-op.single .modal-body").on('click', '.list-media .image-item', function(){
		$(".list-media .image-item").removeClass("active");
		$(this).addClass('active');
		var img_link = $(".list-media .image-item.active img").attr("data-image");
		var img_alt = $(".list-media .image-item.active img").attr("alt");
		var img_date = $(".list-media .image-item.active img").attr("data-date");
		var img_id = $(".list-media .image-item.active").attr("id").split("-");
		var html ="<div class='wrap'>";
			html += "<div class='card card-info'>";
				html += "<div class='card-header'>";
					html += "<h3 class='card-title'>Media Detail</h3>";
					html += '<div class="card-tools"><button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fas fa-minus"></i></button></div>';
				html += '</div>';
				html += '<div class="card-body">';
					html += "<div class='wrap-img'><img src='"+img_link+"' alt='"+img_alt+"'/></div>";
					html +="<h4>"+img_alt+"</h4>";
					html +="<p class='date'>"+img_date+"</p>";
					// html +="<a href='#' class='delete' id='"+img_id[1]+"'>Delete</a>";
				html += '</div>';
			html +="</div>";
		html +="</div>";
		$("#library-op #file-detail").html(html);
	});

	//detail media file
	$(".library-op").on('click', '.list-media .multi__media', function(){

		var checkBoxe = $(this).find('input[type="checkbox"]');
		checkBoxe.prop("checked", !checkBoxe.prop("checked"));

		var current_img = $(this).find('img');
		var img_link = current_img.attr("src");
		var img_alt = current_img.attr("alt");
		var img_date = current_img.attr("data-date");
		var img_id = $(this).attr("data-id").split("-");
		var html ="<div class='wrap'>";
			html += "<div class='card card-info'>";
				html += "<div class='card-header'>";
					html += "<h3 class='card-title'>Media Detail</h3>";
					html += '<div class="card-tools"><button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fas fa-minus"></i></button></div>';
				html += '</div>';
				html += '<div class="card-body">';
					html += "<div class='wrap-img'><img src='"+img_link+"' alt='"+img_alt+"'/></div>";
					html +="<h4>"+img_alt+"</h4>";
					html +="<p class='date'>"+img_date+"</p>";
				html += '</div>';
			html +="</div>";
		html +="</div>";
		$(".library-op #media-detail").html(html);
	});

	$('.library-op .modal-footer').on('click','.btn-primary',function(){
		$('.library-op .modal-footer .library-notify').empty();
		var tag_id = $(this).attr("tag-id");
		// var current_attachment = $('input[name="attachment"]').val().split(',');
		var current_attachment = [];
		$(tag_id).find('.result-multi').empty();
		if($('.library-op input[name="selected__media[]"]:checked').length > 0) {
			$('.library-op input[name="selected__media[]"]:checked').each(function() {
				var img_url = $(this).closest('.multi__media').find('img').attr('src'),
					img_alt = $(this).closest('.multi__media').find('img').attr('alt'),
					img_id = $(this).closest('.multi__media').attr('data-id').split('-');
				// if($.inArray(img_id[1], current_attachment) == -1) {
				// }
				current_attachment.push(img_id[1]);
				var html = '<div data-id="'+ img_id[1] +'" class="image-item multi__media"><div class="wrap">';
				html += '<img src="'+ img_url +'" alt="'+ img_alt +'"/>';
				html += '<a href="javascript:void(0)" class="uncheck__media">&times;</a>';
				html += '</div></div>';
				$(tag_id).find('.result-multi').append(html);
			});
			$(this).closest('.library-op').modal('hide');
			$(".modal-backdrop").modal('hide');
			$('input[name="attachment"]').val(current_attachment.filter(notNull));
		}else{
			$(".library-op .modal-footer .library-notify").text("Please choose files!");
		}
		return false;
	});
	$('body').on('click', '#attachment .uncheck__media', function(e) {
		e.preventDefault();
		var current_attachment = $('input[name="attachment"]').val().split(',');
		var id = $(this).closest('.multi__media').attr('data-id');
		if($.inArray(id, current_attachment) != -1) {
			current_attachment.splice($.inArray(id, current_attachment), 1);
			$(this).closest('.multi__media').remove();
		};
		$('input[name="attachment"]').val(current_attachment);
		attachmentChecked($('input[name="attachment"]'));
	})

	$('a[data-target="#library-multi"]').on('click', function(e){
		$("#library-multi #media-detail").empty();
		var tag_id = $(this).closest('.form-group').attr('id');
		$("#library-multi .modal-footer .btn-primary").attr("tag-id",'#'+tag_id);
	});

	// Search media
	$('body').on('keyup', '.library-op .library__search', function(){
		var value = $(this).val();
		var _token = $('input[name="_token"]').val(),
			catId = $('select[name="media_cate"]').val(),
			action = $(this).closest('form').attr('data-action'),
			current = $(this);
		$.ajax({
			type: 'POST',
			url: action,
			cache: false,
			data:{
				'_token': _token,
				's': value,
				'catId': catId,
				'chosen': $('input[name="attachment"]').val(),
			},
			success:function(data){
				if(data.message=="success"){
					current.closest('form').find('.list-media').html(data.html);
					current.closest('form').find('.limit').val(data.limit);
					current.closest('form').find('.current').val(data.current);
					current.closest('form').find('.total').val(data.total);
				}
			}
		});
		attachmentChecked($('input[name="attachment"]'));
	});
	//load more media
	var total = 0;
	var current = 0;
	var limit = 0;
	$('.library-op .scrollbar-inner').scroll(function(){
		var _token = $('input[name="_token"]').val(),
		mediaCatId = $(this).closest('form').find('select[name="media_cate"]').val(),		
		s = $(this).closest('form').find('.library__search').val(),
		total = parseInt($(this).closest('form').find(".total").val()),
		current = parseInt($(this).closest('form').find('.current').val()),
		current_form = $(this).closest('form'),
		limit = parseInt($(this).closest('form').find('.limit').val());
		if(total>current){
			if($(this).scrollTop() + $(this).height()>= $(this).closest('form').find('.list-media').height() + 10) {
				$.ajax({
					type:'POST',
					url: current_form.attr('data-load'),
					cache: false,
					data:{
						'_token': _token,
						'catId': mediaCatId,
						's': s,
						'limit': limit,	
						'current': current,
						'chosen': $('input[name="attachment"]').val(),
					},
					success:function(data){
						if(data!="error"){
							total = data.total;
							current = data.current
							current_form.find('.list-media').append(data.html);
							current_form.find('.limit').val(data.limit);
							current_form.find('.current').val(data.current);
							current_form.find('.total').val(data.total);
							attachmentChecked($('input[name="attachment"]'));
						}
					}
				});
		    }
	    }
	});

	$(".library-op").on('change','select[name="media_cate"]',function(){
   		var value = $(this).val(),
			_token = $("input[name='_token']").val(),
			current_form = $(this).closest('form');
		current_form.find('.limit').val('');
		current_form.find('.current').val('');
		current_form.find('.list-item .image-item').removeClass("active");
		$(this).addClass("active");
		$(".loading").show();
    	$.ajax({
			type: 'POST',
			url: current_form.attr('data-action'),
			cache: false,
			data:{
				'_token': _token,
				'catId': value,
				's': current_form.find('.library__search').val(),
				'chosen': $('input[name="attachment"]').val(),
			},
			success:function(data){
				$(".loading").hide();
				if(data.message!="error"){
					current_form.find('.list-media').html(data.html);
					current_form.find('.limit').val(data.limit);
					current_form.find('.current').val(data.current);
					current_form.find('.total').val(data.total);
					attachmentChecked($('input[name="attachment"]'));
				}
			}
		});
   });

})(jQuery);

function notNull(element) {
  return element != null && element != '' && element != 0 ;
};

function attachmentChecked(element) {
	var array_check = element.val().split(',');
	$('.library-op .multi__media').each(function() {
		if($.inArray($(this).attr('data-id').split('-')[1], array_check) != -1) {
			$(this).find('input[type="checkbox"]').prop('checked',true);
		}else{
			$(this).find('input[type="checkbox"]').prop('checked',false);
		}
	});
};