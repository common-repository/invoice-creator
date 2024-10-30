<?php
//Login Shortcode
function pravel_invoice_edit_account_form(){
	if(!is_user_logged_in())
	{
		return;
	}
	$user_id = get_current_user_id();
	$user = wp_get_current_user();
	if ( !in_array( 'pravel_shopman', (array) $user->roles ) ) {		
		return;
	}
	$company_logo_url = get_user_meta($user_id, 'company_logo', true);
	$data = '<div class="sign_up_bg">
		<input type="hidden" value="'.$user_id.'" id="pravel_current_userid" />
		
		<div class="tab">';
		
		$tabname = "'pravel_account_tab'";
		$tabname2 = "'pravel_business_tab'";
		$data .= ' <button class="tablinks active" onclick="pravel_open_tab(event, '.$tabname.')">Edit Account Info</button>
		  <button class="tablinks" onclick="pravel_open_tab(event, '.$tabname2.')">Edit Business Info</button>
		</div>
		<div class="sign_up_main tabcontent" id="pravel_account_tab" style="display:block;box-shadow:none;">	
			
			<div class="sign-box">
				<p class="show_reg_error_message" style="display:none;">Kindly fill required fields</p>
				<div class="loading_screeen" style="display:none;">
					<img src="'. PRAVEL_INVOICE_PLUGIN_URL .'/assets/image/loader.gif" />
				</div>
				<div class="sign-form">
					<div class="sign-input-box half">
						<p>FIRST NAME<sup style="color:red;">*</sup></p>
						<input type="text" name="first_name" id="user_first_name" placeholder="First Name" value="'.get_user_meta($user_id, 'first_name', true).'">
						<p class="field_error" style="display:none;"></p>
					</div>
					<div class="sign-input-box half">
						<p>LAST NAME<sup style="color:red;">*</sup></p>
						<input type="text" name="user_last_name" id="user_last_name" placeholder="Last Name" value="'.get_user_meta($user_id, 'last_name', true).'">
						<p class="field_error" style="display:none;"></p>
					</div>
					<div class="pravel_clearfix"></div>
					<div class="sign-input-box half">
						<p>EMAIL ADDRESS<sup style="color:red;">*</sup></p>
						<input type="text" name="user_email" id="user_email" placeholder="Email Address" value="'.$user->user_email.'">
						<p class="field_error" style="display:none;"></p>
					</div>					
					<div class="sign-input-box half">
						<p>Mobile Number<sup style="color:red;">*</sup></p>
						<input type="text" id="mobile_number"  name="mobile_number" placeholder="Mobile Number" value="'.get_user_meta($user_id, 'customer_mobile', true).'">
						<p class="field_error" style="display:none;"></p>
					</div>											
				</div>
				<div class="pravel_clearfix"></div>
				<div class="sign-form-bottom">
					<div class="sign-form-btn">
						<button class="blue-btn" type="button" id="edit_account_data">SUBMIT</button>
					 </div>				
				</div>
			</div>			
		</div>
		<div class="sign_up_main tabcontent" id="pravel_business_tab" style="box-shadow:none;">	
			<div class="sign-box">
				<p class="show_reg_error_message" style="display:none;">Kindly fill required fields</p>
				<p class="intruct_com">Kindly fill up all these information to make a good impression on our clients. It will appear on the invoice. </p>
				
				<div class="loading_screeen" style="display:none;">
					<img src="'. PRAVEL_INVOICE_PLUGIN_URL .'/assets/image/loader.gif" />
				</div>
				<div class="sign-form">
					<div class="sign-input-box half">
						<p>COMPANY NAME</p>
						<input type="text" name="company_name" id="company_name" placeholder="Company Name" value="'.get_user_meta($user_id, 'company_name', true).'">
						<p class="field_error" style="display:none;"></p>
					</div>
					<div class="sign-input-box half">
						<p>Company Logo</p>
						<input type="file" name="company_logo" id="company_logo" placeholder="Company Logo" value="'.get_user_meta($user_id, 'company_logo', true).'" accept="image/png, image/jpeg">
						<p class="field_error" style="display:none;"></p>';
						if($company_logo_url != ''){
							$data .= '<img src="'.$company_logo_url.'" style="height:20px;margin-top:10px;" />';
						}
						
					$data .= '</div>
					<div class="pravel_clearfix"></div>
					<div class="sign-input-box half">
						<p>Company Address Line 1</p>
						<input type="text" name="company_address_1" id="company_address_1" placeholder="Address Line 1" value="'.get_user_meta($user_id, 'company_address_1', true).'">
						<p class="field_error" style="display:none;"></p>
					</div>				
					<div class="sign-input-box half">
						<p>Company Address Line 2</p>
						<input type="text" name="company_address_2" id="company_address_2" placeholder="Address Line 2" value="'.get_user_meta($user_id, 'company_address_2', true).'">
						<p class="field_error" style="display:none;"></p>
					</div>
					<div class="pravel_clearfix"></div>
					<div class="sign-input-box half">
						<p>Company City</p>
						<input type="text" name="company_city" id="company_city" placeholder="Company City" value="'.get_user_meta($user_id, 'company_city', true).'">
						<p class="field_error" style="display:none;"></p>
					</div>				
					<div class="sign-input-box half">
						<p>Company Pincode</p>
						<input type="text" name="company_pincode" id="company_pincode" placeholder="Company Pincode" value="'.get_user_meta($user_id, 'company_pincode', true).'">
						<p class="field_error" style="display:none;"></p>
					</div>		
				</div>
				<div class="pravel_clearfix"></div>
				<div class="sign-form-bottom">
					<div class="sign-form-btn">
						<button class="blue-btn" type="button" id="edit_business_data">SUBMIT</button>
					 </div>				
				</div>
			</div>						
		</div>
		
	</div>';
	return $data;
}

add_shortcode('pravel_invoice_edit_account_form','pravel_invoice_edit_account_form');