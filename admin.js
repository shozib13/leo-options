;(function($) {
	/* Upload Image */
	$('body').on('click','.upload-btn', function(e) {
		e.preventDefault();

		var $button = $(this);

		var that = wp.media({
			title: 'Upload Logo',
			button: {
				text: 'Insert Logo'
			},
			multiple: false
		}).open().on('select', function() {
			var attachment = that.state().get('selection').first().toJSON();

			$button.prev('input').val(attachment.url);

			console.log($button.next('.view'));

			$button.next('.view').html('<img src="' + attachment.url + '" style="width:350px; height: auto;"><span class="close">x</span>');
			

		})
	});


	/* Remove Image */
	$('body').on('click', '.leo-upload-view .close', function() {

		$(this)
			.closest('.leo-upload-view').html('')
			.siblings('input').val('');
	});


	/* Add Repiter Field */
	$('.add-repiter-item').on('click', function() {
		var index = $(this).siblings('.repiter-fields').find('li').length;
		var template = '<li>'+$('.repiter-template').html().replace(/%d%/g, index)+'</li>';

		$(this).siblings('.repiter-fields').append(template);
	});

})(jQuery);



