<?php 
/* Plugin Name: Gallery with thumbnail slider
* Description: Gallery with thumbnail slider(GWTS) is a very nifty responsive gallery plugin that helps you put images and slideshows wherever you need. The plugin is highly customizable. You can adjust size, style, timing, transitions, controls, lightbox effects, vertical gallery and more as per your needs.
* Plugin URI: https://wordpress.org/plugins/gallery-with-thumbnail-slider
* Author: Galaxy Weblinks
* Author URI: http://galaxyweblinks.com
* Version: 4.0
* Text Domain: gallery-with-thumbnail-slider
* License:GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

if(!defined('GWTS_GWL_PLUGINURL')){
	define('GWTS_GWL_PLUGINURL', plugin_dir_url(__FILE__));
}
if(!defined('GWTS_GWL_PLUGINPATH')){
	define('GWTS_GWL_PLUGINPATH', plugin_dir_path(__FILE__));
}

require_once(GWTS_GWL_PLUGINPATH.'gwts-metabox.php');
require_once(GWTS_GWL_PLUGINPATH.'gwts-slider.php');
require_once(GWTS_GWL_PLUGINPATH.'gwts-shortcode.php');
require_once(GWTS_GWL_PLUGINPATH.'function.php');
require_once(GWTS_GWL_PLUGINPATH.'gwts-custom-posttype-gallery.php');
require_once(GWTS_GWL_PLUGINPATH.'gwts-vertical-slider.php');

//admin notice when activate plugin
register_activation_hook(__FILE__, 'gwts_gwl_adminnotice');
function gwts_gwl_adminnotice(){
	update_option('gwts_gwl_gallery_notice','enabled');
}
function gwts_gwl_gallery_admin_notice__success() {
	if(get_option('gwts_gwl_gallery_notice') == 'enabled'){
    	?>
	
    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'To view gallery setting please ', 'gallery-with-thumbnail-slider' ); ?><a href="<?php echo admin_url('edit.php?post_type=gwts-gallery&page=gwts-opts'); ?>"><?php _e( 'click here', 'gallery-with-thumbnail-slider' ); ?></a></p>
    </div>
    <?php 
	delete_option('gwts_gwl_gallery_notice');
	}
}
add_action( 'admin_notices', 'gwts_gwl_gallery_admin_notice__success' );


//Add setings link in the plugins page (beside the activate/deactivate links)
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'gwts_add_action_settings_link' );
function gwts_add_action_settings_link ( $links ) {
 $mylinks = array(
 '<a href="' . admin_url( 'edit.php?post_type=gwts-gallery&page=gwts-slider-settings' ) . '">Settings</a>',
 );
return array_merge( $links, $mylinks );
}


// Create admin menu for gallery
add_action('admin_menu','gwts_gwl_adminmenu');
function gwts_gwl_adminmenu(){
	add_menu_page(__( 'GWTS Gallery', 'gallery-with-thumbnail-slider' ),__( 'GWTS Gallery', 'gallery-with-thumbnail-slider' ),'manage_options','edit.php?post_type=gwts-gallery',NULL);
	add_submenu_page('edit.php?post_type=gwts-gallery',__( 'Enable Gallery', 'gallery-with-thumbnail-slider' ),__( 'Gallery Options', 'gallery-with-thumbnail-slider' ),'manage_options','gwts-opts','gwts_gwl_slider_option_fuction');
	add_submenu_page('edit.php?post_type=gwts-gallery',__( 'Slider Settings', 'gallery-with-thumbnail-slider' ),__( 'Slider Settings', 'gallery-with-thumbnail-slider' ),'manage_options','gwts-slider-settings','gwts_gwl_gallery_option_fuction');
	add_action( 'admin_init', 'gwlts_gwl_gallery_plugin_settings' );
	add_action( 'admin_init', 'gwlts_gwl_reg_galleryoption_plugin_settings' );

}
function gwlts_gwl_reg_galleryoption_plugin_settings()
{
register_setting( 'gwlts-gwl-gallery-options-group', 'gwts_gwl_posttypes' );

}
function gwts_gwl_slider_option_fuction(){
	if( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true'):
   echo    '<div id="setting-error-settings_updated" class="updated settings-error"> 
<p><strong>Settings saved.</strong></p></div>';
endif;


echo _e( '<h3>Enable The Image Gallery Slider For Post Types</h3>', 'gallery-with-thumbnail-slider' );

	
	$getarg = array(
		'public'	=> true,
		'_builtin'	=>	true
		);
	$getptyp = get_post_types($getarg,'names','or');
	$getopt = get_option('gwts_gwl_posttypes');
	$searcharry = array('attachment','revision','nav_menu_item','custom_css','customize_changeset','gwts-gallery','oembed_cache','user_request','wp_block');

	echo '<form method="post" action="options.php">';
	settings_fields( 'gwlts-gwl-gallery-options-group' ); 
   do_settings_sections( 'gwlts-gwl-gallery-options-group' );  
	if(!empty($getptyp)){
		$counterid = 1;
		foreach ($getptyp as $gttype) {
			if(!in_array($gttype, $searcharry)){ ?>
				<div class="postype-sec"><span class="postype-ttl"><?php echo $gttype; ?></span>
					<label class="switch">
						<input type="checkbox" id="togBtn-<?php echo $counterid; ?>" name="gwts_gwl_posttypes[]" value="<?php echo $gttype; ?>" <?php if(!empty($getopt)){ 
					if(in_array($gttype, get_option('gwts_gwl_posttypes'))){ echo "checked"; }} ?> ><div class="gwtssetopt slider round"></div>
					</label>
				</div>
			<?php $counterid++; }
		}		
	}
submit_button();
	wp_nonce_field( basename(__FILE__), 'gwts_gwl_enable_post_type_nonce' );

	echo '</form>';	
	echo '<hr>';
	echo _e( '<h3>Use the shortcode to display galleries listing. </h3>[gwts_gwl_galleries_listing no_of_items=12]<p>Change the "no_of_items" value in the shortcode above to display items in the gallery.</p>', 'gallery-with-thumbnail-slider' );

}
function gwlts_gwl_gallery_plugin_settings() {
    //register our settings
    register_setting( 'gwlts-gwl-gallery-settings-group', 'gwts_gwl_gallery_numberof_items' );
    register_setting( 'gwlts-gwl-gallery-settings-group', 'gwts_gwl_slidemargin' );
    register_setting( 'gwlts-gwl-gallery-settings-group', 'gwts_gwl_classtoslider' );
    register_setting( 'gwlts-gwl-gallery-settings-group', 'gwts_gwl_speedslider' );
    register_setting( 'gwlts-gwl-gallery-settings-group', 'gwts_gwl_slideinterval' );
    register_setting( 'gwlts-gwl-gallery-settings-group', 'gwts_gwl_slidermode' );
    register_setting( 'gwlts-gwl-gallery-settings-group', 'gwts_gwl_allow_looping' );
    register_setting( 'gwlts-gwl-gallery-settings-group', 'gwts_gwl_slider_navigation' );
    register_setting( 'gwlts-gwl-gallery-settings-group', 'gwts_gwl_slider_menuoption' );
    register_setting( 'gwlts-gwl-gallery-settings-group', 'gwts_gwl_numberof_thumbitems' );
    register_setting( 'gwlts-gwl-gallery-settings-group', 'gwts_gwl_sliderwidth' );
    register_setting( 'gwlts-gwl-gallery-settings-group', 'gwts_gwl_slider_pagination' );
    register_setting( 'gwlts-gwl-gallery-settings-group', 'gwts_gwl_lightbx_switcher' );    
}  


/* Sub Menu Setting Callback function */
function gwts_gwl_gallery_option_fuction(){ 
	if( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true'):
   echo    '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
		<p><strong>Settings saved.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
endif;

 	$getmargin = get_option('gwts_gwl_slidemargin');
	$smode = get_option('gwts_gwl_slidermode');
	$sloop = get_option('gwts_gwl_allow_looping');
	$snav = get_option('gwts_gwl_slider_navigation');
	$spager = get_option('gwts_gwl_slider_pagination');	
	$sgallery = get_option('gwts_gwl_slider_menuoption');
	$sthumbitem = get_option('gwts_gwl_numberof_thumbitems');
	$lboxswitchr = get_option('gwts_gwl_lightbx_switcher');
?>
<h3><?php _e( 'Slider Global Settings', 'gallery-with-thumbnail-slider' ); ?></h3>
<form method="POST" action="options.php">
	<?php settings_fields( 'gwlts-gwl-gallery-settings-group' ); ?>
    <?php do_settings_sections( 'gwlts-gwl-gallery-settings-group' ); ?>
 <div class="mainopt">
 <div id="gwts-gwl-sec1" class="gwts-gwl-sec1">
	 <p id="gwts-gwl-selslid" class="gwts-settings-opts"><lable for="gwts_gwl_gallery_numberof_items"><?php _e( 'Display slide(s) :', 'gallery-with-thumbnail-slider' ); ?> </lable>
		 <select name="gwts_gwl_gallery_numberof_items" id="gwts-gwl-galleryitems">
		 <?php $gallitms = get_option('gwts_gwl_gallery_numberof_items');?>
		 	<option value="1" <?php if($gallitms ==1){echo "selected";} ?>>1</option>
		 	<option value="2" <?php if($gallitms ==2){echo "selected";} ?>>2</option>
		 	<option value="3" <?php if($gallitms ==3){echo "selected";} ?>>3</option>
		 	<option value="4" <?php if($gallitms ==4){echo "selected";} ?>>4</option>
		 	<option value="5" <?php if($gallitms ==5){echo "selected";} ?>>5</option>
		 	<option value="6" <?php if($gallitms ==6){echo "selected";} ?>>6</option>
		 </select>
	 </p>
	 <p class="gwts-settings-opts" id="gwts-gwl-slidergap" <?php if($gallitms == "1" || $gallitms == ""){ ?>style="display: none;" <?php } ?>><lable for="gwts_gwl_slidemargin"><?php _e( 'Margin Between slides : ', 'gallery-with-thumbnail-slider' ); ?></lable><input type="number" min="1" max="200" id="gwts-gwl-marginslide" name="gwts_gwl_slidemargin" placeholder="Default 10px" value="<?php if(!empty($getmargin)){echo $getmargin;} ?>"></p>
</div>
 <p class="gwts-settings-opts"><lable for="gwts_gwl_classtoslider"><?php _e( 'Add class in slider : ', 'gallery-with-thumbnail-slider' ); ?></lable><input type="text" placeholder="Add custom class" name="gwts_gwl_classtoslider" value="<?php echo get_option('gwts_gwl_classtoslider'); ?>"></p>

 <p class="gwts-settings-opts"><lable for="gwts_gwl_sliderwidth"><?php _e( 'Slider Max width ( px ) : ', 'gallery-with-thumbnail-slider' ); ?></lable><input type="number" min="200" max="2000" placeholder="Default full width" name="gwts_gwl_sliderwidth" value="<?php echo get_option('gwts_gwl_sliderwidth'); ?>"></p>
 
 <hr>
 <p class="gwts-settings-opts"><lable for="gwts_gwl_speedslider"><?php _e( 'Slider Speed ( ms ) : ', 'gallery-with-thumbnail-slider' ); ?></lable><input type="number" min="200" max="1500" placeholder="Default speed 500" name="gwts_gwl_speedslider" value="<?php if(!empty(get_option('gwts_gwl_speedslider'))){ echo get_option('gwts_gwl_speedslider'); } ?>"></p>
 
 <p class="gwts-settings-opts"><lable for="gwts_gwl_slideinterval"><?php _e( 'Slide Interval/Pause ( seconds ) : ', 'gallery-with-thumbnail-slider' ); ?></lable>

 <input type="number" min="2" max="300" placeholder="Default 2 sec." name="gwts_gwl_slideinterval" value="<?php if(!empty(get_option('gwts_gwl_slideinterval'))){ echo get_option('gwts_gwl_slideinterval'); } ?>"></p>
 
 <!-- Slider Navigation -->
 <p class="gwts-settings-opts"><lable for="gwts_gwl_slider_navigation"><?php _e( 'Slider Navigation : ', 'gallery-with-thumbnail-slider' ); ?></lable>
	<select name="gwts_gwl_slider_navigation" id="gwts-gwl-slidernav">
	 	<option value="true" <?php if($snav == "true"){echo "selected";} ?>>True</option>
	 	<option value="false" <?php if($snav == "false"){echo "selected";} ?>>False</option>
	 </select>
 </p>
 <!-- Slider pagination -->
 
 <p class="gwts-settings-opts"><lable for="gwts_gwl_slider_pagination"><?php _e( 'Slider pagination : ', 'gallery-with-thumbnail-slider' ); ?></lable>
	<select name="gwts_gwl_slider_pagination" id="gwts-gwl-sliderpager">
	 	<option value="true" <?php if($spager == "true"){echo "selected";} ?>>True</option>
	 	<option value="false" <?php if($spager == "false"){echo "selected";} ?>>False</option>
	 </select>
 </p>

<div class="gwts-gwl-pageroption" id="gwts-gwl-pageroption" <?php if($spager == "false") {?> style="display:none" <?php }?>>
 <p class="gwts-settings-opts"><lable for="gwts_gwl_slider_menuoption"><?php _e( 'Select Menu Options : ', 'gallery-with-thumbnail-slider' ); ?></lable>
	<select name="gwts_gwl_slider_menuoption" id="gwts-gwl-showgallery-menu">
	 	<option value="true" <?php if($sgallery == "true"){echo "selected";} ?>>Show Thumbnail</option>
	 	<option value="false" <?php if($sgallery == "false"){echo "selected";} ?>>Show Dot pagination</option>
	 </select>
 </p>
 <p class="gwts-settings-opts" id="gwts-gwl-slider-thumbitems" <?php if($sgallery == "false") {?> style="display:none" <?php }?>><lable for="gwts_gwl_numberof_thumbitems"><?php _e( 'Number of thumbnails : ', 'gallery-with-thumbnail-slider' ); ?></lable><input id="gwts-gwl-thumbnailitems" type="number" min="7" max="15" name="gwts_gwl_numberof_thumbitems" placeholder="Default 9" value="<?php if(!empty($sthumbitem)){echo $sthumbitem;} ?>">
 </p>

</div>
<hr>
<!-- Enable autometic slide -->

<div class="gwts-settings-opts">
<lable for="gwts_gwl_lightbx_switcher"><?php _e( 'Enable Auto Slide: ', 'gallery-with-thumbnail-slider' ); ?></lable>

 <label class="switch">
	<input type="checkbox" id="togBtn-ltbox" name="gwts_gwl_slidermode" value="true"<?php if(!empty($smode)){ echo "checked"; } ?>>
	<div class="gwtssetopt slider round"></div>
</label>
</div>

<!-- Enable loop slider -->

<div class="gwts-settings-opts">
<lable for="gwts_gwl_lightbx_switcher"><?php _e( 'Enable Loop Slide: ', 'gallery-with-thumbnail-slider' ); ?></lable>

 <label class="switch">
	<input type="checkbox" id="gwts_gwl_allow_looping" name="gwts_gwl_allow_looping" value="true"<?php if(!empty($sloop)){ echo "checked"; } ?>>
	<div class="gwtssetopt slider round"></div>
</label>
</div>

<!-- Enable Lightbox slider -->

<div class="gwts-settings-opts">
<lable for="gwts_gwl_lightbx_switcher"><?php _e( 'Enable Lightbox Slider : ', 'gallery-with-thumbnail-slider' ); ?></lable>

 <label class="switch">
	<input type="checkbox" id="togBtn-ltbox" name="gwts_gwl_lightbx_switcher" value="true"<?php if(!empty($lboxswitchr)){ echo "checked"; } ?>>
	<div class="gwtssetopt slider round"></div>
</label>
</div>

 <?php submit_button(__("Save Settings","gallery-with-thumbnail-slider"),'primary','slidersettings'); ?>
 </div>
 <?php 
 wp_nonce_field( basename(__FILE__), 'gwts_gwl_enable_slider_setting_nonce' ); ?>
</form>

<script>
jQuery(document).ready(function(){
	jQuery("#gwts-gwl-galleryitems").change(function(){
		var thisval = jQuery(this).val();
		if(thisval == 1){
			jQuery("#gwts-gwl-slidergap").css('display', 'none');
		}
		else{
			jQuery("#gwts-gwl-slidergap").css('display', 'block');
		}
	});
	//show thumnail option
	jQuery("#gwts-gwl-showgallery-menu").change(function(){
		var showgal = jQuery(this).val();
		if(showgal == "false"){
			jQuery("#gwts-gwl-slider-thumbitems").css('display', 'none');
		}
		else{
			jQuery("#gwts-gwl-slider-thumbitems").css('display', 'block');
		}
	});	

	//pager setting
	jQuery("#gwts-gwl-sliderpager").change(function(){
		var sliderpager = jQuery(this).val();
		if(sliderpager == "false"){
			jQuery("#gwts-gwl-pageroption").css('display', 'none');
		}
		else{
			jQuery("#gwts-gwl-pageroption").css('display', 'block');
		}
	});
});
</script>

<?php }

/* Register jquery for sorting items in gallery*/
function gwts_gwl_gallery_enqueue_script(){
	wp_enqueue_script('gwts-gwl-galleryjs', GWTS_GWL_PLUGINURL.'includes/js/gwts-gallery.js', array('jquery'));
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_style('gwts-gwl-style-css', GWTS_GWL_PLUGINURL.'includes/css/gwts-adminstyle.css');	
	//register vertical script
	wp_register_script( 'gwts-gwl-veticlegal', GWTS_GWL_PLUGINURL.'includes/js/gwts-vertical-gallery-slider.js', array('jquery') );
}
add_action('admin_enqueue_scripts','gwts_gwl_gallery_enqueue_script');


//enqueue script for front end
function gwts_gwl_frontend_enqueue_script(){	
	wp_enqueue_script('gwts-gwl-lightslider', GWTS_GWL_PLUGINURL.'includes/js/lightslider.js', array('jquery'));	
	wp_enqueue_style('gwts-gwl-lightslider-css', GWTS_GWL_PLUGINURL.'includes/css/lightslider.css');
	wp_enqueue_style('gwts-gwl-style-css', GWTS_GWL_PLUGINURL.'includes/css/gwts-style.css');

	wp_enqueue_style('gwts-gwl-lightgal-css', GWTS_GWL_PLUGINURL.'includes/css/lightgallery.css');
	
	wp_enqueue_script('gwts-gwl-cdngal', GWTS_GWL_PLUGINURL.'includes/js/picturefill.min.js', array('jquery'));
	
	wp_enqueue_script('gwts-gwl-lightgallry', GWTS_GWL_PLUGINURL.'includes/js/lightgallery-all.min.js', array('jquery'));
	wp_enqueue_script('gwts-gwl-mousewheel', GWTS_GWL_PLUGINURL.'includes/js/jquery.mousewheel.min.js', array('jquery'));
}
add_action('wp_enqueue_scripts','gwts_gwl_frontend_enqueue_script');
