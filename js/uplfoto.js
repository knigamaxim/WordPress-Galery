jQuery(function($){
	$('.upload_image_button').click(function(){
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = $(this);
		wp.media.editor.send.attachment = function(props, attachment) {
			$(button).parent().prev().attr('src', attachment.url);
			$(button).prev().val(attachment.id);
			wp.media.editor.send.attachment = send_attachment_bkp;
		}
		wp.media.editor.open(button);
		return false;    
	});
	$('.remove_image_button').click(function(){
		var r = confirm("Уверены?");
		if (r == true) {
			var src = $(this).parent().prev().attr('data-src');
			$(this).parent().prev().attr('src', src);
			$(this).prev().prev().val('');
		}
		return false;
	});
	$('#view_big>a:first-of-type input').wrapAll('<form method="post" action="" class="admin-foto-form"></form>');
	$( ".wrap_it").wrapAll('<form method="post" id="dell_all"></form>');
	$( ".wrap_it_2").wrapAll('<form method="post" id="sort_all"></form>');
	$('.btn-sel').click(function(event){
		event.preventDefault();
		var a = $('.del>input');
		for (var i = 0; i < a.length; i++) {
			$(a[i]).attr('checked', true);
		}	
	});
	$('.btn-desel').click(function(event){
		event.preventDefault();
		var a = $('.del>input');
		for (var i = 0; i < a.length; i++) {
			$(a[i]).attr('checked', false);
		}	
	});
	var str = '';
	$('.del_btn').click(function(event){
		event.preventDefault();
		var a = $('.del>input');
		for (var i = 0; i < a.length; i++) {
			if ( $(a[i]).attr('checked') == 'checked' ){
				str+= a[i].value +';';
			}
		}
		$('.inp_arr_img').val(str);
		$('#dell_all').submit();
	});
});
