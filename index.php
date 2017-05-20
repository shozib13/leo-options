<?php
/**
 * Plugin Name: Leo Optoins
 * Plugin URI:  http://leocoder.com
 * Description: Theme Options UI Builder for WordPress. A simple way to create & save Theme Options and Meta Boxes for free or premium themes.
 * Version:     1.0.0
 * Author:      Md Hasanuzzaman
 * Author URI:  http://leocodere.com/hasan
 * License:     GPLv3
 * Text Domain: leo
 */


/**
 * All Implement Of LeoOptions
 * @author Md Hasanuzzaman <webhasan24@gmail.com>
 * @since 1.0.0
 */

class LeoSettings {

	private $hook = array();
	private $pages;


	public function __construct($arr = array()) {

		if(!is_admin()) 
			return false;

		if(!empty($arr) && isset($arr['pages']))
			$this->pages = $arr['pages'];

		add_action('init', array($this, 'init'));
	}

	/**
	 * Initialize Leo FrameWork
	 * @return void
	 * @since 1.0.0
	 * @author webhasan <webhasan24@gmail.com>
	 */
	
	public function init() {
		if($this->pages == NULL || empty($this->pages)) 
			return false;

		add_action('admin_init', array($this, 'add_fields'));
		add_action('admin_init', array($this, 'add_sections'));
		add_action('admin_menu', array($this, 'add_pages'));
		add_action('admin_enqueue_scripts', array($this, 'admin_script'));
	}


	public function add_sections() { 
		foreach($this->pages as $page) {
			foreach($this->get_sections($page) as $section) {
				add_settings_section($section['id'], $section['title'],'__return_false', $section['id']);
			}
		}
	}

	public function add_fields() {
		foreach($this->pages as $page) {
			
			if(isset($page['hidden']) && $page['hidden'] == true){
				continue; 
			}

			register_setting($page['id'],$page['id']);

			foreach($this->get_fields($page) as $section => $field ) {

				add_settings_field( $field['id'], $field['title'], array($this, 'display_field'), $field['sectoin'], $field['sectoin'], $field );

			}
		}
	}

	public function display_field($arr) {

		require_once 'fields_type.php';

		$field_type = 'hs_field_'.$arr['type'];

		if(function_exists($field_type)) {
			$field_type($arr, $this);
		}else {
			echo '<strong>"'.$arr['type'].'"</strong> field type not found!';
		}

	}


	public function add_pages() {


		foreach($this->pages as $page) {

			if(!isset($page['parent']) || $page['parent'] == '') {
				$hook = add_menu_page( 
					$page['page_title'], 
					$page['menu_title'], 
					'manage_options', 
					$page['slug'], 
					array($this, 'display_page'),
					$page['icon'],
					$page['positoin']
				);

			}else {

				$hook = add_submenu_page(
					$page['parent'],
					$page['page_title'], 
					$page['menu_title'], 
					'manage_options', 
					$page['slug'], 
					array($this, 'display_page')
				);
			}


			if(!isset($page['hidden']) || $page['hidden'] == false)
				$this->hook[$hook] = $page;
		}
	}

	public function display_page($page) {
		$screen = get_current_screen();

		foreach($this->pages as $page) {
			if(isset($page['hidden']) && $page['hidden'] == true)
				continue;

			if($this->hook[$screen->id] == $page) { ?>
				<div class="wrap">
					<h2><?php echo $page['menu_title']; ?></h2>
					<?php settings_errors(); ?>

					<form action="options.php" method="post">
						<?php 
							$this->section_tab($page);

							$this->sections_content($page);

							settings_fields($page['id']);

							submit_button();
						?>
					</form>
				</div>
			<?php }
		}


		return false;
	}

	public function get_sections($page) {

		if(!empty($page) && isset($page['sections'])) {
			return $page['sections'];
		}

		return array();
	}

	public function get_fields($page) {

		if(!empty($page) && isset($page['sections'])) {

			$fields = array();

			foreach($page['sections'] as $section) {
				if(!isset($section['fields'])){
					continue;
				}
					

				foreach($section['fields'] as $field) {

					$field['sectoin'] = $section['id'];
					$field['register_id'] = $page['id'];
					$field['label_for'] = $field['id'];

					$fields[] = $field;
				}

			}

			return $fields;
		}

		return array();
	}

	public function require_file($file) {
		return require($file.'.php');
	}

	public function section_tab($page) { ?>

		<?php 
			$sections = $this->get_sections($page);
			if(count($sections) < 2)
				return false;

			$active_section = isset($_GET['sec']) ? $_GET['sec'] : $sections[0]['id'];
		?>

		<div class="nav-tab-wrapper">

			<?php foreach( $sections as $section) : ?>
				
				<a href="?page=<?php echo $page['slug']; ?>&sec=<?php echo $section['id'];  ?>" class="nav-tab <?php if($active_section == $section['id']) echo ' nav-tab-active'; ?>"><?php echo $section['title'] ?></a>

			<?php endforeach; ?>

		</div>

		<?php
	}


	public function sections_content($page) {
		$sections = $this->get_sections($page);

		$active_section = isset($_GET['sec']) ? $_GET['sec'] : $sections[0]['id'];

		foreach($sections as $section) {
			if($active_section !== $section['id']) {
				echo '<div class="hidden">';
					do_settings_sections($section['id']);
				echo '</div>';
			}else {
				do_settings_sections($section['id']);
			}
		}
	}

	public function admin_script() {
		wp_enqueue_media();

		wp_enqueue_script('leo-admin-script', plugin_dir_url( __FILE__ ) .'admin.js', array( 'jquery' ), true, true) ;

		wp_enqueue_style('leo-admin-style', plugin_dir_url( __FILE__ ) .'admin.css') ;
	}
}


$settings = array(
	'pages' => array(
		array(
			'id' 			=> 'theme-options',
			'page_title' 	=> 'Theme Options',
			'menu_title' 	=> 'Theme Optoins',
			'slug' 			=> 'general-settings',
			'positoin'		=>  60,
			'icon'			=> 'dashicons-editor-table',
			'hidden'		=>  true
		),

		array(
			'id' 			=> 'general-settings',
			'page_title' 	=> 'General Settings',
			'menu_title' 	=> 'General Settings',
			'slug' 			=> 'general-settings',
			'parent'		=> 'general-settings',
			'positoin'		=>  null,
			'icon'			=>  null,

			'sections'		=> array(
				array(
					'id' => 'header-section',
					'title' => 'Header Settings',
					'descriptions' => 'All settings about header.',

					'fields' => array(
						array(
							'id' 			=> 'website-title',
							'title' 		=> 'Website Title',
							'description' 	=> 'Write your website title',
							'type'			=> 'text'
						),

						array(
							'id' 			=> 'tagline',
							'title' 		=> 'Slogan',
							'description' 	=> 'Website slogan for show in top',
							'type'			=> 'textarea'
						)

					)
				),

				array(
					'id' => 'footer-section',
					'title' => 'Footer Settings',
					'descriptions' => 'All settings about header.',

					'fields' => array(
						array(
							'id' 			=> 'copyright',
							'title' 		=> 'Copyright Text',
							'type'			=> 'text'
						),

						array(
							'id' 			=> 'footer-article',
							'title' 		=> 'Footer Article',
							'description' 	=> 'Write Top Footer Article',
							'type'			=> 'textarea'
						)

					)
				)
			)

		),

		array(
			'id' 			=> 'blog-settings',
			'page_title' 	=> 'Blog Settings',
			'menu_title' 	=> 'Blog Settings',
			'slug' 			=> 'blog-settings',
			'parent'		=> 'general-settings',
			'positoin'		=>  null,
			'icon'			=>  null,

			'sections'		=> array(

				array(
					'id' => 'banner',
					'title' => 'Blog Banner',

					'fields' => array(
						array(
							'id' 			=> 'banner-image',
							'title' 		=> 'Banner Image',
							'type'			=> 'image'
						),

						array(
							'id' 			=> 'banner-text',
							'title' 		=> 'Banner Text',
							'description' 	=> 'Banner Text',
							'type'			=> 'textarea'
						)

					)
				),

				array(
					'id' => 'slider',
					'title' => 'Slider',

					'fields' => array(
						array(
							'id' 		=> 'slider-section-heading',
							'title' 	=> 'Section Heading',
							'type'		=> 'text'
						),

						array(
							'id' 		=> 'slider',
							'title' 	=> 'Slider',
							'type'		=> 'repiter',

							'subfield' 		=> array(
								array(
									'id' 		=> 'image',
									'title' 	=> 'Slider Image',
									'type'		=> 'image'
								),

								array(
									'id' 		=> 'caption',
									'title' 	=> 'Banner Capton',
									'type'		=> 'textarea'
								)
							)
						)

					)
				)



			)
		)
	)
);


new LeoSettings($settings);


