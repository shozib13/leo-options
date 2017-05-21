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
		var template = '<li data-index="'+index+'">'+$('.repiter-template').html().replace(/%d%/g, index)+'</li>';

		$(this).siblings('.repiter-fields').append(template);
	});

	/*Remove Repiter Field Options*/

	$('body').on('click', '.repiter-fields .remove-field', function() {
		var currentIndex = $(this).closest('li').data('index'), // 3
			nexItems =  $(this).closest('li').nextAll();

		nexItems.each(function(index) {

			var newIndex = index + currentIndex;

			$(this).attr('data-index', newIndex);

			$(this).find('.leo-field').each(function() {
				value = $(this).attr('name');
				updateValue = value.replace(/\d+(?=]\[[^\]]*]$)/gi, newIndex);

				$(this).attr('name', updateValue);
			})
		});


		$(this).closest('li').remove();
	});

})(jQuery);



