<?php
//Login Shortcode
function pravel_invoice_login_form(){
	if(is_user_logged_in())
	{
		return;
	}
	
	$data = '<div class="sign_up_bg" id="custom_login">
		<div class="sign_up_main">	
			<div class="sign-title">
					<h3>LOGIN</h3>
				</div>
			<form id="login-form" >
				<div class="login-form">
				
				<p class="error_pravel" style="display:none;"></p>
					<div class="input-box">
						<p>EMAIL<sup style="color:red;">*</sup></p>
						<input type="text" name="email" id="login_email" placeholder="pravel@gmail.com">
						<p class="field_error" style="display:none;"></p>
					</div>
					<div class="input-box">
						<p>PASSWORD<sup style="color:red;">*</sup></p>
						<input type="password" name="password" id="login_password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;">
						<p class="field_error" style="display:none;"></p>
					</div>
					<div class="login-form-btn">
						<button type="button" id="login_user">LOGIN</button>
						'.wp_nonce_field( 'ajax-login-nonce', 'security' ).'
					</div>
				</div>
			</form>
			
		</div>
	</div>';
	return $data;
}

add_shortcode('pravel_invoice_login_form','pravel_invoice_login_form');