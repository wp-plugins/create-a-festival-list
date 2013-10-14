<?php
/*
Plugin Name: SP Festivals
Plugin URL: http://sptechnolab.com
Description: A simple Festivals list plugin
Version: 1.0
Author: SP Technolab
Author URI: http://sptechnolab.com
Contributors: SP Technola
*/
/*
 * Register CPT sp_festivals
 *
 */
function sp_festivals_setup_post_types() {

	$festivals_labels =  apply_filters( 'sp_festivals_labels', array(
		'name'                => 'Festivals',
		'singular_name'       => 'Festival',
		'add_new'             => __('Add New', 'sp_festivals'),
		'add_new_item'        => __('Add New Festivals', 'sp_festivals'),
		'edit_item'           => __('Edit Festivals', 'sp_festivals'),
		'new_item'            => __('New Festivals', 'sp_festivals'),
		'all_items'           => __('All Festivals', 'sp_festivals'),
		'view_item'           => __('View Festivals', 'sp_festivals'),
		'search_items'        => __('Search Festivals', 'sp_festivals'),
		'not_found'           => __('No Festivals found', 'sp_festivals'),
		'not_found_in_trash'  => __('No Festivals found in Trash', 'sp_festivals'),
		'parent_item_colon'   => '',
		'menu_name'           => __('Festivals', 'sp_festivals'),
		'exclude_from_search' => true
	) );


	$faq_args = array(
		'labels' 			=> $festivals_labels,
		'public' 			=> true,
		'publicly_queryable'=> true,
		'show_ui' 			=> true,
		'show_in_menu' 		=> true,
		'query_var' 		=> true,
		'capability_type' 	=> 'post',
		'has_archive' 		=> true,
		'hierarchical' 		=> false,
		'supports' => array('title','editor','thumbnail','excerpt'),
		'taxonomies' => array('category', 'post_tag')
	);
	register_post_type( 'sp_festivals', apply_filters( 'sp_festivals_post_type_args', $faq_args ) );

}

add_action('init', 'sp_festivals_setup_post_types');
/*
 * Add [sp_festivals limit="-1"] shortcode
 *
 */
function sp_festivals_shortcode( $atts, $content = null ) {
	
	extract(shortcode_atts(array(
		"limit" => ''
	), $atts));
	
	// Define limit
	if( $limit ) { 
		$posts_per_page = $limit; 
	} else {
		$posts_per_page = '-1';
	}
	
	ob_start();

	// Create the Query
	$post_type 		= 'sp_festivals';
	$orderby 		= 'post_date';
	$order 			= 'DESC';
				
	$query = new WP_Query( array ( 
								'post_type'      => $post_type,
								'posts_per_page' => $posts_per_page,
								'orderby'        => $orderby, 
								'order'          => $order,
								'no_found_rows'  => 1
								) 
						);
	
	//Get post type count
	$post_count = $query->post_count;
	$i = 1;
	
	// Displays Custom post info
	if( $post_count > 0) :
	
		// Loop
		while ($query->have_posts()) : $query->the_post();
		?>
		<div class="custompost" style="clear:both; padding:5px 0; border-bottom:1px solid #f1f1f1; float:left; width:100%;">
		<div class="fast_image">
		 <?php
                  if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ) {
                    the_post_thumbnail();
                  }
                  ?>
		</div>
		<div class="fast_right">	
		<h3 class="sp_festivals_title"><?php the_title(); ?></h3>
		<div id="sp_festivals_<?php echo get_the_ID(); ?>" ><?php echo get_the_content(); ?></div>
		</div>
		</div>
		<?php
		$i++;
		endwhile;
		
	endif;
	
	// Reset query to prevent conflicts
	wp_reset_query();
	
	?>
	
	<?php
	
	return ob_get_clean();

}

add_shortcode("sp_festivals", "sp_festivals_shortcode");