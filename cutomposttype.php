<?php
/*
Plugin Name: SP Create your custom post
Plugin URL: http://sptechnolab.com
Description: A simple your custom post plugin
Version: 1.1
Author: SP Technolab
Author URI: http://sptechnolab.com
Contributors: SP Technolab
*/
/*
 * Register sp_customposttype
 *
 */
	$option = 'cpt_option';
	$cptoption = get_option( $option, $default ); 
	$custom_menuname = $cptoption['menu_name']; 
		if ($custom_menuname =='')
			{
				$custom_menuname = 'Custom Post Name';
			}
	$custom_name = $cptoption['post_name']; 
		if ($custom_name =='')
			{
				$custom_name = 'Custom Post Name';
			}
	
	$custom_singular_name = $cptoption['post_singular_name']; 
			if ($custom_singular_name =='')
			{
				$custom_singular_name = 'Custom Post Name';
			}
	
	$custom_shortcode = $cptoption['post_shortcode']; 
			if ($custom_shortcode =='')
			{
				$custom_shortcode = 'YOUR SHORTCODE';
			}
	
function sp_cpt_setup_post_types() {

	global $custom_menuname;
	global $custom_name;
	global $custom_singular_name;

	$cpt_labels =  apply_filters( 'sp_festivals_labels', array(
		'name'                => $custom_name,
		'singular_name'       => $custom_singular_name,
		'add_new'             => __('Add New', 'sp_cpt'),
		'add_new_item'        => __('Add New '.$custom_singular_name, 'sp_cpt'),
		'edit_item'           => __('Edit '.$custom_singular_name, 'sp_cpt'),
		'new_item'            => __('New '.$custom_singular_name, 'sp_cpt'),
		'all_items'           => __('All '.$custom_singular_name, 'sp_cpt'),
		'view_item'           => __('View ' .$custom_singular_name, 'sp_cpt'),
		'search_items'        => __('Search '.$custom_singular_name, 'sp_cpt'),
		'not_found'           => __('No '.$custom_singular_name.' found', 'sp_cpt'),
		'not_found_in_trash'  => __('No '.$custom_singular_name.' found in Trash', 'sp_cpt'),
		'parent_item_colon'   => '',
		'menu_name'           => __($custom_menuname, 'sp_cpt'),
		'exclude_from_search' => true
	) );


	$cpt_args = array(
		'labels' 			=> $cpt_labels,
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
	register_post_type( 'sp_cpt', apply_filters( 'sp_cpt_post_type_args', $cpt_args ) );

}

add_action('init', 'sp_cpt_setup_post_types');
/*
 * Add [sp_cpt limit="-1"] shortcode
 *
 */
function sp_cpt_shortcode( $atts, $content = null ) {
	
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
	$post_type 		= 'sp_cpt';
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
	
	
	// Displays Custom post info
	if( $post_count > 0) :
	
		// Loop
		while ($query->have_posts()) : $query->the_post();
		get_template_part('content'); 
		endwhile;
		
	endif;
	
	// Reset query to prevent conflicts
	wp_reset_query();
	
	?>
	
	<?php
	
	return ob_get_clean();

}

add_shortcode($custom_shortcode, "sp_cpt_shortcode");

class SP_cpt_setting
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_cpt_page' ) );
        add_action( 'admin_init', array( $this, 'page_init_cpt' ) );
    }

    /**
     * Add options page
     */
    public function add_cpt_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Custom Post Type Settings', 
            'manage_options', 
            'cpt-setting-admin', 
            array( $this, 'create_cptsadmin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_cptsadmin_page()
    {
        // Set class property
        $this->options = get_option( 'cpt_option' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Custom Post Type Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'cpt_option_group' );   
                do_settings_sections( 'cpt-setting-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init_cpt()
    {        
        register_setting(
            'cpt_option_group', // Option group
            'cpt_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Custom Post Type Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'cpt-setting-admin' // Page
        );  

        add_settings_field(
            'menu_name', // ID
            'Menu Name', // Title 
            array( $this, 'menu_name_callback' ), // Callback
            'cpt-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'post_name', 
            'Name', 
            array( $this, 'post_name_callback' ), 
            'cpt-setting-admin', 
            'setting_section_id'
        );      
		add_settings_field(
            'post_singular_name', // ID
            'Singular Name ', // Title 
            array( $this, 'post_singular_name_callback' ), // Callback
            'cpt-setting-admin', // Page
            'setting_section_id' // Section           
        );    
		add_settings_field(
            'post_shortcode', // ID
            'Enter your short code ', // Title 
            array( $this, 'post_shortcode_callback' ), // Callback
            'cpt-setting-admin', // Page
            'setting_section_id' // Section           
        );    	

       
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['menu_name'] ) )
            $new_input['menu_name'] = sanitize_text_field( $input['menu_name'] );

        if( isset( $input['post_name'] ) )
            $new_input['post_name'] = sanitize_text_field( $input['post_name'] );
		
		 if( isset( $input['post_singular_name'] ) )
            $new_input['post_singular_name'] = sanitize_text_field( $input['post_singular_name'] );
			
		 if( isset( $input['post_shortcode'] ) )
            $new_input['post_shortcode'] = sanitize_text_field( $input['post_shortcode'] );	
		
        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function menu_name_callback()
    {
        printf(
            '<input type="text" id="menu_name" name="cpt_option[menu_name]" value="%s" />',
            isset( $this->options['menu_name'] ) ? esc_attr( $this->options['menu_name']) : ''
        );
		printf(' For Example <b>Moive</b>');
    }

    /** 
     * Get the settings option array and print one of its values
     */
	 
    public function post_name_callback()
    {
        printf(
            '<input type="text" id="post_name" name="cpt_option[post_name]" value="%s" />',
            isset( $this->options['post_name'] ) ? esc_attr( $this->options['post_name']) : ''
        );
			printf(' For Example <b>Moive</b>');
    }
	 public function post_singular_name_callback()
    {
        printf(
            '<input type="text" id="post_singular_name" name="cpt_option[post_singular_name]" value="%s" />',
            isset( $this->options['post_singular_name'] ) ? esc_attr( $this->options['post_singular_name']) : ''
        );
		printf(' For Example <b>Moive</b>');
    }
	
	 public function post_shortcode_callback()
    {
        printf(
            '<input type="text" id="post_shortcode" name="cpt_option[post_shortcode]" value="%s" />',
            isset( $this->options['post_shortcode'] ) ? esc_attr( $this->options['post_shortcode']) : ''
        );
		printf(' For Example <b>Moive</b>');
    }

  
}

if( is_admin() )
    $my_cpt_page = new SP_cpt_setting();
?>
