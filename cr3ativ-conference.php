<?php
/**
 * Plugin Name: Cr3ativ Conference Plugin
 * Plugin URI: http://cr3ativ.com/conference
 * Description: Custom written plugin for your conference needs on your WordPress site.
 * Author: Jonathan Atkinson
 * Author URI: http://cr3ativ.com/
 * Version: 1.0.0
 */

/* Place custom code below this line. */

/* Variables */
$ja_cr3ativ_conference_main_file = dirname(__FILE__).'/cr3ativ-conference.php';
$ja_cr3ativ_conference_directory = plugin_dir_url($ja_cr3ativ_conference_main_file);
$ja_cr3ativ_conference_path = dirname(__FILE__);

/* Add css file */
function creativ_conference_add_scripts() {
	global $ja_cr3ativ_conference_directory, $ja_cr3ativ_conference_path;
		wp_enqueue_style('creativ_conference', $ja_cr3ativ_conference_directory.'css/cr3ativconference.css');
}
		
add_action('wp_enqueue_scripts', 'creativ_conference_add_scripts');


////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////       WP Default Functionality       ////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
add_theme_support( 'post-thumbnails' );


////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////            Theme Options Metabox            /////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
include_once( 'includes/meta_box.php' );


////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////     Text Domain     /////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
load_plugin_textdomain('cr3at_conf', false, basename( dirname( __FILE__ ) ) . '/languages' );

////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////     Careers post type     ///////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////

function cr3_conferencesettings_admin_menu_setup(){
add_submenu_page(
 'edit.php?post_type=cr3ativconference',
 __('Cr3ativ Conference Options', 'cr3at_conf'),
 __('Conference Options', 'cr3at_conf'),
 'manage_options',
 'cr3_conferencesettings',
 'cr3_conferencesettings_admin_page_screen'
 );
}
add_action('admin_menu', 'cr3_conferencesettings_admin_menu_setup'); //menu setup

/* display page content */
function cr3_conferencesettings_admin_page_screen() {
 global $submenu;
// access page settings 
 $page_data = array();
 foreach($submenu['options-general.php'] as $i => $menu_item) {
 if($submenu['options-general.php'][$i][2] == 'cr3_conferencesettings')
 $page_data = $submenu['options-general.php'][$i];
 }

// output 
?>
<div class="wrap">
    <style>
#cr3_conferencesettings_options .form-table th, #cr3_conferencesettings_options .form-wrap label {
display: none;
}
#cr3_conferencesettings_options label {
    cursor: pointer;
    display: block;
    float: left;
    width: 25%;
}
</style>
       

<?php screen_icon();?>
<h2><?php _e('Cr3ativ Conference Settings', 'cr3at_conf');?></h2>
<form id="cr3_conferencesettings_options" action="options.php" method="post">
<?php
settings_fields('cr3_conferencesettings_options');
do_settings_sections('cr3_conferencesettings'); 
submit_button('Save options', 'primary', 'cr3_conferencesettings_options_submit');
?>
 </form>
</div>
<?php
}

add_action('admin_init', 'cr3_conferencesettings_flush' );

function cr3_conferencesettings_flush(){

		if ( isset( $_POST['cr3_conferencesettings_options'] ) ) {


			flush_rewrite_rules();
		
		}

} 
function cr3_conferencesettings_settings_init(){

register_setting(
 'cr3_conferencesettings_options',
 'cr3_conferencesettings_options',
 'cr3_conferencesettings_options_validate'
 );

add_settings_section(
 'cr3_conferencesettings_authorbox',
 '', 
 'cr3_conferencesettings_authorbox_desc',
 'cr3_conferencesettings'
 );

add_settings_field(
 'cr3_conferencesettings_authorbox_template',
 '', 
 'cr3_conferencesettings_authorbox_field',
 'cr3_conferencesettings',
 'cr3_conferencesettings_authorbox'
 );
    
add_settings_field(
 'cr3_conferencesettings_authorbox_template2',
 '', 
 'cr3_conferencesettings_authorbox_field2',
 'cr3_conferencesettings',
 'cr3_conferencesettings_authorbox2'
 );
    
add_settings_field(
 'cr3_conferencesettings_authorbox_template3',
 '', 
 'cr3_conferencesettings_authorbox_field3',
 'cr3_conferencesettings',
 'cr3_conferencesettings_authorbox3'
 );
    
}

add_action('admin_init', 'cr3_conferencesettings_settings_init');

/* validate input */
function cr3_conferencesettings_options_validate($input){
 global $allowedposttags, $allowedrichhtml;
if(isset($input['authorbox_template']))
 $input['authorbox_template'] = wp_kses_post($input['authorbox_template']);
 $input['authorbox_template2'] = wp_kses_post($input['authorbox_template2']);
 $input['authorbox_template3'] = wp_kses_post($input['authorbox_template3']);
return $input;
}

/* description text */
function cr3_conferencesettings_authorbox_desc(){
_e('Please set the slug name(s) below for your single pages and session category pages.  Default urls will be used if nothing is set.', 'cr3at_conf');
}

/* filed output */
function cr3_conferencesettings_authorbox_field() {
 $options = get_option('cr3_conferencesettings_options');
 $authorbox = (isset($options['authorbox_template'])) ? $options['authorbox_template'] : '';
 $authorbox = strip_tags($authorbox); //sanitise output
 $authorbox2 = (isset($options['authorbox_template2'])) ? $options['authorbox_template2'] : '';
 $authorbox2 = strip_tags($authorbox2); //sanitise output
 $authorbox3 = (isset($options['authorbox_template3'])) ? $options['authorbox_template3'] : '';
 $authorbox3 = strip_tags($authorbox3); //sanitise output


?>
<p>
    <label><?php _e('Session Single Page Slug Name', 'cr3at_conf');?></label>
 <input type="text" id="authorbox_template" name="cr3_conferencesettings_options[authorbox_template]" value="<?php echo $authorbox; ?>" /></p>

<p>
    <label><?php _e('Session Category Page Slug Name', 'cr3at_conf');?></label>
 <input type="text" id="authorbox_template2" name="cr3_conferencesettings_options[authorbox_template2]" value="<?php echo $authorbox2; ?>" /></p>

<p>
    <label><?php _e('Speaker Single Page Slug Name', 'cr3at_conf');?></label>
 <input type="text" id="authorbox_template3" name="cr3_conferencesettings_options[authorbox_template3]" value="<?php echo $authorbox3; ?>" /></p>

<?php
}

add_action('init', 'create_cr3ativconference');

function create_cr3ativconference() {
 $options = get_option('cr3_conferencesettings_options');
 $authorbox = (isset($options['authorbox_template'])) ? $options['authorbox_template'] : '';
 $authorbox = strip_tags($authorbox); //sanitise output	
	$labels = array(
		'name'               => __( 'Sessions', 'post type general name', 'cr3at_conf' ),
		'singular_name'      => __( 'Session', 'post type singular name', 'cr3at_conf' ),
		'menu_name'          => __( 'Conference', 'admin menu', 'cr3at_conf' ),
		'add_new'            => __( 'Add New Session', 'session', 'cr3at_conf' ),
		'add_new_item'       => __( 'Add New Session', 'cr3at_conf' ),
		'new_item'           => __( 'New Session', 'cr3at_conf' ),
		'edit_item'          => __( 'Edit Session', 'cr3at_conf' ),
		'view_item'          => __( 'View Session', 'cr3at_conf' ),
		'all_items'          => __( 'All Sessions', 'cr3at_conf' ),
		'search_items'       => __( 'Search Sessions', 'cr3at_conf' ),
		'not_found'          => __( 'No sessions found.', 'cr3at_conf' ),
		'not_found_in_trash' => __( 'No sessions found in Trash.', 'cr3at_conf' )
	);
    	$cr3ativconference_args = array(
        	'labels' => $labels,
        	'public' => true,
            'menu_icon' => 'dashicons-nametag',
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
            'rewrite' => array('slug' => $authorbox), 
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('title','editor','thumbnail')
        );
       

        
register_post_type('cr3ativconference',$cr3ativconference_args);
	}


$cr3ativconference_fields = array(
	array(
            'label' => __('Date', 'cr3at_conf'),
            'desc' => __('Choose the date.', 'cr3at_conf'),
            'id' => 'cr3ativconfmeetingdate',
            'type' => 'date',
            'std' => ''
        ),
    array(
        'label'    => __('Start Time', 'cr3at_conf'),
        'desc'    => __('Select the start time. 24-hour clock, 01:00-00:30', 'cr3at_conf'),
        'id'      => 'cr3ativ_confstarttime',
        'type'    => 'select',
        'options' => array(
    array('label' => '08:00', 'value' => '08:00'),
    array('label' => '08:30', 'value' => '08:30'),
    array('label' => '09:00', 'value' => '09:00'),
    array('label' => '09:30', 'value' => '09:30'),
    array('label' => '10:00', 'value' => '10:00'),
    array('label' => '10:30', 'value' => '10:30'),
    array('label' => '11:00', 'value' => '11:00'),
    array('label' => '11:30', 'value' => '11:30'),
    array('label' => '12:00', 'value' => '12:00'),
    array('label' => '12:30', 'value' => '12:30'),
    array('label' => '13:00', 'value' => '13:00'),
    array('label' => '13:30', 'value' => '13:30'),
    array('label' => '14:00', 'value' => '14:00'),
    array('label' => '14:30', 'value' => '14:30'),
    array('label' => '15:00', 'value' => '15:00'),
    array('label' => '15:30', 'value' => '15:30'),
    array('label' => '16:00', 'value' => '16:00'),
    array('label' => '16:30', 'value' => '16:30'),
    array('label' => '17:00', 'value' => '17:00'),
    array('label' => '17:30', 'value' => '17:30'),
    array('label' => '18:00', 'value' => '18:00'),
    array('label' => '18:30', 'value' => '18:30'),
    array('label' => '19:00', 'value' => '19:00'),
    array('label' => '19:30', 'value' => '19:30'),
    array('label' => '20:00', 'value' => '20:00'),
    array('label' => '20:30', 'value' => '20:30'),
    array('label' => '21:00', 'value' => '21:00'),
    array('label' => '21:30', 'value' => '21:30'),
    array('label' => '22:00', 'value' => '22:00'),
    array('label' => '22:30', 'value' => '22:30'),
    array('label' => '23:00', 'value' => '23:00'),
    array('label' => '23:30', 'value' => '23:30'),
    array('label' => '00:00', 'value' => '00:00'),
    array('label' => '00:30', 'value' => '00:30'),
    array('label' => '01:00', 'value' => '01:00'),
    array('label' => '01:30', 'value' => '01:30'),
    array('label' => '02:00', 'value' => '02:00'),
    array('label' => '02:30', 'value' => '02:30'),
    array('label' => '03:00', 'value' => '03:00'),
    array('label' => '03:30', 'value' => '03:30'),
    array('label' => '04:00', 'value' => '04:00'),
    array('label' => '04:30', 'value' => '04:30'),
    array('label' => '05:00', 'value' => '05:00'),
    array('label' => '05:30', 'value' => '05:30'),
    array('label' => '06:00', 'value' => '06:00'),
    array('label' => '06:30', 'value' => '06:30'),
    array('label' => '07:00', 'value' => '07:00'),
    array('label' => '07:30', 'value' => '07:30'),
)),
    array(
        'label'    => __('End Time', 'cr3at_conf'),
        'desc'    => __('Select the end time. 24-hour clock, 01:00-00:30', 'cr3at_conf'),
        'id'      => 'cr3ativ_confendtime',
        'type'    => 'select',
        'options' => array(
    array('label' => '08:00', 'value' => '08:00'),
    array('label' => '08:30', 'value' => '08:30'),
    array('label' => '09:00', 'value' => '09:00'),
    array('label' => '09:30', 'value' => '09:30'),
    array('label' => '10:00', 'value' => '10:00'),
    array('label' => '10:30', 'value' => '10:30'),
    array('label' => '11:00', 'value' => '11:00'),
    array('label' => '11:30', 'value' => '11:30'),
    array('label' => '12:00', 'value' => '12:00'),
    array('label' => '12:30', 'value' => '12:30'),
    array('label' => '13:00', 'value' => '13:00'),
    array('label' => '13:30', 'value' => '13:30'),
    array('label' => '14:00', 'value' => '14:00'),
    array('label' => '14:30', 'value' => '14:30'),
    array('label' => '15:00', 'value' => '15:00'),
    array('label' => '15:30', 'value' => '15:30'),
    array('label' => '16:00', 'value' => '16:00'),
    array('label' => '16:30', 'value' => '16:30'),
    array('label' => '17:00', 'value' => '17:00'),
    array('label' => '17:30', 'value' => '17:30'),
    array('label' => '18:00', 'value' => '18:00'),
    array('label' => '18:30', 'value' => '18:30'),
    array('label' => '19:00', 'value' => '19:00'),
    array('label' => '19:30', 'value' => '19:30'),
    array('label' => '20:00', 'value' => '20:00'),
    array('label' => '20:30', 'value' => '20:30'),
    array('label' => '21:00', 'value' => '21:00'),
    array('label' => '21:30', 'value' => '21:30'),
    array('label' => '22:00', 'value' => '22:00'),
    array('label' => '22:30', 'value' => '22:30'),
    array('label' => '23:00', 'value' => '23:00'),
    array('label' => '23:30', 'value' => '23:30'),
    array('label' => '00:00', 'value' => '00:00'),
    array('label' => '00:30', 'value' => '00:30'),
    array('label' => '01:00', 'value' => '01:00'),
    array('label' => '01:30', 'value' => '01:30'),
    array('label' => '02:00', 'value' => '02:00'),
    array('label' => '02:30', 'value' => '02:30'),
    array('label' => '03:00', 'value' => '03:00'),
    array('label' => '03:30', 'value' => '03:30'),
    array('label' => '04:00', 'value' => '04:00'),
    array('label' => '04:30', 'value' => '04:30'),
    array('label' => '05:00', 'value' => '05:00'),
    array('label' => '05:30', 'value' => '05:30'),
    array('label' => '06:00', 'value' => '06:00'),
    array('label' => '06:30', 'value' => '06:30'),
    array('label' => '07:00', 'value' => '07:00'),
    array('label' => '07:30', 'value' => '07:30'),
)),
	array(
            'label' => __('Location', 'cr3at_conf'),
            'desc' => __('Enter location.', 'cr3at_conf'),
            'id' => 'cr3ativ_conflocation',
            'type' => 'text',
            'std' => ""
        ),
	array(
            'label' => __('Speaker', 'cr3at_conf'),
            'desc' => __('Select the speakers.', 'cr3at_conf'),
            'id' => 'cr3ativ_confspeaker',
            'type' => 'post_chosen_speaker',
            'std' => ""
    ),
	array(
            'label' => __('Highlight Style', 'cr3at_conf'),
            'desc' => __('Select this checkbox if you would like to have a highlight this session.', 'cr3at_conf'),
            'id' => 'cr3ativ_highlight',
            'type' => 'checkbox',
            'std' => ""
    )
);
 
$cr3ativconference_box = new cr3ativconference_add_meta_box( 'cr3ativconference_box', __('Session Information', 'cr3at_conf'), $cr3ativconference_fields, 'cr3ativconference', true );


add_filter( 'manage_edit-cr3ativconference_columns', 'my_edit_cr3ativconference_columns' ) ;

function my_edit_cr3ativconference_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Session Name', 'cr3at_conf' ),
		'sessiondate' => __( 'Date', 'cr3at_conf' ),
		'sessionstarttime' => __( 'Start Time', 'cr3at_conf' ),
        'sessionendtime' => __( 'End Time', 'cr3at_conf' ),
		'sessionlocation' => __( 'Location' , 'cr3at_conf'),
        'speaker' => __( 'Speakers' , 'cr3at_conf'),
        'session_category' => __( 'Session Category' , 'cr3at_conf')
	);

	return $columns;
}

add_action( 'manage_cr3ativconference_posts_custom_column', 'my_manage_cr3ativconference_columns', 10, 2 );

function my_manage_cr3ativconference_columns( $column, $post_id ) {
	global $post;
            $sessiondatestart = get_post_meta($post->ID, 'cr3ativ_confstarttime', $single = true); 
	        $sessiondateend = get_post_meta($post->ID, 'cr3ativ_confendtime', $single = true); 
	        $sessionlocation = get_post_meta($post->ID, 'cr3ativ_conflocation', $single = true); 
	        $sessionmeetingdate = get_post_meta($post->ID, 'cr3ativconfmeetingdate', $single = true);
            $cr3ativ_confspeakers = get_post_meta($post->ID, 'cr3ativ_confspeaker', $single = true);
	switch( $column ) {

		case 'sessiondate' :
        if ( !empty( $sessionmeetingdate ) ) {
			$dateformat = get_option('date_format');
            echo date($dateformat, $sessionmeetingdate);
        }
			break;
        
		case 'sessionstarttime' :

             printf( $sessiondatestart ); 
			break;

		case 'sessionendtime' :

             printf( $sessiondateend );
			break;

		case 'sessionlocation' :

			 printf( $sessionlocation );
			break;
        
		case 'speaker' :

			 if ( $cr3ativ_confspeakers ) { 
				
	        	foreach ( $cr3ativ_confspeakers as $cr3ativ_confspeaker ) :
	        	
	        		$speaker = get_post($cr3ativ_confspeaker);

	        		echo '<a href="'. admin_url() .'edit.php?post_type=cr3ativspeaker">'. $speaker->post_title .'</a><br/>'; 
				
				endforeach; 
				
			}
			break;
        
		case 'session_category' :

			$terms = get_the_terms( $post_id, 'cr3ativconfcategory' );

			/* If terms were found. */
			if ( !empty( $terms ) ) {

				$out = array();

				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'cr3ativconfcategory' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'cr3ativconfcategory', 'display' ) )
					);
				}

				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}

			break;

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

add_filter( 'manage_edit-cr3ativconference_sortable_columns', 'my_cr3ativconference_sortable_columns' );

function my_cr3ativconference_sortable_columns( $columns ) {

	$columns['sessiondate'] = 'sessiondate';

	return $columns;
}

/* Only run our customization on the 'edit.php' page in the admin. */
add_action( 'load-edit.php', 'my_edit_cr3ativconference_load' );

function my_edit_cr3ativconference_load() {
	add_filter( 'request', 'my_sort_cr3ativconference' );
}

/* Sorts the movies. */
function my_sort_cr3ativconference( $vars ) {

	if ( isset( $vars['post_type'] ) && 'cr3ativconference' == $vars['post_type'] ) {

		if ( isset( $vars['orderby'] ) && 'sessiondate' == $vars['orderby'] ) {

			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'cr3ativconfmeetingdate',
					'orderby' => 'meta_value_num'
				)
			);
		}
	}

	return $vars;
}

////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////      Speaker post type      /////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
add_action('init', 'create_cr3ativspeaker');

function create_cr3ativspeaker() {
 $options = get_option('cr3_conferencesettings_options');
 $authorbox3 = (isset($options['authorbox_template3'])) ? $options['authorbox_template3'] : '';
 $authorbox3 = strip_tags($authorbox3); //sanitise output	
	
	$labels = array(
		'name' => __('Speakers', 'post type general name', 'cr3at_conf'),
		'singular_name' => __('Speaker', 'post type singular name', 'cr3at_conf'),
		'add_new' => __('Add New', 'speaker', 'cr3at_conf'),
		'add_new_item' => __('Add New Speaker', 'cr3at_conf'),
		'edit_item' => __('Edit Speaker', 'cr3at_conf'),
		'new_item' => __('New Speaker', 'cr3at_conf'),
		'view_item' => __('View Speaker', 'cr3at_conf'),
		'search_items' => __('Search Speaker', 'cr3at_conf'),
		'not_found' =>  __('Nothing found', 'cr3at_conf'),
		'not_found_in_trash' => __('Nothing found in Trash', 'cr3at_conf'),
		'parent_item_colon' => __('Speaker', 'cr3at_conf'),
	);
	
    	$cr3ativspeaker_args = array(
        	'labels' => $labels,
        	'public' => true,
            'menu_icon' => 'dashicons-admin-users',
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array('slug' => $authorbox3), 
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => null,
            'show_in_menu' => 'edit.php?post_type=cr3ativconference',
			'supports' => array('title','editor','thumbnail')
        );
    	register_post_type('cr3ativspeaker',$cr3ativspeaker_args);
	}

$cr3ativspeaker_fields = array(
	array(
		'label'	=> __('Speaker Title', 'cr3at_conf'),
		'desc'	=> __('Enter the professional title of this speaker.', 'cr3at_conf'),
		'id'	=> 'speakertitle',
		'type'	=> 'text'
	),
    array(
		'label'	=> __('Speaker Company URL', 'cr3at_conf'),
		'desc'	=> __('Enter the company url, this will appear as a link using the company name listed below on the speaker single page.', 'cr3at_conf'),
		'id'	=> 'speakerurl',
		'type'	=> 'text'
	),
    array(
		'label'	=> __('Speaker Company Name', 'cr3at_conf'),
		'desc'	=> __('Enter the company name, this will appear under the name on the speaker index page and will appear as a link - using the url above on the speaker single page.', 'cr3at_conf'),
		'id'	=> 'speakerurltext',
		'type'	=> 'text'
	),
	array( // Repeatable & Sortable Text inputs
		'label'	=> __('Social Follow', 'cr3at_conf'), // <label>
		'desc'	=> __('Add as many social follows as you would like.', 'cr3at_conf'), // description
		'id'	=> 'speakerrepeatable', // field id and name
		'type'	=> 'repeatable', // type of field
		'sanitizer' => array( // array of sanitizers with matching kets to next array
			'url' => 'sanitize_text_field'
		),
		'repeatable_fields' => array ( // array of fields to be repeated
			array( // Image ID field
				'label'	=> __('Image', 'cr3at_conf'), // <label>
				'id'	=> 'speakerrepeatable_socailimage', // field id and name
				'type'	=> 'image' // type of field
			),
			'url' => array(
				'label' => __('URL', 'cr3at_conf'),
				'id' => 'speakerrepeatable_socailurl',
				'type' => 'url'
			)

		)
	)
);

$cr3ativspeaker_box = new cr3ativconference_add_meta_box( 'cr3ativconference_box', __('Speaker Data', 'cr3at_conf'), $cr3ativspeaker_fields, 'cr3ativspeaker', true );


	
////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////     Custom taxonomies     ///////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
add_action( 'init', 'cr3ativconfcategory', 0 );
function cr3ativconfcategory()	{
 $options = get_option('cr3_conferencesettings_options');
 $authorbox2 = (isset($options['authorbox_template2'])) ? $options['authorbox_template2'] : '';
 $authorbox2 = strip_tags($authorbox2); //sanitise output
	register_taxonomy( 
		'cr3ativconfcategory', 
		'cr3ativconference', 
			array( 
				'hierarchical' => true, 
				'label' => __('Session Category', 'cr3at_conf'),
				'query_var' => true, 
				'rewrite' => array('slug' => $authorbox2), 
			) 
	);
 
}


add_filter( 'manage_edit-cr3ativspeaker_columns', 'my_edit_cr3ativspeaker_columns' ) ;

function my_edit_cr3ativspeaker_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
        'speakerimage' => __( 'Head Shot' , 'cr3at_conf'),
		'title' => __( 'Speaker Name', 'cr3at_conf' ),
        'speakercompanyname' => __( 'Company' , 'cr3at_conf'),
        'speakercompanytitle' => __( 'Title' , 'cr3at_conf'),
        'date' => __( 'Date Added' , 'cr3at_conf')
	);

	return $columns;
}

add_action( 'manage_cr3ativspeaker_posts_custom_column', 'my_manage_cr3ativspeaker_columns', 10, 2 );

function my_manage_cr3ativspeaker_columns( $column, $post_id ) {
	global $post;
            $speakertitle = get_post_meta($post->ID, 'speakertitle', $single = true); 
	        $speakerurltext = get_post_meta($post->ID, 'speakerurltext', $single = true); 
	        $speakerurl = get_post_meta($post->ID, 'speakerurl', $single = true); 
	switch( $column ) {

		case 'speakerimage' :

			 the_post_thumbnail('thumbnail');
			break;
        
		case 'speakercompanyname' :

			 echo '<a href="'. $speakerurl .'">'. $speakerurltext .'</a><br/>'; 
			break;
        
		case 'speakercompanytitle' :

             printf( $speakertitle ); 
			break;


		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}



function cr3ativoderby($orderby) {
    return 'mt1.meta_value, mt2.meta_value, mt3.meta_value ASC';
}
function cr3ativoderby2($orderby) {
    return 'mt1.meta_value, mt2.meta_value ASC';
}


// get taxonomies terms links
function custom_taxonomies_terms_links(){
  // get post by post id
    global $post;
  $post = get_post( $post->ID );

  // get post type by post
  $post_type = $post->post_type;

  // get post type taxonomies
  $taxonomies = get_object_taxonomies( $post_type, 'cr3ativconfcategory' );

  $out = array();
  foreach ( $taxonomies as $taxonomy_slug => $taxonomy ){

    // get the terms related to post
    $terms = get_the_terms( $post->ID, $taxonomy_slug );

    if ( !empty( $terms ) ) {
      $out[] = "" . $taxonomy->label . "&nbsp;:&nbsp;";
      foreach ( $terms as $term ) {
        $out[] =
          '  <a href="'
        .    get_term_link( $term->slug, $taxonomy_slug ) .'">'
        .    $term->name
        . "</a>&nbsp;";
      }
      $out[] = "";
    }
  }

  return implode('', $out );
}



?>