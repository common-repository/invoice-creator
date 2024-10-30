<?php

//SignUp Shortcode
function pravel_invoice_signup_form(){ 
	if(is_user_logged_in())
	{
		return;
	}
	
	$data = '
	<div class="sign_up_bg" >			
		<div class="sign_up_main">			
			<div class="sign-right">
				<div class="sign-title">
					<h3>CREATE A FREE ACCOUNT</h3>
				</div>
				<div class="sign-box">
				<p class="show_reg_error_message" style="display:none;">Kindly fill required fields</p>
				<div class="loading_screeen" style="display:none;">
					<img src="'. PRAVEL_INVOICE_PLUGIN_URL .'/assets/image/loader.gif" />
				</div>
					<div class="sign-form sign-step01 form_on" style="display:none"  form_on = "form_1">
						<div class="sign-input-box half">
							<p>FIRST NAME<sup style="color:red;">*</sup></p>
							<input type="text" name="first_name" id="user_first_name" placeholder="First Name">
							<p class="field_error" style="display:none;"></p>
						</div>
						<div class="sign-input-box half">
							<p>LAST NAME<sup style="color:red;">*</sup></p>
							<input type="text" name="user_last_name" id="user_last_name" placeholder="Last Name">
							<p class="field_error" style="display:none;"></p>
						</div>
						<div class="pravel_clearfix"></div>
						<div class="sign-input-box half">
							<p>EMAIL ADDRESS<sup style="color:red;">*</sup></p>
							<input type="text" name="user_email" id="user_email" placeholder="Email Address">
							<p class="field_error" style="display:none;"></p>
						</div>
						
						<div class="sign-input-box half">
							<p>USERNAME<sup style="color:red;">*</sup></p>
							<input type="text" id="user_name"  name="user_name" placeholder="User Name">
							<p class="field_error" style="display:none;"></p>
						</div>
						<div class="pravel_clearfix"></div>
						<div class="sign-input-box">
							<p>WHO ARE YOU?<sup style="color:red;">*</sup></p>
							<select id="pravel_select_role" name="pravel_select_role" >
								<option value="0">WHO ARE YOU?</option>
								<option value="pravel_shopman">Shopman</option>
								<option value="pravel_customer">Customer</option>
							</select>
							<p class="field_error" style="display:none;"></p>
						</div>
						<div class="sign-input-box">
							<p>PASSWORD<sup style="color:red;">*</sup></p>
							<input type="password" name="user_password"id="user_password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;">
							<p class="field_error" style="display:none;"></p>
						</div>
						<div class="sign-input-box">
							<p>RETYPE PASSWORD<sup style="color:red;">*</sup></p>
							<input type="password" id="user_c_password" name="user_c_password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;">
							<p class="field_error" style="display:none;"></p>
						</div>
						<div class="pravel_clearfix"></div>
					</div>
			   
					<div class="sign-form sign-step02 " style="display:none" form_on = "form_2">
						<div class="sign-input-box verification_code">
								<p>ENTER VERIFICATION CODE</p>
								<p class="thankyou_text_verification">Thank you – To establish your account and confirm your contact information, we have emailed you a verification code to the address you provided. Please enter the code below to complete your account setup</p> 
								<input type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}"  id="inp_1" />
								<input type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" id="inp_2" />
								<input type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" id="inp_3" />
								<input type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" id="inp_4" />							
								<p class="thankyou_text_verification"></p>
							</div>
					</div>
				   
				 
				   <div class="pravel_clearfix"></div>
					<div class="sign-form-bottom">
						<div class="sign-form-btn">
							<button class="blue-btn" type="button" id="invoice_next_page_show">SUBMIT</button>
						 </div>							
						 <div class="pravel_clearfix"></div>
					</div>
				</div>
			</div>
		</div>
	</div>';
	
	return $data;
}

add_shortcode('pravel_invoice_signup_form','pravel_invoice_signup_form');