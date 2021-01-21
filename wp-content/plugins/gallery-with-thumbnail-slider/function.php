<?php 
if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

function gwts_gwl_shortcode_gallery_slider($postid){
	$outputgal = '';
	if(!empty($postid)){		
	 	$getimag = get_post_meta($postid,'_gwts_gwl_attachment_id',true);
	 	$getttl = get_post_meta($postid,'_gwts_gallery_title',true);
	 	$getdescription = get_post_meta($postid,'_gwts_gallery_desc',true);
		if(!empty($getimag)){ 
			ob_start(); 
			if(!empty($getttl) || !empty($getdescription)) {?>
			<div class="gwts-gwl-prev-gallery">
				<?php if(!empty($getttl)) { ?>				
				<p class="gwts-gwl-prev-title"><strong><?php echo $getttl;?></strong></p> <?php } ?>
				<?php if(!empty($getdescription)) { ?>
				<p class="gwts-gwl-prev-desc"><?php echo $getdescription;?></p> <?php } ?>
			</div>
			<?php } 
			if(null !== get_post_meta($postid, '_gwtsvertical_gal', true) && !empty(get_post_meta($postid, '_gwtsvertical_gal', true))){
					if( null!== get_post_meta( $postid, '_gwtsVerticalOpt', true )){
					    $VssliderRange = get_post_meta($postid, '_gwtsVerticalOpt', true);
					    $smaxwidth = $VssliderRange[0];
					}
			}
			else{
				$smaxwidth = get_option('gwts_gwl_sliderwidth');
			}?>
		 	<div class="item">            
	        	<div class="clearfix" <?php  
			 	if(!empty($smaxwidth)){ ?>
			 		style="max-width:<?php echo $smaxwidth ?>px;"
			 	<?php } ?>>

			 	<?php $lboxswitchr = get_option('gwts_gwl_lightbx_switcher'); ?>
	            	<ul id="gwts-gwl-img-gallery<?php echo $postid; ?>" class="gwts-gwl-slidergal list-unstyled cS-hidden" data-litebx="<?php if(!empty($lboxswitchr)){echo $lboxswitchr;}else{echo "false";}?>">
				        <?php
					 	foreach ($getimag as $imgvalue) {
					 		$attchimg = wp_get_attachment_image_src($imgvalue,'full');
					 		$thumbnailimg = wp_get_attachment_image_src($imgvalue,'thumbnail');
					 		$image_alt = get_post_meta($imgvalue, '_wp_attachment_image_alt', true);?>
					 		<li data-thumb="<?php echo $thumbnailimg[0]; ?>" data-responsive="<?php echo $thumbnailimg[0]; ?>" data-src="<?php echo $attchimg[0]; ?>"> 
		                    	<img src="<?php echo $attchimg[0]; ?>" alt="<?php echo $image_alt;?>"/>
		                    </li>
					 	<?php } ?>
					</ul>
				</div>
			</div>				
			<?php 
				$gallitms = get_option('gwts_gwl_gallery_numberof_items');
				if(!empty($gallitms)){
					$gallitms = $gallitms;
				}
				else{
					$gallitms = 1;
				}
				$getmargin = get_option('gwts_gwl_slidemargin');
				if(!empty($getmargin)){
					$getmargin = $getmargin;
				}
				else{
					$getmargin = 10;
				}
				$addclss = get_option('gwts_gwl_classtoslider');
				$sliderspd = get_option('gwts_gwl_speedslider');
				if(!empty($sliderspd)){
					$sliderspd = $sliderspd;
				}
				else{
					$sliderspd = 500;
				}
				$spause = get_option('gwts_gwl_slideinterval');
				if(!empty($spause)){
					$spause = $spause*1000;
				}
				else{
					$spause = 2000;
				}
				$smode = get_option('gwts_gwl_slidermode');
				if(!empty($smode)){
					$smode = $smode;
				}
				else{
					$smode = "false";
				}
				$sloop = get_option('gwts_gwl_allow_looping');
				if(!empty($sloop)){
					$sloop = $sloop;
				}
				else{
					$sloop = "false";
				}
				$spager = get_option('gwts_gwl_slider_pagination');
				if(!empty($spager)){
					$spager = $spager;
				}
				else{
					$spager = "true";
				}
				$sgallery = get_option('gwts_gwl_slider_menuoption');
				if(!empty($sgallery)){
					$sgallery = $sgallery;
				}
				else{
					$sgallery = "true";
				}
				$sthumbitem = get_option('gwts_gwl_numberof_thumbitems');
				if(!empty($sthumbitem)){
					$sthumbitem = $sthumbitem;
				}
				else{
					$sthumbitem = 9;
				}
				$s_nav = get_option('gwts_gwl_slider_navigation');
				if(!empty($s_nav)){
					$s_nav = $s_nav;
				}
				else{
					$s_nav = "true";
				}				
				
				/*$s_nav = get_option('gwts_gwl_slider_navigation');
				if(!empty($s_nav)){
					$s_nav = $s_nav;
				}
				else{
					$s_nav = "false";
				}*/
				if(null !== get_post_meta($postid, '_gwtsvertical_gal', true) && !empty(get_post_meta($postid, '_gwtsvertical_gal', true))){
					if( null!== get_post_meta( $postid, '_gwtsVerticalOpt', true )){
					    $sliderRange = get_post_meta($postid, '_gwtsVerticalOpt', true);
					}
					  //print_r($sliderRange);
					  $sliderWidth = !empty($sliderRange) ? $sliderRange[0] : '1100'; 
					  $sliderHeight = !empty($sliderRange) ? $sliderRange[1] : '450';
					  $thumbnlWidth = !empty($sliderRange) ? $sliderRange[2] : '100';
					  $maxThumbItm = !empty($sliderRange) ? $sliderRange[3] : '6';

					  if(!empty(get_post_meta($postid, '_gwtsVerticalcontrl', true))){
					  	$contrlNav = 'true';
					  }
					  else{
					  	$contrlNav = 'false';
					  }
					  
					if( null!== get_post_meta( $postid, '_gwtsSetVertBreakpoints', true )){
					    $sliderBreakpoints = get_post_meta($postid, '_gwtsSetVertBreakpoints', true);
					}  
					$vheight480 = !empty($sliderBreakpoints) ? $sliderBreakpoints[0] : '200'; 
					$vthumb480 = !empty($sliderBreakpoints) ? $sliderBreakpoints[1] : '4';
					
					$vheight641 = !empty($sliderBreakpoints) ? $sliderBreakpoints[2] : '300';
					$vthumb641 = !empty($sliderBreakpoints) ? $sliderBreakpoints[3] : '6';

					$vheight800 = !empty($sliderBreakpoints) ? $sliderBreakpoints[4] : '370';
					$vthumb800 = !empty($sliderBreakpoints) ? $sliderBreakpoints[5] : '6';
				?>
				<script>
				jQuery(document).ready(function() {
	                var count  = 0
	                    if (count === 1) return;
	                    jQuery('#gwts-gwl-img-gallery<?php echo $postid; ?>').addClass('cS-hidden');
	                        jQuery('#gwts-gwl-img-gallery<?php echo $postid; ?>').lightSlider({	
		                        gallery:true,	                        
		                        speed:<?php echo $sliderspd;?>,
		                        auto:<?php echo $smode;?>,
		                        item: 1,
							    loop: <?php echo $sloop;?>,
							    thumbItem: <?php echo $maxThumbItm; ?>,
							    vertical: true,
							    verticalHeight:<?php echo $sliderHeight; ?>,
							    vThumbWidth:<?php echo $thumbnlWidth; ?>,
							    thumbMargin:4,
							    controls:<?php echo $contrlNav; ?>,//navigation
							    responsive : [
						            {
						                breakpoint:800,
						                settings: {
						                    item:1,
						                    slideMove:1,
						                    verticalHeight:<?php echo $vheight800; ?>,
						                    thumbItem:<?php echo $vthumb800; ?>,
						                  }
						            },
						            {
						                breakpoint:641,
						                settings: {
						                    item:1,
						                    slideMove:1,
						                    verticalHeight:<?php echo $vheight641; ?>,
						                    thumbItem:<?php echo $vthumb641; ?>,
						                  }
						            },
						            {
						                breakpoint:480,
						                settings: {
						                    item:1,
						                    slideMove:1,
						                    verticalHeight:<?php echo $vheight480; ?>,
						                    thumbItem:<?php echo $vthumb480; ?>,
						                  }
						            },						           
						        ],

		                        onSliderLoad: function(obj) {
		                            jQuery('#gwts-gwl-img-gallery<?php echo $postid; ?>').removeClass('cS-hidden');
		                            var lithbox = jQuery('#gwts-gwl-img-gallery<?php echo $postid;?>').attr("data-litebx");
									if(lithbox=='true'){
										obj.lightGallery({
							                selector: '#gwts-gwl-img-gallery<?php echo $postid;?> .lslide'
							            });
									}            
	                        } 
	                    });
	                    count++;
	            });
	        	</script>
				<?php } else { ?>
				<script>
		    	jQuery(document).ready(function() {
		            jQuery('#gwts-gwl-img-gallery<?php echo $postid; ?>').lightSlider({
		                item:<?php echo $gallitms;?>,		                
		                slideMargin:<?php echo $getmargin;?>,
		                addClass:'<?php echo $addclss;?>',
		                speed:<?php echo $sliderspd;?>,
		                pause:<?php echo $spause;?>,
		                auto:<?php echo $smode;?>,
		                loop:<?php echo $sloop;?>,
		                pager:<?php echo $spager;?>,
		                gallery:<?php echo $sgallery;?>,
		                thumbItem:<?php echo $sthumbitem;?>,
	    				controls:<?php echo $s_nav;?>,
	    				useCSS: true,
	        			cssEasing: 'ease',
	        			easing: 'linear',
	        			keyPress: false,
	        			slideEndAnimation: true,
	        			swipeThreshold: 40,        			
		                onSliderLoad: function(el) {
		                    jQuery('#gwts-gwl-img-gallery<?php echo $postid;?>').removeClass('cS-hidden');
		                    jQuery('#gwts-gwl-img-gallery<?php echo $postid;?>').addClass('gwts-loaded');
							var maxHeight = 0,
							container = jQuery(el),
							children = container.children();

							children.each(function () {
							var childHeight = jQuery(this).height();
							if (childHeight > maxHeight) {
							maxHeight = childHeight;
							}
							});
							container.height(maxHeight);
							
							var lithbox = jQuery('#gwts-gwl-img-gallery<?php echo $postid;?>').attr("data-litebx");
							if(lithbox=='true'){
								el.lightGallery({
					                selector: '#gwts-gwl-img-gallery<?php echo $postid;?> .lslide'
					            });
							}	
		                }  
			        });
				});
		    	</script>

		 <?php }
		 $outputgal = ob_get_clean();			 
		}		
	}
	return $outputgal;
}

/* Gallery thumbnail grid shortcode */
function gwts_gwl_shortcode_display_gallery_list($no_of_items){
	$outputgallery = '';
	$argary = array(
		'posts_per_page' => $no_of_items,
		'post_type'	=>	'gwts-gallery',
		'post_status'	=> 'publish',
		);
	$getgallery = new WP_Query($argary);	
	if($getgallery->have_posts()){
		ob_start();
		echo '<div class="gwts-gwl-gallery-listings"><ul id="gwts-gwl-thumbrig">';		
		while ($getgallery->have_posts()) {
			$getgallery->the_post();
			if ( has_post_thumbnail() ) { ?>
				<li>
        			<a class="gwts-gwl-thumbrig-cell" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" target="_blank">
        				<?php the_post_thumbnail('thumbnail', array( 'class' => 'gwts-gwl-thumbrig-img' )); ?>
		            	<span class="gwts-gwl-thumbrig-overlay"></span>
		            	<span class="gwts-gwl-thumbrig-text"><?php the_title_attribute(); ?></span>
		            </a>
		        </li>				  			
    			<?php   			
			}
			else { ?>
				<li>
        			<a class="gwts-gwl-thumbrig-cell" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" target="_blank">
        				<img class="gwts-gwl-thumbrig-img" src="<?php echo GWTS_GWL_PLUGINURL; ?>includes/images/thumbnail.png" alt="img"/>
		            	<span class="gwts-gwl-thumbrig-overlay"></span>
		            	<span class="gwts-gwl-thumbrig-text"><?php the_title_attribute(); ?></span>
		            </a>
		        </li>
			<?php
			}
			
		}
		echo '<div class="clear clearfix"></div>';
		echo '</div></ul>';
		?>		

		<?php
		$outputgallery = ob_get_clean();
	}	
	return $outputgallery;
}


