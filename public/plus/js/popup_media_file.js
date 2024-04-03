(function($) {
	'use strict';
	/**
	 * library
	 */
	//detail media file
	$(".library-file").on('click', '.list-media .multi__media', function(){
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
		$(".library-file #media-detail").html(html);
	});
	$('.library-file .modal-footer').on('click','.btn-primary',function(){
		$('.library-file .modal-footer .library-notify').empty();
		var tag_id = $(this).attr("tag-id");
		// var current_attachment = $('input[name="attachment"]').val().split(',');
		var current_attachment = [];
		$(tag_id).find('.result-multi').empty();
		if($('.library-file input[name="selected__media[]"]:checked').length > 0) {
			$('.library-file input[name="selected__media[]"]:checked').each(function() {
				var img_url = $(this).closest('.multi__media').find('img').attr('src'),
					img_alt = $(this).closest('.multi__media').find('img').attr('alt'),
					img_id = $(this).closest('.multi__media').attr('data-id').split('-');
				current_attachment.push(img_id[1]);
				var html = '<div data-id="'+ img_id[1] +'" class="image-item multi__media"><div class="wrap">';
				html += '<img src="'+ img_url +'" alt="'+ img_alt +'"/>';
				html += '<a href="javascript:void(0)" class="uncheck__media">&times;</a>';
				html += '</div></div>';
				$(tag_id).find('.result-multi').append(html);
			});
			$(this).closest('.library-file').modal('hide');
			$(".modal-backdrop").modal('hide');
			$('input[name="file"]').val(current_attachment.filter(notNull));
		}else{
			$(".library-file .modal-footer .library-notify").text("Please choose files!");
		}
		return false;
	});

	$('body').on('click', '#file .uncheck__media', function(e) {
		e.preventDefault();
		var current_attachment = $('input[name="file"]').val().split(',');
		var id = $(this).closest('.multi__media').attr('data-id');
		if($.inArray(id, current_attachment) != -1) {
			current_attachment.splice($.inArray(id, current_attachment), 1);
			$(this).closest('.multi__media').remove();
		};
		$('input[name="file"]').val(current_attachment);
		fileChecked($('input[name="file"]'));
	})

	$('a[data-target="#library-multi-file"]').on('click', function(e){
		$("#library-multi-file #media-detail").empty();
		var tag_id = $(this).closest('.form-group').attr('id');
		$("#library-multi-file .modal-footer .btn-primary").attr("tag-id",'#'+tag_id);
	});

	// Search media
	$('body').on('keyup', '.library-file .library__search', function(){
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
				'chosen': $('input[name="file"]').val(),
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
		fileChecked($('input[name="file"]'));
	});
	//load more media
	var total = 0;
	var current = 0;
	var limit = 0;
	$('.library-file .scrollbar-inner').scroll(function(){
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
						'chosen': $('input[name="file"]').val(),
					},
					success:function(data){
						if(data!="error"){
							total = data.total;
							current = data.current
							current_form.find('.list-media').append(data.html);
							current_form.find('.limit').val(data.limit);
							current_form.find('.current').val(data.current);
							current_form.find('.total').val(data.total);
							fileChecked($('input[name="file"]'));
						}
					}
				});
		    }
	    }
	});

	$(".library-file").on('change','select[name="media_cate"]',function(){
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
				'chosen': $('input[name="file"]').val(),
			},
			success:function(data){
				$(".loading").hide();
				if(data.message!="error"){
					current_form.find('.list-media').html(data.html);
					current_form.find('.limit').val(data.limit);
					current_form.find('.current').val(data.current);
					current_form.find('.total').val(data.total);
					fileChecked($('input[name="file"]'));
				}
			}
		});
   });

	// Multi upload by dropzone
	if($('#multiDF').length > 0) {
	    $('#multiDF').dropzone({
	      autoProcessQueue: false,
	      uploadMultiple: true,
	      addRemoveLinks: true,
	      dictRemoveFile: 'Remove',
	      thumbnailHeight: 150,
	      thumbnailWidth: 100,
	      parallelUploads: 10,
	      maxFiles: 10,
	      // previewTemplate: $('#template-preview').html(),
	      url: $('#multiDF').attr('data-action'),
	      headers: {
	        'x-csrf-token':$('input[name="_token"]').val(),
	      },
	      // The setting up of the dropzone
	      init: function() {
	        var multiDropzone = this;
	        var cat_ids = new Array();
	        $('.library-file form button[type="submit"]').on("click", function(e) {
	          // Make sure that the form isn't actually being sent.
	          e.preventDefault();
	          e.stopPropagation();
	          if (multiDropzone.files != "") {
	          	$("#category").val("");
              $("#addFileMultifile .checkbox").each(function(){
                if($(this).find("input").is(":checked")){
                  cat_ids.push($(this).find("input").val());
                }
              });
              $("#category").val(cat_ids.toString());
              $('input[name="category"]').val(cat_ids);
	            multiDropzone.processQueue();
	          } else {
	            $('#multiDF').submit();
	          }
	        });
			this.on('sending', function(file, xhr, formData) {
	            formData.append('category', $('input[name="category"]').val());               
	        });
	        this.on("sendingmultiple", function(files) {
	          // Gets triggered when the form is actually being sent.
	          // Hide the success button or the complete form.
	        });
	        this.on("successmultiple", function(files, response) {
	          multiDropzone.removeFile(files);
	          var current_frm = $('#multiFile form');
	          var _token = $('input[name="_token"]').val();
	          $.ajax({
	            type: 'POST',
	            url: current_frm.attr('data-action'),
	            cache: false,
	            data:{
	              '_token': _token,
	              'catId': '',
	              's': '',
	              'chosen': $('input[name="file"]').val(),
	            },
	            success:function(data){              
	              if(data.message!="error"){
	                current_frm.find('.list-media').html(data.html);
	                current_frm.find('.limit').val(data.limit);
	                current_frm.find('.current').val(data.current);
	                current_frm.find('.total').val(data.total);
	              }
	            }
	          });
	          // $('#multiDF').closest('form').submit();
	        });
	        this.on("errormultiple", function(files, response) {
	          // Gets triggered when there was an error sending the files.
	          // Maybe show form again, and notify user of error
	        });
	      }
	    });
	}

})(jQuery);

function fileChecked(element) {
	var array_check = element.val().split(',');
	$('.library-file .multi__media').each(function() {
		if($.inArray($(this).attr('data-id').split('-')[1], array_check) != -1) {
			$(this).find('input[type="checkbox"]').prop('checked',true);
		}else{
			$(this).find('input[type="checkbox"]').prop('checked',false);
		}
	});
};