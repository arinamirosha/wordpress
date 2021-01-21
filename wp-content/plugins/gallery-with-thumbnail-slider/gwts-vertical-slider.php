<?php
/**
 * Register meta box(es).
 */
if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

/* Register metabox for vertical slider settings */
function gwts_gwl_gallery_register_meta_boxes_verticalslide() {
	$getpostopt = get_option('gwts_gwl_posttypes');
	$getpostid = get_the_ID();	
	$getpostyp = get_post_type($getpostid);
	if(!empty($getpostopt)){
		if (in_array($getpostyp, $getpostopt)){
			add_meta_box( 'gwts-gwl-vslider-settng', __( 'Vertical Thumb Gallery Settings', 'gallery-with-thumbnail-slider' ), 'gwts_gwl_gallery_display_callback_for_verticalslid',$getpostyp, 'side' );
		}		
	}
	add_meta_box( 'gwts-gwl-vslider-settng', __( 'Vertical Thumb Gallery Settings', 'gallery-with-thumbnail-slider' ), 'gwts_gwl_gallery_display_callback_for_verticalslid','gwts-gallery', 'side' );
}
add_action( 'add_meta_boxes', 'gwts_gwl_gallery_register_meta_boxes_verticalslide' );

function gwts_gwl_gallery_display_callback_for_verticalslid($post){ 
	wp_enqueue_script( 'gwts-gwl-veticlegal' );
	if( null!== get_post_meta( get_the_ID(), '_gwtsVerticalOpt', true )){
	    $sliderRange = get_post_meta(get_the_ID(), '_gwtsVerticalOpt', true);
	  }
	  $sliderWidth = !empty($sliderRange) ? $sliderRange[0] : '1100'; 
	  $sliderHeight = !empty($sliderRange) ? $sliderRange[1] : '450';
	  $thumbnlWidth = !empty($sliderRange) ? $sliderRange[2] : '100';
	  $maxThumbItm = !empty($sliderRange) ? $sliderRange[3] : '6';

	if( null!== get_post_meta( get_the_ID(), '_gwtsSetVertBreakpoints', true )){
	    $sliderBreakpoints = get_post_meta(get_the_ID(), '_gwtsSetVertBreakpoints', true);
	}
	$vheight480 = !empty($sliderBreakpoints) ? $sliderBreakpoints[0] : '200'; 
	$vthumb480 = !empty($sliderBreakpoints) ? $sliderBreakpoints[1] : '4';
	
	$vheight641 = !empty($sliderBreakpoints) ? $sliderBreakpoints[2] : '300';
	$vthumb641 = !empty($sliderBreakpoints) ? $sliderBreakpoints[3] : '6';

	$vheight800 = !empty($sliderBreakpoints) ? $sliderBreakpoints[4] : '370';
	$vthumb800 = !empty($sliderBreakpoints) ? $sliderBreakpoints[5] : '6';

	 
	?>	
	<div class="gwts-vertcleslide-container">	
		<!-- Enable vertical gallery -->
		<p class="enble_ver_box"><label for="vertical_slider"><strong><?php _e('Enable Vertical Gallery','gallery-with-thumbnail-slider'); ?></strong></label>
				<input class="gwts-verti-checkbx" type="checkbox" name="enable_vrticle_gal" value="1" <?php if(!empty(get_post_meta($post->ID, '_gwtsvertical_gal', true))){echo "checked"; } ?>>			
		</p>
		<p><small><?php _e('To better view of the slider, use constant images size and customize below options according to them. All these options work when the vertical gallery is enabled.', 'gallery-with-thumbnail-slider'); ?></small></p>
	    <!-- slider width -->
	    <p><strong><?php _e('Slider Width: ', 'gallery-with-thumbnail-slider');?></strong><span id="gwts_displyVert_wdth"></span>px</p>
	      <input type="range" name="gwtsVerticalOpt[]" min="320" max="2200" value="<?php echo $sliderWidth; ?>" class="gwts-opt-vtcle" id="gwts_vrt_width">

	      <!-- slider height -->
	      <p><strong><?php _e('Vertical Height: ', 'gallery-with-thumbnail-slider');?></strong><span id="gwts_Vslide_height"></span>px</p>
	      <input type="range" name="gwtsVerticalOpt[]" min="100" max="900" value="<?php echo $sliderHeight; ?>" class="gwts-opt-vtcle" id="gwts_vrt_height">

	      <!-- Thumb Width -->
	      <p><strong><?php _e('Thumbnail Width: ', 'gallery-with-thumbnail-slider');?></strong><span id="gwts_thumbVWidt"></span>px</p>
	      <input type="range" name="gwtsVerticalOpt[]" min="50" max="200" value="<?php echo $thumbnlWidth; ?>" class="gwts-opt-vtcle" id="gwts_thmb_width">

	      <!-- Thumb Item -->
	    <p><strong><?php _e('Show Thumbnail: ', 'gallery-with-thumbnail-slider');?></strong><span id="gwts_show_maxthumb"></span></p>
	      <input type="range" name="gwtsVerticalOpt[]" min="2" max="20" value="<?php echo $maxThumbItm; ?>" class="gwts-opt-vtcle" id="gwts_max_thumb">  

	      <!-- Navigation -->
	    <p class="enble_ver_box"><label for="vertical_slider"><?php _e('Show Next/Prev Arrows','gallery-with-thumbnail-slider'); ?></label>
			<input class="gwts-verti-checkbx" type="checkbox" name="gwtsVerticalcontrl" value="1" <?php if(!empty(get_post_meta($post->ID, '_gwtsVerticalcontrl', true))){echo "checked"; } ?>>
		</p>
		<hr/>
		<p class="enble_ver_box gwts-rsponsivmode"><strong><label><?php _e('Set Different breakpoints for responsive Gallery ', 'gallery-with-thumbnail-slider');?></label></strong></p>
		<small><?php _e('Set gallery size in the different breakpoints.', 'gallery-with-thumbnail-slider'); ?></small>

		<!-- Set breakpoints to 480px  -->
		<p><strong><?php _e('Breakpoint 480px', 'gallery-with-thumbnail-slider'); ?></strong></p>
		<p><?php _e('Vertical Height: ', 'gallery-with-thumbnail-slider');?><span id="gwts_Vslide_brkheight"></span>px</p>
	    <input type="range" name="gwtsSetVertBreakpoints[]" min="100" max="900" value="<?php echo $vheight480; ?>" class="gwts-opt-vtcle" id="gwts_brekvrt_height">
	    
	    <p><?php _e('Thumb Items: ', 'gallery-with-thumbnail-slider');?><span id="gwts_show_brkmaxthumb"></span></p>
	    <input type="range" name="gwtsSetVertBreakpoints[]" min="2" max="20" value="<?php echo $vthumb480; ?>" class="gwts-opt-vtcle" id="gwts_max_brkthumb">  

	    <!-- Set breakpoints to 641px  -->  
		<p><strong><?php _e('Breakpoint 641px', 'gallery-with-thumbnail-slider'); ?></strong></p>
		<p><?php _e('Vertical Height: ', 'gallery-with-thumbnail-slider');?><span id="gwts_Vslide_sixfouroneheight"></span>px</p>
	    <input type="range" name="gwtsSetVertBreakpoints[]" min="100" max="900" value="<?php echo $vheight641; ?>" class="gwts-opt-vtcle" id="gwts_vrt_sixfoheight">
	    
	    <p><?php _e('Thumb Items: ', 'gallery-with-thumbnail-slider');?><span id="gwts_show_sixfomaxthumb"></span></p>
	    <input type="range" name="gwtsSetVertBreakpoints[]" min="2" max="20" value="<?php echo $vthumb641; ?>" class="gwts-opt-vtcle" id="gwts_max_sixforthumb">  

		<!-- Set breakpoints to 800px  -->
		<p><strong><?php _e('Breakpoint 800px', 'gallery-with-thumbnail-slider'); ?></strong></p>
		<p><?php _e('Vertical Height: ', 'gallery-with-thumbnail-slider');?><span id="gwts_Vslide_eightheight"></span>px</p>
	    <input type="range" name="gwtsSetVertBreakpoints[]" min="100" max="900" value="<?php echo $vheight800; ?>" class="gwts-opt-vtcle" id="gwts_vrt_eightheight">
	    
	    <p><?php _e('Thumb Items: ', 'gallery-with-thumbnail-slider');?><span id="gwts_show_eightmaxthumb"></span></p>
	    <input type="range" name="gwtsSetVertBreakpoints[]" min="2" max="20" value="<?php echo $vthumb800; ?>" class="gwts-opt-vtcle" id="gwts_max_eigthumb">  
	</div>	
<?php }

function gwts_gwl_vertical_gallery_callback($post_id){
	//show vertical gallery

	if(isset($_POST['enable_vrticle_gal'])){
		update_post_meta($post_id,'_gwtsvertical_gal', sanitize_text_field($_POST['enable_vrticle_gal']));		
	}
	else{
		update_post_meta($post_id,'_gwtsvertical_gal', '');
	}
	//show navigation
	if(isset($_POST['gwtsVerticalcontrl'])){
		update_post_meta($post_id,'_gwtsVerticalcontrl', sanitize_text_field($_POST['enable_vrticle_gal']));		
	}
	else{
		update_post_meta($post_id,'_gwtsVerticalcontrl', '');
	}
	//update settings
	if(isset($_POST['gwtsVerticalOpt'])){
		update_post_meta($post_id, '_gwtsVerticalOpt', $_POST['gwtsVerticalOpt']);
	}
	//update breakpoints
	if(isset($_POST['gwtsSetVertBreakpoints'])){
		update_post_meta($post_id, '_gwtsSetVertBreakpoints', $_POST['gwtsSetVertBreakpoints']);
	}


}
add_action('save_post','gwts_gwl_vertical_gallery_callback');