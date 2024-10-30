<?php
	
	if (!defined('ABSPATH')) { header('location: /'); die; }
	
	$blog = fetch_feed('https://awfulclever.com/feed/');
	$blog_count = 0; 
    $blog_items = false;
    if (!is_wp_error($blog)) {
    	$blog_count = $blog->get_item_quantity(10); 
    	$blog_items = $blog->get_items(0, $blog_count);
    }
    
    $products = fetch_feed('https://awfulclever.com/feed/?post_type=download');
	$product_count = 0; 
    $product_items = false;
    if (!is_wp_error($products)) {
    	$product_count = $products->get_item_quantity(10); 
    	$product_items = $products->get_items(0, $product_count);
    }
    
    $plugin_dir = plugin_dir_url(__FILE__);
    
    $user_id = get_current_user_id();
    if (isset($_GET['newsletter']) && $_GET['newsletter'] == 'hide') {
    	update_user_meta($user_id, 'awflclvr_newsletter_hide', true);
    } else if (isset($_GET['newsletter']) && $_GET['newsletter'] == 'show') {
		update_user_meta($user_id, 'awflclvr_newsletter_hide', false);
	}
    $newsletter_hide = (get_user_meta($user_id, 'awflclvr_newsletter_hide', true)) ? true : false;
    
?>

<style>
	.awflclvr-logo {
		float: left;
		margin-right: 20px;
		width: 80px;
		height: 80px;
	}
	
	.welcome-panel-content {
  		padding-bottom: 20px;
	}
	
	.welcome-panel-content:after {
  		content: "";
  		display: table;
  		clear: both;
	}
	
	.welcome-panel-column p,
	.welcome-panel-column .container {
		padding-right: 20px;
	}
	
	.awflclvr-metabox-title {
		font-size: 14px;
		padding: 8px 12px;
		margin: 0;
		line-height: 1.4;
		border-bottom: 1px solid #eee;
	}
	
	#newsletter label {
		font-weight: bold;
		display: block;
	}
	
	#newsletter .all-options {
		width: 100%;
	}
	
	.newsletter_inputs {
		padding: 10px 20px 20px;
		text-align: left;
		background: #f8f8f8;
		border: 1px solid #eee;
	}
	
	#newsletter .mc-field-group {
		margin-bottom: 10px;
	}
	
	.newsletter_button {
		padding-top: 5px;
	}
	
	.videoWrapper {
		margin-top: 27px;
		position: relative;
		padding-bottom: 56.25%; /* 16:9 */
		padding-top: 25px;
		height: 0;
	}
	.videoWrapper iframe {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
	}
</style>

<script>
	jQuery(function($) {
		$('#newsletter').submit(function (e) {
			e.preventDefault();
			$.ajax({
				type: 'GET',
				url: 'https://awfulclever.us16.list-manage.com/subscribe/post-json?c=?',
				data: $(this).serialize(),
				dataType: 'json',
				contentType: 'application/json; charset=utf-8',
				success: function (data) {
					if (data.result == 'success') {
						$('#newsletter').slideUp();
						$('#newsletter_response').slideDown();
					} else {
						$('#newsletter_error').html(data.msg.substring(4));
					}
				}
			});
		});
	});
</script>

<div class="wrap">
	
	<div id="welcome-panel" class="welcome-panel">
		<div class="welcome-panel-content">
			
			<img class="awflclvr-logo" src="<?php echo $plugin_dir; ?>images/awful-clever.png">
			
			<h1>Awful Clever Plugins</h1>
			<p class="about-description">Clever Plugins to Increase Conversions</p>
			
			<div class="welcome-panel-column-container">
				
				<div class="welcome-panel-column">
					<div class="container">
						<h3>Make More Money with WordPress</h3>
						<p>	
							From clever minds come clever plugins. And we're Awful Clever. Check out our website to see how Awful Clever Plugins can help you increase conversions.
						</p>
						<a href="<?php echo $this->get_url('', 'dashboard'); ?>" class="button button-primary" target="_blank">Visit Awful Clever</a>
						<a href="<?php echo $this->get_url('contact', 'dashboard'); ?>" class="button" target="_blank">Questions? Need Help?</a>
						<?php if (!$newsletter_hide) : ?>
							<div id="newsletter_response" style="display: none;">
								<h3>You're about to get Awful Clever!</h3>
								<p>
									<strong>To complete your subscription, please click the link in the email we just sent you.</strong>
								</p>
								<a href="<?php echo $this->get_url('dashboard'); ?>&newsletter=hide" class="button">Got it! Now hide this form</a>
							</div>
							<form action="https://awfulclever.us16.list-manage.com/subscribe/post-json?c=?" id="newsletter" name="newsletter">
								<h3>Get the Awful Clever Newsletter</h3>
								<p>	
									Get clever tips, tricks and tutorials you can use to increase conversions!
								</p>
								<div class="newsletter_inputs">
									<div class="mc-field-group">
										<label for="mce-FNAME">First Name </label>
										<input type="text" value="" name="FNAME" class="all-options" id="mce-FNAME" placeholder="Enter your first name">
									</div>
									<div class="mc-field-group ">
										<label for="mce-EMAIL">Email Address</label>
										<input type="text" value="" name="EMAIL" class="all-options" id="mce-EMAIL" placeholder="Enter your email address">
									</div>
									<p class="file-error">
										<strong id="newsletter_error"></strong>
									</p>
									<div class="newsletter_button">
										<input type="submit" name="submit" value="Send me the Newsletter!" class="button button-primary">
									</div>
									<input type="hidden" name="b_2f2811f837f37011eb203ebce_5745b3ec4b" value="">
									<input type="hidden" name="u" value="2f2811f837f37011eb203ebce">
									<input type="hidden" name="id" value="5745b3ec4b">
								</div>
							</form>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="welcome-panel-column">
					<div class="container">
						<div class="videoWrapper">
							<iframe src="https://www.youtube.com/embed/videoseries?list=PLZ_Gue88vtbJ_pOBlQ9BX03s4tYQ7LW5d&amp;showinfo=0?ecver=2" width="100%" height="auto" frameborder="0" style="border:1px solid #eee;" allowfullscreen></iframe>
						</div>
					</div>
				</div>
				
				<div class="welcome-panel-column welcome-panel-last">
					<h3>Tips, Tricks, Tutorials & Resources</h3>
					<ul class="">
						<li><a href="<?php echo $this->get_url('blog', 'dashboard'); ?>" target="_blank">Awful Clever Blog</a></li>
						<?php
							if ($blog_items && $blog_count > 0) {
								foreach ($blog_items as $b) {
									printf('<li><a href="%s?ref=plugin" target="_blank">%s</a></li>',
										esc_url($b->get_permalink()),
										esc_html($b->get_title())
									);
								}
							}
						?>
					</ul>
				</div>
				
			</div>
			
		</div>
	</div>
	
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			<?php 
				if ($product_items && $product_count > 0) {
					$i = 1;
					foreach ($product_items as $p) {
						$image = '';
						$img_data = $p->get_item_tags('', 'image');
						if (isset($img_data[0]['data'])) {
							$image = sprintf('<a href="%s?ref=plugin" target="_blank"><img style="width: 100%%; height: auto;" src="%s" alt="%s"></a>',
													esc_url($p->get_permalink()),
													$img_data[0]['data'],
													esc_html($p->get_title())
												);
						}
						
						printf('<div id="postbox-container-%s" class="postbox-container">
									<div id="normal-sortables" class="meta-box-sortables ui-sortable">
										<div class="postbox">
											<div class="inside">
												%s
												<h2>%s</h2>
												<p style="margin-top: 0";>%s</p>
												<p style="text-align: center;"><a href="%s?ref=plugin" class="button-primary" target="_blank">Show me the Plugin!</a></p>
											</div>
										</div>
									</div>
								</div>',
								$i,
								$image,
								esc_html($p->get_title()),
								$p->get_content(),
								esc_url($p->get_permalink())
							);
						$i++;
					}
				}
			?>
		</div>
	</div>
	
</div>

