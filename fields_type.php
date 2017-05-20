<?php
/**
 * Simple Text Input Field
 * @param  array $arr Array with field information
 * @return void
 * @since  1.0.0
 * @author Md Hasnauzzaman <webhasan24@gmail.com>
 */

function hs_field_text($arr) {

	extract($arr);

	$index = 0;

	if(isset($parent)) {
		$value = isset(get_option($register_id)[$parent][$index][$id]) ? get_option($register_id)[$parent][$index][$id] : '';
		$name = $register_id.'['.$parent.']['.$index.']'.'['.$id.']';

	}else {
		$value = isset(get_option($register_id)[$id]) ? get_option($register_id)[$id] : '';
		$name = $register_id.'['.$id.']';
	}


?>
	<input type="text" id="<?php echo $id; ?>" class="regular-text" name="<?php echo $name; ?>" value="<?php echo $value; ?>" >
<?php }




/**
 * Textarea Field
 * @param  array $arr Array with field information
 * @return void
 * @since  1.0.0
 * @author Md Hasnauzzaman <webhasan24@gmail.com>
 */

function hs_field_textarea($arr) {
	extract($arr);

	if(isset($template) && $template == true) {
		$index = '%d%';
	}

	if(isset($parent)) {
		$value = isset(get_option($register_id)[$parent][$index][$id]) ? get_option($register_id)[$parent][$index][$id] : '';
		$name = $register_id.'['.$parent.']['.$index.']'.'['.$id.']';

	}else {
		$value = isset(get_option($register_id)[$id]) ? get_option($register_id)[$id] : '';
		$name = $register_id.'['.$id.']';
	}

?>
	<textarea type="text" rows="7" class="regular-text" id="<?php echo $id; ?>" name="<?php echo $name; ?>"><?php echo $value; ?></textarea>

<?php }



/**
 * Image Upload Field. Provide Image URL.
 * @param  array $arr Array with field information
 * @return void
 * @since  1.0.0
 * @author Md Hasnauzzaman <webhasan24@gmail.com>
 */

function hs_field_image($arr) {

	extract($arr);

	if(isset($template) && $template == true) {
		$index = '%d%';
	}
	

	if(isset($parent)) {
		$value = isset(get_option($register_id)[$parent][$index][$id]) ? get_option($register_id)[$parent][$index][$id] : '';

		$name = $register_id.'['.$parent.']['.$index.']'.'['.$id.']';

	}else {
		$value = isset(get_option($register_id)[$id]) ? get_option($register_id)[$id] : '';
		$name = $register_id.'['.$id.']';
	} ?>

	<input type="text" rows="7" class="regular-text" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>">

	<button class="button button-primary upload-btn">Upload</button>

		<div class="view leo-upload-view" style="margin-top: 10px;">
			<?php if($value): ?>
				<img src="<?php echo $value; ?>"  style="max-width: 349px; height: auto;">
				<span class="close">x</span>
			<?php endif; ?>
		</div>

<?php }



/**
 * Image Upload Field. Provide Image URL.
 * @param  array $arr Array with field information
 * @return void
 * @since  1.0.0
 * @author Md Hasnauzzaman <webhasan24@gmail.com>
 */

function hs_field_repiter($arr, $object) {
	extract($arr);	
	$values = isset(get_option($register_id)[$id]) ? get_option($register_id)[$id] : array();
?>

	<div class="repiter-field-warp">

		<ul class="repiter-fields">
			<?php foreach($values as $key => $value): ?>
				<li data-index="<?php echo $key; ?>">
				<span class="remove-field">-</span>
				<?php 
				foreach($subfield as $field) {
					$field['parent'] = $id;
					$field['index'] = $key;
					$field['register_id'] = $register_id;
					$object->display_field($field);
				}
				?>
				</li>
			<?php endforeach; ?>


		</ul><!-- end .repiter-fields -->

		<script class="repiter-template" type="text/template">
			<span class="remove-field">-</span>
			<?php 
				foreach($subfield as $field) {
					$field['parent'] = $id;
					$field['template'] = true;
					$field['register_id'] = $register_id;
					$object->display_field($field);
				}
			?>
		</script>

		<button class="button button-primary add-repiter-item" type="button">+Add Item</button>

	</div>

	<?php 
 }


