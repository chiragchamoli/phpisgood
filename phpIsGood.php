<?php 

function add_post_type($name, $args = array()){
	
	add_action('init', function() use ($name, $args)
	{
		$nameUpper = ucwords($name);
		$default_settings = array(
			'public' =>  true,
			'label' =>  "All $nameUpper",
			'labels' => array('add_new_item' => "Add new $nameUpper"),
			'supports'=> array('title','editor','comments','excerpt'),
			'taxonomies' => array('post_tag','category')
			);
		$args = array_merge($default_settings,$args);
		register_post_type($name, $args);
	});
}


//frameworks

//frameworks
function add_taxonomy($name, $post_type, $args = array()) {
	$name = strtolower($name);
	add_action('init', function() use ($name,  $post_type, $args)
	{
				
		$default_settings = array (
					'label' => ucwords($name)
				);
		$args = array_merge($default_settings,$args);
		register_taxonomy($name, $post_type,$args);

	});

}

add_action('add_meta_boxes', function() {
        
        add_meta_box( 
            'pig_snippet_info',
            'Snippet Info',
            'pig_snippet_info_cb',
            'snippet',
            'normal',
            'high'

        );
    }
	);


function pig_snippet_info_cb()
{
	global $post;
	$post_ref_url = get_post_meta($post->ID,'pig_ref_url',true);

	wp_nonce_field(__FILE__,'pig_nonce');

	?>
	<label for="">Associated URL: </label>
	<input type="text" name="pig_ref_url" id="pig_ref_url" class="widefat" value="<?php echo $post_ref_url; ?>" />
	<?php
}

add_action('save_post',function(){
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

	if($_POST && !wp_verify_nonce($_POST['pig_nonce'],__FILE__)) {
		return; 
	}

	if(isset($_POST['pig_ref_url'])) 
	{
			update_post_meta($post->ID, 'pig_ref_url', $_POST['pig_ref_url'] );
	}
});