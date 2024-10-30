<?php
	
	//Invoice Form
	
	function pravel_invoice_form(){
		if(!is_user_logged_in())
		{
			return;
		}
		$user_id = get_current_user_id();
		$user = wp_get_current_user();
		$company_mobile = get_user_meta($user_id, 'customer_mobile', true);
		$company_name = get_user_meta($user_id, 'company_name', true);
		$company_address_1 = get_user_meta($user_id, 'company_address_1', true);
		$company_address_2 = get_user_meta($user_id, 'company_address_2', true);
		$company_city = get_user_meta($user_id, 'company_city', true);
		$company_pincode = get_user_meta($user_id, 'company_pincode', true);
		$company_logo_url = get_user_meta($user_id, 'company_logo', true);		
		if ( !in_array( 'pravel_shopman', (array) $user->roles ) ) {
			echo '<h2>You need to upgrade your account as a shop man.</h2>';
			return;
		}
		
		$data = '
			<input type="hidden" value="'.$user_id.'" id="pravel_current_userid" />
			<div class="pravel_product_page">
			<button class="pravel_btn add" id="pravel_create_new">CREATE NEW INVOICE</button>
			<div class="table_grid" id="pravel_invoice_data_div" >
				<table class="display" id="pravel_invoice_data">
					<thead>
						<tr>
							<th>No</th>
							<th>Invoice Number</th>
							<th>Customer Name</th>
							<th>Invoice Total</th>	
							<th>Action</th>							
						</tr>
					</thead>
					<tbody>';
					$args = array(
						'post_type' => 'pravel_invoice',
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
							$email = get_post_meta($id , 'pravel_invoice_customer_email', true);
							$mobile = get_post_meta($id , 'pravel_invoice_customer_mobile_number', true);
							$data .= '<tr>
									<td>'.$no.'</td>
									<td>'.get_the_title().'</td>
									<td>'.get_post_meta($id , 'pravel_invoice_customer_first_name', true).' '.get_post_meta($id , 'pravel_invoice_customer_last_name', true).'</td>
									<td>'.get_post_meta($id , 'pravel_invoice_total', true).'</td>
									<td>
										<button class="pravel_btn add pravel_open_invoice_popup" data-id="'.$id.'"> VIEW</button>										
										<button class="pravel_btn add pravel_mail_invoice" data-id="'.$id.'" data-email="'.$email.'">SEND MAIL</button>
										<button class="pravel_btn remove pravel_remove_invoice" data-id="'.$id.'">DELETE</button>
										<div class="pravel_email_section" id="pravel_email_section_'.$id.'" style="display:none;">
											<input type="email" id="pravel_send_email_invoice_'.$id.'" class="pravel_send_email_invoice" placeholder="Enter email" />
											<button type="button" class="pravel_send_invoice pravel_btn add" data-id="'.$id.'">SEND</button>
										</div>
									</td>
								
								</tr>';
								$no++;
						}
					}
							
					$data .= '</tbody>
				</table>
			</div>
			
			<div class="pravel_model_class" id="pravel_create_invoice_modal">
				<div class="pravel_model_inner">
					<div class="sign_up_bg invoice_form">	
						<div class="sign_up_main">			
							<div class="sign-right">
								<div class="sign-title">
									<span class="pravel_invoice_close"></span>
									<h3>CREATE NEW INVOICE</h3>
								</div>
								<div class="sign-box">
								<p class="show_reg_error_message" style="display:none;">Kindly fill required fields</p>
								<div class="loading_screeen" style="display:none;">
									<img src="'. PRAVEL_INVOICE_PLUGIN_URL .'/assets/image/loader.gif" />
								</div>
									<div class="sign-form">
										<div class="sign-input-box half">
											<p>Customer First Name<sup style="color:red;">*</sup></p>
											<input type="text" name="customer_first_name" id="customer_first_name" placeholder="Customer First Name">
											<p class="field_error" style="display:none;"></p>
										</div>		
										<div class="sign-input-box half">
											<p>Customer Last Name<sup style="color:red;">*</sup></p>
											<input type="text" name="customer_last_name" id="customer_last_name" placeholder="Customer Last Name">
											<p class="field_error" style="display:none;"></p>
										</div>	
										<div class="pravel_clearfix"></div>
										<div class="sign-input-box half">
											<p>Customer Email</p>
											<input type="text" name="customer_email" id="customer_email" placeholder="Customer Email">
											<p class="field_error" style="display:none;"></p>
										</div>	
										<div class="sign-input-box half">
											<p>Customer Mobile Number<sup style="color:red;">*</sup></p>
											<input type="number" min="0" name="customer_mobile_number" id="customer_mobile_number" placeholder="Customer Mobile Number">
											<p class="field_error" style="display:none;"></p>
										</div>								
										<div class="pravel_clearfix"></div>
										<p class="invoice_error_message" style="display:none;">Add atleast one valid item in invoice</p>
										';
										$args = array(
												'post_type' => 'pravel_shop_product',
												'post_status' => 'publish',
												'posts_per_page' => -1,
												'author'   => $user_id,
											);
											$posts = new WP_Query( $args );
											if ( $posts -> have_posts() ) {	
										$data .= '<div class="repeater_field" id="repeater_item_from_list">
											<p style="margin-top:20px;margin-bottom:0px;font-weight:bold;">Select Items From your List</p>
											<button class="pravel_btn add repeater-add-btn" type="button">ADD ITEM</button>
											<div class="items" data-group="test">									
												<div class="item-content">	
													<div class="sign-input-box col_three_pravel">												
														<select class="" data-name="product_select_from_list">
															<option value="0">Select Product</option>';	
																while ( $posts -> have_posts() ) {
																	$posts->the_post();
																	$id = get_the_ID();		
																	$data .= '<option value="'.get_post_meta($id , 'pravel_invoice_product_price', true).'">'.get_the_title().'</option>';
																}
															
													$data .= '</select>
														<p class="field_error" style="display:none;"></p>
													</div>
													<div class="sign-input-box col_three_pravel">	
														<input type="number" min="0" class="" placeholder="Total Qty" data-name="qty_by_customer">
														<p class="field_error" style="display:none;"></p>
													</div>
													<div class="sign-input-box col_three_pravel">	
														<input type="text" class="" value="0.00" data-name="product_total_price" disabled>
														<p class="field_error" style="display:none;"></p>
													</div>
												</div>	
												<div class="pravel_clearfix"></div>
												<button class="pravel_btn remove remove-btn" type="button">REMOVE ITEM</button>
											</div>						
										</div>';	
										}
										$data .= '<div class="pravel_clearfix"></div>
										<div class="repeater_field" id="repeater_add_custom_item">
											<p style="margin-top:20px;margin-bottom:0px;font-weight:bold;">Add Items in Invoice</p>
											<button class="pravel_btn add repeater-add-btn" type="button">ADD ITEM</button>
											<div class="items" data-group="test">									
												<div class="item-content">
													<div class="sign-input-box col_four_pravel">
														<input type="text" class="" placeholder="Product Name" data-name="product_name">
														<p class="field_error" style="display:none;"></p>
													</div>
													<div class="sign-input-box col_four_pravel">
														<input type="number" min="0" class="" placeholder="Product Price" 	data-name="product_price">
														<p class="field_error" style="display:none;"></p>
													</div>
													<div class="sign-input-box col_four_pravel">
														<input type="number" min="0" class="" placeholder="Total Qty" data-name="qty_by_customer_new">
														<p class="field_error" style="display:none;"></p>
													</div>
													<div class="sign-input-box col_four_pravel">
														<input type="text" class="" value="0.00" data-name="product_total_price_new" disabled>			
													</div>
													<div class="pravel_clearfix"></div>
												</div>	
												<button class="pravel_btn remove remove-btn" type="button">REMOVE ITEM</button>
											</div>						
										</div>						
									</div>
									<div class="invoive_total_section">
										<p class="pravel_total_p">TOTAL<span id="pravel_final_caluculation">0.00</span></p>
									</div>
								   <div class="pravel_clearfix"></div>
									<div class="sign-form-bottom">
										<div class="sign-form-btn">
											<button class="blue-btn" type="button" id="pravel_create_invoice">SUBMIT</button>
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
				'post_type' => 'pravel_invoice',
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
					$company_logo_url = get_user_meta($user_id, 'company_logo', true);
			$data .='<div class="pravel_model_class" id="pravel_view_invoice_'.$id.'">
							<div class="pravel_model_inner">
								<div class="sign_up_bg invoice-box">	
									<div class="sign_up_main">			
										<div class="sign-right">
											<div class="sign-title">
												<span class="pravel_invoice_close"></span>
												<h3>INVOICE</h3>
											</div>
											<div class="invoice-top-pravel">
												
											</div>
											<div class="sign-box" id="print_section_'.$id.'">
												<p class="show_reg_error_message" style="display:none;">Kindly fill required fields</p>	
												<table>
													<tr>
														<td>';	
															if($company_logo_url != ''){												
																$data .='<img src="'.$company_logo_url.'" class="pravel_brand_logo_img" />';
															}
															
														$data .='</td>
														
														<td style="float:right;">
															<b>Invoice No : '.get_the_title().'</b><br>
															Created: '.get_the_date().'												
														</td>
													</tr>
												</table>
												<table style="margin-bottom: 10px !important;">	
													<td style="border-top:2px solid #f2f2f2;"></td>
												</table>
												<table>
													<tr>
														<td>
															<b>Bill To </b></br>
															'.get_post_meta($id , 'pravel_invoice_customer_first_name', true).' '.get_post_meta($id , 'pravel_invoice_customer_last_name', true).'<br>
															Email : '.get_post_meta($id , 'pravel_invoice_customer_email', true).'<br>
															Phone No. : '.get_post_meta($id , 'pravel_invoice_customer_mobile_number', true).'<br>
														</td>
														
														<td style="float:right;">';
														if($company_name != '' || $company_address_1 != '' || $company_address_2 != '' || $company_city != '' || $company_pincode != ''){
															$data .= '<b>Bill From </b></br>
															'.$company_name.'<br/>
															'.$company_address_1.'<br/>
															'.$company_address_2.'<br/>
															'.$company_city.' '.$company_pincode.'';
														}
														$data .= '</td>
													</tr>
												</table>
												<table >	
													<td style="border-top:2px solid #f2f2f2;"></td>
												</table>
												<table cellpadding="0" cellspacing="0">
												<tr class="top">
													<td colspan="2">
														
													</td>
												</tr>
												<tr class="heading">
													<td>No</td>
													<td>Product Name</td>
													<td>Product Price</td>
													<td>Quantity</td>
													<td>Total</td>
												</tr>';
												$item_data = get_post_meta($id , 'pravel_invoice_items', true);		
												$no = 1;
												foreach($item_data as $val){
													
													$data .='<tr class="item">
														<td>'.$no.'</td>
														<td>'.$val['selected_item_name'].'</td>
														<td>'.$val['selected_item_price'].'</td>
														<td>'.$val['pravel_qty'].'</td>
														<td>'.$val['total_row_price'].'</td>
													</tr>';
													$no++;
												}
												
												
												
											$data .='<tr class="total">
													<td></td>
													<td></td>									
													<td></td>									
													<td></td>									
													<td>
													   Total: '.get_post_meta($id , 'pravel_invoice_total', true).'
													</td>
												</tr>
											</table>
											<table style="margin-bottom: 10px !important;">	
												<td style="border-top:2px solid #f2f2f2;"></td>
											</table>
											<table>	
												<tr>
													<td style="text-align:center;">';
													if($company_mobile != ''){
														$data .='If you have any questions concerning this invoice, please contact <br/>
													Mobile Number : '.$company_mobile.'<br/>';
													}
													$data .='<b>THANK YOU FOR YOUR BUSINESS</b></td>
												</tr>
											</table>
											<button class="pravel_print_click add pravel_btn" data-id="'.$id.'">PRINT INVOICE</button>
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
	add_shortcode('pravel_invoice_form', 'pravel_invoice_form');
	