<?php get_header(); 

global $options;
$headerFormat = (!empty($options['header_format'])) ? $options['header_format'] : 'default';
$theme_skin = ( !empty($options['theme-skin']) ) ? $options['theme-skin'] : 'original';
if($headerFormat == 'centered-menu-bottom-bar') $theme_skin = 'material';

$search_results_layout = ( !empty($options['search-results-layout']) ) ? $options['search-results-layout'] : 'default';
$search_results_header_bg_color = ( !empty($options['search-results-header-bg-color']) ) ? $options['search-results-header-bg-color'] : '#f4f4f4';
$search_results_header_font_color = ( !empty($options['search-results-header-font-color']) ) ? $options['search-results-header-font-color'] : '#000000';
$search_results_header_bg_image = ( !empty($options['search-results-header-bg-image']) && isset($options['search-results-header-bg-image']) ) ? nectar_options_img($options['search-results-header-bg-image']) : null;


?>

<?php 

						/// For Filters ///
	$query_string = $_SERVER['QUERY_STRING'];
	$posts_10=add_query_arg( 'posts_per_page', 10, $query_string );
	$posts_20=add_query_arg( 'posts_per_page', 20, $query_string );
	$posts_30=add_query_arg( 'posts_per_page', 30, $query_string );	
	
	/*
	$ref_url = $_SERVER['HTTP_REFERER'];//getting referral url
	$ref_url_final =substr_replace($ref_url ,"",-1); //removed last slash from url
	$page_slug_val = substr($ref_url_final, strrpos($ref_url_final, '/') + 1); //Get characters after last / in url
	*/		
?>

<div id="page-header-bg" data-midnight="light" data-text-effect="none" data-bg-pos="center" data-alignment="center" data-alignment-v="middle" data-height="250" style="background-color: <?php echo $search_results_header_bg_color;?>;">
	
	<?php if($search_results_header_bg_image) { ?>
		<div class="page-header-bg-image-wrap" id="nectar-page-header-p-wrap">
			<div class="page-header-bg-image" style="background-image: url(<?php echo $search_results_header_bg_image; ?>);"></div>
	  </div> 
		
		<div class="page-header-overlay-color" style="background-color: #333333;"></div> 
	<?php } ?>
	
	<div class="container">
				 <div class="row">
					<div class="col span_6 ">
						<div class="inner-wrap">
							<h1 style="color: <?php echo $search_results_header_font_color; ?>;"><?php echo __('Results For', 'salient'); ?> <span>"<?php echo esc_html( get_search_query( false ) ); ?>"</span></h1>	
							<?php if($wp_query->found_posts) { echo '<span class="result-num" style="color: '.$search_results_header_font_color.';">' . $wp_query->found_posts . ' '. __('results found', 'salient').'</span>'; } ?>							
						</div>
					</div>
				</div>
	</div>
</div>


<div class="container-wrap" data-layout="<?php echo $search_results_layout; ?>">
	<div class="container main-content">

		<div class="row">
			
			<?php $search_col_span = ($search_results_layout == 'default') ? '9' : '12'; ?>
			<div class="col span_<?php echo $search_col_span; ?>">
				
				<div id="search-results" data-layout="<?php echo $search_results_layout; ?>">
				<div class="search-results-header">		
						<?php
										// Calling Main Search Form //
						add_filter( 'get_search_form', 'custom_search_form_2' );
						get_search_form();						
						remove_filter( 'get_search_form', 'custom_search_form_2' );		
						?>
					
					<div class="ps19-search-result-controls">
						<div class="ps19-search-total-result">
						<?php
							if($wp_query->found_posts>0)
							{ 
													/// Getting Current Page ///
							$current_page_id = (get_query_var('paged')) ? get_query_var('paged') : 1; 
							echo " ".$wp_query->found_posts." results for <strong>". get_search_query()."</strong> ";
							}
						?>
						</div>
						<div class="ps19-result-per-page">	
						<?php if($wp_query->found_posts>0){ ?>						
						<span>View:</span>	
							
							<a <?php if($_GET['posts_per_page']=="10" OR $_GET['posts_per_page']=="" ){ echo "class=\"active\"";} ?>
											 href="<?php echo '?'.$posts_10;?>">10</a>
							<?php } ?>
							
							<?php if($wp_query->found_posts>10){ ?>
							<a <?php if ($_GET['posts_per_page']=="20"){ echo "class=\"active\"";}  ?>
											 href="<?php echo '?'.$posts_20;?>">20</a>
							<?php } ?>
							
							
							<?php if($wp_query->found_posts>20){ ?>
							<a <?php if ($_GET['posts_per_page']=="30"){ echo "class=\"active\"";}  ?>
											href="<?php echo '?'.$posts_30;?>">30</a>
							<?php } ?>
						</div>
					</div>
					</div>
					
					
					<?php 
					
					add_filter('wp_get_attachment_image_attributes','nectar_remove_lazy_load_functionality');
					
					if(have_posts()) : while(have_posts()) : the_post(); 
							
							$using_post_thumb = has_post_thumbnail( $post->ID );
							
							if( get_post_type($post->ID) == 'post' ){ ?>
								<article class="result" data-post-thumb="<?php echo $using_post_thumb; ?>">
									<div class="inner-wrap">
										<?php if(has_post_thumbnail( $post->ID )) {	
											echo '<a href="'.get_permalink().'">'.get_the_post_thumbnail($post->ID, 'full', array('title' => '')).'</a>'; 
										} ?>
										<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <span><?php echo __('Blog Post', 'salient'); ?></span></h2>
										<?php if($search_results_layout == 'list-no-sidebar') { the_excerpt(); } ?>-----IN POST------
									</div>
								</article><!--/search-result-->	
							<?php }
							
							else if( get_post_type($post->ID) == 'page' ){ ?>
								<article class="result">
									<div class="inner-wrap">
										<h2 class="title">										
										<a href="<?php esc_url( the_permalink() ); ?>">
										<?php relevanssi_the_title();//the_title(); ?></a> 
										<div>
										<a class="ps19-search-result-link" href="<?php esc_url( the_permalink() ); ?>"  title="Permalink to <?php the_title(); ?>" rel="bookmark"><?php the_permalink(); ?></a>
										</div>
										<span><?php //echo __('Page', 'salient'); ?></span></h2>	
										
										<?php if(has_excerpt()) the_excerpt(); ?>
									</div>
								</article><!--/search-result-->	
							<?php }
							
							else if( get_post_type($post->ID) == 'portfolio' ){ ?>
								<article class="result" data-post-thumb="<?php echo $using_post_thumb; ?>">
									<div class="inner-wrap">
										<?php 
										
										$nectar_custom_project_link = get_post_meta($post->ID, '_nectar_external_project_url', true);
										$nectar_portfolio_project_url = (!empty($nectar_custom_project_link)) ? $nectar_custom_project_link : get_permalink();

										if(has_post_thumbnail( $post->ID )) {	
											echo '<a href="'.$nectar_portfolio_project_url.'">'.get_the_post_thumbnail($post->ID, 'full', array('title' => '')).'</a>'; 
										} 
										?>
										<h2 class="title"><a href="<?php echo $nectar_portfolio_project_url; ?>"><?php the_title(); ?></a> <span><?php echo __('Portfolio Item', 'salient'); ?></span></h2>
									</div>
								</article><!--/search-result-->		
							<?php }
							
							else if( get_post_type($post->ID) == 'product' ){ ?>
								<article class="result" data-post-thumb="<?php echo $using_post_thumb; ?>">
									<div class="inner-wrap">
										<?php if(has_post_thumbnail( $post->ID )) {	
											echo '<a href="'.get_permalink().'">'. get_the_post_thumbnail($post->ID, 'full', array('title' => '')).'</a>'; 
										} ?>
										<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <span><?php echo __('Product', 'salient'); ?></span></h2>	
										<?php if($search_results_layout == 'list-no-sidebar') { the_excerpt(); } ?>
									</div>
								</article><!--/search-result-->	
							<?php } else { ?>
								<article class="result" data-post-thumb="<?php echo $using_post_thumb; ?>">
									<div class="inner-wrap">
										<?php if(has_post_thumbnail( $post->ID )) {	
											echo '<a href="'.get_permalink().'">'.get_the_post_thumbnail($post->ID, 'full', array('title' => '')).'</a>'; 
										} ?>
										<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
										<?php if($search_results_layout == 'list-no-sidebar') { the_excerpt(); } ?>
									</div>
								</article><!--/search-result-->	
							<?php } ?>
							
					<?php endwhile; 
							wp_pagenavi(); //calling pagination					
					else: 
						
						echo "<h3>" . __('Sorry, no results were found.', 'salient') . "</h3>"; 
						echo "<p>" . __('Please try again with different keywords.', 'salient') . "</p>"; 
						//get_search_form();
						
				  endif;					
					remove_filter('wp_get_attachment_image_attributes','nectar_remove_lazy_load_functionality');
					
					?>

				</div><!--/search-results-->
				
				<?php if( get_next_posts_link() || get_previous_posts_link() ) { ?>
					<div id="pagination" data-layout="<?php echo $search_results_layout; ?>">
						<div class="prev"><?php previous_posts_link('&laquo; Previous Entries') ?></div>
						<div class="next"><?php next_posts_link('Next Entries &raquo;','') ?></div>
					</div>	
				<?php }?>
				
			</div><!--/span_9-->
			
			<?php if($search_results_layout == 'default') { ?>
				
				<div id="sidebar" class="col span_3 col_last">
					<?php get_sidebar(); ?>
				</div><!--/span_3-->
				
		  <?php } ?>
		
		</div><!--/row-->
		
	</div><!--/container-->

</div><!--/container-wrap-->

<?php get_footer(); ?>
