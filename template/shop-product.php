<?php 
	//Product Page	
	function pravel_product_page(){
		if(!is_user_logged_in())
		{
			return;
		}
		$user_id = get_current_user_id();
		$user = wp_get_current_user();
		if ( !in_array( 'pravel_shopman', (array) $user->roles ) ) {
			echo '<h2>You need to upgrade your account as a shop man.</h2>';
			return;
		}
		
		$data = '
			<input type="hidden" value="'.$user_id.'" id="pravel_current_userid" />
			<div class="pravel_product_page">
				<button class="pravel_btn add" id="pravel_invoice_add_new_product">ADD NEW PRODUCT</button>
				<div class="table_grid" id="pravel_invoice_product_table_div" >
					<table class="display" id="pravel_invoice_product_table">
						<thead>
							<tr>
								<th>No</th>
								<th>Product Name</th>
								<th>Product Price</th>
								<th>Action</th>						
							</tr>
						</thead>
						<tbody>';
						$args = array(
							'post_type' => 'pravel_shop_product',
							'post_status' => 'publish',
							'posts_per_page' => -1,
							'author'   => $user_id,
						);
						$posts = new WP_Query( $args );
						if ( $posts -> have_posts() ) {
							$no = 1;
							while ( $posts -> have_posts() ) {
								$posts->the_post();
								$id = get_the_ID();								
								$data .= '<tr>
										<td>'.$no.'</td>
										<td>'.get_the_title().'</td>
										<td>'.get_post_meta($id , 'pravel_invoice_product_price', true).'</td>
										<td>
											<button class="pravel_btn add pravel_open_product_edit_popup" data-id="'.$id.'"> EDIT</button>
											<button class="pravel_btn remove pravel_remove_product" data-id="'.$id.'">DELETE</button>
										</td>
									
									</tr>';
									$no++;
							}
						}
								
						$data .= '</tbody>
					</table>
				</div>
				
				<div class="pravel_model_class" id="pravel_add_product_modal">
					<div class="pravel_model_inner">
						<div class="sign_up_bg" >							
							<div class="sign_up_main">			
								<div class="sign-right">
									<div class="sign-title">
										<span class="pravel_invoice_close"></span>
										<h3>ADD NEW PRODUCT</h3>
									</div>
									<div class="sign-box">
									<p class="show_reg_error_message" style="display:none;"></p>
									<div class="loading_screeen" style="display:none;">
										<img src="'. PRAVEL_INVOICE_PLUGIN_URL .'/assets/image/loader.gif" />
									</div>
										<div class="sign-form">
											<div class="sign-input-box">
												<p>Product Name<sup style="color:red;">*</sup></p>
												<input type="text" name="pravel_invoice_product_name" id="pravel_invoice_product_name" placeholder="Product Name">
												<p class="field_error" style="display:none;"></p>
											</div>		
											<div class="sign-input-box">
												<p>Product Price<sup style="color:red;">*</sup></p>
												<input type="number" min="0" name="pravel_invoice_product_price" id="pravel_invoice_product_price" placeholder="Product Price">
												<p class="field_error" style="display:none;"></p>
											</div>	
																	
										</div>
									   <div class="pravel_clearfix"></div>
										<div class="sign-form-bottom">
											<div class="sign-form-btn">
												<button class="pravel_btn add" type="button" id="pravel_invoice_add_product">ADD</button>
											 </div>							
											 <div class="pravel_clearfix"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>';
				
				$args = array(
					'post_type' => 'pravel_shop_product',
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'author'   => $user_id,
				);
				$posts = new WP_Query( $args );
				if ( $posts -> have_posts() ) {
					$no = 1;
					while ( $posts -> have_posts() ) {
						$posts->the_post();
						$id = get_the_ID();		
						$data .= '<div class="pravel_model_class" id="pravel_edit_product_modal_'.$id.'">
							<div class="pravel_model_inner">
								<div class="sign_up_bg" >							
									<div class="sign_up_main">			
										<div class="sign-right">
											<div class="sign-title">
												<span class="pravel_invoice_close"></span>
												<h3>EDIT PRODUCT</h3>
											</div>
											<div class="sign-box">
											<p class="show_reg_error_message" style="display:none;"></p>
											<div class="loading_screeen" style="display:none;">
												<img src="'. PRAVEL_INVOICE_PLUGIN_URL .'/assets/image/loader.gif" />
											</div>
												<div class="sign-form">
													<div class="sign-input-box">
														<p>Product Name<sup style="color:red;">*</sup></p>
														<input type="text" name="pravel_invoice_product_name" class="pravel_invoice_product_name" placeholder="Product Name" value="'.get_the_title().'">
														<p class="field_error" style="display:none;"></p>
													</div>		
													<div class="sign-input-box">
														<p>Product Price<sup style="color:red;">*</sup></p>
														<input type="number" min="0" name="pravel_invoice_product_price" class="pravel_invoice_product_price" placeholder="Product Price" value="'.get_post_meta($id , 'pravel_invoice_product_price', true).'">
														<p class="field_error" style="display:none;"></p>
													</div>	
																			
												</div>
											   <div class="pravel_clearfix"></div>
												<div class="sign-form-bottom">
													<div class="sign-form-btn">
														<button class="pravel_btn add pravel_edit_product" type="button" data-id = "'.$id.'">EDIT</button>
													 </div>							
													 <div class="pravel_clearfix"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>';
						$no++;
						}
					}
				
			$data .= '</div>';
					
		echo $data;
	}
	add_shortcode('pravel_product_page', 'pravel_product_page');