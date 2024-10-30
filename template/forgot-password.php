<?php 
//Forgot Password Shortcode
function pravel_invoice_forgotpassword_form(){
	if(is_user_logged_in())
	{
		return;
	}
	if(isset($_GET['forgot_key'])&& isset($_GET['user'])){
		$reset_user_id = sanitize_text_field( $_GET['user'] );
		$reset_activation_code = sanitize_text_field( $_GET['forgot_key'] );
		$data = '<div class="sign_up_bg" >
			
			<div class="sign_up_main">
				<div class="Pravel_popup_close_btn pravel_sing_close">
			        <a href="javascript:void(0)" class="pravel_close"></a>
	        </div>
				<div class="sign-left">
				</div>
				<div class="sign-right">
					<div class="sign-title">
						<h3>CHANGE PASSWORD</h3>
					</div>
					<div class="sign-box">
					<p class="show_reg_error_message" style="display:none;">Kindly fill required fields</p>
					<div class="loading_screeen" style="display:none;">
						<img src="'. PRAVEL_INVOICE_PLUGIN_URL .'/assets/image/loader.gif" />
					</div>
					<div class="sign-form sign-step01" >							
							<div class="">
								<p>NEW PASSWORD</p>
								<input type="password" name="new_password_custom" id="new_password_custom" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;">
							</div>
							<div class="">
								<p>CONFIRM PASSWORD</p>
								<input type="password" name="confirm_password_custom" id="confirm_password_custom" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;">
							</div>
							<input type="hidden" id="reset_user_id" value="'.$reset_user_id.'" />
							<input type="hidden" id="reset_activation_code" value="'.$reset_activation_code.'" />
							<div class="clearfix"></div>
						</div>
					   <div class="clearfix"></div>
						<div class="sign-form-bottom">
							<div class="sign-form-btn">
								<button class="blue-btn" type="button" id="change_password_new">SUBMIT</button>
							 </div>							 
							 <div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
		</div>	';
	}
	else{
	$data = '
	<div class="sign_up_bg" >			
		<div class="sign_up_main">				
			
			<div class="sign-right">
				<div class="sign-title">
					<h3>FORGOT PASSWORD</h3>
				</div>
				<div class="sign-box">
				<p class="show_reg_error_message" style="display:none;">Kindly fill required fields</p>
				<div class="loading_screeen" style="display:none;">
					<img src="'. PRAVEL_INVOICE_PLUGIN_URL .'/assets/image/loader.gif" />
				</div>
				<div class="sign-form sign-step01" >
						
						<div class="">
							<p>EMAIL ADDRESS</p>
							<input type="email" name="user_email" id="user_email_fpassword" placeholder="johnsmith@gmail.com">
						</div>
						<div class="clearfix"></div>
					</div>
				   <div class="clearfix"></div>
					<div class="sign-form-bottom">
						<div class="sign-form-btn">
							<button class="blue-btn" type="button" id="forgot_password_submit">SUBMIT</button>
						 </div>							 
						 <div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
	</div> ';
	
	}
	return $data;
}
add_shortcode('pravel_invoice_forgotpassword_form','pravel_invoice_forgotpassword_form');