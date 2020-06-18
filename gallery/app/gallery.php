<?php

/*
	This code will add Photo Gallery new post type in admin panel of your WordPress theme.
	Just add it into your functions.php WordPress file. 
	After that you can add, edit and delete Galeries as a new post type. Also you
	can add thumbnails for you galeries, upload and delete fotos.  
	It is required uplfoto.js file. Use correct link to this file in your theme

 */

// add thumbnails support for all type of posts
add_theme_support( 'post-thumbnails' ); 

// add required scripts
add_action('admin_enqueue_scripts', function(){
	wp_enqueue_script('uplfoto', get_template_directory_uri() .'/uplfoto.js');
});

// add a new type of post types - galery
add_action('init', 'register_post_types');
function register_post_types(){
	register_post_type('galery', array(
		'label'  => null,
		'labels' => array(
			'name'               => 'galery', // main name of post type
			'singular_name'      => '____', // one post name
			'add_new'            => 'Add new chapter', // add new post
			'add_new_item'       => 'Adding galery', // title
			'edit_item'          => 'Edit galery', // edit post
			'new_item'           => 'New galery', // текст новой записи
			'view_item'          => 'Watch galery', // view post
			'search_items'       => 'Find gaalry', // searching
			'not_found'          => 'Not found', // not found
			'not_found_in_trash' => 'Not found in trash',
			'parent_item_colon'  => '', // for parent 
			'menu_name'          => 'Galery', // this name will use in admin-bar menu 
		),
		'description'         => '',
		'public'              => true,
		'publicly_queryable'  => null, // depends on public
		'exclude_from_search' => null, // depends on public
		'show_ui'             => null, // depends on public
		'show_in_menu'        => true, // show in admin panel
		'show_in_admin_bar'   => null, // show_in_menu
		'show_in_nav_menus'   => null, // depends on public
		'show_in_rest'        => null, // add to REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => null,
		'menu_icon'           => 'dashicons-images-alt2', 
		'hierarchical'        => false,
		'supports'            => array('title','thumbnail', 'editor'),
		'taxonomies'          => array(),
		'has_archive'         => false,
		'rewrite'             => true,
		'query_var'           => true,
	) );

}


function true_image_uploader_field( $name, $value = '', $w = 115, $h = 90) {
	$default = get_stylesheet_directory_uri() . '/img/no-image.png';
	if( $value ) {
		$image_attributes = wp_get_attachment_image_src( $value, array($w, $h) );
		$src = $image_attributes[0];
	} else {
		$src = $default;
	}
	echo '
	<div>
		<img data-src="' . $default . '" src="' . $src . '" width="' . $w . 'px" height="' . $h . 'px" />
		<div>
			<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
			<button type="submit" class="upload_image_button button">Upload</button>
			<button type="submit" class="remove_image_button button">&times;</button>
		</div>
	</div>
	';
}


function new_fields_galery() {
	add_meta_box( 'new_fields_galery', 'Extra galery fields', 'extra_fields_box_func', 'galery', 'normal', 'high'  );
}
add_action('add_meta_boxes', 'new_fields_galery', 1);


function extra_fields_box_func( $post ){

	if( function_exists( 'true_image_uploader_field' ) ) {
		true_image_uploader_field( 'uploader_custom', get_post_meta($post->ID, 'uploader_custom', true) );
	}

		// delete several posts
		if(isset($_POST['img_id'])){
			$img_values = get_post($_POST['img_id']);
			$arr = explode(';', $_POST['img_id']);
			foreach ($arr as $key) {
				if($key !== ''){
					$img_values = get_post($key);
					$img_values = $img_values->post_title;
					if( wp_delete_attachment($key) ){
						$info_arr.=$img_values.'.jpg<br>';
					} 
				}
			}
		}

		if(isset($_POST['img_id_sort'])){
			$img_values = get_post($_POST['img_id_sort']);
			$arr = explode(';', $_POST['img_id_sort']);
			$count_pr = count($img_values);
			foreach ($arr as $key) {
				if($key !== ''){
					$img_values = get_post($key);
					$img_values = $img_values->post_title;
					$my_post = array();
					$my_post['ID'] = $key;
					$my_post['post_excerpt'] = $count_pr;
					wp_update_post( wp_slash($my_post) );
					$count_pr--;
				}
			}
		}
	?>
	<div>
		<p><b>Galeries fotos:<b></p>
		<div style="border: 1px solid #ccc; margin-bottom: 5px; padding: 3px; display: block;" class="nav_sort">
			<div style="display: flex;">
				<button class="btn-sel">Selet all</button>
				<button class="btn-desel">Deselect all</button>
				<div>
					<input type="hidden" name="img_id" value="" class="wrap_it inp_arr_img">
					<input type="submit" name="del_all_img" value="Delete" class="wrap_it del_btn">
				</div>
			</div>	
		</div>	
		<div style="border: 1px solid #ddd; font-size: 0; display: flex;" id="view_list" class="admin-galery-img new-img">
			<div class="sortable-galery" style="display: flex; flex-wrap: wrap;">
			<? 
				$array = get_attached_media( 'image', $post->ID );
				uasort($array, 'sort_img'); 
				$attach = array_reverse($array);
				$thumb = get_the_post_thumbnail( $post->ID, 'full' );
				
				foreach ($attach as $key) {
					if(!strpos($thumb, $key->guid) ) {
						?>
						<div class="del" style="width: 140px; display: flex; align-items: center; margin-bottom: 20px; margin-right: 10px;">
							<input type="checkbox" name="del_foto" value="<?= $key->ID; ?>" style="margin-right: 5px;">
						<?	
						echo '<div class="ain-img-wrap" style="width:120px; height:120px; background-image: url('.$key->guid.'); background-position: center center; background-size: cover;" data-src="'.$key->guid.'">'.PHP_EOL;
						echo '</div>';
						echo '</div>';
					}
				}

			?>
			</div>
		</div>
	</div>
	<?php
}

// add save post actions
add_action('save_post', 'true_save_box_data_u');
function true_save_box_data_u( $post_id ) {
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;
	update_post_meta( $post_id, 'uploader_custom', $_POST['uploader_custom']);
	return $post_id;
}
add_action('save_post', 'my_extra_fields_art_more', 0);
function my_extra_fields_art_more( $post_id ){
	if ( ! wp_verify_nonce($_POST['extra_fields_nonce'], __FILE__) ) return false; // checking
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false; // exit if autosaved
	if ( !current_user_can('edit_post', $post_id) ) return false; // exit if user are disalowed
	if( !isset($_POST['extra']) ) return false; // exit if no data
	// Saving data
	$_POST['extra'] = array_map('trim', $_POST['extra']); // trim the data
	foreach( $_POST['extra'] as $key=>$value ){
		if( empty($value) ){
			delete_post_meta($post_id, $key); // deleting empty field
			continue;
		}
		update_post_meta($post_id, $key, $value);
	}
	return $post_id;
}
